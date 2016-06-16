<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")) {
  		header("Content-type: application/xhtml+xml"); } else {
  		header("Content-type: text/xml");
	}
	echo("<?xml version='1.0' encoding='iso-8859-1'?>\n");

	if (!isset($_GET["posStart"]))
		$_GET["posStart"] = 0;
	if (!isset($_GET["count"]))
		$_GET["count"] = 15;
?>
<rows pos="<?php echo $_GET['posStart'];?>" total_count="200000">
<?php
			for($i=0; $i<$_GET['count']; $i++){print("<row id='r".($i+$_GET['posStart'])."'><cell>".$i."</cell><cell>index is ".($i+$_GET['posStart'])."</cell><cell>load turn started from ".$_GET['posStart']." + ".$_GET['count']."</cell></row>");
			}
	?>
</rows>
