<?php

function buildShowMenuOutput(array $items, int $typeCount, array $eachCount): string
{
    $output = '';

    for ($i = 0; $i < $typeCount; $i++) {
        $startP = 0;

        for ($k = 0; $k < $i; $k++) {
            $startP += $eachCount[$k];
        }

        $output .= '<div id="section">';

        for ($j = $startP; $j < $startP + $eachCount[$i]; $j++) {
            $output .= '<div class="box" onclick="r(' . $j . ')">' . $items[$j] . '</div>';
        }

        $output .= '</div>';
    }

    return $output;
}