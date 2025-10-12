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

use PHPUnit\Framework\TestCase;

class TypeFactoryBuildDynamicReturnTypeExtensionTest extends TestCase
{
    /**
     * Test that TypeFactory::build() returns correct types and allows valid method calls.
     *
     * @return void
     */
    public function testTypeFactoryBuildReturnsCorrectTypes(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/TypeFactoryCorrectUsage.php');
        $this->assertStringContainsString('[OK] No errors', $output);
    }

    /**
     * Test that TypeFactory::build() catches invalid method calls.
     *
     * @return void
     */
    public function testTypeFactoryBuildCatchesInvalidMethodCalls(): void
    {
        $output = $this->runPhpStan(__DIR__ . '/Fake/TypeFactoryIncorrectUsage.php');

        $this->assertStringContainsString('IntegerType::setUserTimezone()', $output);
        $this->assertStringContainsString('StringType::setUserTimezone()', $output);
        $this->assertStringContainsString('BoolType::setUserTimezone()', $output);
        $this->assertStringContainsString('JsonType::nonExistentMethod()', $output);
        $this->assertStringContainsString('Found 4 errors', $output);
    }

    /**
     * Run PHPStan on a file and return the output.
     *
     * @param string $file File to analyze
     * @return string
     */
    private function runPhpStan(string $file): string
    {
        $configFile = dirname(__DIR__, 3) . '/extension.neon';
        $command = sprintf(
            'cd %s && vendor/bin/phpstan analyze %s --level=max --configuration=%s --no-progress 2>&1',
            escapeshellarg(dirname(__DIR__, 3)),
            escapeshellarg($file),
            escapeshellarg($configFile),
        );

        exec($command, $output, $exitCode);

        return implode("\n", $output);
    }
}
