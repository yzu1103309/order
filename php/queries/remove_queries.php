<?php

function buildDeleteHistoryByAutoQuery(int $auto): string
{
    return "DELETE FROM `History` WHERE Auto=$auto";
}

function buildSelectAllHistoryQuery(): string
{
    return "SELECT * FROM `History`";
}

function buildRemovePostDeleteQueries($remainingHistoryRow): array
{
    if (!$remainingHistoryRow) {
        return ["ALTER TABLE `History` AUTO_INCREMENT=1"];
    }

    return [];
}
