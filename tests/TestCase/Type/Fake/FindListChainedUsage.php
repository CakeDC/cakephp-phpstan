<?php
declare(strict_types=1);

namespace CakeDC\PHPStan\Test\TestCase\Type\Fake;

use Cake\ORM\Locator\LocatorAwareTrait;

/**
 * Test file for find('list') with chained method calls.
 */
class FindListChainedUsage
{
    use LocatorAwareTrait;

    /**
     * Test find('list') with where clause
     *
     * @return array<int|string, string>
     */
    public function testFindListWithWhere(): array
    {
        $table = $this->fetchTable('Articles');

        // Chained where() should not affect the return type
        return $table->find('list')
            ->where(['published' => true])
            ->toArray();
    }

    /**
     * Test find('list') with multiple chained methods
     *
     * @return array<int|string, string>
     */
    public function testFindListWithMultipleChains(): array
    {
        $table = $this->fetchTable('Articles');

        // Multiple chained methods should not affect the return type
        return $table->find('list')
            ->where(['published' => true])
            ->orderBy(['title' => 'ASC'])
            ->limit(100)
            ->toArray();
    }

    /**
     * Test that strlen() works on values (proves type is string)
     *
     * @return void
     */
    public function testFindListValuesAreStrings(): void
    {
        $table = $this->fetchTable('Articles');
        $list = $table->find('list')->toArray();

        foreach ($list as $value) {
            // This would error if $value were Entity
            echo strlen($value);
        }
    }
}
