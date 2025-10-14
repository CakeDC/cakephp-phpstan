<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\I18n\DateTime;
use Cake\ORM\Table;

/**
 * @property \App\Model\Table\VeryCustomize00009ArticlesTable&\Cake\ORM\Association\HasMany $Articles
 */
class UsersTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->hasMany('Articles', [
            'dependent' => true,
            'className' => VeryCustomize00009ArticlesTable::class,
        ]);
    }

    /**
     * @param \App\Model\Entity\User $user
     * @return \App\Model\Entity\User
     */
    public function logLastLogin(User $user): User
    {
        $user->last_login = new DateTime();

        return $this->saveOrFail($user);
    }

    /**
     * @return void
     */
    public function blockOld(): void
    {
        // Test magic findBy methods on association (issue #51)
        $articleQuery = $this->Articles->findByTitle('Test');
        $articleQuery->first();
    }

    /**
     * @param string $name
     * @return \App\Model\Entity\User
     */
    public function findByNamed(string $name): User
    {
        return new User(['name' => strtolower($name)]);
    }

    /**
     * @param int $foo
     * @return int
     */
    public function findAllByFoo(int $foo): int
    {
        return $foo * 200;
    }
}
