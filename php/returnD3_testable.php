<?php

function calculateMonthlyIncome(string $year, array $rows): array
{
    $income = array_fill(0, 13, 0);

    foreach ($rows as $row) {
        // 模擬原本資料表欄位：
        // [0]: Type
        // [1]: Num
        // [2]: List
        // [3]: Total
        // [4]: Auto
        // [5]: Date

        if (!isset($row[3], $row[5])) {
            continue;
        }

        if (preg_match("/^" . preg_quote($year, "/") . "-(\d{2})-/", $row[5], $matches)) {
            $index = intval($matches[1]);
            if ($index >= 1 && $index <= 12) {
                $income[$index] += (int)$row[3];
            }
        }
    }

    return $income;
}

function buildReturnD3Output(string $year, array $rows): string
{
    $income = calculateMonthlyIncome($year, $rows);

    $output = "year,value\n";

    for ($i = 1; $i <= 12; $i++) {
        $month = ($i < 10) ? '0' . $i : (string)$i;
        $output .= $month . "月," . $income[$i] . "\n";
    }

    return $output;
}