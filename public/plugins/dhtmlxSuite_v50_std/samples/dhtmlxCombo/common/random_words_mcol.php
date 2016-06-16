<?php
	
	require_once("config.php");
	
	header("Content-type: text/xml");
	
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'.
		'<complete>';
	
	function p($s) {
		if (!get_magic_quotes_gpc()) return mysql_real_escape_string($s);
		return $s;
	}
	
	mysql_pconnect($mysql_host, $mysql_user, $mysql_pasw);
	mysql_select_db($mysql_db);
	mysql_query("SET NAMES utf8");
	
	if (!isset($_REQUEST["pos"])) $_REQUEST["pos"]=0;
	
	$res = mysql_query("SELECT * FROM random_words");
	if (!$res) {
		//mysql_query("DROP TABLE random_words");
		mysql_query("CREATE TABLE IF NOT EXISTS random_words (".
				"item_id int(10) unsigned NOT NULL AUTO_INCREMENT, ".
				"item_nm varchar(200) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, ".
				"item_cd varchar(15) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL, ".
				"PRIMARY KEY (item_id))".
				"ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
		
		$w = explode(",", file_get_contents(getcwd()."/100000words.txt"));
		$sql = array();
		for ($q=0; $q<count($w); $q++) array_push($sql, "(NULL, '".$w[$q]."', '".rand(123456,987654)."')");
		mysql_query("INSERT INTO random_words (item_id, item_nm, item_cd) VALUES ".implode(", ", $sql));
	}
	
	$mask = @$_REQUEST["mask"];
	$pos = @$_REQUEST["pos"];
	if (!is_numeric($pos)) $pos = 0;
	
	$res = mysql_query("SELECT DISTINCT item_nm FROM random_words WHERE LOWER(item_nm) LIKE LOWER('".p($mask)."%') ORDER BY item_nm LIMIT ".p($pos).", 20");
	while ($row = mysql_fetch_array($res)) {
		$xml .= '<option value="'.$row["item_nm"].'">'.
				'<text>'.
					'<word>'.$row["item_nm"].'</word>'.
					'<random_number>'.rand(100,999).'</random_number>'.
				'</text>'.
			'</option>';
	}
	mysql_free_result($res);
	
	$xml .= '</complete>';
	
	print_r($xml);
	
?>