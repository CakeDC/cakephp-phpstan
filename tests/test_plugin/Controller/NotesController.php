<?php
declare(strict_types=1);

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\MyPlugin\Controller;

use Cake\Controller\Controller;

class NotesController extends Controller
{
    /**
     * Test ControllerFetchTableDynamicReturnTypeExtension with fetchTable using controller's className to extract
     * correct table class
     *
     * @return void
     */
    public function addComplete()
    {
        $data = $this->fetchTable()->complete();
        $this->set(compact('data'));
    }
}
