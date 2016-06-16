<?php 
	header("Content-type:text/xml");
	$id = $_GET["id"];
	echo "<data><Name>$id</Name><Count>$id</Count><Other>$id</Other></data>";
?>