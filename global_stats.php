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
    $time_raw=array(); $hashrate_raw=array();
   while($row = mysql_fetch_array($result))
      {
        $time_raw[] = (int)$row["time"];
        $hashrate_raw[] = (int)$row["hashrate"];
      }
	  
    //echo $row["time"];
    $time = array_reverse($time_raw);
    $hashrate = array_reverse($hashrate_raw);

function make_pair($time, $hashrate) {
    return array($time, $hashrate);
}

$hasharray = array_map('make_pair', $time, $hashrate);
$datapoints = json_encode($hasharray);
if (debug == 1)
  {
    echo "Debug enabled!";
    echo "<br>";
    echo "regular array: ";
    print_r($hasharray);
    echo "<br>JSON encoded: ";
    print $datapoints;
    echo "<br>";
  }
print $datapoints;
mysql_free_result($result);
    ?>
	<div id="placeholder" style="width:600px;height:300px"></div>
	
	<script type="text/javascript">
$(function () {
    var d1 = <?PHP echo $datapoints; ?>;
    
    $.plot($("#placeholder"), [ d1 ] );
});
</script>

	</body>
