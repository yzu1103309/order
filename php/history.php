<?php
    $total = 0;
    $code = "";
    $type = $_POST['type'];
    $check = 0;
    $typename = array(' ','內用','外帶');
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `History`";
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_row( $result )){
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[3]: Total; $row[4]: Auto; $row[5]: Date; : Time;
            if($row[0] == $type){
                $check = 1;
                if($type == 2){
                    $row[1] .= '號';
                }
                $code = '<tr> 
                    <td>'.$row[1].'</td> 
                    <td> NT$'.$row[3].' </td> 
                    <td>'.$row[5].'</td>
                    <td>'.$row[6].'</td>
                    <td style="width:0px;">
                    <input id="view" name="view" type="button" class="btn" style="width: 100px; margin-left: 8px;" value="詳情" onclick="view('.$row[4].','.$row[0].')"></td>
                </tr>'.$code;
            }
        }
        if($check == 1){
            print('<table class="history-table">');
            print('<tr> 
            <td style="width:150px;"><div class="history-type'.$type.'">'.$typename[$type].'</div></td>
            <td style="width:190px;">金額</td> 
            <td style="width:220px;">訂單日期</td>
            <td style="width:190px;">時間</td>
            <td style="width:110px;">選項</td>
            </tr>
            ');
            print($code);
            print('</table>');
        }else{
            print("<h1>查無任何".$typename[$type]."之紀錄！</h1>");
        }
        
    }
?>