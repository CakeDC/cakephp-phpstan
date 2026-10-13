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

namespace CakeDC\PHPStan\Traits;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use function count;

trait BaseCakeRegistryReturnTrait
{
    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type|null
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        if (count($methodCall->getArgs()) === 0) {
            return null;
        }

        $argType = $scope->getType($methodCall->getArgs()[0]->value);
        $names = $argType->getConstantStrings();
        if ($names === [] || !TypeCombinator::union(...$names)->isSuperTypeOf($argType)->yes()) {
            return null;
        }

        return TypeCombinator::union(...array_map(
            fn($name) => $this->getCakeType($name->getValue()),
            $names,
        ));
    }

    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return $this->className;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return bool
     */
    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === $this->methodName;
    }

    /**
     * @param string $baseName
     * @return \PHPStan\Type\ObjectType
     */
    protected function getCakeType(string $baseName): ObjectType
    {
        $className = $this->cakeNameRegistry->getClassName($baseName, $this->namespaceFormat);
        if ($className !== null) {
            return new ObjectType($className);
        }

        return new ObjectType($this->defaultClass);
    }
}
