<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Testing;

use CakeDC\PHPStan\Constraint\ArrayOfStringStartsWith;
use PHPStan\Analyser\Error;

/**
 * @mixin \PHPStan\Testing\RuleTestCase
 */
trait AnalyseCheckLineStartsWithTrait
{
    /**
     * @param string[] $files
     * @param array{array{'0': string, '1':int}} $expected
     * @return void
     */
    public function analyseCheckLineStartsWith(array $files, array $expected): void
    {
        $actualErrors = $this->gatherAnalyserErrors($files);
        $messageText = static function (int $line, string $message): string {
            return sprintf('%02d: %s', $line, $message);
        };
        $actualErrors = array_map(static function (Error $error) use ($messageText): string {
            $line = $error->getLine();
            if ($line === null) {
                return $messageText(-1, $error->getMessage());
            }

            return $messageText($line, $error->getMessage());
        }, $actualErrors);

        $expected = array_map(static function (array $item) use ($messageText): string {
            return $messageText((int)$item[1], (string)$item[0]);
        }, $expected);
        $this->assertThat($expected, new ArrayOfStringStartsWith($actualErrors));
    }
}
