<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/send_testable.php';

class SendTest extends TestCase
{
    public function testBuildSendQueriesForNormalOrder(): void
    {
        $expected = [
            "INSERT INTO Orders (Type,Num,List,Total) VALUES (1,'A001','burger,tea',150)",
            "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES (1,'A001','burger,tea',150,'2026-04-03','10:30')"
        ];

        $actual = buildSendQueries(1, 'A001', 'burger,tea', 150, '2026-04-03', '10:30');

        $this->assertSame($expected, $actual);
    }

    public function testBuildSendQueriesForType2AddsUpdate(): void
    {
        $expected = [
            "INSERT INTO Orders (Type,Num,List,Total) VALUES (2,'25','coffee',80)",
            "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES (2,'25','coffee',80,'2026-04-03','11:00')",
            "UPDATE ToGoNum SET PreNum=25"
        ];

        $actual = buildSendQueries(2, '25', 'coffee', 80, '2026-04-03', '11:00');

        $this->assertSame($expected, $actual);
    }
}