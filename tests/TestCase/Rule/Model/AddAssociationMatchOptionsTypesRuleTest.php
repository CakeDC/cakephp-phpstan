<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use Cake\Core\Configure;
use CakeDC\PHPStan\Rule\Model\AddAssociationMatchOptionsTypesRule;
use PHPStan\Rules\Properties\PropertyReflectionFinder;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class AddAssociationMatchOptionsTypesRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new AddAssociationMatchOptionsTypesRule(
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

    /**
     * @return void
     */
    public function testRule(): void
    {
        $messageThrough = 'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "through" (Cake\ORM\Table|string|null) does not accept stdClass.';
        if (version_compare(Configure::version(), '5.0.5', '<')) {
            $messageThrough = 'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "through" (Cake\ORM\Table|string) does not accept stdClass.';
        }
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingRuleItemsTable.php'], [
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "className" (string) does not accept false.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "cascadeCallbacks" (bool) does not accept int.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "conditions" (array|Closure) does not accept \'Users.active = 1\'.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "dependent" (bool) does not accept int.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "finder" (array|string) does not accept Closure.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "bindingKey" (array<string>|string) does not accept int.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "foreignKey" (array<string>|string|false) does not accept 11.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "joinType" (string) does not accept int.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "tableLocator" (Cake\ORM\Locator\LocatorInterface|null) does not accept stdClass.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "propertyName" (string) does not accept int.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "sourceTable" (Cake\ORM\Table) does not accept string.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "targetTable" (Cake\ORM\Table) does not accept stdClass.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsTo with option "strategy" (string) does not accept false.',
                66,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasMany with option "saveStrategy" (string) does not accept int.',
                85,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasMany with option "sort" (array<Cake\Database\ExpressionInterface|string>|Cake\Database\ExpressionInterface|Closure|string|null) does not accept true.',
                85,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "targetForeignKey" (array<string>|string|null) does not accept Closure.',
                98,
            ],
            [
                $messageThrough,
                98,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "saveStrategy" (string) does not accept Closure.',
                98,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "sort" (array<Cake\Database\ExpressionInterface|string>|Cake\Database\ExpressionInterface|Closure|string|null) does not accept false.',
                98,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::belongsToMany with option "joinTable" (string) does not accept Closure.',
                98,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "className" (string) does not accept false.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "cascadeCallbacks" (bool) does not accept int.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "conditions" (array|Closure) does not accept \'parent_id = id\'.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "dependent" (bool) does not accept int.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "finder" (array|string) does not accept Closure.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "bindingKey" (array<string>|string) does not accept int.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "foreignKey" (array<string>|string|false) does not accept 11.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "joinType" (string) does not accept int.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "tableLocator" (Cake\ORM\Locator\LocatorInterface|null) does not accept stdClass.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "propertyName" (string) does not accept int.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "sourceTable" (Cake\ORM\Table) does not accept string.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "targetTable" (Cake\ORM\Table) does not accept stdClass.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with option "strategy" (string) does not accept false.',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with unknown option "saveStrategy".',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with unknown option "sort".',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with unknown option "joinTable".',
                120,
            ],
            [
                'Call to CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingRuleItemsTable::hasOne with unknown option "somethingElse".',
                120,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "className" (string) does not accept false.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "cascadeCallbacks" (bool) does not accept int.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "conditions" (array|Closure) does not accept \'parent_id = id\'.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "dependent" (bool) does not accept int.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "finder" (array|string) does not accept Closure.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "bindingKey" (array<string>|string) does not accept int.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "foreignKey" (array<string>|string|false) does not accept 11.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "joinType" (string) does not accept int.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "tableLocator" (Cake\ORM\Locator\LocatorInterface|null) does not accept stdClass.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "propertyName" (string) does not accept int.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "sourceTable" (Cake\ORM\Table) does not accept string.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "targetTable" (Cake\ORM\Table) does not accept stdClass.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with option "strategy" (string) does not accept false.',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with unknown option "saveStrategy".',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with unknown option "sort".',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with unknown option "joinTable".',
                148,
            ],
            [
                'Call to Cake\ORM\AssociationCollection::load with unknown option "somethingElse".',
                148,
            ],
        ]);
    }
}
