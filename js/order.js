var originD = Array(itemCount).fill(0);
var dish = Array(itemCount).fill(0);

var btn = '<input id="confirm" name="confirm" type="button" value="確認送單" class="btn" onclick="sendConfirm()" style="float: right; margin-right: 20px;">';
var total = 0;

function r(num){
    for(var i = 0; i<itemCount; i++){
        originD[i] = dish[i];
    }
    dish[num]++;
    refresh();
}
function decr(num){
    for(var i = 0; i<itemCount; i++){
        originD[i] = dish[i];
    }
    if(dish[num]>0){
        dish[num]--;
    }
    
    refresh();
}

function refresh(){
    total = 0;
    var check = 0;
    var code = "";
    //console.log('Org',originD);
    //console.log('dish',dish);
    for(var i = 0; i<itemCount; i++){
        if(originD[i] != dish[i] && dish[i] != 0){
            check = 1;
            code += "<tr style='background-color: #F0B27A;'><td class='td1'>"+
            "<img src='./pics/minus.png' class='picBtn' onclick='decr("+i+")'>"+
            "<img src='./pics/add.png' class='picBtn' onclick='r("+i+")'>"
            +items[i]+"</td><td class='td2'>*"+dish[i]+"</td></tr>";
            total += price[i] * dish[i];
        }else if(dish[i] != 0){
            check = 1;
            code += "<tr><td class='td1'>"+
            "<img src='./pics/minus.png' class='picBtn' onclick='decr("+i+")'>"+
            "<img src='./pics/add.png' class='picBtn' onclick='r("+i+")'>"
            +items[i]+"</td><td class='td2'>*"+dish[i]+"</td></tr>";
            total += price[i] * dish[i];
        }
    }
    $( "#list" ).html(code);
    if(check == 1){
        $( "#sendBtn" ).html(btn);
        $( "#totalW" ).html("總計：NT$ ");
        $( "#total" ).html(total);
    }else{
        $( "#sendBtn" ).html(" ");
        $( "#totalW" ).html(" ");
        $( "#total" ).html(" ");
    }
}
function clear(){
    for(var i = 0; i<itemCount; i++){
        originD[i] = 0;
        dish[i] = 0;
    }
    refresh();
}
function sendConfirm(){
    total = 0;
    var code = "";
    for(var i = 0; i<itemCount; i++){
        if(dish[i] != 0){
            code += "<tr><td class='td1'>"+
            items[i]+"</td><td class='td2'>*"+dish[i]+"</td></tr>";
            total += price[i] * dish[i];
        }
    }
    $( "#confirmList" ).html(code);
    $( "#total2" ).html(total);
    $("#confirmB").css("display","block");
    $("#content").css({"filter": "blur(10px)","-webkit-filter": "blur(10px)","pointer-events": "none"});
    $( "#notify" ).html('<audio id="sound"><source src="audio/'+'notify.mp3'+'" type="audio/mpeg"></audio>');
    document.getElementById("sound").play();
}
function back(){
    $("#confirmB").css("display","none");
    $("#content").css({"filter": "blur(0px)","-webkit-filter": "blur(0px)","pointer-events": "initial"});
}
function send(){
    var number = $( "#num" ).val();
    console.log('number',number);
    var type = $( "#type" ).val();
    var list = "";
    for(var i = 0; i<itemCount-1; i++){
        list += dish[i]+",";
    }
    list += dish[itemCount-1];
    $.ajax({
        url: "php/send.php",
        type: "POST",
        datatype: "html",
        data:{
            num: number,
            type: type,
            list: list,
            total: total
        },
      success: function( output ) {
        //alert("已成功送出訂單。");
        orderPage();
        $("#confirmB").css("display","none");
        $("#content").css({"filter": "blur(0px)","-webkit-filter": "blur(0px)","pointer-events": "initial"});
        $( "#confirmList" ).html(" ");
      },
        error : function(){
        alert( "未成功送出訂單，請重試。" );
        }
    });
}
