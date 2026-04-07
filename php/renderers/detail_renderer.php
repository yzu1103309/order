<?php

function buildDetailSelectQuery(int $auto): string
{
    return "SELECT `List` FROM `History` WHERE Auto=$auto";
}

function buildDetailRowsHtml(array $items, int $itemCount, array $dish): string
{
    $html = '';

    for ($i = 0; $i < $itemCount; $i++) {
        if (shouldRenderDetailRow($dish, $i)) {
            $html .= buildDetailRowHtml($items[$i], $dish[$i]);
        }
    }

    return $html;
}

function shouldRenderDetailRow(array $dish, int $index): bool
{
    return isset($dish[$index]) && $dish[$index] != 0;
}

function buildDetailRowHtml(string $itemName, $count): string
{
    return "<tr><td class='td1'>{$itemName}</td><td class='td2'>*{$count}</td></tr>";
}
