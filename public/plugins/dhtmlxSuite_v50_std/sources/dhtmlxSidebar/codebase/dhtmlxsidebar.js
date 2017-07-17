/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXSideBar(conf) {
	
	var that = this;
	
	this.conf = {
		skin: (conf.skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxsidebar")||"material"),
		css: "dhxsidebar",
		width: conf.width||200,
		scroll_size: 12,
		scroll_mult: 20,
		close_button: false,
		icons_path: conf.icons_path||"",
		selected: null,
		// single cell mode, added in 4.3
		single_cell: (typeof(conf.single_cell)=="undefined"?false:window.dhx4.s2b(conf.single_cell)),
		cell: null,
		// cells header, added in 4.5
		header: window.dhx4.s2b(conf.header),
		// autohide side, added in 4.5
		autohide: window.dhx4.s2b(conf.autohide),
		animate_items: true
	};
	
	// template
	this.setTemplate(conf.template);
	
	// init base
	window.dhtmlXCellTop.apply(this, [conf.parent, (conf==null?null:conf.offsets)]);
	
	// common event system
	window.dhx4._eventable(this);
	
	if (window.navigator.msPointerEnabled == true) {
		this.conf.touch_ms = true;
		this.conf.touch_start = "MSPointerDown",
		this.conf.touch_end = "MSPointerUp"
	} else {
		this.conf.touch_ms = false;
		this.conf.touch_start = "touchstart",
		this.conf.touch_end = "touchend"
	}
	
	// init bars area
	this.side = document.createElement("DIV");
	this.side.className = "dhxsidebar_side dhxsidebar_tpl_"+this.conf.tpl_name;
	this.side.innerHTML = "<div class='dhxsidebar_side_items'></div>";
	this.cont.appendChild(this.side);
	
	
	// overflow arrows
	this.arw = document.createElement("DIV");
	this.arw.className = "dhxsidebar_arrows dhxsidebar_arrows_hidden";
	this.arw.innerHTML = "<div class='dhxsidebar_arrow dhxsidebar_arrow_left'><div class='dhxsidebar_arrow_image'></div></div>"+
				"<div class='dhxsidebar_arrow dhxsidebar_arrow_right'><div class='dhxsidebar_arrow_image'></div></div>";
	this.cont.appendChild(this.arw);
	
	// autohide mode
	if (this.conf.autohide == true) {
		
		this.hideSide();
		
		// for cells left border
		this.cont.className += " dhxsidebar_autohide";
		
		// hide on click
		this._doOnBodyClick = function() {
			if (that.conf.clear_click == true) {
				that.conf.clear_click = false;
				return;
			}
			that.hideSide();
		}
		this._doOnEscDown = function(e) {
			e = e||event;
			if (e.keyCode == 27) {
				that.conf.clear_click = false;
				that.hideSide();
			}
		}
		if (typeof(window.addEventListener) == "function") {
			window.addEventListener(this.conf.touch_start, this._doOnBodyClick, false);
			window.addEventListener("click", this._doOnBodyClick, false);
			window.addEventListener("keydown", this._doOnEscDown, false);
		} else {
			document.body.attachEvent("onclick", this._doOnBodyClick);
			document.body.attachEvent("onkeydown", this._doOnEscDown);
		}
		
	}
	
	window.setTimeout(function(){
		if (that != null && that.side != null) {
			that.side.firstChild.style.top = "0px";
		}
	},1);
	
	
	this._doOnArwClick = function(e) {
		
		e = e||event;
		
		if (e.type != "click" && e.preventDefault) {
			e.preventDefault(); // this will prevent touchmove and click events
		}
		e.cancelBubble = true;
		
		var t = e.target||e.srcElement;
		if (t.className.match(/dhxsidebar_arrow_image/) != null) t = t.parentNode;
		
		if (t.className.match(/dhxsidebar_arrow_left/) != null) {
			that._scrollSide(-that.conf.scroll_size);
		} else if (t.className.match(/dhxsidebar_arrow_right/) != null) {
			that._scrollSide(that.conf.scroll_size);
		}
		
		t = null;
	}
	
	if (typeof(window.addEventListener) == "function") {
		this.arw.addEventListener(this.conf.touch_start, this._doOnArwClick, false);
		this.arw.addEventListener("click", this._doOnArwClick, false);
	} else {
		this.arw.attachEvent("onclick", this._doOnArwClick);
	}
	
	// side click
	this._doOnSideClick = function(e) {
		e = e||event;
		
		var t = e.target||e.srcElement;
		var id = null;
		var b = false;
		
		that.conf.clear_click = true;
		
		if (e.type == "touchstart" || e.type == "pointerdown" || e.type == "MSPointerDown") {
			if (e.preventDefault) {
				e.preventDefault(); // this will prevent touchmove and click events
			}
			if (this.className.match(/dhxsidebar_touch/gi) == null) {
				if (e.type == "touchstart" || (e.type == "pointerdown" && e.pointerType == "touch")) {
					this.className += " dhxsidebar_touch";
				}
			}
		}
		
		while (t != null && id == null && e.type != "pointerdown" && e.type != "MSPointerDown") {
			if (typeof(t.className) != "undefined") {
				if (t.className.match(/^dhxsidebar_item/) != null && typeof(t._idd) != "undefined") {
					id = t._idd;
				} else if (t.className.match(/^dhxsidebar_bubble/) != null) {
					b = true;
					id = t.parentNode._idd;
				}
			}
			t = t.parentNode;
		}
		if (id != null) {
			if (b == false || (b == true && that.callEvent("onBubbleClick", [id, that.t[id].conf.bubble]) == true)) {
				that._setItemActive(id, true);
			}
		}
		t = null;
	}
	
	if (typeof(window.addEventListener) == "function") {
		this.side.addEventListener(this.conf.touch_start, this._doOnSideClick, false);
		this.side.addEventListener(this.conf.touch_end, this._doOnSideClick, false);
		this.side.addEventListener("mouseup", this._doOnSideClick, false);
	} else {
		this.side.attachEvent("onclick", this._doOnSideClick);
	}
	
	this.side.onmouseover = function() {
		this.className = this.className.replace(/\s*dhxsidebar_touch/gi,"");
	}
	
	// side scroll
	this._doOnSideScroll = function(e) {
		e = e||event;
		var y = (e.type=="mousewheel"?-e.wheelDelta:e.deltaY);
		that._scrollSide(y/Math.abs(y)*3);
	}
	
	this._scrollSide = function(dir) { // dir => -1/1
		var top = parseInt(this.side.firstChild.style.top||0)-dir*this.conf.scroll_mult;
		// first check down
		if (top + this.side.firstChild.offsetHeight < this.side.clientHeight) top = this.side.clientHeight - this.side.firstChild.offsetHeight;
		// also check top
		if (top > 0) top = 0;
		this.side.firstChild.style.top = top+"px";
	}
	
	if (typeof(window.addEventListener) == "function") {
		this.side.addEventListener("wheel", this._doOnSideScroll, false);
	} else {
		this.side.attachEvent("onmousewheel", this._doOnSideScroll);
	}
	
	// items
	this.t = {};
	this.s = {};
	
	this._adjustCell = function(id, force) {
		
		if (this.conf.single_cell != true && id == null) return;
		
		var x = (this.conf.autohide==true?0:this.conf.width);
		var w = this.cont.offsetWidth-x;
		
		var y = 0;
		var h = this.cont.offsetHeight;
		
		if (this.conf.single_cell == true) {
			if (force == true) this.conf.cell._setSize(x, y, w, h); // only call from setSizes
		} else {
			if (id != this.conf.selected) {
				y = -5000;
				this.t[id].cell.cell.style.visibility = "hidden";
				this.t[id].cell.cell.style.zIndex = 0;
			}
			this.t[id].cell._setSize(x, y, w, h);
		}
	}
	
	// transition support if any
	var k = window.dhx4.transDetect();
	
	this.conf.transProp = k.transProp;
	this.conf.transEv = k.transEv;
	this.conf.transValue = "all 0.1s";
	
	this._doOnTrEnd = function(e) {
		
		var id = this._idd; // this points to an item
		
		if (that.t[id] == null) return;
		
		var t = that.t[id];
		var actvId = t.conf.transActvId;
		
		if (t.conf.transMode == "hide") {
			
			if (t.conf.remove == true) {
				that._removeItem(id);
			} else {
				t.item.style[t.conf.transProp] = "";
				if (that.conf.single_cell != true) {
					t.cell.cell.style.visibility = "hidden";
					t.cell.cell.style.top = "-5000px";
				}
				t.conf.transActv = false;
			}
			
		} else if (t.conf.transMode == "show") {
			
			t.item.style[t.conf.transProp] = "";
			t.item.style.visibility = "visible";
			t.conf.transMode = null;
			t.conf.transActv = false;
			
		}
		
		if (actvId != null) {
			that._setItemActive(actvId);
		} else {
			that._checkHeight();
		}
		t = null;
		
	}
	
	// data loading
	this._initObj = function(data) {
		this.clearAll();
		if (data.items != null) this.addItem(data.items);
	}
	
	this._xmlToObj = function(data) {
		var items = [];
		var r = data.getElementsByTagName("sidebar");
		if (r != null && r[0] != null) {
			var t = r[0].getElementsByTagName("item");
			for (var q=0; q<t.length; q++) {
				var item = {};
				for (var w=0; w<t[q].attributes.length; w++) {
					item[t[q].attributes[w].nodeName] = t[q].attributes[w].nodeValue;
				}
				items.push(item);
			}
		}
		return {items:items};
	}
	
	dhx4._enableDataLoading(this, "_initObj", "_xmlToObj", "sidebar", {struct:true});
	
	this.unload = function() {
		
		this.conf.unloading = true;
		
		// scroll
		if (typeof(window.addEventListener) == "function") {
			this.side.removeEventListener("wheel", this._doOnSideScroll, false);
		} else {
			this.side.detachEvent("onmousewheel", this._doOnSideScroll);
		}
		
		// autohide
		if (this.conf.autohide == true) {
			if (typeof(window.addEventListener) == "function") {
				window.removeEventListener("click", this._doOnBodyClick, false);
				window.removeEventListener("keydown", this._doOnEscDown, false);
			} else {
				document.body.detachEvent("onclick", this._doOnBodyClick);
				document.body.detachEvent("onkeydown", this._doOnEscDown);
			}
		}
		
		// sudden unload while side opened
		if (typeof(this._sideCoverDetach) == "function") this._sideCoverDetach();
		
		// remove items and separators
		this.clearAll();
		this.s = this.t = null;
		
		// overflow arrows
		if (typeof(window.addEventListener) == "function") {
			this.arw.removeEventListener(this.conf.touch_start, this._doOnArwClick, false);
			this.arw.removeEventListener("click", this._doOnArwClick, false);
		} else {
			this.arw.detachEvent("onclick", this._doOnArwClick);
		}
		this.arw.parentNode.removeChild(this.arw);
		this.arw = null;
		
		// side
		if (typeof(window.addEventListener) == "function") {
			this.side.removeEventListener(this.conf.touch_start, this._doOnSideClick, false);
			this.side.removeEventListener(this.conf.touch_end, this._doOnSideClick, false);
			this.side.removeEventListener("click", this._doOnSideClick, false);
		} else {
			this.side.detachEvent("onclick", this._doOnSideClick);
		}
		this.side.onmouseover = null;
		this.side.parentNode.removeChild(this.side);
		this.side = null;
		
		// celltop
		this._unloadTop();
		
		// single cell
		if (this.conf.single_cell == true) {
			this.conf.cell._unload()
			this.conf.cell = null;
		}
		
		// events and dataloading
		window.dhx4._eventable(this, "clear");
		window.dhx4._enableDataLoading(this, null, null, null, "clear");
		
		// the rest
		for (var a in this) this[a] = null;
		
		that = null;
		
	}
	
	// init single cell if any
	if (this.conf.single_cell == true) {
		this.conf.cell = new dhtmlXSideBarCell("master", this);
		this.cont.appendChild(this.conf.cell.cell);
		//
		this._cells_native = this.cells;
		this.cells = function(id) {
			this.conf.cell._idd = id;
			return this.conf.cell;
		}
	}
	
	// adjust self
	this.setSizes();
	
	// init
	if (conf.items != null) {
		this._initObj(conf);
	} else if (conf.json != null) {
		this.loadStruct(conf.json, conf.onload);
	} else if (conf.xml != null) {
		this.loadStruct(conf.xml, conf.onload);
	}
	
	return this;
};

dhtmlXSideBar.prototype = new dhtmlXCellTop();


// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// add item

dhtmlXSideBar.prototype._addItem = function(conf) {
	
	var id = (typeof(conf.id)!="undefined"?conf.id:window.dhx4.newId());
	
	if (conf.type == "separator") {
		var t = document.createElement("DIV");
		t.className = "dhxsidebar_sep";
		this.side.firstChild.appendChild(t);
		this.s[id] = {sep: t};
		t = null;
		return;
	}
	
	conf.icons_path = this.conf.icons_path;
	
	var t = document.createElement("DIV");
	t.className = "dhxsidebar_item";
	t.innerHTML = window.dhx4.template(this.conf.tpl_str, conf);
	t._idd = id;
	this.side.firstChild.appendChild(t);
	
	t.ondragstart = function(){return false;}
	
	if (this.conf.single_cell == true) {
		var cell = this.conf.cell;
	} else {
		var cell = new dhtmlXSideBarCell(id, this);
		this.cont.appendChild(cell.cell);
	}
	
	this.t[id] = {
		item: t,
		cell: cell,
		init: conf,
		conf: {
			selected: false,
			visible: true,
			close: close
		}
	};
	
	// bubbles
	if (typeof(conf.bubble) != "undefined") {
		this._setItemBubble(id, conf.bubble);
	}
	
	// header text
	if (this.conf.header == true) cell.setHeaderText(window.dhx4.template(this.tpl_header, conf));
	
	cell = t = null;
	
	if (window.dhx4.s2b(conf.selected) == true) {
		this._setItemActive(id);
	} else {
		this._adjustCell(id);
	}
	
	this._checkHeight();
	
};

dhtmlXSideBar.prototype.addItem = function(items) {
	if (!(items instanceof Array)) items = [items];
	for (var q=0; q<items.length; q++) this._addItem(items[q]);
};

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// items opts

dhtmlXSideBar.prototype.items = dhtmlXSideBar.prototype.cells = function(id) {
	if (this.conf.single_cell == true) return this.conf.cell;
	if (this.t[id] != null) return this.t[id].cell;
	return null;
};

dhtmlXSideBar.prototype.forEachCell = dhtmlXSideBar.prototype.forEachItem = function(func) {
	if (this.conf.single_cell == true) {
		if (typeof(func) == "function") {
			func.apply(window, [this.conf.cell]);
		} else if (typeof(func) == "string" && typeof(window[func]) == "function") {
			window[func].apply(window, [this.conf.cell]);
		}
		return;
	}
	for (var a in this.t) {
		if (typeof(func) == "function") {
			func.apply(window, [this.t[a].cell]);
		} else if (typeof(func) == "string" && typeof(window[func]) == "function") {
			window[func].apply(window, [this.t[a].cell]);
		}
	}
};

dhtmlXSideBar.prototype.getAllItems = function() {
	var items = [];
	for (var a in this.t) items.push(a);
	return items;
};

dhtmlXSideBar.prototype.getNumberOfItems = function() {
	return this.getAllItems().length;
};

dhtmlXSideBar.prototype.clearAll = function() { // remove all items and separators
	for (var a in this.t) this._removeItem(a, false, true);
	for (var a in this.s) this.removeSep(a);
};

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// sizing

dhtmlXSideBar.prototype.setSizes = function() {
	this._adjustCont();
	this._adjustSide();
	this._checkHeight();
	this._adjustCell(this.conf.selected, true); // active cell
	this.callEvent("_onSetSizes", []);
};

dhtmlXSideBar.prototype.setSideWidth = function(w) {
	this.conf.width = w;
	this.setSizes();
};

dhtmlXSideBar.prototype._adjustSide = function() {
	
	if (this.conf.side_ofs == null) {
		this.conf.side_ofs = {};
		this.side.style.width = this.conf.width+"px";
		this.side.style.height = this.side.parentNode.offsetHeight+"px";
		this.conf.side_ofs.w = this.side.offsetWidth-parseInt(this.side.style.width);
		this.conf.side_ofs.h = this.side.offsetHeight-parseInt(this.side.style.height);
	}
	
	this.side.style.width = this.conf.width-this.conf.side_ofs.w+"px";
	this.side.style.height = this.side.parentNode.offsetHeight-this.arw.offsetHeight-this.conf.side_ofs.h+"px";
	
	this.arw.style.width = this.side.style.width;
	
	this._scrollSide(0); // fix top position
	
};

dhtmlXSideBar.prototype._checkHeight = function() {
	var arrowsHidden = (this.arw.className.match(/dhxsidebar_arrows_hidden/) != null);
	if (this.conf.side_hfix == null) {
		this.conf.side_hfix = this.side.offsetHeight - this.side.clientHeight;
	}
	if (this.side.firstChild.offsetHeight > this.side.parentNode.clientHeight-this.conf.side_hfix) {
		if (arrowsHidden == true) {
			this.arw.className = "dhxsidebar_arrows";
			this._adjustSide();
		}
	} else {
		if (arrowsHidden == false) {
			this.arw.className = "dhxsidebar_arrows dhxsidebar_arrows_hidden";
			this.side.firstChild.style.top = "0px";
			this._adjustSide();
		}
	}
};

dhtmlXSideBar.prototype.removeSep = function(id) {
	if (this.s[id] == null) return;
	this.side.firstChild.removeChild(this.s[id].sep);
	this.s[id].sep = null;
	this.s[id] = null;
	try { delete this.s[id]; } catch(e){};
};

// show/hide side, added in 4.5
dhtmlXSideBar.prototype.showSide = function() {
	if (this.conf.autohide != true) return;
	
	if (this.sideCover == null) this._sideCoverAttach();
	
	if (this.conf.animate_items == true) {
		
		var animate = function(item, tmTime, prop) {
			window.setTimeout(function(){
				item.style[prop] = "transform 0.3s";
				item.style.transform = "translate(0px,0px)";
				item = null;
			}, tmTime);
		};
		
		if (this.conf.transProp !== false) {
			var q = 100;
			for (var a in this.t) {
				this.t[a].item.style[this.conf.transProp] = "";
				this.t[a].item.style.transform = "translate(-"+(this.conf.width+20)+"px,0px)";
				animate(this.t[a].item, q+=50, this.conf.transProp);
			}
		}
		
	}
	
	var t = this;
	window.setTimeout(function(){
		t.arw.style.left = t.side.style.left = "0px";
		t.sideCover.className = "dhxsidebar_side_cover dhxsidebar_side_cover_actv";
		t = null;
	},50);
	
};

dhtmlXSideBar.prototype.hideSide = function(ef) {
	if (this.conf.autohide != true) return;
	
	this.arw.style.left = this.side.style.left = -this.conf.width-10+"px";
	
	if (this.sideCover != null) {
		if (this.conf.transProp !== false) {
			this.sideCover.className = "dhxsidebar_side_cover";
		} else {
			this._sideCoverDetach();
		}
	}
};

dhtmlXSideBar.prototype._sideCoverAttach = function() {
	
	var that = this;
	
	this.sideCover = document.createElement("DIV");
	this.sideCover.className = "dhxsidebar_side_cover";
	
	if (this.arw.nextSibling != null) {
		this.cont.insertBefore(this.sideCover, this.arw.nextSibling);
	} else {
		this.cont.appendChild(this.sideCover);
	}
	
	this._sideCoverOnTrEnd = function() {
		if (this.className.match(/dhxsidebar_side_cover_actv/) == null) {
			that._sideCoverDetach();
		}
	}
	
	this._sideCoverDetach = function() {
		if (this.sideCover == null) return;
		if (this.conf.transProp !== false) this.sideCover.removeEventListener(this.conf.transEv, this._sideCoverOnTrEnd, false);
		this.sideCover.parentNode.removeChild(this.sideCover);
		this.sideCover = null;
		that = null;
	}
	
	if (this.conf.transProp !== false) {
		this.sideCover.addEventListener(this.conf.transEv, this._sideCoverOnTrEnd, false);
	}
};

// selection
dhtmlXSideBar.prototype._setItemActive = function(id, callEvent) {
	
	if (this.conf.selected == id) {
		if (this.conf.autohide == true) this.hideSide();
		return;
	}
	
	if (typeof(callEvent) == "undefined") callEvent = false;
	
	if (callEvent == true && this.callEvent("onBeforeSelect", [id, this.conf.selected]) !== true) {
		return;
	}
	
	var lastSelected = null;
	if (this.conf.selected != null) {
		lastSelected = this.conf.selected;
		this._setItemInactive(this.conf.selected);
	}
	
	if (this.t[id] != null) {
		this.conf.selected = id;
		this.t[id].selected = true;
		this.t[id].item.className += " dhxsidebar_item_selected";
		if (this.conf.single_cell != true) {
			this.t[id].cell.cell.style.visibility = "visible";
			this.t[id].cell.cell.style.top = "0px";
			this.t[id].cell.cell.style.zIndex = 1;
		}
	} else {
		this.conf.selected = null;
	}
	
	this._adjustCell(id);
	
	if (callEvent == true) {
		this.callEvent("onSelect", [id, lastSelected]);
	}
	
	if (this.conf.autohide == true) {
		this.hideSide();
	}
	
};

dhtmlXSideBar.prototype._setItemInactive = function(id) {
	
	if (this.t[id] == null) return;
	
	this.t[id].selected = false;
	this.t[id].item.className = this.t[id].item.className.replace(/\s{0,}dhxsidebar_item_selected/gi,"");
	
	if (this.conf.single_cell != true) {
		this.t[id].cell.cell.style.visibility = "hidden";
		this.t[id].cell.cell.style.top = "-5000px";
		this.t[id].cell.cell.style.zIndex = 0;
	}
	
};

dhtmlXSideBar.prototype._isItemActive = function(id) {
	return (this.conf.selected == id);
};

dhtmlXSideBar.prototype._getNextVisible = function(id, getFirst) {
	return this._getNearVisible(id, getFirst, "next");
};

dhtmlXSideBar.prototype._getPrevVisible = function(id, getFirst) {
	return this._getNearVisible(id, getFirst, "previous");
};

dhtmlXSideBar.prototype._getFirstVisible = function() {
	return this._getNearVisible(null, false, "first");
};

dhtmlXSideBar.prototype._getNearVisible = function(id, getFirst, mode) {
	
	if (mode == "first") {
		var node = this.side.firstChild.firstChild; // 1st item
		mode = "next";
	} else {
		if (id == null || this.t[id] == null) return (getFirst?this._getFirstVisible():null);
		var node = this.t[id].item[mode+"Sibling"];
	}
	
	var itemId = null;
	
	while (node != null && itemId == null) {
		var k = node._idd;
		if (k != null && itemId == null && this.t[k].conf.visible) {
			itemId = k;
		} else {
			node = node[mode+"Sibling"];
		}
	}
	
	node = null;
	
	return itemId;
};

dhtmlXSideBar.prototype.goToNextItem = function(callEvent) {
	var id = this._getNextVisible(this.conf.selected, true);
	if (id != null) this._setItemActive(id, callEvent);
};

dhtmlXSideBar.prototype.goToPrevItem = function(callEvent) {
	var id = this._getPrevVisible(this.conf.selected, true);
	if (id != null) this._setItemActive(id, callEvent);
};

dhtmlXSideBar.prototype.getActiveItem = function() {
	return this.conf.selected;
};

// templates
dhtmlXSideBar.prototype.setTemplate = function(template, iconsPath) {
	// conf
	this.conf.tpl_name = (template!=null&&this.templates[template]!=null?template:"details");
	this.conf.tpl_str = this.templates[this.conf.tpl_name];
	// icons path if any
	if (iconsPath != null) this.conf.icons_path = iconsPath;
	// update loaded items
	for (var a in this.t) {
		this.t[a].init.icons_path = this.conf.icons_path;
		this.t[a].item.innerHTML = window.dhx4.template(this.conf.tpl_str, this.t[a].init);
	}
	// side area
	if (this.side != null) {
		this.side.className = "dhxsidebar_side dhxsidebar_tpl_"+this.conf.tpl_name;
	}
	if (this._scrollSide != null) {
		this._scrollSide(0); // fix top position
		this._checkHeight();
	}
};

dhtmlXSideBar.prototype.templates = {
	details:	"<img class='dhxsidebar_item_icon' src='#icons_path##icon#' border='0'><div class='dhxsidebar_item_text'>#text#</div>",
	tiles:		"<img class='dhxsidebar_item_icon' src='#icons_path##icon#' border='0'><div class='dhxsidebar_item_text'>#text#</div>",
	icons:		"<img class='dhxsidebar_item_icon' src='#icons_path##icon#' border='0'>",
	icons_text:	"<div class='dhxsidebar_item_icon'><img class='dhxsidebar_item_icon' src='#icons_path##icon#' border='0'></div><div class='dhxsidebar_item_text'>#text#</div>",
	text:		"<div class='dhxsidebar_item_text'>#text#</div>"
};

dhtmlXSideBar.prototype.tpl_bubble = "<div class='dhxsidebar_bubble'>#value#</div>";

dhtmlXSideBar.prototype.tpl_header = "#text#";
window.dhtmlXSideBarCell = function(id, sidebar) {
	
	dhtmlXCellObject.apply(this, [id, "_sidebar"]);
	
	var that = this;
	this.sidebar = sidebar;
	
	this.conf.skin = this.sidebar.conf.skin;
	
	// sidebar calls
	this.conf.sidebar_funcs = {
		show: "_showItem",
		hide: "_hideItem",
		isVisible: "_isItemVisible",
		setActive: "_setItemActive",
		isActive: "_isItemActive",
		setText: "_setItemText",
		getText: "_getItemText",
		remove: "_removeItem",
		setBubble: "_setItemBubble",
		getBubble: "_getItemBubble",
		clearBubble: "_clearItemBubble"
	};
	
	this._sidebarCall = function(name) {
		return function(){
			var t = [this._idd];
			for (var q=0; q<arguments.length; q++) t.push(arguments[q]);
			return this.sidebar[name].apply(this.sidebar, t);
		};
	};
	
	for (var a in this.conf.sidebar_funcs) {
		if (typeof(this[a]) != "function") this[a] = this._sidebarCall(this.conf.sidebar_funcs[a]);
	};
	
	// init header
	if (this.sidebar.conf.header == true) {
		this._initHeader();
		this.cell.childNodes[this.conf.idx.hdr].onclick = function(e) {
			e = e||event;
			var t = (e.target||e.srcElement);
			if (t.className.match(/dhx_cell_sidebar_hdr_icon/gi) != null) {
				that.sidebar.conf.clear_click = true;
				that.sidebar.showSide();
			}
			t = null;
		};
	}
	
	this.attachEvent("_onCellUnload", function(){
		
		// header
		if (this.conf.idx.hdr != null) {
			this.cell.childNodes[this.conf.idx.hdr].onclick = null;
		}
		
		this.sidebar = null;
		
		// sidebar calls
		for (var a in this.conf.sidebar_funcs) this[a] = this.conf.sidebar_funcs[a] = null;
		this.conf.sidebar_funcs = null;
		
		that = null;
	});
	
	this.attachEvent("_onContentLoaded", function() {
		this.sidebar.callEvent("onContentLoaded", arguments);
	});
	
	this.attachEvent("_onBeforeContentAttach", function(dataType) {
		if (dataType == "tabbar" || dataType == "layout" || dataType == "acc") {
			this._hideBorders();
		}
		if (dataType == "sidebar" && this.conf.skin != "dhx_skyblue") {
			this._hideBorders();
		}
		// clear top border for menu/toolbar/ribbon
		if ((this.conf.skin == "dhx_web" || this.conf.skin == "dhx_terrace") && (dataType == "menu" || dataType == "toolbar" || dataType == "ribbon")) {
			if (this.cell.className.match(/dhx_cell_cont_no_top/gi) == null) {
				this.cell.className += " dhx_cell_cont_no_top";
			}
		}
	});
	
};

dhtmlXSideBarCell.prototype = new dhtmlXCellObject();

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// item text

dhtmlXSideBar.prototype._setItemText = function(id, data) {
	if (this.t[id] != null) {
		for (var a in data) this.t[id].init[a] = data[a];
		this.t[id].init.icons_path = this.conf.icons_path;
		this.t[id].item.innerHTML = window.dhx4.template(this.conf.tpl_str, this.t[id].init);
		// bubble
		if (this.t[id].conf.bubble != null) {
			this.t[id].item.innerHTML += window.dhx4.template(this.tpl_bubble, {value: this.t[id].conf.bubble})
		}
		// header
		if (this.conf.header == true) {
			this.t[id].cell.setHeaderText(window.dhx4.template(this.tpl_header, this.t[id].init));
		}
	}
};

dhtmlXSideBar.prototype._getItemText = function(id) {
	var t = {};
	if (this.t[id] != null) {
		for (var a in this.t[id].init) t[a] = this.t[id].init[a];
	}
	return t;
}

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// remove item or separator

dhtmlXSideBar.prototype._removeItem = function(id, actvId, force) { // force w/o effect
	
	if (this.t[id] == null) return;
	
	if (force != true && this.t[id].conf.remove != true) {
		this.t[id].conf.remove = true;
		this._hideItem(id, actvId);
		return;
	}
	
	if (typeof(actvId) == "undefined") actvId = true;
	
	var next = this._getNextVisible(id);
	var prev = this._getPrevVisible(id);
	
	if (this.t[id].conf.transEv == true) {
		this.t[id].item.removeEventListener(this.conf.transEv, this._doOnTrEnd);
		this.t[id].conf.transEv = false;
	}
	
	if (this.conf.single_cell != true) {
		this.t[id].cell._unload();
		this.t[id].cell = null;
	}
	
	this.t[id].item.ondragstart = null;
	this.t[id].item.parentNode.removeChild(this.t[id].item);
	this.t[id].item = null;
	
	for (var a in this.t[id]) this.t[id][a] = null;
	this.t[id] = null;
	try { delete this.t[id]; } catch(e){};
	
	if (this.conf.selected == id && actvId != false) {
		this.conf.selected = null;
		var actvId = (actvId == true ? (next||prev||this._getFirstVisible()) : actvId);
		if (actvId != null) this._setItemActive(actvId);
	} else if (force != true) {
		this._checkHeight();
	}
	
};

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
// show/hide

dhtmlXSideBar.prototype._showItem = function(id, actv) {
	
	if (this.t[id] == null || this.t[id].conf.visible == true || this.t[id].conf.transActv == true) return;
	
	if (this.conf.transProp !== false) {
		
		this.t[id].conf.transActv = true;
		this.t[id].conf.transMode = "show";
		this.t[id].conf.transProp = this.conf.transProp;
		this.t[id].conf.transActvId = (actv?id:null);
		
		if (this.t[id].conf.transEv != true) {
			this.t[id].item.addEventListener(this.conf.transEv, this._doOnTrEnd);
			this.t[id].conf.transEv = true;
		}
		
		this.t[id].conf.visible = true;
		this.t[id].item.style[this.conf.transProp] = this.conf.transValue;
		this.t[id].item.className = "dhxsidebar_item";
		
	} else {
		this.t[id].conf.visible = true;
		this.t[id].item.style.display = "";
		
		if (actv == true) {
			this._setItemActive(id);
		} else {
			this._checkHeight();
		}
	}
};

dhtmlXSideBar.prototype._hideItem = function(id, actvId) {
	
	if (this.t[id] == null || this.t[id].conf.visible != true || this.t[id].conf.transActv == true) return;
	
	var wasSelected = false;
	if (this.conf.selected == id) {
		this.conf.selected = null;
		this.t[id].conf.active = false;
		this.t[id].item.className = "dhxsidebar_item";
		wasSelected = true;
	}
	
	var prev = this._getPrevVisible(id);
	var next = this._getNextVisible(id);
	
	var actvId = (wasSelected && actvId !== false ? (actvId==true?null:actvId)||next||prev : null);
	
	if (this.conf.transProp !== false) {
		
		this.t[id].conf.transActv = true;
		this.t[id].conf.transMode = "hide";
		this.t[id].conf.transProp = this.conf.transProp;
		this.t[id].conf.transActvId = actvId;
		this.t[id].conf.visible = false;
		
		if (this.t[id].conf.transEv != true) {
			this.t[id].item.addEventListener(this.conf.transEv, this._doOnTrEnd);
			this.t[id].conf.transEv = true;
		}
		
		this.t[id].item.style[this.conf.transProp] = this.conf.transValue;
		this.t[id].item.className = "dhxsidebar_item dhxsidebar_item_hidden";
		
		
	} else {
		
		this.t[id].item.style.display = "none";
		this.t[id].conf.visible = false;
		
		// hide cell
		if (this.conf.single_cell != true) {
			this.t[id].cell.cell.style.visibility = "hidden";
			this.t[id].cell.cell.style.top = "-5000px";
		}
		
		if (actvId != null) this._setItemActive(actvId);
		this._checkHeight();
		
		if (this.t[id].conf.remove == true) this._removeItem(id);
	}
	
};

dhtmlXSideBar.prototype._isItemVisible = function(id) {
	return (this.t[id].conf.visible == true);
};

// bubbles
dhtmlXSideBar.prototype._setItemBubble = function(id, value) {
	if (this.t[id] == null) return;
	this.t[id].item.innerHTML = window.dhx4.template(this.conf.tpl_str, this.t[id].init)+window.dhx4.template(this.tpl_bubble, {value: String(value)});
	this.t[id].conf.bubble = value;
};
dhtmlXSideBar.prototype._getItemBubble = function(id) {
	if (this.t[id] == null) return null;
	return (typeof(this.t[id].conf.bubble)=="undefuned"?null:this.t[id].conf.bubble);
};

dhtmlXSideBar.prototype._clearItemBubble = function(id) {
	if (this.t[id] == null) return;
	this.t[id].item.innerHTML = window.dhx4.template(this.conf.tpl_str, this.t[id].init);
	this.t[id].conf.bubble = null;
};
// attach sidebar to cell
dhtmlXCellObject.prototype.attachSidebar = function(conf) {
	
	this.callEvent("_onBeforeContentAttach",["sidebar"]);
	
	if (conf == null) conf = {};
	
	var obj = document.createElement("DIV");
	obj.style.width = "100%";
	obj.style.height = "100%";
	obj.style.position = "relative";
	obj.style.overflow = "hidden";
	
	// all but window borderless
	if (typeof(window.dhtmlXWindowsCell) == "function" && this instanceof window.dhtmlXWindowsCell) {
		
	} else {
		// acc, layout, tabbar, sidebar
		if (this.conf.skin == "dhx_skyblue") obj._ofs = {t:-1,r:-1,b:-1,l:-1};
		if (this.conf.skin == "dhx_web") {
			if (typeof(window.dhtmlXSideBarCell) == "function" && this instanceof window.dhtmlXSideBarCell) obj._ofs = {l: 8};
			if (typeof(window.dhtmlXLayoutCell) == "function" && this instanceof window.dhtmlXLayoutCell) obj._ofs = {t: 2};
			if (typeof(window.dhtmlXTabBarCell) == "function" && this instanceof window.dhtmlXTabBarCell) obj._ofs = {t: 8};
			if (typeof(window.dhtmlXAccordionCell) == "function" && this instanceof window.dhtmlXAccordionCell) obj._ofs = {t: 2};
		}
		if (this.conf.skin == "dhx_terrace") {
			if (typeof(window.dhtmlXSideBarCell) == "function" && this instanceof window.dhtmlXSideBarCell) obj._ofs = {l:-1};
			if (typeof(window.dhtmlXLayoutCell) == "function" && this instanceof window.dhtmlXLayoutCell) obj._ofs = {t:-1,r:-1,b:-1,l:-1};
			if (typeof(window.dhtmlXTabBarCell) == "function" && this instanceof window.dhtmlXTabBarCell) obj._ofs = {t:-1};
			if (typeof(window.dhtmlXAccordionCell) == "function" && this instanceof window.dhtmlXAccordionCell) obj._ofs = {t:-1,r:-1,b:-1,l:-1};
		}
		if (this.conf.skin == "material") {
			if (typeof(window.dhtmlXAccordionCell) == "function" && this instanceof window.dhtmlXAccordionCell) obj._ofs = {t:-1,r:-1,b:-1,l:-1};
			if (typeof(window.dhtmlXTabBarCell) == "function" && this instanceof window.dhtmlXTabBarCell) obj._ofs = {t:-1,r:-1,b:-1,l:-1};
			if (typeof(window.dhtmlXSideBarCell) == "function" && this instanceof window.dhtmlXSideBarCell) obj._ofs = {l:-1};
		}
	}
	
	this._attachObject(obj);
	
	conf.skin = this.conf.skin;
	conf.parent = obj;
	
	this.dataType = "sidebar";
	this.dataObj = new dhtmlXSideBar(conf);
	
	conf.parent = obj = null;
	conf = null;
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
};
/* header */
dhtmlXSideBarCell.prototype._initHeader = function() {
	
	var t = document.createElement("DIV");
	t.className = "dhx_cell_sidebar_hdr";
	t.innerHTML = (this.sidebar.conf.autohide==true?"<div class='dhx_cell_sidebar_hdr_icon'></div>":"")+
			"<div class='dhx_cell_sidebar_hdr_text"+(this.sidebar.conf.autohide==true?" dhx_cell_sidebar_hdr_text_icon":"")+"'></div>";
	
	this.cell.insertBefore(t, this.cell.childNodes[this.conf.idx.cont]);
	t = null;
	
	// include into content top offset calculation
	this.conf.ofs_nodes.t._getHdrHeight = "func";
	
	// show/hide
	this.conf.hdr = {visible: true};
	
	// include into index
	this.conf.idx_data.hdr = "dhx_cell_sidebar_hdr";
	this._updateIdx();
	
};

dhtmlXSideBarCell.prototype._getHdrHeight = function() {
	return this.cell.childNodes[this.conf.idx.hdr].offsetHeight;
};

// visibility
dhtmlXSideBarCell.prototype.showHeader = function() {
	if (this.conf.hdr.visible == true) return;
	this.conf.hdr.visible = true;
	this.cell.childNodes[this.conf.idx.hdr].className = "dhx_cell_sidebar_hdr";
	this._adjustCont(this._idd);
};

dhtmlXSideBarCell.prototype.hideHeader = function() {
	if (this.conf.hdr.visible != true) return;
	this.conf.hdr.visible = false;
	this.cell.childNodes[this.conf.idx.hdr].className = "dhx_cell_sidebar_hdr dhx_cell_sidebar_hdr_hidden";
	this._adjustCont(this._idd);
};

dhtmlXSideBarCell.prototype.isHeaderVisible = function() {
	return (this.conf.hdr.visible==true);
};

// text
dhtmlXSideBarCell.prototype.setHeaderText = function(text) {
	this.conf.text = text;
	var t = this.cell.childNodes[this.conf.idx.hdr];
	t.childNodes[(t.firstChild.className=="dhx_cell_sidebar_hdr_icon"?1:0)].innerHTML = "<span>"+text+"</span>";
	t = null;
};

dhtmlXSideBarCell.prototype.getHeaderText = function() {
	return this.conf.text;
};

