<?php

namespace App\Command\Helper;

use Cake\Console\Helper;

class MyHeadingHelper extends Helper
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
     * Output a text as heading
     *
     * @param string $text The text.
     *
     * @return void
     */
    public function headingOne(string $text): void
    {
        $this->_io->out('# ' . $text . ' #');
    }
}
