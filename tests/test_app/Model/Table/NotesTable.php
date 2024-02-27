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

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;

/**
 * @method \App\Model\Entity\Note|\Cake\Datasource\EntityInterface get(mixed $primaryKey, array|string $finder = 'all',CacheInterface|string|null $cache = null,\Closure|string|null $cacheKey = null, mixed ...$args)
 * @property \App\Model\Table\VeryCustomize00009ArticlesTable&\Cake\ORM\Association\HasMany $VeryCustomize00009Articles
 * @property \Cake\ORM\Association\BelongsTo<\App\Model\Table\UsersTable> $Users
 * @property \Cake\ORM\Association\BelongsTo&\App\Model\Table\UsersTable $MyUsers
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
        $this->belongsTo('MyUsers', [
            'dependent' => true,
        ]);
    }

    /**
     * @return string[]
     */
    public function warning(): array
    {
        $user = $this->MyUsers->get(1);
        $user->name = 'John';
        $this->MyUsers->logLastLogin($user);
        $article = $this->MyUsers->Articles->newSample();
        $article->id = '002';
        $entity = $this->get(10, cache: 'my_cache');
        if ($entity->note === 'Test') {
            $entity = $this->newEmptyEntity();
            $entity->user = $user;
            $entity = $this->patchEntity($entity, ['note' => 'My Warning new']);
            $entity->user_id = 1;
            $this->Users->find('all', order: ['Users.id' => 'DESC'], limit: 12);
            $entity = $this->saveOrFail($entity);
        }
        $this->find('optionsPacked');
        $this->find(
            'twoArgsButNotLegacy',
            sort: ['Notes.note' => 'ASC'],
            myType: 'featured'
        );
        $this->find('argsPacked');

        return [
            'type' => 'warning',
            'note' => $entity->note,
        ];
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param string|int $year
     * @param bool $fun
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findFeatured(SelectQuery $query, string|int $year, bool $fun): SelectQuery
    {
        $where = [
            'year <=' => $year,
        ];
        if ($fun === true) {
            $where[] = $query->newExpr()->in(
                'type',
                ['funny_stuff', 'funny_songs', 'funny_messages']
            );
        }

        return $query->where($where)
            ->orderBy(['Notes.created' => 'DESC']);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param string $myType
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findTwoArgsButNotLegacy(SelectQuery $query, string $myType): SelectQuery
    {
        $where = [
            'year <=' => 2010,
            'type' => $myType,
        ];

        return $query->where($where)
            ->orderBy(['Notes.created' => 'DESC']);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param array<string, mixed> $options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findLegacy(SelectQuery $query, array $options): SelectQuery
    {
        return $query
            ->where([
                'type' => $options['type'] ?? 'normal',
                'active' => $options['active'] ?? false,
            ]);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param mixed ...$options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findOptionsPacked(SelectQuery $query, mixed ...$options): SelectQuery
    {
        return $query->select([
            $options['labelField'] ?? 'note',
        ]);
    }

    /**
     * @param \Cake\ORM\Query\SelectQuery $query
     * @param mixed ...$args
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findArgsPacked(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->select([
            $args['groupLabel'] ?? 'note',
        ]);
    }

    /**
     * @return string
     */
    public function getTypeTestTwoArgsButNotLegacy(): string
    {
        return 'myType';
    }
}
