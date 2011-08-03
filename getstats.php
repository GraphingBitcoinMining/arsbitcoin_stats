<?php
$now = time();
include("config.php");
if(isset($_GET['api_key'])) $api_key = $_GET['api_key'];
$json_url_global = 'https://arsbitcoin.com/api.php';
$json_url_personal = 'https://arsbitcoin.com/api.php?api_key='.$api_key;

$ch_global = curl_init( $json_url_global );
$ch_personal = curl_init( $json_url_personal );
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-type: application/json'),
CURLOPT_SSL_VERIFYPEER =>false
);
// Load strings from API
// global
curl_setopt_array( $ch_global, $options );
//var_dump($ch_global);
$json_string_global = curl_exec($ch_global);
if ($json_string_global == null) {
	die();
}
//var_dump($json_string_global);
//personal
curl_setopt_array( $ch_personal, $options );
$json_string_personal = curl_exec($ch_personal);

//decode strings and store as variable
$json_data_global=json_decode($json_string_global);
$json_data_personal=json_decode($json_string_personal);

// Start global
$hashrate_global = $json_data_global->hashrate;
//$workers = $json_data_global->currentworkers;
$buffer = $json_data_global->smppsbuffer;
$users = $json_data_global->users;
echo 'Global hashrate: '.$hashrate_global.'<br>';
//echo 'Global workers: '.$workers.'<br>';
echo 'Buffer: '.$buffer;
echo "Users: ".$users;
echo '<br>';
// End global

// Start personal
$confirmed_rewards = $json_data_personal->confirmed_rewards;
$payout_history = $json_data_personal->payout_history;
$hashrate_personal = $json_data_personal->hashrate;
echo 'Confirmed rewards: '.$confirmed_rewards.'<br>';
echo 'Personal hashrate: '.$hashrate_personal.'<br>';
echo 'Payout history: '.$payout_history.'<br>';
// End personal

  //This is where we pull network hashrate
$global_network = file_get_contents('http://bitcoincharts.com/markets/');
$regex = '/Network total\<\/td\>\<td\>(.+?) Thash\/s\<\/td\>\<\/tr\>/';
preg_match($regex,$global_network,$match);
$network_hashrate = (float)$match[1] * 1000;
echo $network_hashrate;
?>