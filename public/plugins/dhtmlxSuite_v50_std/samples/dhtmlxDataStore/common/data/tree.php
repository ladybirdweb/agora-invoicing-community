<?php
	$filter = $_GET["dhx_filter"];

	header("Content-type:text/xml");
	echo "<tree id='0'>";
	echo '<item text="'.$filter['name'].'" id="123"></item>';
	echo "</tree>";
?>