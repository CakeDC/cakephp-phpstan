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

namespace App\Mailer;

use Cake\Mailer\Mailer;

class MyTestLoadMailer extends Mailer
{
    /**
     * Test for TableLocatorDynamicReturnTypeExtension with loadModel
     *
     * @return void
     */
    protected function sampleLoading()
    {
        $article = $this->fetchTable('VeryCustomize00009Articles')
            ->newSample();
        $this->viewBuilder()->setVar('article', $article);
    }
}
