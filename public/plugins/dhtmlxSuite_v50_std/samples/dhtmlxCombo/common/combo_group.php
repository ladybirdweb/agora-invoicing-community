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
	
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'.
		'<complete>';
	
	if (@$_REQUEST["mode"] == "state") {
		
		$r = mysql_query("SELECT id, state FROM us_state ORDER BY LOWER(state)");
		while ($o = mysql_fetch_object($r)) $xml .= '<option value="'.$o->id.'"><![CDATA['.$o->state.']]></option>';
		mysql_free_result($r);
		
	} else {
		
		$r = mysql_query("SELECT id, city FROM us_city WHERE state='".p(@$_REQUEST["state"])."' ORDER BY LOWER(city)");
		while ($o = mysql_fetch_object($r)) $xml .= '<option value="'.$o->id.'"><![CDATA['.$o->city.']]></option>';
		mysql_free_result($r);
		
	}
	
	$xml .= '</complete>';
	
	print_r($xml);

?>
