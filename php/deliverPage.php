<?php
    $TypeName = array(' ','內用','外帶');
    $Items = $_POST['items'];
    $ItemCount = $_POST['itemCount'];
    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');
    $check = 0;

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `Delivers`";
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_row( $result )){
            if($row[0] == 2){
                $row[1] = $row[1]."號";
            }
            $count = 0;
            //$row[0]: Type; $row[1]: Num; $row[2]: List; $row[4]: Auto;
            $dish = explode(',',$row[2]);
            print('
            <div class="listB">
            <div class="Type'.$row[0].'">'.$TypeName[$row[0]].'</div>
            <div class="numB">'.$row[1].'</div>
            <table>');
               for($i = 0; $i<$ItemCount; $i++){
                   if($dish[$i]!=0){
                       $count++;
                       print('
                        <tr>
                        <td class="td1">'.$Items[$i].'</td><td class="td2">*'.$dish[$i].'</td>
                        </tr>
                       ');
                   }
                   if($count>=10 && $i<$ItemCount-1){
                       for($j=$i+1;$j<$ItemCount;$j++){
                           if($dish[$j]!=0){
                               $check = 1;
                               break;
                           }
                       }
                       break;
                   }
               }
               
                print('</table>');
                if($check == 0){ 
                    print('
                    <h1 style="font-size: 28px; position: absolute; bottom: 10px; right: 30px;">
                    <input id="send" name="send" type="button" value="已送餐" class="btn" onclick="done('.$row[4].')" style="margin-left: 32px;">
                    </h1>
                    ');
                }else
                {
                    print('<h1 style="font-size: 24px; position: absolute; bottom: 10px; right: 30px; text-decoration: underline dashed red 5px;">
                    尚有品項，接續下單 ⤳
                    </h1>');
                }
                print('</div>'); 
                
                
                              

            while($check == 1){
                $check = 0;
                $count = 0;
                print('
            <div class="listB">
            <div class="Type'.$row[0].'">'.$TypeName[$row[0]].'</div>
            <div class="numB">'.$row[1].'</div>
            <table>');
               for($i = 10; $i<$ItemCount; $i++){
                   if($dish[$i]!=0){
                       $count++;
                       print('
                        <tr>
                        <td class="td1">'.$Items[$i].'</td><td class="td2">*'.$dish[$i].'</td>
                        </tr>
                       ');
                   }
                   if($count>=10 && $i<$ItemCount-1){
                    for($j=$i+1;$j<$ItemCount;$j++){
                        if($dish[$j]!=0){
                            $check = 1;
                            break;
                        }
                    }
                    break;
                }
               }                
            print('</table>');
            if($check == 0){ 
                print('
                <h1 style="font-size: 28px; position: absolute; bottom: 10px; right: 30px;">
                <input id="send" name="send" type="button" value="完成送餐" class="btn" onclick="done('.$row[4].')" style="margin-left: 32px;">
                </h1>
                ');
            }else
            {
                print('<h1 style="font-size: 24px; position: absolute; bottom: 10px; right: 30px;">
                尚有品項，接續下單 ⤳
                </h1>');
            }
            print('
            </div>
            ');
            }
        }
    }
    /* ----- connect mysql end ----- */
?>