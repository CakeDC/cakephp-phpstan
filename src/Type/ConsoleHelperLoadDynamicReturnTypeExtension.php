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

use Cake\Console\ConsoleIo;
use Cake\Console\Helper;
use CakeDC\PHPStan\Traits\BaseCakeRegistryReturnTrait;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class ConsoleHelperLoadDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    use BaseCakeRegistryReturnTrait {
        getCakeType as _getCakeType;
    }

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
     */
    public function __construct()
    {
        $this->className = ConsoleIo::class;
        $this->methodName = 'helper';
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
        $defaultClass = Helper::class;
        $namespaceFormat = '%s\\Command\Helper\\%sHelper';

        return $this->getRegistryReturnType($methodReflection, $methodCall, $scope, $defaultClass, $namespaceFormat);
    }

    /**
     * Before calling BaseCakeRegistryReturnTrait::getCakeType uppercase the
     * first letter as done in the method ConsoleIo::helper
     *
     * @param string $baseName
     * @param string $defaultClass
     * @param array<string>|string $namespaceFormat
     * @return \PHPStan\Type\ObjectType
     */
    protected function getCakeType(string $baseName, string $defaultClass, array|string $namespaceFormat): ObjectType
    {
        $baseName = ucfirst($baseName);

        return $this->_getCakeType($baseName, $defaultClass, $namespaceFormat);
    }
}
