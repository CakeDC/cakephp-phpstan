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

namespace App\Model\Table;

use Cake\ORM\Entity;
use Cake\ORM\Table;

/**
 * Class VeryCustomize00009ArticlesTable
 *
 * @mixin   \App\Model\Behavior\SampleTestCustomMethodBehavior
 * @package App\Model\Table
 */
class VeryCustomize00009ArticlesTable extends Table
{
    /**
     * @param string[] $config Configuration options passed to the constructor
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->behaviors()->load('SampleTestCustomMethod');
    }

    /**
     * @param \Cake\Datasource\EntityInterface $article
     * @return bool
     */
    public function fixArticle($article)
    {
        //logic to clear article
        $this->fakeData();
        $article = $this->findByTitle('sample')->first();
        $article->set('title', 'sample two');

        return true;
    }

    /**
     * Return a new sample article
     *
     * @return \Cake\ORM\Entity
     */
    public function newSample()
    {
        return new Entity(
            [
            'title' => 'This is my title',
            'content' => 'Sample content for test',
            ]
        );
    }
}
