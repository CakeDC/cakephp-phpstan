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

namespace App\Controller;

use Cake\Controller\Controller;

class MyTestLoadComponentController extends Controller
{
    /**
     * Testing ComponentLoadDynamicReturnTypeExtension
     *
     * @return void
     */
    public function myTest()
    {
        $this->loadComponent('Flash')->success('Something');
    }

    /**
     * Test TableLocatorDynamicReturnTypeExtension with load model
     *
     * @return void
     */
    public function lately()
    {
        //getLatestOne is defined at HelloWorld101010ArticlesTable
        $latest = $this->fetchTable('CakeDC/MyPlugin.HelloWorld101010Articles')
            ->getLatestOne();
        $this->set('latest', $latest);
    }
}
