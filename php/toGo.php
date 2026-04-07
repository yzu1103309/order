<?php
    require_once __DIR__ . '/renderers/to_go_renderer.php';

    $ToGoNum = 0;

    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildToGoNumSelectQuery();
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_row($result);
        $ToGoNum = calculateNextToGoNum($row[0]);
    }
    /* ----- connect mysql end ----- */

    print(buildToGoHtml($ToGoNum));
?>
