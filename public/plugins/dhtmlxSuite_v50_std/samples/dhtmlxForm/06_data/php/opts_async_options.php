<?php

sleep(2);

header("Content-Type: text/xml");

if ($_REQUEST["t"] == "select") {

	echo '<?xml version="1.0" encoding="utf-8"?>'.
		'<data>'.
			'<item value="0" label="Pflegeprogramm"/>'.
			'<item value="1" label="Radwechsel"/>'.
			'<item value="2" label="Mobilitaet"/>'.
			'<item value="3" label="Link"/>'.
		'</data>';
	
}

if ($_REQUEST["t"] == "combo") {

	echo '<?xml version="1.0" encoding="utf-8"?>'.
		'<complete>'.
			'<option value="0">Pflegeprogramm</option>'.
			'<option value="1">Radwechsel</option>'.
			'<option value="2">Mobilitaet</option>'.
			'<option value="3">Link</option>'.
		'</complete>';
	
}

?>