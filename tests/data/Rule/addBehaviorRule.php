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

namespace App\Model\Table;

use Cake\ORM\Table;

class AddBehaviorRuleItemsTable extends Table//@codingStandardsIgnoreLine
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
    }
}
