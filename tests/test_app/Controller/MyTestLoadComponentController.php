<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

class MyTestLoadComponentController extends Controller
{
    /**
     * Test action
     *
     * @return void
     */
    public function myTest()
    {
        $model = TableRegistry::getTableLocator()->get('MyCustomSources');
        $this->loadComponent('Paginator')->paginate($model);
    }
}
