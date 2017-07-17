<?php
	
	header("Content-Type: text/xml");
	
	if (@$_REQUEST["mode"] == "combo") {
		$root_tag = "complete";
		$tpl = '<option value="%value%" %selected%>%text%</option>';
	} else {
		$root_tag = "data";
		$tpl = '<item value="%value%" label="%text%" %selected%/>';
	}
	
	function build_opts($type, $tpl) {
		
		if ($type == "colors") {
			$data = array("DarkBlue","DarkCyan","DarkGoldenRod","DarkGray","DarkGreen","DarkKhaki","DarkMagenta","DarkOliveGreen","Darkorange");
			$sel = 4;
		} else {
			$data = array("Acura","AMC Cars","Audi","Alfa Romeo","Aston Martin","Bentley","BMW","Bugatti","Cadillac","Chrysler","Ford","Honda","Hummer","Infiniti","Jeep");
			$sel = 3;
		}
		
		$t = "";
		for ($q=0; $q<count($data); $q++) {
			$t .= str_replace(array("%value%", "%text%", "%selected%"), array($q, $data[$q], ($q==$sel?' selected="true"':'')), $tpl);
		}
		
		return $t;
	}
	
	echo '<?xml version="1.0" encoding="utf-8"?>'.
		'<'.$root_tag.'>'.build_opts(@$_REQUEST["type"], $tpl).'</'.$root_tag.'>';
	
	
?>