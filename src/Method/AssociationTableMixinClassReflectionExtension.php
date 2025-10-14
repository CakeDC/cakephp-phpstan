<?php
declare(strict_types=1);

/**
 * @source https://github.com/cakephp/cakephp
 */

namespace CakeDC\PHPStan\Method;

use Cake\ORM\Association;
use Cake\ORM\Table;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;

class AssociationTableMixinClassReflectionExtension implements
    PropertiesClassReflectionExtension,
    MethodsClassReflectionExtension
{
    /**
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    protected ReflectionProvider $reflectionProvider;

    /**
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     */
    public function __construct(ReflectionProvider $reflectionProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
    }

    /**
     * @return \PHPStan\Reflection\ClassReflection
     */
    protected function getTableReflection(): ClassReflection
    {
        return $this->reflectionProvider->getClass(Table::class);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $methodName      Method name
     * @return bool
     */
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        // Handle Table classes
        if ($classReflection->is(Table::class)) {
            if ($classReflection->hasNativeMethod($methodName)) {
                return false; // Let the native method be used
            }
            // magic findBy* and findAllBy* methods - available on ALL table classes
            if (preg_match('/^find(All)?By/', $methodName) === 1) {
                return true;
            }
        }

        if (!$classReflection->is(Association::class)) {
            return false;
        }

        $classReflection = $this->getAssociationTargetClassReflection($classReflection);
        if ($classReflection->hasNativeMethod($methodName)) {
            return false;
        }

        return preg_match('/^find(All)?By/', $methodName) === 1;
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $methodName      Method name
     * @return \PHPStan\Reflection\MethodReflection
     */
    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        // Handle Table classes
        if ($classReflection->is(Table::class)) {
            if ($classReflection->hasNativeMethod($methodName)) {
                return $classReflection->getNativeMethod($methodName);
            }
            // magic findBy* and findAllBy* methods
            if (preg_match('/^find(All)?By/', $methodName) === 1) {
                return new TableFindByPropertyMethodReflection($methodName, $classReflection);
            }
        }

        // For associations, handle magic find(All)?By methods
        if (preg_match('/^find(All)?By/', $methodName) === 1) {
            return new TableFindByPropertyMethodReflection($methodName, $this->getTableReflection());
        }

        return $this->getTableReflection()->getNativeMethod($methodName);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $propertyName    Method name
     * @return bool
     */
    public function hasProperty(ClassReflection $classReflection, string $propertyName): bool
    {
        if (!$classReflection->is(Association::class)) {
            return false;
        }

        return $this->getTableReflection()->hasInstanceProperty($propertyName);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $propertyName    Method name
     * @return \PHPStan\Reflection\PropertyReflection
     */
    public function getProperty(ClassReflection $classReflection, string $propertyName): PropertyReflection
    {
        return $this->getTableReflection()->getNativeProperty($propertyName);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection
     * @return \PHPStan\Reflection\ClassReflection|null
     */
    protected function getAssociationTargetClassReflection(ClassReflection $classReflection): ?ClassReflection
    {
        $type = $classReflection->getObjectType();
        if (!$type instanceof GenericObjectType) {
            return $this->getTableReflection();
        }
        $subType = $type->getTypes()[0] ?? null;
        if (!$subType instanceof ObjectType) {
            return $this->getTableReflection();
        }
        $tableClass = $subType->getClassReflection();
        if ($tableClass->is(Table::class)) {
            return $tableClass;
        }

        return $this->getTableReflection();
    }
}
