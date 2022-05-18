<?php
    $total = 0;
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `History`";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_row( $result )){
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto;
            $total += $row[3];
        }
        print('<div style="text-align: center;"><h1 style="font-size: 45px;">總收入：NT$'.$total.'</h1></div>');
        print('
            <div style="width:fit-content; height: 60px; margin: 0 auto;">
                <input id="view" name="view" type="button" class="btn" value="內用紀錄" onclick="history(1,1)" style="margin: 0 20px 20px 30px; height: 50px;">
                <input id="view" name="view" type="button" class="btn" value="外帶紀錄" onclick="history(2,1)" style="margin: 0 20px 20px 20px; height: 50px;" >
                <input id="view" name="view" type="button" class="btn" value="收入圖表" onclick="searchYear()" style="margin: 0 20px 20px 20px; height: 50px;" >
                <input id="view" name="view" type="button" class="btn" value="清除所有紀錄" onclick="removeAll()" style="margin: 0 30px 20px 20px; height: 50px; background-color: #FF0000;" >
            </div>
            <div style="margin: 0 auto; width: fit-content" id="historyA">
                <h1>提示：請於上方選擇欲查詢之紀錄類別</h1>
            </div>
        ');
    }

/*
*/
        
?>