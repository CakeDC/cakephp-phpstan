<?php
declare(strict_types=1);

/**
 * @source https://github.com/cakephp/cakephp
 */

namespace CakeDC\PHPStan\Method;

use Cake\ORM\Association;
use Cake\ORM\Table;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\BrokerAwareExtension;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;
use PHPStan\Reflection\PropertiesClassReflectionExtension;
use PHPStan\Reflection\PropertyReflection;

class AssociationTableMixinClassReflectionExtension implements
    PropertiesClassReflectionExtension,
    MethodsClassReflectionExtension,
    BrokerAwareExtension
{
    /**
     * @var \PHPStan\Broker\Broker
     */
    private Broker $broker;

    /**
     * @param \PHPStan\Broker\Broker $broker Class reflection broker
     * @return void
     */
    public function setBroker(Broker $broker): void
    {
        $this->broker = $broker;
    }

    /**
     * @return \PHPStan\Reflection\ClassReflection
     */
    protected function getTableReflection(): ClassReflection
    {
        return $this->broker->getClass(Table::class);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $methodName      Method name
     * @return bool
     */
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        // magic findBy* method
        if ($classReflection->isSubclassOf(Table::class) && preg_match('/^find(?:\w+)?By/', $methodName) > 0) {
            return true;
        }

        if (!$classReflection->isSubclassOf(Association::class)) {
            return false;
        }

        return $this->getTableReflection()->hasMethod($methodName);
    }

    /**
     * @param \PHPStan\Reflection\ClassReflection $classReflection Class reflection
     * @param string          $methodName      Method name
     * @return \PHPStan\Reflection\MethodReflection
     */
    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        // magic findBy* method
        if ($classReflection->isSubclassOf(Table::class) && preg_match('/^find(?:\w+)?By/', $methodName) > 0) {
            return new TableFindByPropertyMethodReflection($methodName, $classReflection);
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
        if (!$classReflection->isSubclassOf(Association::class)) {
            return false;
        }

        return $this->getTableReflection()->hasProperty($propertyName);
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
}
