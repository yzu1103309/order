<?php
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = 'DELETE FROM `History` WHERE 1';
        mysqli_query($conn,$sql);
        $sql = 'ALTER TABLE `History` AUTO_INCREMENT=1';
        mysqli_query($conn,$sql);
    }
?>