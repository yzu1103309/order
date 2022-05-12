<?php
    $Type = $_POST['type'];
    $Num = $_POST['num'];
    $List = $_POST['list'];
    $Total = $_POST['total'];

    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');
    $conn->set_charset("utf8");

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "INSERT INTO Orders (Type,Num,List,Total) VALUES ($Type,'$Num','$List',$Total)";
        mysqli_query($conn,$sql);
        $Date = date("Y-m-d");
        $Time = date("H:i");
        $sql = "INSERT INTO History (Type,Num,List,Total,Date,Time) VALUES ($Type,'$Num','$List',$Total,'$Date','$Time')";
        mysqli_query($conn,$sql);
    }

    if($Type == 2){
        $sql = "UPDATE ToGoNum SET PreNum=$Num";
        mysqli_query($conn,$sql);
    }
    /* ----- connect mysql end ----- */
?>