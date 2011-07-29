<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="./js/js/highstock.js" type="text/javascript"></script>
<script type="text/javascript" src="./js/js/modules/exporting.js"></script>

<title>Stats</title>
<style type="text/css">
    html, body {
        height: 100%; /* make the percentage height on placeholder work */
    }
    .message {
        padding-left: 50px;
        font-size: smaller;
    }
	#donation {
		font-size: smaller;
		color: #A9A9A9;
	}
    </style>
</head>
<body>
<?php
//error_reporting(E_ALL ^ E_NOTICE);
//echo "including config.php<br><br>";
include("config.php");

if ($cache == 0){
//echo "including global stats<br><br>";
include("global_stats.php");
}
if ($cache == 1){
//echo "including memcached.php<br><br>";
include("memcached.php");
}
?>
<center><div style="width: 95%;"><div id="container" style="width:1000px;height:600px"></div>
<div id="network" style="width:1000px;height:300px;"></div>

</div>
	<?php if ($enable_donation_message == 1) {
	echo $donation_message;
} ?></center>

<script type="text/javascript">
var chart1,
		chart2; // globally available

$(document).ready(function() {

      chart1 = new Highcharts.StockChart({
         chart: {
            renderTo: 'container',
            type: 'line'
         },
		 tooltip: {
			formatter: function() {
				var s = '<b>'+ Highcharts.dateFormat('%H:%M, %A, %b %e, %Y', this.x) +'</b>';

                $.each(this.points, function(i, point) {
				color = point.series.color;
				if (this.series.name == 'Workers') {
					s += '<br /><span style="font-weight: bold; color: '+color+'">'+this.series.name + ':' + '</span>'+ Math.round(point.y);
				}
				else {
                    s += '<br /><span style="font-weight: bold; color: '+color+'">'+this.series.name + ':' + '</span>'+ Math.round(point.y*1000)/1000;
				}
                });
            
                return s;
			}
		},
		 
         title: {
            text: 'ArsBitcoin Mining Pool'
         },
         legend: {
            align: 'center',
            enabled: true,
            verticalAlign: 'top',
            y: 40
         },
         rangeSelector: {
			enabled: 1,
			selected: 1,
			buttons: [{
				type: 'day',
				count: 1,
				text: '1d'
			}, {
				type: 'day',
				count: 3,
				text: '3d'
			},{
				type: 'week',
				count: 1,
				text: '1w'
			}, {
				type: 'month',
				count: 1,
				text: '1m'
			}, {
				type: 'year',
				count: 1,
				text: '1y'
			}],
		},
		plotOptions: {
            series: {
                marker: {
                    enabled: false    
                }
            }
		},
		xAxis: {
			type: 'datetime',
			maxZoom: 1 * 24 * 3600000, // fourteen days
			title: {
				text: null
			}
		},
         yAxis: {
		 min: 0,
            title: {
               text: 'value'
            }
         },
         series: [{
            name: 'Hashrate',
            data: <?php echo $hashrate; ?>
         }, {
            name: 'Workers',
            data: <?php echo $workers; ?>
         }, {
			name: 'Buffer',
			data: <?php echo $buffer; ?>
		}]

      });
	  });
	  </script>
	  
<?php /*  remove these comments and the php to add a second graph in with ars hashrate : network hashrate  ?>
<script src="./js/js_highcharts/highcharts.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	  chart2 = new Highcharts.Chart({
         chart: {
			renderTo: 'network',
			type: 'pie'
		 },
		 rangeSelector: {
			enabled: 0
		},
		navigator: {
			enabled: 0
		},
		scrollbar: {
	enabled: 0
},
		title: {
			text: 'Ars Hashrate and Network Hashrate'
		},
		xAxis: {
			type: 'datetime',
			title: {
				text: null
			}
		},
         yAxis: {
		 min: 0,
            title: {
               text: 'value'
            }
         },
		 series: [ {
            name: 'Network Hashrate',
            data: <?php echo $network_rate; ?>
         },{
            name: 'Ars Hashrate',
            data: <?php echo $hashrate; ?>
         }]
		 });
	 
   });
   </script>
   <?php /* */ ?>
</body>
</html>
