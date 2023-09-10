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

namespace CakeDC\PHPStan\Test\TestCase\Type;

use Cake\Controller\Controller;
use CakeDC\PHPStan\Type\ComponentLoadDynamicReturnTypeExtension;
use PHPStan\Reflection\Dummy\DummyMethodReflection;
use PHPUnit\Framework\TestCase;

class ComponentLoadDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * Test getClassMethod
     *
     * @return void
     */
    public function testGetClass()
    {
        $subject = new ComponentLoadDynamicReturnTypeExtension();
        $this->assertSame(Controller::class, $subject->getClass());
    }

    /**
     * Data provider for testIsMethodSupported method
     *
     * @return array
     */
    public static function dataProviderIsMethodSupported()
    {
        return [
            ['loadComponent', true],
            ['get', false],
            ['loadModel', false],
        ];
    }

    /**
     * Test getClassMethod
     *
     * @param        string $testMethod
     * @param        bool   $expected
     * @dataProvider dataProviderIsMethodSupported
     */
    public function testIsMethodSupported(string $testMethod, bool $expected)
    {
        $subject = new ComponentLoadDynamicReturnTypeExtension();
        $methodReflection = new DummyMethodReflection($testMethod);
        $this->assertSame($expected, $subject->isMethodSupported($methodReflection));
    }
}
