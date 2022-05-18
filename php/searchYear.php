<?php
    $conn = new mysqli('localhost','user','12345','order');
    $years = array();
    $prev = 0;

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `History`";
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_row( $result )){
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto; $row[5]: Date;
            preg_match('/\d{4}/', $row[5], $matches);
            if( $matches[0] != $prev ){
                $prev = $matches[0];
                array_push($years, $matches[0]);
            }
        }
        print('<div id="selector">
                    <input type="button" class="smallbtn" style="margin-left:20px; margin-right:65px;" value="⤺  回上一頁" onclick="historyPage()">查詢年份：
                    <select name="num" id="num" class="textbox" style="height: 30px; font-size: 18px; position: relative; top: -2px;">
                        <option value="" disabled="" selected="">請選擇</option>');
             for( $i = 0; $i < count($years); $i++){
                 print('<option value="'.$years[$i].'">'.$years[$i].'</option>');
             }
             print('</select>
                    <input type="button" class="smallbtn" value="查詢" style="margin-right:20px; margin-left: 65px;" onclick="draw()">
                </div>
                <div id="drawA" style="text-align: center;">
                    <h1>提示：請於上方選取可查詢之年份</h1>
                </div>');
    }
?>