<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/order_page_renderer.php';

class OrderPageTest extends TestCase
{
    public function testBuildOrderPageHtml(): void
    {
        $html = buildOrderPageHtml();

        $this->assertStringContainsString('<div id="orderList">', $html);
        $this->assertStringContainsString('<span id="sendBtn"></span>', $html);
        $this->assertStringContainsString('<div id="number">', $html);
        $this->assertStringContainsString('<table id="list">', $html);
        $this->assertStringContainsString('<span id="totalW"></span><span id="total"></span>', $html);
        $this->assertStringContainsString('id="dineIn"', $html);
        $this->assertStringContainsString('value="內用"', $html);
        $this->assertStringContainsString('onclick="dineIn()"', $html);
        $this->assertStringContainsString('id="toGo"', $html);
        $this->assertStringContainsString('value="外帶"', $html);
        $this->assertStringContainsString('onclick="toGo()"', $html);
        $this->assertStringContainsString('提示：選擇內用或者外帶', $html);
    }
}
