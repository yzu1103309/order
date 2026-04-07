<?php
    require_once __DIR__ . '/renderers/search_year_renderer.php';

    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildSearchYearSelectQuery();
        $result = mysqli_query($conn,$sql);

        $rows = array();
        while($row = mysqli_fetch_row($result)){
            $rows[] = $row;
        }

        $years = collectUniqueYears($rows);
        print(buildSearchYearHtml($years));
    }
?>
