<?php

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *  @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Shell;

use Cake\Console\Shell;

class MyTestDynamicLoadShell extends Shell
{
    /**
     * @return bool|int|void|null
     */
    public function main()
    {
        //testing ShellHelperLoadDynamicReturnTypeExtension
        $this->helper('progress')->increment(1);

        //testing TableLocatorDynamicReturnTypeExtension
        $latest = $this->loadModel('CakeDC/MyPlugin.HelloWorld101010Articles')
            ->getLatestOne();
        $this->out($latest['id']);
    }
}
