<?php
	error_reporting(E_ALL ^ E_NOTICE);

	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  		header("Content-type: application/xhtml+xml"); } else {
  		header("Content-type: text/xml");
	}
	echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"); 
	if(!isset($_GET["rowsLoaded"])){
		$_GET["rowsLoaded"] = 0;
	}
?>
<rows>
	<?php
		if($_GET["rowsLoaded"]<500){
			$start = $_GET["rowsLoaded"]+1;
			$to = $start+100;
			for($i = $start;$i<$to;$i++){
				print("<row id='r$i'><cell>index is $i</cell><cell>load turn started from $start</cell></row>");
			}
		}
	?>
</rows>	