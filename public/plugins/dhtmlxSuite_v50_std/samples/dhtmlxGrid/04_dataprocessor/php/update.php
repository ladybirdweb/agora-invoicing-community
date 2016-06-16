<?php
//code below is simplified - in real app you will want to have some kins session based autorization and input value checking
error_reporting(E_ALL ^ E_NOTICE);

//include db connection settings
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

function add_row(){
	global $newId;
	
	$sql = 	"INSERT INTO samples_grid(sales,title,author,price,instore,shipping,bestseller,pub_date)
			VALUES ('".$_GET["c0"]."',
					'".addslashes($_GET["c1"])."',
					'".addslashes($_GET["c2"])."',
					'".$_GET["c3"]."',
					'".$_GET["c4"]."',
					'".$_GET["c5"]."',
					'".$_GET["c6"]."',
					'".$_GET["c7"]."')";
	$res = mysql_query($sql);
	//set value to use in response
	$newId = mysql_insert_id();
	return "insert";	
}

function update_row(){
	$sql = 	"UPDATE samples_grid SET  sales='".$_GET["c0"]."',
				title=		'".addslashes($_GET["c1"])."',
				author=		'".addslashes($_GET["c2"])."',
				price=		'".$_GET["c3"]."',
				instore=	'".$_GET["c4"]."',
				shipping=	'".$_GET["c5"]."',
				bestseller=	'".$_GET["c6"]."',
				pub_date=	'".$_GET["c7"]."' 
			WHERE book_id=".$_GET["gr_id"];
	$res = mysql_query($sql);
	
	return "update";	
}

function delete_row(){

	$d_sql = "DELETE FROM samples_grid WHERE book_id=".$_GET["gr_id"];
	$resDel = mysql_query($d_sql);
	return "delete";	
}


//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may differ in your case
echo('<?xml version="1.0" encoding="iso-8859-1"?>'); 


$mode = $_GET["!nativeeditor_status"]; //get request mode
$rowId = $_GET["gr_id"]; //id or row which was updated 
$newId = $_GET["gr_id"]; //will be used for insert operation


switch($mode){
	case "inserted":
		//row adding request
		$action = add_row();
	break;
	case "deleted":
		//row deleting request
		$action = delete_row();
	break;
	default:
		//row updating request
		$action = update_row();
	break;
}


//output update results
echo "<data>";
echo "<action type='".$action."' sid='".$rowId."' tid='".$newId."'/>";
echo "</data>";

?>