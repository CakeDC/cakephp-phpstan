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
