<?php
require_once __DIR__ . '/queries/complete_queries.php';

$Auto = (int)$_POST['Auto'];

/* ----- connect mysql start ----- */
$conn = new mysqli('localhost', 'user', '12345', 'order');

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    $sql = buildSelectOrderByAutoQuery($Auto);
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_row($result);

    $baseQueries = buildCompleteBaseQueries($Auto, $row);
    foreach ($baseQueries as $sql) {
        mysqli_query($conn, $sql);
    }

    $sql = buildSelectAllOrdersQuery();
    $result = mysqli_query($conn, $sql);
    $remainingRow = mysqli_fetch_row($result);

    $postDeleteQueries = buildPostDeleteQueries($remainingRow);
    foreach ($postDeleteQueries as $sql) {
        mysqli_query($conn, $sql);
    }
}
/* ----- connect mysql end ----- */
?>
