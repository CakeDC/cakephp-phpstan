<?php

/*
 *  Copyright 2020 - 2021, Cake Development Corporation (https://www.cakedc.com)
 *
 *  Licensed under The MIT License
 *  Redistributions of files must retain the above copyright notice.
 *
 *  @copyright Copyright 2020 - 2021, Cake Development Corporation (https://www.cakedc.com)
 *  @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Command\Helper;

use Cake\Console\Helper;

class BazBazHelper extends Helper
{
    /**
     * This method should output content using `$this->_io`.
     *
     * @param array<string> $args The arguments for the helper.
     * @return void
     */
    public function output(array $args): void
    {
        $this->_io->success('Sample heading');
    }

    /**
     * Custom method
     *
     * @return string
     */
    public function foo(): string
    {
        return 'Invoked!';
    }
}
