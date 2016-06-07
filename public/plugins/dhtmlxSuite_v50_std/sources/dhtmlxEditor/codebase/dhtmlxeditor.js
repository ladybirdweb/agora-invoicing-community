/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXEditor(base, skin) {
	
	var that = this;
	
	this.conf = {
		// set content after load
		content: "", // first if set
		contentHTML: "", // second
		// resize
		resizeTM: null,
		resizeTMTime: 100,
		// readonly
		roMode: false,
		// extended toolbar
		toolbar: false,
		iconsPath: "",
		// frame events
		evs: ["focus", "blur", "keydown", "keyup", "keypress", "mouseup", "mousedown", "click", "touchend"], // touchend is dev_event for fix on iOS/iPad
		// focus fix on iOS/iPad
		iOSfix: (navigator.userAgent.match(/Mobile/gi) != null && navigator.userAgent.match(/iPad/gi) != null && navigator.userAgent.match(/AppleWebKit/gi) != null),
		// extended font conf
		extra_css: "",
		font: {family: "Tahoma", size: "12px", color: "black"}
	};
	
	this._doOnFocusChanged = null;
	this._doOnAccess = null;
	
	if (typeof(base) == "object" && base != null && base.tagName == null) {
		skin = base.skin;
		if (base.content != null) this.conf.content = base.content;
		if (base.contentHTML != null) this.conf.contentHTML = base.contentHTML;
		if (base.iconsPath != null) this.conf.iconsPath = base.iconsPath;
		if (base.extraCss != null) this.conf.extra_css = base.extraCss;
		if (base.toolbar != null) this.conf.toolbar = window.dhx4.s2b(base.toolbar);
		if (base.onFocusChanged != null) this._doOnFocusChanged = base.onFocusChanged;
		if (base.onAccess != null) this._doOnAccess = base.onAccess;
		base = base.parent;
	}
	
	// skin config
	this.conf.skin = (skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxeditor")||"material");
	
	// configure base
	if (typeof(base) == "string") base = document.getElementById(base);
	this.base = base;
	this.base.className += " dhxeditor_"+this.conf.skin;
	
	while (this.base.childNodes.length > 0) this.base.removeChild(this.base.childNodes[0]);
	
	// configure base for dhxcont
	var pos = (window.dhx4.isIE ? this.base.currentStyle["position"] : (window.getComputedStyle != null? window.getComputedStyle(this.base, null).getPropertyValue("position") : "" ));
	if (!(pos == "relative" || pos == "absolute")) this.base.style.position = "relative";
	
	// init dhxcont
	this.cell = new dhtmlXEditorCell(window.dhx4.newId(), this);
	this.base.appendChild(this.cell.cell);
	
	this.cBlock = document.createElement("DIV");
	this.cBlock.className = "dhxcont_content_blocker";
	this.cBlock.style.display = "none";
	this.base.appendChild(this.cBlock);
	
	// editable area
	this.editor = document.createElement("IFRAME");
	this.editor.className = "dhxeditor_mainiframe";
	this.editor.frameBorder = 0;
	if (window.dhx4.isOpera) this.editor.scrolling = "yes";
	
	// adjust self
	this.setSizes();
	
	// onAccess event - focus/blue as param
	var fr = this.editor;
	if (typeof(window.addEventListener) != "undefined") {
		fr.onload = function() {
			for (var q=0; q<that.conf.evs.length; q++) {
				fr.contentWindow.addEventListener(that.conf.evs[q], that._ev, false);
			}
		}
	} else {
		fr.onreadystatechange = function(a) {
			if (typeof(fr.readyState) != "undefined" && fr.readyState == "complete") {
				try {
					for (var q=0; q<that.conf.evs.length; q++) {
						fr.contentWindow.document.body.attachEvent("on"+that.conf.evs[q], that._ev);
					}
				} catch(e){};
			}
		}
	}
	
	this._ev = function(e) {
		e = e||event;
		var type = e.type;
		if (that.conf.iOSfix == true && type == "touchend") {
			that.editor.contentWindow.focus();
			return;
		}
		that.callEvent("onAccess", [type, e]);
		if (typeof(that._doOnAccess) == "function") {
			that._doOnAccess(type, e);
		} else if (typeof(that._doOnAccess) == "string" && typeof(window[that._doOnAccess]) == "function") {
			window[that._doOnAccess](type, e);
		}
	}
	this._focus = function() {
		if (window.dhx4.isIE) {
			this.editor.contentWindow.document.body.focus();
		} else {
			this.editor.contentWindow.focus();
		}
	}
	
	this.cell.attachObject(this.editor);
	this.edWin = this.editor.contentWindow;
	this.edDoc = this.edWin.document;
	
	this._prepareContent = function(saveContent, roMode) {
		
		var storedContent = "";
		if (saveContent === true && this.getContent != null) storedContent = this.getContent();
		
		var edDoc = this.editor.contentWindow.document;
		edDoc.open("text/html", "replace");
		if (window.dhx4.isOpera) {
			edDoc.write("<html><head>"+this.conf.extra_css+
					"<style> html, body { overflow:auto;-webkit-overflow-scrolling: touch; padding:0px; height:100%; margin:0px; background-color:#ffffff; "+this._fontConf()+"} </style>"+
					"</head><body "+(roMode!==true?"contenteditable='true'":"")+" tabindex='0'></body></html>");
		} else {
			if (window.dhx4.isKHTML) {
				edDoc.write("<html><head>"+this.conf.extra_css+
						"<style> html {overflow-x: auto;-webkit-overflow-scrolling: touch; overflow-y: auto;} body { overflow: auto; overflow-y: scroll;} "+
							"html,body { padding:0px; height:100%; margin:0px; background-color:#ffffff; "+this._fontConf()+"} </style>"+
						"</head><body "+(roMode!==true?"contenteditable='true'":"")+" tabindex='0'></body></html>");
			} else {
				if (window.dhx4.isIE) {
					// && navigator.appVersion.indexOf("MSIE 9.0")!= -1
					edDoc.write("<html><head>"+this.conf.extra_css+
							"<style> html {overflow-y: auto;} body {overflow-y: scroll;-webkit-overflow-scrolling: touch;} "+
								"html,body { overflow-x: auto; padding:0px; height:100%; margin:0px; background-color: #ffffff; outline: none; "+this._fontConf()+"} </style>"+
							"</head><body "+(roMode!==true?"contenteditable='true'":"")+" tabindex='0'></body></html>");
				} else {
					edDoc.write("<html><head>"+this.conf.extra_css+
							"<style> html,body { overflow-x: auto; overflow-y:-webkit-overflow-scrolling: touch; scroll; "+
								"padding:0px; height:100%; margin:0px; background-color:#ffffff; "+this._fontConf()+"} </style>"+
							"</head><body "+(roMode!==true?"contenteditable='true'":"")+" tabindex='0'></body></html>");
				}
			}
		}
		edDoc.close();
		
		if (window.dhx4.isIE) edDoc.contentEditable = (roMode!==true); else edDoc.designMode = (roMode!==true?"On":"Off");
		if (window.dhx4.isFF) try { edDoc.execCommand("useCSS", false, true); } catch(e) {}
		if (saveContent === true && this.setContent != null) this.setContent(storedContent);
		
	}
	
	// fix
	this._prepareContent();
	
	// resize
	this._doOnResize = function() {
		window.clearTimeout(that.conf.resizeTM);
		that.conf.resizeTM = window.setTimeout(function(){if(that.setSizes)that.setSizes();}, that.conf.resizeTMTime);
	}
	
	// toolbar buttons
	this._runCommand = function(name,param){
		if (this.conf.roMode === true) return;
		if (arguments.length < 2) param = null;
		if (window.dhx4.isIE) this.edWin.focus();
		try {
			var edDoc = this.editor.contentWindow.document
			edDoc.execCommand(name,false,param);
		} catch(e){}
		if (window.dhx4.isIE) {
			this.edWin.focus();
			var self = this;
			window.setTimeout(function(){
				self.edWin.focus();
				self = null;
			},1);
		}
	}
	
	// commands
	this.applyBold = function() {
		this._runCommand("Bold");
	}
	this.applyItalic = function() {
		this._runCommand("Italic");
	}
	this.applyUnderscore = function() {
		this._runCommand("Underline");
	}
	this.clearFormatting = function() {
		this._runCommand("RemoveFormat");
		var k = this.getContent();
		k = k.replace(/<\/?h\d>/gi, "");
		this.setContent(k);
	}
	
	this._doOnClick = function(e) {
		var ev = e||window.event;
		var el = ev.target||ev.srcElement;
		that._showInfo(el);
	}
	
	this._doOnMouseDown = function(e) { // opera only
		var ev = e||window.event;
		var el = ev.target||ev.srcElement;
		that._showInfo(el);
	}
	
	this._doOnKeyUp = function(e) {
		var ev = e||window.event;
		var key = ev.keyCode;
		var el = ev.target||ev.srcElement;
		if ({37:1,38:1,39:1,40:1,13:1}[key] == 1) that._showInfo(el);
	}
	

	this._getParentByTag = function(node, tag_name) {
		tag_name = tag_name.toLowerCase();
		var p = node;
		do {
			if (tag_name == '' || p.nodeName.toLowerCase() == tag_name) return p;
		} while (p = p.parentNode);
		return node;
	}
	
	this._isStyleProperty = function(node, tag_name, name, value){
		tag_name = tag_name.toLowerCase();
		var n = node;
		do {
			if ((n.nodeName.toLowerCase() == tag_name) && (n.style[name] == value)) return true;
   		} while (n = n.parentNode);
   		return false;
	}
	
	this._setStyleProperty = function(el, prop) {
		this.style[prop] = false;
		var n = this._getParentByTag(el, prop);
		if (n && (n.tagName.toLowerCase() == prop)) this.style[prop] = true;
		if (prop == "del" && this._getParentByTag(el, "strike") && this._getParentByTag(el,"strike").tagName.toLowerCase() == "strike") this.style.del = true;
	}
	
	this._showInfo = function(el) {
		
		var el = (this._getSelectionBounds().end)?this._getSelectionBounds().end : el;
		if (!el || !this._setStyleProperty) return;
		
		try {
			if (this.edWin.getComputedStyle) {
				var st = this.edWin.getComputedStyle(el, null);
				var fw = ((st.getPropertyValue("font-weight")==401)?700:st.getPropertyValue("font-weight"));
				this.style = {
					fontStyle	: st.getPropertyValue("font-style"),
					fontSize	: st.getPropertyValue("font-size"),
					textDecoration	: st.getPropertyValue("text-decoration"),
					fontWeight	: fw,
					fontFamily	: st.getPropertyValue("font-family"),
					textAlign	: st.getPropertyValue("text-align")
				};
				if (window.dhx4.isKHTML) { // safari
					this.style.fontStyle = st.getPropertyValue("font-style");
					this.style.vAlign = st.getPropertyValue("vertical-align");
					this.style.del = this._isStyleProperty(el,"span","textDecoration","line-through");
					this.style.u = this._isStyleProperty(el,"span","textDecoration","underline");
				}
			} else {
				var st = el.currentStyle;
				this.style = {
					fontStyle	: st.fontStyle,
					fontSize	: st.fontSize,
					textDecoration	: st.textDecoration,
					fontWeight	:  st.fontWeight,
					fontFamily	: st.fontFamily,
					textAlign	: st.textAlign
				};
			}
			this._setStyleProperty(el,"h1");
			this._setStyleProperty(el,"h2");
			this._setStyleProperty(el,"h3");
			this._setStyleProperty(el,"h4");
			if (!window.dhx4.isKHTML) {
				this._setStyleProperty(el,"del");
				this._setStyleProperty(el,"sub");
				this._setStyleProperty(el,"sup");
				this._setStyleProperty(el,"u");
			}
			this.callEvent("onFocusChanged", [this.style, st])
		} catch(e){ return null; }
	}
	
	this._getSelectionBounds = function() {
   		var range, root, start, end;
		if (this.edWin.getSelection) {
			var selection = this.edWin.getSelection();
			if (window.dhx4.isEdge && selection.rangeCount == 0) return {root: null, start: null, end: null};
			range = selection.getRangeAt(selection.rangeCount-1);
			start = range.startContainer;
			end = range.endContainer;
			root = range.commonAncestorContainer;
			if (start.nodeName == "#text") root = root.parentNode;
			if (start.nodeName == "#text") start = start.parentNode;
			if (start.nodeName.toLowerCase() == "body") start = start.firstChild;
			if (end.nodeName == "#text") end = end.parentNode;
			if (end.nodeName.toLowerCase() == "body") end = end.lastChild;
			if (start == end) root = start;
			return {
				root: root,
				start: start,
				end: end
			};
		} else if (this.edWin.document.selection) {
			range = this.edDoc.selection.createRange()
			if(!range.duplicate) return null;
			root = range.parentElement();
			var r1 = range.duplicate();
			var r2 = range.duplicate();
			r1.collapse(true);
			r2.moveToElementText(r1.parentElement());
			r2.setEndPoint("EndToStart",r1);
			start = r1.parentElement();
			r1 = range.duplicate();
			r2 = range.duplicate();
			r2.collapse(false);
			r1.moveToElementText(r2.parentElement());
			r1.setEndPoint("StartToEnd", r2);
			end = r2.parentElement();
	   		if (start.nodeName.toLowerCase() == "body") start = start.firstChild;
			if (end.nodeName.toLowerCase() == "body") end = end.lastChild;
			
			if (start == end) root = start;
			return {
				root: root,
				start: start,
				end: end
			};
   		}
   		return null;
	}
	
	this.getContent = function(){
		if (!this.edDoc.body) {
			return "";
		} else {
			if (window.dhx4.isFF || window.dhx4.isChrome) return this.editor.contentWindow.document.body.innerHTML.replace(/<\/{0,}br\/{0,}>\s{0,}$/gi,"");
			if (window.dhx4.isIE && this.edDoc.body.innerText.length == 0) return "";
			return this.edDoc.body.innerHTML;
		}
	}
	
	this.setContent = function(str){
		str = str||"";
		if (this.edDoc.body) {
			var ffTest = false;
			if (window.dhx4.isFF) {
				var k = navigator.userAgent.match(/Firefox\/(\d*)/);
				ffTest = (k != null && k[1] < 28);
			}
			if (ffTest) {
				if (typeof(this.conf.ffTest) == "undefined") {
					this.editor.contentWindow.document.body.innerHTML = "";
					this._runCommand("InsertHTML", "test");
					this.conf.ffTest = (this.editor.contentWindow.document.body.innerHTML.length > 0);
				}
				if (this.conf.ffTest) {
					// FF 4.x+
					this.editor.contentWindow.document.body.innerHTML = str;
				} else {
					// FF 2.x, 3.x
					this.editor.contentWindow.document.body.innerHTML = "";
					if (str.length == 0) str=" ";
					this._runCommand("InsertHTML", str);
				}
			} else {
				this.editor.contentWindow.document.body.innerHTML = str;
			}
			this.callEvent("onContentSet",[]);
		} else {
			if (!this.conf.firstLoadEv) {
				this.conf.firstLoadEv = true;
				this.conf.firstLoadData = str;
				this._onFirstLoad = function() {
					that.setContent(that.conf.firstLoadData);
					if (typeof(window.addEventListener) == "function") {
						that.edWin.removeEventListener("load", that._onFirstLoad, false);
					} else {
						that.edWin.detachEvent("onload", that._onFirstLoad);
					}
					that.conf.firstLoadData = null;
					that.conf.firstLoadEv = false;
					that._onFirstLoad = null;
				}
				if (typeof(window.addEventListener) == "function") {
					this.edWin.addEventListener("load", this._onFirstLoad, false);
				} else {
					this.edWin.attachEvent("onload", this._onFirstLoad);
				}
			}
		}
	}
	
	this.setContentHTML = function(url) {
		window.dhx4.ajax.get(url, function(r) {
			if (r.xmlDoc.responseText != null) that.setContent(r.xmlDoc.responseText);
		});
	}
	
	// events
	window.dhx4._eventable(this);
	
	this.attachEvent("onFocusChanged", function(state){
		if (typeof(this._doOnFocusChanged) == "function") {
			this._doOnFocusChanged(state);
		} else if (typeof(this._doOnFocusChanged) == "string" && typeof(window[this._doOnFocusChanged]) == "function") {
			window[this._doOnFocusChanged](state);
		}
	});
	
	if (typeof(window.addEventListener) == "function") {
		window.addEventListener("resize", this._doOnResize, false);
		this.edDoc.addEventListener("click", this._doOnClick, false);
		this.edDoc.addEventListener("keyup", this._doOnKeyUp, false);
		if (window.dhx4.isOpera) this.edDoc.addEventListener("mousedown", this._doOnMouseDown, false);
	} else {
		window.attachEvent("onresize", this._doOnResize);
		this.edDoc.attachEvent("onclick", this._doOnClick);
		this.edDoc.attachEvent("onkeyup", this._doOnKeyUp);
	}
	
	this.unload = function() {
		
		// first detach events from iframe
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("resize", this._doOnResize, false);
			this.edDoc.removeEventListener("click", this._doOnClick, false);
			this.edDoc.removeEventListener("keyup", this._doOnKeyUp, false);
			if (window.dhx4.isOpera) this.edDoc.removeEventListener("mousedown", this._doOnMouseDown, false);
			// editor's
			for (var q=0; q<that.conf.evs.length; q++) {
				fr.contentWindow.removeEventListener(that.conf.evs[q], that._ev, false);
			}
			// iOS fix
			if (this.tb != null && this.conf.iOSfix == true) {
				this.tb.cont.removeEventListener("touchend", this._doOnIOSFix, false);
				this._doOnIOSFix = null;
			}
		} else {
			window.detachEvent("onresize", this._doOnResize, false);
			this.edDoc.detachEvent("onclick", this._doOnClick);
			this.edDoc.detachEvent("onkeyup", this._doOnKeyUp);
			// editor's
			for (var q=0; q<that.conf.evs.length; q++) {
				fr.contentWindow.document.body.detachEvent("on"+that.conf.evs[q], that._ev);
			}
		}
		
		this._doOnAccess = null;
		this._doOnFocusChanged = null;
		
		// remove editor
		if (typeof(window.addEventListener) == "function") {
			this.editor.onload = null;
		} else {
			this.editor.onreadystatechange = null;
		}
		this.editor.parentNode.removeChild(this.editor);
		this.editor = null;
		this.edDoc = null;
		this.edWin = null;
		
		// unload cell
		this.cell._unload();
		this.cell = null;
		
		// extended toolbar
		this.tb = null;
		
		window.dhx4._eventable(this, "clear");
		
		this.cBlock.parentNode.removeChild(this.cBlock);
		this.cBlock = null;
		
		// clear container features
		this.base.className = String(this.base.className).replace(new RegExp("\\s{0,}dhxeditor_"+this.conf.skin),"");
		while (this.base.childNodes.length > 0) this.base.removeChild(this.base.childNodes[0]);
		this.base = null;
		
		this._doOnClick = null;
		this._doOnKeyUp = null;
		this._doOnMouseDown = null;
		
		this._ev = null;
		this._focus = null;
		this._prepareContent = null;
		this._doOnResize = null;
		this.setIconsPath = null;
		this.init = null;
		this.setSizes = null;
		this._runCommand = null;
		this.applyBold = null;
		this.applyItalic = null;
		this.applyUnderscore = null;
		this.clearFormatting = null;
		this._showInfo = null;
		this._getSelectionBounds = null;
		this.getContent = null;
		this.setContent = null;
		this.setContentHTML = null;
		this.setReadonly = null;
		this.isReadonly = null;
		this.unload = null;
		
		that = fr = null;
	}
	
	// load extended toolbar if any
	if (this.conf.toolbar == true && typeof(this.attachToolbar) == "function" && typeof(window.dhtmlXToolbarObject) == "function") {
		this.attachToolbar(this.conf.iconsPath);
		if (this.conf.iOSfix == true) {
			this._doOnIOSFix = function() { that.editor.contentWindow.focus(); }
			this.tb.cont.addEventListener("touchend", this._doOnIOSFix, false);
		}
	}
	this.setIconsPath = function(iconsPath) {
		this.conf.iconsPath = iconsPath;
	}
	
	// load content if any
	if (this.conf.content.length > 0) {
		this.setContent(this.conf.content);
		this.conf.content = "";
	} else if (this.conf.contentHTML.length > 0) {
		this.setContentHTML(this.conf.contentHTML);
		this.conf.contentHTML = "";
	}
	
	return this;
};

dhtmlXEditor.prototype.setSizes = function() {
	this.cell._setSize(0, 0, this.base.clientWidth, this.base.clientHeight);
	if (this.editor != null) {
		this.editor.style.left = "5px";
		this.editor.style.width = this.base.clientWidth-5+"px";
	}
};

dhtmlXEditor.prototype.setReadonly = function(mode) {
	this.conf.roMode = (mode===true);
	this._prepareContent(true, this.conf.roMode);
	this.cBlock.style.display = (this.conf.roMode?"":"none");
};

dhtmlXEditor.prototype.isReadonly = function(mode) {
	return (this.conf.roMode||false);
};

dhtmlXEditor.prototype.setSkin = function(skin) {
	this.base.className = String(this.base.className).replace(new RegExp("dhxeditor_"+this.conf.skin),"dhxeditor_"+skin);
	this.conf.skin = this.cell.conf.skin = skin;
	if (this.tb) {
		this.cell.detachToolbar(skin);
		this.tb = null;
		this.attachToolbar();
	}
	this.setSizes();
};

dhtmlXEditor.prototype._fontConf = function() {
	if (this.conf.skin == "") {
		var data = {family: this.conf.font.family, size: this.conf.font.size, color: this.conf.font.color};
	} else {
		var data = {family: "Roboto, Arial, Helvetica", size: "14px", color: "#404040"};
	}
	return window.dhx4.template("font-size: #size#; font-family: #family#; color: #color#;", data);
};
window.dhtmlXEditorCell = function(id, editor) {
	
	dhtmlXCellObject.apply(this, [id, "_editor"]);
	
	var that = this;
	this.editor = editor;
	
	this.conf.skin = this.editor.conf.skin;
	
	this.attachEvent("_onCellUnload", function(){
		
		// unload simple toolbar
		this._stbUnload();
		
		this.editor = null;
		that = null;
		
	});
	
	// simple toolbar init
	this._stbInit();
	
	return this;
};

dhtmlXEditorCell.prototype = new dhtmlXCellObject();

// simple toolbar
dhtmlXEditorCell.prototype._stbInit = function() {
	
	var that = this;
	
	var t = document.createElement("DIV");
	t.className = "dhx_cell_stb"+(dhx4.isIE6||dhx4.isIE7||dhx4.isIE8?"":" dhx_cell_stb_shadow");
	this.cell.insertBefore(t, this.cell.childNodes[this.conf.idx.cont]);
	
	t.onselectstart = function(e) {
		e = e||event;
		e.cancelBubble = true;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		return false;
	}
	
	var items = {
		bold: "applyBold",
		italic: "applyItalic",
		underline: "applyUnderscore",
		clearformat: "clearFormatting"
	};
	
	for (var k in items) {
		
		var a = document.createElement("A");
		a.href = "javascript:void(0);";
		a.tabIndex = -1;
		t.appendChild(a);
		
		a.onmousedown = a.onclick = function(e){
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
		
		var d = document.createElement("DIV");
		d.className = "dhx_cell_stb_button btn_"+k;
		d._actv = k.charAt(0);
		d._cmd = items[k];
		a.appendChild(d);
		
		d.onclick = function(e){
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
		d.onmousedown = function(e){
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			that.editor[this._cmd]();
			that.editor.callEvent("onToolbarClick",[this._actv]);
		}
		
		d = a = null;
	}
	
	t = null;
	
	this._stbUnload = function() {
		
		var t = this.cell.childNodes[this.conf.idx.stb];
		t.onselectstart = null;
		
		while (t.childNodes.length > 0) {
			t.lastChild.onmousedown = t.lastChild.onclick = null;
			t.lastChild.firstChild.onmousedown = t.lastChild.firstChild.onclick = null;
			t.lastChild.firstChild._actv = t.lastChild.firstChild._cmd = null;
			t.lastChild.removeChild(t.lastChild.firstChild);
			t.removeChild(t.lastChild);
		}
		t.parentNode.removeChild(t);
		t = that = null;
		
		this.conf.idx_data.stb = this.conf.ofs_nodes.t._getStbHeight = null;
		delete this.conf.ofs_nodes.t._getStbHeight
		delete this.conf.idx_data.stb;
		
		this._updateIdx();
		
	};
	
	this.conf.stb_visible = true;
	
	// include into content top offset calculation
	this.conf.ofs_nodes.t._getStbHeight = "func";
	
	// include into index
	this.conf.idx_data.stb = "dhx_cell_stb";
	this._updateIdx();
	
};

dhtmlXEditorCell.prototype._stbHide = function() {
	this.cell.childNodes[this.conf.idx.stb].style.display = "none";
	this.conf.stb_visible = false;
};

dhtmlXEditorCell.prototype._getStbHeight = function() {
	if (this.conf.stb_visible == true && this.conf.skin == "material") {
		if (this.conf.stb_height == null) {
			this.conf.stb_height = window.dhx4.readFromCss("dhxeditor_material stb_height_detect", "scrollHeight", "<div class='dhx_cell_editor'><div class='dhx_cell_stb'></div></div>");
		}
		return this.conf.stb_height;
	}
	return this.cell.childNodes[this.conf.idx.stb].offsetHeight;
};

dhtmlXCellObject.prototype.attachEditor = function(conf) {
	
	this.callEvent("_onBeforeContentAttach",["editor"]);
	
	var obj = document.createElement("DIV");
	obj.style.width = "100%";
	obj.style.height = "100%";
	obj.style.position = "relative";
	obj.style.overflow = "hidden";
	this._attachObject(obj);
	
	if (!(typeof(conf) == "object" && conf != null)) conf = {};
	conf.parent = obj;
	
	this.dataType = "editor";
	this.dataObj = new dhtmlXEditor(conf);
	
	obj = null;
	conf.parent = null;
	conf = null;
	
	// attach to portal extended logic
	if (typeof(window.dhtmlXPortalCell) == "function" && this instanceof window.dhtmlXPortalCell) {
		
		if (this.portal.conf.editor_ev == null) {
			
			var e1 = this.portal.attachEvent("onBeforeDrag", function(id) {
				if (this.cdata[id].dataType == "editor") {
					this.cdata[id].conf.editor_cont = this.cdata[id].dataObj.getContent();
				}
				return true;
			});
			
			var e2 = this.portal.attachEvent("onDrop", function(id) {
				if (this.cdata[id].dataType == "editor") {
					this.cdata[id].dataObj.setContent(this.cdata[id].conf.editor_cont);
					this.cdata[id].dataObj._prepareContent(true);
					this.cdata[id].conf.editor_cont = null;
				}
			});
			
			this.portal.conf.editor_ev = [e1, e2];
		}
		
		this.conf.editor_ev = this.attachEvent("_onBeforeContentDetach", function(){
			
			this.detachEvent(this.conf.editor_ev);
			this.conf.editor_ev = null;
			
			if (this instanceof window.dhtmlXPortalCell) {
				
				var ed = false;
				for (var a in this.portal.cdata) {
					if (this.portal.cdata[a] != this && this.portal.cdata[a].dataType == "editor") {
						ed = true; // portal still have attached editors
					}
				}
				// no more editors, clear events
				if (ed == false) {
					for (var q=0; q<this.portal.conf.editor_ev.length; q++) {
						this.portal.detachEvent(this.portal.conf.editor_ev[q]);
					}
					this.portal.conf.editor_ev = null;
				}
			}
		});
		
	}
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
	
};

