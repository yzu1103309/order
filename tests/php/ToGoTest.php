<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../php/renderers/to_go_renderer.php';

class ToGoTest extends TestCase
{
    public function testBuildToGoNumSelectQuery(): void
    {
        $this->assertSame(
            "SELECT * FROM `ToGoNum` WHERE 1",
            buildToGoNumSelectQuery()
        );
    }

    public function testCalculateNextToGoNum(): void
    {
        $this->assertSame(1, calculateNextToGoNum(0));
        $this->assertSame(2, calculateNextToGoNum(1));
        $this->assertSame(100, calculateNextToGoNum(99));
    }

    public function testCalculateNextToGoNumWrapsBackToOne(): void
    {
        $this->assertSame(1, calculateNextToGoNum(100));
        $this->assertSame(1, calculateNextToGoNum(101));
    }

    public function testBuildToGoHtml(): void
    {
        $html = buildToGoHtml(37);

        $this->assertStringContainsString('value="2"', $html);
        $this->assertStringContainsString('外帶編號', $html);
        $this->assertStringContainsString('name="num"', $html);
        $this->assertStringContainsString('value="37"', $html);
        $this->assertStringContainsString('readonly', $html);
    }
}
