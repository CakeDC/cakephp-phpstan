<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake;

use App\Model\Entity\Note;
use Cake\ORM\Locator\LocatorAwareTrait;
use SplFixedArray;

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
        $note2 = $entity[Note::FIELD_NOTE];
        $matching = $entity['_matchingData'];//allowed access to _matchingData
        $matchingUser = $entity['_matchingData']['Users'];//allowed access to _matchingData
        $joinData = $entity['_joinData'];//allowed access to _matchingData
        $joinDataPosts = $entity['_joinData']['Posts'];//allowed access to _matchingData
        $ids = $entity['_ids'];//allowed access to _matchingData

        //Unknown entity
        $unknown = $this->fetchTable('UnknownRecords')->get(20);
        $date = $unknown['create'];
        $user = $this->fetchTable('Users')->get(10);
        $user['role'] = 'Admin';
        $array = new SplFixedArray(2);
        $array[0] = 'a';
        $array[1] = 'b';

        return [
            'userId' => $entity['user_id'],
            'note' => $note,
            'noted' => $noted,
            'notable' => $notable,
            'date' => $date,
            'note2' => $note2,
            'matchingData' => $matching,
            'matchingUser' => $matchingUser,
            'joinData' => $joinData,
            'joinDataPosts' => $joinDataPosts,
            'ids' => $ids,
        ];
    }
}
