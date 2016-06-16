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
	
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'.
		'<complete>';
	
	$r = mysql_query("SELECT id, text FROM tree_def WHERE LOWER(text) LIKE LOWER('".p($mask)."%') ORDER BY LOWER(text) ");
	while ($o = mysql_fetch_object($r)) {
		$xml .= '<option value="'.str_replace(" ","_",strtolower($o->text)).'"><![CDATA['.$o->text.']]></option>';
	}
	mysql_free_result($r);
	
	$xml .= '</complete>';
	
	print_r($xml);

?>
