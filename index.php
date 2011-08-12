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
include("config.php");

if ($cache == 0){

include("global_stats.php");
}
if ($cache == 1){

include("memcached.php");
}
?>
<center><div style="width: 95%;"><div id="container" style="width:1000px;height:600px"></div>

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
				else if (this.series.name == "Buffer") {
                    s += '<br /><span style="font-weight: bold; color: '+color+'">'+this.series.name + ':' + '</span>'+ Math.round(point.y*1000)/1000 + ' BTC';
				}
				else if (this.series.name == 'Users') {
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
            data: <?php echo $workers; ?>,
			visible: false
         }, {
			name: 'Buffer',
			data: <?php echo $buffer; ?>
		}, {
            name: 'Users',
            data: <?php echo $users; ?>
         }]

      });
	  });
	  </script>
	  
</body>
</html>
