<?php
$now = time();
$type = $_GET['type'];
include("config.php");

$db = mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($database,$db);

$sql = "SELECT time FROM global_stats ORDER BY time DESC LIMIT 0,1";

$result = mysql_query($sql);

$row = mysql_fetch_array($result);
$timestamp = $row[0];
//echo $timestamp;
//This lets us run get more current stats if the api drops for some reason
	/*
	$sql = "SELECT * FROM global_stats ORDER BY time DESC LIMIT 0,36";
	$result = mysql_query($sql);
	$average = 0;
	while($row = mysql_fetch_row($result)) {
	echo "<br />Hashrate: ".$row['hashrate'];
		$average += (float)$row['hashrate'];
	}
	$average = $average / 36;
	echo $average;*/

if ($timestamp+300 <= $now) {

	if(isset($_GET['api_key'])) $api_key = $_GET['api_key'];
	require_once('getstats.php');
	$sql = "select sum(hashrate) / count(id) as average from global_stats where time > (UNIX_TIMESTAMP()-10800)";
	$row = mysql_fetch_row(mysql_query($sql));
	$average = $row[0];
	echo $average;

	//$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`) VALUES (NULL, '$now', '$hashrate_global', '$workers');";
	//$query_global = mysql_query($sql_global);

	$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`, `network_hashrate`, `buffer`, `Users`,`average`) VALUES (NULL, '$now', '$hashrate_global', null, '$network_hashrate', '$buffer', '$users','$average');";
	$query_global = mysql_query($sql_global);

	$sql_personal = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `time`, `hashrate`, `confirmed_rewards`, `payout_history`) VALUES (NULL, '$now', '$hashrate_personal', '$confirmed_rewards', '$payout_history');";
	$query_personal = mysql_query($sql_personal);

	include("getblocks.php");

}

?>
