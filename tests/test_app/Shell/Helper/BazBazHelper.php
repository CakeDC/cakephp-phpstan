<?php

namespace App\Shell\Helper;

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
        $this->_io->success('Sample text');
    }

    /**
     * Sample foo method.
     *
     * @return string
     */
    public function foo(): string
    {
        return 'sample text';
    }
}
