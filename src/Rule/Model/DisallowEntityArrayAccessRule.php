<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Rule\Model;

use Cake\Datasource\EntityInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

class DisallowEntityArrayAccessRule implements Rule
{
    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return ArrayDimFetch::class;
    }

    /**
     * @param \PhpParser\Node $node
     * @param \PHPStan\Analyser\Scope $scope
     * @return array<\PHPStan\Rules\RuleError>
     * @throws \PHPStan\ShouldNotHappenException
     * @throws \PHPStan\Reflection\MissingMethodFromReflectionException
     */
    public function processNode(Node $node, Scope $scope): array
    {
        assert($node instanceof ArrayDimFetch);
        $type = $scope->getType($node->var);
        if (!$type instanceof ObjectType || !is_a($type->getClassName(), EntityInterface::class, true)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Array access to entity to %s is not allowed, access as object instead',
                $type->getClassName(),
            ))
            ->identifier('cake.entity.arrayAccess')
            ->build(),
        ];
    }
}
