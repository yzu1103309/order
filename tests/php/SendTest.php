<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/queries/send_queries.php';

class SendTest extends TestCase
{
    public function testBuildInsertOrderQuery(): void
    {
        $this->assertSame(
            "INSERT INTO Orders (Type,Num,List,Total) VALUES (1,'15桌','1,0,2',120)",
            buildInsertOrderQuery(1, '15桌', '1,0,2', 120)
        );
    }

    public function testBuildInsertHistoryQuery(): void
    {
        $this->assertSame(
            "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES (2,'15','1,0,2',120,'2026-04-08','22:30')",
            buildInsertHistoryQuery(2, '15', '1,0,2', 120, '2026-04-08', '22:30')
        );
    }

    public function testBuildUpdateToGoNumQuery(): void
    {
        $this->assertSame(
            "UPDATE ToGoNum SET PreNum=15",
            buildUpdateToGoNumQuery(15)
        );
    }

    public function testBuildSendBaseQueriesForDineIn(): void
    {
        $expected = [
            "INSERT INTO Orders (Type,Num,List,Total) VALUES (1,'15桌','1,0,2',120)",
            "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES (1,'15桌','1,0,2',120,'2026-04-08','22:30')",
        ];

        $actual = buildSendBaseQueries(1, '15桌', '1,0,2', 120, '2026-04-08', '22:30');

        $this->assertSame($expected, $actual);
    }

    public function testBuildSendBaseQueriesForTakeOut(): void
    {
        $expected = [
            "INSERT INTO Orders (Type,Num,List,Total) VALUES (2,'15','1,0,2',120)",
            "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES (2,'15','1,0,2',120,'2026-04-08','22:30')",
        ];

        $actual = buildSendBaseQueries(2, '15', '1,0,2', 120, '2026-04-08', '22:30');

        $this->assertSame($expected, $actual);
    }

    public function testBuildSendPostQueriesForDineIn(): void
    {
        $this->assertSame([], buildSendPostQueries(1, '15桌'));
    }

    public function testBuildSendPostQueriesForTakeOut(): void
    {
        $expected = [
            "UPDATE ToGoNum SET PreNum=15"
        ];

        $this->assertSame($expected, buildSendPostQueries(2, '15'));
    }
}
