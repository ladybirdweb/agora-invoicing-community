<?php
//code below is simplified - in real app you will want to have some kins session based autorization and input value checking
error_reporting(E_ALL ^ E_NOTICE);

//include db connection settings
require_once('../../common/config.php');
require_once('../../common/config_dp.php');

function add_row(){
	global $newId;
	
	$sql = 	"INSERT INTO samples_grid(sales,title,author,price,instore,shipping,bestseller,pub_date)
			VALUES ('".$_POST["c0"]."',
					'".addslashes($_POST["c1"])."',
					'".addslashes($_POST["c2"])."',
					'".$_POST["c3"]."',
					'".$_POST["c4"]."',
					'".$_POST["c5"]."',
					'".$_POST["c6"]."',
					'".$_POST["c7"]."')";
	$res = mysql_query($sql);
	//set value to use in response
	$newId = mysql_insert_id();
	return "insert";	
}

function update_row(){
	$sql = 	"UPDATE samples_grid SET  sales='".$_POST["c0"]."',
				title=		'".addslashes($_POST["c1"])."',
				author=		'".addslashes($_POST["c2"])."',
				price=		'".$_POST["c3"]."',
				instore=	'".$_POST["c4"]."',
				shipping=	'".$_POST["c5"]."',
				bestseller=	'".$_POST["c6"]."',
				pub_date=	'".$_POST["c7"]."' 
			WHERE book_id=".$_POST["gr_id"];
	$res = mysql_query($sql);
	return "update";	
}

function delete_row(){

	$d_sql = "DELETE FROM samples_grid WHERE book_id=".$_POST["gr_id"];
	$resDel = mysql_query($d_sql);
	return "delete";	
}


$mode = $_POST["!nativeeditor_status"]; //get request mode
$rowId = $_POST["gr_id"]; //id or row which was updated 
$newId = $_POST["gr_id"]; //will be used for insert operation


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
echo '{ "tid":"'.$newId.'" }';

?>