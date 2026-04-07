<?php
require_once __DIR__ . '/queries/remove_all_queries.php';

$conn = new mysqli('localhost','user','12345','order');

if($conn->connect_error){
    die('Connection Failed : '.$conn->connect_error);
}else{
    $queries = buildRemoveAllHistoryQueries();

    foreach ($queries as $sql) {
        mysqli_query($conn,$sql);
    }
}
?>
