<?php
declare(strict_types=1);

/**
 * Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\Locator\LocatorAwareTrait;

class WarningComponent extends Component
{
    use LocatorAwareTrait;

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
        $this->fetchTable('Users')->blockOld();
    }
}
