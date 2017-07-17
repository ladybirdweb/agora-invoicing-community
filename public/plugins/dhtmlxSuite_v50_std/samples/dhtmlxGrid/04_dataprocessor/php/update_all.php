<?php
//code below is simplified - in real app you will want to have some kins session based autorization and input value checking
error_reporting(E_ALL ^ E_NOTICE);

//include db connection settings
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

function add_row($rowId){
	global $newId;
	
	$sql = 	"INSERT INTO samples_grid(sales,title,author,price,instore,shipping,bestseller,pub_date)
			VALUES ('".$_POST[$rowId."_c0"]."',
					'".addslashes($_POST[$rowId."_c1"])."',
					'".addslashes($_POST[$rowId."_c2"])."',
					'".$_POST[$rowId."_c3"]."',
					'".$_POST[$rowId."_c4"]."',
					'".$_POST[$rowId."_c5"]."',
					'".$_POST[$rowId."_c6"]."',
					'".$_POST[$rowId."_c7"]."')";
	$res = mysql_query($sql);
	//set value to use in response
	$newId = mysql_insert_id();
	return "insert";	
}

function update_row($rowId){
	$sql = 	"UPDATE samples_grid SET  sales='".$_POST[$rowId."_c0"]."',
				title=		'".addslashes($_POST[$rowId."_c1"])."',
				author=		'".addslashes($_POST[$rowId."_c2"])."',
				price=		'".$_POST[$rowId."_c3"]."',
				instore=	'".$_POST[$rowId."_c4"]."',
				shipping=	'".$_POST[$rowId."_c5"]."',
				bestseller=	'".$_POST[$rowId."_c6"]."',
				pub_date=	'".$_POST[$rowId."_c7"]."' 
			WHERE book_id=".$rowId;
	$res = mysql_query($sql);
	
	return "update";	
}

function delete_row($rowId){
	
	$d_sql = "DELETE FROM samples_grid WHERE book_id=".$rowId;
	$resDel = mysql_query($d_sql);
	return "delete";
}


//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may differ in your case
echo('<?xml version="1.0" encoding="iso-8859-1"?>'); 
//output update results
echo "<data>";


$ids = explode(",",$_POST["ids"]);
//for each row
for ($i=0; $i < sizeof($ids); $i++) { 
	$rowId = $ids[$i]; //id or row which was updated 
	$newId = $rowId; //will be used for insert operation	
	$mode = $_POST[$rowId."_!nativeeditor_status"]; //get request mode

	switch($mode){
		case "inserted":
			//row adding request
			$action = add_row($rowId);
		break;
		case "deleted":
			//row deleting request
			$action = delete_row($rowId);
		break;
		default:
			//row updating request
			$action = update_row($rowId);
		break;
	}	
	echo "<action type='".$action."' sid='".$rowId."' tid='".$newId."'/>";
	
}

echo "</data>";

?>