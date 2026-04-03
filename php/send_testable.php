<?php

function buildSendQueries($type, $num, $list, $total, $date, $time): array
{
    $queries = [];

    $queries[] = "INSERT INTO Orders (Type,Num,List,Total) VALUES ($type,'$num','$list',$total)";
    $queries[] = "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES ($type,'$num','$list',$total,'$date','$time')";

    if ($type == 2) {
        $queries[] = "UPDATE ToGoNum SET PreNum=$num";
    }

    return $queries;
}