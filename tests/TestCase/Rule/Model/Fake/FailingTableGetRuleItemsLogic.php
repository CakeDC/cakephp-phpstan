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

use Cake\ORM\Locator\LocatorAwareTrait;
use stdClass;

class FailingTableGetRuleItemsLogic //@codingStandardsIgnoreLine
{
    use LocatorAwareTrait;

    /**
     * @return void
     */
    public function process()
    {
        /** @var \App\Model\Table\NotesTable $Table */
        $Table = $this->getTableLocator()->get('Notes');
        $Table->get(1, finder: 'all', cache: 'my_cache');
        $Table->get(2, 'all', ...[
           'order' => ['Notes.id' => 'DESC'],
        ]);
        $Table->get(1, 'all', 'other_cache', ...[
           'order' => ['Notes.id' => 'DESC'],
        ]);
        $Table->get(1, order: ['Notes.name' => 'ASC']);//Good
        $Table->get(
            1, //Bad options
            select: true,
            fields: false,
            conditions: new stdClass(),
            where: true,
            join: 'Users',
            order: false,
            orderBy: true,
            limit: 'Som',
            offset: 'Nothing',
            group: false,
            groupBy: false,
            having: new stdClass(),
            contain: true,
            page: 'Other'
        );
        $Table->get(
            1, //Good options
            select: ['Notes.id', 'Notes.note', 'Notes.created'],
            conditions: ['Notes.active' => 1],
            order: ['Notes.id' => 'DESC'],
            limit: 15,
            offset: 3,
            group: ['Notes.type'],
            contain: ['Users'],
            page: 3
        );
        $Table->get(1, 'all', ...[
           'order' => false,
           'limit' => 'Something',
        ]);
        $Table->get(1, 'all', 'other_cache', null, ...[
           'conditions' => new stdClass(),
           'offset' => 'Nothing',
        ]);
        $Table->get(1, 'all', 'other_cache', null, [
           'fields' => false,
           'contain' => true,
        ]);
    }
}
