/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXMenuObject(base, skin) {
	
	var that = this;
	
	// iframe
	this.conf = {
		skin: (skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxmenu")||"material"),
		mode: "web",
		align: "left",
		is_touched: false,
		selected: -1,
		last_click: -1,
		fixed_pos: false, // should be used for frameset in IE
		rtl: false,
		icons_path: "",
		icons_css: false, // use css for icons or direct img links, added in 5.0
		arrow_ff_fix: (navigator.userAgent.indexOf("MSIE") >= 0 && document.compatMode=="BackCompat"), // border fixer for FF for arrows polygons
		live_id: window.dhx4.newId(),
		tags: {
			root: "menu",
			item: "item",
			text_ext: "itemtext",
			userdata: "userdata",
			tooltip: "tooltip",
			hotkey: "hotkey",
			href: "href"
		},
		autoload: {},
		hide_tm: {},
		// shows sublevel polygons from toplevel items with delay
		top_mode: true,
		top_tmtime: 200,
		// visible area
		v_enabled: false,
		v: {x1: null, x2: null, y1: null, y2: null},
		// open direction
		dir_toplv: "bottom",
		dir_sublv: "right",
		// overflow
		auto_overflow: false,
		overflow_limit: 0,
		of_utm: null, // scroll up - tm
		of_utime: 20, // scroll up - time
		of_ustep: 3, // scroll up - step
		of_dtm: null,
		of_dtime: 20,
		of_dstep: 3,
		of_ah: {dhx_skyblue: 24, dhx_web: 25, dhx_terrace: 27, material: 25}, // arrow height+oplygon top/bottom padding
		of_ih: {dhx_skyblue: 24, dhx_web: 24, dhx_terrace: 24, material: 30}, // item height
		// hide
		tm_sec: 400,
		tm_handler: null,
		// dyn load
		dload: false,
		dload_url: "",
		dload_icon: false, // show loading icon
		dload_params: {action: "loadMenu"}, // extra params
		dload_pid: "parentId", // parentId param name
		// skinbased offsets
		tl_botmarg: 1, // top level bottom margin
		tl_rmarg: 0, // right margin
		tl_ofsleft: 1, // offset left
		// context menu
		context: false,
		ctx_zoneid: false,
		ctx_autoshow: true, // default open action
		ctx_autohide: true, // default close action
		ctx_hideall: true, // true will hide all opened contextual menu polygons on mouseout, false - all except topleft
		ctx_zones: {},
		ctx_baseid: null, // add baseId as context zone
		// selected subitems
		selected_sub: [],
		opened_poly: []
	}
	
	if (typeof(base) == "object" && base != null && typeof(base.tagName) == "undefined") {
		
		// object-api init
		if (base.icons_path != null || base.icon_path != null) this.conf.icons_path = (base.icons_path||base.icon_path);
		if (base.skin != null) this.conf.skin = base.skin;
		if (base.visible_area) {
			this.conf.v_enabled = true;
			this.conf.v = {
				x1: base.visible_area.x1,
				x2: base.visible_area.x2,
				y1: base.visible_area.y1,
				y2: base.visible_area.y2
			};
		}
			
		for (var a in {json:1,xml:1,items:1,top_text:1,align:1,open_mode:1,overflow:1,dynamic:1,dynamic_icon:1,context:1,onload:1,onclick:1,oncheckboxclick:1,onradioclick:1,iconset:1}) {
			if (base[a] != null) this.conf.autoload[a] = base[a];
		}
		
		base = base.parent;
	}
	
	if (base == null) {
		this.base = document.body;
	} else {
		var baseObj = (typeof(base)=="string"?document.getElementById(base):base);
		if (baseObj != null) {
			this.base = baseObj;
			if (!this.base.id) this.base.id = "menuBaseId_"+new Date().getTime();
			this.base.className += " dhtmlxMenu_"+this.conf.skin+"_Middle dir_left";
			this.base._autoSkinUpdate = true;
			 // preserv default oncontextmenu for future restorin in case of context menu
			if (this.base.oncontextmenu) this.base._oldContextMenuHandler = this.base.oncontextmenu;
			//
			this.conf.ctx_baseid = this.base.id;
			this.base.onselectstart = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
			this.base.oncontextmenu = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		} else {
			this.base = document.body;
		}
	}
	
	this.idPrefix = "";
	this.topId = "dhxWebMenuTopId";
	
	this.idPull = {};
	this.itemPull = {};
	this.userData = {};
	this.radio = {};
	//
	this.setSkin = function(skin) {
		var oldSkin = this.conf.skin;
		this.conf.skin = skin;
		switch (this.conf.skin){
			case "dhx_skyblue":
			case "dhx_web":
				this.conf.tl_botmarg = 2;
				this.conf.tl_rmarg = 1;
				this.conf.tl_ofsleft = 1;
				break;
			case "dhx_terrace":
			case "material":
				this.conf.tl_botmarg = 0;
				this.conf.tl_rmarg = 0;
				this.conf.tl_ofsleft = 0;
				break;
		}
		if (this.base._autoSkinUpdate) {
			this.base.className = this.base.className.replace("dhtmlxMenu_"+oldSkin+"_Middle", "")+" dhtmlxMenu_"+this.conf.skin+"_Middle";
		}
		
		for (var a in this.idPull) {
			this.idPull[a].className = String(this.idPull[a].className).replace(oldSkin, this.conf.skin);
			
		}
	}
	this.setSkin(this.conf.skin);
	//
	
	this._addSubItemToSelected = function(item, polygon) {
		var t = true;
		for (var q=0; q<this.conf.selected_sub.length; q++) { if ((this.conf.selected_sub[q][0] == item) && (this.conf.selected_sub[q][1] == polygon)) { t = false; } }
		if (t == true) { this.conf.selected_sub.push(new Array(item, polygon)); }
		return t;
	}
	this._removeSubItemFromSelected = function(item, polygon) {
		var m = new Array();
		var t = false;
		for (var q=0; q<this.conf.selected_sub.length; q++) { if ((this.conf.selected_sub[q][0] == item) && (this.conf.selected_sub[q][1] == polygon)) { t = true; } else { m[m.length] = this.conf.selected_sub[q]; } }
		if (t == true) { this.conf.selected_sub = m; }
		return t;
	}
	this._getSubItemToDeselectByPolygon = function(polygon) {
		var m = new Array();
		for (var q=0; q<this.conf.selected_sub.length; q++) {
			if (this.conf.selected_sub[q][1] == polygon) {
				m[m.length] = this.conf.selected_sub[q][0];
				m = m.concat(this._getSubItemToDeselectByPolygon(this.conf.selected_sub[q][0]));
				var t = true;
				for (var w=0; w<this.conf.opened_poly.length; w++) { if (this.conf.opened_poly[w] == this.conf.selected_sub[q][0]) { t = false; } }
				if (t == true) { this.conf.opened_poly[this.conf.opened_poly.length] = this.conf.selected_sub[q][0]; }
				this.conf.selected_sub[q][0] = -1;
				this.conf.selected_sub[q][1] = -1;
			}
		}
		return m;
	}
	
	this._hidePolygon = function(id) {
		if (this.idPull["polygon_" + id] != null) {
			// clear z-index
			if (this.idPull["polygon_"+id]._zId != null) {
				window.dhx4.zim.clear(this.idPull["polygon_"+id]._zId);
			}
			//
			if (typeof(this._menuEffect) != "undefined" && this._menuEffect !== false) {
				this._hidePolygonEffect("polygon_"+id);
			} else {
				// already hidden
				if (this.idPull["polygon_"+id].style.display == "none") return;
				//
				this.idPull["polygon_"+id].style.display = "none";
				if (this.idPull["arrowup_"+id] != null) this.idPull["arrowup_"+id].style.display = "none";
				if (this.idPull["arrowdown_"+id] != null) this.idPull["arrowdown_"+id].style.display = "none";
				this._updateItemComplexState(id, true, false);
				// hide ie6 cover
				if (window.dhx4.isIE6 && this.idPull["polygon_"+id+"_ie6cover"] != null) this.idPull["polygon_"+id+"_ie6cover"].style.display = "none";
			}
			// call event
			id = String(id).replace(this.idPrefix, "");
			if (id == this.topId) id = null;
			this.callEvent("onHide", [id]);
			
			// corners
			if (id != null && this.conf.skin == "dhx_terrace" && this.itemPull[this.idPrefix+id].parent == this.idPrefix+this.topId) {
				this._improveTerraceButton(this.idPrefix+id, true);
			}
			
		}
	}
	
	this._showPolygon = function(id, openType) {
		
		var itemCount = this._countVisiblePolygonItems(id);
		if (itemCount == 0) return;
		var pId = "polygon_"+id;
		if ((this.idPull[pId] != null) && (this.idPull[id] != null)) {
			
			if (this.conf.top_mode && this.conf.mode == "web" && !this.conf.context) {
				if (!this.idPull[id]._mouseOver && openType == this.conf.dir_toplv) return;
			}
			
			// detect visible area
			if (!this.conf.fixed_pos) this._autoDetectVisibleArea();
			
			// show arrows
			var arrUpH = 0;
			var arrDownH = 0;
			//
			var arrowUp = null;
			var arrowDown = null;
			
			// show polygon
			if (this.idPull[pId]._zId == null) {
				this.idPull[pId]._zId = window.dhx4.newId();
			}
			this.idPull[pId]._zInd = window.dhx4.zim.reserve(this.idPull[pId]._zId);
			
			this.idPull[pId].style.visibility = "hidden";
			this.idPull[pId].style.left = "0px";
			this.idPull[pId].style.top = "0px";
			this.idPull[pId].style.display = "";
			this.idPull[pId].style.zIndex = this.idPull[pId]._zInd;
			
			//
			if (this.conf.auto_overflow) {
				if (this.idPull[pId].childNodes[1].childNodes[0].offsetHeight > this.conf.v.y2-this.conf.v.y1) {
					var t0 = Math.max(Math.floor((this.conf.v.y2-this.conf.v.y1-this.conf.of_ah[this.conf.skin]*2)/this.conf.of_ih[this.conf.skin]),1); // (y2-y1-arrow_height*2)/item_height
					this.conf.overflow_limit = t0;
				} else {
					this.conf.overflow_limit = 0;
					
					if (this.idPull["arrowup_"+id] != null) this._removeUpArrow(String(id).replace(this.idPrefix,""));
					if (this.idPull["arrowdown_"+id] != null) this._removeDownArrow(String(id).replace(this.idPrefix,""));
				}
			}
			
			if (this.conf.overflow_limit > 0 && this.conf.overflow_limit < itemCount)  {
				
				// add overflow arrows if they not exists
				if (this.idPull["arrowup_"+id] == null) this._addUpArrow(String(id).replace(this.idPrefix,""));
				if (this.idPull["arrowdown_"+id] == null) this._addDownArrow(String(id).replace(this.idPrefix,""));
				
				// configure up arrow
				arrowUp = this.idPull["arrowup_"+id];
				arrowUp.style.display = "none";
				//arrUpH = arrowUp.offsetHeight;
				
				// configure bottom arrow
				arrowDown = this.idPull["arrowdown_"+id];
				arrowDown.style.display = "none";
				//arrDownH = arrowDown.offsetHeight;
				
			}
			
			if (this.conf.overflow_limit > 0 && this.conf.overflow_limit < itemCount)  {
				// set fixed size
				this.idPull[pId].childNodes[1].style.height = this.conf.of_ih[this.conf.skin]*this.conf.overflow_limit+"px";
				arrowUp.style.width = arrowDown.style.width = this.idPull[pId].childNodes[1].style.width = this.idPull[pId].childNodes[1].childNodes[0].offsetWidth+"px";
				this.idPull[pId].childNodes[1].scrollTop = 0;
				
				arrowUp.style.display = "";
				arrUpH = arrowUp.offsetHeight;
				
				arrowDown.style.display = "";
				arrDownH = arrowDown.offsetHeight;
					
			} else {
				// remove fixed size
				this.idPull[pId].childNodes[1].style.height = "";
				this.idPull[pId].childNodes[1].style.width = "";
			}
			
			
			if (this.itemPull[id] != null) {
				var parPoly = "polygon_"+this.itemPull[id]["parent"];
			} else if (this.conf.context) {
				var parPoly = this.idPull[this.idPrefix+this.topId];
			}
			
			// define position
			var srcX = (this.idPull[id].tagName != null ? window.dhx4.absLeft(this.idPull[id]) : this.idPull[id][0]);
			var srcY = (this.idPull[id].tagName != null ? window.dhx4.absTop(this.idPull[id]) : this.idPull[id][1]);
			var srcW = (this.idPull[id].tagName != null ? this.idPull[id].offsetWidth : 0);
			var srcH = (this.idPull[id].tagName != null ? this.idPull[id].offsetHeight : 0);
			
			var x = 0;
			var y = 0;
			var w = this.idPull[pId].offsetWidth;
			var h = this.idPull[pId].offsetHeight;
			
			// pos
			if (openType == "bottom") {
				if (this.conf.rtl) {
					x = srcX + (srcW!=null?srcW:0) - w;
				} else {
					if (this.conf.align == "right") {
						x = srcX + srcW - w;
					} else {
						x = srcX - 1 + (openType==this.conf.dir_toplv?this.conf.tl_rmarg:0);
					}
				}
				y = srcY - 1 + srcH + this.conf.tl_botmarg;
			}
			if (openType == "right") { x = srcX + srcW - 1; y = srcY + 2; }
			if (openType == "left") { x = srcX - this.idPull[pId].offsetWidth + 2; y = srcY + 2; }
			if (openType == "top") { x = srcX - 1; y = srcY - h + 2; }
			
			// overflow check
			if (this.conf.fixed_pos) {
				// use fixed document.body/window dimension if required
				var mx = 65536;
				var my = 65536;
			} else {
				var mx = (this.conf.v.x2!=null?this.conf.v.x2:0);
				var my = (this.conf.v.y2!=null?this.conf.v.y2:0);
				
				if (mx == 0) {
					if (window.innerWidth) {
						mx = window.innerWidth;
						my = window.innerHeight;
					} else {
						mx = document.body.offsetWidth;
						my = document.body.scrollHeight;
					}
				}
			}
			if (x+w > mx && !this.conf.rtl) {
				// no space on right, open to left
				x = srcX - w + 2;
			}
			if (x < this.conf.v.x1 && this.conf.rtl) {
				// no space on left, open to right
				x = srcX + srcW - 2;
			}
			if (x < 0) {
				// menu floats left
				x = 0;
			}
			if (y+h > my && this.conf.v.y2 != null) {
				y = Math.max(srcY + srcH - h + 2, (this.conf.v_enabled?this.conf.v.y1+2:2));
				// open from top level
				if (this.conf.context && this.idPrefix+this.topId == id && arrowDown != null) {
					// autoscroll prevent because menu mouse pointer will right over downarrow
					y = y-2;
				}
				if (this.itemPull[id] != null && !this.conf.context) {
					if (this.itemPull[id]["parent"] == this.idPrefix+this.topId) y = y - this.base.offsetHeight;
				}
			}
			//
			this.idPull[pId].style.left = x+"px";
			//this.idPull[pId].style.top = y+arrUpH+"px";
			this.idPull[pId].style.top = y+"px";
			//
			if (typeof(this._menuEffect) != "undefined" && this._menuEffect !== false) {
				this._showPolygonEffect(pId);
			} else {
				this.idPull[pId].style.visibility = "";
				
				if (this.conf.overflow_limit > 0 && this.conf.overflow_limit < itemCount)  {
					this.idPull[pId].childNodes[1].scrollTop = 0;
					this._checkArrowsState(id);
				}
				
				// show ie6 cover
				if (window.dhx4.isIE6) {
					var pIdIE6 = pId+"_ie6cover";
					if (this.idPull[pIdIE6] == null) {
						var ifr = document.createElement("IFRAME");
						ifr.className = "dhtmlxMenu_IE6CoverFix_"+this.conf.skin;
						ifr.frameBorder = 0;
						ifr.setAttribute("src", "javascript:false;");
						document.body.insertBefore(ifr, document.body.firstChild);
						this.idPull[pIdIE6] = ifr;
					}
					this.idPull[pIdIE6].style.left = x+"px";
					this.idPull[pIdIE6].style.top = y+"px";
					this.idPull[pIdIE6].style.width = this.idPull[pId].offsetWidth+"px";
					this.idPull[pIdIE6].style.height = this.idPull[pId].offsetHeight+"px";
					this.idPull[pIdIE6].style.zIndex = this.idPull[pId].style.zIndex-1;
					this.idPull[pIdIE6].style.display = "";
				}
			}
			
			id = String(id).replace(this.idPrefix, "");
			if (id == this.topId) id = null;
			this.callEvent("onShow", [id]);
			
			// corners
			if (id != null && this.conf.skin == "dhx_terrace" && this.itemPull[this.idPrefix+id].parent == this.idPrefix+this.topId) {
				this._improveTerraceButton(this.idPrefix+id, false);
			}
			
		}
	}
	
	this._redistribSubLevelSelection = function(id, parentId) {
		// clear previosly selected items
		while (this.conf.opened_poly.length > 0) this.conf.opened_poly.pop();
		// this.conf.opened_poly = new Array();
		var i = this._getSubItemToDeselectByPolygon(parentId);
		this._removeSubItemFromSelected(-1, -1);
		for (var q=0; q<i.length; q++) { if ((this.idPull[i[q]] != null) && (i[q] != id)) { if (this.itemPull[i[q]]["state"] == "enabled") { this.idPull[i[q]].className = "sub_item"; } } }
		// hide polygons
		for (var q=0; q<this.conf.opened_poly.length; q++) { if (this.conf.opened_poly[q] != parentId) { this._hidePolygon(this.conf.opened_poly[q]); } }
		// add new selection into list new
		if (this.itemPull[id]["state"] == "enabled") {
			this.idPull[id].className = "sub_item_selected";
			if (this.itemPull[id]["complex"] && this.conf.dload && (this.itemPull[id]["loaded"]=="no")) {
				if (this.conf.dload_icon == true) { this._updateLoaderIcon(id, true); }
				this.itemPull[id].loaded = "get";
				var xmlParentId = id.replace(this.idPrefix,"");
				this._dhxdataload.onBeforeXLS = function() {
					var p = {params:{}};
					p.params[this.conf.dload_pid] = xmlParentId;
					for (var a in this.conf.dload_params) p.params[a] = this.conf.dload_params[a];
					return p;
				};
				this.loadStruct(this.conf.dload_url);
			}
			// show
			if (this.itemPull[id]["complex"] || (this.conf.dload && (this.itemPull[id]["loaded"] == "yes"))) {
				// make arrow over
				if ((this.itemPull[id]["complex"]) && (this.idPull["polygon_" + id] != null))  {
					this._updateItemComplexState(id, true, true);
					this._showPolygon(id, this.conf.dir_sublv);
				}
			}
			this._addSubItemToSelected(id, parentId);
			this.conf.selected = id;
		}
	}
	
	this._doOnClick = function(id, type, casState) {
		this.conf.last_click = id;
		// href
		if (this.itemPull[this.idPrefix+id]["href_link"] != null && this.itemPull[this.idPrefix+id].state == "enabled") {
			var form = document.createElement("FORM");
			var k = String(this.itemPull[this.idPrefix+id]["href_link"]).split("?");
			form.action = k[0];
			if (k[1] != null) {
				var p = String(k[1]).split("&");
				for (var q=0; q<p.length; q++) {
					var j = String(p[q]).split("=");
					var m = document.createElement("INPUT");
					m.type = "hidden";
					m.name = (j[0]||"");
					m.value = (j[1]||"");
					form.appendChild(m);
				}
			}
			if (this.itemPull[this.idPrefix+id]["href_target"] != null) { form.target = this.itemPull[this.idPrefix+id]["href_target"]; }
			form.style.display = "none";
			document.body.appendChild(form);
			form.submit();
			if (form != null) {
				document.body.removeChild(form);
				form = null;
			}
			return;
		}
		//
		// some fixes
		if (type.charAt(0)=="c") return; // can't click on complex item
		if (type.charAt(1)=="d") return; // can't click on disabled item
		if (type.charAt(2)=="s") return; // can't click on separator
		//
		if (this.checkEvent("onClick")) {
			this.callEvent("onClick", [id, this.conf.ctx_zoneid, casState]);
		} else {
			if ((type.charAt(1) == "d") || (this.conf.mode == "win" && type.charAt(2) == "t")) return;
		}
		if (this.conf.context && this._isContextMenuVisible() && this.conf.ctx_autohide) {
			this._hideContextMenu();
		} else {
			// if menu unloaded from click event
			if (this._clearAndHide) this._clearAndHide();
		}
	}
	// onTouchMenu action - select topLevel item
	this._doOnTouchMenu = function(id) {
		if (this.conf.is_touched == false) {
			this.conf.is_touched = true;
			if (this.checkEvent("onTouch")) {
				this.callEvent("onTouch", [id]);
			}
		}
	}
	
	// return menu array of all nested objects
	this._searchMenuNode = function(node, menu) {
		var m = new Array();
		for (var q=0; q<menu.length; q++) {
			if (typeof(menu[q]) == "object") {
				if (menu[q].length == 5) { if (typeof(menu[q][0]) != "object") { if ((menu[q][0].replace(this.idPrefix, "") == node) && (q == 0)) { m = menu; } } }
				var j = this._searchMenuNode(node, menu[q]);
				if (j.length > 0) { m = j; }
			}
		}
		return m;
	}
	// return array of subitems for single menu object
	this._getMenuNodes = function(node) {
		var m = new Array;
		for (var a in this.itemPull) { if (this.itemPull[a]["parent"] == node) { m[m.length] = a; } }
		return m;
	}
	// generate random string with specified length
	this._genStr = function(w) {
		var s = "dhxId_";
		var z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for (var q=0; q<w; q++) s += z.charAt(Math.round(Math.random() * (z.length-1)));
		return s;
	}
	
	this.getItemType = function(id) {
		id = this.idPrefix+id;
		if (this.itemPull[id] == null) { return null; }
		return this.itemPull[id]["type"];
	}
	
	this.forEachItem = function(handler) {
		for (var a in this.itemPull) { handler(String(a).replace(this.idPrefix, "")); }
	}
	
	// clear selection and hide menu on onbody click event
	this._clearAndHide = function() {
		that.conf.selected = -1;
		that.conf.last_click = -1;
		while (that.conf.opened_poly.length > 0) { that.conf.opened_poly.pop(); }
		for (var q=0; q<that.conf.selected_sub.length; q++) {
			var id = that.conf.selected_sub[q][0];
			// clear all selection
			if (that.idPull[id] != null) {
				if (that.itemPull[id]["state"] == "enabled") {
					if (that.idPull[id].className == "sub_item_selected") that.idPull[id].className = "sub_item";
					if (that.idPull[id].className == "dhtmlxMenu_"+that.conf.skin+"_TopLevel_Item_Selected") {
						// custom css
						if (that.itemPull[id]["cssNormal"] != null) {
							that.idPull[id].className = that.itemPull[id]["cssNormal"];
						} else {
							// default css
							that.idPull[id].className = "dhtmlxMenu_"+that.conf.skin+"_TopLevel_Item_Normal";
						}
					}
				}
			}
			that._hidePolygon(id);
		}
		
		that.conf.is_touched = false;
		
		// hide all contextmenu polygons on mouseout
		if (that.conf.context && that.conf.ctx_hideall) that._hidePolygon(that.idPrefix+that.topId);
		
	}
	
	/* show sublevel item */
	this._showSubLevelItem = function(id,back) {
		if (document.getElementById("arrow_" + this.idPrefix + id) != null) { document.getElementById("arrow_" + this.idPrefix + id).style.display = (back?"none":""); }
		if (document.getElementById("image_" + this.idPrefix + id) != null) { document.getElementById("image_" + this.idPrefix + id).style.display = (back?"none":""); }
		if (document.getElementById(this.idPrefix + id) != null) { document.getElementById(this.idPrefix + id).style.display = (back?"":"none"); }
	}
	/* hide sublevel item */
	this._hideSubLevelItem = function(id) {
		this._showSubLevelItem(id,true)
	}
	// generating id prefix
	this.idPrefix = this._genStr(12)+"_";
	
	/* attach body events */
	this._bodyClick = function(e) {
		e = e||event;
		if (e.button == 2 || (window.dhx4.isOpera && e.ctrlKey == true)) return;
		if (that.conf.context) {
			if (that.conf.ctx_autohide && (!window.dhx4.isOpera || (that._isContextMenuVisible() && window.dhx4.isOpera))) that._hideContextMenu();
		} else {
			if (that._clearAndHide) that._clearAndHide();
		}
	}
	this._bodyContext = function(e) {
		e = e||event;
		var t = String((e.srcElement||e.target).className);
		if (t.search("dhtmlxMenu") != -1 && t.search("SubLevelArea") != -1) return;
		var toHide = true;
		var testZone = e.target || e.srcElement;
		while (testZone != null) {
			if (testZone.id != null) if (that.isContextZone(testZone.id)) toHide = false;
			if (testZone == document.body) toHide = false;
			testZone = testZone.parentNode;
		}
		if (toHide) that.hideContextMenu();
	}
	
	if (typeof(window.addEventListener) != "undefined") {
		window.addEventListener("click", this._bodyClick, false);
		window.addEventListener("contextmenu", this._bodyContext, false);
	} else {
		document.body.attachEvent("onclick", this._bodyClick);
		document.body.attachEvent("oncontextmenu", this._bodyContext);
	}
	
	this.unload = function() {
		
		window.dhx4._eventable(this, "clear");
		
		// remove menu from global store
		dhtmlXMenuObject.prototype.liveInst[this.conf.live_id] = null;
		try { delete dhtmlXMenuObject.prototype.liveInst[this.conf.live_id]; } catch(e) {}
		this.conf.live_id = null;
		
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("click", this._bodyClick, false);
			window.removeEventListener("contextmenu", this._bodyContext, false);
		} else {
			document.body.detachEvent("onclick", this._bodyClick);
			document.body.detachEvent("oncontextmenu", this._bodyContext);
		}
		this._bodyClick = null;
		this._bodyContext = null;
		
		// will recursively remove all items
		this.removeItem(this.idPrefix+this.topId, true);
		
		this.itemPull = null;
		this.idPull = null;
		
		// clear context zones
		if (this.conf.context) for (var a in this.conf.ctx_zones) this.removeContextZone(a);
		
		if (this.cont != null) {
			this.cont.className = "";
			this.cont.parentNode.removeChild(this.cont);
			this.cont = null;
		}
		
		if (this.base != null) {
			if (!this.conf.context) this.base.className = "";
			if (!this.conf.context) this.base.oncontextmenu = (this.base._oldContextMenuHandler||null);
			this.base.onselectstart = null;
			this.base = null;
		}
		
		for (var a in this) this[a] = null;
		that = null;
		
	}
	
	// register instance
	dhtmlXMenuObject.prototype.liveInst[this.conf.live_id] = this;
	
	window.dhx4._enableDataLoading(this, "_initObj", "_xmlToJson", this.conf.tags.root, {struct:true});
	window.dhx4._eventable(this);
	
	// autoload
	if (window.dhx4.s2b(this.conf.autoload.context) == true) this.renderAsContextMenu();
	
	if (this.conf.autoload.dynamic != null) {
		this.enableDynamicLoading(this.conf.autoload.dynamic, window.dhx4.s2b(this.conf.autoload.dynamic_icon));
	} else if (this.conf.autoload.items != null) {
		this.loadStruct(this.conf.autoload.items, this.conf.autoload.onload);
	} else if (this.conf.autoload.json != null) {
		this.loadStruct(this.conf.autoload.json, this.conf.autoload.onload);
	} else if (this.conf.autoload.xml != null) {
		this.loadStruct(this.conf.autoload.xml, this.conf.autoload.onload);
	}
	
	for (var a in {onclick:1,oncheckboxclick:1,onradioclick:1}) {
		if (this.conf.autoload[a] != null) {
			if (typeof(this.conf.autoload[a]) == "function") {
				this.attachEvent(a, this.conf.autoload[a]);
			} else if (typeof(window[this.conf.autoload[a]]) == "function") {
				this.attachEvent(a, window[this.conf.autoload[a]]);
			}
		}
	}
	
	if (this.conf.autoload.top_text != null) this.setTopText(this.conf.autoload.top_text);
	if (this.conf.autoload.align != null) this.setAlign(this.conf.autoload.align);
	if (this.conf.autoload.open_mode != null) this.setOpenMode(this.conf.autoload.open_mode);
	if (this.conf.autoload.overflow != null) this.setOverflowHeight(this.conf.autoload.overflow);
	
	if (this.conf.autoload.iconset == "awesome") {
		this.conf.icons_css = true;
	}
	
	//
	for (var a in this.conf.autoload) {
		this.conf.autoload[a] = null;
		delete this.conf.autoload[a];
	}
	this.conf.autoload = null;
	
	//
	return this;
	
};

dhtmlXMenuObject.prototype._init = function() {
	if (this._isInited == true) return;
	if (this.conf.dload) {
		this._dhxdataload.onBeforeXLS = function() {
			var p = {params:{}};
			for (var a in this.conf.dload_params) p.params[a] = this.conf.dload_params[a];
			return p;
		};
		this.loadStruct(this.conf.dload_url);
	} else {
		this._initTopLevelMenu();
		this._isInited = true;
	}
};

dhtmlXMenuObject.prototype._countVisiblePolygonItems = function(id) {
	
	var count = 0;
	
	for (var a in this.itemPull) {
		
		var par = this.itemPull[a]["parent"];
		var tp = this.itemPull[a]["type"];
		if (this.idPull[a] != null) {
			if (par == id && (tp == "item" || tp == "radio" || tp == "checkbox") && this.idPull[a].style.display != "none") {
				count++;
			}
		}
	}
	return count;
};

dhtmlXMenuObject.prototype._redefineComplexState = function(id) {
	// alert(id)
	if (this.idPrefix+this.topId == id) { return; }
	if ((this.idPull["polygon_"+id] != null) && (this.idPull[id] != null)) {
		var u = this._countVisiblePolygonItems(id);
		if ((u > 0) && (!this.itemPull[id]["complex"])) { this._updateItemComplexState(id, true, false); }
		if ((u == 0) && (this.itemPull[id]["complex"])) { this._updateItemComplexState(id, false, false); }
	}
};

dhtmlXMenuObject.prototype._updateItemComplexState = function(id, state, over) {
	// 0.2 FIX :: topLevel's items can have complex items with arrow
	if ((!this.conf.context) && (this._getItemLevelType(id.replace(this.idPrefix,"")) == "TopLevel")) {
		// 30.06.2008 fix > complex state for top level item, state only, no arrow
		this.itemPull[id]["complex"] = state;
		return;
	}
	if ((this.idPull[id] == null) || (this.itemPull[id] == null)) { return; }
	// 0.2 FIX :: end
	this.itemPull[id]["complex"] = state;
	// fixed in 0.4 for context
	if (id == this.idPrefix+this.topId) return;
	// end fix
	// try to retrieve arrow img object
	var arrowObj = null;
	
	
	var item = this.idPull[id].childNodes[this.conf.rtl?0:2];
	if (item.childNodes[0]) if (String(item.childNodes[0].className).search("complex_arrow") === 0) arrowObj = item.childNodes[0];
	
	if (this.itemPull[id]["complex"]) {
		// create arrow
		if (arrowObj == null) {
			arrowObj = document.createElement("DIV");
			arrowObj.className = "complex_arrow";
			arrowObj.id = "arrow_"+id;
			while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
			item.appendChild(arrowObj);
		}
		// over state added in 0.4
		
		if (this.conf.dload && (this.itemPull[id].loaded == "get") && this.conf.dload_icon) {
			// change arrow to loader
			if (arrowObj.className != "complex_arrow_loading") arrowObj.className = "complex_arrow_loading";
		} else {
			arrowObj.className = "complex_arrow";
		}
		
		return;
	}
	
	if ((!this.itemPull[id]["complex"]) && (arrowObj!=null)) {
		item.removeChild(arrowObj);
		if (this.itemPull[id]["hotkey_backup"] != null && this.setHotKey) { this.setHotKey(id.replace(this.idPrefix, ""), this.itemPull[id]["hotkey_backup"]); }
	}
	
};

dhtmlXMenuObject.prototype._getItemLevelType = function(id) {
	return (this.itemPull[this.idPrefix+id]["parent"]==this.idPrefix+this.topId?"TopLevel":"SubLevelArea");
};

dhtmlXMenuObject.prototype.setIconsPath = function(path) {
	this.conf.icons_path = path;
};

/* real-time update icon in menu */
dhtmlXMenuObject.prototype._updateItemImage = function(id, levelType) {
	// search existsing image
	id = this.idPrefix+id;
	
	var tp = this.itemPull[id]["type"];
	if (tp == "checkbox" || tp == "radio") return;
	
	var isTopLevel = (this.itemPull[id]["parent"] == this.idPrefix+this.topId && !this.conf.context);
	
	// search existing image
	var imgObj = null;
	if (isTopLevel) {
		for (var q=0; q<this.idPull[id].childNodes.length; q++) {
			if (imgObj == null && (this.idPull[id].childNodes[q].className || "") == "dhtmlxMenu_TopLevel_Item_Icon" || (this.idPull[id].childNodes[q].tagName||"").toLowerCase() == "i") {
				imgObj = this.idPull[id].childNodes[q];
			}
		}
	} else {
		try { var imgObj = this.idPull[id].childNodes[this.conf.rtl?2:0].childNodes[0]; } catch(e) { }
		if (!(imgObj != null && typeof(imgObj.className) != "undefined" && (imgObj.className == "sub_icon" || imgObj.tagName.toLowerCase() == "i"))) imgObj = null;
	}
	
	var imgSrc = this.itemPull[id][(this.itemPull[id]["state"]=="enabled"?"imgen":"imgdis")];
	
	if (imgSrc.length > 0) {
		if (imgObj != null) {
			if (this.conf.icons_css == true) {
				imgObj.className = this.conf.icons_path+imgSrc;
			} else {
				imgObj.src = this.conf.icons_path+imgSrc;
			}
		} else {
			if (isTopLevel) {
				if (this.conf.icons_css == true) {
					var imgObj = document.createElement("i");
					imgObj.className = this.conf.icons_path+imgSrc;
				} else {
					var imgObj = document.createElement("IMG");
					imgObj.className = "dhtmlxMenu_TopLevel_Item_Icon";
					imgObj.src = this.conf.icons_path+imgSrc;
					imgObj.border = "0";
					imgObj.id = "image_"+id;
				}
				if (!this.conf.rtl && this.idPull[id].childNodes.length > 0) this.idPull[id].insertBefore(imgObj,this.idPull[id].childNodes[0]); else this.idPull[id].appendChild(imgObj);				
			} else {
				if (this.conf.icons_css == true) {
					var item = this.idPull[id].childNodes[this.conf.rtl?2:0];
					item.innerHTML = "<i class='"+this.conf.icons_path+imgSrc+"'></i>";
				} else {
					var imgObj = document.createElement("IMG");
					imgObj.className = "sub_icon";
					imgObj.src = this.conf.icons_path+imgSrc;
					imgObj.border = "0";
					imgObj.id = "image_"+id;
					var item = this.idPull[id].childNodes[this.conf.rtl?2:0];
					while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
					item.appendChild(imgObj);
				}
			}
		}
	} else {
		if (imgObj != null) {
			if (isTopLevel) {
				imgObj.parentNode.removeChild(imgObj);
				imgObj = null;
			} else {
				var p = imgObj.parentNode;
				p.removeChild(imgObj);
				p.innerHTML = "&nbsp;";
				p = imgObj = null;
			}
		}
	}
};

// collect parents for remove complex item
dhtmlXMenuObject.prototype._getAllParents = function(id) {
	var parents = new Array();
	for (var a in this.itemPull) {
		if (this.itemPull[a]["parent"] == id) {
			parents[parents.length] = this.itemPull[a]["id"];
			if (this.itemPull[a]["complex"]) {
				var t = this._getAllParents(this.itemPull[a]["id"]);
				for (var q=0; q<t.length; q++) { parents[parents.length] = t[q]; }
			}
		}
	}
	return parents;
};

// visible area
dhtmlXMenuObject.prototype._autoDetectVisibleArea = function() {
	if (this.conf.v_enabled) return;
	var d = window.dhx4.screenDim();
	this.conf.v.x1 = d.left;
	this.conf.v.x2 = d.right;
	this.conf.v.y1 = d.top;
	this.conf.v.y2 = d.bottom;
};

dhtmlXMenuObject.prototype.getItemPosition = function(id) {
	id = this.idPrefix+id;
	var pos = -1;
	if (this.itemPull[id] == null) return pos;
	var parent = this.itemPull[id]["parent"];
	// var obj = (this.idPull["polygon_"+parent]!=null?this.idPull["polygon_"+parent].tbd:this.base);
	var obj = (this.idPull["polygon_"+parent]!=null?this.idPull["polygon_"+parent].tbd:this.cont);
	for (var q=0; q<obj.childNodes.length; q++) { if (obj.childNodes[q]==this.idPull["separator_"+id]||obj.childNodes[q]==this.idPull[id]) { pos = q; } }
	return pos;
};

dhtmlXMenuObject.prototype.setItemPosition = function(id, pos) {
	id = this.idPrefix+id;
	if (this.idPull[id] == null) { return; }
	// added in 0.4
	var isOnTopLevel = (this.itemPull[id]["parent"] == this.idPrefix+this.topId);
	//
	var itemData = this.idPull[id];
	var itemPos = this.getItemPosition(id.replace(this.idPrefix,""));
	var parent = this.itemPull[id]["parent"];
	// var obj = (this.idPull["polygon_"+parent]!=null?this.idPull["polygon_"+parent].tbd:this.base);
	var obj = (this.idPull["polygon_"+parent]!=null?this.idPull["polygon_"+parent].tbd:this.cont);
	obj.removeChild(obj.childNodes[itemPos]);
	if (pos < 0) pos = 0;
	// added in 0.4
	if (isOnTopLevel && pos < 1) { pos = 1; }
	//
	if (pos < obj.childNodes.length) { obj.insertBefore(itemData, obj.childNodes[pos]); } else { obj.appendChild(itemData); }
};

dhtmlXMenuObject.prototype.getParentId = function(id) {
	id = this.idPrefix+id;
	if (this.itemPull[id] == null) { return null; }
	return ((this.itemPull[id]["parent"]!=null?this.itemPull[id]["parent"]:this.topId).replace(this.idPrefix,""));
};


// hide any opened polygons
dhtmlXMenuObject.prototype.hide = function() {
	this._clearAndHide();
};
dhtmlXMenuObject.prototype.clearAll = function() {
	this.removeItem(this.idPrefix+this.topId, true);
	this._isInited = false;
	this.idPrefix = this._genStr(12)+"_";
	this.itemPull = {};
};

// dhtmlxmenu global store
if (typeof(dhtmlXMenuObject.prototype.liveInst) == "undefined") {
	dhtmlXMenuObject.prototype.liveInst = {};
};

dhtmlXMenuObject.prototype.setIconset = function(name) {
	this.conf.icons_css = (name == "awesome");
};
// redistrib selection in case of top node in real-time mode
dhtmlXMenuObject.prototype._redistribTopLevelSelection = function(id, parent) {
	// kick polygons and decelect before selected menues
	var i = this._getSubItemToDeselectByPolygon("parent");
	this._removeSubItemFromSelected(-1, -1);
	for (var q=0; q<i.length; q++) {
		if (i[q] != id) { this._hidePolygon(i[q]); }
		if ((this.idPull[i[q]] != null) && (i[q] != id)) { this.idPull[i[q]].className = this.idPull[i[q]].className.replace(/Selected/g, "Normal"); }
	}
	// check if enabled
	if (this.itemPull[this.idPrefix+id]["state"] == "enabled") {
		this.idPull[this.idPrefix+id].className = "dhtmlxMenu_"+this.conf.skin+"_TopLevel_Item_Selected";
		//
		this._addSubItemToSelected(this.idPrefix+id, "parent");
		this.conf.selected = (this.conf.mode=="win"?(this.conf.selected!=-1?id:this.conf.selected):id);
		if ((this.itemPull[this.idPrefix+id]["complex"]) && (this.conf.selected != -1)) { this._showPolygon(this.idPrefix+id, this.conf.dir_toplv); }
	}
};

dhtmlXMenuObject.prototype._initTopLevelMenu = function() {
	
	this.conf.dir_toplv = "bottom";
	this.conf.dir_sublv = (this.conf.rtl?"left":"right");
	if (this.conf.context) {
		this.idPull[this.idPrefix+this.topId] = new Array(0,0);
		this._addSubMenuPolygon(this.idPrefix+this.topId, this.idPrefix+this.topId);
	} else {
		var m = this._getMenuNodes(this.idPrefix + this.topId);
		for (var q=0; q<m.length; q++) {
			if (this.itemPull[m[q]]["type"] == "item") this._renderToplevelItem(m[q], null);
			if (this.itemPull[m[q]]["type"] == "separator") this._renderSeparator(m[q], null);
		}
	}
};

// add top menu item, complex define that submenues are in presence
dhtmlXMenuObject.prototype._renderToplevelItem = function(id, pos) {
	var that = this;
	var m = document.createElement("DIV");
	m.id = id;
	// custom css
	if (this.itemPull[id]["state"] == "enabled" && this.itemPull[id]["cssNormal"] != null) {
		m.className = this.itemPull[id]["cssNormal"];
	} else {
		m.className = "dhtmlxMenu_"+this.conf.skin+"_TopLevel_Item_"+(this.itemPull[id]["state"]=="enabled"?"Normal":"Disabled");
	}
	
	// text
	if (this.itemPull[id]["title"] != "") {
		var t1 = document.createElement("DIV");
		t1.className = "top_level_text";
		t1.innerHTML = this.itemPull[id]["title"];
		m.appendChild(t1);
	}
	// tooltip
	if (this.itemPull[id]["tip"].length > 0) m.title = this.itemPull[id]["tip"];
	//
	// image in top level
	if ((this.itemPull[id]["imgen"]!="")||(this.itemPull[id]["imgdis"]!="")) {
		var imgTop=this.itemPull[id][(this.itemPull[id]["state"]=="enabled")?"imgen":"imgdis"];
		if (imgTop) {
			if (this.conf.icons_css == true) {
				var i = document.createElement("i");
				i.className = this.conf.icons_path+imgTop;
				if (m.childNodes.length > 0 && !this.conf.rtl) m.insertBefore(i, m.childNodes[0]); else m.appendChild(i);
			} else {
				var img = document.createElement("IMG");
				img.border = "0";
				img.id = "image_"+id;
				img.src = this.conf.icons_path+imgTop;
				img.className = "dhtmlxMenu_TopLevel_Item_Icon";
				if (m.childNodes.length > 0 && !this.conf.rtl) m.insertBefore(img, m.childNodes[0]); else m.appendChild(img);
			}
		}
	}
	m.onselectstart = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
	m.oncontextmenu = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
	// add container for top-level items if not exists yet
	if (!this.cont) {
		this.cont = document.createElement("DIV");
		this.cont.dir = "ltr";
		this.cont.className = (this.conf.align=="right"?"align_right":"align_left");
		this.base.appendChild(this.cont);
	}
	// insert
	
	if (pos != null) { pos++; if (pos < 0) pos = 0; if (pos > this.cont.childNodes.length - 1) pos = null; }
	if (pos != null) this.cont.insertBefore(m, this.cont.childNodes[pos]); else this.cont.appendChild(m);
	
	this.idPull[m.id] = m;
	// create submenues
	if (this.itemPull[id]["complex"] && (!this.conf.dload)) this._addSubMenuPolygon(this.itemPull[id]["id"], this.itemPull[id]["id"]);
	// events
	m.onmouseover = function() {
		if (that.conf.mode == "web") { window.clearTimeout(that.conf.tm_handler); }
		// kick polygons and decelect before selected menues
		var i = that._getSubItemToDeselectByPolygon("parent");
		that._removeSubItemFromSelected(-1, -1);
		for (var q=0; q<i.length; q++) {
			if (i[q] != this.id) { that._hidePolygon(i[q]); }
			if ((that.idPull[i[q]] != null) && (i[q] != this.id)) {
				// custom css
				if (that.itemPull[i[q]]["cssNormal"] != null) {
					that.idPull[i[q]].className = that.itemPull[i[q]]["cssNormal"];
				} else {
					if (that.idPull[i[q]].className == "sub_item_selected") that.idPull[i[q]].className = "sub_item";
					that.idPull[i[q]].className = that.idPull[i[q]].className.replace(/Selected/g, "Normal");
				}
			}
		}
		// check if enabled
		if (that.itemPull[this.id]["state"] == "enabled") {
			this.className = "dhtmlxMenu_"+that.conf.skin+"_TopLevel_Item_Selected";
			//
			that._addSubItemToSelected(this.id, "parent");
			that.conf.selected = (that.conf.mode=="win"?(that.conf.selected!=-1?this.id:that.conf.selected):this.id);
			if (that.conf.dload) {
				if (that.itemPull[this.id].loaded == "no") {
					this._dynLoadTM = new Date().getTime();
					that.itemPull[this.id].loaded = "get";
					var xmlParentId = this.id.replace(that.idPrefix,"");
					that._dhxdataload.onBeforeXLS = function() {
						var p = {params:{}};
						p.params[this.conf.dload_pid] = xmlParentId;
						for (var a in this.conf.dload_params) p.params[a] = this.conf.dload_params[a];
						return p;
					};
					that.loadStruct(that.conf.dload_url);
				}
				if (that.conf.top_mode && that.conf.mode == "web" && !that.conf.context) {
					this._mouseOver = true;
				}
			}
			if ((!that.conf.dload) || (that.conf.dload && (!that.itemPull[this.id]["loaded"] || that.itemPull[this.id]["loaded"]=="yes"))) {
				if ((that.itemPull[this.id]["complex"]) && (that.conf.selected != -1)) {
					if (that.conf.top_mode && that.conf.mode == "web" && !that.conf.context) {
						this._mouseOver = true;
						var showItemId = this.id;
						this._menuOpenTM = window.setTimeout(function(){that._showPolygon(showItemId, that.conf.dir_toplv);}, that.conf.top_tmtime);
					} else {
						that._showPolygon(this.id, that.conf.dir_toplv);
					}
				}
			}
		}
		that._doOnTouchMenu(this.id.replace(that.idPrefix, ""));
	}
	m.onmouseout = function() {
		if (!((that.itemPull[this.id]["complex"]) && (that.conf.selected != -1)) && (that.itemPull[this.id]["state"]=="enabled")) {
			// custom css
			
			if (that.itemPull[this.id]["cssNormal"] != null) {
				// alert(1)
				m.className = that.itemPull[this.id]["cssNormal"];
			} else {
				// default css
				m.className = "dhtmlxMenu_"+that.conf.skin+"_TopLevel_Item_Normal";
			}
		}
		if (that.conf.mode == "web") {
			window.clearTimeout(that.conf.tm_handler);
			that.conf.tm_handler = window.setTimeout(function(){that._clearAndHide();}, that.conf.tm_sec, "JavaScript");
		}
		if (that.conf.top_mode && that.conf.mode == "web" && !that.conf.context) {
			this._mouseOver = false;
			window.clearTimeout(this._menuOpenTM);
		}
	}
	m.onclick = function(e) {
		if (that.conf.mode == "web") { window.clearTimeout(that.conf.tm_handler); }
		// fix, added in 0.4
		if (that.conf.mode != "web" && that.itemPull[this.id]["state"] == "disabled") { return; }
		//
		e = e || event;
		e.cancelBubble = true;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		
		if (that.conf.mode == "win") {
			if (that.itemPull[this.id]["complex"]) {
				if (that.conf.selected == this.id) { that.conf.selected = -1; var s = false; } else { that.conf.selected = this.id; var s = true; }
				if (s) { that._showPolygon(this.id, that.conf.dir_toplv); } else { that._hidePolygon(this.id); }
			}
		}
		var tc = (that.itemPull[this.id]["complex"]?"c":"-");
		var td = (that.itemPull[this.id]["state"]!="enabled"?"d":"-");
		var cas = {"ctrl": e.ctrlKey, "alt": e.altKey, "shift": e.shiftKey};
		that._doOnClick(this.id.replace(that.idPrefix, ""), tc+td+"t", cas);
		return false;
	}
	
	if (this.conf.skin == "dhx_terrace") {
		this._improveTerraceSkin();
	}
};

// recursively creates and adds submenu polygon
dhtmlXMenuObject.prototype._addSubMenuPolygon = function(id, parentId) {
	var s = this._renderSublevelPolygon(id, parentId);
	var j = this._getMenuNodes(parentId);
	for (q=0; q<j.length; q++) { if (this.itemPull[j[q]]["type"] == "separator") { this._renderSeparator(j[q], null); } else { this._renderSublevelItem(j[q], null); } }
	if (id == parentId) { var level = "topLevel"; } else { var level = "subLevel"; }
	for (var q=0; q<j.length; q++) { if (this.itemPull[j[q]]["complex"]) { this._addSubMenuPolygon(id, this.itemPull[j[q]]["id"]); } }
};

// inner: add single subpolygon/item/separator
dhtmlXMenuObject.prototype._renderSublevelPolygon = function(id, parentId) {
	var s = document.createElement("DIV");
	s.className = "dhtmlxMenu_"+this.conf.skin+"_SubLevelArea_Polygon "+(this.conf.rtl?"dir_right":"");
	s.dir = "ltr";
	s.oncontextmenu = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; e.cancelBubble = true; return false; }
	s.id = "polygon_" + parentId;
	s.onclick = function(e) { e = e || event; e.cancelBubble = true; }
	s.style.display = "none";
	document.body.insertBefore(s, document.body.firstChild);
	//
	
	s.innerHTML = '<div style="position:relative;"></div>'+'<div style="position: relative; overflow:hidden;"></div>'+'<div style="position:relative;"></div>';
	
	var tbl = document.createElement("TABLE");
	tbl.className = "dhtmlxMebu_SubLevelArea_Tbl";
	tbl.cellSpacing = 0;
	tbl.cellPadding = 0;
	tbl.border = 0;
	var tbd = document.createElement("TBODY");
	tbl.appendChild(tbd);
	
	s.childNodes[1].appendChild(tbl);
	
	s.tbl = tbl;
	s.tbd = tbd;
	// polygon
	this.idPull[s.id] = s;
	if (this.sxDacProc != null) {
		this.idPull["sxDac_" + parentId] = new this.sxDacProc(s, s.className);
		if (window.dhx4.isIE) {
			this.idPull["sxDac_" + parentId]._setSpeed(this.dacSpeedIE);
			this.idPull["sxDac_" + parentId]._setCustomCycle(this.dacCyclesIE);
		} else {
			this.idPull["sxDac_" + parentId]._setSpeed(this.dacSpeed);
			this.idPull["sxDac_" + parentId]._setCustomCycle(this.dacCycles);
		}
	}
	return s;
};

dhtmlXMenuObject.prototype._renderSublevelItem = function(id, pos) {
	var that = this;
	
	var tr = document.createElement("TR");
	tr.className = (this.itemPull[id]["state"]=="enabled"?"sub_item":"sub_item_dis");
	
	// icon
	var t1 = document.createElement("TD");
	t1.className = "sub_item_icon";
	var tp = this.itemPull[id]["type"];
	var icon = this.itemPull[id][(this.itemPull[id]["state"]=="enabled"?"imgen":"imgdis")];
	if (icon != "") {
		if (tp=="checkbox"||tp=="radio") {
			var img = document.createElement("DIV");
			img.id = "image_"+this.itemPull[id]["id"];
			img.className = "sub_icon "+icon;
			t1.appendChild(img);
		}
		if (!(tp=="checkbox"||tp=="radio")) {
			if (this.conf.icons_css == true) {
				t1.innerHTML = "<i class='"+this.conf.icons_path+icon+"'></i>";
			} else {
				var img = document.createElement("IMG");
				img.id = "image_"+this.itemPull[id]["id"];
				img.className = "sub_icon";
				img.src = this.conf.icons_path+icon;
				t1.appendChild(img);
			}
		}
	} else {
		t1.innerHTML = "&nbsp;";
	}
	
	// text
	var t2 = document.createElement("TD");
	t2.className = "sub_item_text";
	if (this.itemPull[id]["title"] != "") {
		var t2t = document.createElement("DIV");
		t2t.className = "sub_item_text";
		t2t.innerHTML = this.itemPull[id]["title"];
		t2.appendChild(t2t);
	} else {
		t2.innerHTML = "&nbsp;";
	}
	
	// hotkey/sublevel arrow
	var t3 = document.createElement("TD");
	t3.className = "sub_item_hk";
	if (this.itemPull[id]["complex"]) {
		
		var arw = document.createElement("DIV");
		arw.className = "complex_arrow";
		arw.id = "arrow_"+this.itemPull[id]["id"];
		t3.appendChild(arw);
		
	} else {
		if (this.itemPull[id]["hotkey"].length > 0 && !this.itemPull[id]["complex"]) {
			var t3t = document.createElement("DIV");
			t3t.className = "sub_item_hk";
			t3t.innerHTML = this.itemPull[id]["hotkey"];
			t3.appendChild(t3t);
		} else {
			t3.innerHTML = "&nbsp;";
		}
	}
	tr.appendChild(this.conf.rtl?t3:t1);
	tr.appendChild(t2);
	tr.appendChild(this.conf.rtl?t1:t3);
	
	
	//
	tr.id = this.itemPull[id]["id"];
	tr.parent = this.itemPull[id]["parent"];
	// tooltip, added in 0.4
	if (this.itemPull[id]["tip"].length > 0) tr.title = this.itemPull[id]["tip"];
	//
	tr.onselectstart = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
	tr.onmouseover = function(e) {
		if (that.conf.hide_tm[this.id]) window.clearTimeout(that.conf.hide_tm[this.id]);
		if (that.conf.mode == "web") window.clearTimeout(that.conf.tm_handler);
		if (!this._visible) that._redistribSubLevelSelection(this.id, this.parent); // if not visible
		this._visible = true;
	}
	tr.onmouseout = function() {
		if (that.conf.mode == "web") {
			if (that.conf.tm_handler) window.clearTimeout(that.conf.tm_handler);
			that.conf.tm_handler = window.setTimeout(function(){if(that&&that._clearAndHide)that._clearAndHide();}, that.conf.tm_sec, "JavaScript");
		}
		var k = this;
		if (that.conf.hide_tm[this.id]) window.clearTimeout(that.conf.hide_tm[this.id]);
		that.conf.hide_tm[this.id] = window.setTimeout(function(){k._visible=false;}, 50);
	}
	tr.onclick = function(e) {
		// added in 0.4, preven complex closing if user event not defined
		if (!that.checkEvent("onClick") && that.itemPull[this.id]["complex"]) return;
		//
		e = e || event; e.cancelBubble = true;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		tc = (that.itemPull[this.id]["complex"]?"c":"-");
		td = (that.itemPull[this.id]["state"]=="enabled"?"-":"d");
		var cas = {"ctrl": e.ctrlKey, "alt": e.altKey, "shift": e.shiftKey};
		switch (that.itemPull[this.id]["type"]) {
			case "checkbox":
				that._checkboxOnClickHandler(this.id.replace(that.idPrefix, ""), tc+td+"n", cas);
				break;
			case "radio":
				that._radioOnClickHandler(this.id.replace(that.idPrefix, ""), tc+td+"n", cas);
				break;
			case "item":
				that._doOnClick(this.id.replace(that.idPrefix, ""), tc+td+"n", cas);
				break;
		}
		return false;
	}
	// add
	var polygon = this.idPull["polygon_"+this.itemPull[id]["parent"]];
	if (pos != null) { pos++; if (pos < 0) pos = 0; if (pos > polygon.tbd.childNodes.length - 1) pos = null; }
	if (pos != null && polygon.tbd.childNodes[pos] != null) polygon.tbd.insertBefore(tr, polygon.tbd.childNodes[pos]); else polygon.tbd.appendChild(tr);
	this.idPull[tr.id] = tr;
};

dhtmlXMenuObject.prototype._renderSeparator = function(id, pos) {
	var level = (this.conf.context?"SubLevelArea":(this.itemPull[id]["parent"]==this.idPrefix+this.topId?"TopLevel":"SubLevelArea"));
	if (level == "TopLevel" && this.conf.context) return;
	
	var that = this;
	
	if (level != "TopLevel") {
		var tr = document.createElement("TR");
		tr.className = "sub_sep";
		var td = document.createElement("TD");
		td.colSpan = "3";
		tr.appendChild(td);
	}
	
	var k = document.createElement("DIV");
	k.id = "separator_"+id;
	k.className = (level=="TopLevel"?"top_sep":"sub_sep");
	k.onselectstart = function(e) { e = e || event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	k.onclick = function(e) {
		e = e || event; e.cancelBubble = true;
		var cas = {"ctrl": e.ctrlKey, "alt": e.altKey, "shift": e.shiftKey};
		that._doOnClick(this.id.replace("separator_" + that.idPrefix, ""), "--s", cas);
	}
	if (level == "TopLevel") {
		if (pos != null) {
			pos++; if (pos < 0) { pos = 0; }
			// if (this.base.childNodes[pos] != null) { this.base.insertBefore(k, this.base.childNodes[pos]); } else { this.base.appendChild(k); }
			if (this.cont.childNodes[pos] != null) { this.cont.insertBefore(k, this.cont.childNodes[pos]); } else { this.cont.appendChild(k); }
		} else {
			// add as a last item
			// var last = this.base.childNodes[this.base.childNodes.length-1];
			var last = this.cont.childNodes[this.cont.childNodes.length-1];
			// if (String(last).search("TopLevel_Text") == -1) { this.base.appendChild(k); } else { this.base.insertBefore(k, last); }
			if (String(last).search("TopLevel_Text") == -1) { this.cont.appendChild(k); } else { this.cont.insertBefore(k, last); }
		}
		this.idPull[k.id] = k;
	} else {
		var polygon = this.idPull["polygon_"+this.itemPull[id]["parent"]];
		if (pos != null) { pos++; if (pos < 0) pos = 0; if (pos > polygon.tbd.childNodes.length - 1) pos = null; }
		if (pos != null && polygon.tbd.childNodes[pos] != null) polygon.tbd.insertBefore(tr, polygon.tbd.childNodes[pos]); else polygon.tbd.appendChild(tr);
		td.appendChild(k);
		this.idPull[k.id] = tr;
	}
};

dhtmlXMenuObject.prototype.addNewSeparator = function(nextToId, itemId) {
	itemId = this.idPrefix+(itemId!=null?itemId:this._genStr(24));
	var parentId = this.idPrefix+this.getParentId(nextToId);
	
	this._addItemIntoGlobalStrorage(itemId, parentId, "", "separator", false, "", "");
	this._renderSeparator(itemId, this.getItemPosition(nextToId));
};


dhtmlXMenuObject.prototype._initObj = function(items, nested, parentId) {
	
	if (!(items instanceof Array)) {
		parentId = items.parentId;
		if (parentId != null && String(parentId).indexOf(this.idPrefix) !== 0) parentId = this.idPrefix+String(parentId);
		items = items.items;
	}
	
	for (var q=0; q<items.length; q++) {
		
		// api-init, items w/o id
		if (typeof(items[q].id) == "undefined" || items[q].id == null) {
			items[q].id = this._genStr(24);
		}
		
		// empty text fix
		if (items[q].text == null) items[q].text = "";
		
		// api-init, add idPrefix
		if (String(items[q].id).indexOf(this.idPrefix) !== 0) {
			items[q].id = this.idPrefix+String(items[q].id);
		}
		
		var k = {type: "item", tip: "", hotkey: "", state: "enabled", imgen: "", imgdis: ""};
		for (var a in k) { if (typeof(items[q][a]) == "undefined") items[q][a] = k[a]; }
		
		//
		if (items[q].imgen == "" && items[q].img != null) items[q].imgen = items[q].img;
		if (items[q].imgdis == "" && items[q].img_disabled != null) items[q].imgdis = items[q].img_disabled;
		if (items[q].title == null && items[q].text != null) items[q].title = items[q].text;
		
		// hrefs
		if (items[q].href != null) {
			if (items[q].href.link != null) items[q].href_link = items[q].href.link;
			if (items[q].href.target != null) items[q].href_target = items[q].href.target;
		}
		
		// userdata
		if (items[q].userdata != null) {
			for (var a in items[q].userdata) this.userData[items[q].id+"_"+a] = items[q].userdata[a];
		}

		
		// en/dis
		if (typeof(items[q].enabled) != "undefined" && window.dhx4.s2b(items[q].enabled) == false) {
			items[q].state = "disabled";
		} else if (typeof(items[q].disabled) != "undefined" && window.dhx4.s2b(items[q].disabled) == true) {
			items[q].state = "disabled";
		}
		
		//
		if (typeof(items[q].parent) == "undefined") {
			items[q].parent = (parentId != null ? parentId : this.idPrefix+this.topId);
		}
		
		
		// checkbox
		if (items[q].type == "checkbox") {
			items[q].checked = window.dhx4.s2b(items[q].checked);
			items[q].imgen = items[q].imgdis = "chbx_"+(items[q].checked?"1":"0"); // set classname
		}
		// radio
		if (items[q].type == "radio") {
			items[q].checked = window.dhx4.s2b(items[q].checked);
			items[q].imgen = items[q].imgdis = "rdbt_"+(items[q].checked?"1":"0");
			// group
			if (typeof(items[q].group) == "undefined" || items[q].group == null) items[q].group = this._genStr(24);
			if (this.radio[items[q].group] == null) this.radio[items[q].group] = [];
			this.radio[items[q].group].push(items[q].id);
		}
		
		//
		this.itemPull[items[q].id] = items[q];
		if (items[q].items != null && items[q].items.length > 0) {
			this.itemPull[items[q].id].complex = true;
			this._initObj(items[q].items, true, items[q].id);
		} else if (this.conf.dload && items[q].complex == true) {
			this.itemPull[items[q].id].loaded = "no";
		}
		this.itemPull[items[q].id].items = null;
		
	}
	
	if (nested !== true) {
		if (this.conf.dload == true) {
			if (parentId == null) {
				this._initTopLevelMenu();
			} else {
				this._addSubMenuPolygon(parentId, parentId);
				if (this.conf.selected == parentId) {
					var isTop = (this.itemPull[parentId].parent == this.idPrefix+this.topId);
					var level = (isTop && !this.conf.context ? this.conf.dir_toplv:this.conf.dir_sublv);
					var isShow = false;
					if (isTop && this.conf.top_mode && this.conf.mode == "web" && !this.conf.context) {
						var item = this.idPull[parentId];
						if (item._mouseOver == true) {
							var delay = this.conf.top_tmtime - (new Date().getTime()-item._dynLoadTM);
							if (delay > 1) {
								var pId = parentId;
								var that = this;
								item._menuOpenTM = window.setTimeout(function(){
									that._showPolygon(pId, level);
									that = pId = null;
								}, delay);
								isShow = true;
							}
						}
					}
					if (!isShow) this._showPolygon(parentId, level);
				}
				
				this.itemPull[parentId].loaded = "yes";
				if (this.conf.dload_icon == true) this._updateLoaderIcon(parentId, false);
			}
		} else {
			this._init();
		}
	}
	
};

dhtmlXMenuObject.prototype._xmlToJson = function(xml, parentId) {
	
	var items = [];
	
	if (parentId == null) {
		var root = xml.getElementsByTagName(this.conf.tags.root);
		if (root == null || (root != null && root.length == 0)) return {items:[]};
		root = root[0];
	} else {
		root = xml;
	}
	
	if (root.getAttribute("parentId") != null) {
		parentId = this.idPrefix+root.getAttribute("parentId");
	}
	
	for (var q=0; q<root.childNodes.length; q++) {
		if (typeof(root.childNodes[q].tagName) != "undefined" && String(root.childNodes[q].tagName).toLowerCase() == this.conf.tags.item) {
			var r = root.childNodes[q];
			var item = {
				// basic
				id: this.idPrefix+(r.getAttribute("id")||this._genStr(24)),
				title: r.getAttribute("text")||"",
				// images
				imgen: r.getAttribute("img")||"",
				imgdis: r.getAttribute("imgdis")||"",
				tip: "",
				hotkey: "",
				//
				type: r.getAttribute("type")||"item"
			};
			// custom css
			if (r.getAttribute("cssNormal") != null) {
				item.cssNormal = r.getAttribute("cssNormal");
			}
			// checkbox
			if (item.type == "checkbox") item.checked = r.getAttribute("checked");
			// radio
			if (item.type == "radio") {
				item.checked = r.getAttribute("checked");
				item.group = r.getAttribute("group");
			}
			// en/dis
			item.state = "enabled";
			if (r.getAttribute("enabled") != null && window.dhx4.s2b(r.getAttribute("enabled")) == false) {
				item.state = "disabled";
			} else if (r.getAttribute("disabled") != null && window.dhx4.s2b(r.getAttribute("disabled")) == true) {
				item.state = "disabled";
			}
			
			item.parent = (parentId != null ? parentId : this.idPrefix+this.topId);
			// is complex item
			if (this.conf.dload) {
				item.complex = (r.getAttribute("complex") != null);
				if (item.complex) item.loaded = "no";
			} else {
				var i = this._xmlToJson(r, item.id);
				item.items = i.items;
				item.complex = (item.items.length > 0);
			}
			
			// misc
			for (var w=0; w<r.childNodes.length; w++) {
				if (typeof(r.childNodes[w].tagName) != "undefined") {
					var t = String(r.childNodes[w].tagName||"").toLowerCase();
					// userdata
					if (t == this.conf.tags.userdata) {
						var d = r.childNodes[w];
						if (d.getAttribute("name") != null) {
							this.userData[item.id+"_"+d.getAttribute("name")] = (d.firstChild != null && d.firstChild.nodeValue != null ? d.firstChild.nodeValue : "");
						}
					}
					// extended text
					if (t == this.conf.tags.text_ext) {
						item.title = r.childNodes[w].firstChild.nodeValue;
					}
					// tooltips
					if (t == this.conf.tags.tooltip) {
						item.tip = r.childNodes[w].firstChild.nodeValue;
					}
					// hotkeys
					if (t == this.conf.tags.hotkey) {
						item.hotkey = r.childNodes[w].firstChild.nodeValue;
					}
					// hrefs
					if (t == this.conf.tags.href && item.type == "item") {
						item.href_link = r.childNodes[w].firstChild.nodeValue;
						if (r.childNodes[w].getAttribute("target") != null) {
							item.href_target = r.childNodes[w].getAttribute("target");
						}
					}
				}
			}
			items.push(item);
		}
	}
	
	var r = {
		parentId: parentId,//(root.getAttribute("parentId")||null),
		items: items
	};
	
	return r;
};

// dynload
dhtmlXMenuObject.prototype.enableDynamicLoading = function(url, icon) {
	this.conf.dload = true;
	this.conf.dload_url = url;
	this.conf.dload_sign = (String(this.conf.dload_url).search(/\?/)==-1?"?":"&");
	this.conf.dload_icon = icon;
	this._init();
};

dhtmlXMenuObject.prototype._updateLoaderIcon = function(id, state) {
	
	if (this.idPull[id] == null) return;
	if (String(this.idPull[id].className).search("TopLevel_Item") >= 0) return;
	// get arrow
	var ind = (this.conf.rtl?0:2);
	if (!this.idPull[id].childNodes[ind]) return;
	if (!this.idPull[id].childNodes[ind].childNodes[0]) return;
	var aNode = this.idPull[id].childNodes[ind].childNodes[0];
	if (String(aNode.className).search("complex_arrow") === 0) aNode.className = "complex_arrow"+(state?"_loading":"");

};



// add/remove
dhtmlXMenuObject.prototype.addNewSibling = function(nextToId, itemId, itemText, disabled, imgEnabled, imgDisabled) {
	var id = this.idPrefix+(itemId!=null?itemId:this._genStr(24));
	var parentId = this.idPrefix+(nextToId!=null?this.getParentId(nextToId):this.topId);
	
	this._addItemIntoGlobalStrorage(id, parentId, itemText, "item", disabled, imgEnabled, imgDisabled);
	if ((parentId == this.idPrefix+this.topId) && (!this.conf.context)) {
		this._renderToplevelItem(id, this.getItemPosition(nextToId));
	} else {
		this._renderSublevelItem(id, this.getItemPosition(nextToId));
	}
};

dhtmlXMenuObject.prototype.addNewChild = function(parentId, pos, itemId, itemText, disabled, imgEnabled, imgDisabled) {
	if (parentId == null) {
		if (this.conf.context) {
			parentId = this.topId;
		} else {
			this.addNewSibling(parentId, itemId, itemText, disabled, imgEnabled, imgDisabled);
			if (pos != null) this.setItemPosition(itemId, pos);
			return;
		}
	}
	itemId = this.idPrefix+(itemId!=null?itemId:this._genStr(24));
	// remove hotkey, added in 0.4
	if (this.setHotKey) this.setHotKey(parentId, "");
	//
	parentId = this.idPrefix+parentId;
	this._addItemIntoGlobalStrorage(itemId, parentId, itemText, "item", disabled, imgEnabled, imgDisabled);
	if (this.idPull["polygon_"+parentId] == null) { this._renderSublevelPolygon(parentId, parentId); }
	this._renderSublevelItem(itemId, pos-1);
	
	this._redefineComplexState(parentId);
};

dhtmlXMenuObject.prototype.removeItem = function(id, _isTId, _recCall) {
	if (!_isTId) id = this.idPrefix + id;
	
	var pId = null;
	
	if (id != this.idPrefix+this.topId) {
		
		if (this.itemPull[id] == null) return;
		
		// effects
		if (this.idPull["polygon_"+id] && this.idPull["polygon_"+id]._tmShow) window.clearTimeout(this.idPull["polygon_"+id]._tmShow);
		
		// separator top
		var t = this.itemPull[id]["type"];
		
		if (t == "separator") {
			var item = this.idPull["separator_"+id];
			if (this.itemPull[id]["parent"] == this.idPrefix+this.topId) {
				item.onclick = null;
				item.onselectstart = null;
				item.id = null;
				item.parentNode.removeChild(item);
			} else {
				item.childNodes[0].childNodes[0].onclick = null;
				item.childNodes[0].childNodes[0].onselectstart = null;
				item.childNodes[0].childNodes[0].id = null;
				item.childNodes[0].removeChild(item.childNodes[0].childNodes[0]);
				item.removeChild(item.childNodes[0]);
				item.parentNode.removeChild(item);
			}
			this.idPull["separator_"+id] = null;
			this.itemPull[id] = null;
			delete this.idPull["separator_"+id];
			delete this.itemPull[id];
			item = null;
		} else {
			// item checkbox radio
			pId = this.itemPull[id]["parent"];
			var item = this.idPull[id];
			item.onclick = null;
			item.oncontextmenu = null;
			item.onmouseover = null;
			item.onmouseout = null;
			item.onselectstart = null;
			item.id = null;
			while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
			item.parentNode.removeChild(item);
			this.idPull[id] = null;
			this.itemPull[id] = null;
			delete this.idPull[id];
			delete this.itemPull[id];
			item = null;
			
		}
		t = null;
	}
	
	// clear nested items
	for (var a in this.itemPull) if (this.itemPull[a]["parent"] == id) this.removeItem(a, true, true);
	
	// check if empty polygon left
	var p2 = new Array(id);
	if (pId != null && !_recCall) {
		if (this.idPull["polygon_"+pId] != null) {
			if (this.idPull["polygon_"+pId].tbd.childNodes.length == 0) {
				p2.push(pId);
				this._updateItemComplexState(pId, false, false);
			}
		}
	}
	
	// delete nested polygons and parent's if any
	for (var q=0; q<p2.length; q++) {
		if (this.idPull["polygon_"+p2[q]]) {
			var p = this.idPull["polygon_"+p2[q]];
			p.onclick = null;
			p.oncontextmenu = null;
			p.tbl.removeChild(p.tbd);
			p.tbd = null;
			p.childNodes[1].removeChild(p.tbl);
			p.tbl = null;
			p.id = null;
			p.parentNode.removeChild(p);
			p = null;
			if (window.dhx4.isIE6) {
				var pc = "polygon_"+p2[q]+"_ie6cover";
				if (this.idPull[pc] != null) { document.body.removeChild(this.idPull[pc]); delete this.idPull[pc]; }
			}
			if (this.idPull["arrowup_"+id] != null && this._removeArrow) this._removeArrow("arrowup_"+id);
			if (this.idPull["arrowdown_"+id] != null && this._removeArrow) this._removeArrow("arrowdown_"+id);
			//
			this.idPull["polygon_"+p2[q]] = null;
			delete this.idPull["polygon_"+p2[q]];
		}
	}
	p2 = null;
	
	// update corners
	if (this.conf.skin == "dhx_terrace" && arguments.length == 1) this._improveTerraceSkin();
	
};


// add item to storage
dhtmlXMenuObject.prototype._addItemIntoGlobalStrorage = function(itemId, itemParentId, itemText, itemType, disabled, img, imgDis) {
	var item = {
		id:	itemId,
		title:	itemText,
		imgen:	(img!=null?img:""),
		imgdis:	(imgDis!=null?imgDis:""),
		type:	itemType,
		state:	(disabled==true?"disabled":"enabled"),
		parent:	itemParentId,
		complex:false,
		hotkey:	"",
		tip:	""};
	this.itemPull[item.id] = item;
};

dhtmlXMenuObject.prototype.renderAsContextMenu = function() {
	this.conf.context = true;
	if (this.base._autoSkinUpdate == true) {
		this.base.className = this.base.className.replace("dhtmlxMenu_"+this.conf.skin+"_Middle","");
		this.base._autoSkinUpdate = false;
	}
	if (this.conf.ctx_baseid != null) { this.addContextZone(this.conf.ctx_baseid); }
};

dhtmlXMenuObject.prototype.addContextZone = function(zoneId) {
	if (zoneId == document.body) {
		zoneId = "document.body."+this.idPrefix;
		var zone = document.body;
	} else if (typeof(zoneId) == "string") {
		var zone = document.getElementById(zoneId);
	} else {
		var zone = zoneId;
	}
	var zoneExists = false;
	for (var a in this.conf.ctx_zones) { zoneExists = zoneExists || (a == zoneId) || (this.conf.ctx_zones[a] == zone); }
	if (zoneExists == true) return false;
	this.conf.ctx_zones[zoneId] = zone;
	var that = this;
	if (window.dhx4.isOpera) {
		this.operaContext = function(e){ that._doOnContextMenuOpera(e, that); }
		zone.addEventListener("mouseup", this.operaContext, false);
		//
	} else {
		if (zone.oncontextmenu != null && !zone._oldContextMenuHandler) zone._oldContextMenuHandler = zone.oncontextmenu;
		zone.oncontextmenu = function(e) {
			// autoclose any other opened context menues
			for (var q in dhtmlXMenuObject.prototype.liveInst) {
				if (q != that.conf.live_id) {
					if (dhtmlXMenuObject.prototype.liveInst[q].context) {
						dhtmlXMenuObject.prototype.liveInst[q]._hideContextMenu();
					}
				}
			}
			//
			e = e||event;
			e.cancelBubble = true;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			that._doOnContextBeforeCall(e, this);
			return false;
		}
	}
};
dhtmlXMenuObject.prototype._doOnContextMenuOpera = function(e, that) {
	// autoclose any other opened context menues
	for (var q in dhtmlXMenuObject.prototype.liveInst) {
		if (q != that.conf.live_id) {
			if (dhtmlXMenuObject.prototype.liveInst[q].context) {
				dhtmlXMenuObject.prototype.liveInst[q]._hideContextMenu();
			}
		}
	}
	//
	e.cancelBubble = true;
	if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
	if (e.button == 0 && e.ctrlKey == true) { that._doOnContextBeforeCall(e, this); }
	return false;
};

dhtmlXMenuObject.prototype.removeContextZone = function(zoneId) {
	if (!this.isContextZone(zoneId)) return false;
	if (zoneId == document.body) zoneId = "document.body."+this.idPrefix;
	var zone = this.conf.ctx_zones[zoneId];
	if (window.dhx4.isOpera) {
		zone.removeEventListener("mouseup", this.operaContext, false);
	} else {
		zone.oncontextmenu = (zone._oldContextMenuHandler!=null?zone._oldContextMenuHandler:null);
		zone._oldContextMenuHandler = null;
	}
	try {
		this.conf.ctx_zones[zoneId] = null;
		delete this.conf.ctx_zones[zoneId];
 	} catch(e){}
	return true;
};

dhtmlXMenuObject.prototype.isContextZone = function(zoneId) {
	if (zoneId == document.body && this.conf.ctx_zones["document.body."+this.idPrefix] != null) return true;
	var isZone = false;
	if (this.conf.ctx_zones[zoneId] != null) { if (this.conf.ctx_zones[zoneId] == document.getElementById(zoneId)) isZone = true; }
	return isZone;
};
dhtmlXMenuObject.prototype._isContextMenuVisible = function() {
	if (this.idPull["polygon_"+this.idPrefix+this.topId] == null) return false;
	return (this.idPull["polygon_"+this.idPrefix+this.topId].style.display == "");
};
dhtmlXMenuObject.prototype._showContextMenu = function(x, y, zoneId) {
	// hide any opened context menu/polygons
	this._clearAndHide();
	// open
	if (this.idPull["polygon_"+this.idPrefix+this.topId] == null) return false;
	window.clearTimeout(this.conf.tm_handler);
	this.idPull[this.idPrefix+this.topId] = new Array(x, y);
	this._showPolygon(this.idPrefix+this.topId, "bottom");
	this.callEvent("onContextMenu", [zoneId]);
};
dhtmlXMenuObject.prototype._hideContextMenu = function() {
	if (this.idPull["polygon_"+this.idPrefix+this.topId] == null) return false;
	this._clearAndHide();
	this._hidePolygon(this.idPrefix+this.topId);
};

dhtmlXMenuObject.prototype._doOnContextBeforeCall = function(e, cZone) {
	this.conf.ctx_zoneid = cZone.id;
	this._clearAndHide();
	this._hideContextMenu();
	
	// scroll settings
	if (window.dhx4.isChrome == true || window.dhx4.isEdge == true || window.dhx4.isOpera == true || window.dhx4.isIE11 == true) {
		var mx = window.dhx4.absLeft(e.target)+e.offsetX;
		var my = window.dhx4.absTop(e.target)+e.offsetY;
	} else if (window.dhx4.isIE6 == true || window.dhx4.isIE7 == true || window.dhx4.isIE == true) { // old IE or emulation
		var mx = window.dhx4.absLeft(e.srcElement)+e.x||0;
		var my = window.dhx4.absTop(e.srcElement)+e.y||0;
	} else { // the rest
		var p = (e.srcElement||e.target);
		var px = (window.dhx4.isIE||window.dhx4.isKHTML?e.offsetX:e.layerX);
		var py = (window.dhx4.isIE||window.dhx4.isKHTML?e.offsetY:e.layerY);
		var mx = window.dhx4.absLeft(p)+px;
		var my = window.dhx4.absTop(p)+py;
	}
	
	if (this.checkEvent("onBeforeContextMenu")) {
		if (this.callEvent("onBeforeContextMenu", [cZone.id,e])) {
			if (this.conf.ctx_autoshow) {
				this._showContextMenu(mx, my, cZone.id);
				this.callEvent("onAfterContextMenu", [cZone.id,e]);
			}
		}
	} else {
		if (this.conf.ctx_autoshow) {
			this._showContextMenu(mx, my, cZone.id);
			this.callEvent("onAfterContextMenu", [cZone.id]);
		}
	}
};

dhtmlXMenuObject.prototype.showContextMenu = function(x, y) {
	this._showContextMenu(x, y, false);
};

dhtmlXMenuObject.prototype.hideContextMenu = function() {
	this._hideContextMenu();
};

dhtmlXMenuObject.prototype.setAutoShowMode = function(mode) {
	this.conf.ctx_autoshow = (mode==true?true:false);
};

dhtmlXMenuObject.prototype.setAutoHideMode = function(mode) {
	this.conf.ctx_autohide = (mode==true?true:false);
};

dhtmlXMenuObject.prototype.setContextMenuHideAllMode = function(mode) {
	this.conf.ctx_hideall = (mode==true?true:false);
};

dhtmlXMenuObject.prototype.getContextMenuHideAllMode = function() {
	return this.conf.ctx_hideall;
};

dhtmlXMenuObject.prototype._improveTerraceSkin = function() {
	
	for (var a in this.itemPull) {
		
		if (this.itemPull[a].parent == this.idPrefix+this.topId && this.idPull[a] != null) { // this.idPull[a] will null for separator
			
			var bl = false;
			var br = false;
			
			// left side, first item, not sep
			if (this.idPull[a].parentNode.firstChild == this.idPull[a]) {
				bl = true;
			}
			
			// right side, last item, not sep
			if (this.idPull[a].parentNode.lastChild == this.idPull[a]) {
				br = true;
			}
			
			// check siblings
			for (var b in this.itemPull) {
				if (this.itemPull[b].type == "separator" && this.itemPull[b].parent == this.idPrefix+this.topId) {
					if (this.idPull[a].nextSibling == this.idPull["separator_"+b]) {
						br = true;
					}
					if (this.idPull[a].previousSibling == this.idPull["separator_"+b]) {
						bl = true;
					}
				}
			}
			
			this.idPull[a].style.borderLeftWidth = (bl?"1px":"0px");
			this.idPull[a].style.borderTopLeftRadius = this.idPull[a].style.borderBottomLeftRadius = (bl?"3px":"0px");
			
			this.idPull[a].style.borderTopRightRadius = this.idPull[a].style.borderBottomRightRadius = (br?"3px":"0px");
			
			this.idPull[a]._bl = bl;
			this.idPull[a]._br = br;
			
		}
	}
	
};

dhtmlXMenuObject.prototype._improveTerraceButton = function(id, state) {
	if (state) {
		this.idPull[id].style.borderBottomLeftRadius = (this.idPull[id]._bl ? "3px" : "0px");
		this.idPull[id].style.borderBottomRightRadius = (this.idPull[id]._br ? "3px" : "0px");
	} else {
		this.idPull[id].style.borderBottomLeftRadius = "0px";
		this.idPull[id].style.borderBottomRightRadius = "0px";
	}
};

if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype._createNode_menu = function(obj, type, htmlString, append, node) {
		
		if (typeof(node) != "undefined") {
			obj = node;
		} else {
			obj = document.createElement("DIV");
			obj.className = "dhx_cell_menu_"+(this.conf.borders?"def":"no_borders");
			obj.appendChild(document.createElement("DIV"));
		}
		
		this.cell.insertBefore(obj, this.cell.childNodes[this.conf.idx.toolbar||this.conf.idx.cont]); // before toolbar or before cont, 0=hdr
		
		this.conf.ofs_nodes.t.menu = true;
		this._updateIdx();
		// adjust cont will performed after toolbar init
		
		return obj;
		
	};
	
	dhtmlXCellObject.prototype.attachMenu = function(conf) {
		
		if (this.dataNodes.menu) return; // return this.dataNodes.menu?
		
		this.callEvent("_onBeforeContentAttach", ["menu"]);
		
		if (typeof(conf) == "undefined") conf = {};
		if (typeof(conf.skin) == "undefined") conf.skin = this.conf.skin;
		conf.parent = this._attachObject("menu").firstChild;
		
		this.dataNodes.menu = new dhtmlXMenuObject(conf);
		this._adjustCont(this._idd);
		
		conf.parent = null;
		conf = null;
		
		this.callEvent("_onContentAttach", []);
		
		return this.dataNodes.menu;
		
	};
	
	dhtmlXCellObject.prototype.detachMenu = function() {
		
		if (!this.dataNodes.menu) return;
		this.dataNodes.menu.unload();
		this.dataNodes.menu = null;
		delete this.dataNodes.menu;
		
		this._detachObject("menu");
		
	};
	
	dhtmlXCellObject.prototype.showMenu = function() {
		this._mtbShowHide("menu", "");
	};
	
	dhtmlXCellObject.prototype.hideMenu = function() {
		this._mtbShowHide("menu", "none");
	};
	
	dhtmlXCellObject.prototype.getAttachedMenu = function() {
		return this.dataNodes.menu;
	};
	
}

