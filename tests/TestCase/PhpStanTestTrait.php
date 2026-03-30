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

namespace CakeDC\PHPStan\Test\TestCase;

trait PhpStanTestTrait
{
    /**
     * Run PHPStan on a file and return the output.
     *
     * @param string $file File to analyze
     * @return string
     */
    private function runPhpStan(string $file): string
    {
        $configFile = dirname(__DIR__, 2) . '/extension.neon';
        $command = sprintf(
            'cd %s && vendor/bin/phpstan analyze %s --level=max --configuration=%s --no-progress 2>&1',
            escapeshellarg(dirname(__DIR__, 2)),
            escapeshellarg($file),
            escapeshellarg($configFile),
        );

        exec($command, $output, $exitCode);

        return implode("\n", $output);
    }
}
