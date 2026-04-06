<?php

function removeHistoryByAuto(array $rows, $auto): array
{
    $filtered = [];

    foreach ($rows as $row) {
        if (!isset($row[4])) {
            $filtered[] = $row;
            continue;
        }

        if ((string)$row[4] !== (string)$auto) {
            $filtered[] = $row;
        }
    }

    return $filtered;
}

function shouldResetAutoIncrement(array $rows): bool
{
    return count($rows) === 0;
}

function simulateRemove(array $rows, $auto): array
{
    $remainingRows = removeHistoryByAuto($rows, $auto);

    return [
        'rows' => $remainingRows,
        'reset_auto_increment' => shouldResetAutoIncrement($remainingRows),
    ];
}