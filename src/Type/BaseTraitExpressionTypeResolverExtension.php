<?php
declare(strict_types=1);

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ExpressionTypeResolverExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use ReflectionException;

class BaseTraitExpressionTypeResolverExtension implements ExpressionTypeResolverExtension
{
    /**
     * TableLocatorDynamicReturnTypeExtension constructor.
     *
     * @param string $targetTrait The target trait.
     * @param string $methodName The dynamic method to handle.
     * @param string $namespaceFormat The resolve namespace format.
     * @param string|null $propertyDefaultValue A property name for default classname, used when no args in method call.
     */
    public function __construct(
        protected string $targetTrait,
        protected string $methodName,
        protected string $namespaceFormat,
        protected ?string $propertyDefaultValue = null
    ) {
    }

    /**
     * @param \PhpParser\Node\Expr $expr
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     */
    public function getType(Expr $expr, Scope $scope): ?Type
    {
        if (
            !$expr instanceof Expr\MethodCall
            || !$expr->name instanceof Identifier
            || $expr->name->toString() !== $this->methodName
        ) {
            return null;
        }

        $callerType = $scope->getType($expr->var);

        if (!$callerType instanceof ThisType) {
            return null;
        }
        $reflection = $callerType->getClassReflection();
        if (!$this->isFromTargetTrait($reflection)) {
            return null;
        }

        $value = $expr->getArgs()[0]->value ?? null;
        $baseName = $this->getBaseName($value, $reflection);
        if ($baseName === null) {
            return null;
        }
        $className = CakeNameRegistry::getClassName($baseName, $this->namespaceFormat);
        if ($className !== null) {
            return new ObjectType($className);
        }

        return null;
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $reflection
     * @return bool
     */
    protected function isFromTargetTrait(ClassReflection $reflection): bool
    {
        foreach ($reflection->getTraits() as $trait) {
            if ($trait->getName() === $this->targetTrait) {
                return true;
            }
        }
        foreach ($reflection->getParents() as $parent) {
            if ($this->isFromTargetTrait($parent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \PhpParser\Node\Expr|null $value
     * @param \PHPStan\Reflection\ClassReflection $reflection
     * @return string|null
     */
    protected function getBaseName(?Expr $value, ClassReflection $reflection): ?string
    {
        if ($value instanceof String_) {
            return $value->value;
        }

        try {
            if ($value === null && $this->propertyDefaultValue) {
                $value = $reflection->getNativeReflection()
                    ->getProperty($this->propertyDefaultValue)
                    ->getDefaultValue();

                return is_string($value) ? $value : null;
            }
        } catch (ReflectionException) {
            return null;
        }

        return null;
    }
}
