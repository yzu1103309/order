<?php
    $str = "2022-09-20";
    $year = 2022;
    $A = array(0,1,2,3,4,5,6,7,8,9,10,11,12);

    preg_match("/$year-(\d{2})-/", $str, $matches);
    $index = intval($matches[1]);
    print($A[$index]);
?>