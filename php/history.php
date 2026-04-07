<?php
    require_once __DIR__ . '/renderers/history_renderer.php';

    $total = 0;
    $code = "";
    $type = (int)$_POST['type'];
    $page = (int)$_POST['page'];
    [$start, $end] = buildHistoryPageRange($page);
    $check = 0;
    $typename = array(' ','內用','外帶');
    $conn = new mysqli('localhost','user','12345','order');

    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $sql = buildHistorySelectQuery($type);
        $result = mysqli_query($conn,$sql);
        $count = 0;

        while($row = mysqli_fetch_row($result)){
            if(shouldCollectHistoryRow($count, $start)){
                $check = 1;
                $code .= buildHistoryRowHtml($row, $type);

                if(shouldStopCollectHistoryRow($count, $end)){
                    break;
                }
            }
            $count++;
        }

        if(hasHistoryRecords($check)){
            print(buildHistoryTableHeaderHtml($type, $typename));
            print($code);
            print(buildHistoryPaginationHtml($type, $page, (bool)mysqli_fetch_row($result)));
        }else{
            print(buildHistoryEmptyHtml($type, $typename));
        }
    }
?>
