<?php
declare(strict_types=1);

/**
 * Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2024, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\PHPStan\Test\TestCase\Rule\Model;

use CakeDC\PHPStan\Rule\Model\EntityStrictPropertyAccessRule;
use PHPStan\Node\Printer\ExprPrinter;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class EntityStrictPropertyAccessRuleTest extends RuleTestCase
{
    /**
     * @return \PHPStan\Rules\Rule
     */
    protected function getRule(): Rule
    {
        $printer = $this->getContainer()->getByType(ExprPrinter::class);
        $reflectionProvider = $this->getContainer()->getByType(ReflectionProvider::class);
        // getRule() method needs to return an instance of the tested rule
        return new EntityStrictPropertyAccessRule($printer, $reflectionProvider);
    }

    /**
     * @return void
     */
    public function testRule(): void
    {
        // first argument: path to the example file that contains some errors that should be reported by MyRule
        // second argument: an array of expected errors,
        // each error consists of the asserted error message, and the asserted error file line
        $this->analyse([__DIR__ . '/Fake/FailingUser.php'], [
            [
                'Modifying property "first_name" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'first_name\', \'John\') for proper dirty tracking.',
                35, // asserted error line
            ],
            [
                'Modifying property "last_name" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'last_name\', \'Doe\') for proper dirty tracking.',
                36, // asserted error line
            ],
            [
                'Modifying property "password" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'password\', \'0000000\') for proper dirty tracking.',
                37, // asserted error line
            ],
            [
                'Modifying property "is_active" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'is_active\', true) for proper dirty tracking.',
                38, // asserted error line
            ],
            [
                'Modifying property "id" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'id\', 100) for proper dirty tracking.',
                39, // asserted error line
            ],
            [
                'Modifying property "rate" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'rate\', 9.5) for proper dirty tracking.',
                40, // asserted error line
            ],
            [
                'Modifying property "first_name" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'first_name\', $name) for proper dirty tracking.',
                43, // asserted error line
            ],
            [
                'Modifying property "password" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'password\', static::FIELD_ID) for proper dirty tracking.',
                44, // asserted error line
            ],
            [
                'Modifying property "role" in entity class "CakeDC\PHPStan\Test\TestCase\Rule\Model\Fake\FailingUser" is not allowed. Use $this->set(\'role\', \'user\') for proper dirty tracking.',
                47, // asserted error line
            ],
        ]);
    }
}
