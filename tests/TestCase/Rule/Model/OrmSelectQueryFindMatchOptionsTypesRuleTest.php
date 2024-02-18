<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use CakeDC\PHPStan\Rule\Model\OrmSelectQueryFindMatchOptionsTypesRule;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class OrmSelectQueryFindMatchOptionsTypesRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new OrmSelectQueryFindMatchOptionsTypesRule(
            new RuleLevelHelper(
                $this->createReflectionProvider(),
                true,
                false,
                true,
                false,
                false,
                true,
                false
            )
        );
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingOrmFindRuleItemsLogic.php'], [
            ['Call to App\Model\Table\NotesTable::find with option "select" (array|Cake\Database\ExpressionInterface|Cake\ORM\Association|Cake\ORM\Table|Closure|float|int|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "fields" (array|Cake\Database\ExpressionInterface|Cake\ORM\Association|Cake\ORM\Table|Closure|float|int|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "conditions" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "where" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "order" (array|Cake\Database\ExpressionInterface|Closure|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "orderBy" (array|Cake\Database\ExpressionInterface|Closure|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "limit" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "offset" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "group" (array|Cake\Database\ExpressionInterface|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "groupBy" (array|Cake\Database\ExpressionInterface|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "having" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "contain" (array|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "page" (int) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::find with option "conditions" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept false.', 65],
            ['Call to App\Model\Table\NotesTable::find with option "limit" (Cake\Database\ExpressionInterface|int|null) does not accept stdClass.', 65],
            ['Call to App\Model\Table\NotesTable::find with option "group" (array|Cake\Database\ExpressionInterface|string) does not accept true.', 65],
        ]);
    }
}
