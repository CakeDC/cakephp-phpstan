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
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use ReflectionException;

class TableLocatorDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait {
        getTypeFromMethodCall as getTypeFromMethodCallWithArgs;
    }

    /**
     * @var class-string
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
     * @param class-string $className  The target className.
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
     * @return \PHPStan\Type\Type|null
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): ?Type {
        if (count($methodCall->getArgs()) === 0) {
            $targetClassReflection = $this->getTargetClassReflection($scope, $methodCall);
            $type = null;
            if ($targetClassReflection !== null) {
                $type = $this->getReturnTypeWithoutArgs($methodReflection, $methodCall, $targetClassReflection);
            }
            if ($type !== null) {
                return $type;
            }

            return null;
        }

        return $this->getTypeFromMethodCallWithArgs($methodReflection, $methodCall, $scope);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $target
     * @return string|null
     * @throws \ReflectionException
     */
    protected function getDefaultTable(ClassReflection $target): ?string
    {
        $default = $target->getNativeReflection()
            ->getProperty('defaultTable')
            ->getDefaultValueExpression();
        if ($default instanceof String_) {
            return $default->value;
        }

        return null;
    }

    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Reflection\ClassReflection $targetClassReflection
     * @return \PHPStan\Type\Type|null
     */
    protected function getReturnTypeWithoutArgs(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        ClassReflection $targetClassReflection,
    ): ?Type {
        try {
            $defaultTable = $this->getDefaultTable($targetClassReflection);
            if (is_string($defaultTable) && $defaultTable !== '') {
                return $this->getCakeType($defaultTable);
            }
        } catch (ReflectionException) {
        }

        return null;
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @return \PHPStan\Reflection\ClassReflection|null
     */
    protected function getTargetClassReflection(Scope $scope, MethodCall $methodCall): ?ClassReflection
    {
        return $scope->getType($methodCall->var)->getObjectClassReflections()[0] ?? null;
    }
}
