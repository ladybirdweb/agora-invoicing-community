<?php
header("Content-Type: text/xml");

// we removed database support from this demo to make it more easy for local tests
// general notes for response:
// 1) top node <menu> for top level (i.e. w/o parentId attr)
// 2) top node <menu parentId="xxx"> for sublevel, xxx => $_REQUEST["parentId"]
// 3) for xomplex item use attach complex="true"
// 4) param $_REQUEST["action"] is set to "loadMenu" (if server side used for several purposes)

$xml = '<?xml version="1.0"?>';

// top level
if (!isset($_REQUEST["parentId"])) {
	
	$xml .= '<menu>'.
			'<item id="navigation" text="Navigation" complex="true"></item>'.
			'<item id="help" text="Help" complex="true"></item>'.
		'</menu>';

} else {
	
	$xml .= '<menu parentId="'.$_REQUEST["parentId"].'">';
	
	switch($_REQUEST["parentId"]) {
		
		case "navigation":
			$xml .= '<item id="nav_general" text="General" complex="true"></item>'.
				'<item id="nav_settings" text="Settings" complex="true"></item>';
			break;
		
		case "help":
			$xml .= '<item id="help_about" text="About..." img="about.gif"></item>'.
				'<item id="help_help" text="Help" img="help.gif"></item>'.
				'<item id="help_bugrep" text="Bug Reporting" img="bug_reporting.gif"></item>'.
				'<item id="help_s" type="separator"></item>'.
				'<item id="help_tip" text="Tip of the Day" type="checkbox" enabled="false" checked="true"></item>';
			break;
		
		case "nav_general":
			$xml .= '<item id="file" text="File" complex="true"></item>'.
				'<item id="edit" text="Edit" complex="true"></item>';
			break;
		
		case "file":
			$xml .= '<item id="file_new" text="New" img="new.gif" imgdis="new_dis.gif"></item>'.
				'<item id="file_s1" type="separator"></item>'.
				'<item id="file_open" text="Open" img="open.gif"></item>'.
				'<item id="file_save" text="Save" img="save.gif" imgdis="save_dis.gif"></item>'.
				'<item id="file_saveas" text="Save As..." enabled="false" imgdis="save_as_dis.gif"></item>'.
				'<item id="file_s2" type="separator"></item>'.
				'<item id="file_print" text="Print" img="print.gif" imgdis="print_dis.gif"></item>'.
				'<item id="file_pagesetup" text="Page Setup" enabled="false" img="page_setup.gif" imgdis="page_setup_dis.gif"></item>'.
				'<item id="file_s3" type="separator"></item>'.
				'<item id="file_close" text="Close" img="close.gif"></item>';
			break;
		
		case "edit":
			$xml .= '<item id="edit_undo" text="Undo" img="undo.gif" imgdis="undo_dis.gif"></item>'.
				'<item id="edit_redo" text="Redo" img="redo.gif" imgdis="redo_dis.gif"></item>'.
				'<item id="edit_s1" type="separator"></item>'.
				'<item id="edit_selectall" text="Select All" img="select_all.gif" imgdis="select_all_dis.gif"></item>'.
				'<item id="edit_s2" type="separator"></item>'.
				'<item id="edit_cut" text="Cut" img="cut.gif" imgdis="cut_dis.gif"></item>'.
				'<item id="edit_copy" text="Copy" img="copy.gif" imgdis="copy_dis.gif"></item>'.
				'<item id="edit_paste" text="Paste" img="paste.gif" imgdis="paste_dis.gif"></item>';
			break;
		
		case "nav_settings":
			$xml .= '<item id="set_linenumber" text="Line Numbering" type="checkbox" enabled="false" checked="true"></item>'.
				'<item id="set_colortext" text="Colorize Text" type="checkbox" checked="true"></item>'.
				'<item id="set_ignorecase" text="Ignore Case" type="checkbox"></item>'.
				'<item id="set_s1" type="separator"></item>'.
				'<item id="set_popup" text="Show Popup on Errors" type="checkbox"></item>'.
				'<item id="set_s2" type="separator"></item>'.
				'<item id="set_forecolor" text="Font Color" complex="true"></item>'.
				'<item id="set_backcolor" text="Background Color" complex="true"></item>'.
				'<item id="set_s3" type="separator"></item>'.
				'<item id="set_eol" text="End Of Line" complex="true"></item>'.
				'<item id="set_syntax" text="Syntax" complex="true"></item>';
			break;
		
		case "set_forecolor":
			$xml .= '<item id="fcolor_black" text="Black" type="radio" checked="true" group="fontcolor"></item>'.
				'<item id="fcolor_brown" text="Brown" type="radio" group="fontcolor"></item>'.
				'<item id="fcolor_red" text="Red" type="radio" group="fontcolor"></item>'.
				'<item id="fcolor_blue" text="Blue" type="radio" group="fontcolor"></item>';
			break;
		
		case "set_backcolor":
			$xml .= '<item id="bgcolor_transparent" text="Transparent" type="radio" checked="true" group="bgcolor"></item>'.
				'<item id="bgcolor_s" type="separator"></item>'.
				'<item id="bgcolor_white" text="White" type="radio" group="bgcolor"></item>'.
				'<item id="bgcolor_yellow" text="Yellow" type="radio" group="bgcolor"></item>'.
				'<item id="bgcolor_cyan" text="Cyan" type="radio" group="bgcolor"></item>'.
				'<item id="bgcolor_magenta" text="Magenta" type="radio" group="bgcolor"></item>'.
				'<item id="bgcolor_black" text="Black" type="radio" group="bgcolor"></item>';
			break;
		
		case "set_eol":
			$xml .= '<item id="eol_unix" text="Unix" type="radio" enabled="false" checked="true" group="eol"></item>'.
				'<item id="eol_dos" text="DOS/Windows" type="radio" enabled="false" group="eol"></item>'.
				'<item id="eol_macos" text="MacOS" type="radio" enabled="false" group="eol"></item>';
			break;
			
		case "set_syntax":
			$xml .= '<item id="syntax_ignore" text="Ignore" type="radio" checked="true" group="syntax"></item>'.
				'<item id="syntax_s" type="separator"></item>'.
				'<item id="syntax_htmljs" text="HTML/JS" type="radio" group="syntax"></item>'.
				'<item id="syntax_phpaspsjp" text="PHP/ASP/JSP" type="radio" group="syntax"></item>'.
				'<item id="syntax_java" text="Java" type="radio" group="syntax"></item>';
			break;
	}
	
	$xml .= '</menu>';
}


print_r($xml);

?>