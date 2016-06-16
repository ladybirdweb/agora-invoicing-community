<?php
header("Content-Type: text/plain");

// we removed database support from this demo to make it more easy for local tests
// general notes for response:
// 1) top node <menu> for top level (i.e. w/o parentId attr)
// 2) top node <menu parentId: "xxx"> for sublevel, xxx => $_REQUEST["parentId"]
// 3) for xomplex item use attach complex: true
// 4) param $_REQUEST["action"] is set to "loadMenu" (if server side used for several purposes)

$xml = '';

// top level
if (!isset($_REQUEST["parentId"])) {
	
	$xml .= '['.
			'{id: "navigation", text: "Navigation", complex: true},'.
			'{id: "help", text: "Help", complex: true}'.
		']';

} else {
	
	$xml .= '{parentId: "'.$_REQUEST["parentId"].'", items: [';
	
	switch($_REQUEST["parentId"]) {
		
		case "navigation":
			$xml .= '{id: "nav_general", text: "General", complex: true},'.
				'{id: "nav_settings", text: "Settings", complex: true}';
			break;
		
		case "help":
			$xml .= '{id: "help_about", text: "About...", img: "about.gif"},'.
				'{id: "help_help", text: "Help", img: "help.gif"},'.
				'{id: "help_bugrep", text: "Bug Reporting", img: "bug_reporting.gif"},'.
				'{id: "help_s", type: "separator"},'.
				'{id: "help_tip", text: "Tip of the Day", type: "checkbox", enabled: false, checked: true}';
			break;
		
		case "nav_general":
			// sleep(2);
			$xml .= '{id: "file", text: "File", complex: true},'.
				'{id: "edit", text: "Edit", complex: true}';
			break;
		
		case "file":
			$xml .= '{id: "file_new", text: "New", img: "new.gif", imgdis: "new_dis.gif"},'.
				'{id: "file_s1", type: "separator"},'.
				'{id: "file_open", text: "Open", img: "open.gif"},'.
				'{id: "file_save", text: "Save", img: "save.gif", imgdis: "save_dis.gif"},'.
				'{id: "file_saveas", text: "Save As...", enabled: false, imgdis: "save_as_dis.gif"},'.
				'{id: "file_s2", type: "separator"},'.
				'{id: "file_print", text: "Print", img: "print.gif", imgdis: "print_dis.gif"},'.
				'{id: "file_pagesetup", text: "Page Setup", enabled: false, img: "page_setup.gif", imgdis: "page_setup_dis.gif"},'.
				'{id: "file_s3", type: "separator"},'.
				'{id: "file_close", text: "Close", img: "close.gif"}';
			break;
		
		case "edit":
			$xml .= '{id: "edit_undo", text: "Undo", img: "undo.gif", imgdis: "undo_dis.gif"},'.
				'{id: "edit_redo", text: "Redo", img: "redo.gif", imgdis: "redo_dis.gif"},'.
				'{id: "edit_s1", type: "separator"},'.
				'{id: "edit_selectall", text: "Select All", img: "select_all.gif", imgdis: "select_all_dis.gif"},'.
				'{id: "edit_s2", type: "separator"},'.
				'{id: "edit_cut", text: "Cut", img: "cut.gif", imgdis: "cut_dis.gif"},'.
				'{id: "edit_copy", text: "Copy", img: "copy.gif", imgdis: "copy_dis.gif"},'.
				'{id: "edit_paste", text: "Paste", img: "paste.gif", imgdis: "paste_dis.gif"}';
			break;
		
		case "nav_settings":
			// sleep(2);
			$xml .= '{id: "set_linenumber", text: "Line Numbering", type: "checkbox", enabled: false, checked: true},'.
				'{id: "set_colortext", text: "Colorize Text", type: "checkbox", checked: true},'.
				'{id: "set_ignorecase", text: "Ignore Case", type: "checkbox"},'.
				'{id: "set_s1", type: "separator"},'.
				'{id: "set_popup", text: "Show Popup on Errors", type: "checkbox"},'.
				'{id: "set_s2", type: "separator"},'.
				'{id: "set_forecolor", text: "Font Color", complex: true},'.
				'{id: "set_backcolor", text: "Background Color", complex: true},'.
				'{id: "set_s3", type: "separator"},'.
				'{id: "set_eol", text: "End Of Line", complex: true},'.
				'{id: "set_syntax", text: "Syntax", complex: true}';
			break;
		
		case "set_forecolor":
			$xml .= '{id: "fcolor_black", text: "Black", type: "radio", checked: true, group: "fontcolor"},'.
				'{id: "fcolor_brown", text: "Brown", type: "radio", group: "fontcolor"},'.
				'{id: "fcolor_red", text: "Red", type: "radio", group: "fontcolor"},'.
				'{id: "fcolor_blue", text: "Blue", type: "radio", group: "fontcolor"}';
			break;
		
		case "set_backcolor":
			$xml .= '{id: "bgcolor_transparent", text: "Transparent", type: "radio", checked: true, group: "bgcolor"},'.
				'{id: "bgcolor_s", type: "separator"},'.
				'{id: "bgcolor_white", text: "White", type: "radio", group: "bgcolor"},'.
				'{id: "bgcolor_yellow", text: "Yellow", type: "radio", group: "bgcolor"},'.
				'{id: "bgcolor_cyan", text: "Cyan", type: "radio", group: "bgcolor"},'.
				'{id: "bgcolor_magenta", text: "Magenta", type: "radio", group: "bgcolor"},'.
				'{id: "bgcolor_black", text: "Black", type: "radio", group: "bgcolor"}';
			break;
		
		case "set_eol":
			$xml .= '{id: "eol_unix", text: "Unix", type: "radio", enabled: false, checked: true, group: "eol"},'.
				'{id: "eol_dos", text: "DOS/Windows", type: "radio", enabled: false, group: "eol"},'.
				'{id: "eol_macos", text: "MacOS", type: "radio", enabled: false, group: "eol"}';
			break;
			
		case "set_syntax":
			$xml .= '{id: "syntax_ignore", text: "Ignore", type: "radio", checked: true, group: "syntax"},'.
				'{id: "syntax_s", type: "separator"},'.
				'{id: "syntax_htmljs", text: "HTML/JS", type: "radio", group: "syntax"},'.
				'{id: "syntax_phpaspsjp", text: "PHP/ASP/JSP", type: "radio", group: "syntax"},'.
				'{id: "syntax_java", text: "Java", type: "radio", group: "syntax"}';
			break;
	}
	
	$xml .= ']}';
}


print_r($xml);

?>