<?php
include("config.php");
$json_url = 'https://arsbitcoin.com/blockApi.php';

$db = mysql_connect($host,$dbuser,$dbpassword);
mysql_select_db($database,$db);
 
	function objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}
		if (is_array($d)) {
			return array_map(__FUNCTION__, $d);
		}
		else {
			return $d;
		}
	}
 

$ch = curl_init($json_url);
$options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Content-type: application/json'),
CURLOPT_SSL_VERIFYPEER =>false
);

curl_setopt_array( $ch, $options );
$json_string = curl_exec( $ch );
$json_data = json_decode( $json_string );

$blocks_raw = $json_data->blocks;
//print_r($blocks);
//var_dump($blocks[1]);
$blocks = objectToArray($blocks_raw);
print_r($blocks);
echo "<br>";

foreach($blocks as $value) {
  $sql = "SELECT * FROM `blocks` WHERE `id` LIKE {$value['id']}";
  $query = mysql_query($sql);
  $exists = @mysql_num_rows($query);
  
  if($exists == 0) {
    echo "INSERT INTO `arsbtcstats`.`blocks` (`id`, `blocknumber`, `timestamp`, `shares`) VALUES ({$value['id']}, {$value['blockNumber']}, {$value['timestamp']}, {$value['shares']});";
    $sql = "INSERT INTO `arsbtcstats`.`blocks` (`id`, `blocknumber`, `timestamp`, `shares`) VALUES ({$value['id']}, {$value['blockNumber']}, {$value['timestamp']}, {$value['shares']});";
    $query = mysql_query($sql);
    
    echo "Added - Block ".$value['id']." with ".$value['shares']." shares when it was completed at ".date('Y-m-d H:i:s', $value['timestamp']);
    echo "<br>";
  }
  else {
    echo "Block ".$value['id']." is already in the database.";
    echo "<br>";
  }
}
mysql_close();
?>
