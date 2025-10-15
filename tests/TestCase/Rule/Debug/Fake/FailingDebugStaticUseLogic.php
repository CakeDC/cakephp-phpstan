<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Debug\Fake;

use Cake\Error\Debugger;
use DebugKit\DebugSql;

class FailingDebugStaticUseLogic
{
    /**
     * @return void
     */
    public function execute(): void
    {
        Debugger::dump(['a' => 1, 'b' => 2, 'c' => 3]);
        Debugger::printVar(['y' => 10, 'x' => 20, 'z' => 30]);
        DebugSql::sql();
        DebugSql::sqld();
    }
}
