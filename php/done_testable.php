<?php

function buildDoneQueries($auto, $hasRemainingRows): array
{
    $queries = [];

    $queries[] = "DELETE FROM `Delivers` WHERE `Auto`= $auto;";
    $queries[] = "SELECT * FROM `Delivers`";

    if (!$hasRemainingRows) {
        $queries[] = "ALTER TABLE `Delivers` AUTO_INCREMENT=1";
    }

    return $queries;
}