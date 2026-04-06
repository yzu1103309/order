<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/removeAll_testable.php';

class RemoveAllTest extends TestCase
{
    public function testRemoveAllHistoryClearsAllRows(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
            ['C', 1, 'item3', 300, 3, '2024-01-12'],
        ];

        $result = removeAllHistory($rows);

        $this->assertSame([], $result);
    }

    public function testRemoveAllHistoryKeepsEmptyArrayEmpty(): void
    {
        $rows = [];

        $result = removeAllHistory($rows);

        $this->assertSame([], $result);
    }

    public function testShouldResetAutoIncrementAfterRemoveAllAlwaysReturnsTrue(): void
    {
        $this->assertTrue(shouldResetAutoIncrementAfterRemoveAll());
    }

    public function testSimulateRemoveAllClearsRowsAndResetsAutoIncrement(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
        ];

        $result = simulateRemoveAll($rows);

        $this->assertSame([], $result['rows']);
        $this->assertTrue($result['reset_auto_increment']);
    }
}