<?php
    $Auto = $_POST['Auto'];
    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `Orders` WHERE `Auto` = $Auto";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_row( $result );
        //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto;
        $sql = "INSERT INTO Delivers (Type,Num,List,Total) VALUES ($row[0],'$row[1]','$row[2]',$row[3])";
        mysqli_query($conn,$sql);
        $sql = "DELETE FROM `Orders` WHERE `Auto`= $Auto;";
        mysqli_query($conn,$sql);
        
        $sql = "SELECT * FROM `Orders`";
        $result = mysqli_query($conn,$sql);
        if(!($row = mysqli_fetch_row( $result ))){
            $sql = 'ALTER TABLE `Orders` AUTO_INCREMENT=1';
            mysqli_query($conn,$sql);
        };
    }
    /* ----- connect mysql end ----- */
?>