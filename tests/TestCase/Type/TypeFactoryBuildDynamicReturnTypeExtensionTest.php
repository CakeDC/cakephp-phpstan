<?php
declare(strict_types=1);

/**
 * Copyright 2025, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2025, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Test\TestCase\Type;

use CakeDC\PHPStan\Test\TestCase\PhpStanTestTrait;
use PHPUnit\Framework\TestCase;

class TypeFactoryBuildDynamicReturnTypeExtensionTest extends TestCase
{
    use PhpStanTestTrait;

    /**
     * Test that TypeFactory::build() returns correct types and allows valid method calls.
     *
     * @return void
     */
    public function testTypeFactoryBuildReturnsCorrectTypes(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/TypeFactoryCorrectUsage.php');
        static::assertStringContainsString('[OK] No errors', $output);
    }

    /**
     * Test that TypeFactory::build() catches invalid method calls.
     *
     * @return void
     */
    public function testTypeFactoryBuildCatchesInvalidMethodCalls(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/TypeFactoryIncorrectUsage.php');

        static::assertStringContainsString('IntegerType::setUserTimezone()', $output);
        static::assertStringContainsString('StringType::setUserTimezone()', $output);
        static::assertStringContainsString('BoolType::setUserTimezone()', $output);
        static::assertStringContainsString('JsonType::nonExistentMethod()', $output);
        static::assertStringContainsString('Found 4 errors', $output);
    }
}
