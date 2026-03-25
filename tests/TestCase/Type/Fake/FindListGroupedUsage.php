<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Test file for find('list') with groupField return type extension.
 */
class FindListGroupedUsage
{
    use LocatorAwareTrait;

    /**
     * Test that find('list') with groupField returns nested array
     *
     * @return array<int|string, array<int|string, string>>
     */
    public function testFindListWithGroupField(): array
    {
        $table = $this->fetchTable('Articles');

        // This should be inferred as array<int|string, array<int|string, string>>
        return $table->find('list', groupField: 'category_id')->toArray();
    }

    /**
     * Test grouped list with chained methods
     *
     * @return array<int|string, array<int|string, string>>
     */
    public function testFindListGroupedWithChain(): array
    {
        $table = $this->fetchTable('Articles');

        return $table->find('list', groupField: 'category_id')
            ->where(['published' => true])
            ->orderBy(['title' => 'ASC'])
            ->toArray();
    }

    /**
     * Test iterating grouped list (proves nested type is correct)
     *
     * @return void
     */
    public function testFindListGroupedIteration(): void
    {
        $table = $this->fetchTable('Articles');
        $grouped = $table->find('list', groupField: 'category_id')->toArray();

        // Outer loop: groups
        foreach ($grouped as $groupKey => $items) {
            // Inner loop: items in group
            foreach ($items as $itemKey => $value) {
                // This would error if $value were not string
                echo strlen($value);
            }
        }
    }

    /**
     * Test grouped list with all options
     *
     * @return array<int|string, array<int|string, string>>
     */
    public function testFindListGroupedWithAllFields(): array
    {
        $table = $this->fetchTable('Articles');

        return $table->find('list', keyField: 'id', valueField: 'title', groupField: 'category_id')->toArray();
    }
}
