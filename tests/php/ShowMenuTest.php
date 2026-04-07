<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/show_menu_renderer.php';

class ShowMenuTest extends TestCase
{
    public function testCalculateShowMenuStartPosition(): void
    {
        $eachCount = [5, 4, 3];

        $this->assertSame(0, calculateShowMenuStartPosition(0, $eachCount));
        $this->assertSame(5, calculateShowMenuStartPosition(1, $eachCount));
        $this->assertSame(9, calculateShowMenuStartPosition(2, $eachCount));
    }

    public function testSliceShowMenuSectionItems(): void
    {
        $items = ['牛肉麵', '湯餃', '餛飩麵', '滷肉飯', '爌肉飯'];

        $this->assertSame(
            ['餛飩麵', '滷肉飯'],
            sliceShowMenuSectionItems($items, 2, 2)
        );
    }

    public function testBuildShowMenuBoxHtml(): void
    {
        $this->assertSame(
            '<div class="box" onclick="r(3)">餛飩麵</div>',
            buildShowMenuBoxHtml(3, '餛飩麵')
        );
    }

    public function testBuildShowMenuSectionHtml(): void
    {
        $actual = buildShowMenuSectionHtml(['牛肉麵', '湯餃'], 0);

        $expected =
            '<div id="section">' .
            '<div class="box" onclick="r(0)">牛肉麵</div>' .
            '<div class="box" onclick="r(1)">湯餃</div>' .
            '</div>';

        $this->assertSame($expected, $actual);
    }

    public function testBuildShowMenuSectionsHtml(): void
    {
        $items = ['紅燒牛肉麵', '清燉牛肉麵', '牛肉湯餃', '餛飩麵', '滷肉飯', '爌肉飯'];
        $typeCount = 2;
        $eachCount = [4, 2];

        $actual = buildShowMenuSectionsHtml($items, $typeCount, $eachCount);

        $expected =
            '<div id="section">' .
            '<div class="box" onclick="r(0)">紅燒牛肉麵</div>' .
            '<div class="box" onclick="r(1)">清燉牛肉麵</div>' .
            '<div class="box" onclick="r(2)">牛肉湯餃</div>' .
            '<div class="box" onclick="r(3)">餛飩麵</div>' .
            '</div>' .
            '<div id="section">' .
            '<div class="box" onclick="r(4)">滷肉飯</div>' .
            '<div class="box" onclick="r(5)">爌肉飯</div>' .
            '</div>';

        $this->assertSame($expected, $actual);
    }

    public function testBuildShowMenuSectionsHtmlWithZeroTypeCount(): void
    {
        $this->assertSame('', buildShowMenuSectionsHtml(['A', 'B'], 0, [1, 1]));
    }
}
