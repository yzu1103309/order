<?php
    $year = $_POST['year'];
    $conn = new mysqli('localhost','user','12345','order');
    $income = array('',0,0,0,0,0,0,0,0,0,0,0,0,0);

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `History` WHERE Date LIKE '$year%'";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_row( $result )){
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto; $row[5]: Date;
            preg_match("/$year-(\d{2})-/", $row[5], $matches);
            $index = intval($matches[1]); //get the int value
            $income[$index] += $row[3];
        }
    }
    print("year,value\n");
    for($i = 1 ; $i <= 12; $i++){
        $month;
        if($i<10)
            $month = '0'.$i;
        else
            $month = $i;
        print($month.'月,'.$income[$i]."\n");
    }
    //print("year,value\n01月,10320\n02月,20975\n03月,50000\n04月,55875\n05月,30605\n06月,39600\n07月,20500\n08月,25050\n09月,35600\n10月,24355\n11月,30520\n12月,42390");
    
?>