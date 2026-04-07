<?php
    require_once __DIR__ . '/renderers/show_menu_renderer.php';

    $Items = $_POST['items']; //array
    $ItemCount = (int)$_POST['itemCount']; //9
    $TypeCount = (int)$_POST['typeCount']; //2
    $EachCount = $_POST['eachTypeCount']; //[5,4]

    print(buildShowMenuSectionsHtml($Items, $TypeCount, $EachCount));
?>
