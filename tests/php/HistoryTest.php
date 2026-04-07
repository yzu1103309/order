<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/history_renderer.php';

class HistoryTest extends TestCase
{
    public function testBuildHistorySelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `History` WHERE Type=2 ORDER BY `History`.`Date` DESC, `History`.`Time` DESC",
            buildHistorySelectQuery(2)
        );
    }

    public function testBuildHistoryPageRange(): void
    {
        $this->assertSame([0, 6], buildHistoryPageRange(1));
        $this->assertSame([7, 13], buildHistoryPageRange(2));
        $this->assertSame([14, 20], buildHistoryPageRange(3));
    }

    public function testNormalizeHistoryNumForDineIn(): void
    {
        $this->assertSame('15桌', normalizeHistoryNum(1, '15桌'));
    }

    public function testNormalizeHistoryNumForTakeOut(): void
    {
        $this->assertSame('15號', normalizeHistoryNum(2, '15'));
    }

    public function testBuildHistoryRowHtmlForDineIn(): void
    {
        $row = [1, '15桌', '1,0,0', 120, 9, '2026-04-07', '18:30:00'];

        $html = buildHistoryRowHtml($row, 1);

        $this->assertStringContainsString('<td>15桌</td>', $html);
        $this->assertStringContainsString('<td> NT$120 </td>', $html);
        $this->assertStringContainsString('<td>2026-04-07</td>', $html);
        $this->assertStringContainsString('<td>18:30:00</td>', $html);
        $this->assertStringContainsString('onclick="view(9,1)"', $html);
    }

    public function testBuildHistoryRowHtmlForTakeOut(): void
    {
        $row = [2, '15', '1,0,0', 220, 10, '2026-04-07', '19:00:00'];

        $html = buildHistoryRowHtml($row, 2);

        $this->assertStringContainsString('<td>15號</td>', $html);
        $this->assertStringContainsString('onclick="view(10,2)"', $html);
    }

    public function testShouldCollectHistoryRow(): void
    {
        $this->assertFalse(shouldCollectHistoryRow(0, 7));
        $this->assertTrue(shouldCollectHistoryRow(7, 7));
        $this->assertTrue(shouldCollectHistoryRow(8, 7));
    }

    public function testShouldStopCollectHistoryRow(): void
    {
        $this->assertFalse(shouldStopCollectHistoryRow(5, 6));
        $this->assertTrue(shouldStopCollectHistoryRow(6, 6));
    }

    public function testHasHistoryRecords(): void
    {
        $this->assertTrue(hasHistoryRecords(1));
        $this->assertFalse(hasHistoryRecords(0));
    }

    public function testBuildHistoryTableHeaderHtml(): void
    {
        $typename = [' ', '內用', '外帶'];
        $html = buildHistoryTableHeaderHtml(2, $typename);

        $this->assertStringContainsString('history-type2', $html);
        $this->assertStringContainsString('外帶', $html);
        $this->assertStringContainsString('金額', $html);
        $this->assertStringContainsString('訂單日期', $html);
        $this->assertStringContainsString('時間', $html);
    }

    public function testBuildHistoryPaginationHtmlFirstPageWithNext(): void
    {
        $html = buildHistoryPaginationHtml(2, 1, true);

        $this->assertStringContainsString('value="已達首頁"', $html);
        $this->assertStringContainsString(' - 第1頁 - ', $html);
        $this->assertStringContainsString('onclick="history(2,2)"', $html);
    }

    public function testBuildHistoryPaginationHtmlMiddlePage(): void
    {
        $html = buildHistoryPaginationHtml(1, 3, true);

        $this->assertStringContainsString('onclick="history(1,2)"', $html);
        $this->assertStringContainsString(' - 第3頁 - ', $html);
        $this->assertStringContainsString('onclick="history(1,4)"', $html);
    }

    public function testBuildHistoryPaginationHtmlLastPage(): void
    {
        $html = buildHistoryPaginationHtml(1, 2, false);

        $this->assertStringContainsString('onclick="history(1,1)"', $html);
        $this->assertStringContainsString('value="已達末頁"', $html);
    }

    public function testBuildHistoryEmptyHtml(): void
    {
        $typename = [' ', '內用', '外帶'];

        $this->assertSame(
            '<h1>查無任何外帶之紀錄！</h1>',
            buildHistoryEmptyHtml(2, $typename)
        );
    }
}
