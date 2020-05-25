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

use Cake\Console\Shell;
use CakeDC\PHPStan\Type\ShellHelperLoadDynamicReturnTypeExtension;
use PHPStan\Reflection\Dummy\DummyMethodReflection;
use PHPUnit\Framework\TestCase;

class ShellHelperLoadDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * Test getClassMethod
     *
     * @return void
     */
    public function testGetClass()
    {
        $subject = new ShellHelperLoadDynamicReturnTypeExtension();
        $this->assertSame(Shell::class, $subject->getClass());
    }

    /**
     * Data provider for testIsMethodSupported method
     *
     * @return array
     */
    public function dataProviderIsMethodSupported()
    {
        return [
            ['get', false],
            ['helper', true],
            ['Helper', false]
        ];
    }
    /**
     * Test getClassMethod
     *
     * @param string $testMethod
     * @param bool $expected
     * @dataProvider dataProviderIsMethodSupported
     */
    public function testIsMethodSupported(string $testMethod, bool $expected)
    {
        $subject = new ShellHelperLoadDynamicReturnTypeExtension();
        $methodReflection = new DummyMethodReflection($testMethod);
        $this->assertSame($expected, $subject->isMethodSupported($methodReflection));
    }
}
