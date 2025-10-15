<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use Cake\Database\TypeFactory;

class TypeFactoryCorrectUsage
{
    public function testDateTimeTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('datetime');
        $type->setUserTimezone('America/New_York');
    }

    public function testTimestampTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('timestamp');
        $type->setUserTimezone('UTC');
    }

    public function testDateTimeFractionalTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('datetimefractional');
        $type->setUserTimezone('UTC');
    }

    public function testDateTimeTimezoneTypeWithSetUserTimezone(): void
    {
        $type = TypeFactory::build('timestamptimezone');
        $type->setUserTimezone('UTC');
    }

    public function testDateTypeWithSetLocaleFormat(): void
    {
        $type = TypeFactory::build('date');
        $type->setLocaleFormat('yyyy-MM-dd');
    }

    public function testTimeTypeWithSetLocaleFormat(): void
    {
        $type = TypeFactory::build('time');
        $type->setLocaleFormat('HH:mm:ss');
    }
}
