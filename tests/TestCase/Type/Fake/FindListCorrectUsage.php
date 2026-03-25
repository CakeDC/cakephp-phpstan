<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Test file for find('list') return type extension.
 */
class FindListCorrectUsage
{
    use LocatorAwareTrait;

    /**
     * Test that find('list') returns array<int|string, string>
     *
     * @return array<int|string, string>
     */
    public function testFindList(): array
    {
        $table = $this->fetchTable('Articles');

        // This should be inferred as array<int|string, string>
        $list = $table->find('list')->toArray();

        // Iterating should work with string values
        foreach ($list as $id => $title) {
            echo strlen($title);
        }

        return $list;
    }

    /**
     * Test find('list') with custom fields
     *
     * @return array<int|string, string>
     */
    public function testFindListWithFields(): array
    {
        $table = $this->fetchTable('Articles');

        return $table->find('list', keyField: 'id', valueField: 'title')->toArray();
    }
}
