<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
This version of Software is free for using in non-commercial applications.
For commercial use please contact sales@dhtmlx.com to obtain license
*/


//start session to build different trees for different sessions (if you set $_SESSION["id"] to some hardcoded value, this way of processing will be skipped)
	error_reporting(E_ALL ^ E_NOTICE);
	
session_start();
if(!isset($_SESSION["id"]))
	$_SESSION["id"] = microtime();

//include db connection settings
require_once("../../common/config.php");

$link = mysql_pconnect($mysql_host, $mysql_user, $mysql_pasw);
$db = mysql_select_db ($mysql_db);


//FUNCTIONS TO USE IN THE CODE LATER

//print tree XML based on parent_id (function calls itself to go through the nested levels)
	function getLevelFromDB($parent_id){
		//get tree level from database taking parent id as incomming argument
		$sql = "SELECT  item_id, item_nm FROM samples_tree WHERE item_parent_id=$parent_id and GUID='".$_SESSION["id"]."' ORDER BY item_order";
		$res = mysql_query ($sql);
		if($res){
			while($row=mysql_fetch_array($res)){
				//create xml tag for tree node
				print("<item id='".$row['item_id']."' text=\"". str_replace('"',"&quot;",$row['item_nm'])."\">");
				//include child nodes
				getLevelFromDB($row['item_id']);
				//close xml tag for the node
				print("</item>");
			}
		}else{
			echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
		}
	}

//XML HEADER

//include XML Header (as response will be in xml format)
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
 		header("Content-type: application/xhtml+xml"); } else {
 		header("Content-type: text/xml");
}
echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"); 
?>

<!-- start tree xml -->
<tree id="0">
	
<?php
	//print tree XML
	getLevelFromDB(0);
	//Close db connection
	mysql_close($link);
?>

<!-- close tree xml -->
</tree>
