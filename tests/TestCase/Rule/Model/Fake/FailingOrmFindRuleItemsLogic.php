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
        $Table->find('list');
        $Table->find('list', keyField: 'id', valueField: 'name', groupField: 'parent_id');
        $Table->find('list', groupField: 'name');
        $Table->find('list', groupField: false);
        $Table->find(
            'list',
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
            keyField: false,
            valueField: new stdClass(),
            groupField: true,
        );
        $Table->find()->find('list', groupField: 'name');
        $Table->find()->find('list', groupField: false);
        $Table->find()->find(
            'list',
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
            keyField: false,
            valueField: new stdClass(),
            groupField: true,
        );
        $Table->find('threaded', keyField: 'id', parentField: 'parent_id');
        $Table->find('threaded', parentField: 'parent_id');
        $Table->find('threaded', parentField: false);
        $Table->find(
            'threaded',
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
            keyField: false,
            parentField: true,
        );
        $Table->find('threaded');//all options are optional, this is okay.
        $Table->find(
            'featured', //custom finder is known
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
            year: 2024,
            fun: true
        );
        $Table->find(
            'featured', //custom finder is known but options are invalid
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
            year: 10.0,
            fun: 1
        );
        $Table->find(
            'featured', //custom finder is known but required options are missing only have basic find options
            fields: ['Notes.id', 'Notes.note', 'Notes.created'],
        );
        $Table->find(
            'featured', //custom finder is known but required options are missing
        );
        $Table->find(
            'unkonwn',
        );
        $Table->find('legacy');//Legacy should ignore params check
        $Table->find('legacy', [//Legacy should ignore params check
            'sort' => ['Notes.note' => 'ASC'],
        ]);
        $Table->find('legacy', [//Legacy should ignore params check
            'sort' => ['Notes.note' => 'ASC'],
            'type' => 'featured',
            'active' => false,
        ]);
        $Table->find('optionsPacked');
        $Table->find('optionsPacked', [//Legacy should ignore params check
            'sort' => ['Notes.note' => 'ASC'],
        ]);
        $Table->find('optionsPacked', [//Legacy should ignore params check
            'sort' => ['Notes.note' => 'ASC'],
            'labelField' => 'id',
        ]);
        $Table->find('optionsPacked', [//Legacy should ignore params check
            'sort' => ['Notes.note' => 'ASC'],
            'labelField' => 'id',
        ]);
        $Table->find(
            'optionsPacked',
            sort: ['Notes.note' => 'ASC'],
            labelField: 'id'
        );
        $Table->find(
            'optionsPacked',
            sort: ['Notes.note' => 'ASC'],
            labelField: 'id'
        );
        $Table->find('argsPacked');
        $Table->find(
            'argsPacked',
            sort: ['Notes.note' => 'ASC'],
            groupLabel: 'type'
        );
        $Table->find('argsPacked', [
            'sort' => ['Notes.note' => 'ASC'],
            'groupLabel' => 'id',
        ]);
        $Table->find('twoArgsButNotLegacy', [
            'sort' => ['Notes.note' => 'ASC'],
            'myType' => 'featured',
        ]);
        $Table->find('twoArgsButNotLegacy', [
            'sort' => ['Notes.note' => 'ASC'],
        ]);
        $Table->find(
            'twoArgsButNotLegacy',
            sort: ['Notes.note' => 'ASC'],
            myType: 'featured'
        );
        $Table->find('twoArgsButNotLegacy');
        $Table->find(
            'twoArgsButNotLegacy',
            sort: ['Notes.note' => 'ASC'],
            myType: 19
        );
        $field = $Table->getTypeTestTwoArgsButNotLegacy();
        $value = 'featured';
        $Table->find('twoArgsButNotLegacy', [$field => $value]);
        $Table->find('twoArgsButNotLegacy', [$field => 'test']);
        $Table->find('twoArgsButNotLegacy', [$Table->getTypeTestTwoArgsButNotLegacy() => $value]);
        $Table->find('twoArgsButNotLegacy', [$Table->getTypeTestTwoArgsButNotLegacy() => 'sample']);
        $Table->find('twoArgsButNotLegacy', ...[$field => $value]);
        $Table->find('twoArgsButNotLegacy', ...[$field => 'test']);
        $Table->find('twoArgsButNotLegacy', ...[$Table->getTypeTestTwoArgsButNotLegacy() => $value]);
        $Table->find('twoArgsButNotLegacy', ...[$Table->getTypeTestTwoArgsButNotLegacy() => 'sample']);
    }
}
