<?php

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use CakeDC\PHPStan\Rule\Model\AddAssociationRule;
use CakeDC\PHPStan\Rule\Model\AddAssociationWithValidOptionsTypesRule;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class AddAssociationWithValidOptionsTypesRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new AddAssociationWithValidOptionsTypesRule(
            new RuleLevelHelper(
                $this->createReflectionProvider(),
                true,
                false,
                true,
                false,
                false,
                true,
                false
            ),
            new PropertyReflectionFinder()
        );
    }

    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingRuleItemsTable.php'], [
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "className" (string) does not accept false.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "cascadeCallbacks" (bool) does not accept int.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "conditions" (array|Closure) does not accept \'Users.active = 1\'.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "dependent" (bool) does not accept int.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "finder" (array|string) does not accept Closure.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "bindingKey" (array<string>|string) does not accept int.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "foreignKey" (array<string>|string|false) does not accept 11.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "joinType" (string) does not accept int.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "tableLocator" (Cake\ORM\Locator\LocatorInterface|null) does not accept stdClass.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "propertyName" (string) does not accept int.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "sourceTable" (Cake\ORM\Table) does not accept string.',
                62
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "targetTable" (Cake\ORM\Table) does not accept stdClass.',
                62
            ],
        ]);
    }

}
