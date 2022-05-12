<?php
    $ToGoNum = 0;
    
    /* ----- connect mysql start ----- */
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = "SELECT * FROM `ToGoNum` WHERE 1";
        $result = mysqli_query($conn,$sql);
        $row = mysqli_fetch_row( $result );
        if($row[0]<100){
            $ToGoNum = $row[0]+1;
        }else{
            $ToGoNum = 1;
        }
    }
    /* ----- connect mysql end ----- */

    print('<div id="number2">
    <input type="text" name="type" id="type" value="2" readonly style="display: none;">
    外帶編號：
    <input type="text" name="num" id="num" class="textbox" value="'.$ToGoNum.'" readonly>
    </div>
    ');
?>