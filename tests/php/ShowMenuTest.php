<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/showMenu_testable.php';

class ShowMenuTest extends TestCase
{
    public function testBuildShowMenuOutputWithTwoTypes(): void
    {
        $items = [
            '紅燒牛肉麵',
            '清燉牛肉麵',
            '牛肉湯餃',
            '餛飩麵',
            '榨菜肉絲麵',
            '滷肉飯（大）',
            '滷肉飯（小）',
            '爌肉飯',
            '爌肉飯便當'
        ];

        $typeCount = 2;
        $eachCount = [5, 4];

        $output = buildShowMenuOutput($items, $typeCount, $eachCount);

        $expected = '<div id="section">'
            . '<div class="box" onclick="r(0)">紅燒牛肉麵</div>'
            . '<div class="box" onclick="r(1)">清燉牛肉麵</div>'
            . '<div class="box" onclick="r(2)">牛肉湯餃</div>'
            . '<div class="box" onclick="r(3)">餛飩麵</div>'
            . '<div class="box" onclick="r(4)">榨菜肉絲麵</div>'
            . '</div>'
            . '<div id="section">'
            . '<div class="box" onclick="r(5)">滷肉飯（大）</div>'
            . '<div class="box" onclick="r(6)">滷肉飯（小）</div>'
            . '<div class="box" onclick="r(7)">爌肉飯</div>'
            . '<div class="box" onclick="r(8)">爌肉飯便當</div>'
            . '</div>';

        $this->assertSame($expected, $output);
    }

    public function testBuildShowMenuOutputWithSingleType(): void
    {
        $items = ['紅茶', '奶茶', '綠茶'];
        $typeCount = 1;
        $eachCount = [3];

        $output = buildShowMenuOutput($items, $typeCount, $eachCount);

        $this->assertStringContainsString('<div class="box" onclick="r(0)">紅茶</div>', $output);
        $this->assertStringContainsString('<div class="box" onclick="r(1)">奶茶</div>', $output);
        $this->assertStringContainsString('<div class="box" onclick="r(2)">綠茶</div>', $output);
    }

    public function testBuildShowMenuOutputWithNoTypes(): void
    {
        $items = [];
        $typeCount = 0;
        $eachCount = [];

        $output = buildShowMenuOutput($items, $typeCount, $eachCount);

        $this->assertSame('', $output);
    }

    public function testBuildShowMenuOutputStartsSecondTypeAtCorrectIndex(): void
    {
        $items = ['A', 'B', 'C', 'D', 'E'];
        $typeCount = 2;
        $eachCount = [2, 3];

        $output = buildShowMenuOutput($items, $typeCount, $eachCount);

        $this->assertStringContainsString('<div class="box" onclick="r(2)">C</div>', $output);
        $this->assertStringContainsString('<div class="box" onclick="r(3)">D</div>', $output);
        $this->assertStringContainsString('<div class="box" onclick="r(4)">E</div>', $output);
    }
}