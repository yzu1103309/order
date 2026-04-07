<?php
require_once __DIR__ . '/queries/done_queries.php';

$Auto = (int)$_POST['Auto'];
/* ----- connect mysql start ----- */
$conn = new mysqli('localhost', 'user', '12345', 'order');

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    $sql = buildDeleteDeliverByAutoQuery($Auto);
    mysqli_query($conn, $sql);

    $sql = buildSelectAllDeliversQuery();
    $result = mysqli_query($conn, $sql);
    $remainingRow = mysqli_fetch_row($result);

    $postDeleteQueries = buildDonePostDeleteQueries($remainingRow);
    foreach ($postDeleteQueries as $sql) {
        mysqli_query($conn, $sql);
    }
}
/* ----- connect mysql end ----- */
?>
