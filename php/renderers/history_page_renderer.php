<?php

function buildHistoryPageSelectQuery(): string
{
    return "SELECT * FROM `History`";
}

function calculateHistoryTotal(array $rows): int
{
    $total = 0;

    foreach ($rows as $row) {
        $total += (int)$row[3];
    }

    return $total;
}

function buildHistoryPageTotalHtml(int $total): string
{
    return '<div style="text-align: center;"><h1 style="font-size: 45px;">總收入：NT$' . $total . '</h1></div>';
}

function buildHistoryPageActionsHtml(): string
{
    return '
            <div style="width:fit-content; height: 60px; margin: 0 auto;">
                <input id="view" name="view" type="button" class="btn" value="內用紀錄" onclick="history(1,1)" style="margin: 0 20px 20px 30px; height: 50px;">
                <input id="view" name="view" type="button" class="btn" value="外帶紀錄" onclick="history(2,1)" style="margin: 0 20px 20px 20px; height: 50px;" >
                <input id="view" name="view" type="button" class="btn" value="收入圖表" onclick="searchYear()" style="margin: 0 20px 20px 20px; height: 50px;" >
                <input id="view" name="view" type="button" class="btn" value="清除所有紀錄" onclick="removeAll()" style="margin: 0 30px 20px 20px; height: 50px; background-color: #FF0000;" >
            </div>
            <div style="margin: 0 auto; width: fit-content" id="historyA">
                <h1>提示：請於上方選擇欲查詢之紀錄類別</h1>
            </div>
        ';
}

function buildHistoryPageHtml(int $total): string
{
    return buildHistoryPageTotalHtml($total) . buildHistoryPageActionsHtml();
}
