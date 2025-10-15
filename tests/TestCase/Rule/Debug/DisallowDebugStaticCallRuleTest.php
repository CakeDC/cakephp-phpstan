<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Debug;

use CakeDC\PHPStan\Rule\Debug\DisallowDebugStaticCallRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class DisallowDebugStaticCallRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        return new DisallowDebugStaticCallRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingDebugStaticUseLogic.php'], [
            [
                'Use of debug method "Cake\Error\Debugger::dump" is not allowed. The use in shipped code is discouraged because they can leak sensitive information or clutter output.',
                16, // asserted error line
            ],
            [
                'Use of debug method "Cake\Error\Debugger::printVar" is not allowed. The use in shipped code is discouraged because they can leak sensitive information or clutter output.',
                17, // asserted error line
            ],
            [
                'Use of debug method "DebugKit\DebugSql::sql" is not allowed. The use in shipped code is discouraged because they can leak sensitive information or clutter output.',
                18, // asserted error line
            ],
            [
                'Use of debug method "DebugKit\DebugSql::sqld" is not allowed. The use in shipped code is discouraged because they can leak sensitive information or clutter output.',
                19, // asserted error line
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/../../../../extension.neon'];
    }
}
