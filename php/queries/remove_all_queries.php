<?php

function buildRemoveAllHistoryQueries(): array
{
    return [
        'DELETE FROM `History` WHERE 1',
        'ALTER TABLE `History` AUTO_INCREMENT=1',
    ];
}
