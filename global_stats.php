<head>
<script type="text/javascript" src="./js/flot/jquery.js"></script>
<script type="text/javascript" src="./js/flot/jquery.flot.js"></script>
<title>View Hashrate</title>
</head>
<body>
<?php
    $debug = $_GET['debug'];
    include("config.php");
    $db = mysql_connect("localhost", "arsbtcstats", "password");
    mysql_select_db("arsbtcstats", $db);

    $request = "SELECT * FROM `global_stats` ORDER BY `id` DESC LIMIT 0,100";
    $result = mysql_query($request,$db);
    $time_raw=array();
	$hashrate_raw=array();
	$workers_raw=array();
   while($row = mysql_fetch_array($result))
      {
        $time_raw[] = (int)$row["time"]*1000;
        $hashrate_raw[] = (int)$row["hashrate"]/1000;
		$workers_raw[] = (int)$row["workers"];
      }
	  
    //echo $row["time"];
    $time = array_reverse($time_raw);
    $hashrate = array_reverse($hashrate_raw);
	$workers = array_reverse($workers_raw);
	//for ( $i = 0; $i < sizeof($time); $i++)
	//{
	//	$time[$i] = date("Y-m-d H:i:s", $time[$i]);
	//}
	
function make_pair($time, $hashrate) {
    return array($time, $hashrate);
}

$hasharray = array_map('make_pair', $time, $hashrate);
$datapoints = json_encode($hasharray);

function make_pair2($time, $workers) {
    return array($time, $workers);
}

$hasharray = array_map('make_pair2', $time, $workers);
$datapoints2 = json_encode($hasharray);
if ($_GET[debug] == 1)
  {
    echo "Debug enabled!";
    echo "<br>";
    echo "regular array: ";
    print_r($hasharray);
    echo "<br>JSON encoded: ";
    print $datapoints;
    echo "<br>";
  }

mysql_free_result($result);
    ?>
	<div id="placeholder" style="width:600px;height:300px"></div>
	
	<script type="text/javascript">
$(function () {
    var d1 = <?PHP echo $datapoints; ?>;
	var d2 = <?PHP echo $datapoints2; ?>;
    
    var plot = $.plot($("#placeholder"),
           [ { data: d1, label: "Hashrate (GH)"}, { data: d2, label: "Current Workers"} ], {
               series: {
                   lines: { show: true },
                   points: { show: true },
				   
               },
			   xaxis: {
						mode: "time",
						timeformat: "%H:%M<br>%m/%d"
				 },
				 
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
							"<center>" + item.series.label +" at <br>" + datetime + "<br>= " + y + "</center>");
			}
		}
            
        
    });

    
});
</script>

	</body>
