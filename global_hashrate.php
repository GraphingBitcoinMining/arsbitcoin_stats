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
<body><?php
    $debug = $_GET['debug'];
    include("config.php");
    $db = mysql_connect("$host", "$dbuser", "$dbpassword");
    mysql_select_db("$database", $db);

    $request = "SELECT * FROM `global_stats` ORDER BY `id` DESC LIMIT 0,100";
    $result = mysql_query($request,$db);
    $time_raw=array();
	$hashrate_raw=array();
   while($row = mysql_fetch_array($result))
      {
        $time_raw[] = (int)$row["time"]*1000;
        $hashrate_raw[] = (int)$row["hashrate"]/1000;
      }
	  
    //echo $row["time"];
    $time = array_reverse($time_raw);
    $hashrate = array_reverse($hashrate_raw);
	//for ( $i = 0; $i < sizeof($time); $i++)
	//{
	//	$time[$i] = date("Y-m-d H:i:s", $time[$i]);
	//}
	
function make_pair($time, $hashrate) {
    return array($time, $hashrate);
}

$hasharray = array_map('make_pair', $time, $hashrate);
$datapoints = json_encode($hasharray);

if ($_GET[debug] == 1)
  {
    echo "<h1>Debug enabled!</h1>";
    echo "<br>";
    echo "<h2>Regular array (Time & Workers):</h2>";
    print_r($hasharray);
    echo "<h2>JSON encoded:</h2>";
    print $datapoints;
  }

mysql_free_result($result);
    ?>
	<div id="placeholder" style="width:90%;height:90%"></div>
	
	<script type="text/javascript">
$(function () {
    var d1 = <?PHP echo $datapoints; ?>;
	   
    var plot = $.plot($("#placeholder"),
           [ { data: d1, label: "Hashrate (GH)", color: "<?php echo $hr_color; ?>"} ], {
               series: {
                   lines: { show: true },
                   points: { show: true }
				},
			   xaxis: {
						mode: "time",
						timeformat: "%H:%M<br>%m/%d"
				 },
				 yaxis: { min: 0 , max: <?php echo (max($hashrate) + 5); ?> , tickSize: 10},
               grid: { hoverable: true, clickable: true }
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
					var day = time.getDate() + 1;
					var hours = time.getUTCHours();
					var minutes = time.getMinutes() ;
					if (minutes < 10){
					minutes = "0" + minutes
					}
					var datetime = hours  + ':' + minutes + '  ' + month + '/' + day;
				
				showTooltip(item.pageX, item.pageY,
							"<center>" + item.series.label +" at <br>" + datetime + " =<br>" + y + "</center>");
			}
		}
            
        
    });

    
});
</script>
