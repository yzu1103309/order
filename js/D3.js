function searchYear(){
    $.ajax({
        url: "php/searchYear.php",
        type: "POST",
        datatype: "html",
        
        success: function( output ) {
            $('#content').html('<div style="margin: 0 auto; width: fit-content" id="historyA"></div>');
            $('#historyA').html(output);
        },
        error : function(){
        alert( "Request failed." );
        }
    });
}

function draw(){
    var year = $("#num").val();
    if( year == null ){
        $("#drawA").html("<h1>提示：請先選擇查詢年份！</h1>");
    }
    else
    {
        $.ajax({
            url: "php/returnD3.php",
            type: "POST",
            datatype: "html",
             
            data: {
                year: year
            },
            
            success: function( output ) {
                console.log( output );     
                $( "#drawA" ).html('<svg width="900" height="800"></svg>');
                var svg = d3.select("svg"),
                        margin = 150,
                        width = svg.attr("width") - margin,
                        height = svg.attr("height") - margin
    
                    svg.append("text")
                    .attr("transform", "translate(300,0)")
                    .attr("x", 50)
                    .attr("y", 40)
                    .attr("font-size", "24px")
                    .attr("font-weight", "bold")
                    .text(year+"年月份收入圖表")
    
                    var xScale = d3.scaleBand().range([0, width]).padding(0.3),
                        yScale = d3.scaleLinear().range([height, 0]);
    
                    var g = svg.append("g")
                            .attr("transform", "translate(" + 100 + "," + 100 + ")");
    
                var file = output;
                console.log( d3.csvParse(file) );
                var data = d3.csvParse(file);
    
                xScale.domain(data.map(function(d) { return d.year; }));
            yScale.domain([0, d3.max(data, function(d) { return parseInt(d.value);})]);
    
            g.append("g")
             .attr("transform", "translate(0," + height + ")")
             .call(d3.axisBottom(xScale))
             .append("text")
             .attr("y", 40)
             .attr("x", 780)
             .attr("font-size","14px")
             .attr("text-anchor", "end")
             .attr("stroke", "black")
             .text("月份");
    
            g.append("g")
             .call(d3.axisLeft(yScale).tickFormat(function(d){
                 return "$" + d;
             })
             .ticks(10))
             
             .append("text")
             .attr("font-size","14px")
             
             .attr("y", "0px")
             .attr("x", "-60px")
             .attr("font-size","14px")
             .attr("text-anchor", "end")
             .attr("stroke", "black")
             .text("收入");
    
            g.selectAll(".bar")
             .data(data)
             .enter().append("rect")
             .attr("class", "bar")
             .attr("x", function(d) { return xScale(d.year); })
             .attr("y", function(d) { return yScale(d.value); })
             .attr("width", xScale.bandwidth())
             .attr("height", function(d) { return height - yScale(d.value); });
            },
            error : function(){
            alert( "Request failed." );
            }
        });
    }
}