<?php
$now = time();
$type = $_GET['type'];
include("config.php");
if(isset($_GET['api_key'])) $api_key = $_GET['api_key'];
require_once('getstats.php');
$db = mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($database,$db);

//$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`) VALUES (NULL, '$now', '$hashrate_global', '$workers');";
//$query_global = mysql_query($sql_global);

$sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`, `network_hashrate`) VALUES (NULL, '$now', '$hashrate_global', '$workers', '$network_hashrate');";
$query_global = mysql_query($sql_global);

$sql_personal = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `time`, `hashrate`, `confirmed_rewards`, `payout_history`) VALUES (NULL, '$now', '$hashrate_personal', '$confirmed_rewards', '$payout_history');";
$query_personal = mysql_query($sql_personal);

include("getblocks.php");
?>
