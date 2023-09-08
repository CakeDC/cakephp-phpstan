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

namespace App\View\Cell;

use Cake\View\Cell;

class MyTestLoadCell extends Cell
{
    /**
     * Test for TableLocatorDynamicReturnTypeExtension with loadModel
     *
     * @return void
     */
    public function sample()
    {
        $article = $this->fetchTable('VeryCustomize00009Articles')
            ->newSample();
        $this->set('article', $article);
    }
}
