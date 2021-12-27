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

namespace CakeDC\PHPStan\Test\TestCase\Type;

use CakeDC\PHPStan\Type\TableLocatorDynamicReturnTypeExtension;
use PHPStan\Reflection\Dummy\DummyMethodReflection;
use PHPUnit\Framework\TestCase;

class TableLocatorDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * Data provider for testGetClass method
     *
     * @return array
     */
    public function dataProviderGetClass()
    {
        return [
            ['Cake\ORM\Locator\LocatorInterface', 'get'],
            ['Cake\Controller\Controller', 'loadModel']
        ];
    }

    /**
     * Test getClassMethod
     *
     * @param string $targetClass
     * @param string $methodName
     * @dataProvider dataProviderGetClass
     */
    public function testGetClass(string $targetClass, string $methodName)
    {
        $subject = new TableLocatorDynamicReturnTypeExtension($targetClass, $methodName);
        $this->assertSame($targetClass, $subject->getClass());
    }

    /**
     * Data provider for testIsMethodSupported method
     *
     * @return array
     */
    public function dataProviderIsMethodSupported()
    {
        return [
            ['get', 'get', true],
            ['get', 'read', false],
            ['loadModel', 'loadModel', true],
            ['loadModel', 'get', false]
        ];
    }
    /**
     * Test getClassMethod
     *
     * @param string $allowedMethod
     * @param string $testMethod
     * @param bool $expected
     * @dataProvider dataProviderIsMethodSupported
     */
    public function testIsMethodSupported(string $allowedMethod, string $testMethod, bool $expected)
    {
        $subject = new TableLocatorDynamicReturnTypeExtension(
            'Cake\ORM\Locator\LocatorInterface',
            $allowedMethod
        );
        $methodReflection = new DummyMethodReflection($testMethod);
        $this->assertSame($expected, $subject->isMethodSupported($methodReflection));
    }
}
