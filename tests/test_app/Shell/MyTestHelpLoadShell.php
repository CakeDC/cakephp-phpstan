<?php

namespace App\Shell;

use Cake\Console\Shell;

class MyTestHelpLoadShell extends Shell
{
    /**
     * @return bool|int|void|null
     */
    public function main()
    {
        //logic
        $this->helper('progress')->increment(1);
    }
}
