dhtmlXDataView.prototype.types.ficon={
	css:"ficon",
	template:dhtmlx.Template.fromHTML("<div align='center'><img onmousedown='return false;' border='0' src='{common.image()}'><div class='dhx_item_text'>{common.text()}</div></div>"),
	template_edit:dhtmlx.Template.fromHTML("<div align='center'><img onmousedown='return false;' border='0' src='{common.image()}'><input class='dhx_item_editor' bind='obj.name'></div>"),
	template_loading:dhtmlx.Template.fromHTML(""),
	width:75,
	height:75,
	margin:1,
	padding:0,
	drag_marker:"dnd_selector_cells.png",
	//custom properties
	icons_src_dir:"./",
	image:function(obj){
		return this.icons_src_dir + "/ico_"+(obj.name.split(".")[1]||"fldr") + "_32.gif";
	},
	text:function(obj){
		return obj.name.split(".")[0];
	}
};

dhtmlXDataView.prototype.types.ftiles={
	css:"ftiles",
	template:dhtmlx.Template.fromHTML("<img onmousedown='return false;' style='width:48px; float: left;' border='0' src='{common.image()}'><div style='margin-top: 10px;' class='dhx_item_text'>{common.text()}</div><div class='dhx_item_text_gray'>{common.size()}</div>"),
	template_edit:dhtmlx.Template.fromHTML("<img onmousedown='return false;' style='width:48px; float: left;' border='0' src='{common.image()}'><textarea class='dhx_item_editor' bind='obj.name'></textarea></div>"),
	template_loading:dhtmlx.Template.fromHTML(""),
	width:140,
	height:58,
	margin:1,
	padding:4,
	drag_marker:"dnd_selector_cells.png",
	//custom properties
	icons_src_dir:"./",
	image:function(obj){
		return this.icons_src_dir + "/ico_"+(obj.name.split(".")[1]||"fldr") + "_48.gif";
	},
	text:function(obj){
		return obj.name.split(".")[0];
	},
	size:function(obj){
		return obj.filesize?(obj.filesize+"b"):"";
	}
};

dhtmlXDataView.prototype.types.ftable={
	css:"ftable",
	template:dhtmlx.Template.fromHTML("<div style='float: left; width: 17px;'><img onmousedown='return false;' border='0' src='{common.image()}'></div><div style='float: left; width: 115px; overflow:hidden;' class='dhx_item_text'><span style='padding-left: 2px; padding-right: 2px;'>{common.text()}</span></div><div style='float: left; width: 60px; text-align: right;' class='dhx_item_text'>{common.size()}</div><div style='float: left; width: 130px; padding-left: 10px;' class='dhx_item_text'>{common.date()}</div>"),
	template_edit:dhtmlx.Template.fromHTML("<div style='float: left; width: 17px;'><img onmousedown='return false;' border='0' src='{common.image()}'></div><div style='float: left; width: 115px;' class='dhx_item_text'><span style='padding-left: 2px; padding-right: 2px;'><input type='text' style='width:100%; height:100%;' bind='obj.name'></span></div><div style='float: left; width: 60px; text-align: right;' class='dhx_item_text'>{common.size()}</div><div style='float: left; width: 130px; padding-left: 10px;' class='dhx_item_text'>{common.date()}</div>"),
	template_loading:dhtmlx.Template.fromHTML(""),
	width:370,
	height:20,
	margin:1,
	padding:0,
	drag_marker:"dnd_selector_lines.png",
	//custom properties
	icons_src_dir:"./",
	image:function(obj){
		return this.icons_src_dir + "/ico_"+(obj.name.split(".")[1]||"fldr") + "_18.gif";
	},
	text:function(obj){
		return obj.name.split(".")[0];
	},
	size:function(obj){
		return obj.filesize?(obj.filesize+"b"):"";
	},
	date:function(obj){
		return obj.modifdate;
	}
};

dhtmlXDataView.prototype.types.fthumbs={
	css:"fthumbs",
	template:dhtmlx.Template.fromHTML("<div align='center'><img border='0' src='{common.image()}'><div class='dhx_item_text'><span>{common.text()}</span></div></div>"),
	width:110,
	height:116,
	margin:15,
	padding:2,
	//custom properties
	thumbs_creator_url:"./",
	photos_rel_dir:"./",
	image:function(obj){
		return this.thumbs_creator_url+"?img="+this.photos_rel_dir+"/"+obj.name+"&width=94&height=94";
	},
	text:function(obj){
		return obj.name.split(".")[0];
	}
};