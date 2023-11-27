<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\I18n\DateTime;
use Cake\ORM\Table;

class UsersTable extends Table
{
    /**
     * @param \App\Model\Entity\User $user
     * @return \App\Model\Entity\User
     */
    public function logLastLogin(User $user): User
    {
        $user->last_login = new DateTime();

        return $this->saveOrFail($user);
    }
}
