<?php
    require_once __DIR__ . '/renderers/dine_in_renderer.php';

    $TableCount = (int)$_POST['tableCount'];
    print(buildDineInSelectorHtml($TableCount));
?>
