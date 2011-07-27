<?php
$api_key="";
$cache = 1; //To enable caching with memcached, change this to 1
$host = "localhost";
$dbuser = "arsbtcstats";
$dbpassword = "password";
$database = "arsbtcstats";
/*
if you are willing to share the love with the developers of this app, leave $donation_message equal to 1, otherwise change it to 0
*/

$enable_donation_message = 1;
$enable_memcache_message = 1;

$donation_message = "<center><p id='donation'>If you enjoy these graphs, the developers would appreciate donations at: <br>1Pz3Rg38y2H3cRfvVyMCeVk8BHHLTXW7pM</p></center>";

$memcache_message = "<center><p id='memcache'>These values got loaded from memcache!</p></center>";
$hr_color = "#0099FF"; // Hashrate Color
$worker_color = "#FF9900"; // Worker Color
?>
