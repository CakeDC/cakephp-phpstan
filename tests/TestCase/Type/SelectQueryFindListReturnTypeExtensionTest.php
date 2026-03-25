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

class SelectQueryFindListReturnTypeExtensionTest extends TestCase
{
    use PhpStanTestTrait;

    /**
     * Test that find('list')->toArray() returns correct type.
     *
     * @return void
     */
    public function testFindListReturnsCorrectType(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/FindListCorrectUsage.php');
        static::assertStringContainsString('[OK] No errors', $output);
    }

    /**
     * Test that find('list') type is properly inferred in chained queries.
     *
     * @return void
     */
    public function testFindListChainedQueriesReturnCorrectType(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/FindListChainedUsage.php');
        static::assertStringContainsString('[OK] No errors', $output);
    }

    /**
     * Test that find('list') with groupField returns nested array type.
     *
     * @return void
     */
    public function testFindListWithGroupFieldReturnsNestedArray(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/FindListGroupedUsage.php');
        static::assertStringContainsString('[OK] No errors', $output);
    }
}
