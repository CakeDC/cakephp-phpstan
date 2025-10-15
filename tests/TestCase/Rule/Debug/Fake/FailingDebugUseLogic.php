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
        debug($list);//Error
        debug_print_backtrace();//Error
        debug_zval_dump('Hello World');//Error
        ksort($list);//Not a debug should not fail
        print_r(['Hello World!'], true);//No error, text is returned
        print_r(['Hello World!']);//Error
        print_r(['Hello World!'], false);//Error
        var_dump(['a' => 1, 'b' => 2, 'c' => 3]);//Error
        var_export($list, true);//No error, text is returned
        var_export($list);//Error
        var_export($list, false);//Error
        stackTrace();
        pr(['b' => 2, 'c' => 3]);
        //Last
        dd(['a' => 1, 'b' => 2, 'c' => 3]);
    }
}
