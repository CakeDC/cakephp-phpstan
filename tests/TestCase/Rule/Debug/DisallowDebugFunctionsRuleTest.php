<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Debug;

use CakeDC\PHPStan\Rule\Debug\DisallowDebugFunctionsRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class DisallowDebugFunctionsRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new DisallowDebugFunctionsRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingDebugUseLogic.php'], [
            [
                'Use of debug function "debug" is not allowed',
                14, // asserted error line
            ],
            [
                'Use of debug function "debug_print_backtrace" is not allowed',
                15, // asserted error line
            ],
            [
                'Use of debug function "debug_zval_dump" is not allowed',
                16, // asserted error line
            ],
            [
                'Use of debug function "print_r" is not allowed',
                19, // asserted error line
            ],
            [
                'Use of debug function "print_r" is not allowed',
                20, // asserted error line
            ],
            [
                'Use of debug function "var_dump" is not allowed',
                21, // asserted error line
            ],
            [
                'Use of debug function "var_export" is not allowed',
                23, // asserted error line
            ],
            [
                'Use of debug function "var_export" is not allowed',
                24, // asserted error line
            ],
            [
                'Use of debug function "stackTrace" is not allowed',
                25, // asserted error line
            ],
            [
                'Use of debug function "pr" is not allowed',
                26, // asserted error line
            ],
            [
                'Use of debug function "dd" is not allowed',
                28, // asserted error line
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
