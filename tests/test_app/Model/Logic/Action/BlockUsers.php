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

/**
 * Class DeleteUsers
 *
 * @package App\Model\Logic\Action
 */
class BlockUsers extends WarnUsers
{
    /**
     * This object's default table alias.
     *
     * @var string|null
     */
    protected ?string $defaultTable = 'Users';

    /**
     * @throws \Exception
     * @return void
     */
    public function process()
    {
        parent::process();

        $this->fetchTable()->blockOld();
    }
}
