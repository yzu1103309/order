<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/return_d3_renderer.php';

class ReturnD3Test extends TestCase
{
    public function testBuildReturnD3SelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `History` WHERE Date LIKE '2026%'",
            buildReturnD3SelectQuery('2026')
        );
    }

    public function testCreateMonthlyIncomeArray(): void
    {
        $expected = ['', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $this->assertSame($expected, createMonthlyIncomeArray());
    }

    public function testExtractMonthIndex(): void
    {
        $this->assertSame(1, extractMonthIndex('2026', '2026-01-15'));
        $this->assertSame(12, extractMonthIndex('2026', '2026-12-31'));
    }

    public function testAddIncomeToMonth(): void
    {
        $income = createMonthlyIncomeArray();
        $income = addIncomeToMonth($income, 3, 120);
        $income = addIncomeToMonth($income, 3, 80);

        $this->assertSame(200, $income[3]);
    }

    public function testBuildMonthLabel(): void
    {
        $this->assertSame('01月', buildMonthLabel(1));
        $this->assertSame('09月', buildMonthLabel(9));
        $this->assertSame('10月', buildMonthLabel(10));
        $this->assertSame('12月', buildMonthLabel(12));
    }

    public function testBuildReturnD3Csv(): void
    {
        $income = createMonthlyIncomeArray();
        $income[1] = 10320;
        $income[2] = 20975;
        $income[12] = 42390;

        $expected =
            "year,value\n" .
            "01月,10320\n" .
            "02月,20975\n" .
            "03月,0\n" .
            "04月,0\n" .
            "05月,0\n" .
            "06月,0\n" .
            "07月,0\n" .
            "08月,0\n" .
            "09月,0\n" .
            "10月,0\n" .
            "11月,0\n" .
            "12月,42390\n";

        $this->assertSame($expected, buildReturnD3Csv($income));
    }
}
