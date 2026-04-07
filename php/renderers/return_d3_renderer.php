<?php

function buildReturnD3SelectQuery(string $year): string
{
    return "SELECT * FROM `History` WHERE Date LIKE '$year%'";
}

function createMonthlyIncomeArray(): array
{
    return ['', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
}

function extractMonthIndex(string $year, string $date): int
{
    preg_match("/$year-(\d{2})-/", $date, $matches);
    return intval($matches[1]);
}

function addIncomeToMonth(array $income, int $monthIndex, int $total): array
{
    $income[$monthIndex] += $total;
    return $income;
}

function buildMonthLabel(int $month): string
{
    if ($month < 10) {
        return '0' . $month . '月';
    }

    return $month . '月';
}

function buildReturnD3Csv(array $income): string
{
    $csv = "year,value\n";

    for ($i = 1; $i <= 12; $i++) {
        $csv .= buildMonthLabel($i) . ',' . $income[$i] . "\n";
    }

    return $csv;
}
