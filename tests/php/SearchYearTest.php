<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/searchYear_testable.php';

class SearchYearTest extends TestCase
{
    public function testExtractYearsReturnsDifferentAdjacentYears(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 'N', '2022-01-10'],
            ['B', 1, 'item2', 200, 'N', '2022-03-15'],
            ['C', 1, 'item3', 300, 'N', '2023-05-20'],
            ['D', 1, 'item4', 400, 'N', '2023-08-01'],
            ['E', 1, 'item5', 500, 'N', '2024-12-25'],
        ];

        $years = extractYears($rows);

        $this->assertSame(['2022', '2023', '2024'], $years);
    }

    public function testExtractYearsSkipsIncompleteRows(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 'N', '2022-01-10'],
            ['B', 1, 'item2'],
            ['C', 1, 'item3', 300, 'N', '2023-05-20'],
        ];

        $years = extractYears($rows);

        $this->assertSame(['2022', '2023'], $years);
    }

    public function testBuildSearchYearOutputContainsOptions(): void
    {
        $rows = [
            ['A', 1, 'item1', 100, 'N', '2022-01-10'],
            ['B', 1, 'item2', 200, 'N', '2023-03-15'],
            ['C', 1, 'item3', 300, 'N', '2024-06-20'],
        ];

        $output = buildSearchYearOutput($rows);

        $this->assertStringContainsString('<option value="2022">2022</option>', $output);
        $this->assertStringContainsString('<option value="2023">2023</option>', $output);
        $this->assertStringContainsString('<option value="2024">2024</option>', $output);
        $this->assertStringContainsString('提示：請於上方選取可查詢之年份', $output);
    }

    public function testBuildSearchYearOutputWithNoValidYearStillShowsBaseHtml(): void
    {
        $rows = [
            ['A', 1, 'item1'],
            ['B', 1, 'item2'],
        ];

        $output = buildSearchYearOutput($rows);

        $this->assertStringContainsString('<div id="selector">', $output);
        $this->assertStringContainsString('<option value="" disabled="" selected="">請選擇</option>', $output);
        $this->assertStringNotContainsString('<option value="2022">2022</option>', $output);
    }
}