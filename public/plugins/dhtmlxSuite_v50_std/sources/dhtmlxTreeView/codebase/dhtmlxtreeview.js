/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXTreeView(conf) {
	
	// console.log("add cache for kids for removeItem");
	// console.log("add unload");
	
	var that = this;
	
	if (typeof(conf) == "object" && conf.tagName == null) {
		// api-init
	} else {
		conf = {parent: conf, clear: true};
	}
	
	this.base = (typeof(conf.parent)=="string"?document.getElementById(conf.parent):conf.parent);
	
	if (this.base != document.body) {
		while (this.base.childNodes.length > 0) this.base.removeChild(this.base.lastChild);
	}
	
	this.conf = {
		skin: (conf.skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxtreeview")||"material"),
	        tree_id: window.dhx4.newId(), // register tree in common pull
	        ofs: {w: 1, h: 0}, // skyblue only
		adjust_base: false,
		icons: this.icons[(typeof(conf.iconset) == "string" && this.icons[conf.iconset] != null && this.icons[conf.iconset].r == true ? conf.iconset : "tree_native")],
		autoload: {
			url: null, // will set automaticaly from 1st loadStruct
			mode: "id" // user function allowed here
		},
		selected: {},
		ud: {}, // usersdata
		idx: {sign:0,icon:1,text:2}, // icons index
		silent: false, // do not callEvent if true
		// macos related for selection
		is_mac: (navigator.platform.match(/^mac/i) != null && typeof(window.addEventListener) == "function"),
		mac_cmd_key: false
	};
	
	this.setSkin(this.conf.skin);
	
	this.cont = document.createElement("DIV");
	this.cont.className = "dhxtreeview_cont";
	this.base.appendChild(this.cont);
	
	this.area = document.createElement("DIV");
	this.area.className = "dhxtreeview_area";
	this.cont.appendChild(this.area);
	
	this.cont.onclick = function(e) {
		e = e||event;
		that.callEvent("_onTreeClick", [e, {stop:false}]);
	}
	
	this.cont.ondblclick = function(e) {
		e = e||event;
		var t = (e.target||e.srcElement);
		if (t.className.match(/dhxtreeview_item_label/) != null) {
			that._openCloseItem(t.parentNode.parentNode._itemId, true);
		}
	}
	
	
	this.items = {};
	
	this._addItem = function(id, pId, data, index) {
		
		var level = (pId!=null?this.items[pId].level+1:1);
		
		var t = document.createElement("DIV");
		t.className = "dhxtreeview_item";
		t.innerHTML = "<div class='dhxtreeview_item_text'></div>";
		
		if (index != null && index < 0) data.index = 0;
		
		if (pId == null) {
			var node = this.area;
		} else {
			var k = this.items[pId].kids;
			if (k == false) this._initKidsNode(pId);
			this.items[pId].kids_request = false; // do not use dyn load if at least 1 child is present
			var node = this.items[pId].item.lastChild.firstChild;
		}
		
		if (index != null && node.childNodes[index] != null) {
			node.insertBefore(t, node.childNodes[index]);
		} else {
			node.appendChild(t);
		}
		node = null;
		
		t._itemId = id;
		t._treeId = this.conf.tree_id;
		
		if (window.dhx4.isIE == true) {
			t.onselectstart = function(e){
				e = e||event;
				if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			}
		}
		
		this.items[id] = {
			id: id,
			pId: pId,
			treeId: this.conf.tree_id,
			text: data.text,
			item: t,
			level: level,
			kids: false, // true/false if any kid
			opened: window.dhx4.s2b(data.open),
			userdata: window.dhx4._copyObj(data.userdata||{}),
			half_opened: false // true/false to change sign only, used for dyn.load
		};
		
		this.callEvent("_onItemRendered", [id, data]);
		
		t.firstChild.innerHTML = this._getItemHtml(id);
		t = null;
		
		// pre-select
		if (window.dhx4.s2b(data.select) == true) this._setSelected(id, true);
		
		this.callEvent("_onItemInited", [id, data]);
		
	}
	
	this.addItem = function(id, text, parentId, index) {
		if (this.items[id] != null) return;
		if (parentId != null && this.items[parentId] == null) parentId = null;
		this._addItem(id, parentId, {text: text}, index);
		this._callPublicEvent("onAddItem", [id, text, parentId, index]);
	}
	
	this._removeSingleItem = function(id) {
		if (window.dhx4.isIE == true) this.items[id].item.onselectstart = null;
		this.items[id].item.parentNode.removeChild(this.items[id].item);
		
		for (var a in this.items[id]) {
			this.items[id][a] = null;
			delete this.items[id][a];
		}
		
		delete this.items[id];
		
		// clear selection if any
		if (this.conf.selected[id] == true) delete this.conf.selected[id];
	}
	
	this._removeItem = function(id) {
		
		// remove nested
		for (var a in this.items) {
			if (this.items[a].pId == id) this._removeItem(a);
		}
		
		if (id != null) this._removeSingleItem(id);
	}
	
	this.deleteItem = function(id) {
		if (this.items[id] == null) return;
		if (!this._callPublicEvent("onBeforeDeleteItem", [id])) return;
		this._removeItem(id);
		this._callPublicEvent("onDeleteItem", [id]);
	}
	
	this.clearAll = function() {
		this._removeItem(null);
		if (this.conf.unloading != true) this._fixAreaWidth();
	}
	
	this._initKidsNode = function(id) {
		
		var p;
		
		if (this.items[id].item.lastChild.className.match(/dhxtreeview_kids_cont/) == null) {
		
			p = document.createElement("DIV");
			p.className = "dhxtreeview_kids_cont";
			p.innerHTML = "<div style='position:relative;'></div>";
			
			p.style.opacity = "1";
			
			if (this.items[id].opened != true) {
				if (this.conf.transProp != false) {
					p.style.height = "0px";
					p.style.opacity = "0";
					p.firstChild.style.display = "none";
				} else {
					p.style.display = "none";
				}
			}
			
			this.items[id].item.appendChild(p);
			
		}
		
		this.items[id].kids = true;
		this._iconUpdate(id);
		this._signUpdate(id);
		
		p = null;
	}
	
	this._clearKidsNode = function(id) {
		
		if (this.items[id].item.lastChild.className.match(/dhxtreeview_kids_cont/) != null) {
			this.items[id].item.removeChild(this.items[id].item.lastChild);
		}
		
		this.items[id].kids = false;
		this._iconUpdate(id);
		this._signUpdate(id);
		
	}
	
	// open/colse
	this.openItem = function(id, anim) {
		if (this.items[id].opened != true) {
			if (typeof(anim) == "undefined") anim = true;
			this._openCloseItem(id, anim);
		}
	}
	this.closeItem = function(id, anim) {
		if (this.items[id].opened == true) {
			if (typeof(anim) == "undefined") anim = true;
			this._openCloseItem(id, anim);
		}
	}
	
	this._openCloseItem = function(id, anim) {
		
		if (this.callEvent("_onBeforeOpen", [id]) !== true) return;
		
		if (!(this.items[id].kids == true || this.items[id].kids_request == true)) return false;
		
		if (this.items[id].half_opened == true) {
			this.items[id].half_opened = false;
			this._signUpdate(id);
			return;
		}
		
		if (anim && this.conf.transProp != false) {
			
			if (!this.items[id].transEv) {
				this.items[id].item.lastChild.addEventListener(this.conf.transEv, this._doOnTrEnd);
				this.items[id].transEv = true;
			}
			
			if (this.items[id].opened == true) {
				
				// close
				
				this.items[id].transMode = "close";
				
				this.items[id].item.lastChild.style.overflow = "hidden";
				this.items[id].item.lastChild.style.height = this.items[id].item.lastChild.childNodes[0].offsetHeight+"px";
				
				window.setTimeout(function(){
						
					that.items[id].item.lastChild.style[that.conf.transProp] = that.conf.transValueHeight;
					that.items[id].item.lastChild.style.height = "0px";
					that.items[id].item.lastChild.style.opacity = "0";
					
					that.items[id].opened = false;
					that._iconUpdate(id);
					that._signUpdate(id);
					
				},50);
				
			} else {
				
				// open
				
				this.items[id].transMode = "open";
				
				this.items[id].item.lastChild.style[this.conf.transProp] = this.conf.transValueHeight;
				
				this.items[id].item.lastChild.childNodes[0].style.display = "";
				this.items[id].item.lastChild.style.overflow = "hidden";
				
				this.items[id].item.lastChild.style.height = this.items[id].item.lastChild.childNodes[0].offsetHeight+"px";
				this.items[id].item.lastChild.style.opacity = "1";
				
				this.items[id].opened = true;
				this._iconUpdate(id);
				this._signUpdate(id);
			}
			
			
		} else {
			
			// open/close
			this.items[id].opened = !this.items[id].opened;
			this.items[id].item.lastChild.style.display = (this.items[id].opened==true ? "" : "none");
			
			// add for dnd
			this.items[id].item.lastChild.childNodes[0].style.display = this.items[id].item.lastChild.style.display;
			this.items[id].item.lastChild.style.height = (this.items[id].opened==true?"":"0px");
			this.items[id].item.lastChild.style.opacity = (this.items[id].opened==true?1:0);
			// end for dnd
			
			this._iconUpdate(id);
			this._signUpdate(id);
			this._fixAreaWidth();
		}
	}
	
	this._doOnTrEnd = function() {
		
		var id = this.parentNode._itemId;
		that.items[id].item.lastChild.style[that.conf.transProp] = "";
		
		if (that.items[id].transMode == "close") {
			that.items[id].item.lastChild.childNodes[0].style.display = "none";
			//that._iconUpdate(id);
		} else {
			that.items[id].item.lastChild.style.height = "";
			that.items[id].item.lastChild.style.overflow = "";
		}
		that._fixAreaWidth();
	}
	
	// dimension
	this.setSizes = function() {
		// adjust top-parent, used in window when tree has border
		if (this.conf.adjust_base == true) {
			this.base.style.width = this.base.parentNode.clientWidth-2+"px";
			this.base.style.height = this.base.parentNode.clientHeight-2+"px";
		}
		//
		this.cont.style.left = this.conf.ofs.w+"px";
		this.cont.style.top = this.conf.ofs.h+"px";
		this.cont.style.width = this.base.clientWidth-this.conf.ofs.w*2+"px";
		this.cont.style.height = this.base.clientHeight-this.conf.ofs.h*2+"px";
		//
		this._fixAreaWidth();
	}
	
	this._fixAreaWidth = function(r) {
		this.area.style.width = "100%";
		if (this.cont.scrollWidth != this.cont.clientWidth) {
			this.area.style.width = this.cont.scrollWidth+1+"px";
		}
		if (window.dhx4.isIE7 == true && r !== false) { // extra loop for ie7 to fix scroll artefacts
			window.setTimeout(function(){that._fixAreaWidth(false);},1);
		}
	}
	
	
	this.setSizes();
	
	dhx4._eventable(this);
	
	// transition
	var k = window.dhx4.transDetect();
	this.conf.transProp = k.transProp;
	this.conf.transEv = k.transEv;
	this.conf.transValueHeight = "height 0.15s";
	k = null;
	
	// macos multiselect
	if (this.conf.is_mac == true) {
		this._macOnKey = function(e) {
			if (((window.dhx4.isKHTML || window.dhx4.isChrome || window.dhx4.isOpera) && (e.keyCode == 91 || e.keyCode == 93)) || (window.dhx4.isFF && e.keyCode == 224)) {
				that.conf.mac_cmd_key = (e.type == "keydown");
			}
		}
		window.addEventListener("keydown", this._macOnKey, false);
		window.addEventListener("keyup", this._macOnKey, false);
	}
	
	// extra modules to init if any
	for (var a in this.modules) {
		if (this.modules[a].init != null) this[this.modules[a].init](conf);
	}
	
	this.unload = function() {
		
		this.conf.unloading = true;
		
		this.cont.onclick = null;
		this.cont.ondblclick = null;
		
		this.clearAll();
		
		if (this.conf.is_mac == true) {
			window.removeEventListener("keydown", this._macOnKey, false);
			window.removeEventListener("keyup", this._macOnKey, false);
		}
		
		// extra modules to unload if any
		for (var a in this.modules) {
			if (this.modules[a].unload != null) this[this.modules[a].unload]();
		}
		
		this.area.parentNode.removeChild(this.area);
		this.area = null;
		
		this.cont.parentNode.removeChild(this.cont);
		this.cont = null;
		
		this.base.className = String(this.base.className).replace(new RegExp("\s{0,}dhxtreeview_"+(this.conf.skin||"")), "");
		
		window.dhx4._eventable(this, "clear");
		
		for (var a in this) this[a] = null;
		
		that = null;
		
	}
	
	// autoload/etc
	if (conf.items != null || conf.json != null || conf.xml != null) {
		this.loadStruct(conf.items||conf.json||conf.xml, conf.onload);
	}
	
	return this;
	
};

dhtmlXTreeView.prototype.modules = {};

// misc
dhtmlXTreeView.prototype.setSkin = function(skin) {
	this.base.className = String(this.base.className).replace(new RegExp("\s{0,}dhxtreeview_"+(this.conf.skin||"")), "") + " dhxtreeview_"+skin;
	this.conf.skin = skin;
	this.conf.icon_width = dhx4.readFromCss("dhxtreeview_"+this.conf.skin+" dhxtreeview_icon_width");
	this.conf.ofs = (this.conf.skin == "dhx_skyblue" ? {w:1, h:0} : {w:0, h:0});
};

dhtmlXTreeView.prototype.setItemText = function(id, text) {
	if (this.items[id] != null) {
		this.items[id].text = text;
		this.items[id].item.firstChild.childNodes[this.conf.idx.text].innerHTML = text;
		this._callPublicEvent("onTextChange", [id, text]);
	}
};
dhtmlXTreeView.prototype.getItemText = function(id) {
	return this.items[id].text;
};

//

dhtmlXTreeView.prototype.getParentId = function(id) {
	return this.items[id].pId;
};
dhtmlXTreeView.prototype.getSubItems = function(parentId) {
	var t = [];
	for (var a in this.items) {
		if (this.items[a].pId == parentId) t.push(a);
	}
	return t;
};

// render item html
dhtmlXTreeView.prototype._refreshItemHtml = function(id, updSign, updIcon) {
	this.items[id].item.firstChild.innerHTML = this._getItemHtml(id);
	if (updSign == true) this._signUpdate(id);
	if (updIcon == true) this._iconUpdate(id);
};
dhtmlXTreeView.prototype._getItemHtml = function(id) {
	var html = [];
	var nodeIndex = 0;
	for (var a in this.conf.idx) {
		var data = this["_itemHtml_"+a](id, nodeIndex);
		if (data.nodeText !== false) {
			html.push(data.nodeText);
			nodeIndex += data.nodeIndex;
		}
	}
	return html.join("");
};
dhtmlXTreeView.prototype._getIconOfs = function(id, index) {
	return ((this.items[id].level-1+index)*this.conf.icon_width);
};
dhtmlXTreeView.prototype._itemHtml_text = function(id, nodeIndex) {
	return {
		nodeIndex: 1,
		nodeText: "<div class='dhxtreeview_item_label' style='left:"+this._getIconOfs(id, nodeIndex)+"px;'>"+this.items[id].text+"</div>"
	};
};

// userdata
dhtmlXTreeView.prototype.setUserData = function(id, name, value) {
	var item = this.items[id];
	if (item) item.userdata[name] = value;
};
dhtmlXTreeView.prototype.getUserData = function(id, name) {
	var item = this.items[id];
	if (item && !name) return item.userdata;
	return item ? (item.userdata[name]||null) : null;
};

// events extension
dhtmlXTreeView.prototype.silent = function(f) {
	this.conf.silent = true;
	if (typeof(f) == "function") f.apply(window, [this]);
	this.conf.silent = false;
};
dhtmlXTreeView.prototype._callPublicEvent = function() {
	return (this.conf.silent == false ? this.callEvent.apply(this, arguments) : true);
};
if (typeof(window.dhtmlXCellObject) == "function") {
	
	dhtmlXCellObject.prototype.attachTreeView = function(conf) {
		
		this.callEvent("_onBeforeContentAttach", ["treeview"]);
		
		var obj = document.createElement("DIV");
		obj.style.position = "relative";
		obj.style.overflow = "hidden";
		obj.style.width = "100%";
		obj.style.height = "100%";
		
		this._attachObject(obj);
		
		var treeConf = {parent: obj, skin: this.conf.skin};
		if (conf != null && typeof(conf) == "object") {
			for (var a in conf) { if (typeof(treeConf[a]) == "undefined") treeConf[a] = conf[a]; }
		}
		
		this.dataType = "treeview";
		this.dataObj = new dhtmlXTreeView(treeConf);
		
		// draw border if attached to window
		if (typeof(window.dhtmlXWindowsCell) == "function" && this instanceof window.dhtmlXWindowsCell) {
			obj.className += " dhxtreeview_with_border";
			this.dataObj.conf.adjust_base = true;
			this.dataObj.setSizes();
		}
		
		treeConf.parent = null;
		treeConf = obj = conf = null;
		
		this.callEvent("_onContentAttach", []);
		
		return this.dataObj;
		
	};
	
}
// register checkboxes module
dhtmlXTreeView.prototype.modules.chbx = {
	init: "_chbxInit"
};

// public
dhtmlXTreeView.prototype.enableCheckboxes = function(mode) {
	mode = (mode==true);
	if (this.conf.enable_chbx != mode) {
		this.conf.enable_chbx = mode;
		this._chbxUpdIndex();
		for (var a in this.items) this._refreshItemHtml(a, true, true);
	}
};

dhtmlXTreeView.prototype.getAllChecked = function(parentId) {
	return this._chbxGetCheckedBranch(parentId, true);
};
dhtmlXTreeView.prototype.getAllUnchecked = function(parentId) {
	return this._chbxGetCheckedBranch(parentId, false);
};

dhtmlXTreeView.prototype.checkItem = function(id) {
	this._chbxSetChecked(id, true, true);
};
dhtmlXTreeView.prototype.uncheckItem = function(id) {
	this._chbxSetChecked(id, false, true);
};
dhtmlXTreeView.prototype.isItemChecked = function(id) {
	if (this.items[id] == null) return null;
	return (this.items[id].checked == true);
};

dhtmlXTreeView.prototype.enableCheckbox = function(id) {
	this._chbxSetEnabled(id, true);
};
dhtmlXTreeView.prototype.disableCheckbox = function(id) {
	this._chbxSetEnabled(id, false);
};
dhtmlXTreeView.prototype.isCheckboxEnabled = function(id) {
	return (this.items[id].chbx_enabled == true);
};

dhtmlXTreeView.prototype.showCheckbox = function(id) {
	this._chbxSetVisible(id, true);
};
dhtmlXTreeView.prototype.hideCheckbox = function(id) {
	this._chbxSetVisible(id, false);
};
dhtmlXTreeView.prototype.isCheckboxVisible = function(id) {
	return (this.items[id].chbx_visible == true);
};

// private
dhtmlXTreeView.prototype._chbxInit = function(conf) { // init
	
	this.enableCheckboxes(conf.checkboxes);
	
	this.attachEvent("_onItemRendered", function(id, data){
		
		this.items[id].checked = window.dhx4.s2b(data.checked);
		
		var conf = (data.checkbox||"enabled,visible");
		this.items[id].chbx_enabled = (conf.match(/disabled/)==null);
		this.items[id].chbx_visible = (conf.match(/hidden/)==null);
		
	});
	
	this.attachEvent("_onTreeClick", function(e, flow){
			
		if (this.conf.enable_chbx != true) return;
		
		var t = (e.target||e.srcElement);
		if (t.tagName.toLowerCase() == "i") t = t.parentNode; // check if icon
		
		if ((t.parentNode.className||"").match(/dhxtreeview_item_text/) != null && t == t.parentNode.childNodes[this.conf.idx.chbx]) { // check if checkbox
			var id = t.parentNode.parentNode._itemId;
			if (this.items[id].chbx_enabled == true) this._chbxSetChecked(id, !this.items[id].checked, true);
			flow.stop = true;
		}
	});
	
	conf = null;
	
};

dhtmlXTreeView.prototype._itemHtml_chbx = function(id, nodeIndex) {
	var r = {nodeIndex: 0, nodeText: false};
	if (this.conf.enable_chbx == true) {
		if (this.items[id].chbx_visible == true) r.nodeIndex = 1;
		r.nodeText = "<div class='dhxtreeview_item_icon' style='left:"+this._getIconOfs(id, nodeIndex)+"px;"+(r.nodeIndex>0?"":"display:none;")+"'>"+this._chbxGenIcon(id)+"</div>";
	}
	return r;
};

dhtmlXTreeView.prototype._chbxSetChecked = function(id, state) {
	if (this.conf.enable_chbx != true) return;
	state = (state==true);
	if (this.items[id].checked != state) {
		if (this._callPublicEvent("onBeforeCheck", [id, (this.items[id].checked==true)]) !== true) return;
		this.items[id].checked = state;
		this.items[id].item.childNodes[0].childNodes[this.conf.idx.chbx].innerHTML = this._chbxGenIcon(id);
		this._callPublicEvent("onCheck", [id, state]);
	}
};

dhtmlXTreeView.prototype._chbxSetEnabled = function(id, mode) {
	if (this.items[id].chbx_enabled != mode) {
		this.items[id].chbx_enabled = mode;
		this.items[id].item.firstChild.childNodes[this.conf.idx.chbx].innerHTML = this._chbxGenIcon(id);
	}
};

dhtmlXTreeView.prototype._chbxSetVisible = function(id, mode) {
	if (this.items[id].chbx_visible != mode) {
		this.items[id].chbx_visible = mode;
		this._refreshItemHtml(id, true, true);
	}
};

dhtmlXTreeView.prototype._chbxGenIcon = function(id) {
	var icon = this.conf.icons["chbx_"+(this.items[id].chbx_enabled?"":"dis_")+(this.items[id].checked?"1":"0")];
	return '<i class="'+this.conf.icons.prefix+' '+icon+'"></i>';
};

dhtmlXTreeView.prototype._chbxUpdIndex = function() {
	if (this.conf.enable_chbx == true) {
		this.conf.idx = {sign: 0, chbx: 1, icon: 2, text: 3};
	} else {
		this.conf.idx = {sign: 0, icon: 1, text: 2};
	}
};

dhtmlXTreeView.prototype._chbxGetCheckedBranch = function(pId, mode) {
	var k = [];
	for (var a in this.items) {
		if (this.items[a].pId == pId) {
			if (this.items[a].checked == mode) k.push(a);
			if (this.items[a].kids == true) k = k.concat(this._chbxGetCheckedBranch(a, mode));
		}
	}
	return k;
};
// register selection module
dhtmlXTreeView.prototype.modules.sign = {
	init: "_signInit"
};

// private
dhtmlXTreeView.prototype._signInit = function() {
	this.attachEvent("_onTreeClick", function(e, flow){
		if (flow.stop == true) return; // check if cancelled by prev attached function
		var t = (e.target||e.srcElement);
		if (t.tagName.toLowerCase() == "i") t = t.parentNode; // check if icon
		if ((t.parentNode.className||"").match(/dhxtreeview_item_text/) != null && t == t.parentNode.childNodes[this.conf.idx.sign]) {
			this._openCloseItem(t.parentNode.parentNode._itemId, true);
			flow.stop = true;
		}
	});
};

dhtmlXTreeView.prototype._signUpdate = function(id) {
	var t = this.items[id];
	var img = t.item.childNodes[0].childNodes[this.conf.idx.sign];
	if (t.kids == true || t.kids_request == true) {
		img.innerHTML = '<i class="'+this.conf.icons.prefix+" "+this.conf.icons[(t.opened||t.half_opened?"minus":"plus")]+'"></i>';
	} else {
		img.innerHTML = "";
	}
	t = img = null;
}

dhtmlXTreeView.prototype._itemHtml_sign = function(id, nodeIndex) { // item html renderer
	return {
		nodeIndex: 1,
		nodeText: "<div class='dhxtreeview_item_icon' style='left:"+this._getIconOfs(id, nodeIndex)+"px;'></div>"
	};
};
// register selection module
dhtmlXTreeView.prototype.modules.selection = {
	init: "_selectionInit"
};

// public
dhtmlXTreeView.prototype.selectItem = function(id) {
	if (this.conf.msel == true) {
		var t = {};
		if (!(id instanceof Array)) id = [id];
		for (var q=0; q<id.length; q++) t[id[q]] = true;
		for (var a in this.conf.selected) {
			if (t[a] == true) {
				delete t[a]; // already selected
			} else {
				this._setSelected(a, false); // clear selection if not preserve
			}
		}
		for (var a in t) this._setSelected(a, true); // select the rest
	} else if (id != null && this.conf.selected[id] != true && !(id instanceof Array)) {
		if (this._clearSelection(id) == false) this._setSelected(id, true);
	}
};

dhtmlXTreeView.prototype.unselectItem = function(id) {
	if (this.conf.msel == true) {
		if (!(id instanceof Array)) id = [id];
		for (var q=0; q<id.length; q++) {
			if (this.conf.selected[id[q]] == true) this._setSelected(id[q], false);
		}
	} else if (id != null) {
		this._setSelected(id, false);
	}
};

dhtmlXTreeView.prototype.getSelectedId = function() {
	var ids = [];
	for (var a in this.conf.selected) ids.push(a);
	return (this.conf.msel?ids:(ids[0]||null));
};

dhtmlXTreeView.prototype.enableMultiselect = function(mode) {
	mode = (mode==true);
	if (this.conf.msel != mode) {
		this._clearSelection();
		this.conf.msel = mode;
	}
};

// private
dhtmlXTreeView.prototype._selectionInit = function(conf) { // init
	
	this.conf.msel = window.dhx4.s2b(conf.multiselect);
	
	this.attachEvent("_onTreeClick", function(e, flow){
		
		if (flow.stop == true) return; // check if cancelled by prev attached function
		
		var t = (e.target||e.srcElement);
		if (t.tagName.toLowerCase() == "i") t = t.parentNode; // check if icon
		
		var selectId = null;
		
		if (t.className.match(/dhxtreeview_item_label/) != null) {
			selectId = t.parentNode.parentNode._itemId;
		} else if (t.className.match(/^dhxtreeview_item_text/) != null) {
			selectId = t.parentNode._itemId;
		} else if (t.className.match(/^dhxtreeview_item_icon/) != null) {
			selectId = t.parentNode.parentNode._itemId;
		}
		
		if (selectId != null)  {
			if (this.conf.msel == true) {
				if ((e.ctrlKey == true || this.conf.mac_cmd_key == true) && e.shiftKey == false && e.altKey == false) { // ctrl pressed
					this._setSelected(selectId, !this._isSelected(selectId));
				} else if (e.ctrlKey == false && e.shiftKey == false && e.altKey == false && this.conf.mac_cmd_key == false) { // nothing pressed
					if (this._clearSelection(selectId) == false) this._setSelected(selectId, true);
				}
			} else {
				if (this._clearSelection(selectId) == false) this._setSelected(selectId, true);
			}
		}
	});
};

dhtmlXTreeView.prototype._setSelected = function(id, mode) {
	if (mode == true) {
		if (this.conf.selected[id] != true) {
			this.items[id].item.childNodes[0].className += " dhxtreeview_item_text_selected";
			this.conf.selected[id] = true;
			this._callPublicEvent("onSelect", [id, true]);
		}
	} else {
		if (this.conf.selected[id] == true) {
			this.items[id].item.childNodes[0].className = String(this.items[id].item.childNodes[0].className).replace(/\s*dhxtreeview_item_text_selected/gi, "");
			delete this.conf.selected[id];
			this._callPublicEvent("onSelect", [id, false]);
		}
	}
};

dhtmlXTreeView.prototype._clearSelection = function(exceptId) {
	var r = false;
	for (var a in this.conf.selected) {
		if (exceptId != null && a == exceptId) r = true; else this._setSelected(a, false);
	}
	return r; // true if item stay selected
};

dhtmlXTreeView.prototype._isSelected = function(id) {
	return (this.conf.selected[id]==true);
};

// register icons module
dhtmlXTreeView.prototype.modules.icons = {
	init: "_iconModuleInit"
};

// public
dhtmlXTreeView.prototype.setItemIcons = function(id, icons) {
	if (icons == null && this.items[id].icons != null) {
		delete this.items[id].icons; // clear all custom for certain item
	} else if (icons != null) {
		if (this.items[id].icons == null) this.items[id].icons = {};
		for (var a in icons) {
			if (icons[a] != null) {
				this.items[id].icons[a] = icons[a];
			} else if (icons[a] == null && this.items[id].icons[a] != null) {
				delete this.items[id].icons[a]; // clear only specified icon
			}
		}
	}
	this._iconUpdate(id);
};

dhtmlXTreeView.prototype.setIconColor = function(id, color) {
	var icon = this.items[id].item.firstChild.childNodes[this.conf.idx.icon].firstChild;
	if (color == null) {
		if (this.items[id].icon_color != null) {
			delete this.items[id].icon_color;
			icon.style.color = "inherit";
		}
	} else {
		if (this.items[id].icon_color != color) {
			this.items[id].icon_color = color;
			icon.style.color = color;
		}
	}
	icon = null;
};

dhtmlXTreeView.prototype.setIconset = function(name) {
	if (this.icons[name] != null && this.icons[name].r == true) {
		this.conf.icons = this.icons[name];
	}
};

// private
dhtmlXTreeView.prototype._iconModuleInit = function() { // init
	this.attachEvent("_onItemRendered", function(id, data){
		if (data.icons != null) this.items[id].icons = data.icons;
		if (data.icon_color != null) this.items[id].icon_color = data.icon_color;
	});
};

dhtmlXTreeView.prototype._iconConf = function(id) { // return array with icons
	var icons = this.items[id].icons||{};
	for (var a in {folder_opened:1, folder_closed:1, file:1}) {
		if (typeof(icons[a]) == "undefined") icons[a] = this.conf.icons[a]; // if item has own icons missing will updated here
	}
	return icons;
};

dhtmlXTreeView.prototype._iconHtml = function(id, css) { // generate <i> for icon
	var attrs = ['class="'+this.conf.icons.prefix+" "+css+'"'];
	if (this.items[id].icon_color != null) attrs.push('style="color:'+this.items[id].icon_color+';"');
	return "<i "+attrs.join(" ")+"></i>";
};

dhtmlXTreeView.prototype._itemHtml_icon = function(id, nodeIndex) { // item html renderer
	return {
		nodeIndex: 1,
		nodeText: "<div class='dhxtreeview_item_icon' style='left:"+this._getIconOfs(id, nodeIndex)+"px;'>"+this._iconHtml(id, this._iconConf(id).file)+"</div>"
	};
};

dhtmlXTreeView.prototype._iconUpdate = function(id) { // update icon inner call
	var t = this.items[id];
	var icons = this._iconConf(id);
	var css = (t.kids == true || t.kids_request == true ? icons[t.opened?"folder_opened":"folder_closed"] : icons.file);
	t.item.childNodes[0].childNodes[this.conf.idx.icon].innerHTML = this._iconHtml(id, css);
	t = null;
};

// config
dhtmlXTreeView.prototype.icons = {
	tree_native: {
		r: true, // allow rendering depending on browser
		prefix: "dhxtreeview_icon", // common prefix for all icons/arrows/checkboxes/etc
		plus: "dhxtreeview_icon_plus",
		minus: "dhxtreeview_icon_minus",
		file: "dhxtreeview_icon_file",
		folder_opened: "dhxtreeview_icon_folder_opened",
		folder_closed: "dhxtreeview_icon_folder_closed",
		loading: "dhxtreeview_icon_loading",
		chbx_0: "dhxtreeview_icon_chbx_0",
		chbx_1: "dhxtreeview_icon_chbx_1",
		chbx_dis_0: "dhxtreeview_icon_chbx_dis_0",
		chbx_dis_1: "dhxtreeview_icon_chbx_dis_1"
	},
	font_awesome: {
		r: (!(window.dhx4.isIE6 == true || window.dhx4.isIE7 == true)),
		prefix: "fa",
		plus: "fa-caret-right",
		minus: "fa-caret-down",
		file: "fa-file-o",
		folder_opened: "fa-folder-open-o",
		folder_closed: "fa-folder-o",
		loading: "fa-refresh fa-spin",
		chbx_0: "fa-square-o",
		chbx_1: "fa-check-square-o",
		chbx_dis_0: "fa-square-o dhx-disabled",
		chbx_dis_1: "fa-check-square-o dhx-disabled"
	}
};
// register loading module
dhtmlXTreeView.prototype.modules.loading = {
	init: "_loadingInit",
	unload: "_loadingUnload"
};

dhtmlXTreeView.prototype._loadingInit = function(conf) {
	window.dhx4._enableDataLoading(this, "_initObj", "_xmlToObj", "tree", {struct:true});
	this.conf.root_id = (typeof(conf.root_id)=="undefined" || conf.root_id==null ? "0" : conf.root_id); // top-level item
	this._dhxdataload.onBeforeXLS = function(url) { // add tree_id for 1st load if any
		if (this.conf.autoload.url == null) this.conf.autoload.url = url;
		return {url:url.replace(/\{id\}/gi, this.conf.root_id)};
	}
};

dhtmlXTreeView.prototype._loadingUnload = function() {
	window.dhx4._enableDataLoading(this, null, null, null, "clear");
};

dhtmlXTreeView.prototype._initObj = function(data, url, pId, fixArea) {
	
	for (var q=0; q<data.length; q++) {
		
		var id = data[q].id;
		if (id == null) id = "dhxtreeview_id_"+window.dhx4.newId();
		
		// add item if not exists, if already exists - refresh?
		if (this.items[id] == null) this._addItem(id, pId, data[q]);
		
		// nested
		if (data[q].items != null) this._initObj(data[q].items, null, id, true);
	}
	
	// done
	if (fixArea != true) {
		this._fixAreaWidth();
	}
};

dhtmlXTreeView.prototype._xmlToObj = function(root, nested) {
	
	if (nested != true) root = root.getElementsByTagName("tree")[0];
	var data = [];
	
	for (var q=0; q<root.childNodes.length; q++) {
		var node = root.childNodes[q];
		
		if ((node.tagName||"").toLowerCase() == "item") {
			
			// main item attrs
			var item = {};
			for (var w=0; w<node.attributes.length; w++) {
				item[node.attributes[w].name] = node.attributes[w].value;
			}
			
			if (node.childNodes.length > 0) {
				
				// nested items
				var nested = this._xmlToObj(node, true);
				if (nested.length > 0) item.items = nested;
				
				// icons and userdata
				for (var w=0; w<node.childNodes.length; w++) {
					var tag = (node.childNodes[w].tagName||"").toLowerCase();
					if ({icons:1, userdata:1}[tag] == 1) {
						if (item[tag] == null) item[tag] = {};
						for (var e=0; e<node.childNodes[w].attributes.length; e++) {
							var name = node.childNodes[w].attributes[e].name;
							if (item[tag][name] == null) item[tag][name] = node.childNodes[w].attributes[e].value;
						}
					}
					n2 = null;
				}
				
			}
			
			data.push(item);
		}
		
		node = null;
	}
	
	return data;
};

// register dnd module
dhtmlXTreeView.prototype.modules.dnd = {
	init: "_dndInit",
	unload: "_dndUnload"
};

dhtmlXTreeView.prototype.enableDragAndDrop = function(mode) {
	this.conf.enable_dnd = window.dhx4.s2b(mode);
};

dhtmlXTreeView.prototype._dndInit = function(conf) { // init
	
	var that = this;
	
	this.enableDragAndDrop(conf.dnd);
	this.conf.dnd = null;
	
	this._dndOnMouseDown = function(e) {
		
		if (that.conf.enable_dnd != true) return;
		
		e = e||event;
		
		if (typeof(e.button) != "undefined" && e.button >= 2) return false;
		
		var id = null;
		var treeId = null;
		
		var t = e.target||e.srcElement;
		
		var ofs_x = window.dhx4.absLeft(t)+(typeof(e.offsetX)=="undefined"?e.layerX:e.offsetX) - e.clientX;
		var ofs_y = window.dhx4.absTop(t)+(typeof(e.offsetY)=="undefined"?e.layerY:e.offsetY) - e.clientY;
		
		while (t != null && t != that.cont) {
			if ((t.className||"").match(/dhxtreeview_item/) != null && t._itemId != null) {
				id = t._itemId;
				treeId = t._treeId;
				t = null;
			} else {
				t = t.parentNode;
			}
		}
		t = null;
		
		if (id == null) return; // check if empty click and abort
		
		that.conf.dnd = {
			inited: false,
			id: id,
			treeId: treeId,
			selected: (that.conf.selected[id]==true),
			tid: null,
			drop: {},
			x: e.clientX,
			y: e.clientY,
			ofs_x: ofs_x,
			ofs_y: ofs_y,
			zi: window.dhx4.newId(),
			scroll: false,
			scroll_ofs: 5, // offset for single loop
			scroll_time: 30, // timeout
			scroll_tm: null,
			kids: {}, // all kids of dragged to prevent dnd in advance
			idx: {}
		};
		
		
		
		that._dndInitEvents();
		
	}
	
	this._dndOnMouseMove = function(e) {
		
		e = e||event;
		
		if (that.conf.dnd.inited != true) {
			
			if (Math.abs(that.conf.dnd.x - e.clientX) >= 15 || Math.abs(that.conf.dnd.y - e.clientY) >= 15) {
				
				if (that._callPublicEvent("onBeforeDrag", [that.conf.dnd.id]) !== true) return;
				
				that.conf.dnd.inited = true;
				that.cont.className += " dhxtreeview_dnd_mode";
				that._dndInitDraggedObj();
				that._dndCollectKids(that.conf.dnd.id);
				that._dndCollectIndexes(that.area);
				
				// rearrange selection
				if (that._clearSelection(that.conf.dnd.id) == false) that._setSelected(that.conf.dnd.id, true);
				
				// update item css
				that.items[that.conf.dnd.id].item.className += " dhxtreeview_item_dragged";
				document.body.className += " dhxtreeview_dnd_mode";
				
				// tree area to check if scroll should be performed
				that.conf.dnd.cont = {
					x1: window.dhx4.absLeft(that.base),
					y1: window.dhx4.absTop(that.base)
				};
				that.conf.dnd.cont.x2 = that.conf.dnd.cont.x1 + that.base.offsetWidth;
				that.conf.dnd.cont.y2 = that.conf.dnd.cont.y1 + that.base.offsetHeight;
				
			} else {
				return;
			}
			
		}
		
		that.conf.dnd.x = e.clientX;
		that.conf.dnd.y = e.clientY;
		
		that._dndAdjustDraggedObj();
		
		// check tree area edges and scroll content if any
		var stopScroll = true;
		
		if (that.cont.scrollHeight > that.cont.clientHeight) {
			if (that.conf.dnd.x >= that.conf.dnd.cont.x1 && that.conf.dnd.x <= that.conf.dnd.cont.x2) {
				if (that.cont.scrollTop > 0 && that.conf.dnd.y >= that.conf.dnd.cont.y1 && that.conf.dnd.y <= that.conf.dnd.cont.y1 + 10) { // top edge
					that._dndScroll("up");
					stopScroll = false;
				} else if (that.cont.scrollTop+that.cont.clientHeight < that.cont.scrollHeight && that.conf.dnd.y <= that.conf.dnd.cont.y2 && that.conf.dnd.y >= that.conf.dnd.cont.y2 - 10) { // bottom edge
					that._dndScroll("down");
					stopScroll = false;
				}
			}
		}
		
		if (stopScroll == true && that.conf.dnd.scroll == true) {
			that._dndScroll("stop");
		}
		
		// detect node by target
		var t = (e.target||e.srcElement);
		
		// remove blink artefact if any
		if (t.parentNode != null && (t.parentNode.className||"").match(/dhxtreeview_kids_cont/) != null) {
			t = null;
			return;
		}
		
		var upd = false;
		var tid = null;
		var treeId = null;
		
		if (t.className != null) {
			if (t.className.match(/dhxtreeview_item_[li]/) != null) { // label/icon
				tid = t.parentNode.parentNode._itemId;
				treeId = t.parentNode.parentNode._treeId;
			} else if (t.className.match(/dhxtreeview_item_[t]/) != null) { // text
				tid = t.parentNode._itemId;
				treeId = t.parentNode._treeId;
			}
		}
		
		// check if the same tree
		if (tid != null && treeId != that.conf.dnd.treeId) {
			return;
		}
		
		// check if target is the same or if target is child
		if (that.conf.dnd.id == tid || that.conf.dnd.kids[tid] == true) {
			tid = null;
		}
		
		if (tid != null) {
			
			var h = that.items[tid].item.firstChild.offsetHeight;
			var ofs = Math.max(Math.floor(Math.min(e.layerY||e.offsetY, h) * 3 / h), 0);
			
			// depending on item type and offset - allow/block some offsets
			
			if (ofs == 0) { // drop as sibling above target
				
				if (that.items[tid].item.previousSibling == that.items[that.conf.dnd.id].item) { // do not allow if prev sibling is dragged
					ofs = null;
				}
				
			} else if (ofs == 1) { // drop as child of target
				
				if (that.items[that.conf.dnd.id].pId == tid) { // if already child of selected parent
					ofs = null;
				} else if (that.items[tid].kids == true && that.items[tid].item.lastChild.firstChild.firstChild == that.items[that.conf.dnd.id].item) { // do not allo if dragged already 1st child
					ofs = null;
				} else if (that.items[tid].opened == false) { // open node
					//that._openCloseItem(tid, true);
				}
				
			} else if (ofs == 2) { // drop as sibling below target
				
				if (that.items[tid].opened == true) { // do not allow for opened item
					ofs = null;
				} else if (that.items[tid].item.nextSibling == that.items[that.conf.dnd.id].item) { // do not allow if next sibling is dragged
					ofs = null;
				}
			}
			//
			if (ofs != that.conf.dnd.ofs) {
				that.conf.dnd.ofs = ofs;
				upd = true;
			}
		}
		
		if (tid != that.conf.dnd.tid) {
			
			// clear old one
			if (that.conf.dnd.tid != null) {
				that._dndUpdateTargetCss(that.conf.dnd.tid, false);
			}
			// update new
			if (tid != null) {
				upd = true;
			}
			that.conf.dnd.tid = tid;
		}
		
		if (upd == true) {
			
			var mode = false;
			
			if (ofs != null) {
				
				var drop = {
					id: that.conf.dnd.id,
					pId: that.items[tid].pId||null,
					index: null,
					idxOfs: (that.items[that.conf.dnd.id].pId == that.items[tid].pId && that.conf.dnd.idx[that.conf.dnd.id] < that.conf.dnd.idx[tid] ? -1 : 0)
				};
				
				if (ofs == 0 || ofs == 2) {
					drop.index = that.conf.dnd.idx[tid]+(ofs==2?1:0)+drop.idxOfs;
				} else if (ofs == 1) {
					drop.pId = tid;
					drop.index = (that.items[tid].item.lastChild.className.match(/dhxtreeview_kids_cont/)==null?0:that.items[tid].item.lastChild.firstChild.childNodes.length);
				}
				
				if (that.conf.dnd.drop.id != drop.id || that.conf.dnd.drop.pId != drop.pId || that.conf.dnd.drop.index != drop.index) {
					that.conf.dnd.drop = drop;
					if (that._callPublicEvent("onDragOver", [that.conf.dnd.drop.id, that.conf.dnd.drop.pId, that.conf.dnd.drop.index]) === true) mode = true;
				}
				
			}
			
			if (mode != true) that.conf.dnd.ofs = ofs = null;
			
			that._dndUpdateTargetCss(tid, mode);
			
		}
		
	}
	
	this._dndOnMouseUp = function(e) {
		
		e = e||event;
		
		if (typeof(e.button) != "undefined" && e.button >= 2) return;
		
		that._dndUnloadEvents();
		that._dndUnloadDraggedObj();
		
		if (that.conf.dnd.scroll == true) {
			that._dndScroll("stop");
		}
		
		if (that.cont.className.match(/dhxtreeview_dnd_mode/gi) != null) {
			that.cont.className = String(that.cont.className).replace(/\s*dhxtreeview_dnd_mode/gi, "");
		}
		
		if (that.conf.dnd.tid != null) {
			that._dndUpdateTargetCss(that.conf.dnd.tid, false);
		}
		
		if (that.conf.dnd.inited == true) {
			
			that.items[that.conf.dnd.id].item.className = String(that.items[that.conf.dnd.id].item.className).replace(/\s*dhxtreeview_item_dragged/gi, "");
			document.body.className = String(document.body.className).replace(/\s*dhxtreeview_dnd_mode/, "");
			
			if (that.conf.dnd.tid != null && that.conf.dnd.ofs != null) {
				
				if (that._callPublicEvent("onBeforeDrop", [that.conf.dnd.drop.id, that.conf.dnd.drop.pId, that.conf.dnd.drop.index]) === true) {
					
					var obj = that.items[that.conf.dnd.id];
					var tobj = that.items[that.conf.dnd.tid];
					var pobj = (obj.pId != null ? that.items[obj.pId] : null); // prev_parent id
					
					var levelOfs;
					
					// 1) dom
					if (that.conf.dnd.ofs == 1) {
						
						var open = false;
						if (tobj.kids == false) {
							that._initKidsNode(tobj.id);
							open = true;
						}
						tobj.item.lastChild.firstChild.appendChild(obj.item);
						//
						if (open == true) {
							that._openCloseItem(tobj.id, false);
						}
						
						obj.pId = tobj.id;
						levelOfs = tobj.level+1-obj.level;
						
					} else if (that.conf.dnd.ofs == 0 || that.conf.dnd.ofs == 2) { // sibling before/after
						
						if (that.conf.dnd.ofs == 0) { // before
							tobj.item.parentNode.insertBefore(obj.item, tobj.item);
						} else if (tobj.item.nextSibling != null) { // after
							tobj.item.parentNode.insertBefore(obj.item, tobj.item.nextSibling);
						} else { // after
							tobj.item.parentNode.appendChild(obj.item);
						}
						
						obj.pId = tobj.pId;
						levelOfs = tobj.level-obj.level;
					}
					
					// update nested if level changed
					if (levelOfs != 0) {
						that.conf.dnd.kids[obj.id] = true;
						for (var a in that.conf.dnd.kids) {
							that.items[a].level += levelOfs;
							that._refreshItemHtml(a, (that.items[a].kids == true), true);
						}
					}
					
					// 4) check parent's kids area and remove if empty
					if (pobj != null && pobj.kids == true && pobj.item.lastChild.firstChild.childNodes.length == 0) {
						that._clearKidsNode(pobj.id)
						pobj.opened = false;
					}
					
					obj = tobj = pobj = null;
					
					that._fixAreaWidth();
					
					that._callPublicEvent("onDrop", [that.conf.dnd.drop.id, that.conf.dnd.drop.pId, that.conf.dnd.drop.index]);
					
				}
				
			}
			
		}
		
		window.dhx4.zim.clear(that.conf.dnd.zi);
		
		that.conf.dnd = null;
		
	}
	
	this._dndOnContextMenu = function(e) {
		if (that.conf.dnd.inited == true) {
			e = e||event;
			e.cancelBubble = true;
			if (e.preventDefault) e.preventDefault();
			e.returnValue = false;
			return false;
		}
	}
	
	// events
	this._dndInitEvents = function() {
		
		if (typeof(window.addEventListener) == "function") {
			window.addEventListener("mousemove", this._dndOnMouseMove, false);
			window.addEventListener("mouseup", this._dndOnMouseUp, false);
			window.addEventListener("contextmenu", this._dndOnContextMenu, false);
		} else {
			document.body.attachEvent("onmousemove", this._dndOnMouseMove);
			document.body.attachEvent("onmouseup", this._dndOnMouseUp);
			document.body.attachEvent("oncontextmenu", this._dndOnContextMenu);
		}
		
	}
	
	this._dndUnloadEvents = function() {
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("mousemove", this._dndOnMouseMove, false);
			window.removeEventListener("mouseup", this._dndOnMouseUp, false);
			window.removeEventListener("contextmenu", this._dndOnContextMenu, false);
		} else {
			document.body.detachEvent("onmousemove", this._dndOnMouseMove);
			document.body.detachEvent("onmouseup", this._dndOnMouseUp);
			document.body.detachEvent("oncontextmenu", this._dndOnContextMenu);
		}
	}
	
	// dragged object
	this._dndInitDraggedObj = function() {
		this.conf.dnd.dragged = document.createElement("DIV");
		this.conf.dnd.dragged.className = "dhxtreeview_dragged_obj_"+this.conf.skin;
		this.conf.dnd.dragged.style.zIndex = window.dhx4.zim.reserve(this.conf.dnd.zi);
		document.body.appendChild(this.conf.dnd.dragged);
		//
		this.conf.dnd.dragged.innerHTML = this.getItemText(this.conf.dnd.id);
	}
	
	this._dndAdjustDraggedObj = function() {
		this.conf.dnd.dragged.style.left = this.conf.dnd.x + this.conf.dnd.ofs_x + 12 + "px";
		this.conf.dnd.dragged.style.top = this.conf.dnd.y + this.conf.dnd.ofs_y + 18 + "px";
	}
	
	this._dndUnloadDraggedObj = function() {
		if (this.conf.dnd.dragged != null) {
			document.body.removeChild(this.conf.dnd.dragged);
			this.conf.dnd.dragged = null;
		}
	}
	
	// target node ui
	this._dndUpdateTargetCss = function(id, mode) {
		
		var t = this.items[id].item.childNodes[0];
		
		if (this.conf.dnd.ofs == null) {
			mode = false;
		}
		
		if (mode == true) {
			
			t.className = String(t.className).replace(/(\s*dhxtreeview_drop_\d)?$/i, " dhxtreeview_drop_"+this.conf.dnd.ofs);
			//
			if (t.nextSibling == null || t.nextSibling.className.match(/dhxtreeview_drop_preview/) == null) {
				var k = document.createElement("DIV");
				k.className = "dhxtreeview_drop_preview";
				k.style.left = t.lastChild.previousSibling.style.left;
				if (t.nextSibling == null) {
					t.parentNode.appendChild(k);
				} else {
					t.parentNode.insertBefore(k, t.nextSibling);
				}
				k = null;
			}
			
			t.nextSibling.className = String(t.nextSibling.className).replace(/(\s*dhxtreeview_drop_\d)?$/i, " dhxtreeview_drop_"+this.conf.dnd.ofs);
			
		} else if (t.className.match(/dhxtreeview_drop_\d/) != null) {
			t.className = String(t.className).replace(/\s*dhxtreeview_drop_\d/gi, "");
			//
			if (t.nextSibling != null && t.nextSibling.className.match(/dhxtreeview_drop_preview/) != null) {
				t.parentNode.removeChild(t.nextSibling);
			}
		}
		
		t = null;
		
	}
	
	// cache for kids items, dnd can't be performed
	this._dndCollectKids = function(pId) {
		for (var a in this.items) {
			if (this.items[a].pId == pId) {
				this.conf.dnd.kids[a] = true;
				if (this.items[a].kids == true) this._dndCollectKids(a);
			}
		}
	}
	
	this._dndCollectIndexes = function(node) {
		for (var q=0; q<node.childNodes.length; q++) {
			this.conf.dnd.idx[node.childNodes[q]._itemId] = q;
			if (node.childNodes[q].lastChild.className.match(/dhxtreeview_kids_cont/) != null) {
				this._dndCollectIndexes(node.childNodes[q].lastChild.firstChild);
			}
		}
	}
	
	// scroll content area
	this._dndScroll = function(mode, force) {
		
		if (mode == "stop") {
			
			if (that.conf.dnd.scroll == true) {
				if (that.conf.dnd.scroll_tm) window.clearTimeout(that.conf.dnd.scroll_tm);
				that.conf.dnd.scroll = false;
			}
			
			return;
			
		} else {
			
			if (that.conf.dnd.scroll == true) {
				if (force != true) return; // call from script, already performed, aborting
			} else {
				that.conf.dnd.scroll = true;
			}
			
			var stopScroll = false;
			if (mode == "up") {
				that.cont.scrollTop = Math.max(0, that.cont.scrollTop-that.conf.dnd.scroll_ofs);
				if (that.cont.scrollTop == 0) stopScroll = true;
			} else {
				that.cont.scrollTop = Math.min(that.cont.scrollHeight-that.cont.clientHeight, that.cont.scrollTop+that.conf.dnd.scroll_ofs);
				if (that.cont.scrollTop+that.cont.clientHeight == that.cont.scrollHeight) stopScroll = true;
			}
			
			if (stopScroll != true) {
				that.conf.dnd.scroll_tm = window.setTimeout(function(){
					that._dndScroll(mode, true);
				}, that.conf.dnd.scroll_time);
			}
			
		}
		
	}
	
	
	if (typeof(window.addEventListener) == "function") {
		this.cont.addEventListener("mousedown", this._dndOnMouseDown, false);
	} else {
		this.cont.attachEvent("onmousedown", this._dndOnMouseDown);
	}
	
	
	// unload
	this._dndUnload = function() {
		
		if (typeof(window.addEventListener) == "function") {
			this.cont.removeEventListener("mousedown", this._dndOnMouseDown, false);
		} else {
			this.cont.detachEvent("onmousedown", this._dndOnMouseDown);
		}
		
		that = null;
	}
	
	conf = null;
	
};
// register context menu module
dhtmlXTreeView.prototype.modules.ctx = {
	init: "_ctxInit",
	unload: "_ctxUnload"
};

// public
dhtmlXTreeView.prototype.enableContextMenu = function(mode) {
	this.conf.ctx = window.dhx4.s2b(mode);
};

// private
dhtmlXTreeView.prototype._ctxInit = function(conf) { // init
	
	var that = this;
	
	this._ctxCall = function(e) {
		
		if (that.conf.ctx != true) return;
		
		e = e||event;
		var t = (e.target||e.srcElement);
		if (t.tagName.toLowerCase() == "i") t = t.parentNode; // check if icon
		var id = (t.parentNode._itemId||t.parentNode.parentNode._itemId);
		
		var cx = window.dhx4.absLeft(t)+(typeof(e.offsetX)=="undefined"?e.layerX:e.offsetX);
		var cy = window.dhx4.absTop(t)+(typeof(e.offsetY)=="undefined"?e.layerY:e.offsetY);
		
		if (id != null && that._callPublicEvent("onContextMenu", [id, cx, cy, e]) !== true) {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			e.returnValue = false;
			return false;
		}
		
	}
	
	if (typeof(window.addEventListener) == "function") {
		this.cont.addEventListener("contextmenu", this._ctxCall, false);
	} else {
		this.cont.attachEvent("oncontextmenu", this._ctxCall);
	}
	
	// unload
	this._ctxUnload = function() {
		if (typeof(window.addEventListener) == "function") {
			this.cont.removeEventListener("contextmenu", this._ctxCall, false);
		} else {
			this.cont.detachEvent("oncontextmenu", this._ctxCall);
		}
		that = null;
	};
	
	// autoload if any
	this.enableContextMenu(conf.context_menu);
	
};
// reguster dynload module
dhtmlXTreeView.prototype.modules.dynload = {
	init: "_dynLoadInit"
};

dhtmlXTreeView.prototype._dynLoadInit = function() {
	
	this.attachEvent("_onItemInited", function(id, data){
		
		// true/false to send extra request to server
		this.items[id].kids_request = window.dhx4.s2b(data.kids);
		
		// kids dyn load if any
		if (this.items[id].kids_request == true) {
			this._initKidsNode(id);
		}
		
		// dynload + open, load more data right after item added
		if (this.items[id].opened == true && this.items[id].kids_request == true) {
			var t = this;
			window.setTimeout(function(){
				t._dynLoadRequest(id);
				t = null;
			}, 100);
		}
		
	});
	
	this.attachEvent("_onBeforeOpen", function(id){
		
		// load more kids if any
		if (this.items[id].opened != true && this.items[id].kids_request == true) {
			if (this._dynLoadRequest(id) != true) {
				// change only plus-minus, will opened after data loaded
				this.items[id].half_opened = true;
				this._signUpdate(id);
				return false;
			}
		}
		
		return true;
	});
};

dhtmlXTreeView.prototype._dynLoadRequest = function(id) {
	
	// return true/false for node auto-open
	// if false - open after data will loaded
	if (typeof(this.conf.autoload.mode) == "function") {
		this.conf.autoload.mode.apply(window,[id]);
		return true;
	}
	
	if (this.conf.autoload.url == null) return; // data was loaded in different way
	
	this._dhxdataload.onBeforeXLS = function(url){
		return {url:url.replace(/\{id\}/gi,id)};
	}
	
	// loading icon
	var t = this;
	this.items[id].kids_loading_tm = window.setTimeout(function(){
		t._dynLoadUpdateIcon(id, true);
		t = null;
	}, 100);
	
	this.loadStruct(this.conf.autoload.url, function(){
		window.clearTimeout(this.items[id].kids_loading_tm);
		this._dynLoadUpdateIcon(id, false);
		if (this.items[id].half_opened) {
			this.items[id].half_opened = false;
			this._openCloseItem(id, true);
		}
	});
	
	this.items[id].kids_request = false;
	
	return false;
};

dhtmlXTreeView.prototype._dynLoadUpdateIcon = function(id, mode) {
	this.items[id].loading = (mode == true);
	if (this.items[id].loading == true) {
		this.items[id].item.childNodes[0].childNodes[this.conf.idx.icon].innerHTML = "<i class='"+this.conf.icons.prefix+" "+this.conf.icons.loading+"'></i>";
	} else {
		this._iconUpdate(id);
	}
};
