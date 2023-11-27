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

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * @method \App\Model\Entity\Note|\Cake\Datasource\EntityInterface get($primaryKey, $options = [])
 * @property \App\Model\Table\VeryCustomize00009ArticlesTable&\Cake\ORM\Association\HasMany $VeryCustomize00009Articles
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 */
class NotesTable extends Table
{
    /**
     * @return string[]
     */
    public function warning(): array
    {
        $entity = $this->get(10);
        if ($entity->note === 'Test') {
            $entity = $this->newEmptyEntity();
            $entity = $this->patchEntity($entity, ['note' => 'My Warning new']);
            $entity->user_id = 1;
            $entity = $this->saveOrFail($entity);
        }

        return [
            'type' => 'warning',
            'note' => $entity->note,
        ];
    }
}
