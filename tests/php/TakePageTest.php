<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/take_page_renderer.php';

class TakePageTest extends TestCase
{
    public function testBuildTakeSelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `Orders`",
            buildTakeSelectQuery()
        );
    }

    public function testNormalizeTakeNumForDineIn(): void
    {
        $this->assertSame('15桌', normalizeTakeNum(1, '15桌'));
    }

    public function testNormalizeTakeNumForTakeOut(): void
    {
        $this->assertSame('15號', normalizeTakeNum(2, '15'));
    }

    public function testBuildTakePagesSinglePageShowsCompleteButton(): void
    {
        $typeName = [' ', '內用', '外帶'];
        $items = ['蛋炒飯', '陽春麵', '蛋花湯', '豬血湯'];
        $row = [1, '15桌', '1,0,2,0', 300, 9];

        $pages = buildTakePages($row, $items, 4, $typeName);

        $this->assertCount(1, $pages);
        $this->assertStringContainsString('蛋炒飯', $pages[0]);
        $this->assertStringContainsString('蛋花湯', $pages[0]);
        $this->assertStringNotContainsString('陽春麵</td><td class="td2">*0', $pages[0]);
        $this->assertStringContainsString('製作完成', $pages[0]);
        $this->assertStringNotContainsString('尚有品項，接續下單', $pages[0]);
    }

    public function testBuildTakePagesTwoPagesDoNotDuplicateItems(): void
    {
        $typeName = [' ', '內用', '外帶'];
        $items = [
            '紅燒牛肉麵','清燉牛肉麵','餛飩麵','榨菜肉絲麵','陽春麵',
            '蛋炒飯','滷肉飯（大）','滷肉飯（小）','爌肉飯','爌肉飯便當',
            '蛋花湯','貢丸湯'
        ];
        $row = [2, '15', '1,1,1,1,1,1,1,1,1,1,1,1', 999, 5];

        $pages = buildTakePages($row, $items, 12, $typeName);

        $this->assertCount(2, $pages);

        $this->assertStringContainsString('紅燒牛肉麵', $pages[0]);
        $this->assertStringContainsString('清燉牛肉麵', $pages[0]);
        $this->assertStringContainsString('餛飩麵', $pages[0]);
        $this->assertStringContainsString('榨菜肉絲麵', $pages[0]);
        $this->assertStringContainsString('陽春麵', $pages[0]);
        $this->assertStringContainsString('蛋炒飯', $pages[0]);
        $this->assertStringContainsString('滷肉飯（大）', $pages[0]);
        $this->assertStringContainsString('滷肉飯（小）', $pages[0]);
        $this->assertStringContainsString('爌肉飯', $pages[0]);
        $this->assertStringContainsString('爌肉飯便當', $pages[0]);

        $this->assertStringNotContainsString('蛋花湯', $pages[0]);
        $this->assertStringNotContainsString('貢丸湯', $pages[0]);

        $this->assertStringContainsString('蛋花湯', $pages[1]);
        $this->assertStringContainsString('貢丸湯', $pages[1]);
        $this->assertStringNotContainsString('紅燒牛肉麵', $pages[1]);
        $this->assertStringNotContainsString('清燉牛肉麵', $pages[1]);

        $this->assertStringContainsString('尚有品項，接續下單', $pages[0]);
        $this->assertStringContainsString('製作完成', $pages[1]);
        $this->assertStringContainsString('15號', $pages[0]);
    }

    public function testBuildTakePagesThreePagesFollowOriginalOrder(): void
    {
        $typeName = [' ', '內用', '外帶'];
        $items = [
            '紅燒牛肉麵','清燉牛肉麵','餛飩麵','榨菜肉絲麵','陽春麵',
            '蛋炒飯','滷肉飯（大）','滷肉飯（小）','爌肉飯','爌肉飯便當',
            '蛋花湯','貢丸湯','酸辣湯','豬血湯',
            '牛肉湯餃','酸辣湯餃','水餃（5顆）','鍋貼（5顆）',
            '招牌乾麵','麻醬麵','燙青菜','皮蛋豆腐','小黃瓜','海帶','豆干'
        ];
        $counts = array_fill(0, 25, '1');

        $row = [1, '3桌', implode(',', $counts), 1000, 12];

        $pages = buildTakePages($row, $items, 25, $typeName);

        $this->assertCount(3, $pages);

        $this->assertStringContainsString('紅燒牛肉麵', $pages[0]);
        $this->assertStringContainsString('爌肉飯便當', $pages[0]);
        $this->assertStringNotContainsString('蛋花湯', $pages[0]);

        $this->assertStringContainsString('蛋花湯', $pages[1]);
        $this->assertStringContainsString('招牌乾麵', $pages[1]);
        $this->assertStringContainsString('麻醬麵', $pages[1]);
        $this->assertStringNotContainsString('爌肉飯便當', $pages[1]);
        $this->assertStringNotContainsString('燙青菜', $pages[1]);

        $this->assertStringContainsString('燙青菜', $pages[2]);
        $this->assertStringContainsString('豆干', $pages[2]);
        $this->assertStringNotContainsString('麻醬麵', $pages[2]);
    }
}
