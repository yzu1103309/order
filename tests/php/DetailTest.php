<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/detail_testable.php';

class DetailTest extends TestCase
{
    // 測試只會輸出數量不為 0 的餐點資料
    public function testBuildDetailRowsFiltersZeroItems(): void
    {
        $items = ['burger', 'tea', 'cake', 'coffee'];
        $dishCounts = [1, 0, 2, 0];
        $itemCount = 4;

        $expected = [
            "<tr><td class='td1'>burger</td><td class='td2'>*1</td></tr>",
            "<tr><td class='td1'>cake</td><td class='td2'>*2</td></tr>"
        ];

        $actual = buildDetailRows($items, $dishCounts, $itemCount);

        $this->assertSame($expected, $actual);
    }

    // 測試當全部為 0 時，不會輸出任何資料
    public function testBuildDetailRowsAllZero(): void
    {
        $items = ['burger', 'tea'];
        $dishCounts = [0, 0];
        $itemCount = 2;

        $expected = [];

        $actual = buildDetailRows($items, $dishCounts, $itemCount);

        $this->assertSame($expected, $actual);
    }
}