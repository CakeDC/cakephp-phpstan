<?php

/**
 * Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2020, Cake Development Corporation (https://www.cakedc.com)
 *  @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\MyPlugin\Model\Logic\Action;

use Cake\ORM\TableRegistry;

/**
 * Class ClearArticles
 *
 * @package CakeDC\MyPlugin\Model\Logic\Action
 */
class ClearArticles
{
    /**
     * @throws \Exception
     * @return void
     */
    public function process()
    {
        $Table = TableRegistry::getTableLocator()->get('CakeDC/MyPlugin.HelloWorld101010Articles');
        $records = $Table
            ->find('all')
            ->where(['created <=' => new \DateTime('-30 days')])
            ->all();

        foreach ($records as $record) {
            $Table->clearArticle($record);
        }
    }
}
