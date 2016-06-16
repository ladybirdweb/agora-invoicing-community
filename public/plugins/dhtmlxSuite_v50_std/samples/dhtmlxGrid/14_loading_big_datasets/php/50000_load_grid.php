<?php
	error_reporting(E_ALL ^ E_NOTICE);
	
	header("Content-type:text/xml");
	ini_set('max_execution_time', 600);
	require_once('../../common/config.php'); 
	print("<?xml version=\"1.0\"  encoding=\"ISO-8859-1\"?>");
?>
<?php
function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}


	//$_GET['posStart'];
	if(isset($_GET["posStart"]))
		$posStart = $_GET['posStart'];
	else
		$posStart = 0;
	if(isset($_GET["count"]))
		$count = $_GET['count'];
	else
		$count = 100;
	if(isset($_GET["nm_mask"]))
		$nm_mask = $_GET['nm_mask'];
	else
		$nm_mask = "";
	if(isset($_GET["cd_mask"]))
		$cd_mask = $_GET['cd_mask'];
	else
		$cd_mask = "";
	
	//$_GET['count'];
	
	$link = mysql_pconnect($mysql_host, $mysql_user, $mysql_pasw);
	$db = mysql_select_db ($mysql_db);
	
    if (!isset($_GET["orderBy"]))
      $_GET["orderBy"]=0;
    if (!isset($_GET["direction"]) || $_GET["direction"]=="asc")
      $_GET["direction"]="ASC";
     else
      $_GET["direction"]="DESC";
     

	$fields=array("item_nm","","item_cd");
    getDataFromDB('','',$fields[$_GET["orderBy"]],$_GET["direction"]);
	mysql_close($link);
	
	
	//print one level of the tree, based on parent_id
function getDataFromDB($name_mask,$code_mask,$sort_by,$sort_dir){
		GLOBAL $posStart,$count,$nm_mask,$cd_mask;
		$sql = "SELECT  * FROM grid50000 Where 0=0";
		if($nm_mask!='')
			$sql.= " and item_nm like '$nm_mask%'";
		if($cd_mask!='')
			$sql.= " and item_cd like '$cd_mask%'";
		if($sort_dir=='')
			$sort_dir = "asc";
		if($sort_by!='')
			$sql.= " Order By $sort_by $sort_dir";
		//print($sql);
		//echo $sql;
		if($posStart==0){
			$sqlCount = "Select count(*) as cnt from ($sql) as tbl";
			//print($sqlCount);
			$resCount = mysql_query ($sqlCount);
			while($rowCount=mysql_fetch_array($resCount)){
				$totalCount = $rowCount["cnt"];
			}
		}
		$sql.= " LIMIT ".$posStart.",".$count;
		$res = mysql_query ($sql);
		print("<rows total_count='".$totalCount."' pos='".$posStart."'>");
		if($res){
			while($row=mysql_fetch_array($res)){
				print("<row id='".$row['item_id']."'>");
					print("<cell>");
					print($row['item_nm']);//."[".$row['item_id']."]");	
					print("</cell>");
					print("<cell>");
						print("index is ".$posStart);	
					print("</cell>");
					print("<cell>");
					print($row['item_cd']);	
					print("</cell>");
				print("</row>");
				$posStart++;
			}
		}else{
			echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
		}
		
		print("</rows>");
	}
?>
