<?php

namespace CakeDC\PHPStan\Type;

use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ControllerFetchTableDynamicReturnTypeExtension extends TableLocatorDynamicReturnTypeExtension
{
    /**
     * @inheritDoc
     */
    protected function getReturnTypeWithoutArgs(Scope $scope, string $defaultClass, string $namespaceFormat, MethodReflection $methodReflection): \PHPStan\Type\ObjectType|Type
    {

        try {
            $defaultTable = $this->getDefaultTable($scope);
            if (is_string($defaultTable) && $defaultTable) {
                return $this->getCakeType($defaultTable, $defaultClass, $namespaceFormat);
            }
        } catch (\ReflectionException) {
        }
        $tableClassName = $this->getDefaultTableByControllerClass($scope);
        if ($tableClassName !== null) {
            return new ObjectType($tableClassName);
        }

        return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())
            ->getReturnType();

    }

    /**
     * @param \PHPStan\Analyser\Scope $scope
     * @return string|null
     */
    protected function getDefaultTableByControllerClass(Scope $scope): ?string
    {
        $hasProperty = $scope->getClassReflection()
            ?->getNativeReflection()
            ?->hasProperty('defaultTable');
        if (!$hasProperty) {
            return null;
        }
        $namespace = $scope->getClassReflection()
            ->getNativeReflection()
            ->getNamespaceName();
        $pos = strrpos($namespace, '\\Controller');
        if ($pos === false) {
            return null;
        }
        $baseNamespace = substr($namespace, 0, $pos);
        $shortName = $scope->getClassReflection()
            ->getNativeReflection()
            ->getShortName();
        $shortName = str_replace('Controller', '', $shortName);
        $tableClassName = sprintf('%s\\Model\\Table\\%sTable',
            $baseNamespace,
            $shortName
        );

        if (class_exists($tableClassName)) {
            return $tableClassName;
        }

        return null;
    }

}
