<?php
error_reporting(E_ALL ^ E_NOTICE);

//include db connection settings
//change this setting according to your environment
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may be different in your case
echo('<?xml version="1.0" encoding="utf-8"?>'); 

//start output of data
echo '<rows id="0">';

//output data from DB as XML
$sql = "SELECT  * from samples_grid";
$res = mysql_query ($sql);
		
if($res){
	while($row=mysql_fetch_array($res)){
		//create xml tag for grid's row
		echo ("<row id='".$row['book_id']."'>");
		print("<cell><![CDATA[".$row['sales']."]]></cell>");
		print("<cell><![CDATA[".$row['title']."]]></cell>");
		print("<cell><![CDATA[".$row['author']."]]></cell>");
		print("<cell><![CDATA[".$row['price']."]]></cell>");
		print("<cell><![CDATA[".$row['instore']."]]></cell>");
		print("<cell><![CDATA[".$row['shipping']."]]></cell>");
		print("<cell><![CDATA[".$row['bestseller']."]]></cell>");
		print("<cell><![CDATA[".gmdate("m/d/Y",strtotime($row['pub_date']))."]]></cell>");
		print("</row>");
	}
}else{
//error occurs
	echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
}

echo '</rows>';

?>