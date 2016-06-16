<?php
	
	if (@$_REQUEST["image"] == "y") {
		
		$data = file_get_contents("aquarium.jpg");
		
		header("Content-Disposition: attachment; filename=aquarium.jpg");
		header("Content-Type: image/jpg");
		header("Content-Length: ".strlen($data));
		
		print_r($data);
		
	} else {
		
		$data = "Name:\t\t".@$_REQUEST["name"]."\r\n".
			"Country:\t".@$_REQUEST["country"]."\r\n";
		
		header("Content-Disposition: attachment; filename=file.txt");
		header("Content-Type: text/plain");
		header("Content-Length: ".strlen($data));
		
		echo $data;
	}
	
?>