<?php
	
	require_once("config.php");
	
	header("Content-Type: text/xml");
	
	function p($s) {
		if (!get_magic_quotes_gpc()) return mysql_real_escape_string($s);
		return $s;
	}
	
	function r($s) {
		return str_replace("'", "\\'", $s);
	}
	
	mysql_pconnect($mysql_host, $mysql_user, $mysql_pasw);
	mysql_select_db($mysql_db);
	mysql_query("SET NAMES utf8");
	
	$mask = @$_REQUEST["mask"];
	$pos = p(@$_REQUEST["pos"]);
	if (!is_numeric($pos)) $pos = 0;
	
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
		'<complete'.($pos!=0?' add="true"':'').'>'."\n"; // "add" attr no more needed in 4.0
	
	$r = mysql_query("SELECT id, city FROM us_city WHERE LOWER(city) LIKE LOWER('".p($mask)."%') ORDER BY LOWER(city) LIMIT $pos,10");
	while ($o = mysql_fetch_object($r)) {
		$value = str_replace(" ","_",strtolower($o->city));
		$xml .= "\t".'<option value="'.$value.'"><![CDATA['.$o->city.']]></option>'."\n";
	}
	mysql_free_result($r);
	
	$xml .= '</complete>';
	
	print_r($xml);

?>
