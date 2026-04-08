<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/history_page_renderer.php';

class HistoryPageTest extends TestCase
{
    public function testBuildHistoryPageSelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `History`",
            buildHistoryPageSelectQuery()
        );
    }

    public function testCalculateHistoryTotal(): void
    {
        $rows = [
            [1, '01桌', '1,0,0', 120, 1, '2026-04-08', '10:00'],
            [2, '15', '0,1,0', 80, 2, '2026-04-08', '11:00'],
            [1, '02桌', '0,0,1', 200, 3, '2026-04-08', '12:00'],
        ];

        $this->assertSame(400, calculateHistoryTotal($rows));
    }

    public function testCalculateHistoryTotalWithEmptyRows(): void
    {
        $this->assertSame(0, calculateHistoryTotal([]));
    }

    public function testBuildHistoryPageTotalHtml(): void
    {
        $html = buildHistoryPageTotalHtml(1234);

        $this->assertStringContainsString('總收入：NT$1234', $html);
    }

    public function testBuildHistoryPageActionsHtml(): void
    {
        $html = buildHistoryPageActionsHtml();

        $this->assertStringContainsString('value="內用紀錄"', $html);
        $this->assertStringContainsString('onclick="history(1,1)"', $html);
        $this->assertStringContainsString('value="外帶紀錄"', $html);
        $this->assertStringContainsString('onclick="history(2,1)"', $html);
        $this->assertStringContainsString('value="收入圖表"', $html);
        $this->assertStringContainsString('onclick="searchYear()"', $html);
        $this->assertStringContainsString('value="清除所有紀錄"', $html);
        $this->assertStringContainsString('onclick="removeAll()"', $html);
        $this->assertStringContainsString('提示：請於上方選擇欲查詢之紀錄類別', $html);
    }

    public function testBuildHistoryPageHtml(): void
    {
        $html = buildHistoryPageHtml(5678);

        $this->assertStringContainsString('總收入：NT$5678', $html);
        $this->assertStringContainsString('value="內用紀錄"', $html);
        $this->assertStringContainsString('value="外帶紀錄"', $html);
        $this->assertStringContainsString('value="收入圖表"', $html);
        $this->assertStringContainsString('value="清除所有紀錄"', $html);
    }
}
