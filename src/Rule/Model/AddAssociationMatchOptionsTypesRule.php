<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\Association\BelongsTo;
use Cake\ORM\Association\BelongsToMany;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use PhpParser\Node;
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
    protected array $targetMethods = [
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
        if (!isset($this->targetMethods[$node->name->name])) {
            return [];
        }
        $reference = $scope->getType($node->var)->getReferencedClasses()[0] ?? null;
        if ($reference === null || !str_ends_with($reference, 'Table')) {
            return [];
        }

        if (
            !isset($args[1])
            || !$args[1]->value instanceof Node\Expr\Array_
        ) {
            return [];
        }
        $properties = $this->getPropertiesTypeCheck($node->name->name);
        $errors = [];
        foreach ($args[1]->value->items as $item) {
            if (
                !$item instanceof ArrayItem
                || !$item->key instanceof String_
            ) {
                continue;
            }
            if (isset($properties[$item->key->value])) {
                $error = $this->processPropertyTypeCheck(
                    $properties[$item->key->value],
                    $node,
                    $item,
                    $scope,
                    $reference
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
     * @param string $property
     * @param \PhpParser\Node\Expr\MethodCall $node
     * @param \PhpParser\Node\Expr\ArrayItem $item
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $reference
     * @return \PHPStan\Rules\RuleError|null
     * @throws \PHPStan\Reflection\MissingPropertyFromReflectionException
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function processPropertyTypeCheck(
        string $property,
        MethodCall $node,
        ArrayItem $item,
        Scope $scope,
        string $reference
    ): ?RuleError {
        assert($node->name instanceof Node\Identifier);
        $object = new ObjectType($this->targetMethods[$node->name->name]);
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
            $reference,
            $node->name->name,
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
     * @param string $methodName
     * @return array<string, string>
     */
    protected function getPropertiesTypeCheck(string $methodName): array
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
        if ($methodName === 'belongsToMany') {
            $properties['targetForeignKey'] = 'targetForeignKey';
            $properties['through'] = 'through';
            $properties['junction'] = 'junctionTableName';
        }
        if ($methodName === 'hasMany' || $methodName === 'belongsToMany') {
            $properties['saveStrategy'] = 'saveStrategy';
            $properties['sort'] = 'sort';
        }

        return $properties;
    }
}
