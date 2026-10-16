<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string $role
 * @property \Cake\I18n\DateTime $last_login
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 */
class User extends Entity
{
    /**
     * @var string|null
     */
    public protected(set) ?string $name;

    /**
     * @var string|null
     */
    public protected(set) ?string $password {
        set(?string $value) {
            $this->password = $value ? password_hash($value, PASSWORD_DEFAULT) : null;
        }
    }
}
