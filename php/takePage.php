<?php
    require_once __DIR__ . '/renderers/take_page_renderer.php';

    $TypeName = array(' ','內用','外帶');
    $Items = $_POST['items'];
    $ItemCount = (int)$_POST['itemCount'];
    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildTakeSelectQuery();
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_row($result)){
            $pages = buildTakePages($row, $Items, $ItemCount, $TypeName);

            foreach ($pages as $pageHtml) {
                print($pageHtml);
            }
        }
    }
    /* ----- connect mysql end ----- */
?>
