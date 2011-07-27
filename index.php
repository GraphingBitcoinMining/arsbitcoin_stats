<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<!--[if IE]><script language="javascript" type="text/javascript" src="./js/flot/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="./js/flot/jquery.js"></script>
<script type="text/javascript" src="./js/flot/jquery.flot.js"></script>
<script language="javascript" type="text/javascript" src="./js/flot/jquery.flot.pie.js"></script>
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
<center><div style="width: 95%;"><div id="placeholder" style="width:800px;height:300px"></div>
<div id="network" style="width:350px;height:100px;"></div>
</div></center>

	<hr>
	<center><div style="width: 95%;">
	<h4>Daily</h4>
	<div id="day" style="width:90%;height:200px"></div>
	<h4>Weekly</h4>
	<div id="week" style="width:90%;height:200px"></div>
	<h4>Monthly</h4>
	<div id="month" style="width:90%;height:200px"></div>
	<h4>Anually</h4>
	<div id="year" style="width:90%;height:200px"></div>
	</div>
	<?php if ($donation_message == 1) {
	echo $message;
} ?></center>


	<?php //echo $datapoints2; ?>
<?php //echo $datapoints3; ?>
	<script type="text/javascript">
	
$(function () {
    var d1 = <?PHP echo $datapoints; ?>;
	var d2 = <?PHP echo $datapoints2; ?>;
	var d3 = <?PHP echo $datapoints3; ?>;
	var buffer = <?PHP echo $buffer; ?>;
	
	var data = [ { label: "Network Hashrate (<?PHP echo $network_hashrate; ?> GH)", data: <?PHP echo $network_hashrate; ?>, color: "#ffcc00" },
		{ label: "Pool Hashrate (<?PHP echo $hashrate; ?> GH)", data: <?PHP echo $hashrate; ?> , color: "<?php {echo $hr_color;} ?>"} ];
	
	$.plot($("#network"), data,
{
        series: {
            pie: { 
                show: true,
				stroke: { width: .1 }	
            },
			
        }
		
		
});


    $.plot($("#placeholder"),
           [ 
			{ data: d1, lines: { show: true }, points: { show: false }, label: "Hashrate (GH)", color: "<?php {echo $hr_color;} ?>"}, 
			{ data: buffer, lines: { show: true }, points: { show: false }, label: "SMPPS Buffer", color: "#009900"},
			{ data: d2, lines: { show: true }, points: { show: false }, label: "Workers", color: "<?php echo $worker_color; ?>" } , 
			{ data: d3, bars: { show: true }, label: "Block Found", color: "#000000"}
		], {
               
			   xaxis: {
						min: <?PHP echo (time() - 345600)*1000; ?>,
						mode: "time",
						timeformat: "%H:%M<br>%m/%d"
				 },
				 yaxis: { max: <?php echo $y_max; ?>, min: 0},
               grid: { hoverable: true},
			   legend: { position: 'nw' }
             });
			 
	$.plot($("#day"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php echo $hr_color; ?>"} ], {
               series: {
                   lines: { show: true },
                   points: { show: false }
				},
			   xaxis: {
						min: <?PHP echo (time() - 86400)*1000; ?>,
						mode: "time",
						timeformat: "%H:%M<br>%m/%d"
				 },
				 yaxis: { min: 0},
               grid: { hoverable: false},
			   legend: { position: 'sw' }
             });

    $.plot($("#week"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php echo $hr_color; ?>"} ], {
               series: {
                   lines: { show: true },
                   points: { show: false }
				},
			   xaxis: {
						min: <?PHP echo (time() - 604800)*1000; ?>,
						mode: "time",
						timeformat: "%m/%d"
				 },
				 yaxis: { min: 0},
               grid: { hoverable: false },
			   legend: { position: 'sw' }
             });

    $.plot($("#month"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php echo $hr_color; ?>"} ], {
               series: {
                   lines: { show: true },
                   points: { show: false }
				},
			   xaxis: {
						min: <?PHP echo (time() - 2629743)*1000; ?>,
						mode: "time",
						timeformat: "%m/%d"
				 },
				 yaxis: { min: 0},
               grid: { hoverable: false },
			   legend: { position: 'sw' }
             });
			 
    $.plot($("#year"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php echo $hr_color; ?>"} ], {
               series: {
                   lines: { show: true },
                   points: { show: false }
				},
			   xaxis: {
						min: <?PHP echo (time() - 31556926)*1000; ?>,
						mode: "time",
						timeformat: "%m/%d"
				 },
				 yaxis: { min: 0 },
               grid: { hoverable: false },
			   legend: { position: 'sw' }
             });

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
	

    var previousPoint = null;
    $("#placeholder").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
		
       
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				
				$("#tooltip").remove();
				var x = item.datapoint[0],
					y = item.datapoint[1];
					var time = new Date(x);
					var month = time.getMonth() + 1;
					var day = time.getDate();
					var hours = time.getUTCHours();
					var minutes = time.getMinutes() ;
					if (minutes < 10){
					minutes = "0" + minutes
					}
					var datetime = hours  + ':' + minutes + '  ' + month + '/' + day;
					if (item.series.label != "Block Found") {
						var content = "<center>" + item.series.label +" at <br>" + datetime + " =<br>" + y + "</center>";}
					else {
						var content = "<center>" + item.series.label +" at <br>" + datetime + "</center>"; }

				showTooltip(item.pageX, item.pageY,
							content);
			}
		}
		else {
                $("#tooltip").remove();
                previousPoint = null;            
            }
            
        
    });
 
});
</script>


</body>
</html>