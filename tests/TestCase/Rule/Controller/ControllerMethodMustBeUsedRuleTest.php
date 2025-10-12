<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Test\TestCase\Rule\Controller;

use CakeDC\PHPStan\Rule\Controller\ControllerMethodMustBeUsedRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class ControllerMethodMustBeUsedRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        return new ControllerMethodMustBeUsedRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/Fake/FailingControllerMethodReturnLogic.php'], [
            [
                'Method `render()` must be used to prevent unreachable code. Use `return $this->render()` or assign it to a variable.',
                17,
            ],
            [
                'Method `redirect()` must be used to prevent unreachable code. Use `return $this->redirect()` or assign it to a variable.',
                29,
            ],
            [
                'Method `render()` must be used to prevent unreachable code. Use `return $this->render()` or assign it to a variable.',
                62,
            ],
            [
                'Method `redirect()` must be used to prevent unreachable code. Use `return $this->redirect()` or assign it to a variable.',
                74,
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
