<?php
    require_once __DIR__ . '/renderers/return_d3_renderer.php';

    $year = $_POST['year'];
    $conn = new mysqli('localhost','user','12345','order');
    $income = createMonthlyIncomeArray();

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildReturnD3SelectQuery($year);
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_row($result)){
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto; $row[5]: Date;
            $index = extractMonthIndex($year, $row[5]);
            $income = addIncomeToMonth($income, $index, (int)$row[3]);
        }
    }

    print(buildReturnD3Csv($income));
?>
