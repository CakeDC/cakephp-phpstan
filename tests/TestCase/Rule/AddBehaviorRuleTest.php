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

namespace CakeDC\PHPStan\Test\TestCase\Type;
namespace CakeDC\PHPStan\Test\TestCase\Rule;

use CakeDC\PHPStan\Rule\Model\AddBehaviorRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class AddBehaviorRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new AddBehaviorRule();
    }

    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/../../data/Rule/addBehaviorRule.php'], [
            [
                'Call to App\Model\Table\AddBehaviorRuleItemsTable::addBehavior could not find the behavior class for "Timtamp"',
                31, // asserted error line
            ],
            [
                'Call to App\Model\Table\AddBehaviorRuleItemsTable::addBehavior could not find the behavior class for "MyTreeBehavior"',
                32, // asserted error line
            ],
            [
                'Call to App\Model\Table\AddBehaviorRuleItemsTable::addBehavior could not find the behavior class for "Cake\Behavior\TranslateBehavior"',
                35, // asserted error line
            ],
        ]);

        // the test fails, if the expected error does not occur,
        // or if there are other errors reported beside the expected one
    }
}
