<?php
declare(strict_types=1);

/**
 * Copyright 2025, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use Cake\Database\TypeFactory;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use ReflectionClass;
use ReflectionException;

/**
 * Provides return type for TypeFactory::build() based on the type name argument.
 *
 * This allows PHPStan to understand that TypeFactory::build('datetime') returns
 * a DateTimeType instance with its specific methods like setUserTimezone().
 */
class TypeFactoryBuildDynamicReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    /**
     * @var array<string, class-string>|null
     */
    private ?array $typeMap = null;

    /**
     * @return class-string
     */
    public function getClass(): string
    {
        return TypeFactory::class;
    }

    /**
     * Checks if the method is supported.
     *
     * @param \PHPStan\Reflection\MethodReflection $methodReflection Method reflection
     * @return bool
     */
    public function isStaticMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'build';
    }

    /**
     * Returns the type from the static method call.
     *
     * @param \PHPStan\Reflection\MethodReflection $methodReflection Method reflection
     * @param \PhpParser\Node\Expr\StaticCall $methodCall Static method call
     * @param \PHPStan\Analyser\Scope $scope Scope
     * @return \PHPStan\Type\Type|null
     */
    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope,
    ): ?Type {
        $args = $methodCall->getArgs();
        if (count($args) === 0) {
            return null;
        }

        $argType = $scope->getType($args[0]->value);
        $constantStrings = $argType->getConstantStrings();
        if (count($constantStrings) !== 1) {
            return null;
        }

        $typeName = $constantStrings[0]->getValue();
        $typeMap = $this->getTypeMap();

        if (!isset($typeMap[$typeName])) {
            return null;
        }

        return new ObjectType($typeMap[$typeName]);
    }

    /**
     * Get the type map by reading TypeFactory's static property via reflection.
     * This is cached after the first call.
     *
     * @return array<string, class-string>
     */
    private function getTypeMap(): array
    {
        if ($this->typeMap !== null) {
            return $this->typeMap;
        }

        try {
            $reflection = new ReflectionClass(TypeFactory::class);
            $property = $reflection->getProperty('_types');
            $property->setAccessible(true);

            /** @var array<string, class-string> $defaultValue */
            $defaultValue = $property->getDefaultValue();
            $this->typeMap = $defaultValue;

            return $this->typeMap;
        } catch (ReflectionException $e) {
            return [];
        }
    }
}
