<?php
	require("./config.php");

	$tree = new TreeConnector($conn, $dbType);
	$tree->render_table("tasks","taskId","taskName","","parentId");
?>