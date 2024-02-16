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

use App\Model\Table\VeryCustomize00009ArticlesTable;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

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
            'finder' => fn() => 'f',
            'bindingKey' => 10,
            'foreignKey' => 11,
            'joinType' => 12,
            'tableLocator' => new \stdClass(),
            'propertyName' => 13,
            'sourceTable' => 'Users',
            'targetTable' => new \stdClass(),
            'strategy' => false,
        ]);
    }
}
