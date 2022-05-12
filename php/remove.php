<?php
    $Auto = $_POST['auto'];
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "DELETE FROM `History` WHERE Auto=$Auto";
        mysqli_query($conn,$sql);
    }

    $sql = "SELECT * FROM `History`";
    $result = mysqli_query($conn,$sql);
    if(!($row = mysqli_fetch_row( $result ))){
        $sql = 'ALTER TABLE `History` AUTO_INCREMENT=1';
        mysqli_query($conn,$sql);
    };
?>