<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/dine_in_renderer.php';

class DineInTest extends TestCase
{
    public function testFormatDineInTableNumber(): void
    {
        $this->assertSame('01', formatDineInTableNumber(1));
        $this->assertSame('09', formatDineInTableNumber(9));
        $this->assertSame('10', formatDineInTableNumber(10));
        $this->assertSame('12', formatDineInTableNumber(12));
    }

    public function testBuildDineInTableOptionHtml(): void
    {
        $this->assertSame(
            '<option value="01桌">01桌</option>',
            buildDineInTableOptionHtml(1)
        );

        $this->assertSame(
            '<option value="12桌">12桌</option>',
            buildDineInTableOptionHtml(12)
        );
    }

    public function testBuildDineInTableOptionsHtml(): void
    {
        $expected =
            '<option value="01桌">01桌</option>' .
            '<option value="02桌">02桌</option>' .
            '<option value="03桌">03桌</option>';

        $this->assertSame($expected, buildDineInTableOptionsHtml(3));
    }

    public function testBuildDineInTableOptionsHtmlWithZeroTables(): void
    {
        $this->assertSame('', buildDineInTableOptionsHtml(0));
    }

    public function testBuildDineInSelectorHtml(): void
    {
        $html = buildDineInSelectorHtml(2);

        $this->assertStringContainsString('請選擇桌號', $html);
        $this->assertStringContainsString('value="1"', $html);
        $this->assertStringContainsString('onchange="showMenu()"', $html);
        $this->assertStringContainsString('<option value="01桌">01桌</option>', $html);
        $this->assertStringContainsString('<option value="02桌">02桌</option>', $html);
    }
}
