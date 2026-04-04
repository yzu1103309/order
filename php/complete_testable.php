<?php

function buildCompleteQueries($auto, $row, $hasRemainingOrders): array
{
    $queries = [];

    // SELECT
    $queries[] = "SELECT * FROM `Orders` WHERE `Auto` = $auto";

    // INSERT into Delivers
    $queries[] = "INSERT INTO Delivers (Type,Num,List,Total) VALUES ($row[0],'$row[1]','$row[2]',$row[3])";

    // DELETE from Orders
    $queries[] = "DELETE FROM `Orders` WHERE `Auto`= $auto;";

    // SELECT again
    $queries[] = "SELECT * FROM `Orders`";

    // if empty → reset
    if (!$hasRemainingOrders) {
        $queries[] = "ALTER TABLE `Orders` AUTO_INCREMENT=1";
    }

    return $queries;
}