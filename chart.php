<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Chart</title>
</head>
<style>
.highcharts-figure, .highcharts-data-table table {
    min-width: 360px; 
    width: 1000px;
    max-width: 1000px;
    margin: 1em auto;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}

</style>
<body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<figure class="highcharts-figure">
    <div id="container2"></div>
    <p class="highcharts-description">
    </p>
</figure>
</body>

<script>
	total=[]
	time=[]
	tcp=[]
	udp=[]

	$.getJSON('chartapi.php', function(data) {
    		//console.log(data)
    		for(i=0;i<data.length;i++){
    			//console.log(data[i].count)
    			total.push(data[i].count)
    			time.push(data[i].time)
    			tcp.push(data[i].tcp)
    			udp.push(data[i].udp)
    		}

	});
setTimeout(function(){ test(total,time,tcp,udp) }, 2000);

function test(total,time,tcp,udp){
Highcharts.chart('container2', {

    title: {
        text: 'Network Traffic'
    },
    yAxis: {
        title: {
            text: 'Number of Hits'
        }
    },
     credits: {
                            enabled: false
                          },

     xAxis: {
        categories: time
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        
    },

    series: [{
        name: 'Total',
        data: total
    }, {
        name: 'TCP',
        data: tcp
    }, {
        name: 'UDP',
        data: udp
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
}
</script>
</html>
