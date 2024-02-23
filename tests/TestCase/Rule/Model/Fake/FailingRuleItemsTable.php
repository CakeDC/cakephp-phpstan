<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2023, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake;

use App\Model\Table\MyUsersTable;
use App\Model\Table\UsersTable;
use App\Model\Table\VeryCustomize00009ArticlesTable;
use Cake\ORM\Association\HasMany;
use Cake\ORM\Association\HasOne;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use stdClass;

class FailingRuleItemsTable extends Table//@codingStandardsIgnoreLine
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('items');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');//valid name
        $this->addBehavior('Timtamp');//Invalid name
        $this->addBehavior('Tree', [
            'className' => 'MyTreeBehavior',//invalid name
        ]);
        $this->addBehavior('Translate', [
            'className' => 'Cake\Behavior\TranslateBehavior',//invalid full className
        ]);
        $this->addBehavior('CounterCache', [
            'className' => 'Cake\ORM\Behavior\CounterCacheBehavior',//valid className
        ]);
        $this->addBehavior('SampleTestCustomMethod');//valid behavior from test_app

        $this->belongsTo('Notes');
        $this->belongsTo('Fantasies');//Invalid model
        $this->belongsTo('Articles', [
            'className' => VeryCustomize00009ArticlesTable::class,
            'cascadeCallbacks' => true,
            'conditions' => ['Articles.active' => 1],
            'dependent' => true,
            'finder' => 'recent',
            'bindingKey' => ['my_binding_key'],
            'foreignKey' => 'my_foreign_key',
            'joinType' => 'INNER',
            'tableLocator' => new TableLocator(),
            'propertyName' => 'my_property_name',
            'sourceTable' => $this,
            'targetTable' => TableRegistry::getTableLocator()->get('Articles'),
        ]);
        $this->belongsTo('Users', [
            'className' => false,
            'cascadeCallbacks' => 1,//Can't be integer, it should be bool
            'conditions' => 'Users.active = 1',//Can't be string, it should be Closure or array
            'dependent' => 0,//Must be
            'finder' => fn () => 'f',
            'bindingKey' => 10,
            'foreignKey' => 11,
            'joinType' => 12,
            'tableLocator' => new stdClass(),
            'propertyName' => 13,
            'sourceTable' => 'Users',
            'targetTable' => new stdClass(),
            'strategy' => false,
        ]);
        $this->hasMany('MyUsers', [
            'saveStrategy' => HasMany::SAVE_REPLACE,
            'sort' => ['MyUsers.first_name' => 'ASC'],
        ]);
        $this->hasMany('BadUsers', [
            'className' => MyUsersTable::class,
            'saveStrategy' => 10,
            'sort' => true,
        ]);
        $this->belongsToMany('GoodUsers', [
            'className' => UsersTable::class,
            'targetForeignKey' => 'my_target_foreign_key',
            'through' => 'MyUsers',
            'saveStrategy' => HasMany::SAVE_REPLACE,
            'sort' => ['MyUsers.first_name' => 'ASC'],
            'joinTable' => 'my_valid_junction_table',
        ]);
        $this->belongsToMany('SadUsers', [
            'className' => UsersTable::class,
            'targetForeignKey' => fn () => 10,
            'through' => new stdClass(),
            'saveStrategy' => fn () => 'na',
            'sort' => false,
            'joinTable' => fn () => 'my_users_failing',
        ]);
        $this->hasOne('MainArticles', [
            'className' => VeryCustomize00009ArticlesTable::class,
            'cascadeCallbacks' => true,
            'conditions' => ['MainArticles.is_main' => 1],
            'dependent' => true,
            'finder' => 'recent',
            'bindingKey' => ['my_binding_key'],
            'foreignKey' => 'my_foreign_key',
            'joinType' => 'INNER',
            'tableLocator' => new TableLocator(),
            'propertyName' => 'my_property_name',
            'sourceTable' => $this,
            'targetTable' => TableRegistry::getTableLocator()->get('Articles'),
        ]);
        $this->hasOne('ParentUsers', [
            'className' => false,
            'cascadeCallbacks' => 1,//Can't be integer, it should be bool
            'conditions' => 'parent_id = id',//Can't be string, it should be Closure or array
            'dependent' => 0,//Must be
            'finder' => fn () => 'f',
            'bindingKey' => 10,
            'foreignKey' => 11,
            'joinType' => 12,
            'tableLocator' => new stdClass(),
            'propertyName' => 13,
            'sourceTable' => 'Users',
            'targetTable' => new stdClass(),
            'strategy' => false,
            'saveStrategy' => HasMany::SAVE_REPLACE,
            'sort' => ['MyUsers.first_name' => 'ASC'],
            'joinTable' => 'my_valid_junction_table',
            'somethingElse' => 'an_invalid_option_key',
        ]);
        $this->associations()->load(HasMany::class, 'CrazyUsers');
        $this->behaviors()->load('Transmate', [
            'className' => 'Cake\Behavior\TransmateBehavior',//invalid full className
        ]);
        $this->associations()->load(HasMany::class, 'MasterUsers', [
            'className' => MyUsersTable::class,//valid
            'conditions' => ['MasterUsers.is_master' => true],
        ]);
        $this->behaviors()->load('Tree');//valid
        $this->associations()->load(HasOne::class, 'PalUsers', [
            'className' => false,
            'cascadeCallbacks' => 1,//Can't be integer, it should be bool
            'conditions' => 'parent_id = id',//Can't be string, it should be Closure or array
            'dependent' => 0,//Must be
            'finder' => fn () => 'f',
            'bindingKey' => 10,
            'foreignKey' => 11,
            'joinType' => 12,
            'tableLocator' => new stdClass(),
            'propertyName' => 13,
            'sourceTable' => 'Users',
            'targetTable' => new stdClass(),
            'strategy' => false,
            'saveStrategy' => HasMany::SAVE_REPLACE,
            'sort' => ['MyUsers.first_name' => 'ASC'],
            'joinTable' => 'my_valid_junction_table',
            'somethingElse' => 'an_invalid_option_key',
        ]);
        $this->associations()->load(HasOne::class, 'FunArticles', [
            'className' => VeryCustomize00009ArticlesTable::class,
            'cascadeCallbacks' => true,
            'conditions' => ['MainArticles.is_sad' => false],
            'dependent' => true,
            'finder' => 'recent',
            'bindingKey' => ['my_binding_key'],
            'foreignKey' => 'my_foreign_key',
            'joinType' => 'INNER',
            'tableLocator' => new TableLocator(),
            'propertyName' => 'my_property_name',
            'sourceTable' => $this,
            'targetTable' => TableRegistry::getTableLocator()->get('Articles'),
        ]);
        $this->associations()->load(HasOne::class, 'Notes');
        $this->hasOne('BakedArticles', [
            'cascadeCallbacks' => true,
            'conditions' => ['BakedArticles.baked' => 1],
        ])->setClassName(VeryCustomize00009ArticlesTable::class);

        $this->hasOne('CakeArticles', [
            'cascadeCallbacks' => true,
            'conditions' => ['CakeArticles.category_id' => 10],
        ])->setClassName('Articles');
        $this->hasOne('CakeArticles')
            ->setFinder('myFinder')
            ->setClassName('SomeArticles');
        $this->associations()->load(HasOne::class, 'CleanArticles', [
            'cascadeCallbacks' => true,
            'conditions' => ['CleanArticles.clean' => 1],
        ])->setClassName(VeryCustomize00009ArticlesTable::class);
    }
}
