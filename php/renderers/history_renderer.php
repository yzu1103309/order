<?php

function buildHistorySelectQuery(int $type): string
{
    return "SELECT * FROM `History` WHERE Type=$type ORDER BY `History`.`Date` DESC, `History`.`Time` DESC";
}

function buildHistoryPageRange(int $page): array
{
    $start = ($page - 1) * 7;
    $end = ($page * 7) - 1;

    return [$start, $end];
}

function normalizeHistoryNum(int $type, string $num): string
{
    if ($type === 2) {
        return $num . '號';
    }

    return $num;
}

function buildHistoryRowHtml(array $row, int $type): string
{
    $displayNum = normalizeHistoryNum($type, $row[1]);

    return '<tr>
                    <td>' . $displayNum . '</td>
                    <td> NT$' . $row[3] . ' </td>
                    <td>' . $row[5] . '</td>
                    <td>' . $row[6] . '</td>
                    <td style="width:0px;">
                    <input id="view" name="view" type="button" class="btn" style="width: 100px; margin-left: 8px;" value="詳情" onclick="view(' . $row[4] . ',' . $row[0] . ')"></td>
                </tr>';
}

function shouldCollectHistoryRow(int $count, int $start): bool
{
    return $count >= $start;
}

function shouldStopCollectHistoryRow(int $count, int $end): bool
{
    return $count == $end;
}

function hasHistoryRecords(int $check): bool
{
    return $check == 1;
}

function buildHistoryTableHeaderHtml(int $type, array $typename): string
{
    return '<div id="table-wrap" style="height: 580px;"><table class="history-table">'
        . '<tr>
            <td style="width:150px;"><div class="history-type' . $type . '">' . $typename[$type] . '</div></td>
            <td style="width:190px;">金額</td>
            <td style="width:220px;">訂單日期</td>
            <td style="width:190px;">時間</td>
            <td style="width:110px;">選項</td>
            </tr>
            ';
}

function buildHistoryPaginationHtml(int $type, int $page, bool $hasNextPage): string
{
    $html = "</table></div><div id='TurnPage'>";

    if ($page != 1) {
        $prev = $page - 1;
        $html .= '<input type="button" class="smallbtn" style="margin-left:20px; margin-right:100px;" value="上一頁" onclick="history(' . $type . ',' . $prev . ')">';
    } else {
        $html .= '<input type="button" class="smallbtn" style="margin-left:20px; margin-right:100px;" value="已達首頁">';
    }

    $html .= " - 第{$page}頁 - ";

    if ($hasNextPage) {
        $next = $page + 1;
        $html .= '<input type="button" class="smallbtn" value="下一頁" style="margin-right:20px; margin-left: 100px;" onclick="history(' . $type . ',' . $next . ')"></div>';
    } else {
        $html .= '<input type="button" class="smallbtn" value="已達末頁" style="margin-right:20px; margin-left: 100px;"></div>';
    }

    return $html;
}

function buildHistoryEmptyHtml(int $type, array $typename): string
{
    return "<h1>查無任何" . $typename[$type] . "之紀錄！</h1>";
}
