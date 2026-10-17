<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake;

use Cake\ORM\Entity;

class BaseFailingUser extends Entity
{
    public protected(set) ?string $role;
}
