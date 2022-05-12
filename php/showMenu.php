<?php
    $Items = $_POST['items']; //array
    $ItemCount = $_POST['itemCount']; //9
    $TypeCount = $_POST['typeCount']; //2
    $EachCount = $_POST['eachTypeCount']; //[5,4]

    for($i = 0; $i<$TypeCount; $i++)
    {
        $startP = 0;
        for($k = 0; $k<$i; $k++){
            $startP += $EachCount[$k];
        }
        print('<div id="section">');
        for($j = $startP; $j<$startP + $EachCount[$i]; $j++){
            print('<div class="box" onclick="r('.$j.')">'.$Items[$j].'</div>');
        }
        print('</div>');

    }

    // print('
    // <div id="section">
    //     <div class="box" onclick="r(0)">紅燒牛肉麵</div>
    //     <div class="box" onclick="r(1)">清燉牛肉麵</div>
    //     <div class="box" onclick="r(2)">牛肉湯餃</div>
    //     <div class="box" onclick="r(3)">餛飩麵</div>
    //     <div class="box" onclick="r(4)">榨菜肉絲麵</div>
    // </div>
    // <div id="section">
    //     <div class="box" onclick="r(5)">滷肉飯（大）</div>
    //     <div class="box" onclick="r(6)">滷肉飯（小）</div>
    //     <div class="box" onclick="r(7)">爌肉飯</div>
    //     <div class="box" onclick="r(8)">爌肉飯便當</div>
    // </div>
    // <div id="section">
    //     <div class="box" onclick="r(9)">紅燒牛肉麵</div>
    //     <div class="box" onclick="r(10)">清燉牛肉麵</div>
    //     <div class="box" onclick="r(11)">牛肉湯餃</div>
    //     <div class="box" onclick="r(12)">餛飩麵</div>
    //     <div class="box" onclick="r(13)">榨菜肉絲麵</div>
    // </div>

    // ');
?>