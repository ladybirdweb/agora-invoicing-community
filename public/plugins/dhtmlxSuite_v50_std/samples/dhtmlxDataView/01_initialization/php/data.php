<?php

	require_once("../../common/connector/dataview_connector.php");
	require_once("../../common/config.php");
	
	$conn = mysql_connect($mysql_host,$mysql_user,$mysql_pasw);
	mysql_select_db($mysql_db);
	
	$data = new DataViewConnector($conn);
	$data->dynamic_loading(50);
	$data->render_table("packages_plain","Id","Package,Version,Maintainer");
?>