<?php

function buildDeliverSelectQuery(): string
{
    return "SELECT * FROM `Delivers`";
}

function normalizeDeliverNum($type, string $num): string
{
    if ((int)$type === 2) {
        return $num . "號";
    }

    return $num;
}

function buildDeliverPages(array $row, array $items, int $itemCount, array $typeName): array
{
    $dish = explode(',', $row[2]);
    $entries = [];

    for ($i = 0; $i < $itemCount; $i++) {
        if (isset($dish[$i]) && $dish[$i] != 0) {
            $entries[] = [
                'name' => $items[$i],
                'count' => $dish[$i],
            ];
        }
    }

    $pages = array_chunk($entries, 10);

    $htmlPages = [];
    $pageCount = count($pages);
    $displayNum = normalizeDeliverNum($row[0], $row[1]);

    foreach ($pages as $index => $pageEntries) {
        $hasMore = $index < $pageCount - 1;
        $htmlPages[] = buildDeliverPageHtml(
            (int)$row[0],
            $typeName[(int)$row[0]],
            $displayNum,
            $pageEntries,
            $hasMore,
            $row[4]
        );
    }

    return $htmlPages;
}

function buildDeliverPageHtml(
    int $type,
    string $typeLabel,
    string $displayNum,
    array $entries,
    bool $hasMore,
    $auto
): string {
    $html = '
            <div class="listB">
            <div class="Type' . $type . '">' . $typeLabel . '</div>
            <div class="numB">' . $displayNum . '</div>
            <table>';

    foreach ($entries as $entry) {
        $html .= '
                        <tr>
                        <td class="td1">' . $entry['name'] . '</td><td class="td2">*' . $entry['count'] . '</td>
                        </tr>
                       ';
    }

    $html .= '</table>';

    if ($hasMore) {
        $html .= '<h1 style="font-size: 24px; position: absolute; bottom: 10px; right: 30px; text-decoration: underline dashed red 5px;">
                    尚有品項，接續下單 ⤳
                    </h1>';
    } else {
        $html .= '
                    <h1 style="font-size: 28px; position: absolute; bottom: 10px; right: 30px;">
                    <input id="send" name="send" type="button" value="已送餐" class="btn" onclick="done(' . $auto . ')" style="margin-left: 32px;">
                    </h1>
                    ';
    }

    $html .= '</div>';

    return $html;
}
