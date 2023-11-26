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

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Log\Log;

class NotesController extends Controller
{
    /**
     * Test TableEntityDynamicReturnTypeExtension to use correct entity type (App\Model\Entity\Node)
     * Should not have errors when accessig note property after NotesTable::newEntity, NotesTable::patchEntity
     * NotesTable::newEmptyEntity, NotesTable::saveOrFail, NotesTable::findOrCreate
     * NotesTable::newEntities, NotesTable::patchEntities, NotesTable::saveManyOrFail
     *
     * @return void
     */
    public function add()
    {
        $entity = $this->fetchTable()->newEntity(['user_id' => 1]);
        $entity->note = 'My Note';
        Log::info('Accessing note after newEntity call' . $entity->note);

        $entityPatched = $this->fetchTable()->patchEntity($entity, ['user_id' => 10, 'note' => 'Other note']);
        Log::info('Accessing note after patchEntity call' . $entityPatched->note);

        $emptyEntity  = $this->fetchTable()->newEmptyEntity();
        $emptyEntity->note = 'My Empty new entity test';
        Log::info('Accessing note after newEmptyEntity call' . $emptyEntity->note);

        $entitySaved = $this->fetchTable()->saveOrFail($entityPatched);
        Log::info('Accessing note after saveOrFail call' . $entitySaved->note);

        $findOrCreate = $this->fetchTable()->findOrCreate(['user_id' => 1, 'note' => 'My Note']);
        Log::info('Accessing note after findOrCreate call' . $findOrCreate->note);
    }

    /**
     * Test ControllerFetchTableDynamicReturnTypeExtension with fetchTable using controller's className to extract
     * correct table class
     * Test TableEntityDynamicReturnTypeExtension with Notes::get to use correct entity type (App\Model\Entity\Node)
     *
     * @return void
     */
    public function addWarning()
    {
        $data = $this->fetchTable()->warning();
        $note = $this->fetchTable()->get(1)->note;
        $otherNote = $this->fetchTable()->get(2);

        $data['note2'] = [
            'note' => $otherNote->note,
            'user_id' => $otherNote->user_id,
        ];
        $this->set(compact('data', 'note',));
    }
}
