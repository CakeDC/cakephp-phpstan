<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\AssociationCollection;
use CakeDC\PHPStan\Rule\Traits\ParseClassNameFromArgTrait;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;

class AddAssociationMatchOptionsTypesRule implements Rule
{
    use ParseClassNameFromArgTrait;

    /**
     * @var \PHPStan\Rules\RuleLevelHelper
     */
    private RuleLevelHelper $ruleLevelHelper;

    /**
     * @param \PHPStan\Rules\RuleLevelHelper $ruleLevelHelper
     */
    public function __construct(RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }

    /**
     * @var array<string, string>
     */
    protected array $tableSourceMethods = [
        'belongsTo' => BelongsTo::class,
        'belongsToMany' => BelongsToMany::class,
        'hasMany' => HasMany::class,
        'hasOne' => HasOne::class,
    ];

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return array<\PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof MethodCall);
        $args = $node->getArgs();
        if (!$node->name instanceof Node\Identifier) {
            return [];
        }
        $reference = $scope->getType($node->var)->getReferencedClasses()[0] ?? null;
        if ($reference === null) {
            return [];
        }
        $details = $this->getDetails($reference, $node->name->name, $args);
        if ($details === null || $details['type'] === null) {
            return [];
        }
        if (
            $details['options'] === null
            || !$details['options']->value instanceof Node\Expr\Array_
        ) {
            return [];
        }
        $properties = $this->getPropertiesTypeCheck($details['type']);
        $errors = [];
        foreach ($details['options']->value->items as $item) {
            if (
                !$item instanceof ArrayItem
                || !$item->key instanceof String_
            ) {
                continue;
            }
            if (isset($properties[$item->key->value])) {
                $error = $this->processPropertyTypeCheck(
                    $details,
                    $properties[$item->key->value],
                    $item,
                    $scope
                );
                if ($error) {
                    $errors[] = $error;
                }
            } else {
                $errors[] = RuleErrorBuilder::message(sprintf(
                    'Call to %s::%s with unknown option "%s".',
                    $reference,
                    $node->name->name,
                    $item->key->value
                ))
                    ->identifier('cake.addAssociationWithValidOption.unknownOption')
                    ->build();
            }
        }

        return $errors;
    }

    /**
     * @param string $reference
     * @param string $methodName
     * @param array<\PhpParser\Node\Arg> $args
     * @return array{'alias': ?string, 'options': ?\PhpParser\Node\Arg, 'type': ?string, 'reference':string, 'methodName':string}|null
     */
    protected function getDetails(string $reference, string $methodName, array $args): ?array
    {
        if (str_ends_with($reference, 'Table')) {
            return [
                'alias' => isset($args[0]) ? $this->parseClassNameFromExprTrait($args[0]->value) : null,
                'options' => $args[1] ?? null,
                'type' => $this->tableSourceMethods[$methodName] ?? null,
                'reference' => $reference,
                'methodName' => $methodName,
            ];
        }
        if (
            $reference === AssociationCollection::class
            && $methodName === 'load'
            && isset($args[0])
            && $args[0] instanceof Arg
        ) {
            return [
                'alias' => isset($args[1]) ? $this->parseClassNameFromExprTrait($args[1]->value) : null,
                'options' => $args[2] ?? null,
                'type' => $this->parseClassNameFromExprTrait($args[0]->value),
                'reference' => $reference,
                'methodName' => $methodName,
            ];
        }

        return null;
    }

    /**
     * @param array{'alias': ?string, 'options': ?\PhpParser\Node\Arg, 'type': string, 'reference':string, 'methodName':string} $details
     * @param string $property
     * @param \PhpParser\Node\Expr\ArrayItem $item
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Rules\RuleError|null
     * @throws \PHPStan\Reflection\MissingPropertyFromReflectionException
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function processPropertyTypeCheck(
        array $details,
        string $property,
        ArrayItem $item,
        Scope $scope
    ): ?RuleError {
        $object = new ObjectType($details['type']);
        $classReflection = $object->getClassReflection();
        assert($classReflection instanceof ClassReflection);
        $propertyType = $classReflection
            ->getProperty('_' . $property, $scope)
            ->getWritableType();
        $assignedValueType = $scope->getType($item->value);
        $accepts = $this->ruleLevelHelper->acceptsWithReason($propertyType, $assignedValueType, true);//@phpstan-ignore-line
        if ($accepts->result) {
            return null;
        }
        assert($item->key instanceof String_);
        $propertyDescription = sprintf(
            'Call to %s::%s with option "%s"',
            $details['reference'],
            $details['methodName'],
            $item->key->value
        );
        $verbosityLevel = VerbosityLevel::getRecommendedLevelByType($propertyType, $assignedValueType);

        return RuleErrorBuilder::message(
            sprintf(
                '%s (%s) does not accept %s.',
                $propertyDescription,
                $propertyType->describe($verbosityLevel),
                $assignedValueType->describe($verbosityLevel)
            )
        )
            ->acceptsReasonsTip($accepts->reasons)
            ->identifier('cake.addAssociationWithValidOption.invalidType')
            ->build();
    }

    /**
     * @param string $type
     * @return array<string, string>
     */
    protected function getPropertiesTypeCheck(string $type): array
    {
        $properties = [
            'cascadeCallbacks',
            'className',
            'conditions',
            'dependent',
            'finder',
            'bindingKey',
            'foreignKey',
            'joinType',
            'tableLocator',
            'propertyName',
            'sourceTable',
            'targetTable',
            'strategy',
        ];
        $properties = array_combine($properties, $properties);
        if ($type === BelongsToMany::class) {
            $properties['targetForeignKey'] = 'targetForeignKey';
            $properties['through'] = 'through';
            $properties['joinTable'] = 'junctionTableName';
        }
        if ($type === HasMany::class || $type === BelongsToMany::class) {
            $properties['saveStrategy'] = 'saveStrategy';
            $properties['sort'] = 'sort';
        }

        return $properties;
    }
}
