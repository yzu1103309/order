<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/detail_renderer.php';

class DetailTest extends TestCase
{
    public function testBuildDetailSelectQuery(): void
    {
        $this->assertSame(
            "SELECT `List` FROM `History` WHERE Auto=12",
            buildDetailSelectQuery(12)
        );
    }

    public function testShouldRenderDetailRow(): void
    {
        $dish = ['1', '0', '2'];

        $this->assertTrue(shouldRenderDetailRow($dish, 0));
        $this->assertFalse(shouldRenderDetailRow($dish, 1));
        $this->assertTrue(shouldRenderDetailRow($dish, 2));
        $this->assertFalse(shouldRenderDetailRow($dish, 3));
    }

    public function testBuildDetailRowHtml(): void
    {
        $this->assertSame(
            "<tr><td class='td1'>漢堡</td><td class='td2'>*3</td></tr>",
            buildDetailRowHtml('漢堡', 3)
        );
    }

    public function testBuildDetailRowsHtmlSkipsZeroCountItems(): void
    {
        $items = ['漢堡', '薯條', '可樂', '濃湯'];
        $dish = ['2', '0', '1', '0'];

        $actual = buildDetailRowsHtml($items, 4, $dish);

        $expected =
            "<tr><td class='td1'>漢堡</td><td class='td2'>*2</td></tr>" .
            "<tr><td class='td1'>可樂</td><td class='td2'>*1</td></tr>";

        $this->assertSame($expected, $actual);
    }

    public function testBuildDetailRowsHtmlUsesOnlyItemCountRange(): void
    {
        $items = ['漢堡', '薯條', '可樂'];
        $dish = ['1', '1', '1'];

        $actual = buildDetailRowsHtml($items, 2, $dish);

        $expected =
            "<tr><td class='td1'>漢堡</td><td class='td2'>*1</td></tr>" .
            "<tr><td class='td1'>薯條</td><td class='td2'>*1</td></tr>";

        $this->assertSame($expected, $actual);
    }

    public function testBuildDetailRowsHtmlReturnsEmptyStringWhenAllZero(): void
    {
        $items = ['漢堡', '薯條'];
        $dish = ['0', '0'];

        $this->assertSame('', buildDetailRowsHtml($items, 2, $dish));
    }
}
