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
echo $timestamp;
//This lets us run get more current stats if the api drops for some reason

if ($timestamp+300 <= $now) {

	if(isset($_GET['api_key'])) $api_key = $_GET['api_key'];
	require_once('getstats.php');


	//$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`) VALUES (NULL, '$now', '$hashrate_global', '$workers');";
	//$query_global = mysql_query($sql_global);

	$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`, `network_hashrate`, `buffer`, `users`) VALUES (NULL, '$now', '$hashrate_global', 'null, '$network_hashrate', '$buffer', '$users');";
	$query_global = mysql_query($sql_global);

	$sql_personal = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `time`, `hashrate`, `confirmed_rewards`, `payout_history`) VALUES (NULL, '$now', '$hashrate_personal', '$confirmed_rewards', '$payout_history');";
	$query_personal = mysql_query($sql_personal);

	include("getblocks.php");

}

?>
