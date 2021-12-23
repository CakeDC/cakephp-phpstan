<?php

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *  @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Type;

use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use Cake\Console\Helper;
use Cake\Console\Shell;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

class ShellHelperLoadDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
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
     */
    public function __construct()
    {
        $this->className = Shell::class;
        $this->methodName = 'helper';
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
        $defaultClass = Helper::class;
        $namespaceFormat = '%s\\Shell\Helper\\%sHelper';

        return $this->getRegistryReturnType($methodReflection, $methodCall, $scope, $defaultClass, $namespaceFormat);
    }

    /**
     * @param string $baseName
     * @return array
     * @psalm-return array{string|null, string}
     */
    protected function pluginSplit($baseName): array
    {
        list($plugin, $name) = pluginSplit($baseName);
        $name = \ucfirst($name);

        return [$plugin, $name];
    }
}
