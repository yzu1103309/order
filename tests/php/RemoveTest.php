<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/queries/remove_queries.php';

class RemoveTest extends TestCase
{
    public function testBuildDeleteHistoryByAutoQuery(): void
    {
        $this->assertSame(
            "DELETE FROM `History` WHERE Auto=12",
            buildDeleteHistoryByAutoQuery(12)
        );
    }

    public function testBuildSelectAllHistoryQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `History`",
            buildSelectAllHistoryQuery()
        );
    }

    public function testBuildRemovePostDeleteQueriesWhenNoRemainingHistory(): void
    {
        $expected = [
            "ALTER TABLE `History` AUTO_INCREMENT=1"
        ];

        $this->assertSame($expected, buildRemovePostDeleteQueries(false));
        $this->assertSame($expected, buildRemovePostDeleteQueries(null));
    }

    public function testBuildRemovePostDeleteQueriesWhenHistoryStillRemains(): void
    {
        $remainingRow = [2, '15', '1,0,1', 120, 8, '2026-04-08', '20:00:00'];

        $this->assertSame([], buildRemovePostDeleteQueries($remainingRow));
    }
}
