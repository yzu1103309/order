<?php

function removeAllHistory(array $rows): array
{
    return [];
}

function shouldResetAutoIncrementAfterRemoveAll(): bool
{
    return true;
}

function simulateRemoveAll(array $rows): array
{
    return [
        'rows' => removeAllHistory($rows),
        'reset_auto_increment' => shouldResetAutoIncrementAfterRemoveAll(),
    ];
}