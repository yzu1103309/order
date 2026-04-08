<?php
    require_once __DIR__ . '/renderers/history_page_renderer.php';

    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildHistoryPageSelectQuery();
        $result = mysqli_query($conn,$sql);

        $rows = [];
        while($row = mysqli_fetch_row($result)){
            $rows[] = $row;
        }

        $total = calculateHistoryTotal($rows);
        print(buildHistoryPageHtml($total));
    }
?>
