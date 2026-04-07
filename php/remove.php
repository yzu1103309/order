<?php
require_once __DIR__ . '/queries/remove_queries.php';

$Auto = (int)$_POST['auto'];
$conn = new mysqli('localhost','user','12345','order');

if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}else{
    $sql = buildDeleteHistoryByAutoQuery($Auto);
    mysqli_query($conn,$sql);

    $sql = buildSelectAllHistoryQuery();
    $result = mysqli_query($conn,$sql);
    $remainingRow = mysqli_fetch_row($result);

    $postDeleteQueries = buildRemovePostDeleteQueries($remainingRow);
    foreach ($postDeleteQueries as $sql) {
        mysqli_query($conn,$sql);
    }
}
?>
