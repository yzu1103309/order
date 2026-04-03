<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/returnD3_testable.php';

class ReturnD3Test extends TestCase
{
    public function testBuildReturnD3OutputWithData(): void
    {
        $year = '2024';

        $rows = [
            ['A', 1, 'item1', 100, 'N', '2024-01-10'],
            ['B', 1, 'item2', 200, 'N', '2024-01-20'],
            ['C', 1, 'item3', 300, 'N', '2024-02-15'],
            ['D', 1, 'item4', 400, 'N', '2024-12-25'],
            ['E', 1, 'item5', 999, 'N', '2023-05-01'],
        ];

        $expected = <<<CSV
year,value
01月,300
02月,300
03月,0
04月,0
05月,0
06月,0
07月,0
08月,0
09月,0
10月,0
11月,0
12月,400

CSV;

        $actual = buildReturnD3Output($year, $rows);

        $this->assertSame(
            str_replace("\r\n", "\n", $expected),
            str_replace("\r\n", "\n", $actual)
        );
    }

    public function testBuildReturnD3OutputWithoutData(): void
    {
        $year = '2025';

        $rows = [
            ['A', 1, 'item1', 100, 'N', '2024-01-10'],
            ['B', 1, 'item2', 200, 'N', '2024-02-20'],
        ];

        $expected = <<<CSV
year,value
01月,0
02月,0
03月,0
04月,0
05月,0
06月,0
07月,0
08月,0
09月,0
10月,0
11月,0
12月,0

CSV;

        $actual = buildReturnD3Output($year, $rows);

        $this->assertSame(
            str_replace("\r\n", "\n", $expected),
            str_replace("\r\n", "\n", $actual)
        );
    }

    public function testCalculateMonthlyIncomeAccumulatesSameMonth(): void
    {
        $year = '2024';

        $rows = [
            ['A', 1, 'item1', 50, 'N', '2024-03-01'],
            ['B', 1, 'item2', 70, 'N', '2024-03-18'],
            ['C', 1, 'item3', 30, 'N', '2024-03-25'],
        ];

        $income = calculateMonthlyIncome($year, $rows);

        $this->assertSame(150, $income[3]);
    }

    public function testCalculateMonthlyIncomeSkipsIncompleteRows(): void
    {
        $year = '2024';

        $rows = [
            ['A', 1, 'item1', 100, 'N', '2024-01-10'],
            ['B', 1, 'item2'],
            ['C', 1, 'item3', 50, 'N', '2024-01-20'],
        ];

        $income = calculateMonthlyIncome($year, $rows);

        $this->assertSame(150, $income[1]);
    }
}