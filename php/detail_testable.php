<?php

function buildDetailRows($items, $dishCounts, $itemCount): array
{
    $rows = [];

    for ($i = 0; $i < $itemCount; $i++) {
        if ($dishCounts[$i] != 0) {
            $rows[] = "<tr><td class='td1'>{$items[$i]}</td><td class='td2'>*{$dishCounts[$i]}</td></tr>";
        }
    }

    return $rows;
}