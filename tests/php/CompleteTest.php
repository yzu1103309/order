<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/queries/complete_queries.php';

class CompleteTest extends TestCase
{
    public function testBuildSelectOrderByAutoQuery(): void
    {
        $expected = "SELECT * FROM `Orders` WHERE `Auto` = 1";
        $actual = buildSelectOrderByAutoQuery(1);

        $this->assertSame($expected, $actual);
    }

    public function testBuildCompleteBaseQueriesForDineIn(): void
    {
        $auto = 1;
        $row = [1, '15桌', '3,1,1,0,1,0,1,1,0,1,1,1,2,3,1,0,0,1', 960, 1];

        $expected = [
            "INSERT INTO Delivers (Type,Num,List,Total) VALUES (1,'15桌','3,1,1,0,1,0,1,1,0,1,1,1,2,3,1,0,0,1',960)",
            "DELETE FROM `Orders` WHERE `Auto`= 1;"
        ];

        $actual = buildCompleteBaseQueries($auto, $row);

        $this->assertSame($expected, $actual);
    }

    public function testBuildCompleteBaseQueriesForTakeOut(): void
    {
        $auto = 7;
        $row = [2, '15', '1,0,2,0', 120, 7];

        $expected = [
            "INSERT INTO Delivers (Type,Num,List,Total) VALUES (2,'15','1,0,2,0',120)",
            "DELETE FROM `Orders` WHERE `Auto`= 7;"
        ];

        $actual = buildCompleteBaseQueries($auto, $row);

        $this->assertSame($expected, $actual);
    }

    public function testBuildSelectAllOrdersQuery(): void
    {
        $expected = "SELECT * FROM `Orders`";
        $actual = buildSelectAllOrdersQuery();

        $this->assertSame($expected, $actual);
    }

    public function testBuildPostDeleteQueriesWhenNoRemainingOrders(): void
    {
        $expected = [
            "ALTER TABLE `Orders` AUTO_INCREMENT=1"
        ];

        $actualFalse = buildPostDeleteQueries(false);
        $actualNull = buildPostDeleteQueries(null);

        $this->assertSame($expected, $actualFalse);
        $this->assertSame($expected, $actualNull);
    }

    public function testBuildPostDeleteQueriesWhenOrdersStillRemain(): void
    {
        $remainingRow = [2, '8', '1,0,0', 50, 2];

        $expected = [];
        $actual = buildPostDeleteQueries($remainingRow);

        $this->assertSame($expected, $actual);
    }
}
