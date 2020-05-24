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

namespace App\Model\Table;

use Cake\ORM\Table;

class VeryCustomize00009ArticlesTable extends Table
{
    /**
     * @param \Cake\Datasource\EntityInterface $article
     *
     * @return bool
     */
    public function fixArticle($article)
    {
        //logic to clear article

        return true;
    }
}
