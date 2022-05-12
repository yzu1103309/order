var Count1 = null;
var Count2 = null;
var notify1 = '<audio id="sound"><source src="audio/'+soundFileName1+'" type="audio/mpeg"></audio>';
var notify2 = '<audio id="sound"><source src="audio/'+soundFileName2+'" type="audio/mpeg"></audio>';
//to show the order page
function orderPage() {
    $.ajax({
        url: "php/orderPage.php",
        type: "POST",
        datatype: "html",
        success: function( output ) {
            $( "#titleText" ).html("點餐管理系統 / 點餐頁面");
            $( "#content" ).html(output);
        },
          error : function(){
          alert( "Request failed." );
        }
    });
}
function dineIn() {
    $( "#menu" ).html(" ");
    $.ajax({
        url: "php/dineIn.php",
        type: "POST",
        datatype: "html",
        data: {
            tableCount: tableCount
        },
      success: function( output ) {
          $( "#number" ).html(output);
          $( "#menu" ).html("<h1>提示：請於右方選擇桌號</h1>");
      },
        error : function(){
        alert( "Request failed." );
        }
    });
    clear();
}
function toGo() {
    $( "#menu" ).html(" ");
    $.ajax({
        url: "php/toGo.php",
        type: "POST",
        datatype: "html",
      success: function( output ) {
          $( "#number" ).html(output);
      },
        error : function(){
        alert( "Request failed." );
        }
    });
    showMenu();
}
function showMenu() {
    $.ajax({
        url: "php/showMenu.php",
        type: "POST",
        datatype: "html",
        data: {
            items: items,
            itemCount: itemCount,
            typeCount: typeCount,
            eachTypeCount: eachTypeCount
        },
      success: function( output ) {
          $( "#menu" ).html(output);
      },
        error : function(){
        alert( "Request failed." );
        }
    });
    clear();
  }
function takePage(){
    $.ajax({
        url: "php/takePage.php",
        type: "POST",
        datatype: "html",
        data: {
            items: items,
            itemCount: itemCount
        },
      success: function( output ) {
        $( "#content" ).html(output);
        Count2 = $('.listB').length;
        if(Count2>Count1 && Count1!=null){
            $( "#notify" ).html(notify1);
            document.getElementById("sound").play();
        }
        Count1 = Count2;
      },
        error : function(){
        alert( "Request failed." );
        clearInterval(interval01 );
        }
    });
}
function deliverPage(){
    $.ajax({
        url: "php/deliverPage.php",
        type: "POST",
        datatype: "html",
        data: {
            items: items,
            itemCount: itemCount
        },
      success: function( output ) {
        $( "#content" ).html(output);
        Count2 = $('.listB').length;
        if(Count2>Count1 && Count1!=null){
            $( "#notify" ).html(notify2);
            document.getElementById("sound").play();
        }
        Count1 = Count2;
      },
        error : function(){
        alert( "Request failed." );
        clearInterval(interval02);
        }
    });
}
var interval01 = null;
var interval02 = null;
function autoRefresh01(){
    takePage();
    $( "#titleText" ).html("點餐管理系統 / 接單頁面");
    interval01 = window.setInterval("takePage()",1000);
}
function autoRefresh02(){
    deliverPage();
    $( "#titleText" ).html("點餐管理系統 / 送餐頁面");
    interval02 = window.setInterval("deliverPage()",1000);
}
function complete(Auto){
    $.ajax({
        url: "php/complete.php",
        type: "POST",
        datatype: "html",
        data: {
            Auto: Auto
        },
        success: function( output ) {
            takePage();
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}
function done(Auto){
    $.ajax({
        url: "php/done.php",
        type: "POST",
        datatype: "html",
        data: {
            Auto: Auto
        },
        success: function( output ) {
            deliverPage();
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}

function historyPage(){
    $.ajax({
        url: "php/historyPage.php",
        type: "POST",
        datatype: "html",
        /*
        data: {
            Auto: Auto
        },
        */
        success: function( output ) {
            $( "#titleText" ).html("點餐管理系統 / 歷史訂單");
            $("#content").html(output);
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}

function history(type){
    $.ajax({
        url: "php/history.php",
        type: "POST",
        datatype: "html",
        data: {
            type: type
        },
        success: function( output ) {
            $("#historyA").html(output);
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}
function view(auto,type){
    $.ajax({
        url: "php/detail.php",
        type: "POST",
        datatype: "html",
        data: {
            auto: auto,
            items: items,
            itemCount: itemCount
        },
        success: function( output ) {
            $("#detailedList").html(output);
            $("#detailB").css("display","block");
            $("#content").css({"filter": "blur(10px)","-webkit-filter": "blur(10px)","pointer-events": "none"});
            document.getElementById("remove").setAttribute("onclick", "remove("+auto+","+type+")");
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}

function back02(){
    $("#detailB").css("display","none");
    $("#content").css({"filter": "blur(0px)","-webkit-filter": "blur(0px)","pointer-events": "initial"});
}

function remove(auto,type){
    var result = confirm("您確定要刪除這ㄧ筆紀錄嗎？\n注意：這項操作無法復原！");
    if(result == 1){
        $.ajax({
            url: "php/remove.php",
            type: "POST",
            datatype: "html",
            data: {
                auto: auto,
            },
            success: function( output ) {
                $("#detailB").css("display","none");
                $("#content").css({"filter": "blur(0px)","-webkit-filter": "blur(0px)","pointer-events": "initial"});
                historyPage();
                history(type);
            },
            error : function(){
                alert( "Error:暫時無法刪除" );
            }
        });
    }
}

function removeAll(){
    var result = confirm("您確定要刪除「所有」紀錄（包括收入）嗎？\n注意：這項操作無法復原！");
    if(result == 1){
        $.ajax({
            url: "php/removeAll.php",
            type: "POST",
            datatype: "html",
            
            success: function( output ) {
                historyPage();
            },
            error : function(){
                alert( "Error:暫時無法刪除" );
            }
        });
    }
}
