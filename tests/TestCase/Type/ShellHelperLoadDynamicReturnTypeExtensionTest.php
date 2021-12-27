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

use Cake\Console\ConsoleIo;
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
     * Test getClassMethod
     *
     * @return void
     */
    public function testGetClassWithCustom()
    {
        $subject = new ShellHelperLoadDynamicReturnTypeExtension(ConsoleIo::class);
        $this->assertSame(ConsoleIo::class, $subject->getClass());
    }

    /**
     * Data provider for testIsMethodSupported method
     *
     * @return array
     */
    public function dataProviderIsMethodSupported()
    {
        return [
            ['get', null, false],
            ['get', 'helper', false],
            ['helper', 'helper', true],
            ['helper', null, true],
            ['Helper', null, false],
            ['myHelper', 'myHelper', true],
            ['myHelper', 'helper', false],
        ];
    }
    /**
     * Test getClassMethod
     *
     * @param string $testMethod
     * @param string|null $methodConfigured
     * @param bool $expected
     * @dataProvider dataProviderIsMethodSupported
     */
    public function testIsMethodSupported(string $testMethod, ?string$methodConfigured, bool $expected)
    {
        $subject = new ShellHelperLoadDynamicReturnTypeExtension(null, $methodConfigured);
        $methodReflection = new DummyMethodReflection($testMethod);
        $this->assertSame($expected, $subject->isMethodSupported($methodReflection));
    }
}
