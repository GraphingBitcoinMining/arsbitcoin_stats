﻿<?php
	$time_raw=array();
	$hashrate_raw=array();
	$workers_raw=array();
	$blocks_raw = array();


        $db = mysql_connect($host,$dbuser,$dbpassword);
        mysql_select_db($database,$db);
			
	function make_pair($time, $hashrate) {
		return array($time, $hashrate);
	}
	function make_network_pair($time, $network_hashrate) {
		return array($time, $network_hashrate);
	}

	function make_pair2($time, $workers) {
		return array($time, $workers);
	}
	
	function block_array($blocks) {
		return array($blocks, 5000);
	}
	
	function make_buffer_pair($buffer_time, $buffer) {
	return array($buffer_time, $buffer);
}
	function get_datapoints($query) {

		include("config.php");
		$memcache = new Memcache;
		$memcache->connect('localhost', 11211) or die ("Could not connect");
		$db = mysql_connect("$host", "$database", "$dbpassword");
		mysql_select_db($database,$db);
		/* first try the cache */
		
		$expire = 300;
			if ($query == 'hashrate') { // $datapoints1
			//echo "Checking cache for hashrate<br><br>";
				$data = $memcache->get('hashrate');
				
				if (!$data) {
					//echo "<br><br>Not Memcached<br><br>";
					$data = array();
					//echo "running query for hashrate<br><br>";
					/*$request = "SELECT * FROM `global_stats` WHERE `time` >= (1311799681) ORDER BY `id` DESC";
						$result = mysql_query($request,$db);
					   while($row = mysql_fetch_array($result))
						  {
								$buffer_time_raw[] = (float)$row["time"]*1000;
								$buffer_raw[] = (float)$row["buffer"];
						  }*/
					$request = "SELECT * FROM `global_stats` ORDER BY `id` DESC";
					$result = mysql_query($request,$db);
					   while($row = mysql_fetch_array($result))
						  {
							$time_raw[] = (float)$row["time"]*1000;
							$hashrate_raw[] = round((float)$row["hashrate"]/1000, 2);
							$workers_raw[] = round((float)$row["workers"]);
							$network_hashrate_raw[] = round((float)$row["network_hashrate"], 2);
							$buffer_raw[] = (float)$row["buffer"];
						  }
						  
							$buffer_time = array_reverse($time_raw);
							$time = array_reverse($time_raw);
							$hashrate = array_reverse($hashrate_raw);
							$hashrate2 = end($hashrate);
							$workers = array_reverse($workers_raw);
							
							$network_rate = array_reverse($network_hashrate_raw);
							
							$buffer = array_reverse($buffer_raw);
							$y_max = max($buffer) +50;
							//get hashrate
							$hasharray = array_map('make_pair', $time, $network_rate);
							$network_rate = json_encode($hasharray);
							
							$hasharray = array_map('make_network_pair', $time, $hashrate);
							$hashrate = json_encode($hasharray);
							//var_dump($datapoints);
							//get workers
							$hasharray = array_map('make_pair2', $time, $workers);
							$datapoints2 = json_encode($hasharray);
							
							
							$buffer_array = array_map('make_buffer_pair', $buffer_time, $buffer);
							$buffer = json_encode($buffer_array);
							
							$data = array('1'=>$hashrate,'2'=>$datapoints2,'3'=>$network_rate,'4'=>$hashrate2,'5'=>$y_max,'6'=>$buffer);
					//var_dump($data);
					$memcache->set('hashrate', $data, 0, $expire ) or die ("Failed to save data at the server");
				}
				
				return $data;
			}
			
			if ($query == 'blocks') { // $datapoints3
			
			//echo "<br>Checking cache for blocks<br><br>";
				$data = $memcache->get('blocks');
				if (!$data) {
				//echo "<br><br>Not Memcached<br><br>";
				$data = array();
				//echo"running query for blocks<br><br>";
				$min_time = time() - 345600;
					$request = "SELECT * FROM blocks WHERE (`timestamp` >= ({$min_time})) ORDER BY `timestamp`";
					//var_dump($request);
					$result = mysql_query($request,$db);
					while($row = mysql_fetch_array($result))
					 {
						$blocks_raw[] = (float)$row["timestamp"]*1000;
					}
					//echo $row["time"];
					$blocks = array_reverse($blocks_raw);
					//var_dump($data);
					$hasharray = array_map('block_array', $blocks);
					$data = json_encode($hasharray);
					//var_dump($datapoints3);
					//$data = array('1' => $datapoints3);
					$memcache->set('blocks', $data, 0, $expire ) or die ("Failed to save data at the server");
				}
				return $data;
			}
	}
    $debug = $_GET['debug'];
    
    
$result = get_datapoints('hashrate');

$hashrate = $result['1'];
//echo "<h1>Hashrate</h1>";
//var_dump($hashrate);
$workers = $result['2'];
$network_rate = $result['3'];
//echo "<h1>Network Hashrate</h1>";
//var_dump($network_rate);
$last_hashrate = $result['4'];
$y_max = $result['5'];
echo $buffer;
$buffer = $result['6'];
$local_hashrate = $last_hashrate;
	
    $result = get_datapoints('blocks');
	
	$datapoints3 = $result;
	//echo "<br><br>Blocks Result:<br>";
	//var_dump($result);
    //$blocks_raw=array();

    
	//for ( $i = 0; $i < sizeof($time); $i++)
	//{
	//	$time[$i] = date("Y-m-d H:i:s", $time[$i]);
	//}
/*	
function make_pair($time, $hashrate) {
    return array($time, $hashrate);
}



function make_pair2($time, $workers) {
    return array($time, $workers);
}
*/



//$network_hashrate = end($network_rate) - (end($hashrate));
if ($_GET[debug] == 1)
  {
  echo "Debug Mode";
  }


mysql_free_result($result);
    ?>
