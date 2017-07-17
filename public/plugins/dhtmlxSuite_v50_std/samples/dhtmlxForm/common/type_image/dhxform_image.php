<?php

@session_start();
$idd = session_id();

// this part is for loading image into item
if (@$_REQUEST["action"] == "loadImage") {
	
	// default image
	$i = "male.png";
	
	// check if requested image exists
	if (file_exists("uploaded/".$idd)) $i = "uploaded/".$idd;
	
	// output image
	header("Content-Type: image/jpg");
	print_r(file_get_contents($i));
	die();
	
}

// this part is for uploading the new one
if (@$_REQUEST["action"] == "uploadImage") {
	
	@unlink("uploaded/".$idd);
	
	$filename = time();
	move_uploaded_file($_FILES["file"]["tmp_name"], "uploaded/".$idd);
	
	header("Content-Type: text/html; charset=utf-8");
	print_r("{state: true, itemId: '".@$_REQUEST["itemId"]."', itemValue: '".str_replace("'","\\'",$filename)."'}");
	
}

?>