<?php
	
	/*
	
	top-level output
	[ {item}, {item}, {item} ]
	
	sub-level output
	[{id: $_REQUEST["id"], items: [
		{item}, {item}, {item}
	]}]
	
	*/
	
	header("Content-Type: text/plain");
	
	function p($s) {
		if (!get_magic_quotes_gpc()) return mysql_real_escape_string($s);
		return $s;
	}
	
	function r($s) {
		return str_replace("'", "\\'", $s);
	}
	
	mysql_connect("127.0.0.1", "root", "1");
	mysql_select_db("dhx4");
	
	$id = @$_REQUEST["id"];
	$nodes = array();
	
	if (is_numeric($id)) {
		
		$r = mysql_query("SELECT ".
					"t.id AS id, ".
					"t.pId AS pId, ".
					"t.text AS text, ".
					"IF ((SELECT COUNT(*) FROM tree_def AS p WHERE p.pId=t.id)>0, 1, 0) AS kids ".
					"FROM tree_def AS t ".
					"WHERE t.pId='".p($id)."' ");
		
		while ($o = mysql_fetch_object($r)) {
			$item = array("id: ".$o->id, "text: '".r($o->text)."'");
			if ($o->kids > 0) array_push($item, "kids: true");
			array_push($nodes, " {".implode(", ", $item)."}");
		}
		
		mysql_free_result($r);
	}
	
	if ($id != 0) sleep(1);
	
	$out = "[\n".implode(",\n", $nodes)."\n]";
	if ($id > 0) $out = "[{id: ".$id.", items: ".$out."}]"; // sub level, add wrap with parentId item
	
	print_r($out);

?>
