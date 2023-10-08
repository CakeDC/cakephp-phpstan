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

use Cake\ORM\Table;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use ReflectionException;

class TableLocatorDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var string
     */
    protected string $className;
    /**
     * @var string
     */
    protected string $methodName;
    protected string $defaultClass;
    protected string $namespaceFormat;

    /**
     * TableLocatorDynamicReturnTypeExtension constructor.
     *
     * @param string $className  The target className.
     * @param string $methodName The dynamic method to handle.
     */
    public function __construct(string $className, string $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->defaultClass = Table::class;
        $this->namespaceFormat = '%s\\Model\\Table\\%sTable';
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall       $methodCall
     * @param \PHPStan\Analyser\Scope            $scope
     * @return \PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): ?Type {
        $defaultClass = Table::class;
        $namespaceFormat = '%s\\Model\\Table\\%sTable';

        if (count($methodCall->getArgs()) === 0) {
            $targetClassReflection = $this->getTargetClassReflection($scope, $methodCall);
            if ($targetClassReflection === null) {
                return null;
            }

            return $this->getReturnTypeWithoutArgs($methodReflection, $methodCall, $targetClassReflection);
        }

        return $this->getRegistryReturnType($methodReflection, $methodCall, $scope, $defaultClass, $namespaceFormat);
    }

    /**
     * @param \ReflectionClass $target
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getDefaultTable(\ReflectionClass $target): mixed
    {
        return $target->getProperty('defaultTable')?->getDefaultValue();
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \ReflectionClass $targetClassReflection
     * @return \PHPStan\Type\ObjectType|\PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function getReturnTypeWithoutArgs(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        \ReflectionClass $targetClassReflection
    ): ObjectType|Type|null {
        try {
            $defaultTable = $this->getDefaultTable($targetClassReflection);
            if (is_string($defaultTable) && $defaultTable) {
                return $this->getCakeType($defaultTable, $this->defaultClass, $this->namespaceFormat);
            }
        } catch (ReflectionException) {
        }

        return null;
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @return \ReflectionClass|null
     */
    protected function getTargetClassReflection(Scope $scope, MethodCall $methodCall): ?\ReflectionClass
    {
        $reference = $scope->getType($methodCall->var)->getReferencedClasses();

        if (!isset($reference[0])) {
            return null;
        }
        try {
            return new \ReflectionClass($reference[0]);
        } catch (ReflectionException $e) {
            return null;
        }
    }
}
