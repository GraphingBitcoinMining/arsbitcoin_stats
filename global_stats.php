<?php
    $debug = $_GET['debug'];
    include("config.php");
    $db = mysql_connect("$host", "$database", "$dbpassword");
    mysql_select_db("$database", $db);

    $request = "SELECT * FROM `global_stats` ORDER BY `id` DESC LIMIT 0,500";
    $result = mysql_query($request,$db);
    $time_raw=array();
	$hashrate_raw=array();
	$workers_raw=array();
   while($row = mysql_fetch_array($result))
      {
        $time_raw[] = (int)$row["time"]*1000;
        $hashrate_raw[] = (int)$row["hashrate"]/1000;
		$workers_raw[] = (int)$row["workers"];
		$network_hashrate_raw[] = (float)$row["network_hashrate"];
      }
	$min_time = min($time_raw)/1000;
	
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
	$network_rate = array_reverse($network_hashrate_raw);
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
	return array($blocks, 5000);
}
$hasharray = array_map('make_pair2', $time, $workers);
$datapoints2 = json_encode($hasharray);
$hasharray = array_map('block_array', $blocks);
$datapoints3 = json_encode($hasharray);
$network_hashrate = end($network_rate) - (end($hashrate));
if ($_GET[debug] == 1)
  {
    echo "<h1>Debug enabled!</h1>";
    echo "<br>";
    echo "<h2>Regular array (Time & Workers):</h2>";
    print_r($hasharray);
    echo "<h2>JSON encoded:</h2>";
    print $datapoints;
    echo "<br>";
	echo "<h2>Network Hashrate :</h2>".$network_hashrate." GH/s";
	echo "<h2>Pool Hashrate :</h2>".(end($hashrate))." GH/s";
  }

  $request = "SELECT * FROM `global_stats` ORDER BY `id` DESC";
    $result = mysql_query($request,$db);
    $time_raw=array();
	$hashrate_raw=array();
   while($row = mysql_fetch_array($result))
      {
        $time_raw[] = (int)$row["time"]*1000;
        $hashrate_raw[] = (int)$row["hashrate"]/1000;
      }
	  
    $time = array_reverse($time_raw);
    $hashrate = array_reverse($hashrate_raw);


$hasharray = array_map('make_pair', $time, $hashrate);
$datapoints4 = json_encode($hasharray);

if ($_GET[debug] == 1)
  {
    echo "<h1>Debug enabled!</h1>";
    echo "<br>";
    echo "<h2>Regular array (Time & Workers):</h2>";
    print_r($hasharray);
    echo "<h2>JSON encoded:</h2>";
    print $datapoints4;
  }

mysql_free_result($result);
    ?>
