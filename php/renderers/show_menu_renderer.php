<?php

function buildShowMenuSectionsHtml(array $items, int $typeCount, array $eachCount): string
{
    $html = '';

    for ($i = 0; $i < $typeCount; $i++) {
        $startP = calculateShowMenuStartPosition($i, $eachCount);
        $sectionItems = sliceShowMenuSectionItems($items, $startP, (int)$eachCount[$i]);
        $html .= buildShowMenuSectionHtml($sectionItems, $startP);
    }

    return $html;
}

function calculateShowMenuStartPosition(int $typeIndex, array $eachCount): int
{
    $startP = 0;

    for ($k = 0; $k < $typeIndex; $k++) {
        $startP += (int)$eachCount[$k];
    }

    return $startP;
}

function sliceShowMenuSectionItems(array $items, int $startPosition, int $count): array
{
    return array_slice($items, $startPosition, $count);
}

function buildShowMenuSectionHtml(array $sectionItems, int $startPosition): string
{
    $html = '<div id="section">';

    foreach ($sectionItems as $offset => $itemName) {
        $html .= buildShowMenuBoxHtml($startPosition + $offset, $itemName);
    }

    $html .= '</div>';

    return $html;
}

function buildShowMenuBoxHtml(int $itemIndex, string $itemName): string
{
    return '<div class="box" onclick="r(' . $itemIndex . ')">' . $itemName . '</div>';
}
