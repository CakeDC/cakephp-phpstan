<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Rule\Model;

use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

class AddAssociationRule implements Rule
{
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
        $nameArg = $args[0];
        if (!$nameArg->value instanceof String_) {
            return [];
        }
        $className = $this->getInputClassName($nameArg->value, $args);
        if (CakeNameRegistry::getTableClassName($className)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(sprintf(
                'Call to %s::%s could not find the model class for "%s"',
                $reference,
                $node->name->name,
                $className,
            ))
                ->identifier('cake.addAssociation')
                ->build(),
        ];
    }

    /**
     * @param \PhpParser\Node\Scalar\String_ $nameArg
     * @param array<\PhpParser\Node\Arg> $args
     * @return string
     */
    protected function getInputClassName(String_ $nameArg, array $args): string
    {
        $behaviorName = $nameArg->value;

        if (
            !isset($args[1])
            || !$args[1]->value instanceof Node\Expr\Array_
        ) {
            return $behaviorName;
        }
        foreach ($args[1]->value->items as $item) {
            if (
                !$item instanceof Node\Expr\ArrayItem
                || !$item->key instanceof String_
                || $item->key->value !== 'className'
            ) {
                continue;

            }
            if ($item->value instanceof String_) {
                return $item->value->value;
            }

            if ($item->value instanceof Node\Expr\ClassConstFetch) {
                return (string)$item->value->class;
            }
        }

        return $behaviorName;
    }
}
