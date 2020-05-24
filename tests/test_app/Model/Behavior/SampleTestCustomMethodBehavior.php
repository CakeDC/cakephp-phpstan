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

namespace App\Model\Behavior;

use Cake\ORM\Behavior;

class SampleTestCustomMethodBehavior extends Behavior
{
    /**
     * @return string
     */
    public function fakeData()
    {
        return 'some data';
    }
}
