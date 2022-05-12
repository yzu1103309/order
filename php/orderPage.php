<?php
    print('
    <div id="orderList">
        <h1 style="margin-left:20px;">Order List
        <span id="sendBtn"></span></h1>
        <div id="number">
        </div>
        <table id="list">
        </table>
        <h1 id="totalSec" style="text-align: right; padding-right: 20px; font-size: 28px;"><span id="totalW"></span><span id="total"></span></h1>
    </div>
    <div id="type">
    <input id="dineIn" name="dineIn" type="button" class="btn" value="內用" onclick="dineIn()">
    <input id="toGo" name="toGo" type="button" class="btn" value="外帶" onclick="toGo()" style="margin-left: 30px;">
    </div>
    <div id="menu">
        <h1>提示：選擇內用或者外帶</h1>
    </div>
    ');
?>