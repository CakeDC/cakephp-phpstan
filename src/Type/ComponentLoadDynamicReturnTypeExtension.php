<?php

namespace CakeDC\PHPStan;

use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use Cake\Controller\Component;
use Cake\Controller\Controller;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

class ComponentLoadDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait;

    /**
     * @var string
     */
    private $className;
    /**
     * @var string
     */
    private $methodName;

    /**
     * TableLocatorDynamicReturnTypeExtension constructor.
     *
     */
    public function __construct()
    {
        $this->className = Controller::class;
        $this->methodName = 'loadComponent';
    }

    /**
     * @param MethodReflection $methodReflection
     * @param MethodCall $methodCall
     * @param Scope $scope
     * @return Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        $defaultClass = Component::class;
        $namespaceFormat = '\\%s\\Controller\Component\\%sComponent';

        return $this->getRegistryReturnType($methodReflection, $methodCall, $scope, $defaultClass, $namespaceFormat);
    }
}
