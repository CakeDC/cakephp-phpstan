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

namespace CakeDC\MyPlugin\Model\Table;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

/**
 * Class HelloWorld101010ArticlesTable
 *
 * @package CakeDC\MyPlugin\Model\Table
 */
class HelloWorld101010ArticlesTable extends Table
{
    /**
     * @param \Cake\Datasource\EntityInterface $article
     *
     * @return bool
     */
    public function clearArticle($article)
    {
        //logic to clear article

        return true;
    }

    /**
     * @return \Cake\Datasource\EntityInterface|array<string, mixed>
     */
    public function getLatestOne()
    {
        return $this->find()->orderDesc('created')->firstOrFail();
    }
}
