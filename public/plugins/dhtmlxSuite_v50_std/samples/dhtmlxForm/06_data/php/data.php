<?php
	require_once('../../common/config.php');
	require_once('./connector/form_connector.php');
	
//	sleep(1);
	
	$form = new FormConnector($conn);
	//$form->enable_log("log.txt");
	$form->render_table("packages_plain","Id","Package,Version,Size,Maintainer");
?>