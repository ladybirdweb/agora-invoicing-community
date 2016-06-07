/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXToolbarObject(base, skin) {
	
	var main_self = this;
	
	this.conf = {
		skin: (skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxtoolbar")||"material"),
		align: "left",
		align_autostart: "left",
		icons_path: "",
		icons_css: false,
		iconSize: 18,
		sel_ofs_x: 0,
		sel_ofs_y: 0,
		xml_autoload: null,
		items_autoload: null,
		cssShadow: (dhx4.isIE6||dhx4.isIE7||dhx4.isIE8?"":" dhx_toolbar_shadow") // for material
	}
	
	if (typeof(base) == "object" && base != null && typeof(base.tagName) == "undefined") {
		// object-api init
		if (base.icons_path != null || base.icon_path != null) this.conf.icons_path = (base.icons_path||base.icon_path);
		if (base.icons_size != null) this.conf.icons_size_autoload = base.icons_size;
		if (base.iconset != null) this.conf.icons_css = (base.iconset == "awesome");
		if (base.json != null) this.conf.json_autoload = base.json;
		if (base.xml != null) this.conf.xml_autoload = base.xml;
		if (base.onload != null) this.conf.onload_autoload = base.onload;
		if (base.onclick != null || base.onClick != null) this.conf.auto_onclick = (base.onclick|| base.onClick);
		if (base.items != null) this.conf.items_autoload = base.items;
		if (base.skin != null) this.conf.skin = base.skin;
		if (base.align != null) this.conf.align_autostart = base.align;
		base = base.parent;
	}
	
	this.cont = (typeof(base)!="object")?document.getElementById(base):base;
	while (this.cont.childNodes.length > 0) this.cont.removeChild(this.cont.childNodes[0]);
	
	base = null;
	
	this.cont.dir = "ltr";
	
	this.base = document.createElement("DIV");
	this.base.className = "dhxtoolbar_float_left";
	this.cont.appendChild(this.base);
	
	this.cont.ontouchstart = function(e){
		e = e||event;
		if ((String(e.target.tagName||"").toLowerCase()=="input")) return true;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		e.cancelBubble = true;
		return false;
	}
	
	this.setSkin(this.conf.skin);
	
	this.objPull = {};
	this.anyUsed = null;
	
	/* random prefix */
	this._genStr = function(w) {
		var s = "dhxId_";
		var z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for (var q=0; q<w; q++) s += z.charAt(Math.round(Math.random() * (z.length-1)));
		return s;
	}
	this.rootTypes = new Array("button", "buttonSelect", "buttonTwoState", "separator", "label", "slider", "text", "buttonInput");
	this.idPrefix = this._genStr(12);
	//
	
	window.dhx4._enableDataLoading(this, "_initObj", "_xmlToJson", "toolbar", {struct:true});
	window.dhx4._eventable(this);
	//
	// return obj if exists by tagname
	this._getObj = function(obj, tag) {
		var targ = null;
		for (var q=0; q<obj.childNodes.length; q++) {
			if (obj.childNodes[q].tagName != null) {
				if (String(obj.childNodes[q].tagName).toLowerCase() == String(tag).toLowerCase()) targ = obj.childNodes[q];
			}
		}
		return targ;
	}
	// create and return image object
	this._addImgObj = function(obj) {
		var imgObj = document.createElement(this.conf.icons_css==true?"I":"IMG");
		if (obj.childNodes.length > 0) obj.insertBefore(imgObj, obj.childNodes[0]); else obj.appendChild(imgObj);
		return imgObj;
	}
	// set/clear item image/imagedis
	this._setItemImage = function(item, url, dis) {
		if (dis == true) item.imgEn = url; else item.imgDis = url;
		if ((!item.state && dis == true) || (item.state && dis == false)) return;
		if (this.conf.icons_css == true) {
			var imgObj = this._getObj(item.obj, "i");
			if (imgObj == null) imgObj = this._addImgObj(item.obj);
			imgObj.className = this.conf.icons_path+url;
		} else {
			var imgObj = this._getObj(item.obj, "img");
			if (imgObj == null) imgObj = this._addImgObj(item.obj);
			imgObj.src = this.conf.icons_path+url;
		}
	}
	this._clearItemImage = function(item, dis) {
		if (dis == true) item.imgEn = ""; else item.imgDis = "";
		if ((!item.state && dis == true) || (item.state && dis == false)) return;
		var imgObj = this._getObj(item.obj, (this.conf.icons_css?"i":"img"));
		if (imgObj != null) imgObj.parentNode.removeChild(imgObj);
	}
	// set/get item text
	this._setItemText = function(item, text) {
		var txtObj = this._getObj(item.obj, "div");
		if (text == null || text.length == 0) {
			if (txtObj != null) txtObj.parentNode.removeChild(txtObj);
			return;
		}
		if (txtObj == null) {
			txtObj = document.createElement("DIV");
			txtObj.className = "dhxtoolbar_text";
			item.obj.appendChild(txtObj);
		}
		txtObj.innerHTML = text;
	}
	this._getItemText = function(item) {
		var txtObj = this._getObj(item.obj, "div");
		if (txtObj != null) return txtObj.innerHTML;
		return "";
	}
	
	// enable/disable btn
	this._enableItem = function(item) {
		if (item.state) return;
		item.state = true;
		if (this.objPull[item.id]["type"] == "buttonTwoState" && this.objPull[item.id]["obj"]["pressed"] == true) {
			item.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_pres";
			item.obj.renderAs = "dhx_toolbar_btn dhxtoolbar_btn_over";
		} else {
			item.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
			item.obj.renderAs = item.obj.className; 
		}
		if (item.arw) item.arw.className = String(item.obj.className).replace("btn","arw");
		var imgObj = this._getObj(item.obj, (this.conf.icons_css?"i":"img"));
		if (item.imgEn != "") {
			if (imgObj == null) imgObj = this._addImgObj(item.obj);
			imgObj[this.conf.icons_css?"className":"src"] = this.conf.icons_path+item.imgEn;
		} else {
			if (imgObj != null) imgObj.parentNode.removeChild(imgObj);
		}
	}
	this._disableItem = function(item) {
		if (!item.state) return;
		item.state = false;
		item.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.objPull[item.id]["type"]=="buttonTwoState"&&item.obj.pressed?"pres_":"")+"dis";
		item.obj.renderAs = "dhx_toolbar_btn dhxtoolbar_btn_def";
		if (item.arw) item.arw.className = String(item.obj.className).replace("btn","arw");
		var imgObj = this._getObj(item.obj, (this.conf.icons_css?"i":"img"));
		if (item.imgDis != "") {
			if (imgObj == null) imgObj = this._addImgObj(item.obj);
			imgObj[this.conf.icons_css?"className":"src"] = this.conf.icons_path+item.imgDis;
		} else {
			if (imgObj != null) imgObj.parentNode.removeChild(imgObj);
		}
		// if (this.objPull[item.id]["type"] == "buttonTwoState") this.objPull[item.id]["obj"]["pressed"] = false;
		// hide opened polygon if any
		if (item.polygon != null) {
			if (item.polygon.style.display != "none") {
				window.dhx4.zim.clear(item.polygon._idd);
				item.polygon.style.display = "none";
				if (item.polygon._ie6cover) item.polygon._ie6cover.style.display = "none";
				// fix border
				if (this.conf.skin == "dhx_terrace") this._improveTerraceButtonSelect(item.id, true);
				// event
				this.callEvent("onButtonSelectHide", [item.obj.idd]);
			}
		}
		this.anyUsed = null;
	}
	
	this.clearAll = function() {
		for (var a in this.objPull) this._removeItem(String(a).replace(this.idPrefix,""));
	}
	
	
	//
	this._doOnClick = function(e) {
		if (main_self && main_self.forEachItem) {
			main_self.forEachItem(function(itemId){
				if (main_self.objPull[main_self.idPrefix+itemId].type == "buttonSelect") {
					// hide any opened buttonSelect's polygons, clear selection if any
					var item = main_self.objPull[main_self.idPrefix+itemId];
					if (item.arw._skip === true) {
						item.arw._skip = false;
					} else if (item.polygon.style.display != "none") {
						item.obj.renderAs = "dhx_toolbar_btn dhxtoolbar_btn_def";
						item.obj.className = item.obj.renderAs;
						item.arw.className = String(item.obj.renderAs).replace("btn","arw");
						main_self.anyUsed = null;
						main_self.conf.touch_id = null;
						window.dhx4.zim.clear(item.polygon._idd);
						item.polygon.style.display = "none";
						if (item.polygon._ie6cover) item.polygon._ie6cover.style.display = "none";
						// fix border
						if (main_self.conf.skin == "dhx_terrace") main_self._improveTerraceButtonSelect(item.id, true);
						// event
						main_self.callEvent("onButtonSelectHide", [item.obj.idd]);
					}
				}
			});
		}
	}
	
	if (typeof(window.addEventListener) != "undefined") {
		window.addEventListener("mousedown", this._doOnClick, false);
		window.addEventListener("touchstart", this._doOnClick, false);
	} else {
		document.body.attachEvent("onmousedown", this._doOnClick);
	}
	
	if (this.conf.icons_size_autoload != null) {
		this.setIconSize(this.conf.icons_size_autoload);
		this.conf.icons_size_autoload = null;
	}
	
	if (this.conf.items_autoload != null) {
		this.loadStruct(this.conf.items_autoload, this.conf.onload_autoload);
		this.conf.items_autoload = null;
	} else if (this.conf.json_autoload != null) {
		this.loadStruct(this.conf.json_autoload, this.conf.onload_autoload);
		this.conf.json_autoload = null;
	} else if (this.conf.xml_autoload != null) {
		this.loadStruct(this.conf.xml_autoload, this.conf.onload_autoload);
		this.conf.xml_autoload = null;
	}
	
	if (this.conf.align_autostart != this.conf.align) {
		this.setAlign(this.conf.align_autostart);
		this.conf.align_autostart = null;
	}
	
	if (typeof(this.conf.auto_onclick) == "function") {
		this.attachEvent("onClick", this.conf.auto_onclick);
	} else if (typeof(this.conf.auto_onclick) == "string" && typeof(window[this.conf.auto_onclick]) == "function") {
		this.attachEvent("onClick", window[this.conf.auto_onclick]);
	}
	
	//
	return this;
}
dhtmlXToolbarObject.prototype.addSpacer = function(nextToId) {
	var nti = this.idPrefix+nextToId;
	if (this._spacer != null) {
		// spacer already at specified position
		if (this._spacer.idd == nextToId) return;
		// if current spacer contain nextToId item
		// move all items from first to nextToId to this.base
		if (this._spacer == this.objPull[nti].obj.parentNode) {
			var doMove = true;
			while (doMove) {
				var idd = this._spacer.childNodes[0].idd;
				this.base.appendChild(this._spacer.childNodes[0]);
				if (idd == nextToId || this._spacer.childNodes.length == 0) {
					if (this.objPull[nti].arw != null) this.base.appendChild(this.objPull[nti].arw);
					doMove = false;
				}
			}
			this._spacer.idd = nextToId;
			this._fixSpacer();
			return;
		}
		// if this.base contain nextToId item, move (insertBefore[0])
		if (this.base == this.objPull[nti].obj.parentNode) {
			var doMove = true;
			var chArw = (this.objPull[nti].arw!=null);
			while (doMove) {
				var q = this.base.childNodes.length-1;
				if (chArw == true) if (this.base.childNodes[q] == this.objPull[nti].arw) doMove = false;
				if (this.base.childNodes[q].idd == nextToId) doMove = false;
				if (doMove) { if (this._spacer.childNodes.length > 0) this._spacer.insertBefore(this.base.childNodes[q], this._spacer.childNodes[0]); else this._spacer.appendChild(this.base.childNodes[q]); }
			}
			this._spacer.idd = nextToId;
			this._fixSpacer();
			return;
		}
		
	} else {
		var np = null;
		for (var q=0; q<this.base.childNodes.length; q++) {
			if (this.base.childNodes[q] == this.objPull[this.idPrefix+nextToId].obj) {
				np = q;
				if (this.objPull[this.idPrefix+nextToId].arw != null) np = q+1;
			}
		}
		if (np != null) {
			this._spacer = document.createElement("DIV");
			this._spacer.className = (this.conf.align=="right"?" dhxtoolbar_float_left":" dhxtoolbar_float_right");
			this._spacer.dir = "ltr";
			this._spacer.idd = nextToId;
			while (this.base.childNodes.length > np+1) this._spacer.appendChild(this.base.childNodes[np+1]);
			this.cont.appendChild(this._spacer);
			this._fixSpacer();
		}
	}
	if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
}
dhtmlXToolbarObject.prototype.removeSpacer = function() {
	if (!this._spacer) return;
	while (this._spacer.childNodes.length > 0) this.base.appendChild(this._spacer.childNodes[0]);
	this._spacer.parentNode.removeChild(this._spacer);
	this._spacer = null;
	if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
}
dhtmlXToolbarObject.prototype._fixSpacer = function() {
	// IE icons mixing fix
	if (typeof(window.addEventListener) == "undefined" && this._spacer != null) {
		this._spacer.style.borderLeft = "1px solid #a4bed4";
		var k = this._spacer;
		window.setTimeout(function(){k.style.borderLeft="0px solid #a4bed4";k=null;},1);
	}
}

dhtmlXToolbarObject.prototype.getType = function(itemId) {
	var parentId = this.getParentId(itemId);
	if (parentId != null) {
		var typeExt = null;
		var itemData = this.objPull[this.idPrefix+parentId]._listOptions[itemId];
		if (itemData != null) if (itemData.sep != null) typeExt = "buttonSelectSeparator"; else typeExt = "buttonSelectButton";
		return typeExt;
	} else {
		if (this.objPull[this.idPrefix+itemId] == null) return null;
		return this.objPull[this.idPrefix+itemId]["type"];
	}
}

dhtmlXToolbarObject.prototype.getTypeExt = function(itemId) {
	var type = this.getType(itemId);
	if (type == "buttonSelectButton" || type == "buttonSelectSeparator") {
		if (type == "buttonSelectButton") type = "button"; else type = "separator";
		return type;
	}
	return null;
}
dhtmlXToolbarObject.prototype.inArray = function(array, value) {
	for (var q=0; q<array.length; q++) { if (array[q]==value) return true; }
	return false;
}
dhtmlXToolbarObject.prototype.getParentId = function(listId) {
	var parentId = null;
	for (var a in this.objPull) if (this.objPull[a]._listOptions) for (var b in this.objPull[a]._listOptions) if (b == listId) parentId = String(a).replace(this.idPrefix,"");
	return parentId;
}
/* adding items */
dhtmlXToolbarObject.prototype._addItem = function(itemData, pos) {
	if (typeof(itemData.text) == "string") {
		itemData.text = window.dhx4.trim(itemData.text);
		if (itemData.text.length == 0) itemData.text = null;
	}
	this._addItemToStorage(itemData, pos);
	if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
}

dhtmlXToolbarObject.prototype.addButton = function(id, pos, text, imgEnabled, imgDisabled) {
	this._addItem({id:id, type:"button", text:text, img:imgEnabled, imgdis:imgDisabled}, pos);
}

dhtmlXToolbarObject.prototype.addText = function(id, pos, text) {
	this._addItem({id:id,type:"text",text:text}, pos);
}

dhtmlXToolbarObject.prototype.addButtonSelect = function(id, pos, text, opts, imgEnabled, imgDisabled, renderSelect, openAll, maxOpen, mode) { 
	var options = [];
	for (var q=0; q<opts.length; q++) {
		var u = {};
		if (opts[q] instanceof Array) {
			u.id = opts[q][0];
			u.type = (opts[q][1]=="obj"?"button":"separator");
			u.text = (opts[q][2]||null);
			u.img = (opts[q][3]||null);
		} else if (opts[q] instanceof Object && opts[q] != null && typeof(opts[q].id) != "undefined" && typeof(opts[q].type) != "undefined") {
			u.id = opts[q].id;
			u.type = (opts[q].type=="obj"?"button":"separator");
			u.text = opts[q].text;
			u.img = opts[q].img;
		}
		options.push(u);
	}
	this._addItem({id:id, type:"buttonSelect", text:text, img:imgEnabled, imgdis:imgDisabled, renderSelect:renderSelect, openAll:openAll, options:options, maxOpen:maxOpen, mode:mode}, pos);
}

dhtmlXToolbarObject.prototype.addButtonTwoState = function(id, pos, text, imgEnabled, imgDisabled) {
	this._addItem({id:id, type:"buttonTwoState", img:imgEnabled, imgdis:imgDisabled, text:text}, pos);
}

dhtmlXToolbarObject.prototype.addSeparator = function(id, pos) {
	this._addItem({id:id,type:"separator"}, pos);
}

dhtmlXToolbarObject.prototype.addSlider = function(id, pos, len, valueMin, valueMax, valueNow, textMin, textMax, tip) {
	this._addItem({id:id, type:"slider", length:len, valueMin:valueMin, valueMax:valueMax, valueNow:valueNow, textMin:textMin, textMax:textMax, toolTip:tip}, pos);
}

dhtmlXToolbarObject.prototype.addInput = function(id, pos, value, width) {
	this._addItem({id:id,type:"buttonInput",value:value,width:width}, pos);
}

dhtmlXToolbarObject.prototype.forEachItem = function(handler) {
	for (var a in this.objPull) {
		if (this.inArray(this.rootTypes, this.objPull[a]["type"])) {
			handler(this.objPull[a]["id"].replace(this.idPrefix,""));
		}
	}
};
(function(){
	var list="isVisible,enableItem,disableItem,isEnabled,setItemText,getItemText,setItemToolTip,getItemToolTip,getInput,setItemImage,setItemImageDis,clearItemImage,clearItemImageDis,setItemState,getItemState,setItemToolTipTemplate,getItemToolTipTemplate,setValue,getValue,setMinValue,getMinValue,setMaxValue,getMaxValue,setWidth,getWidth,setMaxOpen".split(",")
	var ret=[false,"","",false,"","","","","","","","","",false,"","","",null,"",[null,null],"",[null,null],"",null]
	var functor=function(name,res){
		return function(itemId,a,b){
			itemId = this.idPrefix+itemId;
			if (this.objPull[itemId][name] != null) return this.objPull[itemId][name].call(this.objPull[itemId],a,b); else return res;
		};
	}
	for (var i=0; i<list.length; i++){
		var name=list[i];
		var res=ret[i];
		dhtmlXToolbarObject.prototype[name] = functor(name,res);
	}
})();

dhtmlXToolbarObject.prototype.showItem = function(itemId) {
	itemId = this.idPrefix+itemId;
	if (this.objPull[itemId] != null && this.objPull[itemId].showItem != null) {
		this.objPull[itemId].showItem();
		if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
	}
}

dhtmlXToolbarObject.prototype.hideItem = function(itemId) {
	itemId = this.idPrefix+itemId;
	if (this.objPull[itemId] != null && this.objPull[itemId].hideItem != null) {
		this.objPull[itemId].hideItem();
		if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
	}
}
dhtmlXToolbarObject.prototype.getPosition = function(itemId) {
	return this._getPosition(itemId);
}
dhtmlXToolbarObject.prototype._getPosition = function(id, getRealPosition) {
	
	if (this.objPull[this.idPrefix+id] == null) return null;
	
	var pos = null;
	var w = 0;
	for (var q=0; q<this.base.childNodes.length; q++) {
		if (this.base.childNodes[q].idd != null) {
			if (this.base.childNodes[q].idd == id) pos = w;
			w++;
		}
	}
	if (!pos && this._spacer != null) {
		for (var q=0; q<this._spacer.childNodes.length; q++) {
			if (this._spacer.childNodes[q].idd != null) {
				if (this._spacer.childNodes[q].idd == id) pos = w;
				w++;
			}
		}
	}
	return pos;
}

dhtmlXToolbarObject.prototype.setPosition = function(itemId, pos) {
	this._setPosition(itemId, pos);
}

dhtmlXToolbarObject.prototype._setPosition = function(id, pos) {
	
	if (this.objPull[this.idPrefix+id] == null) return;
	
	var spacerId = null;
	if (this._spacer) {
		spacerId = this._spacer.idd;
		this.removeSpacer();
	}
	
	if (isNaN(pos)) pos = this.base.childNodes.length;
	if (pos < 0) pos = 0;
	
	var item = this.objPull[this.idPrefix+id];
	this.base.removeChild(item.obj);
	if (item.arw) this.base.removeChild(item.arw);
	
	var newPos = this._getIdByPosition(pos, true);
	
	if (newPos[0] == null) {
		this.base.appendChild(item.obj);
		if (item.arw) this.base.appendChild(item.arw);
	} else {
		this.base.insertBefore(item.obj, this.base.childNodes[newPos[1]]);
		if (item.arw) this.base.insertBefore(item.arw, this.base.childNodes[newPos[1]+1]);
	}
	if (spacerId != null) this.addSpacer(spacerId);
	
}
dhtmlXToolbarObject.prototype._getIdByPosition = function(pos, retRealPos) {
	
	var id = null;
	var w = 0;
	var realPos = 0;
	for (var q=0; q<this.base.childNodes.length; q++) {
		if (this.base.childNodes[q]["idd"] != null && id == null) {
			if ((w++) == pos) id = this.base.childNodes[q]["idd"];
		}
		if (id == null) realPos++;
	}
	realPos = (id==null?null:realPos);
	return (retRealPos==true?new Array(id, realPos):id);
}

dhtmlXToolbarObject.prototype.removeItem = function(itemId) {
	this._removeItem(itemId);
	if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
};

dhtmlXToolbarObject.prototype._removeItem = function(itemId) {
	
	var t = this.getType(itemId);
	
	itemId = this.idPrefix+itemId;
	var p = this.objPull[itemId];
	
	
	if ({button:1, buttonTwoState:1}[t] == 1) {
		
		if (window.dhx4.isIE) p.obj.onselectstart = null;
		this._evs.clear.apply(p, [p.obj.evs, p.obj]);
		
		for (var a in p.obj) if (typeof(p.obj[a]) == "function") p.obj[a] = null;
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		for (var a in p) p[a] = null;
		
	}
	
	if (t == "buttonSelect") {
		
		for (var a in p._listOptions) this.removeListOption(itemId, a);
		p._listOptions = null;
		
		if (p.polygon._ie6cover) {
			document.body.removeChild(p.polygon._ie6cover);
			p.polygon._ie6cover = null;
		}
		
		p.p_tbl.removeChild(p.p_tbody);
		p.polygon.removeChild(p.p_tbl);
		p.polygon.onselectstart = null;
		document.body.removeChild(p.polygon);
		
		if (window.dhx4.isIE) {
			p.obj.onselectstart = null;
			p.arw.onselectstart = null;
		}
		this._evs.clear.apply(p, [p.obj.evs, p.obj]);
		this._evs.clear.apply(p, [p.arw.evs, p.arw]);
		
		for (var a in p.obj) if (typeof(p.obj[a]) == "function") p.obj[a] = null;
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		for (var a in p.arw) if (typeof(p.arw[a]) == "function") p.arw[a] = null;
		p.arw.parentNode.removeChild(p.arw);
		p.arw = null;
		
		for (var a in p) p[a] = null;
		
		
	}
	
	if (t == "buttonInput") {
		
		p.obj.childNodes[0].onkeydown = null;
		p.obj.removeChild(p.obj.childNodes[0]);
		
		p.obj.w = null;
		p.obj.idd = null;
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		p.id = null;
		p.type = null;
		
		p.enableItem = null;
		p.disableItem = null;
		p.isEnabled = null;
		p.showItem = null;
		p.hideItem = null;
		p.isVisible = null;
		p.setItemToolTip = null;
		p.getItemToolTip = null;
		p.setWidth = null;
		p.getWidth = null;
		p.setValue = null;
		p.getValue = null;
		p.setItemText = null;
		p.getItemText = null;
		
	}
	
	if (t == "slider") {
		
		if (window.dhx4.isIPad) {
			document.removeEventListener("touchmove", pen._doOnMouseMoveStart, false);
			document.removeEventListener("touchend", pen._doOnMouseMoveEnd, false);
		} else {
			if (typeof(window.addEventListener) == "function") {
				window.removeEventListener("mousemove", p.pen._doOnMouseMoveStart, false);
				window.removeEventListener("mouseup", p.pen._doOnMouseMoveEnd, false);
			} else {
				document.body.detachEvent("onmousemove", p.pen._doOnMouseMoveStart);
				document.body.detachEvent("onmouseup", p.pen._doOnMouseMoveEnd);
			}
		}
		
		p.pen.allowMove = null;
		p.pen.initXY = null;
		p.pen.maxX = null;
		p.pen.minX = null;
		p.pen.nowX = null;
		p.pen.newNowX = null;
		p.pen.valueMax = null;
		p.pen.valueMin = null;
		p.pen.valueNow = null;
		
		p.pen._definePos = null;
		p.pen._detectLimits = null;
		p.pen._doOnMouseMoveStart = null;
		p.pen._doOnMouseMoveEnd = null;
		p.pen.onmousedown = null;
		
		p.obj.removeChild(p.pen);
		p.pen = null;
		
		p.label.tip = null;
		document.body.removeChild(p.label);
		p.label = null;
		
		p.obj.onselectstart = null;
		p.obj.idd = null;
		while (p.obj.childNodes.length > 0) p.obj.removeChild(p.obj.childNodes[0]);
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		p.id = null;
		p.type = null;
		p.state = null;
		
		p.enableItem = null;
		p.disableItem = null;
		p.isEnabled = null;
		p.setItemToolTipTemplate = null;
		p.getItemToolTipTemplate = null;
		p.setMaxValue = null;
		p.setMinValue = null;
		p.getMaxValue = null;
		p.getMinValue = null;
		p.setValue = null;
		p.getValue = null;
		p.showItem = null;
		p.hideItem = null;
		p.isVisible = null;
		
	}
	
	if (t == "separator") {
		
		p.obj.onselectstart = null;
		p.obj.idd = null;
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		p.id = null;
		p.type = null;
		
		p.showItem = null;
		p.hideItem = null;
		p.isVisible = null;
		
	}
	
	if (t == "text") {
		
		p.obj.onselectstart = null;
		p.obj.idd = null;
		p.obj.parentNode.removeChild(p.obj);
		p.obj = null;
		
		p.id = null;
		p.type = null;
		
		p.showItem = null;
		p.hideItem = null;
		p.isVisible = null;
		p.setWidth = null;
		p.setItemText = null;
		p.getItemText = null;
		
	}
	
	t = null;
	p = null;
	this.objPull[this.idPrefix+itemId] = null;
	delete this.objPull[this.idPrefix+itemId];
	
	
};
//#tool_list:06062008{
(function(){
	var list="addListOption,removeListOption,showListOption,hideListOption,isListOptionVisible,enableListOption,disableListOption,isListOptionEnabled,setListOptionPosition,getListOptionPosition,setListOptionText,getListOptionText,setListOptionToolTip,getListOptionToolTip,setListOptionImage,getListOptionImage,clearListOptionImage,forEachListOption,getAllListOptions,setListOptionSelected,getListOptionSelected".split(",")
	var functor = function(name){
				return function(parentId,a,b,c,d,e){
				parentId = this.idPrefix+parentId;
				if (this.objPull[parentId] == null) return;
				if (this.objPull[parentId]["type"] != "buttonSelect") return;
				return this.objPull[parentId][name].call(this.objPull[parentId],a,b,c,d,e);
			}
		}
	for (var i=0; i<list.length; i++){
		var name=list[i];
		dhtmlXToolbarObject.prototype[name]=functor(name)
	}
})();

dhtmlXToolbarObject.prototype._rtlParseBtn = function(t1, t2) {
	return t1+t2;
}
/*****************************************************************************************************************************************************************
	object: separator
*****************************************************************************************************************************************************************/
dhtmlXToolbarObject.prototype._separatorObject = function(that, id, data) {
	//
	this.id = that.idPrefix+id;
	this.obj = document.createElement("DIV");
	this.obj.className = "dhx_toolbar_sep";
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.idd = String(id);
	this.obj.title = (data.title||"");
	this.obj.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	
	this.obj.ontouchstart = function(e){
		e = e||event;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		e.cancelBubble = true;
		return false;
	}
	
	//
	// add object
	that.base.appendChild(this.obj);
	
	// functions
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	//
	return this;
}
/*****************************************************************************************************************************************************************
	object: text
*****************************************************************************************************************************************************************/
dhtmlXToolbarObject.prototype._textObject = function(that, id, data) {
	this.id = that.idPrefix+id;
	this.obj = document.createElement("DIV");
	this.obj.className = "dhx_toolbar_text";
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.idd = String(id);
	this.obj.title = (data.title||"");
	this.obj.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	
	this.obj.ontouchstart = function(e){
		e = e||event;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		e.cancelBubble = true;
		return false;
	}
	
	//
	this.obj.innerHTML = (data.text||"");
	//
	that.base.appendChild(this.obj);
	//
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	this.setItemText = function(text) {
		this.obj.innerHTML = text;
	}
	this.getItemText = function() {
		return this.obj.innerHTML;
	}
	this.setWidth = function(width) {
		this.obj.style.width = width+"px";
	}
	this.setItemToolTip = function(t) {
		this.obj.title = t;
	}
	this.getItemToolTip = function() {
		return this.obj.title;
	}
	//
	return this;
}
/*****************************************************************************************************************************************************************
	object: button
******************************************************************************************************************************************************************/
dhtmlXToolbarObject.prototype._buttonObject = function(that, id, data) {
	
	this.id = that.idPrefix+id;
	this.state = (data.enabled!=null?false:true);
	this.imgEn = (data.img||"");
	this.imgDis = (data.imgdis||"");
	this.img = (this.state?(this.imgEn!=""?this.imgEn:""):(this.imgDis!=""?this.imgDis:""));
	
	//
	this.obj = document.createElement("DIV");
	this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.state?"def":"dis");
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.allowClick = false;
	this.obj.extAction = (data.action||null);
	this.obj.renderAs = this.obj.className;
	this.obj.idd = String(id);
	this.obj.title = (data.title||"");
	this.obj.pressed = false;
	//
	var img = (that.conf.icons_css?"<i class='"+that.conf.icons_path+this.img+"'></i>":"<img src='"+that.conf.icons_path+this.img+"'>");
	this.obj.innerHTML = that._rtlParseBtn((this.img!=""?img:""), (data.text!=null?"<div class='dhxtoolbar_text'>"+data.text+"</div>":""));
	
	// add object
	that.base.appendChild(this.obj);
	
	if (window.dhx4.isIE) {
		this.obj.onselectstart = function(e) {
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
	}
	
	var t = this;
	this._doOnMouseOver = function(e) {
		e = e||event;
		if (t.state == false || t.obj.pressed == true || t.obj.over == true) return;
		if (t.obj.className.match(/dhxtoolbar_btn_over/gi) == null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_over";
		t.obj.over = true;
	}
	
	this._doOnMouseOut = function(e) {
		e = e||event;
		if (t.state == false) return;
		if (t.obj.className.match(/dhxtoolbar_btn_over/gi) != null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
		t.obj.over = t.obj.pressed = false;
	}
	
	this._doOnMouseDown = function(e) {
		e = e||event;
		if (e.type == "touchstart") {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id != null && that.conf.touch_id != t.id) return; // multiple touches?
			that.conf.touch_id = t.id;
		}
		if (t.state == false) return;
		if (t.obj.className.match(/dhxtoolbar_btn_pres/gi) == null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_pres";
		t.obj.pressed = true;
	}
	
	this._doOnMouseUp = function(e) {
		e = e||event;
		if (e.type == "touchend") {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id == t.id) that.conf.touch_id = null;
		}
		if (t.state == false || t.obj.pressed == false) return;
		if (t.obj.className.match(/dhxtoolbar_btn_pres/gi) != null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(t.obj.over?"over":"def");
		t.obj.pressed = false;
		if (t.obj.extAction){window.setTimeout(function(){try{if(t&&t.obj)window[t.obj.extAction](t.id);}catch(e){}},1);}
		that.callEvent("onClick", [t.obj.idd.replace(that.idPrefix,"")]);
	}
	
	// add mouse events
	this.obj.evs = {
		mouseover: "_doOnMouseOver",
		mouseout: "_doOnMouseOut",
		mousedown: "_doOnMouseDown",
		mouseup: "_doOnMouseUp",
		touchstart: "_doOnMouseDown",
		touchend: "_doOnMouseUp"
	};
	
	that._evs.add.apply(this, [this.obj.evs, this.obj]);
	
	// functions
	this.enableItem = function() {
		that._enableItem(this);
	}
	this.disableItem = function() {
		that._disableItem(this);
	}
	this.isEnabled = function() {
		return this.state;
	}
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	this.setItemText = function(text) {
		that._setItemText(this, text);
	}
	this.getItemText = function() {
		return that._getItemText(this);
	}
	this.setItemImage = function(url) {
		that._setItemImage(this, url, true);
	}
	this.clearItemImage = function() {
		that._clearItemImage(this, true);
	}
	this.setItemImageDis = function(url) {
		that._setItemImage(this, url, false);
	}
	this.clearItemImageDis = function() {
		that._clearItemImage(this, false);
	}
	this.setItemToolTip = function(tip) {
		this.obj.title = tip;
	}
	this.getItemToolTip = function() {
		return this.obj.title;
	}
	return this;
}

/******************************************************************************************************************************************************************
	object: buttonSelect
*******************************************************************************************************************************************************************/
dhtmlXToolbarObject.prototype._buttonSelectObject = function(that, id, data) {
	this.id = that.idPrefix+id;
	this.state = (data.enabled!=null?(data.enabled=="true"?true:false):true);
	this.imgEn = (data.img||"");
	this.imgDis = (data.imgdis||"");
	this.img = (this.state?(this.imgEn!=""?this.imgEn:""):(this.imgDis!=""?this.imgDis:""));
	
	this.mode = (data.mode||"button"); // button, select
	if (this.mode == "select") {
		this.openAll = true;
		this.renderSelect = false;
		if (!data.text||data.text.length==0) data.text = "&nbsp;"
	} else {
		this.openAll = (window.dhx4.s2b(data.openAll)==true);
		this.renderSelect = (data.renderSelect == null ? true : window.dhx4.s2b(data.renderSelect));
	}
	this.maxOpen = (!isNaN(data.maxOpen?data.maxOpen:"")?data.maxOpen:null);
	
	this._maxOpenTest = function() {
		if (!isNaN(this.maxOpen)) {
			if (!that._sbw) {
				var t = document.createElement("DIV");
				t.className = "dhxtoolbar_maxopen_test";
				document.body.appendChild(t);
				var k = document.createElement("DIV");
				k.className = "dhxtoolbar_maxopen_test2";
				t.appendChild(k);
				that._sbw = t.offsetWidth-k.offsetWidth;
				t.removeChild(k);
				k = null;
				document.body.removeChild(t);
				t = null;
			}
		}
	}
	this._maxOpenTest();
	
	//
	this.obj = document.createElement("DIV");
	this.obj.allowClick = false;
	this.obj.extAction = (data.action||null);
	this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.state?"def":"dis");
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.renderAs = this.obj.className;
	this.obj.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	this.obj.idd = String(id);
	this.obj.title = (data.title||"");
	this.obj.pressed = false;
	
	this.callEvent = false;
	
	
	var img = (that.conf.icons_css?"<i class='"+that.conf.icons_path+this.img+"'></i>":"<img src='"+that.conf.icons_path+this.img+"'>");
	this.obj.innerHTML = that._rtlParseBtn((this.img!=""?img:""),(data.text!=null?"<div class='dhxtoolbar_text'>"+data.text+"</div>":""));
	
	// add object
	that.base.appendChild(this.obj);
	
	this.arw = document.createElement("DIV");
	this.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_"+(this.state?"def":"dis");
	this.arw.style.display = this.obj.style.display;
	this.arw.innerHTML = "<div class='arwimg'>&nbsp;</div>";
	
	this.arw.title = this.obj.title;
	this.arw.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	that.base.appendChild(this.arw);
	
	var self = this;
	
	if (window.dhx4.isIE) {
		this.arw.onselectstart = this.obj.onselectstart = function(e) {
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
	}
	
	this._doOnMouseOver = function(e) {
		e = e||event;
		if (self.state == false || self.obj.over == true || that.anyUsed == self.obj.idd) return;
		if (self.obj.className.match(/dhxtoolbar_btn_over/gi) == null) {
			self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_over";
			self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_over";
		}
		self.obj.over = true;
	}
	
	this._doOnMouseOut = function(e) {
		e = e||event;
		if (self.state == false || that.anyUsed == self.obj.idd || that.anyUsed == self.obj.idd) return;
		if (self.obj.className.match(/dhxtoolbar_btn_over/gi) != null) {
			self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
			self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_def";
		}
		self.obj.over = self.obj.pressed = false;
	}
	
	this._doOnMouseDown = function(e) {
		e = e||event;
		if (e.type == "touchstart") {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id != null && that.conf.touch_id != self.id) return; // multiple touches?
			that.conf.touch_id = self.id;
		}
		if (self.state == false) return;
		
		if (that.anyUsed == self.obj.idd) {
			// hide polygon
			if (self.obj.over == true) {
				self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_over";
				self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_over";
			} else {
				self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
				self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_def";
			}
			self._hidePoly(true);
			that.anyUsed = null;
		} else {
			// show polygon
			var node = (e.target||e.srcElement);
			if (self.openAll == true || node == self.arw || node.parentNode == self.arw) {
				if (e.type == "touchstart") self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_over";
				self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_pres";
				self.arw._skip = true;
				self._showPoly(true);
				that.anyUsed = self.obj.idd;
			} else {
				if (self.obj.className.match(/dhxtoolbar_btn_pres/gi) == null) {
					self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_pres";
					self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_pres";
				}
				self.obj.pressed = true;
			}
		}
	}
	
	this._doOnMouseUp = function(e) {
		e = e||event;
		if (e.type == "touchend") {
			if (self.polygon.style.display == "") return;
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id == self.id) that.conf.touch_id = null;
		}
		if (self.state == false || self.obj.pressed == false) return;
		if (self.obj.className.match(/dhxtoolbar_btn_pres/gi) != null) {
			self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(self.obj.over?"over":"def");
			self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_"+(self.obj.over?"over":"def");
		}
		// event
		if (this.extAction) {var k = this;window.setTimeout(function(){try{window[k.extAction](id);}catch(e){};k=null;},1);}
		that.callEvent("onClick", [self.obj.idd.replace(that.idPrefix,"")]);
	}
	
	// add mouse events
	this.arw.evs = {
		mouseover: "_doOnMouseOver",
		mouseout: "_doOnMouseOut",
		mousedown: "_doOnMouseDown",
		touchstart: "_doOnMouseDown"
	};
	that._evs.add.apply(this, [this.arw.evs, this.arw]);
	
	this.obj.evs = {
		mouseover: "_doOnMouseOver",
		mouseout: "_doOnMouseOut",
		mousedown: "_doOnMouseDown",
		mouseup: "_doOnMouseUp",
		touchstart: "_doOnMouseDown",
		touchend: "_doOnMouseUp"
	};
	that._evs.add.apply(this, [this.obj.evs, this.obj]);
	
	this._showPoly = function(callEvent) {
		// hide other if already opened
		if (that.anyUsed != null) {
			if (that.objPull[that.idPrefix+that.anyUsed].type == "buttonSelect") {
				var item = that.objPull[that.idPrefix+that.anyUsed];
				if (item.polygon.style.display != "none") {
					item.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
					item.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_def";
					item.obj.over = false;
					window.dhx4.zim.clear(item.polygon._idd);
					item.polygon.style.display = "none";
					if (item.polygon._ie6cover) item.polygon._ie6cover.style.display = "none";
					// fix border
					if (that.conf.skin == "dhx_terrace") that._improveTerraceButtonSelect(item.id, true);
					// event
					that.callEvent("onButtonSelectHide", [item.obj.idd]);
				}
			}
		}
		// show
		this.polygon.style.top = "0px";
		this.polygon.style.visibility = "hidden";
		this.polygon.style.zIndex = window.dhx4.zim.reserve(this.polygon._idd);
		this.polygon.style.display = "";
		// fix border
		if (that.conf.skin == "dhx_terrace") that._improveTerraceButtonSelect(this.id, false);
		// check maxOpen
		this._fixMaxOpenHeight(this.maxOpen||null);
		// detect overlay by Y axis
		that._autoDetectVisibleArea();
		// calculate top position
		var newTop = window.dhx4.absTop(this.obj)+this.obj.offsetHeight+that.conf.sel_ofs_y;
		var newH = this.polygon.offsetHeight;
		if (newTop + newH > that.tY2) {
			// if maxOpen mode enabled, check if at bottom at least one item can be shown
			// and show it, if no space - show on top. in maxOpen mode not enabled, show at top
			var k0 = (this.maxOpen!=null?Math.floor((that.tY2-newTop)/22):0); // k0 = count of items that can be visible
			if (k0 >= 1) {
				this._fixMaxOpenHeight(k0);
			} else {
				newTop = window.dhx4.absTop(this.obj)-newH-that.conf.sel_ofs_y;
				if (newTop < 0) newTop = 0;
			}
		}
		this.polygon.style.top = newTop+"px";
		// calculate left position
		if (that.rtl) {
			this.polygon.style.left = window.dhx4.absLeft(this.obj)+this.obj.offsetWidth-this.polygon.offsetWidth+that.conf.sel_ofs_x+"px";
		} else {
			var x1 = document.body.scrollLeft;
			var x2 = x1+(window.innerWidth||document.body.clientWidth);
			var newLeft = window.dhx4.absLeft(this.obj)+that.conf.sel_ofs_x;
			if (newLeft+this.polygon.offsetWidth > x2) newLeft = window.dhx4.absLeft(this.arw)+this.arw.offsetWidth-this.polygon.offsetWidth;
			this.polygon.style.left = Math.max(newLeft,5)+"px";
		}
		this.polygon.style.visibility = "visible";
		// show IE6 cover if needed
		if (this.polygon._ie6cover) {
			this.polygon._ie6cover.style.left = this.polygon.style.left;
			this.polygon._ie6cover.style.top = this.polygon.style.top;
			this.polygon._ie6cover.style.width = this.polygon.offsetWidth+"px";
			this.polygon._ie6cover.style.height = this.polygon.offsetHeight+"px";
			this.polygon._ie6cover.style.display = "";
		}
		// event, added in 4.5
		if (callEvent) that.callEvent("onButtonSelectShow", [this.obj.idd]);
	}
	
	this._hidePoly = function(callEvent) {
		window.dhx4.zim.clear(this.polygon._idd);
		this.polygon.style.display = "none";
		if (this.polygon._ie6cover) this.polygon._ie6cover.style.display = "none";
		// fix border
		if (that.conf.skin == "dhx_terrace") that._improveTerraceButtonSelect(this.id, true);
		if (callEvent) that.callEvent("onButtonSelectHide", [this.obj.idd]); // event, added in 4.5
		// reset touch event if any
		that.conf.touch_id = null;
	}
	
	this.obj.iddPrefix = that.idPrefix;
	this._listOptions = {};
	
	this._fixMaxOpenHeight = function(maxOpen) {
		var h = "auto";
		var h0 = false;
		if (maxOpen !== null) {
			var t = 0;
			for (var a in this._listOptions) t++;
			if (t > maxOpen) {
				this._ph = 22*maxOpen;
				h = this._ph+"px";
			} else {
				h0 = true;
			}
		}
		this.polygon.style.width = "auto";
		this.polygon.style.height = "auto";
		if (!h0 && self.maxOpen != null) {
			this.polygon.style.width = this.p_tbl.offsetWidth+that._sbw+"px";
			this.polygon.style.height = h;
		}
	}
	
	// inner objects: separator
	this._separatorButtonSelectObject = function(id, data, pos) {
		
		this.obj = {};
		this.obj.tr = document.createElement("TR");
		this.obj.tr.className = "tr_sep";
		this.obj.tr.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		this.obj.td = document.createElement("TD");
		this.obj.td.colSpan = "2";
		this.obj.td.className = "td_btn_sep";
		this.obj.td.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		
		if (isNaN(pos)) pos = self.p_tbody.childNodes.length+1; else if (pos < 1) pos = 1;
		if (pos > self.p_tbody.childNodes.length) self.p_tbody.appendChild(this.obj.tr); else self.p_tbody.insertBefore(this.obj.tr, self.p_tbody.childNodes[pos-1]);
		
		this.obj.tr.appendChild(this.obj.td);
		
		this.obj.sep = document.createElement("DIV");
		this.obj.sep.className = "btn_sep";
		this.obj.sep.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		this.obj.td.appendChild(this.obj.sep);
		
		self._listOptions[id] = this.obj;
		return this;
	}
	// inner objects: button
	this._buttonButtonSelectObject = function(id, data, pos) {
		
		var en = true;
		if (typeof(data.enabled) != "undefined") {
			en = window.dhx4.s2b(data.enabled);
		} else if (typeof(data.disabled) != "undefined") {
			en = window.dhx4.s2b(data.disabled);
		}
		
		this.obj = {};
		this.obj.tr = document.createElement("TR");
		this.obj.tr.en = en;
		this.obj.tr.extAction = (data.action||null);
		this.obj.tr._selected = (data.selected!=null);
		this.obj.tr.className = "tr_btn"+(this.obj.tr.en?(this.obj.tr._selected&&self.renderSelect?" tr_btn_selected":""):" tr_btn_disabled");
		this.obj.tr.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		this.obj.tr.idd = String(id);
		
		if (isNaN(pos)) pos = self.p_tbody.childNodes.length+1; else if (pos < 1) pos = 1;
		if (pos > self.p_tbody.childNodes.length) self.p_tbody.appendChild(this.obj.tr); else self.p_tbody.insertBefore(this.obj.tr, self.p_tbody.childNodes[pos-1]);
		
		this.obj.td_a = document.createElement("TD");
		this.obj.td_a.className = "td_btn_img";
		this.obj.td_a.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		this.obj.td_b = document.createElement("TD");
		this.obj.td_b.className = "td_btn_txt";
		this.obj.td_b.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; return false; }
		
		if (that.rtl) {
			this.obj.tr.appendChild(this.obj.td_b);
			this.obj.tr.appendChild(this.obj.td_a);
		} else {
			this.obj.tr.appendChild(this.obj.td_a);
			this.obj.tr.appendChild(this.obj.td_b);
		}
		
		// image
		if (data.img != null) {
			if (that.conf.icons_css == true) {
				this.obj.td_a.innerHTML = "<i class='"+that.conf.icons_path+data.img+"'></i>";
			} else {
				this.obj.td_a.innerHTML = "<img class='btn_sel_img' src='"+that.conf.icons_path+data.img+"' border='0'>";
			}
			this.obj.tr._img = data.img;
		} else {
			this.obj.td_a.innerHTML = "&nbsp;";
		}
		
		// text
		var itemText = (data.text!=null?data.text:(data.itemText||""));
		this.obj.td_b.innerHTML = "<div class='btn_sel_text'>"+itemText+"</div>";
		
		this.obj.tr.onmouseover = function(e) {
			e = e||event;
			if (e.type.match(/touch/) != null) return;
			if (!this.en || (this._selected && self.renderSelect)) return;
			this.className = "tr_btn tr_btn_over";
		}
		
		this.obj.tr.onmouseout = function(e) {
			e = e||event;
			if (e.type.match(/touch/) != null) return;
			if (!this.en) return;
			if (this._selected && self.renderSelect) {
				if (String(this.className).search("tr_btn_selected") == -1) this.className = "tr_btn tr_btn_selected";
			} else {
				this.className = "tr_btn";
			}
		}
		
		this.obj.tr.ontouchstart = this.obj.tr.onmousedown = function(e) {
			e = e||event;
			if (this._etype == null) this._etype = e.type;
		}
		this.obj.tr.onclick = function(e) {
			
			e = e||event;
			e.cancelBubble = true;
			if (!this.en) return;
			
			self.setListOptionSelected(this.idd.replace(that.idPrefix,""));
			
			self.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
			self.arw.className = "dhx_toolbar_arw dhxtoolbar_btn_def";
			self.obj.over = false;
			
			if (this._etype != null && this._etype.match(/touch/) == null) {
				window.dhx4.zim.clear(self.polygon._idd);
				self.polygon.style.display = "none";
				if (self.polygon._ie6cover) self.polygon._ie6cover.style.display = "none";
			} else {
				var p = self.polygon;
				window.setTimeout(function(){
					window.dhx4.zim.clear(p._idd);
					p.style.display = "none";
					p = null;
				}, 500);
			}
			this._etype = null;
			
			// fix border
			if (that.conf.skin == "dhx_terrace") that._improveTerraceButtonSelect(self.id, true);
			that.anyUsed = null;
			that.conf.touch_id = null;
			// event
			that.callEvent("onButtonSelectHide", [self.obj.idd]);
			// event
			var id = this.idd.replace(that.idPrefix,"");
			if (this.extAction) try {window[this.extAction](id);} catch(e){};
			that.callEvent("onClick", [id]);
		}		
		self._listOptions[id] = this.obj;
		
		return this;
		
	}
	
	// add polygon
	this.polygon = document.createElement("DIV");
	this.polygon.dir = "ltr";
	this.polygon.style.display = "none";
	this.polygon.className = "dhx_toolbar_poly_"+that.conf.skin+" dhxtoolbar_icons_"+that.conf.iconSize+that.conf.cssShadow;
	this.polygon.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	this.polygon.onmousedown = function(e) { e = e||event; e.cancelBubble = true; }
	this.polygon.style.overflowY = "auto";
	this.polygon._idd = window.dhx4.newId();
	
	this.polygon.ontouchstart = function(e){
		e = e||event;
		e.cancelBubble = true;
	}
	this.polygon.ontouchend = function(e){
		e = e||event;
		e.cancelBubble = true;
	}
	
	this.p_tbl = document.createElement("TABLE");
	this.p_tbl.className = "buttons_cont";
	this.p_tbl.cellSpacing = "0";
	this.p_tbl.cellPadding = "0";
	this.p_tbl.border = "0";
	this.polygon.appendChild(this.p_tbl);
	
	this.p_tbody = document.createElement("TBODY");
	this.p_tbl.appendChild(this.p_tbody);
	
	//
	if (data.options != null) {
		for (var q=0; q<data.options.length; q++) {
			var t = "_"+(data.options[q].type||"")+"ButtonSelectObject";
			if (data.options[q].id == null) data.options[q].id = that._genStr(24);
			if (typeof(this[t]) == "function") new this[t](data.options[q].id, data.options[q]);
		}
	}
	
	document.body.appendChild(this.polygon);
	
	// add poly ie6cover
	if (window.dhx4.isIE6) {
		this.polygon._ie6cover = document.createElement("IFRAME");
		this.polygon._ie6cover.frameBorder = 0;
		this.polygon._ie6cover.style.position = "absolute";
		this.polygon._ie6cover.style.border = "none";
		this.polygon._ie6cover.style.backgroundColor = "#000000";
		this.polygon._ie6cover.style.filter = "alpha(opacity=100)";
		this.polygon._ie6cover.style.display = "none";
		this.polygon._ie6cover.setAttribute("src","javascript:false;");
		document.body.appendChild(this.polygon._ie6cover);
	}
	
	// functions
	// new engine
	this.setWidth = function(width) {
		this.obj.style.width = width-this.arw.offsetWidth+"px";
		this.polygon.style.width = this.obj.offsetWidth+this.arw.offsetWidth-2+"px";
		this.p_tbl.style.width = this.polygon.style.width;
	}
	this.enableItem = function() {
		that._enableItem(this);
	}
	this.disableItem = function() {
		that._disableItem(this);
	}
	this.isEnabled = function() {
		return this.state;
	}
	this.showItem = function() {
		this.obj.style.display = "";
		this.arw.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
		this.arw.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	this.setItemText = function(text) {
		that._setItemText(this, text);
	}
	this.getItemText = function() {
		return that._getItemText(this);
	}
	this.setItemImage = function(url) {
		that._setItemImage(this, url, true);
	}
	this.clearItemImage = function() {
		that._clearItemImage(this, true);
	}
	this.setItemImageDis = function(url) {
		that._setItemImage(this, url, false);
	}
	this.clearItemImageDis = function() {
		that._clearItemImage(this, false);
	}
	this.setItemToolTip = function(tip) {
		this.obj.title = tip;
		this.arw.title = tip;
	}
	this.getItemToolTip = function() {
		return this.obj.title;
	}
	/* list option functions */
	// new engine
	this.addListOption = function(id, pos, type, text, img) {
		if (!(type == "button" || type == "separator")) return;
		var dataItem = {id:id,type:type,text:text,img:img};
		new this["_"+type+"ButtonSelectObject"](id, dataItem, pos);
	}
	// new engine
	this.removeListOption = function(id) {
		if (!this._isListButton(id, true)) return;
		var item = this._listOptions[id];
		if (item.td_a != null && item.td_b != null) {
			// button
			item.td_a.onselectstart = null;
			item.td_b.onselectstart = null;
			while (item.td_a.childNodes.length > 0) item.td_a.removeChild(item.td_a.childNodes[0]);
			while (item.td_b.childNodes.length > 0) item.td_b.removeChild(item.td_b.childNodes[0]);
			item.tr.onselectstart = null;
			item.tr.onmouseover = null;
			item.tr.onmouseout = null;
			item.tr.onclick = null;
			item.tr.ontouchstart = null;
			item.tr.onmousedown = null;
			while (item.tr.childNodes.length > 0) item.tr.removeChild(item.tr.childNodes[0]);
			item.tr.parentNode.removeChild(item.tr);
			item.td_a = null;
			item.td_b = null;
			item.tr = null;
		} else {
			// separator
			item.sep.onselectstart = null;
			item.td.onselectstart = null;
			item.tr.onselectstart = null;
			while (item.td.childNodes.length > 0) item.td.removeChild(item.td.childNodes[0]);
			while (item.tr.childNodes.length > 0) item.tr.removeChild(item.tr.childNodes[0]);
			item.tr.parentNode.removeChild(item.tr);
			item.sep = null;
			item.td = null;
			item.tr = null;
		}
		item = null;
		this._listOptions[id] = null;
		try { delete this._listOptions[id]; } catch(e) {}
	}
	// new engine
	this.showListOption = function(id) {
		if (!this._isListButton(id, true)) return;
		this._listOptions[id].tr.style.display = "";
	}
	// new engine
	this.hideListOption = function(id) {
		if (!this._isListButton(id, true)) return;
		this._listOptions[id].tr.style.display = "none";
	}
	// new engine
	this.isListOptionVisible = function(id) {
		if (!this._isListButton(id, true)) return;
		return (this._listOptions[id].tr.style.display != "none");
	}
	// new engine
	this.enableListOption = function(id) {
		if (!this._isListButton(id)) return;
		this._listOptions[id].tr.en = true;
		this._listOptions[id].tr.className = "tr_btn"+(this._listOptions[id].tr._selected&&that.renderSelect?" tr_btn_selected":"");
	}
	// new engine
	this.disableListOption = function(id) {
		if (!this._isListButton(id)) return;
		this._listOptions[id].tr.en = false;
		this._listOptions[id].tr.className = "tr_btn tr_btn_disabled";
	}
	// new engine
	this.isListOptionEnabled = function(id) {
		if (!this._isListButton(id)) return;
		return this._listOptions[id].tr.en;
	}
	// new engine
	this.setListOptionPosition = function(id, pos) {
		if (!this._listOptions[id] || this.getListOptionPosition(id) == pos || isNaN(pos)) return;
		if (pos < 1) pos = 1;
		var tr = this._listOptions[id].tr;
		this.p_tbody.removeChild(tr);
		if (pos > this.p_tbody.childNodes.length) this.p_tbody.appendChild(tr); else this.p_tbody.insertBefore(tr, this.p_tbody.childNodes[pos-1]);
		tr = null;
	}
	// new engine
	this.getListOptionPosition = function(id) {
		var pos = -1;
		if (!this._listOptions[id]) return pos;
		for (var q=0; q<this.p_tbody.childNodes.length; q++) if (this.p_tbody.childNodes[q] == this._listOptions[id].tr) pos=q+1;
		return pos;
	}
	// new engine
	this.setListOptionImage = function(id, img) {
		if (!this._isListButton(id)) return;
		var td = this._listOptions[id].tr.childNodes[(that.rtl?1:0)];
		td.innerHTML = (that.conf.icons_css?"<i class='"+that.conf.icons_path+img+"'></i>":"<img src='"+that.conf.icons_path+img+"' class='btn_sel_img'>");
		td = null;
	}
	// new engine
	this.getListOptionImage = function(id) {
		if (!this._isListButton(id)) return;
		var td = this._listOptions[id].tr.childNodes[(that.rtl?1:0)];
		var src = null;
		if (td.childNodes.length > 0) src = td.childNodes[0][(that.conf.icons_css?"className":"src")];
		td = null;
		return src;
	}
	// new engine
	this.clearListOptionImage = function(id) {
		if (!this._isListButton(id)) return;
		var td = this._listOptions[id].tr.childNodes[(that.rtl?1:0)];
		while (td.childNodes.length > 0) td.removeChild(td.childNodes[0]);
		td.innerHTML = "&nbsp;";
		td = null;
	}
	// new engine
	this.setListOptionText = function(id, text) {
		if (!this._isListButton(id)) return;
		this._listOptions[id].tr.childNodes[(that.rtl?0:1)].childNodes[0].innerHTML = text;
	}
	// new engine
	this.getListOptionText = function(id) {
		if (!this._isListButton(id)) return;
		return this._listOptions[id].tr.childNodes[(that.rtl?0:1)].childNodes[0].innerHTML;
	}
	// new engine
	this.setListOptionToolTip = function(id, tip) {
		if (!this._isListButton(id)) return;
		this._listOptions[id].tr.title = tip;
	}
	// new engine
	this.getListOptionToolTip = function(id) {
		if (!this._isListButton(id)) return;
		return this._listOptions[id].tr.title;
	}
	// works
	this.forEachListOption = function(handler) {
		for (var a in this._listOptions) handler(a);
	}
	// works, return array with ids
	this.getAllListOptions = function() {
		var listData = new Array();
		for (var a in this._listOptions) listData[listData.length] = a;
		return listData;
	}
	// new engine
	this.setListOptionSelected = function(id) {
		for (var a in this._listOptions) {
			var item = this._listOptions[a];
			if (item.td_a != null && item.td_b != null && item.tr.en) {
				if (a == id) {
					item.tr._selected = true;
					item.tr.className = "tr_btn"+(this.renderSelect?" tr_btn_selected":"");
					//
					if (this.mode == "select") {
						if (item.tr._img) this.setItemImage(item.tr._img); else this.clearItemImage();
						this.setItemText(this.getListOptionText(id));
					}
				} else {
					item.tr._selected = false;
					item.tr.className = "tr_btn";
				}
			}
			item = null;
		}
	}
	// new engine
	this.getListOptionSelected = function() {
		var id = null;
		for (var a in this._listOptions) if (this._listOptions[a].tr._selected == true) id = a;
		return id;
	}
	// inner, return tru if list option is button and is exists
	this._isListButton = function(id, allowSeparator) {
		if (this._listOptions[id] == null) return false;
		if (!allowSeparator && this._listOptions[id].tr.className == "tr_sep") return false;
		return true;
	}
	
	this.setMaxOpen = function(r) {
		this._ph = null;
		if (typeof(r) == "number") {
			this.maxOpen = r;
			this._maxOpenTest();
			return;
		}
		this.maxOpen = null;
	}
	
	if (data.width) this.setWidth(data.width);
	
	if (this.mode == "select" && typeof(data.selected) != "undefined") this.setListOptionSelected(data.selected);
	
	//
	return this;
}

/*****************************************************************************************************************************************************************
	object: buttonInput
***************************************************************************************************************************************************************** */
dhtmlXToolbarObject.prototype._buttonInputObject = function(that, id, data) {
	//
	this.id = that.idPrefix+id;
	this.obj = document.createElement("DIV");
	this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.idd = String(id);
	this.obj.w = (data.width!=null?data.width:100);
	this.obj.title = (data.title!=null?data.title:"");
	//
	this.obj.innerHTML = "<input class='dhxtoolbar_input' type='text' style='width:"+this.obj.w+"px;'"+(data.value!=null?" value='"+data.value+"'":"")+">";
	
	var th = that;
	var self = this;
	this.obj.childNodes[0].onkeydown = function(e) {
		e = e||event;
		if (e.keyCode == 13) { th.callEvent("onEnter", [self.obj.idd, this.value]); }
	}
	// add
	that.base.appendChild(this.obj);
	//
	this.enableItem = function() {
		this.obj.childNodes[0].disabled = false;
	}
	this.disableItem = function() {
		this.obj.childNodes[0].disabled = true;
	}
	this.isEnabled = function() {
		return (!this.obj.childNodes[0].disabled);
	}
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display != "none");
	}
	this.setValue = function(value) {
		this.obj.childNodes[0].value = value;
	}
	this.getValue = function() {
		return this.obj.childNodes[0].value;
	}
	this.setWidth = function(width) {
		this.obj.w = width;
		this.obj.childNodes[0].style.width = this.obj.w+"px";
	}
	this.getWidth = function() {
		return this.obj.w;
	}
	this.setItemToolTip = function(tip) {
		this.obj.title = tip;
	}
	this.getItemToolTip = function() {
		return this.obj.title;
	}
	this.getInput = function() {
		return this.obj.firstChild;
	}
	
	if (typeof(data.enabled) != "undefined" && window.dhx4.s2b(data.enabled) == false) {
		this.disableItem();
	}
	
	//
	return this;
}

/*****************************************************************************************************************************************************************
	object: buttonTwoState
***************************************************************************************************************************************************************** */
dhtmlXToolbarObject.prototype._buttonTwoStateObject = function(that, id, data) {
	this.id = that.idPrefix+id;
	this.state = (data.enabled!=null?false:true);
	this.imgEn = (data.img!=null?data.img:"");
	this.imgDis = (data.imgdis!=null?data.imgdis:"");
	this.img = (this.state?(this.imgEn!=""?this.imgEn:""):(this.imgDis!=""?this.imgDis:""));
	//
	this.obj = document.createElement("DIV");
	this.obj.pressed = (data.selected!=null);
	this.obj.extAction = (data.action||null);
	this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.obj.pressed?"pres"+(this.state?"":"_dis"):(this.state?"def":"dis"));
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.renderAs = this.obj.className;
	this.obj.idd = String(id);
	this.obj.title = (data.title||"");
	if (this.obj.pressed) { this.obj.renderAs = "dhx_toolbar_btn dhxtoolbar_btn_over"; }
	
	var img = (that.conf.icons_css?"<i class='"+that.conf.icons_path+this.img+"'></i>":"<img src='"+that.conf.icons_path+this.img+"'>");
	this.obj.innerHTML = that._rtlParseBtn((this.img!=""?img:""),(data.text!=null?"<div class='dhxtoolbar_text'>"+data.text+"</div>":""));
	
	// add object
	that.base.appendChild(this.obj);
	
	if (window.dhx4.isIE) {
		this.obj.onselectstart = function(e) {
			e = e||event;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
	}
	
	var t = this;
	this._doOnMouseOver = function(e) {
		e = e||event;
		if (t.state == false || t.obj.over == true) return;
		if (t.obj.pressed != true && t.obj.className.match(/dhxtoolbar_btn_over/gi) == null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_over";
		t.obj.over = true;
	}
	
	this._doOnMouseOut = function(e) {
		e = e||event;
		if (t.state == false) return;
		if (t.obj.pressed != true && t.obj.className.match(/dhxtoolbar_btn_over/gi) != null) t.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
		t.obj.over = false;
	}
	
	this._doOnMouseDown = function(e) {
		e = e||event;
		if (e.type == "touchstart") {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id != null && that.conf.touch_id != t.id) return; // multiple touches?
			that.conf.touch_id = t.id;
		}
		if (t.state == false) return;
		if (that.callEvent("onBeforeStateChange", [t.obj.idd.replace(that.idPrefix, ""), t.obj.pressed]) !== true) return;
		t.obj.pressed = !t.obj.pressed;
		t.obj.className = "dhx_toolbar_btn " + (t.obj.pressed == true ? "dhxtoolbar_btn_pres" : (t.obj.over == true? "dhxtoolbar_btn_over" : "dhxtoolbar_btn_def"));
		//
		var id = t.obj.idd.replace(that.idPrefix, "");
		if (t.obj.extAction) try {window[t.obj.extAction](idd, t.obj.pressed);} catch(e){};
		that.callEvent("onStateChange", [id, t.obj.pressed]);
	}
	this._doOnMouseUp = function(e) {
		e = e||event;
		if (e.type == "touchend") {
			if (e.preventDefault) e.preventDefault();
			e.cancelBubble = true;
			if (that.conf.touch_id == t.id) that.conf.touch_id = null;
		}
	}
	
	
	// mouse events
	this.obj.evs = {
		mouseover: "_doOnMouseOver",
		mouseout: "_doOnMouseOut",
		mousedown: "_doOnMouseDown",
		touchstart: "_doOnMouseDown",
		touchend: "_doOnMouseUp"
	};
	
	that._evs.add.apply(this, [this.obj.evs, this.obj]);
	
	// functions
	this.setItemState = function(state, callEvent) {
		if (this.obj.pressed != state) {
			if (state == true) {
				this.obj.pressed = true;
				this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_pres"+(this.state?"":"_dis");
			} else {
				this.obj.pressed = false;
				this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.state?"def":"dis");
			}
			if (callEvent == true) {
				var id = this.obj.idd.replace(that.idPrefix, "");
				if (this.obj.extAction) try {window[this.obj.extAction](id, this.obj.pressed);} catch(e){};
				that.callEvent("onStateChange", [id, this.obj.pressed]);
			}
		}
	}
	this.getItemState = function() {
		return this.obj.pressed;
	}
	this.enableItem = function() {
		that._enableItem(this);
	}
	this.disableItem = function() {
		that._disableItem(this);
	}
	this.isEnabled = function() {
		return this.state;
	}
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	this.setItemText = function(text) {
		that._setItemText(this, text);
	}
	this.getItemText = function() {
		return that._getItemText(this);
	}
	this.setItemImage = function(url) {
		that._setItemImage(this, url, true);
	}
	this.clearItemImage = function() {
		that._clearItemImage(this, true);
	}
	this.setItemImageDis = function(url) {
		that._setItemImage(this, url, false);
	}
	this.clearItemImageDis = function() {
		that._clearItemImage(this, false);
	}
	this.setItemToolTip = function(tip) {
		this.obj.title = tip;
	}
	this.getItemToolTip = function() {
		return this.obj.title;
	}
	//
	return this;
}

/*****************************************************************************************************************************************************************
	object: slider
***************************************************************************************************************************************************************** */
dhtmlXToolbarObject.prototype._sliderObject = function(that, id, data) {
	this.id = that.idPrefix+id;
	this.state = (data.enabled!=null?(data.enabled=="true"?true:false):true);
	this.obj = document.createElement("DIV");
	this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_"+(this.state?"def":"dis");
	this.obj.style.display = (data.hidden!=null?"none":"");
	this.obj.onselectstart = function(e) { e = e||event; if (e.preventDefault) e.preventDefault(); else e.returnValue = false; }
	this.obj.idd = String(id);
	this.obj.len = (data.length!=null?Number(data.length):50);
	//
	this.obj.innerHTML = "<div class='dhxtoolbar_text'>"+(data.textMin||"")+"</div>"+
				"<div class='dhxtoolbar_sl_bg_l'></div>"+
				"<div class='dhxtoolbar_sl_bg_m' style='width:"+this.obj.len+"px;'></div>"+
				"<div class='dhxtoolbar_sl_bg_r'></div>"+
				"<div class='dhxtoolbar_text'>"+(data.textMax||"")+"</div>";
	// add object
	that.base.appendChild(this.obj);
	var self = this;
	
	this.pen = document.createElement("DIV");
	this.pen.className = "dhxtoolbar_sl_pen";
	this.obj.appendChild(this.pen);
	var pen = this.pen;
	
	this.label = document.createElement("DIV");
	this.label.dir = "ltr";
	this.label.className = "dhx_toolbar_slider_label_"+that.conf.skin+(that.rtl?"_rtl":"");
	this.label.style.display = "none";
	this.label.tip = (data.toolTip||"%v");
	this.label._zi = window.dhx4.newId();
	document.body.appendChild(this.label);
	var label = this.label;
	
	// mix-max value
	this.pen.valueMin = (data.valueMin!=null?Number(data.valueMin):0);
	this.pen.valueMax = (data.valueMax!=null?Number(data.valueMax):100);
	if (this.pen.valueMin > this.pen.valueMax) this.pen.valueMin = this.pen.valueMax;
	
	// init value
	this.pen.valueNow = (data.valueNow!=null?Number(data.valueNow):this.pen.valueMax);
	if (this.pen.valueNow > this.pen.valueMax) this.pen.valueNow = this.pen.valueMax;
	if (this.pen.valueNow < this.pen.valueMin) this.pen.valueNow = this.pen.valueMin;
	
	// min/max x coordinate
	this.pen._detectLimits = function() {
		this.minX = self.obj.childNodes[1].offsetLeft+2;
		this.maxX = self.obj.childNodes[3].offsetLeft-this.offsetWidth+1;
	}
	this.pen._detectLimits();
	
	// position
	this.pen._definePos = function() {
		this.nowX = Math.round((this.valueNow-this.valueMin)*(this.maxX-this.minX)/(this.valueMax-this.valueMin)+this.minX);
		this.style.left = this.nowX+"px";
		this.newNowX = this.nowX;
	}
	this.pen._definePos();

	this.pen.initXY = 0;
	this.pen.allowMove = false;
	this.pen[window.dhx4.isIPad?"ontouchstart":"onmousedown"] = function(e) {
		if (self.state == false) return;
		e = e||event;
		this.initXY = (window.dhx4.isIPad?e.touches[0].clientX:e.clientX); //e.clientX;
		this.newValueNow = this.valueNow;
		this.allowMove = true;
		this.className = "dhxtoolbar_sl_pen dhxtoolbar_over";
		if (label.tip != "") {
			label.style.visibility = "hidden";
			label.style.display = "";
			label.innerHTML = label.tip.replace("%v", this.valueNow);
			label.style.left = Math.round(window.dhx4.absLeft(this)+this.offsetWidth/2-label.offsetWidth/2)+"px";
			label.style.top = window.dhx4.absTop(this)-label.offsetHeight-3+"px";
			label.style.visibility = "";
			label.style.zIndex = window.dhx4.zim.reserve(label._zi);
		}
	}
	
	this.pen._doOnMouseMoveStart = function(e) {
		// optimized for destructor
		e=e||event;
		if (!pen.allowMove) return;
		var ecx = (window.dhx4.isIPad?e.touches[0].clientX:e.clientX);
		var ofst = ecx - pen.initXY;
		
		// mouse goes out to left/right from pen
		if (ecx < window.dhx4.absLeft(pen)+Math.round(pen.offsetWidth/2) && pen.nowX == pen.minX) return;
		if (ecx > window.dhx4.absLeft(pen)+Math.round(pen.offsetWidth/2) && pen.nowX == pen.maxX) return;
		
		pen.newNowX = pen.nowX + ofst;
		
		if (pen.newNowX < pen.minX) pen.newNowX = pen.minX;
		if (pen.newNowX > pen.maxX) pen.newNowX = pen.maxX;
		pen.nowX = pen.newNowX;
		pen.style.left = pen.nowX+"px";
		pen.initXY = ecx;
		pen.newValueNow = Math.round((pen.valueMax-pen.valueMin)*(pen.newNowX-pen.minX)/(pen.maxX-pen.minX)+pen.valueMin);
		if (label.tip != "") {
			label.innerHTML = label.tip.replace(/%v/gi, pen.newValueNow);
			label.style.left = Math.round(window.dhx4.absLeft(pen)+pen.offsetWidth/2-label.offsetWidth/2)+"px";
			label.style.top = window.dhx4.absTop(pen)-label.offsetHeight-3+"px";
		}
		e.cancelBubble = true;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		return false;
	}
	this.pen._doOnMouseMoveEnd = function() {
		if (!pen.allowMove) return;
		pen.className = "dhxtoolbar_sl_pen";
		pen.allowMove = false;
		pen.nowX = pen.newNowX;
		pen.valueNow = pen.newValueNow;
		if (label.tip != "") {
			label.style.display = "none";
			window.dhx4.zim.clear(label._zi);
		}
		that.callEvent("onValueChange", [self.obj.idd.replace(that.idPrefix, ""), pen.valueNow]);
	}
	
	if (window.dhx4.isIPad) {
		document.addEventListener("touchmove", pen._doOnMouseMoveStart, false);
		document.addEventListener("touchend", pen._doOnMouseMoveEnd, false);
	} else {
		if (typeof(window.addEventListener) != "undefined") {
			window.addEventListener("mousemove", pen._doOnMouseMoveStart, false);
			window.addEventListener("mouseup", pen._doOnMouseMoveEnd, false);
		} else {
			document.body.attachEvent("onmousemove", pen._doOnMouseMoveStart);
			document.body.attachEvent("onmouseup", pen._doOnMouseMoveEnd);
		}
	}
	// functions
	this.enableItem = function() {
		if (this.state) return;
		this.state = true;
		this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_def";
	}
	this.disableItem = function() {
		if (!this.state) return;
		this.state = false;
		this.obj.className = "dhx_toolbar_btn dhxtoolbar_btn_dis";
	}
	this.isEnabled = function() {
		return this.state;
	}
	this.showItem = function() {
		this.obj.style.display = "";
	}
	this.hideItem = function() {
		this.obj.style.display = "none";
	}
	this.isVisible = function() {
		return (this.obj.style.display == "");
	}
	this.setValue = function(value, callEvent) {
		value = Number(value);
		if (value < this.pen.valueMin) value = this.pen.valueMin;
		if (value > this.pen.valueMax) value = this.pen.valueMax;
		this.pen.valueNow = value;
		this.pen._definePos();
		if (callEvent == true) that.callEvent("onValueChange", [this.obj.idd.replace(that.idPrefix, ""), this.pen.valueNow]);
	}
	this.getValue = function() {
		return this.pen.valueNow;
	}
	this.setMinValue = function(value, label) {
		value = Number(value);
		if (value > this.pen.valueMax) return;
		this.obj.childNodes[0].innerHTML = label;
		this.obj.childNodes[0].style.display = (label.length>0?"":"none");
		this.pen.valueMin = value;
		if (this.pen.valueNow < this.pen.valueMin) this.pen.valueNow = this.pen.valueMin;
		this.pen._detectLimits();
		this.pen._definePos();
	}
	this.setMaxValue = function(value, label) {
		value = Number(value);
		if (value < this.pen.valueMin) return;
		this.obj.childNodes[4].innerHTML = label;
		this.obj.childNodes[4].style.display = (label.length>0?"":"none");
		this.pen.valueMax = value;
		if (this.pen.valueNow > this.pen.valueMax) this.pen.valueNow = this.pen.valueMax;
		this.pen._detectLimits();
		this.pen._definePos();
	}
	this.getMinValue = function() {
		var label = this.obj.childNodes[0].innerHTML;
		var value = this.pen.valueMin;
		return new Array(value, label);
	}
	this.getMaxValue = function() {
		var label = this.obj.childNodes[4].innerHTML;
		var value = this.pen.valueMax;
		return new Array(value, label);
	}
	this.setItemToolTipTemplate = function(template) {
		this.label.tip = template;
	}
	this.getItemToolTipTemplate = function() {
		return this.label.tip;
	}
	//
	return this;
}

dhtmlXToolbarObject.prototype.unload = function() {
	
	if (typeof(window.addEventListener) == "function") {
		window.removeEventListener("mousedown", this._doOnClick, false);
		window.removeEventListener("touchstart", this._doOnClick, false);
	} else {
		document.body.detachEvent("onmousedown", this._doOnClick);
	}
	
	this._doOnClick = null;
	
	this.clearAll();
	this.objPull = null;
	
	if (this._xmlLoader) {
		this._xmlLoader.destructor();
		this._xmlLoader = null;
	}
	
	while (this.base.childNodes.length > 0) this.base.removeChild(this.base.childNodes[0]);
	this.cont.removeChild(this.base);
	this.base = null;
	
	while (this.cont.childNodes.length > 0) this.cont.removeChild(this.cont.childNodes[0]);
	this.cont.className = "";
	this.cont = null;
	
	window.dhx4._enableDataLoading(this, null, null, null, "clear");
	window.dhx4._eventable(this, "clear");
	
	this.tX1 = null;
	this.tX2 = null;
	this.tY1 = null;
	this.tY2 = null;
	
	this.anyUsed = null;
	this.idPrefix = null;
	this.rootTypes = null;
	
	this._rtl = null;
	this._rtlParseBtn = null;
	this.setRTL = null;
	
	this._sbw = null;
	this._getObj = null;
	this._addImgObj = null;
	this._setItemImage = null;
	this._clearItemImage = null;
	this._setItemText = null;
	this._getItemText = null;
	this._enableItem = null;
	this._disableItem = null;
	this._xmlParser = null;
	
	this._addItemToStorage = null;
	this._genStr = null;
	this._addItem = null;
	this._getPosition = null;
	this._setPosition = null;
	this._getIdByPosition = null;
	this._separatorObject = null;
	this._textObject = null;
	this._buttonObject = null;
	this._buttonSelectObject = null;
	this._buttonInputObject = null;
	this._buttonTwoStateObject = null;
	this._sliderObject = null;
	this._autoDetectVisibleArea = null;
	this._removeItem = null;
	this.setAlign = null;
	this.setSkin = null;
	this.setIconsPath = null;
	this.setIconPath = null;
	this.loadXML = null;
	this.loadXMLString = null;
	this.clearAll = null;
	this.addSpacer = null;
	this.removeSpacer = null;
	this.getType = null;
	this.getTypeExt = null;
	this.inArray = null;
	this.getParentId = null;
	this.addButton = null;
	this.addText = null;
	this.addButtonSelect = null;
	this.addButtonTwoState = null;
	this.addSeparator = null;
	this.addSlider = null;
	this.addInput = null;
	this.forEachItem = null;
	this.showItem = null;
	this.hideItem = null;
	this.isVisible = null;
	this.enableItem = null;
	this.disableItem = null;
	this.isEnabled = null;
	this.setItemText = null;
	this.getItemText = null;
	this.setItemToolTip = null;
	this.getItemToolTip = null;
	this.setItemImage = null;
	this.setItemImageDis = null;
	this.clearItemImage = null;
	this.clearItemImageDis = null;
	this.setItemState = null;
	this.getItemState = null;
	this.setItemToolTipTemplate = null;
	this.getItemToolTipTemplate = null;
	this.setValue = null;
	this.getValue = null;
	this.setMinValue = null;
	this.getMinValue = null;
	this.setMaxValue = null;
	this.getMaxValue = null;
	this.setWidth = null;
	this.getWidth = null;
	this.getPosition = null;
	this.setPosition = null;
	this.removeItem = null;
	this.addListOption = null;
	this.removeListOption = null;
	this.showListOption = null;
	this.hideListOption = null;
	this.isListOptionVisible = null;
	this.enableListOption = null;
	this.disableListOption = null;
	this.isListOptionEnabled = null;
	this.setListOptionPosition = null;
	this.getListOptionPosition = null;
	this.setListOptionText = null;
	this.getListOptionText = null;
	this.setListOptionToolTip = null;
	this.getListOptionToolTip = null;
	this.setListOptionImage = null;
	this.getListOptionImage = null;
	this.clearListOptionImage = null;
	this.forEachListOption = null;
	this.getAllListOptions = null;
	this.setListOptionSelected = null;
	this.getListOptionSelected = null;
	this.unload = null;
	this.setUserData = null;
	this.getUserData = null;
	this.setMaxOpen = null;
	this.items = null;
	this.conf = null;
};

dhtmlXToolbarObject.prototype._autoDetectVisibleArea = function() {
	var d = window.dhx4.screenDim();
	this.tX1 = d.left;
	this.tX2 = d.right;
	this.tY1 = d.top;
	this.tY2 = d.bottom;
};

dhtmlXToolbarObject.prototype.setIconset = function(name) {
	this.conf.icons_css = (name=="awesome");
};
dhtmlXToolbarObject.prototype._evs = {
	add: function(evs, obj) { // this->calle
		for (var a in evs) {
			if (typeof(window.addEventListener) == "function") {
				obj.addEventListener(a, this[evs[a]], false);
			} else if (a.match(/^touch/) == null) {
				obj.attachEvent("on"+a, this[evs[a]]);
			}
		}
		obj = evs = null;
	},
	clear: function(evs, obj) {
		for (var a in evs) {
			if (typeof(window.addEventListener) == "function") {
				obj.removeEventListener(a, this[evs[a]], false);
			} else if (a.match(/^touch/) == null) {
				obj.detachEvent("on"+a, this[evs[a]]);
			}
		}
		obj = evs = null;
	}
};
dhtmlXToolbarObject.prototype._initObj = function(data) {
	for (var q=0; q<data.length; q++) this._addItemToStorage(data[q]);
	if (this.conf.skin == "dhx_terrace") this._improveTerraceSkin();
};

dhtmlXToolbarObject.prototype._xmlToJson = function(xml) {
	
	var data = [];
	var root = xml.getElementsByTagName("toolbar");
	
	if (root != null && root[0] != null) {
		
		root = root[0];
		
		var getExtText = function(node) {
			var t = null;
			for (var q=0; q<node.childNodes.length; q++) {
				if (t == null && node.childNodes[q].tagName == "itemText") {
					t = window.dhx4._xmlNodeValue(node.childNodes[q]);
					break;
				}
			}
			return t;
		}
		
		var t = ["id", "type", "hidden", "title", "text", "enabled", "img", "imgdis", "action", "openAll", "renderSelect", "mode", "maxOpen", "width", "value", "selected", "length", "textMin", "textMax", "toolTip", "valueMin", "valueMax", "valueNow"];
		var p = ["id", "type", "enabled", "disabled", "action", "selected", "img", "text"];
		//
		for (var q=0; q<root.childNodes.length; q++) {
			if (root.childNodes[q].tagName == "item") {
				
				var itemData = {};
				for (var w=0; w<t.length; w++) {
					var val = root.childNodes[q].getAttribute(t[w]);
					if (val != null) itemData[t[w]] = val;
				}
				
				for (var e=0; e<root.childNodes[q].childNodes.length; e++) {
					if (root.childNodes[q].childNodes[e].tagName == "item" && itemData.type == "buttonSelect") {
						var u = {};
						for (var w=0; w<p.length; w++) {
							var val = root.childNodes[q].childNodes[e].getAttribute(p[w]);
							if (val != null) u[p[w]] = val;
						}
						// listed options userdata
						var h = root.childNodes[q].childNodes[e].getElementsByTagName("userdata");
						for (var w=0; w<h.length; w++) {
							if (!u.userdata) u.userdata = {};
							var r = {};
							try { r.name = h[w].getAttribute("name"); } catch(k) { r.name = null; }
							try { r.value = h[w].firstChild.nodeValue; } catch(k) { r.value = ""; }
							if (r.name != null) u.userdata[r.name] = r.value;
						}
						// get extended <itemText>
						u.text = getExtText(root.childNodes[q].childNodes[e])||u.text;
						//
						if (itemData.options == null) itemData.options = [];
						itemData.options.push(u);
					}
					
					// items userdata
					if (root.childNodes[q].childNodes[e].tagName == "userdata") {
						if (itemData.userdata == null) itemData.userdata = {};
						var u = {};
						try { u.name = root.childNodes[q].childNodes[e].getAttribute("name"); } catch(k) { u.name = null; }
						try { u.value = root.childNodes[q].childNodes[e].firstChild.nodeValue; } catch(k) { u.value = ""; }
						if (u.name != null) itemData.userdata[u.name] = u.value;
					}
				}
				
				// get extended <itemText>
				itemData.text = getExtText(root.childNodes[q])||itemData.text;
				
				data.push(itemData);
			}
		}
		
		getExtText = null;
	}
	
	return data;
};

dhtmlXToolbarObject.prototype._addItemToStorage = function(data, pos) {
	
	var id = (data.id||this._genStr(24));
	var type = (data.type||"");
	
	if (type == "spacer") {
		this.addSpacer(this._lastId);
	} else {
		this._lastId = id;
	}
	
	if (type != "" && this["_"+type+"Object"] != null) {
		
		if (type == "buttonSelect") {
			if (data.options != null) {
				for (var q=0; q<data.options.length; q++) { // js-array button select init used obj/sep
					if (data.options[q].type == "obj") data.options[q].type = "button";
					if (data.options[q].type == "sep") data.options[q].type = "separator";
				}
			}
		}
		
		if (type == "slider") {
			var k = {
				tip_template: "toolTip",
				value_min: "valueMin",
				value_max: "valueMax",
				value_now: "valueNow",
				text_min: "textMin",
				text_max: "textMax"
			};
			for (var a in k) {
				if (data[k[a]] == null && data[a] != null) data[k[a]] = data[a];
			}
		}
		
		if (type == "buttonInput") {
			if (data.value == null && data.text != null) data.value = data.text;
		}
		
		if (type == "buttonTwoState") {
			if (typeof(data.selected) == "undefined" && typeof(data.pressed) != "undefined" && window.dhx4.s2b(data.pressed)) {
				data.selected = true;
			}
		}
		
		// common
		if (typeof(data.enabled) == "undefined" && typeof(data.disabled) != "undefined" && window.dhx4.s2b(data.disabled)) {
			data.enabled = false;
		}
		if (data.imgDis == null && data.img_disabled != null) {
			data.imgdis = data.img_disabled;
		}
		
		if ((typeof(data.openAll) == "undefined" || data.openAll == null) && this.conf.skin == "dhx_terrace") data.openAll = true;
		this.objPull[this.idPrefix+id] = new this["_"+type+"Object"](this, id, data);
		this.objPull[this.idPrefix+id]["type"] = type;
		this.setPosition(id, pos);
	}
	
	// userdata
	if (data.userdata != null) {
		for (var a in data.userdata) this.setUserData(id, a, data.userdata[a]);
	}
	// userdata for options
	if (data.options != null) {
		for (var q=0; q<data.options.length; q++) {
			if (data.options[q].userdata != null) {
				for (var a in data.options[q].userdata) {
					this.setListOptionUserData(data.id, data.options[q].id, a, data.options[q].userdata[a]);
				}
			}
		}
	}

};

// skin
dhtmlXToolbarObject.prototype.setSkin = function(skin, onlyIcons) {
	if (onlyIcons === true) {
		// prevent of removing skin postfixes when attached to layout/acc/etc
		this.cont.className = this.cont.className.replace(/dhxtoolbar_icons_\d{1,}/,"dhxtoolbar_icons_"+this.conf.iconSize);
	} else {
		this.conf.skin = skin;
		if (this.conf.skin == "dhx_skyblue") {
			this.conf.sel_ofs_y = 1;
		}
		if (this.conf.skin == "dhx_web") {
			this.conf.sel_ofs_y = 1;
			this.conf.sel_ofs_x = 1;
		}
		if (this.conf.skin == "dhx_terrace") {
			this.conf.sel_ofs_y = -1;
			this.conf.sel_ofs_x = 0;
		}
		if (this.conf.skin == "material") {
			this.conf.sel_ofs_y = -1;
			this.conf.sel_ofs_x = 0;
		}
		this.cont.className = "dhx_toolbar_"+this.conf.skin+" dhxtoolbar_icons_"+this.conf.iconSize+this.conf.cssShadow;
	}
	
	for (var a in this.objPull) {
		var item = this.objPull[a];
		if (item["type"] == "slider") {
			item.pen._detectLimits();
			item.pen._definePos();
			item.label.className = "dhx_toolbar_slider_label_"+this.conf.skin;
		}
		if (item["type"] == "buttonSelect") {
			item.polygon.className = "dhx_toolbar_poly_"+this.conf.skin+" dhxtoolbar_icons_"+this.conf.iconSize+this.conf.cssShadow;
		}
	}
	if (skin == "dhx_terrace") this._improveTerraceSkin();
};

dhtmlXToolbarObject.prototype.setAlign = function(align) {
	this.conf.align = (align=="right"?"right":"left");
	this.base.className = (align=="right"?"dhxtoolbar_float_right":"dhxtoolbar_float_left");
	if (this._spacer) this._spacer.className = (align=="right"?" dhxtoolbar_float_left":" dhxtoolbar_float_right")
};

dhtmlXToolbarObject.prototype.setIconSize = function(size) {
	this.conf.iconSize = ({18:true,24:true,32:true,48:true}[size]?size:18);
	this.setSkin(this.conf.skin, true);
	this.callEvent("_onIconSizeChange",[this.conf.iconSize]);
};

dhtmlXToolbarObject.prototype.setIconsPath = function(path) {
	this.conf.icons_path = path;
};


// user data
dhtmlXToolbarObject.prototype.setUserData = function(id, name, value) {
	id = this.idPrefix+id;
	if (this.objPull[id] != null) {
		if (this.objPull[id].userData == null) this.objPull[id].userData = {};
		this.objPull[id].userData[name] = value;
	}
};
dhtmlXToolbarObject.prototype.getUserData = function(id, name) {
	id = this.idPrefix+id;
	if (this.objPull[id] != null && this.objPull[id].userData != null) return this.objPull[id].userData[name]||null;
	return null;
};
// userdata for listed options
dhtmlXToolbarObject.prototype._isListOptionExists = function(listId, optionId) {
	if (this.objPull[this.idPrefix+listId] == null) return false;
	var item = this.objPull[this.idPrefix+listId];
	if (item.type != "buttonSelect") return false;
	if (item._listOptions[optionId] == null) return false;
	return true;
};
dhtmlXToolbarObject.prototype.setListOptionUserData = function(listId, optionId, name, value) {
	// is exists?
	if (!this._isListOptionExists(listId, optionId)) return;
	// set userdata
	var opt = this.objPull[this.idPrefix+listId]._listOptions[optionId];
	if (opt.userData == null) opt.userData = {};
	opt.userData[name] = value;
};
dhtmlXToolbarObject.prototype.getListOptionUserData = function(listId, optionId, name) {
	// is exists?
	if (!this._isListOptionExists(listId, optionId)) return null;
	// get userdata
	var opt = this.objPull[this.idPrefix+listId]._listOptions[optionId];
	if (!opt.userData) return null;
	return (opt.userData[name]?opt.userData[name]:null);
};


// terrace skin fixes
dhtmlXToolbarObject.prototype._improveTerraceSkin = function() {
	
	if (this.conf.terrace_radius == null) this.conf.terrace_radius = "3px";
	
	var p = [];
	var bn = {separator: true, text: true}; // border-less items
	
	var e = [this.base];
	if (this._spacer != null) e.push(this._spacer);
	for (var w=0; w<e.length; w++) {
		p[w] = [];
		for (var q=0; q<e[w].childNodes.length; q++) {
			if (e[w].childNodes[q].idd != null && e[w].childNodes[q].style.display != "none") {
				var a = this.idPrefix+e[w].childNodes[q].idd;
				if (this.objPull[a] != null && this.objPull[a].obj == e[w].childNodes[q]) {
					p[w].push({a:a,type:this.objPull[a].type,node:this.objPull[a][this.objPull[a].type=="buttonSelect"?"arw":"obj"]});
				}
			}
		}
		e[w] = null;
	}
	
	for (var w=0; w<p.length; w++) {
		for (var q=0; q<p[w].length; q++) {
			
			var t = p[w][q];
			
			// check if border-right/border-left needed
			var br = false;
			var bl = false;
			
			if (!bn[t.type]) {
				
				// right side - check if item last-child or next-sibling is borderless item
				if (q == p[w].length-1 || (p[w][q+1] != null && bn[p[w][q+1].type])) br = true;
				
				// left side, check if item first-child or prev-sibling is borderless item
				if (q == 0 || (q-1 >= 0 && p[w][q-1] != null && bn[p[w][q-1].type])) bl = true;
				
			}
			
			t.node.style.borderRightWidth = (br?"1px":"0px");
			t.node.style.borderTopRightRadius = t.node.style.borderBottomRightRadius = (br?this.conf.terrace_radius:"0px");
			
			if (t.type == "buttonSelect") {
				t.node.previousSibling.style.borderTopLeftRadius = t.node.previousSibling.style.borderBottomLeftRadius = (bl?this.conf.terrace_radius:"0px");
				t.node.previousSibling._br = br;
				t.node.previousSibling._bl = bl;
			} else {
				t.node.style.borderTopLeftRadius = t.node.style.borderBottomLeftRadius = (bl?this.conf.terrace_radius:"0px");
			}
			
			t.node._br = br;
			t.node._bl = bl;
			
		}
	}
	
	for (var w=0; w<p.length; w++) {
		for (var q=0; q<p[w].length; q++) {
			for (var a in p[w][q]) p[w][q][a] = null;
			p[w][q] = null;
		}
		p[w] = null;
	}
	
	p = e = null;
};

// enable/disable riunded corners when sublist opened
dhtmlXToolbarObject.prototype._improveTerraceButtonSelect = function(id, state) {
	var item = this.objPull[id];
	if (state == true) {
		item.obj.style.borderBottomLeftRadius = (item.obj._bl?this.conf.terrace_radius:"0px");
		item.arw.style.borderBottomRightRadius = (item.obj._br?this.conf.terrace_radius:"0px");
	} else {
		item.obj.style.borderBottomLeftRadius = "0px";
		item.arw.style.borderBottomRightRadius = "0px";
	}
	item = null;
};

if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype._createNode_toolbar = function(obj, type, htmlString, append, node) {
		
		if (typeof(node) != "undefined") {
			obj = node;
		} else {
			obj = document.createElement("DIV");
			obj.className = "dhx_cell_toolbar_"+(this.conf.borders?"def":"no_borders");
			obj.appendChild(document.createElement("DIV"));
			obj.firstChild.className = "dhx_toolbar_base_18_dhx_skyblue";
		}
		
		this.cell.insertBefore(obj, this.cell.childNodes[this.conf.idx.cont]); // before cont only
		
		this.conf.ofs_nodes.t.toolbar = true;
		this._updateIdx();
		// adjust cont will performed after toolbar init
		
		return obj;
		
	}
	
	dhtmlXCellObject.prototype.attachToolbar = function(conf) {
		
		if (!(this.dataNodes.ribbon == null && this.dataNodes.toolbar == null)) return;
		
		this.callEvent("_onBeforeContentAttach", ["toolbar"]);
		
		if (typeof(conf) == "undefined") {
			conf = {};
		} else if (typeof(conf) == "string") {
			conf = {skin:conf};
		}
		if (typeof(conf.skin) == "undefined") conf.skin = this.conf.skin;
		conf.parent = this._attachObject("toolbar").firstChild;
		
		this.dataNodes.toolbar = new dhtmlXToolbarObject(conf);
		this._adjustCont(this._idd);
		
		this.dataNodes.toolbar._masterCell = this;
		this.dataNodes.toolbar.attachEvent("_onIconSizeChange", function(){
			this._masterCell._adjustCont();
		});
		
		conf.parent = null;
		conf = null;
		
		this.callEvent("_onContentAttach", []);
		
		return this.dataNodes.toolbar;
		
	};
	
	dhtmlXCellObject.prototype.detachToolbar = function() {
		
		if (!this.dataNodes.toolbar) return;
		this.dataNodes.toolbar._masterCell = null; // link to this
		this.dataNodes.toolbar.unload();
		this.dataNodes.toolbar = null;
		delete this.dataNodes.toolbar;
		
		this._detachObject("toolbar");
		
	};
	
	dhtmlXCellObject.prototype.showToolbar = function() {
		this._mtbShowHide("toolbar", "");
	};
	
	dhtmlXCellObject.prototype.hideToolbar = function() {
		this._mtbShowHide("toolbar", "none");
	};
	

	
	dhtmlXCellObject.prototype.getAttachedToolbar = function() {
		return this.dataNodes.toolbar;
	};
	
}

