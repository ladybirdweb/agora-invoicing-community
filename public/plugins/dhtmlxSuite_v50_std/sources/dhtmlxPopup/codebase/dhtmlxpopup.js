/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXPopup(conf) {
	
	var that = this;
	
	this.conf = conf||{};
	conf = null;
	
	this.mode = (this.conf.mode||"bottom"); // popup position related to caller element
	this.conf.zi = window.dhx4.newId();
	
	// enable/disable native context menu, enabled by default
	this.conf.context = (this.conf.context==null?true:window.dhx4.s2b(this.conf.context));
	
	// enable/disable full-fix for content in IE6 (depending on content fix mey be not required), disabled by default
	this.conf.IE6_display_fix = (this.conf.IE6_display_fix==null?false:window.dhx4.s2b(this.conf.IE6_display_fix));
	
	// click/touch conf
	this.conf.last_p_click = this.conf.last_body_click = null;
	
	this.p = document.createElement("DIV");
	this.p.style.display = "none";
	this.p.innerHTML = "<div class='dhx_popup_area"+(window.dhx4.isIE?" dhx_popup_area_ie":"")+"'>"+
					"<table cellspacing='0' cellpadding='0' border='0' class='dhx_popup_table'><tbody></tbody></table>"+
				"</div>"+
				"<div class='dhx_popup_arrow dhx_popup_arrow_"+this.mode+"'></div>";
	
	document.body.appendChild(this.p);
	
	this.p.oncontextmenu = function(e) {
		if (that.conf.context == false) {
			e = e||event;
			e.returnValue = false;
			return false;
		}
	}
	
	this.skinParams = {
		dhx_terrace: {
			t0: 19, // minimal top offset for polygon, i.e. space between polygon top and arrow top
			t1: 9,  // if no more space at top, and "t0 allowed" - move top polygon position a bit to bottom, t1 = offset for empty space at top
			t2: 19, // same as t0, for width
			t3: 9   // same as t1, for width
		},
		dhx_skyblue: {t0: 12, t1: 9, t2: 12, t3: 9},
		dhx_web: {t0: 12, t1: 9, t2: 12, t3: 9},
		material: {t0: 19, t1: 9, t2: 19, t3: 9}
	};
	
	this.p.ontouchstart = this.p.onclick = function(e) {
		e = e||event;
		
		// skip click if touch
		var r = (that.conf.last_p_click != null && that.conf.last_p_click != e.type);
		that.conf.last_p_click = e.type;
		if (r == true) return;
		
		that._clearClick = true;
		if (that._nodeObj != null) {
			that.callEvent("onContentClick",[]);
			return true;
		}
		var t = (e.target||e.srcElement);
		var id = null;
		while (t != that.p && t != null) {
			if (typeof(t._idd) != "undefined" && !t._isSeparator) {
				id = t._idd;
				t = null;
			} else {
				t = t.parentNode;
			}
		}
		t = null;
		if (id != null) {
			that.callEvent("onClick",[id]);
			if (that != null && that.isVisible != null && that.isVisible() && that.callEvent("onBeforeHide",["select",e,id]) === true) {
				e.cancelBubble = true;
				that.hide();
			}
		}
	}
	
	this.separator = "DHXSEP_"+window.dhx4.newId();
	
	this.tpl = [];
	this._setTemplate = function(t) {
		this.tpl = t.split(",");
	}
	
	this.show = function(id) { // 4 coords for custom object, x, y, width, height
		
		var p = null;
		
		if (arguments.length == 1) {
			
			// if id not specified show on first
			
			if (!id) {
				id = this.conf.id[0];
			} else {
				if (!this._idExists(id)) return;
			}
			if (this.conf.toolbar) {
				p = this.conf.toolbar._getItemDim(id);
			}
			if (this.conf.ribbon) {
				p = this.conf.ribbon._getItemDim(id);
			}
			if (this.conf.form) {
				p = this.conf.form._getItemDim(id);
			}
			
		} else if (arguments.length == 4) {
			
			this._clearClick = true;
			
			// show for custom object, 4 coords
			p = {
				left:   arguments[0],
				top:    arguments[1],
				width:  arguments[2],
				height: arguments[3]
			}
			
			id = null;
		}
		
		if (!p) return;
		
		this.p.style.visibility = "hidden";
		this.p.style.display = "";
		
		this._setPos(p);
		
		this.p.style.zIndex = window.dhx4.zim.reserve(this.conf.zi);
		this.p.style.visibility = "visible";
		
		this._lastId = id;
		
		this.callEvent("onShow",[id]);
		
		
	}
	
	this._setPos = function(p, state) {
		
		var x = p.left;
		var y = p.top;
		var w = p.width;
		var h = p.height;
		
		this._posData = {
			left: x,
			top: y,
			width: w,
			height: h
		};
		
		var d = window.dhx4.screenDim();
		
		var mode = state||this.mode;
		if (typeof(state) == "undefined") state = false;
		
		// avail space form each side, negative value = no-space
		var availSpace = {
			top: (y-this.p.offsetHeight)-d.top,
			bottom: d.bottom-(y+h+this.p.offsetHeight),
			left: x-this.p.offsetWidth-d.left,
			right: d.right-(x+w+this.p.offsetWidth)
		};
		
		if (!state && availSpace[mode] < 0) {
			var k = this._getAvailPos(mode, availSpace);
			if (k !== false) {
				this._setPos(p, k);
				return;
			}
			
		}
		
		if (mode == "top" || mode == "bottom") {
			
			var t0 = this.skinParams[this.conf.skin].t2;
			var t1 = this.skinParams[this.conf.skin].t3;
			
			var pw2 = Math.round(this.p.offsetWidth/2); // 1/2 polygon width
			var aw2 = Math.round(this.p.lastChild.offsetWidth/2); // 1/2 arrow width
			
			// define max left and right position of input including rendering [d.left..d.right] area
			if (x < d.left) { var x1 = Math.min(x+w, d.left); w = x+w-x1; x = x1; } // left
			if (x+w > d.right) w = d.right-x; // right
			
			// arrow position
			var ta = Math.round(x+w/2);
			
			// polygon top
			var left = ta - pw2;
			var maxLeft = ta - t0 - aw2;
			var maxLeftRight = ta+aw2+t0-this.p.offsetWidth;
			
			
			if (left < d.left-t1) { // left
				left = Math.min(d.left-t1, maxLeft);
			} else if (left+this.p.offsetWidth > d.right+t1) { // right
				left = Math.max(maxLeftRight, d.right+t1-this.p.offsetWidth); // -scrollWidth here?
			}
			
			// draw polygon
			this.p.style.left = left+"px";
			this.p.style.top = (mode=="top"?y-this.p.offsetHeight:y+h)+"px";
			
			// fix arrow offset (it inside polygon)
			ta = ta-left-aw2;
			
			// draw arrow
			this.p.lastChild.className = "dhx_popup_arrow dhx_popup_arrow_"+mode;
			this.p.lastChild.style.top = (mode=="top"?this.p.offsetHeight-this.p.lastChild.offsetHeight:0)+"px";
			this.p.lastChild.style.left = ta+"px";
			
			
		}
		
		
		if (mode == "left" || mode == "right") {
			
			var t0 = this.skinParams[this.conf.skin].t0;
			var t1 = this.skinParams[this.conf.skin].t1;
			
			var ph2 = Math.round(this.p.offsetHeight/2); // 1/2 polygon height
			var ah2 = Math.round(this.p.lastChild.offsetHeight/2); // 1/2 arrow height
			
			// define max top and bottom position of input including rendering [d.top..d.bottom] area
			if (y < d.top) { var y1 = Math.min(y+h, d.top); h = y+h-y1; y = y1; } // top
			if (y+h > d.bottom) h = d.bottom-y; // bottom
			
			// arrow position
			var ta = Math.round(y+h/2);
			
			// polygon top
			var top = ta - ph2;
			var maxTop = ta - t0 - ah2;
			var maxTopBottom = ta+ah2+t0-this.p.offsetHeight;
			
			
			if (top < d.top-t1) { // top
				top = Math.min(d.top-t1, maxTop);
			} else if (top+this.p.offsetHeight > d.bottom+t1) { // bottom
				top = Math.max(maxTopBottom, d.bottom+t1-this.p.offsetHeight);
			}
			
			// draw polygon
			this.p.style.left = (mode=="left"?x-this.p.offsetWidth:x+w)+"px";
			this.p.style.top = top+"px";
			
			// fix arrow offset (it inside polygon)
			ta = ta-top-ah2;
			
			// draw arrow
			this.p.lastChild.className = "dhx_popup_arrow dhx_popup_arrow_"+mode;
			this.p.lastChild.style.left = (mode=="left"?this.p.offsetWidth-this.p.lastChild.offsetWidth:0)+"px";
			this.p.lastChild.style.top = ta+"px";
			
		}
		
		if (this._IEDisp && this._nodeId != null) {
			var t = document.getElementById(this._nodeId);
			if (this.conf.IE6_display_fix == true) t.style.visibility = "hidden";
			window.setTimeout(function(){
				t.style.visibility = "visible";
				t = null;
			},1);
		}
	}
	
	this._getAvailPos = function(mode, data) {
		
		var seq = {
			top: ["bottom","right","left"],
			bottom: ["top","right","left"],
			left: ["right", "bottom", "top"],
			right: ["left", "bottom", "top"]
		};
		
		var dir = null;
		
		// check "next" with avail space
		for (var q=0; q<seq[mode].length; q++) {
			if (dir == null && data[seq[mode][q]] > 0) dir = seq[mode][q];
		}
		
		// define which side have more space
		if (dir == null) {
			dir = "bottom";
			for (var a in data) if (data[a] > data[dir]) dir = a;
		}
		
		if (dir == mode) return false;
		
		return dir;
		
	}
	
	this._repaint = function() {
		if (this.isVisible()) this._setPos(this._posData);
	}
	
	this.clear = function() {
		
		if (this._nodeObj) {
			if (window.dhx4.isIE && typeof(window.dhtmlXLayoutObject) == "function" && this._nodeObj instanceof window.dhtmlXLayoutObject) {
				this.p.onmousedown = null;
			}
			if (this._nodeObj.unload) {
				this._nodeObj.unload();
			} else if (this._nodeObj.destruct) {
				this._nodeObj.destruct();
			}
			this._nodeObj = this._nodeId = null;
			// events if any
			if (this._nodeObjEv != null) {
				for (var q=0; q<this._nodeObjEv.length; q++) this.detachEvent(this._nodeObjEv[q]);
				this._nodeObjEv = null;
			}
		}
		
		if (this._IEHoverInited) this._IEHoverClear();
		
		var r = this.p.firstChild.firstChild.firstChild; // table->tbody
		while (r.childNodes.length > 0) r.removeChild(r.lastChild);
		
		r = null;
		
		this.itemData = {};
	}
	
	this.hide = function() {
		if (this.p.style.display != "none") {
			this.p.style.display = "none";
			window.dhx4.zim.clear(this.conf.zi);
			var id = this._lastId;
			this._lastId = null;
			this.callEvent("onHide",[id]);
			// reset click/tuoch events types
			this.conf.last_p_click = this.conf.last_body_click = null;
		}
	}
	
	this.isVisible = function() {
		return (this.p.style.display == "");
	}
	
	this.itemData = {};
	
	this.getItemData = function(id) {
		if (!id) return this.itemData;
		if (this.itemData[id]) return this.itemData[id];
		return {};
	}
	
	this.setSkin = function(skin) {
		this.conf.skin = skin;
		this.p.className = "dhx_popup_"+this.conf.skin;
		if (this._nodeObj != null && typeof(this._nodeObj.setSkin) == "function") this._nodeObj.setSkin(this.conf.skin);
		this._repaint();
	}
	
	this.attachList = function(template, data) {
		
		this._setTemplate(template);
		
		this.clear();
		var r = this.p.firstChild.firstChild.firstChild; // table->tbody
		
		for (var q=0; q<data.length; q++) {
			
			var tr = document.createElement("TR");
			if (data[q] != this.separator) {
				if (typeof(data[q].id) == "undefined" || data[q].id == null) {
					tr._idd = window.dhx4.newId();
					while (this.itemData[tr._idd] != null) tr._idd = window.dhx4.newId();
				} else {
					tr._idd = data[q].id;
				}
				this.itemData[tr._idd] = data[q];
			}
			r.appendChild(tr);
			
			if (data[q] == this.separator) {
				tr.className = "dhx_popup_sep";
				tr._isSeparator = true;
				var td = document.createElement("TD");
				td.className = "dhx_popup_sep";
				td.colSpan = this.tpl.length;
				td.innerHTML = "<div class='dhx_popup_sep'>&nbsp;</div>";
				tr.appendChild(td);
				td = null;
			} else {
				for (var w=0; w<this.tpl.length; w++) {
					var css = "dhx_popup_td";
					if (this._IEFirstLast && (this.tpl.length==1||w==0||w==this.tpl.length-1)) {
						if (this.tpl.length==1) css += " dhx_popup_td_single"; else css += (w==0?" dhx_popup_td_first":" dhx_popup_td_last");
					}
					var td = document.createElement("TD");
					td.className = css;
					td.innerHTML = data[q][this.tpl[w]]||"&nbsp;";
					tr.appendChild(td);
					td = null;
				}
				// IE6 hover functionality
				if (this._IEHover) {
					tr._IEHover = true;
					if (!this._IEHoverInited) this._IEHoverInit();
				}
			}
			tr = null;
		}
		r = null;
		
		this._repaint();
		
	}
	
	this._attachNode = function(mode, data) {
		
		this.clear();
		
		this._nodeId = "dhxpopup_node_"+window.dhx4.newId();
		
		var r = this.p.firstChild.firstChild.firstChild; // table->tbody
		
		var tr = document.createElement("TR");
		tr.className = "dhxnode";
		r.appendChild(tr);
		
		var td = document.createElement("TD");
		td.className = "dhx_popup_td";
		td.innerHTML = "<div id='"+this._nodeId+"' style='position:relative;'></div>";
		
		if (data.width) td.firstChild.style.width = data.width+"px";
		if (data.height) td.firstChild.style.height = data.height+"px";
		
		tr.appendChild(td);
		
		td = tr = r = null;
		
		if (typeof(this["_attach_init_"+mode]) == "function") {
			this["_attach_init_"+mode](data);
			this._enableIEVFix();
		}
		
		this._repaint();
		
		return this._nodeObj;
		
	}
	
	this.unload = function() {
		
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("touchstart", this._doOnClick, false);
			window.removeEventListener("click", this._doOnClick, false);
			window.removeEventListener("keyup", this._doOnKeyUp, false);
			window.removeEventListener("unload", this._doOnUnload, false);
		} else {
			document.body.detachEvent("onclick", this._doOnClick, false);
			document.body.detachEvent("onkeyup", this._doOnKeyUp, false);
			document.body.detachEvent("onunload", this._doOnUnload, false);
		}
		window.dhx4.detachEvent(this.conf.ev_grid_click);
		this.clear();
		
		if (this.conf.toolbarEvent != null && this.conf.toolbar != null) {
			if (this.conf.toolbar.detachEvent != null) {
				// make sure toolbar wasn't unloaded
				this.conf.toolbar.detachEvent(this.conf.toolbarEvent);
			} else {
				this.conf.toolbar._getItemDim = null;
			}
		}
		
		if (this.conf.ribbonEvent != null && this.conf.ribbon != null) {
			if (this.conf.ribbon.detachEvent != null) {
				this.conf.ribbon.detachEvent(this.conf.ribbonEvent);
			} else {
				this.conf.ribbon._getItemDim = null;
			}
		}
		
		if (this.conf.slider != null) {
			for (var q=0; q<this.conf.slider_events.length; q++) {
				this.conf.slider.detachEvent(this.conf.slider_events[q]);
			}
			this.conf.slider_events = null;
			this._sliderShow = this._sliderHide = null;
			this.conf.slider = null;
		}
		
		window.dhx4._eventable(this, "clear");
		
		this.p.onclick = this.p.ontouchstart = this.p.oncontextmenu = null;
		this.p.parentNode.removeChild(this.p);
		this.p = null;
		
		for (var a in this.conf) this.conf[a] = null;
		for (var a in this) this[a] = null;
		
		that = null;
		
	}
	
	window.dhx4._eventable(this);
	
	this._doOnClick = function(e) {
		e = e||event;
		
		// skip click if touch
		var r = (that.conf.last_body_click != null && that.conf.last_body_click != e.type);
		that.conf.last_body_click = e.type;
		if (r == true) return;
		
		if (that._clearClick == true) {
			that._clearClick = false;
			return;
		}
		
		if (that.conf.form != null) {
			var id;
			var t = (e.target||e.srcElement);
			if ((t.tagName||"").toLowerCase() == "option") t = t.parentNode;
			if (t.className != null && t.className.search("dhxform") >= 0) {
				if (t.parentNode != null && t.parentNode.parentNode != null && t.parentNode.parentNode._idd != null) {
					id = t.parentNode.parentNode._idd;
					if (t.parentNode.parentNode._type == "ra") id = [t.parentNode.parentNode._group, t.parentNode.parentNode._value];
				}
			} else {
				// check if button, editor or combo
				var k = true;
				var f = false;
				while (k && !f) {
					var p = (t.className||"").toLowerCase();
					if (p.length > 0) f = (p == "dhxform_btn" || p.search(/dhxeditor_inside/gi) >= 0 || p == "dhxcombo_input" || p.search(/dhxcombolist/gi) >=0);
					t = t.parentNode;
					k = (t != null);
				}
				if (f) return;
			}
			t = null;
			if (id != null && that._idExists(id)) return;
		}
		// if popup has attached form - skin closing if list clicked (it attached to body)
		if (typeof(window.dhtmlXForm) == "function" && that._nodeObj instanceof window.dhtmlXForm) {
			var combos = {};
			var p = 0;
			var form = that._nodeObj;
			form.forEachItem(function(id){
				if (form.getItemType(id) == "combo") {
					combos[form.getCombo(id).list._listId] = true;
					p++;
				}
			});
			form = null;
			//
			if (p > 0) {
				var t = (e.target||e.srcElement);
				var k = true;
				var f = false;
				while (k == true && f != true) {
					var p = (t.className||"").toLowerCase();
					if (p.length > 0 && p.search(/^dhxcombolist/gi) >=0 && t._listId != null && combos[t._listId] == true) {
						f = true;
						t = null;
					} else {
						t = t.parentNode;
						k = (t != null);
					}
				}
				if (f == true) {
					// clicked combo belong to attached form, cancel popup hiding
					return;
				}
			}
		}
		if (that.isVisible() && that.callEvent("onBeforeHide",["click",e]) === true) {
			that.hide();
		}
	}
	this._doOnKeyUp = function(e) {
		e = e||event;
		if (e.keyCode == 27) {
			if (that.isVisible() && that.callEvent("onBeforeHide",["esc",e]) === true) {
				that.hide();
			}
		}
	}
	this._doOnUnload = function() {
		that.unload();
	}
	
	if (typeof(window.addEventListener) == "function") {
		window.addEventListener("touchstart", this._doOnClick, false);
		window.addEventListener("click", this._doOnClick, false);
		window.addEventListener("keyup", this._doOnKeyUp, false);
		window.addEventListener("unload", this._doOnUnload, false);
	} else {
		document.body.attachEvent("onclick", this._doOnClick, false);
		document.body.attachEvent("onkeyup", this._doOnKeyUp, false);
		document.body.attachEvent("onunload", this._doOnUnload, false);
	}
	
	// grid canceling some events, but trigger inner event
	this.conf.ev_grid_click = window.dhx4.attachEvent("_onGridClick", function(ev, grid){
		if (that._nodeObj != null && (that._nodeObj == grid || that._findGrid(that._nodeObj, grid) == true)) {
			// clicked grid is inside popup, attached directly or to any nested control
			// do not hide popup by this click
		} else {
			// grid is outside of popup
			that._clearClick = false;
			that._doOnClick(ev);
		}
		grid = null;
	});
	
	this._findGrid = function(obj, grid) {
		var gridFound = false;
		if (typeof(window.dhtmlXTabBar) == "function" && obj instanceof window.dhtmlXTabBar) {
			obj.forEachTab(function(tab){
				var nested = tab.getAttachedObject();
				if (gridFound == false && nested != null) {
					gridFound = gridFound||(nested==grid)||this._findGrid(nested);
					nested = null;
				}
			});
		}
		// check if attached to form's container
		if (typeof(window.dhtmlXForm) == "function" && obj instanceof window.dhtmlXForm) {
			obj.forEachItem(function(id){
				if (gridFound != true && obj.getItemType(id) == "container" && grid.entBox == obj.getContainer(id)) {
					gridFound = true;
				}
			});
		}
		obj = null;
		return gridFound;
	};
	
	this._idExists = function(id) {
		var r = false;
		for (var q=0; q<this.conf.id.length; q++) {
			if (this.conf.id[q] instanceof Array) { // radiobutton
				r = r||(this.conf.id[q][0]==id[0]&&this.conf.id[q][1]==id[1]);
			} else {
				r = r||this.conf.id[q]==id;
			}
		}
		return r;
	}
	
	// IE6/Quircks Display fix
	this._IEDisp = (window.dhx4.isIE6 || document.compatMode != "CSS1Compat");
	
	// IE6 hover functionality
	this._IEHover = (window.dhx4.isIE6 == true);
	if (this._IEHover) {
		this._IEHoverInit = function() {
			this.p.onmouseover = function() {
				var t = event.srcElement;
				while (t != this && t._IEHover != true) t = t.parentNode;
				if (t._IEHover) {
					if (that._IEHoverTM) window.clearTimeout(that._IEHoverTM);
					if (that._lastIEHover == t) return;
					that._IEHoverRender(t);
					t = null;
				}
			}
			this.p.onmouseout = function() {
				if (that._IEHoverTM) window.clearTimeout(that._IEHoverTM);
				that._IEHoverTM = window.setTimeout(function(){that._IEHoverRender(null);},1);
			}
			this._IEHoverRender = function(t) {
				if (this._lastIEHover != null) {
					if (this._lastIEHover.className.search(/tr_hover/gi) >= 0) {
						this._lastIEHover.className = this._lastIEHover.className.replace(/\s{0,}tr_hover/gi, "");
						this._lastIEHover = null;
					}
				}
				if (t != null && t.className.search(/tr_hover/gi) < 0) {
					t.className += " tr_hover";
					that._lastIEHover = t;
				}
			}
			this._IEHoverInited = true;
		}
		this._IEHoverClear =  function() {
			this.p.onmouseover = null;
			this.p.onmouseout = null;
			this._IEHoverInited = false;
		}
	}
	
	// IE6/7/8 first/last-child
	this._IEFirstLast = (window.dhx4.isIE6 || window.dhx4.isIE7 || window.dhx4.isIE8);
	
	// IE6 visibility fix
	this._enableIEVFix = function() {
		if (window.dhx4.isIE6 || window.dhx4.isIE7) {
			var e1 = this.attachEvent("onHide", function(){
				document.getElementById(this._nodeId).style.visibility = "hidden";
			});
			var e2 = this.attachEvent("onShow", function(){
				document.getElementById(this._nodeId).style.visibility = "visible";
			});
			if (this._nodeObjEv == null) this._nodeObjEv = [];
			this._nodeObjEv.push(e1,e2);
		}
	}
	
	// auto-init, toolbar mode
	if (typeof(window.dhtmlXToolbarObject) == "function" && this.conf.toolbar != null && this.conf.toolbar instanceof window.dhtmlXToolbarObject && this.conf.id != null) {
		
		if (!(this.conf.id instanceof Array)) this.conf.id = [this.conf.id];
		
		this.skinParent = this.conf.toolbar.conf.skin;
		
		this._doOnToolbarClick = function(id) {
			for (var q=0; q<that.conf.id.length; q++) {
				if (id == that.conf.id[q]) {
					if (id != that._lastId) {
						that.show(id);
						that._clearClick = true;
					}
				}
			}
		}
		
		// extension for toolbar, return pos/dim for specified button
		if (typeof(dhtmlXToolbarObject.prototype._getItemDim) == "undefined") {
			
			dhtmlXToolbarObject.prototype._getItemDim = function(id) {
				var t = this.objPull[this.idPrefix+id];
				var p = {
					left: window.dhx4.absLeft(t.obj),
					top: window.dhx4.absTop(t.obj),
					width: t.obj.offsetWidth+(t.arw?t.arw.offsetWidth:0),
					height: t.obj.offsetHeight
				};
				t = null;
				return p;
			}
			
		}
		
		this.conf.toolbarEvent = this.conf.toolbar.attachEvent("onClick", this._doOnToolbarClick);
		
	}
	
	// auto-init, ribbon mode
	if (typeof(window.dhtmlXRibbon) == "function" && this.conf.ribbon != null && this.conf.ribbon instanceof window.dhtmlXRibbon && this.conf.id != null) {
		
		if (!(this.conf.id instanceof Array)) this.conf.id = [this.conf.id];
		
		this.skinParent = this.conf.ribbon.conf.skin;
		
		this._doOnRibbonClick = function(id) {
			for (var q=0; q<that.conf.id.length; q++) {
				if (id == that.conf.id[q]) {
					if (id != that._lastId) {
						that.show(id);
						that._clearClick = true;
					}
				}
			}
		}
		
		// extension for toolbar, return pos/dim for specified button
		if (typeof(dhtmlXRibbon.prototype._getItemDim) == "undefined") {
			
			dhtmlXRibbon.prototype._getItemDim = function(id) {
				var t = this._items[id].base;
				var p = {
					left: window.dhx4.absLeft(t),
					top: window.dhx4.absTop(t),
					width: t.offsetWidth,
					height: t.offsetHeight
				};
				t = null;
				return p;
			}
			
		}
		
		this.conf.ribbonEvent = this.conf.ribbon.attachEvent("_showPopup", this._doOnRibbonClick);
		
	}
	
	// auto-init, form mode
	if (typeof(window.dhtmlXForm) == "function" && this.conf.form != null && this.conf.form instanceof window.dhtmlXForm && this.conf.id != null) {
		
		if (!(this.conf.id instanceof Array)) this.conf.id = [this.conf.id];
		
		if (!this.conf.mode) this.mode = "right"; // default mode for form
		this.skinParent = this.conf.form.skin;
		
		if (typeof(dhtmlXForm.prototype._getItemDim) == "undefined") {
			
			dhtmlXForm.prototype._getItemDim = function(name, value) {
				return this.doWithItem(name, "_getDim");
			};
			
			for (var a in dhtmlXForm.prototype.items) {
				
				dhtmlXForm.prototype.items[a]._getDim = function(item) {
					
					var t = item;
					
					if ({ta:true, pw:true, se:true, tp:true, fl:true, calendar:true, colorpicker:1, editor:true, container:true}[item._type]) {
						t = item.childNodes[item._ll?1:0].childNodes[0];
					}
					if ({ch:true, ra:true, btn2state:true}[item._type]) {
						t = item.childNodes[item._ll?1:0].childNodes[1];
					}
					if ({bt:true, lb:true, fs:true}[item._type]) {
						t = item.firstChild;
					}
					if ({combo:true}[item._type]) {
						t = item._combo.DOMParent.firstChild;
					}
					if ({image:true}[item._type]) {
						t = item.childNodes[item._ll?1:0].lastChild;
					}
					if ({bl:true}[item._type]) {
						t = item.firstChild.firstChild.firstChild; // nested form's base
					}
					
					var p = {
						left: window.dhx4.absLeft(t),
						top: window.dhx4.absTop(t),
						width: t.offsetWidth,
						height: t.offsetHeight
					};
					
					t = null;
					return p;
					
				};
				
			}
			
		}
		
	}
	
	// auto-init, slider mode
	if (typeof(window.dhtmlXSlider) == "function" && this.conf.slider != null && this.conf.slider instanceof window.dhtmlXSlider) {
		
		if (!this.conf.mode) this.mode = "top"; // default mode for slider
		
		this.conf.slider_tm = null;
		
		this._sliderShow = function() {
			if (that.conf.slider_tm) window.clearTimeout(that.conf.slider_tm);
			var t = that.conf.slider._r_actv;
			if (t != null) {
				var p = {
					left: window.dhx4.absLeft(t),
					top: window.dhx4.absTop(t),
					width: t.offsetWidth,
					height: t.offsetHeight
				};
				that.show(p.left, p.top, p.width, p.height);
				t = null;
			}
		}
		
		this._sliderHide = function() {
			that.conf.slider_tm = window.setTimeout(function(){
				that.hide();
			}, 200);
		}
		
		this.conf.slider_events = [
			this.conf.slider.attachEvent("onMouseDown", this._sliderShow),
			this.conf.slider.attachEvent("onMouseUp", this._sliderHide),
			this.conf.slider.attachEvent("onChange", this._sliderShow)
		];
		
	}
	
	
	// define skin
	this.setSkin(this.skinParent||this.conf.skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhx_popup")||"material");
	
	return this;
	
};

// object
dhtmlXPopup.prototype.attachObject = function(obj) {
	return this._attachNode("object", {obj: obj});
};
dhtmlXPopup.prototype._attach_init_object = function(data) {
	this._nodeObj = (typeof(data.obj)=="string"?document.getElementById(data.obj):data.obj);
	data.obj = null;
	document.getElementById(this._nodeId).appendChild(this._nodeObj);
	this._nodeObj.style.display = "";
	this._nodeObj.style.visibility = "visible";
};

// html
dhtmlXPopup.prototype.attachHTML = function(html) {
	return this._attachNode("html", {html: html});
};
dhtmlXPopup.prototype._attach_init_html = function(data) {
	document.getElementById(this._nodeId).innerHTML = data.html;
	this._nodeObj = {text: data.html};
};

// form
dhtmlXPopup.prototype.attachForm = function(struct, width) {
	return this._attachNode("form", {struct: struct, width: width});
};
dhtmlXPopup.prototype._attach_init_form = function(data) {
	var that = this;
	this._nodeObj = new dhtmlXForm(this._nodeId, data.struct);
	this._nodeObj.setSkin(this.conf.skin);
	this._nodeObj.attachEvent("_onBeforeEditorAccess", function(){
		// prevent popup autohide, editor will trigger body/click
		that._clearClick = true;
	});
	// fix editors which were inited in hidden popup
	if (this.conf.editorEv != null && this.checkEvent(this.conf.editorEv) == false) {
		this.conf.editorEv = null;
	}
	// attach event for first popup show
	if (dhtmlXForm.prototype.items.editor != null && this.conf.editorEv == null) {
		this.conf.editorEv = this.attachEvent("onShow", function(){
			var editors = dhtmlXForm.prototype.items.editor.editor;
			for (var a in editors) {
				var base = editors[a].base;
				var fixSize = false;
				while (base != null) {
					if (base == this.p) {
						fixSize = true;
						base = null;
					} else {
						base = base.parentNode;
					}
				}
				base = null;
				if (fixSize == true) {
					editors[a].cell.conf.cells_cont = null;
					editors[a].setSizes();
				}
			}
			editors = null;
			this.detachEvent(this.conf.editorEv);
			this.conf.editorEv = null;
		});
		if (this._nodeObjEv == null) this._nodeObjEv = [];
		this._nodeObjEv.push(this.conf.editorEv);
	}
};

// calendar
dhtmlXPopup.prototype.attachCalendar = function(conf) {
	return this._attachNode("calendar", conf||{});
};
dhtmlXPopup.prototype._attach_init_calendar = function(data) {
	if (data["double"] == true) {
		this._nodeObj = new dhtmlXDoubleCalendarObject(this._nodeId);
		this._nodeObj.leftCalendar.setSkin(this.conf.skin);
		this._nodeObj.rightCalendar.setSkin(this.conf.skin);
	} else {
		this._nodeObj = new dhtmlXCalendarObject(this._nodeId);
		this._nodeObj.setSkin(this.conf.skin);
	}
	this._nodeObj.show();
};

// grid
dhtmlXPopup.prototype.attachGrid = function(width, height) {
	return this._attachNode("grid", {width: width||400, height: height||200});
};
dhtmlXPopup.prototype._attach_init_grid = function() {
	this._nodeObj = new dhtmlXGridObject(this._nodeId);
	this._nodeObj.setSkin(this.conf.skin);
};

// tree
dhtmlXPopup.prototype.attachTree = function(width, height, rootId) {
	return this._attachNode("tree", {width: width||400, height: height||200, rootId: rootId||0});
};
dhtmlXPopup.prototype._attach_init_tree = function(data) {
	this._nodeObj = new dhtmlXTreeObject(this._nodeId, "100%", "100%", (data.rootId))
};

// layout
dhtmlXPopup.prototype.attachLayout = function(width, height, pattern) {
	return this._attachNode("layout", {width: width||400, height: height||200, pattern: pattern||"3L"});
};
dhtmlXPopup.prototype._attach_init_layout = function(data) {
	this._nodeObj = new dhtmlXLayoutObject(this._nodeId, data.pattern, this.conf.skin)
};

// accordion
dhtmlXPopup.prototype.attachAccordion = function(width, height, conf) {
	return this._attachNode("accordion", {width: width||400, height: height||200, conf:conf||{}});
};
dhtmlXPopup.prototype._attach_init_accordion = function(conf) {
	conf.conf.parent = this._nodeId;
	conf.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXAccordion(conf.conf);
};

// tabbar
dhtmlXPopup.prototype.attachTabbar = function(width, height, conf) {
	if (typeof(conf) == "string") {
		conf = {mode:conf}; // 3.6 compat
	} else if (typeof(conf) != "object" || conf == null) {
		conf = {};
	}
	return this._attachNode("tabbar", {width: width||400, height: height||200, conf:conf});
};
dhtmlXPopup.prototype._attach_init_tabbar = function(conf) {
	conf.conf.parent = this._nodeId;
	conf.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXTabBar(conf.conf);
};

// sidebar
dhtmlXPopup.prototype.attachSidebar = function(width, height, conf) {
	if (conf == null) conf = {};
	return this._attachNode("sidebar", {width: width||400, height: height||200, conf:conf});
};
dhtmlXPopup.prototype._attach_init_sidebar = function(conf) {
	conf.conf.parent = this._nodeId;
	conf.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXSideBar(conf.conf);
};

// editor
dhtmlXPopup.prototype.attachEditor = function(width, height, conf) {
	return this._attachNode("editor", {width: width||400, height: height||200, conf: conf||{}});
};
dhtmlXPopup.prototype._attach_init_editor = function(data) {
	document.getElementById(this._nodeId).className = "dhxeditor_"+this.conf.skin;
	data.conf.parent = this._nodeId;
	if (data.conf.skin == null) data.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXEditor(data.conf);
	var evId = this.attachEvent("onShow", function(){
		if (this._nodeObj instanceof window.dhtmlXEditor) this._nodeObj.setSizes();
	});
	if (this._nodeObjEv == null) this._nodeObjEv = [];
	this._nodeObjEv.push(evId);
};

// color picker
dhtmlXPopup.prototype.attachColorPicker = function(conf) {
	if (typeof(conf) != "object" || conf == null) conf = {};
	return this._attachNode("colorpicker", {conf:conf});
};
dhtmlXPopup.prototype._attach_init_colorpicker = function(conf) {
	conf.conf.skin = this.conf.skin;
	conf.conf.parent = this._nodeId;
	this._nodeObj = new dhtmlXColorPicker(conf.conf);
};

// carousel
dhtmlXPopup.prototype.attachCarousel = function(width, height, conf) {
	if (conf == null) conf = {};
	return this._attachNode("carousel", {width: width||400, height: height||300, conf:conf});
};
dhtmlXPopup.prototype._attach_init_carousel = function(conf) {
	conf.conf.parent = this._nodeId;
	conf.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXCarousel(conf.conf);
};

// treeview
dhtmlXPopup.prototype.attachTreeView = function(width, height, conf) {
	if (conf == null) conf = {};
	return this._attachNode("treeview", {width: width||400, height: height||300, conf: conf});
};
dhtmlXPopup.prototype._attach_init_treeview = function(conf) {
	conf.conf.parent = this._nodeId;
	conf.conf.skin = this.conf.skin;
	this._nodeObj = new dhtmlXTreeView(conf.conf);
	//
	this._nodeObj.base.className += " dhxtreeview_with_border";
	//
	var evId = this.attachEvent("onShow", function(){
		if (this._nodeObj instanceof window.dhtmlXTreeView) this._nodeObj.setSizes();
	});
	if (this._nodeObjEv == null) this._nodeObjEv = [];
	this._nodeObjEv.push(evId);
};

// other functionality
dhtmlXPopup.prototype.setDimension = function(w, h) {
	if (this._nodeId == null || (w == null && h == null)) return;
	var node = document.getElementById(this._nodeId);
	if (w != null) node.style.width = w+"px";
	if (h != null) node.style.height = h+"px";
	this._repaint();
	if (this._nodeObj != null && typeof(this._nodeObj.setSizes) == "function") this._nodeObj.setSizes();
	node = null;
};
