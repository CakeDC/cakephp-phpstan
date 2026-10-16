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

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use CakeDC\PHPStan\Rule\Model\EntityStrictPropertyAccessRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class EntityStrictPropertyAccessRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new EntityStrictPropertyAccessRule();
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingUser.php'], [
            [
                'Modifying property "$this->first_name" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(...) for proper dirty tracking.',
                32, // asserted error line
            ],
            [
                'Modifying property "$this->last_name" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(...) for proper dirty tracking.',
                33, // asserted error line
            ],
        ]);
    }
}
