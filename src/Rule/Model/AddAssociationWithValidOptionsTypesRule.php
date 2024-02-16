<?php

namespace CakeDC\PHPStan\Rule\Model;

use Cake\ORM\Association\BelongsTo;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Type\ObjectType;
use PHPStan\Type\VerbosityLevel;

class AddAssociationWithValidOptionsTypesRule implements Rule
{
    /**
     * @var RuleLevelHelper
     */
    private $ruleLevelHelper;

    public function __construct(RuleLevelHelper $ruleLevelHelper)
    {
        $this->ruleLevelHelper = $ruleLevelHelper;
    }
    protected array $targetMethods = ['belongsTo'];

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
        if (!in_array($node->name->name, $this->targetMethods) || !isset($args[0]) || !$args[0] instanceof Arg) {
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
        $maps = [
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
        ];
        $items = [];
        foreach ($args[1]->value->items as $item) {
            if (
                !$item instanceof Node\Expr\ArrayItem
                || !$item->key instanceof String_
            ) {
                continue;
            }
            if (in_array($item->key->value, $maps)) {
                $object = new ObjectType(BelongsTo::class);
                $propertyType = $object->getClassReflection()
                    ->getProperty('_' . $item->key->value, $scope)
                    ->getWritableType();
                $assignedValueType = $scope->getType($item->value);
                $accepts = $this->ruleLevelHelper->acceptsWithReason($propertyType, $assignedValueType, true);
                if (!$accepts->result) {
                    $propertyDescription = sprintf(
                        'Call to %s::%s with option "%s"',
                        $reference,
                        $node->name->name,
                        $item->key->value
                    );
                    $verbosityLevel = VerbosityLevel::getRecommendedLevelByType($propertyType, $assignedValueType);
                    $items[] = RuleErrorBuilder::message(sprintf('%s (%s) does not accept %s.', $propertyDescription, $propertyType->describe($verbosityLevel), $assignedValueType->describe($verbosityLevel)))->acceptsReasonsTip($accepts->reasons)->build();
                }
            }
        }
        return $items;
    }
}
