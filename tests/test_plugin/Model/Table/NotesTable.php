<?php
declare(strict_types=1);

/**
 * Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\MyPlugin\Model\Table;

use Cake\ORM\Table;

class NotesTable extends Table
{
    /**
     * @return string[]
     */
    public function complete(): array
    {
        return ['note' => 'completed'];
    }

    /**
     * @return string[]
     */
    public function info(): array
    {
        return ['note' => 'info'];
    }

    /**
     * @return string[]
     */
    public function notice(): array
    {
        return ['note' => 'notice'];
    }
}
