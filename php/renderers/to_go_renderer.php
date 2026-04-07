<?php

function buildToGoNumSelectQuery(): string
{
    return "SELECT * FROM `ToGoNum` WHERE 1";
}

function calculateNextToGoNum($currentNum): int
{
    if ((int)$currentNum < 100) {
        return (int)$currentNum + 1;
    }

    return 1;
}

function buildToGoHtml(int $toGoNum): string
{
    return '<div id="number2">
    <input type="text" name="type" id="type" value="2" readonly style="display: none;">
    外帶編號：
    <input type="text" name="num" id="num" class="textbox" value="' . $toGoNum . '" readonly>
    </div>
    ';
}
