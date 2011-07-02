<?php
    $debug = $_GET['debug'];
    include("config.php");
    $db = mysql_connect("$host", "$database", "$dbpassword");
    mysql_select_db("$database", $db);

    $request = "SELECT * FROM `global_stats` ORDER BY `id` DESC LIMIT 0,1000";
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
	$min_time = min($time_raw)/1000;
echo $min_time;
	$request = "SELECT * FROM blocks WHERE (`timestamp` >= ({$min_time})) ORDER BY `timestamp`";
    $result = mysql_query($request,$db);
    $blocks_raw=array();
while($row = mysql_fetch_array($result))
      {
        $blocks_raw[] = (int)$row["timestamp"]*1000;
}
    //echo $row["time"];
	$blocks = array_reverse($blocks_raw);
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
function block_array($blocks) {
	return array($blocks, 90);
}
$hasharray = array_map('make_pair2', $time, $workers);
$datapoints2 = json_encode($hasharray);
$hasharray = array_map('block_array', $blocks);
$datapoints3 = json_encode($hasharray);
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
