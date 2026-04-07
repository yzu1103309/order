<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/queries/remove_all_queries.php';

class RemoveAllTest extends TestCase
{
    public function testBuildRemoveAllHistoryQueries(): void
    {
        $expected = [
            'DELETE FROM `History` WHERE 1',
            'ALTER TABLE `History` AUTO_INCREMENT=1',
        ];

        $this->assertSame($expected, buildRemoveAllHistoryQueries());
    }
}
