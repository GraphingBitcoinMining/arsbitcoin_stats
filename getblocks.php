<?php
include("config.php");
$json_url = 'http://arsbitcoin.com/blockApi.php';

$ch = curl_init($json_url);
$options = array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => array('Content-type: application/json')
);

curl_setopt_array( $ch, $options );
$json_string = curl_exec( $ch );
$json_data = json_decode( $json_string );

$blocks = $json_data->blocks;
print_r($blocks);
var_dump($blocks[1]);
?>