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
     * @inheritDoc
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
        /**
         * @var \Cake\ORM\Entity $article
         */
        $article = $this->findByTitle('sample')->first();
        $article->set('title', 'sample two');
        /**
         * @var \Cake\ORM\Entity $article
         */
        $article = $this->findByTitleAndActive('sample', true)->first();
        $article->set('title', 'sample two');

        // Test actual (non-magic) findOrCreateBySku method with specific signature (issue #55)
        // When called directly on the table (not through association), PHPStan should use the native method
        $entityOrCreate = $this->findOrCreateBySku('TEST-SKU', 'Test Value');
        $entityOrCreate->set('title', 'Updated Title');

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
            ],
        );
    }

    /**
     * Custom non-magic findOrCreateBySku method with specific signature
     *
     * @param string $sku
     * @param string $foo
     * @return \Cake\Datasource\EntityInterface
     */
    public function findOrCreateBySku(string $sku, string $foo)
    {
        /** @var \Cake\ORM\Entity|null $entity */
        $entity = $this->findBySku($sku)->first();
        if ($entity === null) {
            $entity = $this->newEntity(['sku' => $sku, 'foo' => $foo]);
            $entity = $this->saveOrFail($entity);
        }

        return $entity;
    }
}
