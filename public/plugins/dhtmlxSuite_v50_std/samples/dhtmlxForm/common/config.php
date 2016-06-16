<?php

$mysql_host = "192.168.3.251";
$mysql_user = "sampleDB";
$mysql_pasw = "sampleDB";
$mysql_db   = "sampleDB";

$conn = mysql_connect($mysql_host,$mysql_user,$mysql_pasw);
mysql_select_db($mysql_db);

?>