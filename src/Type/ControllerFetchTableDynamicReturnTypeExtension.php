<?php
declare(strict_types=1);

/**
 * Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ControllerFetchTableDynamicReturnTypeExtension extends TableLocatorDynamicReturnTypeExtension
{
    /**
     * @inheritDoc
     */
    protected function getReturnTypeWithoutArgs(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        ClassReflection $targetClassReflection
    ): ?Type {
        $type = parent::getReturnTypeWithoutArgs($methodReflection, $methodCall, $targetClassReflection);
        if ($type !== null) {
            return $type;
        }
        $tableClassName = $this->getDefaultTableByControllerClass($targetClassReflection);
        if ($tableClassName !== null) {
            return new ObjectType($tableClassName);
        }

        return null;
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $targetClassReflection
     * @return string|null
     */
    protected function getDefaultTableByControllerClass(ClassReflection $targetClassReflection): ?string
    {
        $hasProperty = $targetClassReflection->hasProperty('defaultTable');
        if (!$hasProperty) {
            return null;
        }
        $nativeReflection = $targetClassReflection->getNativeReflection();
        $namespace = $nativeReflection->getNamespaceName();
        $pos = strrpos($namespace, '\\Controller');
        if ($pos === false) {
            return null;
        }
        $baseNamespace = substr($namespace, 0, $pos);
        $shortName = $nativeReflection->getShortName();
        $shortName = str_replace('Controller', '', $shortName);
        $tableClassName = sprintf(
            '%s\\Model\\Table\\%sTable',
            $baseNamespace,
            $shortName
        );

        if (class_exists($tableClassName)) {
            return $tableClassName;
        }

        return null;
    }
}
