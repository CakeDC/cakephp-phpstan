<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use Cake\Database\TypeFactory;

class TypeFactoryIncorrectUsage
{
    public function testIntegerTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('integer');
        $type->setUserTimezone('UTC');
    }

    public function testStringTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('string');
        $type->setUserTimezone('UTC');
    }

    public function testBoolTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('boolean');
        $type->setUserTimezone('UTC');
    }

    public function testJsonTypeWithNonExistentMethod(): void
    {
        $type = TypeFactory::build('json');
        $type->nonExistentMethod();
    }
}
