<?php

function buildSearchYearSelectQuery(): string
{
    return "SELECT * FROM `History`";
}

function extractYearFromDate(string $date): string
{
    preg_match('/\d{4}/', $date, $matches);
    return $matches[0];
}

function shouldAppendYear(string $year, $prev): bool
{
    return $year != $prev;
}

function collectUniqueYears(array $rows): array
{
    $years = array();
    $prev = 0;

    foreach ($rows as $row) {
        //$row[5]: Date
        $year = extractYearFromDate($row[5]);
        if (shouldAppendYear($year, $prev)) {
            $prev = $year;
            array_push($years, $year);
        }
    }

    return $years;
}

function buildYearOptionsHtml(array $years): string
{
    $html = '';

    for ($i = 0; $i < count($years); $i++) {
        $html .= '<option value="' . $years[$i] . '">' . $years[$i] . '</option>';
    }

    return $html;
}

function buildSearchYearHtml(array $years): string
{
    return '<div id="selector">
                    <input type="button" class="smallbtn" style="margin-left:20px; margin-right:65px;" value="⤺  回上一頁" onclick="historyPage()">查詢年份：
                    <select name="num" id="num" class="textbox" style="height: 30px; font-size: 18px; position: relative; top: -2px;">
                        <option value="" disabled="" selected="">請選擇</option>'
                    . buildYearOptionsHtml($years) .
                    '</select>
                    <input type="button" class="smallbtn" value="查詢" style="margin-right:20px; margin-left: 65px;" onclick="draw()">
                </div>
                <div id="drawA" style="text-align: center;">
                    <h1>提示：請於上方選取可查詢之年份</h1>
                </div>';
}
