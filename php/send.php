<?php
require_once __DIR__ . '/queries/send_queries.php';

$Type = (int)$_POST['type'];
$Num = $_POST['num'];
$List = $_POST['list'];
$Total = (int)$_POST['total'];

/* ----- connect mysql start ----- */
$conn = new mysqli('localhost','user','12345','order');
$conn->set_charset("utf8");

if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}else{
    $Date = date("Y-m-d");
    $Time = date("H:i");

    $baseQueries = buildSendBaseQueries($Type, $Num, $List, $Total, $Date, $Time);
    foreach ($baseQueries as $sql) {
        mysqli_query($conn,$sql);
    }

    $postQueries = buildSendPostQueries($Type, $Num);
    foreach ($postQueries as $sql) {
        mysqli_query($conn,$sql);
    }
}
/* ----- connect mysql end ----- */
?>
