<?php
/*
Copyright DHTMLX LTD. http://www.dhtmlx.com
This version of Software is free for using in non-commercial applications.
For commercial use please contact sales@dhtmlx.com to obtain license
*/

//start session (see get.php for details) 
	error_reporting(E_ALL ^ E_NOTICE);
	
session_start();
if(!isset($_SESSION["id"]))
	$_SESSION["id"] = microtime();
	
//include db connection settings
require_once("../../common/config.php");

$link = mysql_pconnect($mysql_host, $mysql_user, $mysql_pasw);
$db = mysql_select_db ($mysql_db);

//FUNCTION TO USE IN THE CODE LATER

//deletes single node in db
function deleteSingleNode($id){
		$d_sql = "Delete from samples_tree where item_id=".$id." and GUID='".$_SESSION["id"]."'";
		$resDel = mysql_query($d_sql);
	}
//deletes all child nodes of the item
function deleteBranch($pid){
	$s_sql = "Select item_id,item_order from samples_tree where item_parent_id=$pid  and GUID='".$_SESSION["id"]."'";
	$res = mysql_query($s_sql);
	if($res){
		while($row=mysql_fetch_array($res)){
			deleteBranch($row['item_id']);
			deleteSingleNode($row['item_id']);
		}
	}
}

//XML HEADER

//include XML Header (as response will be in xml format)
if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
 		header("Content-type: application/xhtml+xml"); } else {
 		header("Content-type: text/xml");
}
echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n"); 

if(isset($_GET["!nativeeditor_status"]) && $_GET["!nativeeditor_status"]=="inserted"){

	//INSERT
	
	//insert new row
	$sql = 	"Insert into samples_tree(item_nm,item_parent_id,item_desc,item_order,GUID) ";
	$sql.= 	"Values('".addslashes($_GET["tr_text"])."',".$_GET["tr_pid"].",'',".$_GET["tr_order"].",'".$_SESSION["id"]."')";
	
		$res = mysql_query($sql);
		$newId = mysql_insert_id();
	//update items orders on the level where node was added
	$sql_uorders = "UPDATE samples_tree SET item_order=item_order+1 WHERE item_parent_id=".$_GET["tr_pid"]." AND item_order>=".$_GET["tr_order"]." and item_id!=".$newId." and GUID='".$_SESSION["id"]."'";
		$res = mysql_query($sql_uorders);
		
	//set value to use in response
	$action = "insert";
	
	
}else if(isset($_GET["!nativeeditor_status"]) && $_GET["!nativeeditor_status"]=="deleted"){

	//DELETE
	
	//updateitems order on the level where node was deleted
	$sql_uorders = "UPDATE samples_tree SET item_order=item_order-1 WHERE item_parent_id=".$_GET["tr_pid"]." AND item_order>".($_GET["tr_order"])." and GUID='".$_SESSION["id"]."'";
	
		//delete all nested nodes and current node
		deleteBranch($_GET["tr_id"]);
		deleteSingleNode($_GET["tr_id"]);
		$res = mysql_query($sql_uorders);
	//set values to use in response
	$newId = $_GET["tr_id"];
	$action = "delete";
	
}else{

	//UPDATE and Drag-n-Drop
	
	//get information about node parent and order before update
	$sql_getoldparent = "Select item_parent_id,item_order from samples_tree where item_id=".$_GET["tr_id"]." and GUID='".$_SESSION["id"]."'";
	$res = mysql_query($sql_getoldparent);
	$old_values = mysql_fetch_array($res);
	//update node info 
	$sql = 	"Update samples_tree set item_nm = '".addslashes($_GET["tr_text"])."',item_parent_id = ".$_GET["tr_pid"].",item_desc = '".addslashes($_GET["ud_description"])."',item_order = ".$_GET["tr_order"]." where item_id=".$_GET["tr_id"]." and GUID='".$_SESSION["id"]."'";
	//update nodes order on old node level (after drag-n-drop node level can be changed)
	$sql_uorders_old = "UPDATE samples_tree SET item_order=item_order-1 WHERE item_parent_id=".$old_values[0]." AND item_order>".$old_values[1]." and item_id<>".$_GET["tr_id"]." and GUID='".$_SESSION["id"]."'";
	//update nodes order on current node level
	$sql_uorders_new = "UPDATE samples_tree SET item_order=item_order+1 WHERE item_parent_id=".$_GET["tr_pid"]." AND item_order>=".$_GET["tr_order"]." and item_id<>".$_GET["tr_id"]." and GUID='".$_SESSION["id"]."'";
		$res = mysql_query($sql);
		$res = mysql_query($sql_uorders_old);
		$res = mysql_query($sql_uorders_new);
	
	//set values to include in response
	$newId = $_GET["tr_id"];
	$action = "update";
}
?>
<!-- response xml -->
<data>
	<action type='<?php echo $action; ?>' sid='<?php echo $_GET["tr_id"]; ?>' tid='<?php echo $newId; ?>'/>
</data>
