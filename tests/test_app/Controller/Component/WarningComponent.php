<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class WarningComponent extends Component
{
    /**
     * @return void
     */
    public function testWarning()
    {
        /**
         * @var \App\Controller\NotesController $controller
         */
        $controller = $this->getController();
        $controller->fetchTable()->warning();
    }
}
