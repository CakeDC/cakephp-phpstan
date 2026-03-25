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

namespace CakeDC\PHPStan\Type;

use Cake\ORM\Query\SelectQuery;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\IntegerType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;

/**
 * PHPStan extension to provide proper return types for find('list')->toArray()
 *
 * When find('list') is detected in the method chain before toArray(),
 * this returns array<int|string, string> instead of the generic entity array.
 *
 * When groupField is used, returns array<int|string, array<int|string, string>>
 *
 * This handles chained queries like:
 * - $table->find('list')->toArray()
 * - $table->find('list')->where([...])->orderBy([...])->toArray()
 * - $table->find('list', groupField: 'category_id')->toArray()
 */
class SelectQueryFindListReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return SelectQuery::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'toArray';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        $findListCall = $this->findFindListCall($methodCall->var);
        if ($findListCall === null) {
            return null;
        }

        $keyType = new UnionType([new IntegerType(), new StringType()]);
        $valueType = new StringType();

        // Check if groupField is present
        if ($this->hasGroupField($findListCall)) {
            // Return array<int|string, array<int|string, string>> for grouped list
            return new ArrayType(
                $keyType,
                new ArrayType($keyType, $valueType),
            );
        }

        // Return array<int|string, string> for simple list
        return new ArrayType($keyType, $valueType);
    }

    /**
     * Recursively find the find('list') call in the method call chain
     */
    private function findFindListCall(mixed $expr): ?MethodCall
    {
        if (!$expr instanceof MethodCall) {
            return null;
        }

        if ($this->isFindListCall($expr)) {
            return $expr;
        }

        return $this->findFindListCall($expr->var);
    }

    /**
     * Check if a method call is find('list')
     */
    private function isFindListCall(MethodCall $methodCall): bool
    {
        if (!$methodCall->name instanceof Identifier) {
            return false;
        }

        if ($methodCall->name->name !== 'find') {
            return false;
        }

        $args = $methodCall->getArgs();
        if (count($args) === 0) {
            return false;
        }

        $firstArg = $args[0]->value;
        if (!$firstArg instanceof String_) {
            return false;
        }

        return $firstArg->value === 'list';
    }

    /**
     * Check if the find('list') call has a groupField argument
     */
    private function hasGroupField(MethodCall $methodCall): bool
    {
        $args = $methodCall->getArgs();

        foreach ($args as $arg) {
            // Check for named argument: groupField: 'something'
            if ($arg->name instanceof Identifier && $arg->name->name === 'groupField') {
                return true;
            }
        }

        return false;
    }
}
