<?php

function buildDineInTableOptionsHtml(int $tableCount): string
{
    $html = '';

    for ($i = 1; $i <= $tableCount; $i++) {
        $html .= buildDineInTableOptionHtml($i);
    }

    return $html;
}

function buildDineInTableOptionHtml(int $tableNumber): string
{
    $displayNumber = formatDineInTableNumber($tableNumber);

    return '<option value="' . $displayNumber . '桌">' . $displayNumber . '桌</option>';
}

function formatDineInTableNumber(int $tableNumber): string
{
    if ($tableNumber < 10) {
        return '0' . $tableNumber;
    }

    return (string)$tableNumber;
}

function buildDineInSelectorHtml(int $tableCount): string
{
    return '<div id="number2">請選擇桌號：
    <input type="text" name="type" id="type" value="1" readonly style="display: none;">
    <select name="num" id="num" class="textbox" style="margin-left: 10px;" onchange="showMenu()">
        <option value="" disabled selected="">請選擇</option>'
        . buildDineInTableOptionsHtml($tableCount) .
    '</select></div>';
}
