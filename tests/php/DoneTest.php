<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/queries/done_queries.php';

class DoneTest extends TestCase
{
    public function testBuildDeleteDeliverByAutoQuery(): void
    {
        $this->assertSame(
            "DELETE FROM `Delivers` WHERE `Auto`= 5;",
            buildDeleteDeliverByAutoQuery(5)
        );
    }

    public function testBuildSelectAllDeliversQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `Delivers`",
            buildSelectAllDeliversQuery()
        );
    }

    public function testBuildDonePostDeleteQueriesWhenNoRemainingDelivers(): void
    {
        $expected = [
            "ALTER TABLE `Delivers` AUTO_INCREMENT=1"
        ];

        $this->assertSame($expected, buildDonePostDeleteQueries(false));
        $this->assertSame($expected, buildDonePostDeleteQueries(null));
    }

    public function testBuildDonePostDeleteQueriesWhenDeliversStillRemain(): void
    {
        $remainingRow = [2, '15', '1,0,1', 120, 8];

        $this->assertSame([], buildDonePostDeleteQueries($remainingRow));
    }
}
