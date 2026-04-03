<?php

function extractYears(array $rows): array
{
    $years = [];
    $prev = 0;

    foreach ($rows as $row) {
        if (!isset($row[5])) {
            continue;
        }

        if (preg_match('/\d{4}/', $row[5], $matches)) {
            if ($matches[0] != $prev) {
                $prev = $matches[0];
                $years[] = $matches[0];
            }
        }
    }

    return $years;
}

function buildSearchYearOutput(array $rows): string
{
    $years = extractYears($rows);

    $output = '<div id="selector">
                    <input type="button" class="smallbtn" style="margin-left:20px; margin-right:65px;" value="⤺  回上一頁" onclick="historyPage()">查詢年份：
                    <select name="num" id="num" class="textbox" style="height: 30px; font-size: 18px; position: relative; top: -2px;">
                        <option value="" disabled="" selected="">請選擇</option>';

    for ($i = 0; $i < count($years); $i++) {
        $output .= '<option value="' . $years[$i] . '">' . $years[$i] . '</option>';
    }

    $output .= '</select>
                    <input type="button" class="smallbtn" value="查詢" style="margin-right:20px; margin-left: 65px;" onclick="draw()">
                </div>
                <div id="drawA" style="text-align: center;">
                    <h1>提示：請於上方選取可查詢之年份</h1>
                </div>';

    return $output;
}