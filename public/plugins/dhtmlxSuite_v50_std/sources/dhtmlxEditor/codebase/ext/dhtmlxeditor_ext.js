/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXEditor.prototype.attachToolbar = function(iconsPath) {
	
	if (this.tb != null) return;
	
	if (iconsPath != null) this.conf.iconsPath = iconsPath;
	
	this.cell._stbHide();
	this.tb = this.cell.attachToolbar({
		icons_path: this.conf.iconsPath+"/dhxeditor_"+String(this.conf.skin).replace(/^dhx_/,"")+"/",
		skin: this.conf.skin
	});
	this.setSizes();
	
	var ext = (this.conf.skin=="material"?"png":"gif");
	
	this._availFonts = new Array("Arial", "Arial Narrow", "Comic Sans MS", "Courier", "Georgia", "Impact", "Tahoma", "Times New Roman", "Verdana");
	this._initFont = this._availFonts[0];
	this._xmlFonts = "";
	for (var q=0; q<this._availFonts.length; q++) {
		var fnt = String(this._availFonts[q]).replace(/\s/g,"_");
		this._xmlFonts += '<item type="button" id="applyFontFamily:'+fnt+'"><itemText><![CDATA[<img src="'+this.tb.imagePath+'font_'+String(fnt).toLowerCase()+'.'+ext+'" border="0" style="/*margin-top:1px;margin-bottom:1px;*/width:110px;height:16px;">]]></itemText></item>';
	}
	//
	this._availSizes = {"1":"8pt", "2":"10pt", "3":"12pt", "4":"14pt", "5":"18pt", "6":"24pt", "7":"36pt"};
	this._xmlSizes = "";
	for (var a in this._availSizes) {
		this._xmlSizes += '<item type="button" id="applyFontSize:'+a+':'+this._availSizes[a]+'" text="'+this._availSizes[a]+'"/>';
	}
	this.tbXML = '<toolbar>'+
				// h1-h4
				'<item id="applyH1" type="buttonTwoState" img="h1.'+ext+'" imgdis="h4_dis.'+ext+'" title="H1"/>'+
				'<item id="applyH2" type="buttonTwoState" img="h2.'+ext+'" imgdis="h4_dis.'+ext+'" title="H2"/>'+
				'<item id="applyH3" type="buttonTwoState" img="h3.'+ext+'" imgdis="h4_dis.'+ext+'" title="H3"/>'+
				'<item id="applyH4" type="buttonTwoState" img="h4.'+ext+'" imgdis="h4_dis.'+ext+'" title="H4"/>'+
				'<item id="separ01" type="separator"/>'+
				// text
				'<item id="applyBold" type="buttonTwoState" img="bold.'+ext+'" imgdis="bold_dis.'+ext+'" title="Bold Text"/>'+
				'<item id="applyItalic" type="buttonTwoState" img="italic.'+ext+'" imgdis="italic_dis.'+ext+'" title="Italic Text"/>'+
				'<item id="applyUnderscore" type="buttonTwoState" img="underline.'+ext+'" imgdis="underline_dis.'+ext+'" title="Underscore Text"/>'+
				'<item id="applyStrikethrough" type="buttonTwoState" img="strike.'+ext+'" imgdis="strike_dis.'+ext+'" title="Strikethrough Text"/>'+
				'<item id="separ02" type="separator"/>'+
				// align
				'<item id="alignLeft" type="buttonTwoState" img="align_left.'+ext+'" imgdis="align_left_dis.'+ext+'" title="Left Alignment"/>'+
				'<item id="alignCenter" type="buttonTwoState" img="align_center.'+ext+'" imgdis="align_center_dis.'+ext+'" title="Center Alignment"/>'+
				'<item id="alignRight" type="buttonTwoState" img="align_right.'+ext+'" imgdis="align_right_dis.'+ext+'" title="Right Alignment"/>'+
				'<item id="alignJustify" type="buttonTwoState" img="align_justify.'+ext+'" title="Justified Alignment"/>'+
				'<item id="separ03" type="separator"/>'+
				// sub/super script
				'<item id="applySub" type="buttonTwoState" img="script_sub.'+ext+'" imgdis="script_sub.'+ext+'" title="Subscript"/>'+
				'<item id="applySuper" type="buttonTwoState" img="script_super.'+ext+'" imgdis="script_super_dis.'+ext+'" title="Superscript"/>'+
				'<item id="separ04" type="separator"/>'+
				// etc
				'<item id="createNumList" type="button" img="list_number.'+ext+'" imgdis="list_number_dis.'+ext+'" title="Number List"/>'+
				'<item id="createBulList" type="button" img="list_bullet.'+ext+'" imgdis="list_bullet_dis.'+ext+'" title="Bullet List"/>'+
				'<item id="separ05" type="separator"/>'+
				//
				'<item id="increaseIndent" type="button" img="indent_inc.'+ext+'" imgdis="indent_inc_dis.'+ext+'" title="Increase Indent"/>'+
				'<item id="decreaseIndent" type="button" img="indent_dec.'+ext+'" imgdis="indent_dec_dis.'+ext+'" title="Decrease Indent"/>'+
				'<item id="separ06" type="separator"/>'+
				'<item id="clearFormatting" type="button" img="clear.'+ext+'" title="Clear Formatting"/>'+
			'</toolbar>';
	
	this.tb.loadStruct(this.tbXML);
	
	this._checkAlign = function(alignSelected) {
		this.tb.setItemState("alignCenter", false);
		this.tb.setItemState("alignRight", false);
		this.tb.setItemState("alignJustify", false);
		this.tb.setItemState("alignLeft", false);
		if (alignSelected) this.tb.setItemState(alignSelected, true);
	}
	
	this._checkH = function(h) {
		this.tb.setItemState("applyH1", false);
		this.tb.setItemState("applyH2", false);
		this.tb.setItemState("applyH3", false);
		this.tb.setItemState("applyH4", false);
		if (h) this.tb.setItemState(h, true);
	}
	
	this._doOnFocusChanged = function(state) {
		/*bold*/
		if(!state.h1&&!state.h2&&!state.h3&&!state.h4){
			var bold = (String(state.fontWeight).search(/bold/i) != -1) || (Number(state.fontWeight) >= 700);
			this.tb.setItemState("applyBold", bold);
		} else this.tb.setItemState("applyBold", false);
		// align
		var alignId = "alignLeft";
		if (String(state.textAlign).search(/center/) != -1) { alignId = "alignCenter"; }
		if (String(state.textAlign).search(/right/) != -1) { alignId = "alignRight"; }
		if (String(state.textAlign).search(/justify/) != -1) { alignId = "alignJustify"; }
		this.tb.setItemState(alignId, true);
		this._checkAlign(alignId);
		/*heading*/
		this.tb.setItemState("applyH1", state.h1);
		this.tb.setItemState("applyH2", state.h2);
		this.tb.setItemState("applyH3", state.h3);
		this.tb.setItemState("applyH4", state.h4);
		if (window._KHTMLrv) {
			/*for Safari*/
			state.sub = (state.vAlign == "sub");
			state.sup = (state.vAlign == "super");
		}
		this.tb.setItemState("applyItalic", (state.fontStyle == "italic"));
		this.tb.setItemState("applyStrikethrough", state.del);
		this.tb.setItemState("applySub", state.sub);
		this.tb.setItemState("applySuper", state.sup);
		this.tb.setItemState("applyUnderscore", state.u);
	}
	
	this._doOnToolbarClick = function(id) {
		var action = String(id).split(":");
		if (this[action[0]] != null) {
			if (typeof(this[action[0]]) == "function") {
				this[action[0]](action[1]);
				this.callEvent("onToolbarClick",[id]);
			}
		}
	}
	
	this._doOnStateChange = function(itemId, state) {
		this[itemId]();
		switch (itemId) {
			case "alignLeft":
			case "alignCenter":
			case "alignRight":
			case "alignJustify":
				this._checkAlign(itemId);
				break;
			case "applyH1":
			case "applyH2":
			case "applyH3":
			case "applyH4":
				this._checkH(itemId);
				break;
		}
		this.callEvent("onToolbarClick",[itemId]);
	}
	this._doOnBeforeStateChange = function(itemId, state) {
		if ((itemId == "alignLeft" || itemId == "alignCenter" || itemId == "alignRight" || itemId == "alignJustify") && state == true) {
			return false;
		}
		return true;
	}
	var that = this;
	
	this.tb.attachEvent("onClick", function(id){that._doOnToolbarClick(id);});
	this.tb.attachEvent("onStateChange", function(id,st){that._doOnStateChange(id,st);});
	this.tb.attachEvent("onBeforeStateChange", function(id,st){return that._doOnBeforeStateChange(id,st);});
	
	this.applyBold = function(){
		this._runCommand("Bold");
	}
	
	this.applyItalic = function(){
		this._runCommand("Italic");
	}
	
	this.applyUnderscore = function(){
		this._runCommand("Underline");
	}
	
	this.applyStrikethrough = function(){
		this._runCommand("StrikeThrough");
	}
	
	this.alignLeft = function(){
		this._runCommand("JustifyLeft");
	}
	
	this.alignRight = function(){
		this._runCommand("JustifyRight");
	}
	
	this.alignCenter = function(){
		this._runCommand("JustifyCenter");
	}
	
	this.alignJustify = function(){
		this._runCommand("JustifyFull");
	}
	
	this.applySub = function(){
		this._runCommand("Subscript");
	}
	
	this.applySuper = function(){
		this._runCommand("Superscript");
	}
	
	this.applyH1 = function(){
		this._runCommand("FormatBlock","<H1>");
	}
	
	this.applyH2 = function(){
		this._runCommand("FormatBlock","<H2>");
	}
	
	this.applyH3 = function(){
		this._runCommand("FormatBlock","<H3>");
	}
	
	this.applyH4 = function(){
		this._runCommand("FormatBlock","<H4>");
	}
	
	this.createNumList = function(){
		this._runCommand("InsertOrderedList");
	}
	
	this.createBulList = function(){
		this._runCommand("InsertUnorderedList");
	}
	
	this.increaseIndent = function(){
		this._runCommand("Indent");
	}
	
	this.decreaseIndent = function(){
		this._runCommand("Outdent");
	}
	this.clearFormatting = function() {
		this._runCommand("RemoveFormat");
		this.tb.setItemState("applyBold", false);
		this.tb.setItemState("applyItalic", false);
		this.tb.setItemState("applyStrikethrough", false);
		this.tb.setItemState("applySub", false);
		this.tb.setItemState("applySuper", false);
		this.tb.setItemState("applyUnderscore", false);
		var k = this.getContent();
		k = k.replace(/<\/?h\d>/gi, "");
		this.setContent(k);
	}
	
};

