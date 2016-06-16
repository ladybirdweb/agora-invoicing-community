<?php 
	header("Content-type:text/xml");
	$filter = $_GET["dhx_filter"];
	echo "<rows><row id='1'><cell>1</cell><cell><![CDATA[Data for ".$filter["name"]."]]></cell></row></rows>";
?>