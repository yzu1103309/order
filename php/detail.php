<?php
    require_once __DIR__ . '/renderers/detail_renderer.php';

    $Auto = (int)$_POST['auto'];
    $Items = $_POST['items'];
    $ItemCount = (int)$_POST['itemCount'];

    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildDetailSelectQuery($Auto);
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_row($result);
        $dish = explode(',',$row[0]);

        print(buildDetailRowsHtml($Items, $ItemCount, $dish));
    }
?>
