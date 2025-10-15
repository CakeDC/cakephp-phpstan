<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Debug\Fake;


class FailingDebugUseLogic
{

    /**
     * @return void
     */
    public function execute(): void
    {
        $list = [1, 2, 3, 4];
        debug($list);
    }
}
