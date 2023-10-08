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
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;
use ReflectionException;

class TableLocatorDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var string
     */
    private string $className;
    /**
     * @var string
     */
    private string $methodName;

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
    ): Type {
        $defaultClass = Table::class;
        $namespaceFormat = '%s\\Model\\Table\\%sTable';

        if (count($methodCall->getArgs()) === 0) {
            return $this->getReturnTypeWithoutArgs($scope, $defaultClass, $namespaceFormat, $methodReflection);
        }

        return $this->getRegistryReturnType($methodReflection, $methodCall, $scope, $defaultClass, $namespaceFormat);
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @return mixed|null
     * @throws \ReflectionException
     */
    protected function getDefaultTable(Scope $scope): mixed
    {
        $defaultTable = $scope->getClassReflection()
            ?->getNativeReflection()
            ?->getProperty('defaultTable')
            ?->getDefaultValue();
        return $defaultTable;
    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @param string $defaultClass
     * @param string $namespaceFormat
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @return \PHPStan\Type\ObjectType|\PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function getReturnTypeWithoutArgs(Scope $scope, string $defaultClass, string $namespaceFormat, MethodReflection $methodReflection): \PHPStan\Type\ObjectType|Type
    {
        try {
            $defaultTable = $this->getDefaultTable($scope);
            if (is_string($defaultTable) && $defaultTable) {
                return $this->getCakeType($defaultTable, $defaultClass, $namespaceFormat);
            }
        } catch (ReflectionException) {
        }

        return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())
            ->getReturnType();
    }
}
