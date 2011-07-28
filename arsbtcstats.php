<?php

$now = time();

$host = "localhost";
$database = "arsbtcstats";
$user = "arsbtcstats";
$password = "password";
$db = mysql_connect($host,$user,$password);
mysql_select_db($database,$db);
$api_key="7e468d0bda6a6c98cdf7e1173d65c4f315fb6f4937160049dff1af4775ab9db2";

//$json_string='{"confirmed_rewards":"0","hashrate":"14","payout_history":"0","workers":{"Lego399.default":{"alive":"0","hashrate":"0"},"Lego399.laptop_2":{"alive":"0","hashrate":"0"},"Lego399.laptop2":{"alive":"1","hashrate":"14"}}}';


//$json_url = 'http://arsbitcoin.com/api.php?api_key=f891403409a40aef81e51155e8b427725c20cdbb019f62054e3e1b9cc68267b5';
$json_url = 'http://arsbitcoin.com/api.php';
//echo $json_url;
$ch = curl_init( $json_url );

$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-ype: application/json')
);

curl_setopt_array( $ch, $options );

$json_string = curl_exec($ch);
$json_data=json_decode($json_string);

//$confirmed_rewards = $json_data->confirmed_rewards;
$hashrate = $json_data->hashrate;
//$payout_history = $json_data->payout_history;

//echo $confirmed_rewards;
echo $hashrate;
echo '<br><br>';
//echo $payout_history;

$sql = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`) VALUES (NULL, '$now', '$hashrate');";
//$sql = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `confirmed_rewards`, `hashrate`, `payout_history`) VALUES (NULL, '$confirmed_rewads', '$hashrate', '$payout_history');";
$query = mysql_query($sql);

//Personal User Stats

$json_url = 'http://arsbitcoin.com/api.php?api_key='.$api_key;

$ch = curl_init( $json_url );

$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-ype: application/json')
);

curl_setopt_array( $ch, $options );

$json_string = curl_exec($ch);
$json_data=json_decode($json_string);

$confirmed_rewards = $json_data->confirmed_rewards;

$payout_history = $json_data->payout_history;

$hashrate = $json_data->hashrate;

echo $confirmed_rewards;
echo '<br>';
echo $hashrate;
echo '<br>';
echo $payout_history;

$sql2 = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `confirmed_rewards`, `hashrate`, `payout_history`, `time`) VALUES (NULL, '$confirmed_rewards', '$hashrate', '$payout_history', '$now');";
$query2 = mysql_query($sql2);
//echo $json_string;
//echo $json_data->hashrate;
?>