<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/search_year_renderer.php';

class SearchYearTest extends TestCase
{
    public function testBuildSearchYearSelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `History`",
            buildSearchYearSelectQuery()
        );
    }

    public function testExtractYearFromDate(): void
    {
        $this->assertSame('2026', extractYearFromDate('2026-04-08'));
        $this->assertSame('2025', extractYearFromDate('2025-12-31'));
    }

    public function testShouldAppendYear(): void
    {
        $this->assertTrue(shouldAppendYear('2026', 0));
        $this->assertTrue(shouldAppendYear('2025', '2026'));
        $this->assertFalse(shouldAppendYear('2026', '2026'));
    }

    public function testCollectUniqueYears(): void
    {
        $rows = [
            [1, '1桌', '1,0', 100, 1, '2026-04-08', '10:00'],
            [1, '2桌', '1,0', 100, 2, '2026-03-01', '09:00'],
            [2, '15', '1,0', 100, 3, '2025-12-31', '18:00'],
            [2, '16', '1,0', 100, 4, '2025-01-01', '12:00'],
            [1, '3桌', '1,0', 100, 5, '2024-11-11', '08:00'],
        ];

        $this->assertSame(['2026', '2025', '2024'], collectUniqueYears($rows));
    }

    public function testBuildYearOptionsHtml(): void
    {
        $html = buildYearOptionsHtml(['2026', '2025']);

        $this->assertStringContainsString('<option value="2026">2026</option>', $html);
        $this->assertStringContainsString('<option value="2025">2025</option>', $html);
    }

    public function testBuildSearchYearHtml(): void
    {
        $html = buildSearchYearHtml(['2026', '2025']);

        $this->assertStringContainsString('回上一頁', $html);
        $this->assertStringContainsString('請選擇', $html);
        $this->assertStringContainsString('<option value="2026">2026</option>', $html);
        $this->assertStringContainsString('<option value="2025">2025</option>', $html);
        $this->assertStringContainsString('onclick="draw()"', $html);
        $this->assertStringContainsString('提示：請於上方選取可查詢之年份', $html);
    }
}
