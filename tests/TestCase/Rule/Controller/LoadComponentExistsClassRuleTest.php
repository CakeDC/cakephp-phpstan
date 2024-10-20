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

use CakeDC\PHPStan\Rule\Controller\LoadComponentExistsClassRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class LoadComponentExistsClassRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new LoadComponentExistsClassRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingLoadComponentController.php'], [
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Mailer\Fake\FailingLoadComponentController::loadComponent could not find the class for "CrazyWorld"',
                18,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Mailer\Fake\FailingLoadComponentController::loadComponent could not find the class for "HelloWorld"',
                19,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Mailer\Fake\FailingLoadComponentController::loadComponent could not find the class for "CrayFaker"',
                25,
            ],
        ]);
    }
}
