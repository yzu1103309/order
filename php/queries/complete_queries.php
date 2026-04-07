<?php

function buildSelectOrderByAutoQuery(int $auto): string
{
    return "SELECT * FROM `Orders` WHERE `Auto` = $auto";
}

function buildCompleteBaseQueries(int $auto, array $row): array
{
    return [
        "INSERT INTO Delivers (Type,Num,List,Total) VALUES ($row[0],'$row[1]','$row[2]',$row[3])",
        "DELETE FROM `Orders` WHERE `Auto`= $auto;"
    ];
}

function buildSelectAllOrdersQuery(): string
{
    return "SELECT * FROM `Orders`";
}

function buildPostDeleteQueries($remainingOrderRow): array
{
    if (!$remainingOrderRow) {
        return ["ALTER TABLE `Orders` AUTO_INCREMENT=1"];
    }

    return [];
}
