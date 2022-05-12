<?php
    $Auto = $_POST['auto'];
    $Items = $_POST['items'];
    $ItemCount = $_POST['itemCount'];

    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT `List` FROM `History` WHERE Auto=$Auto";
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_row( $result );
        $dish = explode(',',$row[0]);

        for($i = 0; $i<$ItemCount; $i++){
            if($dish[$i]!=0){
                print("<tr><td class='td1'>$Items[$i]</td><td class='td2'>*$dish[$i]</td></tr>");
            }
        }
    }
?>