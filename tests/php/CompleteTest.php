<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/complete_testable.php';

class CompleteTest extends TestCase
{
    // 測試當 Orders 刪除後仍有資料時，不會重設 AUTO_INCREMENT
    public function testCompleteWithRemainingOrders(): void
    {
        $row = [1, 'A001', 'burger', 100];

        $expected = [
            "SELECT * FROM `Orders` WHERE `Auto` = 5",
            "INSERT INTO Delivers (Type,Num,List,Total) VALUES (1,'A001','burger',100)",
            "DELETE FROM `Orders` WHERE `Auto`= 5;",
            "SELECT * FROM `Orders`"
        ];

        $actual = buildCompleteQueries(5, $row, true);

        $this->assertSame($expected, $actual);
    }

    // 測試當 Orders 刪除後無資料時，會重設 AUTO_INCREMENT
    public function testCompleteWithNoRemainingOrders(): void
    {
        $row = [1, 'A001', 'burger', 100];

        $expected = [
            "SELECT * FROM `Orders` WHERE `Auto` = 5",
            "INSERT INTO Delivers (Type,Num,List,Total) VALUES (1,'A001','burger',100)",
            "DELETE FROM `Orders` WHERE `Auto`= 5;",
            "SELECT * FROM `Orders`",
            "ALTER TABLE `Orders` AUTO_INCREMENT=1"
        ];

        $actual = buildCompleteQueries(5, $row, false);

        $this->assertSame($expected, $actual);
    }
}