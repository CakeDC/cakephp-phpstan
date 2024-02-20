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

use CakeDC\PHPStan\Utility\CakeNameRegistry;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function count;
use function method_exists;

trait BaseCakeRegistryReturnTrait
{
    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        if (count($methodCall->getArgs()) === 0) {
            return $this->getTypeWhenNotFound($methodReflection);
        }

        $argType = $scope->getType($methodCall->getArgs()[0]->value);
        if (!method_exists($argType, 'getValue')) {
            return new ObjectType($this->defaultClass);
        }

        return $this->getCakeType($argType->getValue());
    }

    /**
     * Get the target class.
     *
     * @return string
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
        $className = CakeNameRegistry::getClassName($baseName, $this->namespaceFormat);
        if ($className !== null) {
            return new ObjectType($className);
        }

        return new ObjectType($this->defaultClass);
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return \PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function getTypeWhenNotFound(MethodReflection $methodReflection): Type
    {
        return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())
            ->getReturnType();
    }
}
