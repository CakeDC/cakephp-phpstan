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

use CakeDC\PHPStan\Rule\Controller\ControllerMethodMustReturnRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class ControllerMethodMustReturnRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        return new ControllerMethodMustReturnRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../../../test_app/Controller/FailingControllerMethodReturnLogic.php'], [
            [
                'Method render() must be returned to prevent unreachable code. Use "return $this->render()" instead.',
                17,
            ],
            [
                'Method redirect() must be returned to prevent unreachable code. Use "return $this->redirect()" instead.',
                29,
            ],
            [
                'Method render() must be returned to prevent unreachable code. Use "return $this->render()" instead.',
                62,
            ],
            [
                'Method redirect() must be returned to prevent unreachable code. Use "return $this->redirect()" instead.',
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
