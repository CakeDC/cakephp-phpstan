<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\Datasource\EntityInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class DisallowEntityArrayAccessRule implements Rule
{
    /**
     * @var list<string>
     */
    protected array $allowedKeys = [
        '_matchingData',
        '_joinData',
        '_ids',
    ];

    /**
     * @inheritDoc
     */
    public function getNodeType(): string
    {
        return ArrayDimFetch::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof ArrayDimFetch);
        $type = $scope->getType($node->var);
        if (!$type->isObject()->yes()) {
            return [];
        }
        $reflection = $type->getObjectClassReflections()[0] ?? null;
        if ($reflection === null || !$reflection->is(EntityInterface::class)) {
            return [];
        }

        if ($node->dim instanceof String_ && in_array($node->dim->value, $this->allowedKeys, true)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Array access to entity to %s is not allowed, access as object instead',
                $reflection->getName(),
            ))
            ->identifier('cake.entity.arrayAccess')
            ->build(),
        ];
    }
}
