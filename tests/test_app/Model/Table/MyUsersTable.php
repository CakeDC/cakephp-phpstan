<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method iterable<\App\Model\Entity\User>|false saveMany(iterable<\App\Model\Entity\User> $entities, $options = [])
 * @method iterable<\App\Model\Entity\User> saveManyOrFail(iterable<\App\Model\Entity\User> $entities, $options = [])
 * @method iterable<\App\Model\Entity\User>|false deleteMany(iterable<\App\Model\Entity\User> $entities, $options = [])
 * @method iterable<\App\Model\Entity\User> deleteManyOrFail(iterable<\App\Model\Entity\User> $entities, $options = [])
 */
class MyUsersTable extends Table
{
}
