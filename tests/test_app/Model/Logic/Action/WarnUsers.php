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

namespace App\Model\Logic\Action;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Class DeleteUsers
 *
 * @package App\Model\Logic\Action
 */
class WarnUsers
{
    use LocatorAwareTrait;

    /**
     * @throws \Exception
     * @return void
     */
    public function process()
    {
        $this->fetchTable('Notes')
            ->warning();
    }
}
