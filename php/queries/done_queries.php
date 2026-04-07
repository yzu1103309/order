<?php

function buildDeleteDeliverByAutoQuery(int $auto): string
{
    return "DELETE FROM `Delivers` WHERE `Auto`= $auto;";
}

function buildSelectAllDeliversQuery(): string
{
    return "SELECT * FROM `Delivers`";
}

function buildDonePostDeleteQueries($remainingDeliverRow): array
{
    if (!$remainingDeliverRow) {
        return ["ALTER TABLE `Delivers` AUTO_INCREMENT=1"];
    }

    return [];
}
