<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/done_testable.php';

class DoneTest extends TestCase
{
    public function testBuildDoneQueriesWhenRowsStillRemain(): void
    {
        $expected = [
            "DELETE FROM `Delivers` WHERE `Auto`= 5;",
            "SELECT * FROM `Delivers`"
        ];

        $actual = buildDoneQueries(5, true);

        $this->assertSame($expected, $actual);
    }

    public function testBuildDoneQueriesWhenNoRowsRemain(): void
    {
        $expected = [
            "DELETE FROM `Delivers` WHERE `Auto`= 5;",
            "SELECT * FROM `Delivers`",
            "ALTER TABLE `Delivers` AUTO_INCREMENT=1"
        ];

        $actual = buildDoneQueries(5, false);

        $this->assertSame($expected, $actual);
    }
}