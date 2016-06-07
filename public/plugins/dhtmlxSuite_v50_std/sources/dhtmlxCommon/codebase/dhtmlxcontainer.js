/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXCellObject(idd, css) {
	
	this.cell = document.createElement("DIV");
	this.cell.className = "dhx_cell"+(css||"");
	
	this._idd = idd;
	this._isCell = true;
	
	this.conf = {
		borders: true,
		idx: {},
		css: css||"",
		idx_data: {
			cont: "dhx_cell_cont",
			pr1: "dhx_cell_progress_bar",
			pr2: "dhx_cell_progress_img",
			pr3: "dhx_cell_progress_svg",
			menu: "dhx_cell_menu",
			toolbar: "dhx_cell_toolbar",
			ribbon: "dhx_cell_ribbon",
			sb: "dhx_cell_statusbar",
			cover: "dhx_cell_cover"
		},
		ofs_nodes: { t:{}, b:{} }	// attached dataNodes (menu/toolbar/status), can be true, false;
						// in case of layout - "func" for header
	}
	
	this.dataNodes = {}; // menu/toolbar/status
	
	this.views = {};
	
	// cont
	var p = document.createElement("DIV");
	p.className = "dhx_cell_cont"+this.conf.css;
	this.cell.appendChild(p);
	p = null;
	
	this._updateIdx = function() {
		for (var a in this.conf.idx) {
			this.conf.idx[a] = null;
			delete this.conf.idx[a];
		}
		for (var q=0; q<this.cell.childNodes.length; q++) {
			var css = this.cell.childNodes[q].className;
			for (var a in this.conf.idx_data) {
				var r = new RegExp(this.conf.idx_data[a]);
				if (css.match(r) != null) this.conf.idx[a] = q;
			}
		}
		
		this.callEvent("_onIdxUpdated",[]);
	}
	
	this._adjustAttached = function() {
		// mtb/ribbon
		for (var a in this.dataNodes) {
			if (this.dataNodes[a] != null && typeof(this.dataNodes[a].setSizes) == "function") {
				this.dataNodes[a].setSizes();
			}
		}
		// attached node
		if (this.dataObj != null && typeof(this.dataObj.setSizes) == "function") {
			// check if dataObj is layuot which was attached separately
			if (this.dataType == "layout" && typeof(window.dhtmlXLayoutCell) == "function" && this instanceof window.dhtmlXLayoutCell && this.dataObj._getMainInst() != this.layout._getMainInst()) {
				this.dataObj.setSizes();
				return;
			}
			this.dataObj.setSizes.apply(this.dataObj, arguments);
		}
	}
	
	this._setSize = function(x, y, w, h, parentIdd, noCalcCont, actionType, customProps) {
		
		if (this.conf.size == null) this.conf.size = {};
		if (customProps == null) customProps = {}; // ability to use margin-left instead of left
		
		var styleProps = {left: "x", top: "y", width: "w", height: "h"};
		
		this.conf.size.x = x;
		this.conf.size.y = y;
		this.conf.size.w = Math.max(w,0);
		this.conf.size.h = Math.max(h,0);
		
		for (var a in styleProps) {
			var name = (customProps[a]||a);
			this.cell.style[name] = this.conf.size[styleProps[a]]+"px";
		}
		
		this.callEvent("_onSetSize",[]);
		
		if (noCalcCont !== true) {
			this._adjustCont(parentIdd, actionType);
		} else {
			this._adjustAttached(parentIdd);
		}
		
		this._adjustProgress();
	}
	
	this._adjustCont = function(parentIdd, actionType) {
		
		var t = this.cell.childNodes[this.conf.idx.cont];
		
		// attempt to adjust cell in collapsed layout
		if (typeof(window.dhtmlXLayoutCell) == "function" && this instanceof window.dhtmlXLayoutCell && this.conf.collapsed == true) {
			t.style.left = t.style.top = "0px";
			t.style.width = t.style.height = "200px";
			t = null;
			return;
		}
		
		// header height, menu, toolbar if any
		var ht = 0;
		for (var a in this.conf.ofs_nodes.t) {
			var k = this.conf.ofs_nodes.t[a];
			ht += (k=="func"?this[a]():(k==true?this.cell.childNodes[this.conf.idx[a]].offsetHeight:0));
		}
		
		// bottom offset (height reduce if status attached)
		var hb = 0;
		for (var a in this.conf.ofs_nodes.b) {
			var k = this.conf.ofs_nodes.b[a];
			hb += (k=="func"?this[a]():(k==true?this.cell.childNodes[this.conf.idx[a]].offsetHeight:0));
		}
		
		t.style.left = "0px";
		t.style.top = ht+"px";
		
		if (this.conf.cells_cont == null) {
			this.conf.cells_cont = {};
			t.style.width = this.cell.offsetWidth+"px";
			t.style.height = Math.max(this.cell.offsetHeight-ht-hb,0)+"px";
			this.conf.cells_cont.w = parseInt(t.style.width)-t.offsetWidth;
			this.conf.cells_cont.h = parseInt(t.style.height)-t.offsetHeight;
		}
		
		t.style.left = "0px";
		t.style.top = ht+"px";
		t.style.width = Math.max(this.cell.offsetWidth+this.conf.cells_cont.w,0)+"px";
		t.style.height = Math.max(this.conf.size.h-ht-hb+this.conf.cells_cont.h,0)+"px";
		t = null;
		
		// move out?
		this._adjustAttached(parentIdd); // for layout only 1 arg should be here
		
		// editor adjust cont here, browser check needed ( !!make some tests :)
		if (actionType == "expand" && this.dataType == "editor" && this.dataObj != null) {
			this.dataObj._prepareContent(true);
		}
	}
	
	this._mtbUpdBorder = function() {
		
		var t = ["menu","toolbar","ribbon"];
		for (var q=0; q<t.length; q++) {
			if (this.conf.idx[t[q]] != null) {
				var p = this.cell.childNodes[this.conf.idx[t[q]]];
				var c1 = "dhx_cell_"+t[q]+"_no_borders";
				var c2 = "dhx_cell_"+t[q]+"_def";
				p.className = p.className.replace(new RegExp(this.conf.borders?c1:c2), this.conf.borders?c2:c1);
				p = null;
			}
		}
	}
	
	this._resetSizeState = function() {
		// delete autosize settings, autocalc for cell_cont borders, paddings, useful on skinchange
		this.conf.cells_cont = null;
	}
	
	/* views */
	
	// test with url and getFrame()
	
	// current view
	this.conf.view = "def";
	
	// views loaded at least once
	this.conf.views_loaded = {};
	this.conf.views_loaded[this.conf.view] = true;
	
	// move current data to archive
	this._viewSave = function(name) {
		
		this.views[name] = {
			borders: this.conf.borders,
			ofs_nodes: {t:{},b:{}},
			url_data: this.conf.url_data,
			dataType: this.dataType,
			dataObj: this.dataObj,
			cellCont: [],
			dataNodes: {},
			dataNodesCont: {}
		};
		
		// attached cont
		var cellCont = this.cell.childNodes[this.conf.idx.cont];
		while (cellCont.childNodes.length > 0) {
			this.views[name].cellCont.push(cellCont.firstChild);
			cellCont.removeChild(cellCont.firstChild);
		}
		cellCont = null;
		
		this.dataType = null;
		this.dataObj = null;
		this.conf.url_data = null;
		
		// menu/toolbar/status
		for (var a in this.dataNodes) {
			
			for (var b in this.conf.ofs_nodes) {
				if (typeof(this.conf.ofs_nodes[b][a]) != "undefined") {
					this.views[name].ofs_nodes[b][a] = this.conf.ofs_nodes[b][a];
					this.conf.ofs_nodes[b][a] = null;
					delete this.conf.ofs_nodes[b][a];
				}
			}
			
			this.views[name].dataNodesCont[a] = this.cell.childNodes[this.conf.idx[a]];
			this.cell.removeChild(this.cell.childNodes[this.conf.idx[a]]);
			
			this.views[name].dataNodes[a] = this.dataNodes[a];
			this.dataNodes[a] = null;
			delete this.dataNodes[a];
			
			this._updateIdx();
		}
		
		this.callEvent("_onViewSave", [name]);
		
	}
	
	this._viewRestore = function(name) {
		
		if (this.views[name] == null) return;
		
		// content
		this.dataObj = this.views[name].dataObj;
		this.dataType = this.views[name].dataType;
		this.conf.url_data = this.views[name].url_data;
		for (var q=0; q<this.views[name].cellCont.length; q++) this.cell.childNodes[this.conf.idx.cont].appendChild(this.views[name].cellCont[q]);
		
		// data nodes (menu/toolbar/status)
		for (var a in this.views[name].dataNodes) {
			
			this.dataNodes[a] = this.views[name].dataNodes[a];
			// below is not very universal solution for extending
			if (a == "menu") this.cell.insertBefore(this.views[name].dataNodesCont[a], this.cell.childNodes[this.conf.idx.toolbar||this.conf.idx.cont]);
			if (a == "toolbar") this.cell.insertBefore(this.views[name].dataNodesCont[a], this.cell.childNodes[this.conf.idx.cont]);
			if (a == "ribbon") this.cell.insertBefore(this.views[name].dataNodesCont[a], this.cell.childNodes[this.conf.idx.cont]);
			if (a == "sb") this.cell.appendChild(this.views[name].dataNodesCont[a]);
			
			this._updateIdx();
		}
		
		// ofs_nodes
		for (var a in this.views[name].ofs_nodes) {
			for (var b in this.views[name].ofs_nodes[a]) this.conf.ofs_nodes[a][b] = this.views[name].ofs_nodes[a][b];
		}
		
		if (this.conf.borders != this.views[name].borders) {
			this[this.views[name].borders?"_showBorders":"_hideBorders"](true);
		}
		
		// reload url attache dwith POST
		if (this.dataType == "url" && this.conf.url_data != null && this.conf.url_data.ajax == false && this.conf.url_data.post_data != null) {
			this.reloadURL();
		}
		
		this.callEvent("_onViewRestore", [name]);
		
		this._viewDelete(name);
		
	}
	
	this._viewDelete = function(name) {
		
		if (this.views[name] == null) return;
		
		this.views[name].borders = null;
		
		for (var a in this.views[name].ofs_nodes) {
			for (var b in this.views[name].ofs_nodes[a]) this.views[name].ofs_nodes[a][b] = null;
			this.views[name].ofs_nodes[a] = null;
		}
		
		this.views[name].dataType = null;
		this.views[name].dataObj = null;
		this.views[name].url_data = null;
		
		for (var q=0; q<this.views[name].cellCont.length; q++) this.views[name].cellCont[q] = null;
		this.views[name].cellCont = null;
		
		for (var a in this.views[name].dataNodes) {
			this.views[name].dataNodes[a] = null;
			this.views[name].dataNodesCont[a] = null;
		}
		
		this.views[name].dataNodes = this.views[name].dataNodesCont = null;
		
		this.views[name] = null;
		delete this.views[name];
		
	}
	
	/* views end */
	
	window.dhx4._eventable(this);
	this._updateIdx();
	
	return this;
	
};

// views
dhtmlXCellObject.prototype.showView = function(name) {
	
	if (this.conf.view == name) return false; // alredy visible
	
	// save current view
	this._viewSave(this.conf.view);
	
	// restore requested view if exists
	this._viewRestore(name);
	
	// update cell rendering
	this._updateIdx();
	this._adjustCont();
	
	this.conf.view = name;
	
	var t = (typeof(this.conf.views_loaded[this.conf.view]) == "undefined");
	this.conf.views_loaded[this.conf.view] = true;
	
	return t;
	
};

dhtmlXCellObject.prototype.getViewName = function() {
	return this.conf.view;
};

dhtmlXCellObject.prototype.unloadView = function(name) {
	// hidden view, unload menu/toolbar/status, etc
	
	// unload actve view
	if (name == this.conf.view) {
		
		// set unloading flag to prevent some adjust operations
		var t = this.conf.unloading;
		this.conf.unloading = true;
		
		// remove content
		if (typeof(this.detachMenu) == "function") this.detachMenu();
		if (typeof(this.detachToolbar) == "function") this.detachToolbar();
		if (typeof(this.detachRibbon) == "function") this.detachRibbon();
		this.detachStatusBar();
		this._detachObject(null, true);
		
		// restore unloading flag
		this.conf.unloading = t;
		if (!this.conf.unloading) this._adjustCont(this._idd);
		
		return;
	}
	
	if (this.views[name] == null) return;
	
	var v = this.views[name];
	for (var a in v.dataNodes) {
		if (typeof(v.dataNodes[a].unload) == "function") v.dataNodes[a].unload();
		v.dataNodes[a] = null;
		v.dataNodesCont[a] = null;
	}
	if (v.dataType == "url") {
		if (v.cellCont != null && v.cellCont[0] != "null") {
			this._detachURLEvents(v.cellCont[0]);
		}
	} else if (v.dataObj != null) {
		if (typeof(v.dataObj.unload) == "function") {
			v.dataObj.unload();
		} else if (typeof(v.dataObj.destructor) == "function") {
			v.dataObj.destructor();
		}
		v.dataObj = null;
	}
	v = null;
	
	this._viewDelete(name);
	
	if (typeof(this.conf.views_loaded[name]) != "undefined") {
		delete this.conf.views_loaded[name];
	}
	
};


// id
dhtmlXCellObject.prototype.getId = function() {
	return this._idd;
};

// progress
dhtmlXCellObject.prototype.progressOn = function() {
	
	if (this.conf.progress == true) return;
	
	this.conf.progress = true;
	
	// cover
	var t1 = document.createElement("DIV");
	t1.className = this.conf.idx_data.pr1;
	
	// image/animation
	var t2 = document.createElement("DIV");
	if (this.conf.skin == "material" && (window.dhx4.isFF || window.dhx4.isChrome || window.dhx4.isOpera || window.dhx4.isEdge)) {
		t2.className = this.conf.idx_data.pr3;
		t2.innerHTML = '<svg class="dhx_cell_prsvg" viewBox="25 25 50 50"><circle class="dhx_cell_prcircle" cx="50" cy="50" r="20"/></svg>';
	} else {
		t2.className = this.conf.idx_data.pr2;
	}
	
	if (this.conf.idx.cover != null) {
		this.cell.insertBefore(t2, this.cell.childNodes[this.conf.idx.cover]);
	} else {
		this.cell.appendChild(t2);
	}
	this.cell.insertBefore(t1, t2);
	
	t1 = t2 = null;
	
	this._updateIdx();
	this._adjustProgress();
	
};

dhtmlXCellObject.prototype.progressOff = function() {
	
	if (this.conf.progress != true) return;
	
	for (var a in {pr3:3,pr2:2,pr1:1}) {
		var node = this.cell.childNodes[this.conf.idx[a]];
		if (node != null) node.parentNode.removeChild(node);
		node = null;
	}
	
	this.conf.progress = false;
	
	this._updateIdx();
};

dhtmlXCellObject.prototype._adjustProgress = function() {
	
	if (this.conf.idx.pr1 == null) return;
	
	if (!this.conf.pr) this.conf.pr = {};
	
	var p1 = this.cell.childNodes[this.conf.idx.pr1]; // half-transparent cover
	var p2 = this.cell.childNodes[this.conf.idx.pr2]||this.cell.childNodes[this.conf.idx.pr3]; // image/svg
	
	if (!this.conf.pr.ofs) {
		p2.style.width = p1.offsetWidth + "px";
		p2.style.height = p1.offsetHeight + "px";
		this.conf.pr.ofs = {
			w: p2.offsetWidth-p2.clientWidth,
			h: p2.offsetHeight-p2.clientHeight
		};
	}
	
	p2.style.width = p1.offsetWidth - this.conf.pr.ofs.w + "px";
	p2.style.height = p1.offsetHeight - this.conf.pr.ofs.h + "px";
	
	p1 = p2 = null;
};

// content cover
dhtmlXCellObject.prototype._showCellCover = function() {
	
	if (this.conf.cover == true) return;
	this.conf.cover = true;
	
	var t = document.createElement("DIV");
	t.className = this.conf.idx_data.cover;
	this.cell.appendChild(t);
	t = null;
	
	this._updateIdx();
};

dhtmlXCellObject.prototype._hideCellCover = function() {
	
	if (this.conf.cover != true) return;
	
	this.cell.removeChild(this.cell.childNodes[this.conf.idx.cover]);
	this._updateIdx();
	
	this.conf.cover = false;
	
};

// borders
dhtmlXCellObject.prototype._showBorders = function(noAdjust) {
	
	if (this.conf.borders) return;
	
	this.conf.borders = true;
	
	this.cell.childNodes[this.conf.idx.cont].className = "dhx_cell_cont"+this.conf.css;
	
	this.conf.cells_cont = null;
	this._mtbUpdBorder();
	
	this.callEvent("_onBorderChange",[true]);
	
	if (noAdjust !== true) this._adjustCont(this._idd);
	
};
	
dhtmlXCellObject.prototype._hideBorders = function(noAdjust) {
	
	if (!this.conf.borders) return;
	
	this.conf.borders = false;
	
	this.cell.childNodes[this.conf.idx.cont].className = "dhx_cell_cont"+this.conf.css+" dhx_cell_cont_no_borders";
	this.conf.cells_cont = null;
	this._mtbUpdBorder();
	
	this.callEvent("_onBorderChange",[false]);
	
	if (noAdjust !== true) this._adjustCont(this._idd);
	
};

// basic width/height
dhtmlXCellObject.prototype._getWidth = function() {
	return this.cell.offsetWidth;
};

dhtmlXCellObject.prototype._getHeight = function() {
	return this.cell.offsetHeight;
};


dhtmlXCellObject.prototype.showInnerScroll = function() {
	this.cell.childNodes[this.conf.idx.cont].style.overflow = "auto";
};

/* unload */
dhtmlXCellObject.prototype._unload = function() {
	
	this.conf.unloading = true;
	
	this.callEvent("_onCellUnload",[]);
	
	this.progressOff();
	
	// unload current view (remove attached content)
	this.unloadView(this.conf.view);
	
	this.dataNodes = null;
	
	this.cell.parentNode.removeChild(this.cell);
	this.cell = null;
	
	window.dhx4._eventable(this, "clear");
	
	// views
	for (var a in this.views) this.unloadView(a);
	
	this.conf = null;
	
	// others
	for (var a in this) this[a] = null; // no mercy
	
};


dhtmlXCellObject.prototype.attachObject = function(obj, adjust) {
	
	// adjust - for windows only
	if (window.dhx4.s2b(adjust) && !(typeof(window.dhtmlXWindowsCell) == "function" && this instanceof window.dhtmlXWindowsCell)) {
		adjust = false;
	}
	
	if (typeof(obj) == "string") obj = document.getElementById(obj);
	
	// already attached
	if (obj.parentNode == this.cell.childNodes[this.conf.idx.cont]) {
		obj = null;
		return;
	}
	
	if (adjust) {
		obj.style.display = "";
		var w = obj.offsetWidth;
		var h = obj.offsetHeight;
	}
	
	this._attachObject(obj);
	this.dataType = "obj";
	obj.style.display = "";
	obj = null;
	
	if (adjust) this._adjustByCont(w,h);
	
};

dhtmlXCellObject.prototype.appendObject = function(obj) {
	
	if (typeof(obj) == "string") obj = document.getElementById(obj);
	
	// already attached
	if (obj.parentNode == this.cell.childNodes[this.conf.idx.cont]) {
		obj = null;
		return;
	}
	
	
	if (!this.conf.append_mode) {
		this.cell.childNodes[this.conf.idx.cont].style.overflow = "auto";
		this.conf.append_mode = true;
	}
	
	this._attachObject(obj, null, null, true);
	this.dataType = "obj";
	obj.style.display = "";
	obj = null;
	
};

dhtmlXCellObject.prototype.detachObject = function(remove, moveTo) {
	this._detachObject(null, remove, moveTo);
};

dhtmlXCellObject.prototype.getAttachedStatusBar = function() {
	return this.dataNodes.sb;
};
dhtmlXCellObject.prototype.getAttachedObject = function() {
	if (this.dataType == "obj" || this.dataType == "url" || this.dataType == "url-ajax") {
		return this.cell.childNodes[this.conf.idx.cont].firstChild;
	} else {
		return this.dataObj;
	}
};

dhtmlXCellObject.prototype.attachURL = function(url, useAjax, postData) {
	
	// prepare POST if any, postData should be true or {} otherwise GET
	if (postData == true) postData = {};
	var postReq = (typeof(postData) != "undefined" && postData != false && postData != null);
	
	if (this.conf.url_data == null) this.conf.url_data = {};
	this.conf.url_data.url = url;
	this.conf.url_data.ajax = (useAjax == true);
	this.conf.url_data.post_data = (postData==true?{}:(postData||null)); // true or object
	
	if (this.conf.url_data.xml_doc != null) {
		try {this.conf.url_data.xml_doc.xmlDoc.abort();}catch(e){};
		this.conf.url_data.xml_doc.xmlDoc = null;
		this.conf.url_data.xml_doc = null;
	}
	
	if (useAjax == true) {
		
		var t = this;
		if (postReq) {
			var params = "";
			for (var a in postData) params += "&"+encodeURIComponent(a)+"="+encodeURIComponent(postData[a]);

			this.conf.url_data.xml_doc = dhx4.ajax.post(url, params, function(r){
				if (t.attachHTMLString != null && typeof(r.xmlDoc.responseText) == "string") {
					t.attachHTMLString("<div style='position:relative;width:100%;height:100%;overflow:auto;'>"+r.xmlDoc.responseText+"</div>");
					if (typeof(t._doOnFrameContentLoaded) == "function") t._doOnFrameContentLoaded();
					t.dataType = "url-ajax";
				}
				t = r = null;
			});
		} else {
			this.conf.url_data.xml_doc = dhx4.ajax.get(url, function(r){
				if (t.attachHTMLString != null && typeof(r.xmlDoc.responseText) == "string") {
					t.attachHTMLString("<div style='position:relative;width:100%;height:100%;overflow:auto;'>"+r.xmlDoc.responseText+"</div>");
					if (typeof(t._doOnFrameContentLoaded) == "function") t._doOnFrameContentLoaded();
					t.dataType = "url-ajax";
				}
				t = r = null;
			});
		}
		
	} else {
		if (this.dataType == "url") {
			var fr = this.getFrame();
		} else {
			var fr = document.createElement("IFRAME");
			fr.frameBorder = 0;
			fr.border = 0;
			fr.style.width = "100%";
			fr.style.height = "100%";
			fr.style.position = "relative";
			this._attachObject(fr);
			this.dataType = "url";
			this._attachURLEvents();
		}
		if (postReq) {
			var firstLoad = (typeof(this.conf.url_data.post_ifr) == "undefined");
			this.conf.url_data.post_ifr = true; // load later
			if (firstLoad) this._attachURLEvents();
			fr.src = "about:blank";
		} else {
			fr.src = url+window.dhx4.ajax._dhxr(url);
		}
		fr = null;
	}
	
	fr = null;
};

dhtmlXCellObject.prototype.reloadURL = function() {
	if (!(this.dataType == "url" || this.dataType == "url-ajax")) return;
	if (this.conf.url_data == null) return;
	this.attachURL(this.conf.url_data.url, this.conf.url_data.ajax, this.conf.url_data.post_data);
};

dhtmlXCellObject.prototype.attachHTMLString = function(str) {
	this._attachObject(null, null, str);
	// esec script
	var z = str.match(/<script[^>]*>[^\f]*?<\/script>/g)||[];
	for (var i=0; i<z.length; i++) {
		var s = z[i].replace(/<([\/]{0,1})script[^>]*>/gi,"");
		if (s) {
			if (window.execScript) window.execScript(s); else window.eval(s);
		}
	}
};

dhtmlXCellObject.prototype.attachScheduler = function(day, mode, cont_id, scheduler) {
	
	scheduler = scheduler || window.scheduler;
	
	var ready = false;
	if (cont_id) {
		var obj = document.getElementById(cont_id);
		if (obj) ready = true;
	}
	if (!ready) {
		var tabs = cont_id || '<div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div><div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div><div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>';
		var obj = document.createElement("DIV");
		obj.id = "dhxSchedObj_"+new Date().getTime();
		obj.style.width = "100%";
		obj.style.height = "100%";
		obj.style.position = "relative";
		obj.style.overflow = "hidden";
		obj.className = "dhx_cal_container";
		obj.innerHTML = '<div class="dhx_cal_navline"><div class="dhx_cal_prev_button">&nbsp;</div><div class="dhx_cal_next_button">&nbsp;</div><div class="dhx_cal_today_button"></div><div class="dhx_cal_date"></div>'+tabs+'</div><div class="dhx_cal_header"></div><div class="dhx_cal_data"></div>';
	}
	
	this._attachObject(obj);
	
	this.dataType = "scheduler";
	this.dataObj = scheduler;
	this.dataObj.setSizes = function(){
		this.update_view();
	}
	
	scheduler.init(obj.id, day, mode);
	
	obj = null;
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
};

dhtmlXCellObject.prototype.attachMap = function(opts) {
	
	var obj = document.createElement("DIV");
	obj.style.width = "100%";
	obj.style.height = "100%";
	obj.style.position = "relative";
	obj.style.overflow = "hidden";
	this._attachObject(obj);
	
	if (!opts) opts = {center: new google.maps.LatLng(40.719837,-73.992348), zoom: 11, mapTypeId: google.maps.MapTypeId.ROADMAP};
	
	this.dataType = "maps";
	this.dataObj = new google.maps.Map(obj, opts);
	
	this.dataObj.setSizes = function() {
		google.maps.event.trigger(this, "resize");
	}
	
	obj = null;
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
	
};

// status bar
dhtmlXCellObject.prototype._createNode_sb = function(obj, type, htmlString, append, node) {
	// type -> (object) conf={text:string,height:number}
	if (typeof(node) != "undefined") {
		obj = node;
	} else {
		var conf = type||{};
		var text = (typeof(conf.text)=="string" && conf.text.length > 0 ? conf.text : "&nbsp;");
		var h = (typeof(conf.height) == "number" ? conf.height : false);
		var obj = document.createElement("DIV");
		
		obj.className = "dhx_cell_statusbar_def";
		obj.innerHTML = "<div class='"+(conf.paging==true?"dhx_cell_statusbar_paging":"dhx_cell_statusbar_text")+"'>"+text+"</div>";
		
		// height, optional
		if (h != false) obj.firstChild.style.height = obj.firstChild.style.lineHeight = h+"px";
	}
	
	// before progress or last
	if (this.conf.idx.pr1 != null) {
		this.cell.insertBefore(obj, this.cell.childNodes[this.conf.idx.pr1]);
	} else {
		this.cell.appendChild(obj);
	}
	
	this.conf.ofs_nodes.b.sb = true;
	this._updateIdx();
	this._adjustCont(this._idd);
	
	return obj;
};

dhtmlXCellObject.prototype.attachStatusBar = function(conf) { // args-optinal, new in version
	
	if (this.dataNodes.sb) return;  // return this.dataNodes.sb?
	
	if (conf != null && window.dhx4.s2b(conf.paging) == true) conf.height = null; // will set by css
	
	if (this.conf.skin == "dhx_skyblue" && typeof(window.dhtmlXWindowsCell) == "function" && this instanceof window.dhtmlXWindowsCell) {
		 this.cell.childNodes[this.conf.idx.cont].className += " dhx_cell_statusbar_attached";
	}
	this.dataNodes.sb = this._attachObject("sb", conf);
	
	this.dataNodes.sb.setText = function(text) { this.childNodes[0].innerHTML = text; }
	this.dataNodes.sb.getText = function() { return this.childNodes[0].innerHTML; }
	this.dataNodes.sb.onselectstart = function(e) { return false; }
	
	return this.dataNodes.sb;
	
};

dhtmlXCellObject.prototype.detachStatusBar = function() {
	
	if (!this.dataNodes.sb) return;
	
	if (this.conf.skin == "dhx_skyblue"  && typeof(window.dhtmlXWindowsCell)== "function" && this instanceof window.dhtmlXWindowsCell) {
		 this.cell.childNodes[this.conf.idx.cont].className = this.cell.childNodes[this.conf.idx.cont].className.replace(/\s{0,}dhx_cell_statusbar_attached/,"");
	}
	
	this.dataNodes.sb.setText = this.dataNodes.sb.getText = this.dataNodes.sb.onselectstart = null;
	this.dataNodes.sb = null;
	delete this.dataNodes.sb;
	
	this._detachObject("sb");
	
};

dhtmlXCellObject.prototype.showStatusBar = function() {
	this._mtbShowHide("sb", "");
};

dhtmlXCellObject.prototype.hideStatusBar = function() {
	this._mtbShowHide("sb", "none");
};

dhtmlXCellObject.prototype._mtbShowHide = function(name, disp) {
	if (!this.dataNodes[name]) return;
	this.cell.childNodes[this.conf.idx[name]].style.display = disp;
	this._adjustCont();
};


/* private logic */

// !!! fix
dhtmlXCellObject.prototype.getFrame = dhtmlXCellObject.prototype._getFrame = function() { // _getFrame deprecated, use getFrame
	if (this.dataType != "url") return null;
	return this.cell.childNodes[this.conf.idx.cont].firstChild;
};

dhtmlXCellObject.prototype._attachURLEvents = function() {
	
	if (this.dataType != "url") return;
	
	var t = this;
	var cId = this._idd;
	var fr = this.cell.childNodes[this.conf.idx.cont].firstChild;
	
	if (typeof(this._doOnFrameMouseDown) != "function") {
		this._doOnFrameMouseDown = function(e) {
			// console.log("frame mouse down"); // needed for windows to activate window
			t.callEvent("_onContentMouseDown", [cId,e||event]);
		}
	}
	
	if (typeof(window.addEventListener) == "function") {
		fr.onload = function() {
			try { if (typeof(t._doOnFrameMouseDown) == "function") this.contentWindow.document.body.addEventListener("mousedown", t._doOnFrameMouseDown, false); } catch(e) {};
			try { if (typeof(t._doOnFrameContentLoaded) == "function") t._doOnFrameContentLoaded(); } catch(e) {};
		}
	} else {
		// ie8-
		fr.onreadystatechange = function(a) {
			if (this.readyState == "complete") {
				try { if (typeof(t._doOnFrameMouseDown) == "function") this.contentWindow.document.body.attachEvent("onmousedown", t._doOnFrameMouseDown); } catch(e) {};
				try { if (typeof(t._doOnFrameContentLoaded) == "function") t._doOnFrameContentLoaded(); } catch(e) {};
			}
		}
	}
	//fr = null;
};


dhtmlXCellObject.prototype._doOnFrameContentLoaded = function() {
	if (this.conf.url_data.post_ifr == true) {
		var d = this.getFrame().contentWindow.document;
		var f = d.createElement("FORM");
		f.method = "POST";
		f.action = this.conf.url_data.url;
		d.body.appendChild(f);
		var postData = {};
		if (window.dhx4.ajax.cache != true) postData["dhxr"+new Date().getTime()] = "1";
		for (var a in this.conf.url_data.post_data) postData[a] = this.conf.url_data.post_data[a];
		for (var a in postData) {
			var k = d.createElement("INPUT");
			k.type = "hidden";
			k.name = a;
			k.value = postData[a];
			f.appendChild(k);
			k = null;
		}
		this.conf.url_data.post_ifr = false;
		f.submit();
	} else {
		this.callEvent("_onContentLoaded", [this._idd]);
	}
};

dhtmlXCellObject.prototype._detachURLEvents = function(fr) {
	if (fr == null) {
		if (this.dataType != "url") return;
		fr = this.cell.childNodes[this.conf.idx.cont].firstChild;
	}
	if (typeof(window.addEventListener) == "function") {
		fr.onload = null;
		try { fr.contentWindow.document.body.removeEventListener("mousedown", this._doOnFrameMouseDown, false); } catch(e) {/* console.log("error: url detach mousedown event fail"); */};
	} else {
		fr.onreadystatechange = null;
		try { fr.contentWindow.document.body.detachEvent("onmousedown", this._doOnFrameMouseDown); } catch(e) { };
	}
	fr = null;
};




dhtmlXCellObject.prototype._attachObject = function(obj, type, htmlString, append, node) {
	
	if (typeof(obj) == "string" && {menu:1,toolbar:1,ribbon:1,sb:1}[obj] == 1) {
		return this["_createNode_"+obj].apply(this, arguments);
	}
	
	if (append != true) this._detachObject(null, true, null);
	
	if (typeof(htmlString) == "string") {
		this.cell.childNodes[this.conf.idx.cont].innerHTML = htmlString;
	} else {
		this.cell.childNodes[this.conf.idx.cont].appendChild(obj);
	}
	
	obj = null;
};
	
dhtmlXCellObject.prototype._detachObject = function(obj, remove, moveTo) {
	
	this.callEvent("_onBeforeContentDetach",[]);
	
	if (obj == "menu" || obj == "toolbar" || obj == "ribbon" || obj == "sb") {
		
		var p = this.cell.childNodes[this.conf.idx[obj]];
		p.parentNode.removeChild(p);
		p = null;
		
		this.conf.ofs_nodes[obj=="sb"?"b":"t"][obj] = false;
		
		this._updateIdx();
		if (!this.conf.unloading) this._adjustCont(this._idd);
		
		return;
	}
	
	if (remove == true) {
		moveTo = false;
	} else {
		if (typeof(moveTo) == "undefined") {
			moveTo = document.body;
		} else {
			if (typeof(moveTo) == "string") moveTo = document.getElementById(moveTo);
		}
	}
	
	// clear obj
	if (moveTo === false) {
		// cancel ajax-request if unloading
		if (this.conf.unloading == true && String(this.dataType).match(/ajax/) != null) {
			if (this.conf.url_data != null && this.conf.url_data.xml_doc != null) {
				try {this.conf.url_data.xml_doc.xmlDoc.abort();}catch(e){};
				this.conf.url_data.xml_doc.xmlDoc = null;
				this.conf.url_data.xml_doc = null;
			}
		}
		//
		if (this.dataType == "url") {
			this._detachURLEvents();
		} else if (this.dataObj != null) {
			if (typeof(this.dataObj.unload) == "function") {
				this.dataObj.unload();
			} else if (typeof(this.dataObj.destructor) == "function") {
				this.dataObj.destructor(); // at least for grid
			}
		}
	}
	
	// clear cell cont
	var p = this.cell.childNodes[this.conf.idx.cont];
	while (p.childNodes.length > 0) {
		if (moveTo === false) {
			p.removeChild(p.lastChild);
		} else {
			p.firstChild.style.display = "none"; // replace with/add - visibility:hidden?
			moveTo.appendChild(p.firstChild);
		}
	}
	
	if (this.conf.append_mode) {
		p.style.overflow = "";
		this.conf.append_mode = false;
	}
	
	var resetHdrBrd = (this.dataType == "tabbar");
	
	this.dataObj = null;
	this.dataType = null;
	
	moveTo = p = null;
	
	if (this.conf.unloading != true && resetHdrBrd) {
		this.showHeader(true);
		this._showBorders();
	}
	
};

// for dock/undock
dhtmlXCellObject.prototype._attachFromCell = function(cell) {
	
	// clear existing
	this.detachObject(true);
	
	var mode = "layout";
	if (typeof(window.dhtmlXWindowsCell) == "function" && this instanceof window.dhtmlXWindowsCell) {
		mode = "window";
	}
	
	// check opacity:
	// 1) detach from window cell, opacity set to 0.4
	if (typeof(window.dhtmlXWindowsCell) == "function" && cell instanceof window.dhtmlXWindowsCell && cell.wins.w[cell._idd].conf.parked == true) {
		cell.wins._winCellSetOpacity(cell._idd, "open", false);
	}
	// 2) acc-cell collapsed
	if (typeof(window.dhtmlXAccordionCell) == "function" && cell instanceof window.dhtmlXAccordionCell && cell.conf.opened == false) {
		cell._cellSetOpacity("open", false);
	}
	
	// menu, toolbar, status
	for (var a in cell.dataNodes) {
		
		this._attachObject(a, null, null, null, cell.cell.childNodes[cell.conf.idx[a]]);
		this.dataNodes[a] = cell.dataNodes[a];
		
		cell.dataNodes[a] = null;
		cell.conf.ofs_nodes[a=="sb"?"b":"t"][a] = false;
		cell._updateIdx();
		
	}
	
	this._mtbUpdBorder();
	
	if (cell.dataType != null && cell.dataObj != null) {
		this.dataType = cell.dataType;
		this.dataObj = cell.dataObj;
		while (cell.cell.childNodes[cell.conf.idx.cont].childNodes.length > 0) {
			this.cell.childNodes[this.conf.idx.cont].appendChild(cell.cell.childNodes[cell.conf.idx.cont].firstChild);
		}
		cell.dataType = null;
		cell.dataObj = null;
		
		// fixes
		if (this.dataType == "grid") {
			if (mode == "window" && this.conf.skin == "dhx_skyblue") {
				this.dataObj.entBox.style.border = "1px solid #a4bed4";
				this.dataObj._sizeFix = 0;
			} else {
				this.dataObj.entBox.style.border = "0px solid white";
				this.dataObj._sizeFix = 2;
			}
		}
	} else {
		// for attached urls and objects simple move them
		while (cell.cell.childNodes[cell.conf.idx.cont].childNodes.length > 0) {
			this.cell.childNodes[this.conf.idx.cont].appendChild(cell.cell.childNodes[cell.conf.idx.cont].firstChild);
		}
	}
	
	this.conf.view = cell.conf.view;
	cell.conf.view = "def";
	for (var a in cell.views) {
		this.views[a] = cell.views[a];
		cell.views[a] = null;
		delete cell.views[a];
	}
	
	cell._updateIdx();
	cell._adjustCont();
	
	this._updateIdx();
	this._adjustCont();
	
	// save progress state
	if (cell.conf.progress == true) {
		cell.progressOff();
		this.progressOn();
	} else {
		this.progressOff();
	}
	
	// check opacity, set opacity to 0.4
	// 1) attach to window cell, window parked
	if (mode == "window" && this.wins.w[this._idd].conf.parked) {
		this.wins._winCellSetOpacity(this._idd, "close", false);
	}
	
};

/*

 +--------------------------------------------------------+
 |  base                                                  |
 |                                                        |
 |   +-----------------------------------------------+    |
 |   |  header                                       |    |
 |   +-----------------------------------------------+    |
 |                                                        |
 |   +-----------------------------------------------+    |
 |   |  mtb                                          |    |
 |   +-----------------------------------------------+    |
 |                                                        |
 |   +-----------------------------------------------+    |
 |   |  cont                                         |    |
 |   |                                               |    |
 |   |                                               |    |
 |   +-----------------------------------------------+    |
 |                                                        |
 |   +-----------------------------------------------+    |
 |   |  sb                                           |    |
 |   +-----------------------------------------------+    |
 |                                                        |
 |   +-----------------------------------------------+    |
 |   |  footer                                       |    |
 |   +-----------------------------------------------+    |
 |                                                        |
 +--------------------------------------------------------+
 
*/

function dhtmlXCellTop(base, offsets) {
	
	if (arguments.length == 0 || typeof(base) == "undefined") return;
	
	var that = this;
	
	this.dataNodes = {}; // menu/toolbar/ribbon/sb/hdr/ftr
	
	this.conf.ofs = {t:0, b:0, l:0, r:0}; // base outer offset (fullscreen margins)
	this.conf.ofs_nodes = {t:{}, b:{}}; // attached menu/toolbar/sb/hdr/ftr
	this.conf.progress = false;
	
	this.conf.fs_mode = false; // fullscreen mode
	this.conf.fs_tm = null; // fullscreen resize timeout
	this.conf.fs_resize = false; // fullscreen resize
	
	if (base == document.body) {
		
		this.conf.fs_mode = true;
		
		// fullscreen init
		this.base = base;
		
		// custom offset for body
		if (this.base == document.body) {
			var ofsDef = {
				dhx_skyblue: {t: 2, b: 2, l: 2, r: 2},
				dhx_web:     {t: 8, b: 8, l: 8, r: 8},
				dhx_terrace: {t: 9, b: 9, l: 8, r: 8},
				material:    {t: 9, b: 9, l: 8, r: 8}
			};
			this.conf.ofs = (ofsDef[this.conf.skin] != null ? ofsDef[this.conf.skin] : ofsDef.dhx_skyblue);
		}
		
	} else {
		this.base = (typeof(base)=="string"?document.getElementById(base):base);
	}
	
	this.base.className += " "+this.conf.css+"_base_"+this.conf.skin;
	
	this.cont = document.createElement("DIV");
	this.cont.className = this.conf.css+"_cont";
	this.base.appendChild(this.cont);
	
	// conf-offsets override (attachObject usualy)
	if (offsets != null) {
		this.setOffsets(offsets, false);
	} else if (this.base._ofs != null) {
		this.setOffsets(this.base._ofs, false);
		this.base._ofs = null;
		try {delete this.base._ofs;}catch(e){}; // IE6/IE7 fix
	}
	
	this._adjustCont = function() {
		
		var ofsYT = this.conf.ofs.t;
		for (var a in this.conf.ofs_nodes.t) ofsYT += (this.conf.ofs_nodes.t[a]==true ? this.dataNodes[a].offsetHeight:0);
		
		var ofsYB = this.conf.ofs.b;
		for (var a in this.conf.ofs_nodes.b) ofsYB += (this.conf.ofs_nodes.b[a]==true ? this.dataNodes[a].offsetHeight:0);
		
		this.cont.style.left = this.conf.ofs.l + "px";
		this.cont.style.width = this.base.clientWidth - this.conf.ofs.l - this.conf.ofs.r + "px";
		
		this.cont.style.top = ofsYT + "px";
		this.cont.style.height = this.base.clientHeight - ofsYT - ofsYB + "px";
		
	}
	
	this._setBaseSkin = function(skin) {
		this.base.className = this.base.className.replace(new RegExp(this.conf.css+"_base_"+this.conf.skin,"gi"), this.conf.css+"_base_"+skin);
	}
	
	this._initFSResize = function() {
		
		if (this.conf.fs_resize == true) return; // already inited
		
		this._doOnResizeStart = function() {
			window.clearTimeout(that.conf.fs_tm);
			that.conf.fs_tm = window.setTimeout(that._doOnResizeEnd, 200);
		}
		
		this._doOnResizeEnd = function() {
			that.setSizes();
		}
		
		if (typeof(window.addEventListener) == "function") {
			window.addEventListener("resize", this._doOnResizeStart, false);
		} else {
			window.attachEvent("onresize", this._doOnResizeStart);
		}
		
		this.conf.fs_resize = true;
	}
	
	// resize events
	if (this.conf.fs_mode == true) this._initFSResize();
	
	this._unloadTop = function() {
		
		this._mtbUnload();
		this.detachHeader();
		this.detachFooter();
		
		if (this.conf.fs_mode == true) {
			if (typeof(window.addEventListener) == "function") {
				window.removeEventListener("resize", this._doOnResizeStart, false);
			} else {
				window.detachEvent("onresize", this._doOnResizeStart);
			}
		}
		
		this.base.removeChild(this.cont);
		var r = new RegExp("\s{0,}"+this.conf.css+"_base_"+this.conf.skin, "gi");
		this.base.className = this.base.className.replace(r, "");
		this.cont = this.base = null;
		
		that = null;
	}
	
	base = null;
};

// outer offsets
dhtmlXCellTop.prototype.setOffsets = function(data, upd) { // set 'upd' to false to prevent adjusting (useful on init stage)
	var t = false;
	for (var a in data) {
		var k = a.charAt(0);
		if (typeof(this.conf.ofs[k]) != "undefined" && !isNaN(data[a])) {
			this.conf.ofs[k] = parseInt(data[a]);
			t = true;
		}
	}
	if (upd !== false && typeof(this.setSizes) == "function" && t == true) this.setSizes();
};


// top-level menu/toolbar/ribbon/status

// menu
dhtmlXCellTop.prototype.attachMenu = function(conf) {
	
	if (this.dataNodes.menu != null) return;
	
	this.dataNodes.menuObj = document.createElement("DIV");
	this.dataNodes.menuObj.className = "dhxcelltop_menu";
	
	this.base.insertBefore(this.dataNodes.menuObj, this.dataNodes.toolbarObj||this.dataNodes.ribbonObj||this.cont);
	
	if (typeof(conf) != "object" || conf == null) conf = {};
	conf.skin = this.conf.skin;
	conf.parent = this.dataNodes.menuObj;
	
	this.dataNodes.menu = new dhtmlXMenuObject(conf);
	
	this.dataNodes.menuEv = this.attachEvent("_onSetSizes", function(){
		if (this.dataNodes.menuObj.style.display == "none") return;
		if (this.conf.ofs_menu == null) {
			this.dataNodes.menuObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
			this.conf.ofs_menu = {w: this.dataNodes.menuObj.offsetWidth-parseInt(this.dataNodes.menuObj.style.width)};
		}
		this.dataNodes.menuObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.menuObj.style.marginTop = (this.dataNodes.haObj!=null?0:this.conf.ofs.t)+"px";
		this.dataNodes.menuObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r-this.conf.ofs_menu.w+"px";
		
	});
	
	this.conf.ofs_nodes.t.menuObj = true;
	
	this.setSizes();
	
	conf.parnt = null;
	conf = null;
	
	return this.dataNodes.menu;
};

dhtmlXCellTop.prototype.detachMenu = function() {
	
	if (this.dataNodes.menu == null) return;
	
	this.dataNodes.menu.unload();
	this.dataNodes.menu = null;
	
	this.dataNodes.menuObj.parentNode.removeChild(this.dataNodes.menuObj);
	this.dataNodes.menuObj = null;
	
	this.detachEvent(this.dataNodes.menuEv);
	this.dataNodes.menuEv = null;
	
	delete this.dataNodes.menu;
	delete this.dataNodes.menuObj;
	delete this.dataNodes.menuEv;
	
	this.conf.ofs_nodes.t.menuObj = false;
	
	if (!this.conf.unloading) this.setSizes();
};

// toolbar
dhtmlXCellTop.prototype.attachToolbar = function(conf) {
	
	if (!(this.dataNodes.ribbon == null && this.dataNodes.toolbar == null)) return;
	
	this.dataNodes.toolbarObj = document.createElement("DIV");
	this.dataNodes.toolbarObj.className = "dhxcelltop_toolbar";
	this.base.insertBefore(this.dataNodes.toolbarObj, this.cont);
	this.dataNodes.toolbarObj.appendChild(document.createElement("DIV"));
	
	if (typeof(conf) != "object" || conf == null) conf = {};
	conf.skin = this.conf.skin;
	conf.parent = this.dataNodes.toolbarObj.firstChild;
	
	this.dataNodes.toolbar = new dhtmlXToolbarObject(conf);
	
	this.dataNodes.toolbarEv = this.attachEvent("_onSetSizes", function() {
		if (this.dataNodes.toolbarObj.style.display == "none") return;
		this.dataNodes.toolbarObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.toolbarObj.style.marginTop = (this.dataNodes.haObj!=null||this.dataNodes.menuObj!=null?0:this.conf.ofs.t)+"px";
		this.dataNodes.toolbarObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
	});
	
	this.dataNodes.toolbar._masterCell = this;
	this.dataNodes.toolbar.attachEvent("_onIconSizeChange", function(){
		this._masterCell.setSizes();
	});
	
	this.conf.ofs_nodes.t.toolbarObj = true;
	
	this.setSizes();
	
	conf.parnt = null;
	conf = null;
	
	return this.dataNodes.toolbar;
};

dhtmlXCellTop.prototype.detachToolbar = function() {
	
	if (this.dataNodes.toolbar == null) return;
	
	this.dataNodes.toolbar._masterCell = null; // link to this
	this.dataNodes.toolbar.unload();
	this.dataNodes.toolbar = null;
	
	this.dataNodes.toolbarObj.parentNode.removeChild(this.dataNodes.toolbarObj);
	this.dataNodes.toolbarObj = null;
	
	this.detachEvent(this.dataNodes.toolbarEv);
	this.dataNodes.toolbarEv = null;
	
	this.conf.ofs_nodes.t.toolbarObj = false;
	
	delete this.dataNodes.toolbar;
	delete this.dataNodes.toolbarObj;
	delete this.dataNodes.toolbarEv;
	
	if (!this.conf.unloading) this.setSizes();
};

// ribbon
dhtmlXCellTop.prototype.attachRibbon = function(conf) {
	
	if (!(this.dataNodes.ribbon == null && this.dataNodes.toolbar == null)) return;
	
	this.dataNodes.ribbonObj = document.createElement("DIV");
	this.dataNodes.ribbonObj.className = "dhxcelltop_ribbon";
	this.base.insertBefore(this.dataNodes.ribbonObj, this.cont);
	this.dataNodes.ribbonObj.appendChild(document.createElement("DIV"));
	
	if (typeof(conf) != "object" || conf == null) conf = {};
	conf.skin = this.conf.skin;
	conf.parent = this.dataNodes.ribbonObj.firstChild;
	
	this.dataNodes.ribbon = new dhtmlXRibbon(conf);
	
	this.dataNodes.ribbonEv = this.attachEvent("_onSetSizes", function() {
		if (this.dataNodes.ribbonObj.style.display == "none") return;
		this.dataNodes.ribbonObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.ribbonObj.style.marginTop = (this.dataNodes.haObj!=null||this.dataNodes.menuObj!=null?0:this.conf.ofs.t)+"px";
		this.dataNodes.ribbonObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
		this.dataNodes.ribbon.setSizes();
	});
	
	this.conf.ofs_nodes.t.ribbonObj = true;
	
	var t = this;
	this.dataNodes.ribbon.attachEvent("_onHeightChanged", function(){
		t.setSizes();
	});
	
	this.setSizes();
	
	conf.parnt = null;
	conf = null;
	
	return this.dataNodes.ribbon;
};

dhtmlXCellTop.prototype.detachRibbon = function() {
	
	if (this.dataNodes.ribbon == null) return;
	
	this.dataNodes.ribbon.unload();
	this.dataNodes.ribbon = null;
	
	this.dataNodes.ribbonObj.parentNode.removeChild(this.dataNodes.ribbonObj);
	this.dataNodes.ribbonObj = null;
	
	this.detachEvent(this.dataNodes.ribbonEv);
	this.dataNodes.ribbonEv = null;
	
	this.conf.ofs_nodes.t.ribbonObj = false;
	
	delete this.dataNodes.ribbon;
	delete this.dataNodes.ribbonObj;
	delete this.dataNodes.ribbonEv;
	
	if (!this.conf.unloading) this.setSizes();
};


// status
dhtmlXCellTop.prototype.attachStatusBar = function(conf) { // arg-optional, new in version
	
	if (this.dataNodes.sbObj) return;
	
	if (typeof(conf) == "undefined") conf = {};
	
	this.dataNodes.sbObj = document.createElement("DIV");
	this.dataNodes.sbObj.className = "dhxcelltop_statusbar";
	
	if (this.cont.nextSibling != null) {
		this.base.insertBefore(this.dataNodes.sbObj, this.cont.nextSibling);
	} else {
		this.base.appendChild(this.dataNodes.sbObj);
	}
	
	this.dataNodes.sbObj.innerHTML = "<div class='dhxcont_statusbar'>"+(typeof(conf.text)=="string" && conf.text.length > 0 ? conf.text:"&nbsp;")+"</div>";
	if (typeof(conf.height) == "number") this.dataNodes.sbObj.firstChild.style.height = this.dataNodes.sbObj.firstChild.style.lineHeight = conf.height+"px";
	
	this.dataNodes.sbObj.setText = function(text) { this.childNodes[0].innerHTML = text; }
	this.dataNodes.sbObj.getText = function() { return this.childNodes[0].innerHTML; }
	this.dataNodes.sbObj.onselectstart = function(e) { return false; }
	
	
	this.dataNodes.sbEv = this.attachEvent("_onSetSizes", function(){
		if (this.dataNodes.sbObj.style.display == "none") return;
		this.dataNodes.sbObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.sbObj.style.bottom = (this.dataNodes.faObj != null?this.dataNodes.faObj.offsetHeight:0)+this.conf.ofs.t+"px";
		this.dataNodes.sbObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
	});
	
	this.conf.ofs_nodes.b.sbObj = true;
	
	this.setSizes();
	
	return this.dataNodes.sbObj;
	
};

dhtmlXCellTop.prototype.detachStatusBar = function() {
	
	if (!this.dataNodes.sbObj) return;
	
	this.dataNodes.sbObj.setText = this.dataNodes.sbObj.getText = this.dataNodes.sbObj.onselectstart = null;
	this.dataNodes.sbObj.parentNode.removeChild(this.dataNodes.sbObj);
	this.dataNodes.sbObj = null;
	
	this.detachEvent(this.dataNodes.sbEv);
	this.dataNodes.sbEv = null;
	
	this.conf.ofs_nodes.b.sbObj = false;
	
	delete this.dataNodes.sb;
	delete this.dataNodes.sbObj;
	delete this.dataNodes.sbEv;
	
	if (!this.conf.unloading) this.setSizes();
	
};

// show/hide
dhtmlXCellTop.prototype.showMenu = function() {
	this._mtbShowHide("menuObj", "");
};

dhtmlXCellTop.prototype.hideMenu = function() {
	this._mtbShowHide("menuObj", "none");
};

dhtmlXCellTop.prototype.showToolbar = function(){
	this._mtbShowHide("toolbarObj", "");
};

dhtmlXCellTop.prototype.hideToolbar = function(){
	this._mtbShowHide("toolbarObj", "none");
};

dhtmlXCellTop.prototype.showRibbon = function(){
	this._mtbShowHide("ribbonObj", "");
};

dhtmlXCellTop.prototype.hideRibbon = function(){
	this._mtbShowHide("ribbonObj", "none");
};

dhtmlXCellTop.prototype.showStatusBar = function() {
	this._mtbShowHide("sbObj", "");
};

dhtmlXCellTop.prototype.hideStatusBar = function(){
	this._mtbShowHide("sbObj", "none");
};

dhtmlXCellTop.prototype._mtbShowHide = function(name, disp) {
	if (this.dataNodes[name] == null) return;
	this.dataNodes[name].style.display = disp;
	this.setSizes();
};

dhtmlXCellTop.prototype._mtbUnload = function(name, disp) {
	this.detachMenu();
	this.detachToolbar();
	this.detachStatusBar();
	this.detachRibbon();
};

// getters
dhtmlXCellTop.prototype.getAttachedMenu = function() {
	return this.dataNodes.menu;
};
dhtmlXCellTop.prototype.getAttachedToolbar = function() {
	return this.dataNodes.toolbar;
};
dhtmlXCellTop.prototype.getAttachedRibbon = function() {
	return this.dataNodes.ribbon;
};
dhtmlXCellTop.prototype.getAttachedStatusBar = function() {
	return this.dataNodes.sbObj;
};

// top-level progress
dhtmlXCellTop.prototype.progressOn = function() {
	
	if (this.conf.progress) return;
	
	this.conf.progress = true;
	
	var t1 = document.createElement("DIV");
	t1.className = "dhxcelltop_progress";
	this.base.appendChild(t1);
	
	var t2 = document.createElement("DIV");
	if (this.conf.skin == "material" && (window.dhx4.isFF || window.dhx4.isChrome || window.dhx4.isOpera || window.dhx4.isEdge)) {
		t2.className = "dhxcelltop_progress_svg";
		t2.innerHTML = '<svg class="dhx_cell_prsvg" viewBox="25 25 50 50"><circle class="dhx_cell_prcircle" cx="50" cy="50" r="20"/></svg>';
	} else {
		var t2 = document.createElement("DIV");
		t2.className = "dhxcelltop_progress_img";
	}
	this.base.appendChild(t2);
	
	t1 = t2 = null;
	
};

dhtmlXCellTop.prototype.progressOff = function() {
	
	if (!this.conf.progress) return;
	
	var p = {dhxcelltop_progress: true, dhxcelltop_progress_img: true, dhxcelltop_progress_svg: true};
	for (var q=0; q<this.base.childNodes.length; q++) {
		if (typeof(this.base.childNodes[q].className) != "undefined" && p[this.base.childNodes[q].className] == true) {
			p[this.base.childNodes[q].className] = this.base.childNodes[q];
		}
	}
	
	for (var a in p) {
		if (p[a] != true) this.base.removeChild(p[a]);
		p[a] = null;
	}
	
	this.conf.progress = false;
	p = null;
	
};

// fullscreen header-footer

// top margin (2px for skyblue) for fullscreen will always adjusted automatcaly
// bottom margin - can be generated by user-content, write in documentstion
// same for footer

dhtmlXCellTop.prototype.attachHeader = function(obj, height) {

	if (this.dataNodes.haObj != null) return; // already attached
	
	if (typeof(obj) != "object") obj = document.getElementById(obj);
	
	this.dataNodes.haObj = document.createElement("DIV");
	this.dataNodes.haObj.className = "dhxcelltop_hdr";
	this.dataNodes.haObj.style.height = (height||obj.offsetHeight)+"px";
	
	this.base.insertBefore(this.dataNodes.haObj, this.dataNodes.menuObj||this.dataNodes.toolbarObj||this.cont);
	
	this.dataNodes.haObj.appendChild(obj);
	obj.style.visibility = "visible";
	obj = null;
	
	this.dataNodes.haEv = this.attachEvent("_onSetSizes", function(){
		this.dataNodes.haObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.haObj.style.marginTop = this.conf.ofs.t+"px";
		this.dataNodes.haObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
	});
	
	this.conf.ofs_nodes.t.haObj = true;
	
	this.setSizes();
	
};

dhtmlXCellTop.prototype.detachHeader = function() {
	
	if (!this.dataNodes.haObj) return;
	
	while (this.dataNodes.haObj.childNodes.length > 0) {
		this.dataNodes.haObj.lastChild.style.visibility = "hidden";
		document.body.appendChild(this.dataNodes.haObj.lastChild);
	}
	this.dataNodes.haObj.parentNode.removeChild(this.dataNodes.haObj);
	this.dataNodes.haObj = null;
	
	this.detachEvent(this.dataNodes.haEv);
	this.dataNodes.haEv = null;
	
	this.conf.ofs_nodes.t.haObj = false;
	
	delete this.dataNodes.haEv;
	delete this.dataNodes.haObj;
	
	if (!this.conf.unloading) this.setSizes();

};

dhtmlXCellTop.prototype.attachFooter = function(obj, height) {
	
	if (this.dataNodes.faObj != null) return;
	
	if (typeof(obj) != "object") obj = document.getElementById(obj);
	
	this.dataNodes.faObj = document.createElement("DIV");
	this.dataNodes.faObj.className = "dhxcelltop_ftr";
	this.dataNodes.faObj.style.height = (height||obj.offsetHeight)+"px";
	
	var p = (this.dataNodes.sbObj||this.cont);
	if (this.base.lastChild == p) {
		this.base.appendChild(this.dataNodes.faObj);
	} else {
		this.base.insertBefore(this.dataNodes.faObj, p.nextSibling);
	}
	
	this.dataNodes.faEv = this.attachEvent("_onSetSizes", function(){
		this.dataNodes.faObj.style.left = this.conf.ofs.l+"px";
		this.dataNodes.faObj.style.bottom = this.conf.ofs.b+"px";
		this.dataNodes.faObj.style.width = this.base.offsetWidth-this.conf.ofs.l-this.conf.ofs.r+"px";
	});
	
	this.dataNodes.faObj.appendChild(obj);
	obj.style.visibility = "visible";
	p = obj = null;
	
	this.conf.ofs_nodes.b.faObj = true;
	
	this.setSizes();
	
};

dhtmlXCellTop.prototype.detachFooter = function() {
	
	if (!this.dataNodes.faObj) return;
	
	while (this.dataNodes.faObj.childNodes.length > 0) {
		this.dataNodes.faObj.lastChild.style.visibility = "hidden";
		document.body.appendChild(this.dataNodes.faObj.lastChild);
	}
	this.dataNodes.faObj.parentNode.removeChild(this.dataNodes.faObj);
	this.dataNodes.faObj = null;
	
	this.detachEvent(this.dataNodes.faEv);
	this.dataNodes.faEv = null;
	
	this.conf.ofs_nodes.b.faObj = false;
	
	delete this.dataNodes.faEv;
	delete this.dataNodes.faObj;
	
	if (!this.conf.unloading) this.setSizes();
	
};

