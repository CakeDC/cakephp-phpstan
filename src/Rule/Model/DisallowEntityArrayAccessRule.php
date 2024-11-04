<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\Datasource\EntityInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class DisallowEntityArrayAccessRule implements Rule
{
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
