<?php
//code below is simplified - in real app you will want to have some kins session based autorization and input value checking
error_reporting(E_ALL ^ E_NOTICE);

//include db connection settings
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

function add_row(){
	global $newId;

	$sql = 	"INSERT INTO samples_grid(sales,title,author,price,instore,shipping,bestseller,pub_date)
			VALUES ('".$_GET["sales"]."',
					'".addslashes($_GET["book"])."',
					'".addslashes($_GET["author"])."',
					'".$_GET["price"]."',
					'".$_GET["store"]."',
					'".$_GET["shipping"]."',
					'".$_GET["best"]."',
					'".$_GET["date"]."')";
	$res = mysql_query($sql);
	//set value to use in response
	$newId = mysql_insert_id();
	return "insert";	
}

function update_row(){
	$sql = 	"UPDATE samples_grid SET  sales='".$_GET["sales"]."',
				title=		'".addslashes($_GET["book"])."',
				author=		'".addslashes($_GET["author"])."',
				price=		'".$_GET["price"]."',
				instore=	'".$_GET["store"]."',
				shipping=	'".$_GET["shipping"]."',
				bestseller=	'".$_GET["best"]."',
				pub_date=	'".$_GET["date"]."' 
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