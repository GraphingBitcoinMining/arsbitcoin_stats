<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="./js/js/highstock.js" type="text/javascript"></script>


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
<div id="network" style="width:350px;height:350px;"></div>
</div>
	<?php if ($enable_donation_message == 1) {
	echo $message;
} ?></center>
<?php echo "Hashrate: ". $local_hashrate."<br>Network Hashrate: ".$network_hashrate; ?>
<?php 
	$total = $local_hashrate + $network_hashrate;
	$local_percent = ($local_hashrate / $total) *100;
	$network_percent = ($network_hashrate / $total) *100;
	echo "<br><br>Local Percent: ".$local_percent."<br>Network Percent: ".$network_percent;
?>
<script type="text/javascript">
var chart1; // globally available
$(document).ready(function() {

      chart1 = new Highcharts.StockChart({
         chart: {
            renderTo: 'container',
            type: 'line'
         },
		 
         title: {
            text: 'ArsBitCoin Mining Pool'
         },
         rangeSelector: {
			enabled: 1,
			selected: 1,
			buttons: [{
				type: 'day',
				count: 1,
				text: '1d'
			}, {
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
		 gridLineWidth: 2 ? 0 : 1,
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
         }]

      });

	  chart2 = new Highcharts.Chart({
         chart: {
         renderTo: 'network',
         plotBackgroundColor: null,
         plotBorderWidth: null,
         plotShadow: false
		  },
		  title: {
			 text: 'Ars pool compared to network hashrate'
		  },
		  tooltip: {
			 formatter: function() {
				return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
			 }
		  },
		  plotOptions: {
			 pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
				   enabled: true,
				   color: Highcharts.theme.textColor || '#000000',
				   connectorColor: Highcharts.theme.textColor || '#000000',
				   formatter: function() {
					  return '<b>'+ this.point.name +'</b>: '+ this.y +' %';
				   }
				}
			 }
		  },
		   series: [{
			 type: 'pie',
			 name: 'Browser share',
			 data: [
				['Network Hashrate',	<?php echo $network_percent; ?>],
				{
				   name: 'Ars Hashrate',    
				   y:	<?php echo $local_percent; ?>,
				   sliced: true,
				   selected: true
				}
			 ]
		  }]
	   });
	  
   });
   </script>
   
</body>
</html>