<?php
$now = time();
$type = $_GET['type'];
include("config.php");
$db = mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($database,$db);
//$json_string='{"confirmed_rewards":"0.0585075","hashrate":"35","payout_history":"0.0585075","workers":{"Lego399.default":{"alive":"0","hashrate":"0"},"Lego399.laptop_2":{"alive":"0","hashrate":"0"},"Lego399.laptop2":{"alive":"1","hashrate":"21"},"Lego399.macmini":{"alive":"0","hashrate":"0"},"Lego399.laptop":{"alive":"1","hashrate":"14"}}}'

if ($type == "all")
{
  $json_url_global = 'http://arsbitcoin.com/api.php';
  $json_url_personal = 'http://arsbitcoin.com/api.php?api_key='.$api_key;
  
  $ch_global = curl_init( $json_url_global );
  $ch_personal = curl_init( $json_url_personal );
  $options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Content-ype: application/json')
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
  $sql_global = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`) VALUES (NULL, '$now', '$hashrate_global', '$workers');";
  $query_global = mysql_query($sql_global);
  // End global
  
  // Start personal
  $confirmed_rewards = $json_data_personal->confirmed_rewards;
  $payout_history = $json_data_personal->payout_history;
  $hashrate_personal = $json_data_personal->hashrate;
  echo 'Confirmed rewards: '.$confirmed_rewards.'<br>';
  echo 'Personal hashrate: '.$hashrate_personal.'<br>';
  echo 'Payout history: '.$payout_history.'<br>';
  $sql_personal = "INSERT INTO `arsbtcstats`.`personal_stats` (`id`, `time`, `hashrate`, `confirmed_rewards`, `payout_history`) VALUES (NULL, '$now', '$hashrate_personal', '$confirmed_rewards', '$payout_history');";
  $query_personal = mysql_query($sql_personal);
  // End personal
}
else
{
  echo "Using legacy script -- NO PERSONAL STATS! <br><br>";
  $json_url = 'http://arsbitcoin.com/api.php';
  $ch = curl_init( $json_url );

  $options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Content-ype: application/json')
  );
  curl_setopt_array( $ch, $options );
  $json_string = curl_exec($ch);
  $json_data=json_decode($json_string);
  
  $hashrate = $json_data->hashrate;
  $workers = $json_data->currentworkers;
  echo 'Global hashrate: '.$hashrate.'<br>';
  echo 'Global workers: '.$workers.'<br>';
  $sql = "INSERT INTO `arsbtcstats`.`global_stats` (`id`, `time`, `hashrate`, `workers`) VALUES (NULL, '$now', '$hashrate', '$workers');";
  $query = mysql_query($sql);
}
?>