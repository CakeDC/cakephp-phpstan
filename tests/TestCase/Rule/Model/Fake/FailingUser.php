<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake;

use Cake\ORM\Entity;

class FailingUser extends Entity
{
    public const string FIELD_ID = 'id';

    public protected(set) int $id;
    public protected(set) float $rate;
    public protected(set) ?string $email;
    public protected(set) bool $is_active = false;
    public protected(set) ?string $first_name;
    public protected(set) ?string $last_name;

    public protected(set) ?string $password {//phpcs:ignore
        set(?string $value) {//phpcs:ignore
            $this->password = $value ? password_hash($value, PASSWORD_DEFAULT) : null;//phpcs:ignore
        }
    }

    public string $full_name {//phpcs:ignore
        get => $this->first_name . ' ' . $this->last_name;//phpcs:ignore
    }

    /**
     * @return void
     */
    public function fail()
    {
        //Error properties should be set using $this>set method
        $this->first_name = 'John';
        $this->last_name = 'Doe';
        $this->password = '0000000';
        $this->is_active = true;
        $this->id = 100;
        $this->rate = 9.5;
        $this['is_active'] = true;//This is okay
        $name = 'James';
        $this->first_name = $name;
        $this->password = static::FIELD_ID;
    }
}
