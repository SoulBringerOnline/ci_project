function plot_chart(  target, lines_info , time_format , x_ticks ) {
    var data = []
    for (i=0;i<lines_info.length;i++)
    {
        data[i] = {}
        data[i].label = lines_info[i].label
        data[i].data = lines_info[i].data
        data[i].color = lines_info[i].color
        data[i].lines = {show: true,lineWidth: 1 } 
        data[i].points = {show: lines_info[i].show_points}
    }

    var options = {
        series: {
                lines: { 
                    show: true,
                    lineWidth: 1,
                    fill: true, fillColor: { colors: [ { opacity: 0.8 }, { opacity: 0.3 } ,{ opacity: 0.2 } ] }
                },
                points: { show: false },
                shadowSize: 0
            },
            legend:{   
                position: "se" ,
        },
        grid: {
            hoverable: true
        },
        tooltip: true,
        tooltipOpts: {
            content: '%y',
        },
        xaxis: {
            mode: "time" , 
            timeformat: time_format,
            ticks:x_ticks
        },
        yaxes: {
            tickFormatter: function (val, axis) {
                return "$" + val;
            },
        }

    };
    $.plot(target, data, options);
}
