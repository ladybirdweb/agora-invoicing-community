<?php
	require_once('../../common/config.php');
	require_once('./connector/options_connector.php');
	
	$options = new SelectOptionsConnector($conn);
	//$options->enable_log("log.txt");
	$options->render_sql("SELECT Id, Package FROM packages_plain WHERE Id < 50","","Id,Package");
?>