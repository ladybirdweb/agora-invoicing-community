<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
This version of Software is free for using in non-commercial applications.
For commercial use please contact sales@dhtmlx.com to obtain license
*/

$mysql_host = "192.168.3.251";
$mysql_user = "sampleDB";
$mysql_pasw = "sampleDB";
$mysql_db   = "sampleDB";
$dbType = "MySQL";

require_once("./connector/tree_connector.php");

$conn = mysql_connect($mysql_host,$mysql_user,$mysql_pasw);
mysql_select_db($mysql_db);

?>