<?php
$now = time();
include("config.php");
if(isset($_GET['api_key'])) $api_key = $_GET['api_key'];
$json_url_global = 'http://arsbitcoin.com/api.php';
$json_url_personal = 'http://arsbitcoin.com/api.php?api_key='.$api_key;

$ch_global = curl_init( $json_url_global );
$ch_personal = curl_init( $json_url_personal );
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_HTTPHEADER => array('Content-type: application/json')
);

// Load strings from API
// global
curl_setopt_array( $ch_global, $options );
$json_string_global = curl_exec($ch_global);

//personal
curl_setopt_array( $ch_personal, $options );
$json_string_personal = curl_exec($ch_personal);

//decode strings and store as variable
$json_data_global=json_decode($json_string_global);
$json_data_personal=json_decode($json_string_personal);

// Start global
$hashrate_global = $json_data_global->hashrate;
$workers = $json_data_global->currentworkers;
echo 'Global hashrate: '.$hashrate_global.'<br>';
echo 'Global workers: '.$workers.'<br>';
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
?>