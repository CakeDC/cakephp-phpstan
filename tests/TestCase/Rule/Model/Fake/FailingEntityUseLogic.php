<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake;

use Cake\ORM\Locator\LocatorAwareTrait;

class FailingEntityUseLogic
{
    use LocatorAwareTrait;

    /**
     * @return array
     */
    public function execute(): array
    {
        /**
         * @var \App\Model\Table\NotesTable $Table
         */
        $Table = $this->fetchTable('Notes');
        $entity = $Table->get(1);
        $note = $entity['note'];
        $notable = 'Notable ' . $entity['note'];
        $noted = $entity->note;

        //Unknown entity
        $unknown = $this->fetchTable('UnknownRecords')->get(20);
        $date = $unknown['create'];
        $user = $this->fetchTable('Users')->get(10);
        $user['role'] = 'Admin';

        return [
            'userId' => $entity['user_id'],
            'note' => $note,
            'noted' => $noted,
            'notable' => $notable,
            'date' => $date,
        ];
    }
}
