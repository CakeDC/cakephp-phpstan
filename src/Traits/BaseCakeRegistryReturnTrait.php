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

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use function Cake\Core\pluginSplit;
use function class_exists;
use function count;
use function method_exists;
use function sprintf;
use function str_replace;

trait BaseCakeRegistryReturnTrait
{
    /**
     * @param \PHPStan\Reflection\MethodReflection $methodReflection
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param \PHPStan\Analyser\Scope $scope
     * @return \PHPStan\Type\Type
     * @throws \PHPStan\ShouldNotHappenException
     */
    protected function getRegistryReturnType(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ): Type {
        if (count($methodCall->getArgs()) === 0) {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())
                ->getReturnType();
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
     * @return array
     * @psalm-return array{string|null, string}
     */
    protected function pluginSplit(string $baseName): array
    {
        return pluginSplit($baseName);
    }

    /**
     * @param string $baseName
     * @return \PHPStan\Type\ObjectType
     */
    protected function getCakeType(string $baseName): ObjectType
    {
        [$plugin, $name] = $this->pluginSplit($baseName);
        $prefixes = $plugin ? [$plugin] : ['Cake', 'App'];
        $namespaceFormat = (array)$this->namespaceFormat;
        foreach ($namespaceFormat as $format) {
            foreach ($prefixes as $prefix) {
                $namespace = str_replace('/', '\\', $prefix);
                $className = sprintf($format, $namespace, $name);
                if (class_exists($className)) {
                    return new ObjectType($className);
                }
            }
        }

        return new ObjectType($this->defaultClass);
    }
}
