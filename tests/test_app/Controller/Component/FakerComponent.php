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

use App\Model\Entity\User;
use Cake\Controller\Component;

class FakerComponent extends Component
{
    /**
     * @return \App\Model\Entity\User
     */
    public function fakeUser(): User
    {
        /**
         * @var \App\Controller\NotesController $controller
         */
        $controller = $this->getController();

        return $controller->fetchTable('Users')->newEntity(['id' => 10]);
    }
}
