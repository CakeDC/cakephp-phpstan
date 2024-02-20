<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use CakeDC\PHPStan\Rule\Model\TableGetMatchOptionsTypesRule;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleLevelHelper;
use PHPStan\Testing\RuleTestCase;

class TableGetMatchOptionsTypesRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        // getRule() method needs to return an instance of the tested rule
        return new TableGetMatchOptionsTypesRule(
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
        $this->analyse([__DIR__ . '/Fake/FailingTableGetRuleItemsLogic.php'], [
            ['Call to App\Model\Table\NotesTable::get with option "select" (array|Cake\Database\ExpressionInterface|Cake\ORM\Association|Cake\ORM\Table|Closure|float|int|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "fields" (array|Cake\Database\ExpressionInterface|Cake\ORM\Association|Cake\ORM\Table|Closure|float|int|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "conditions" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "where" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "order" (array|Cake\Database\ExpressionInterface|Closure|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "orderBy" (array|Cake\Database\ExpressionInterface|Closure|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "limit" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "offset" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "group" (array|Cake\Database\ExpressionInterface|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "groupBy" (array|Cake\Database\ExpressionInterface|string) does not accept false.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "having" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "contain" (array|string) does not accept true.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "page" (int) does not accept string.', 37],
            ['Call to App\Model\Table\NotesTable::get with option "order" (array|Cake\Database\ExpressionInterface|Closure|string) does not accept false.', 65],
            ['Call to App\Model\Table\NotesTable::get with option "limit" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 65],
            ['Call to App\Model\Table\NotesTable::get with option "conditions" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 69],
            ['Call to App\Model\Table\NotesTable::get with option "offset" (Cake\Database\ExpressionInterface|int|null) does not accept string.', 69],
            ['Call to App\Model\Table\NotesTable::get with option "fields" (array|Cake\Database\ExpressionInterface|Cake\ORM\Association|Cake\ORM\Table|Closure|float|int|string) does not accept false.', 73],
            ['Call to App\Model\Table\NotesTable::get with option "contain" (array|string) does not accept true.', 73],
            ['Call to App\Model\Table\UsersTable::get with option "groupBy" (array|Cake\Database\ExpressionInterface|string) does not accept false.', 78],
            ['Call to App\Model\Table\UsersTable::get with option "having" (array|Cake\Database\ExpressionInterface|Closure|string|null) does not accept stdClass.', 78],
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
