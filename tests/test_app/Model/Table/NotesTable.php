<?php
declare(strict_types=1);

/**
 * Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * @method \App\Model\Entity\Note|\Cake\Datasource\EntityInterface get(mixed $primaryKey, array|string $finder = 'all',CacheInterface|string|null $cache = null,\Closure|string|null $cacheKey = null, mixed ...$args)
 * @property \App\Model\Table\VeryCustomize00009ArticlesTable&\Cake\ORM\Association\HasMany $VeryCustomize00009Articles
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\UsersTable> $Users
 */
class NotesTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('notes');
        $this->setDisplayField('note');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', ['dependent' => true, 'className' => MyUsersTable::class]);
    }

    /**
     * @return string[]
     */
    public function warning(): array
    {
        $entity = $this->get(10, cache: 'my_cache');
        if ($entity->note === 'Test') {
            $entity = $this->newEmptyEntity();
            $entity = $this->patchEntity($entity, ['note' => 'My Warning new']);
            $entity->user_id = 1;
            $this->Users->find('all', order: ['Users.id' => 'DESC'], limit: 12);
            $entity = $this->saveOrFail($entity);
        }

        return [
            'type' => 'warning',
            'note' => $entity->note,
        ];
    }
}
