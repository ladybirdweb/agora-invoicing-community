<?php
//code below is simplified - in real app you will want to have some kins session based autorization and input value checking
error_reporting(E_ALL ^ E_NOTICE);

//include db connection 
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may differ in your case
echo('<?xml version="1.0" encoding="iso-8859-1"?>'); 


$mode = $_GET["!nativeeditor_status"]; //get request mode
$rowId = $_GET["gr_id"]; //id or row which was updated 
$newId = $_GET["gr_id"]; //will be used for insert operation


//output update results
echo "<data>";
echo "<action type='error' sid='".$rowId."' tid='".$newId."'>Details about errors on server side</action>";
echo "</data>";

?>