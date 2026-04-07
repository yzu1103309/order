<?php

function buildInsertOrderQuery(int $type, string $num, string $list, int $total): string
{
    return "INSERT INTO Orders (Type,Num,List,Total) VALUES ($type,'$num','$list',$total)";
}

function buildInsertHistoryQuery(int $type, string $num, string $list, int $total, string $date, string $time): string
{
    return "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES ($type,'$num','$list',$total,'$date','$time')";
}

function buildUpdateToGoNumQuery(int $num): string
{
    return "UPDATE ToGoNum SET PreNum=$num";
}

function buildSendBaseQueries(
    int $type,
    string $num,
    string $list,
    int $total,
    string $date,
    string $time
): array {
    return [
        buildInsertOrderQuery($type, $num, $list, $total),
        buildInsertHistoryQuery($type, $num, $list, $total, $date, $time),
    ];
}

function buildSendPostQueries(int $type, string $num): array
{
    if ($type === 2) {
        return [buildUpdateToGoNumQuery((int)$num)];
    }

    return [];
}
