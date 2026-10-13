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

class MyTestExtendedCommand extends MyTestLoadCommand
{
    /**
     * @inheritDoc
     */
    public function execute(): ?int
    {
        $this->fetchTable('VeryCustomize00009Articles')->newSample();
        $this->io->helper('MyHeading')->headingOne('Sample Text 02');
        $this->getMailer('MyTestLoad')->testing();

        return null;
    }
}
