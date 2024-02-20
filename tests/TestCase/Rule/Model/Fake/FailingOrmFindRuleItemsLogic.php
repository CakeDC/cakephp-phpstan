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

class FailingOrmFindRuleItemsLogic //@codingStandardsIgnoreLine
{
    use LocatorAwareTrait;

    /**
     * @return void
     */
    public function process()
    {
        /** @var \App\Model\Table\NotesTable $Table */
        $Table = $this->getTableLocator()->get('Notes');
        $Table->find(type: 'all');
        $Table->find('all', ...[
           'order' => ['Notes.id' => 'DESC'],
        ]);
        $Table->find('all', ...[
           'select' => ['Notes.id', 'Notes.name'],
        ]);
        $Table->find('all', order: ['Notes.name' => 'ASC']);//Good
        $Table->find(//bad information
            'all',
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
        $Table->find(
            'all', //Good options
            select: ['Notes.id', 'Notes.note', 'Notes.created'],
            conditions: ['Notes.active' => 1],
            order: ['Notes.id' => 'DESC'],
            limit: 15,
            offset: 3,
            group: ['Notes.type'],
            contain: ['Users'],
            page: 3
        );
        $Table->find(
            'all', //some good options but not all
            select: ['Notes.id', 'Notes.note', 'Notes.created'],
            conditions: false, //bad
            order: ['Notes.id' => 'DESC'],
            limit: new stdClass(), //bad
            offset: 3,
            group: true, //bad
            contain: ['Users'],
            page: 3
        );
        $query = $Table->find();
        $query->find(//bad information
            'all',
            select: false,
            conditions: new stdClass(),
            offset: '23',
        );
        $Table->Users->find(
            'all',
            select: ['Notes.id', 'Notes.note', 'Notes.created'],
            conditions: false, //bad
            order: ['Notes.id' => 'DESC'],
            limit: new stdClass(), //bad
            offset: 3,
            page: '22',
        );
    }
}
