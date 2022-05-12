<?php
    $TableCount = $_POST['tableCount'];
    print('<div id="number2">請選擇桌號：
    <input type="text" name="type" id="type" value="1" readonly style="display: none;">
    <select name="num" id="num" class="textbox" style="margin-left: 10px;" onchange="showMenu()">
        <option value="" disabled selected="">請選擇</option>');
        for($i = 1;$i<=$TableCount;$i++){
            if($i<10){
                $add = '0'.$i;
            }else{
                $add = $i;
            }
            print('<option value="'.$add.'桌">'.$add.'桌</option>');
        }
    print('</select></div>');
?>

<!-- 
    <option value="01桌">01桌</option>
    <option value="02桌">02桌</option>
    <option value="03桌">03桌</option>
    <option value="04桌">04桌</option>
    <option value="05桌">05桌</option>
    <option value="06桌">06桌</option>
    <option value="07桌">07桌</option>
    <option value="08桌">08桌</option>
    <option value="09桌">09桌</option>
    <option value="10桌">10桌</option> 
-->