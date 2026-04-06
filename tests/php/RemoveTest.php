<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/remove_testable.php';

class RemoveTest extends TestCase
{
    public function testRemoveHistoryByAutoRemovesMatchingRow(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
            ['C', 1, 'item3', 300, 3, '2024-01-12'],
        ];

        $result = removeHistoryByAuto($rows, 2);

        $expected = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['C', 1, 'item3', 300, 3, '2024-01-12'],
        ];

        $this->assertSame($expected, $result);
    }

    public function testRemoveHistoryByAutoKeepsAllRowsWhenAutoNotFound(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
        ];

        $result = removeHistoryByAuto($rows, 99);

        $this->assertSame($rows, $result);
    }

    public function testShouldResetAutoIncrementReturnsTrueWhenNoRowsRemain(): void
    {
        $rows = [];

        $this->assertTrue(shouldResetAutoIncrement($rows));
    }

    public function testShouldResetAutoIncrementReturnsFalseWhenRowsRemain(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
        ];

        $this->assertFalse(shouldResetAutoIncrement($rows));
    }

    public function testSimulateRemoveResetsAutoIncrementWhenLastRowRemoved(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 7, '2024-01-10'],
        ];

        $result = simulateRemove($rows, 7);

        $this->assertSame([], $result['rows']);
        $this->assertTrue($result['reset_auto_increment']);
    }

    public function testSimulateRemoveDoesNotResetAutoIncrementWhenRowsStillRemain(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 7, '2024-01-10'],
            ['B', 1, 'item2', 200, 8, '2024-01-11'],
        ];

        $result = simulateRemove($rows, 7);

        $expectedRows = [
            ['B', 1, 'item2', 200, 8, '2024-01-11'],
        ];

        $this->assertSame($expectedRows, $result['rows']);
        $this->assertFalse($result['reset_auto_increment']);
    }

    public function testRemoveHistoryByAutoKeepsIncompleteRows(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 1, '2024-01-10'],
            ['broken row'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
        ];

        $result = removeHistoryByAuto($rows, 1);

        $expected = [
            ['broken row'],
            ['B', 1, 'item2', 200, 2, '2024-01-11'],
        ];

        $this->assertSame($expected, $result);
    }
}