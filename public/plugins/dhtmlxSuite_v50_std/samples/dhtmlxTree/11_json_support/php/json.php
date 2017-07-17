<?php
	error_reporting(E_ALL ^ E_NOTICE);
	

	if(isset($_GET["id"]))
		$id=$_GET["id"];
	else $id=0;
	
	echo "{id:'".$id."', item:[\n";
	for ($i=0; $i <5 ; $i++) { 
		if ($i!=0) echo "," ;
		echo "{id:'".$i.'-'.$id."',  text:'level 1-".$i."-".$id."', child:'1' }\n";
	}
	echo "]}\n";
?>