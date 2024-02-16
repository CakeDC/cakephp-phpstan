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
use Cake\ORM\Table;

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
        ]);
    }
}
