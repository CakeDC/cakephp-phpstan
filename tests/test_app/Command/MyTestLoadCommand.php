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

namespace App\Command;

use Cake\Command\Command;
use Cake\Mailer\MailerAwareTrait;

class MyTestLoadCommand extends Command
{
    use MailerAwareTrait;

    /**
     * @inheritDoc
     */
    public function execute(): ?int
    {
        $this->fetchTable('VeryCustomize00009Articles')->newSample();

        $this->io->helper('progress')->increment(1);
        $this->io->out($this->io->helper('BazBaz')->foo());
        $this->io->helper('MyHeading')->headingOne('Sample Text 01');
        $this->getMailer('MyTestLoad')->testing();

        return null;
    }
}
