/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXRibbon(struct) {
	
	var that = this, _skin, cont;
	
	this.conf = {
		type: "ribbon",
		icons_path: (struct && struct.icons_path)?struct.icons_path:"",
		icons_css: (struct && struct.iconsset == "awesome"),
		arrows_mode: (struct && struct.arrows_mode)?struct.arrows_mode:null,
		skin: "dhx_skyblue"
	};
	
	this._eventHandlers = {};
	this._base = null;
	this._items = {};
	this._tabbar = null;
	this.childIds = [];
	
	if (typeof(struct) == "string") {
		cont = struct;
		
	} else if (struct && struct.tagName) {
		cont = struct;
		
	} else if (struct && struct.parent) {
		if (struct.parent.tagName || typeof(struct.parent) == "string") {
			cont = struct.parent;
		}
	}
	
	/***   ***/
	this._doOnHighlight0 = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].mouseover) == "function") {
			if (that.items[item.type].mouseover(item, that, e) != true) {
				return false;
			}
		}
		
		if (!/dhxrb_highlight0/.test(el.className)) {
			el.className += " dhxrb_highlight0";
		}
	};
	
	this._doOffHighlight0 = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].mouseout) == "function") {
			if  (that.items[item.type].mouseout(item, that, e) != true) {
				return false;
			}
		}
		
		if (/dhxrb_highlight1/.test(el.className)) {
			el.className = el.className.replace(/\s?dhxrb_highlight1/, "");
		}
		
		if (/dhxrb_highlight0/.test(el.className)) {
			el.className = el.className.replace(/\s?dhxrb_highlight0/, "");
		}
		
	};
	
	this._doOnHighlight1 = function (e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		that.callEvent("_showPopup",[item.id]);
		
		if (that.items[item.type] && typeof(that.items[item.type].mousedown) == "function") {
			if (that.items[item.type].mousedown(item, that, e) != true) {
				return false;
			}
		}
			
		if (!/dhxrb_highlight1/.test(el.className)) {
			el.className += " dhxrb_highlight1";
		}
	};
	
	this._doOffHighlight1 = function (e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].mouseup) == "function") {
			if (that.items[item.type].mouseup(item, that, e) != true) {
				return false;
			}
		}
		
		if (/dhxrb_highlight1/.test(el.className)) {
			el.className = el.className.replace(/\s?dhxrb_highlight1/, "");
		}
	};
	
	this._doOnClick = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].click) == "function") {
			that.items[item.type].click(item, that, e);
		}
	};
	
	this._doOnFocus = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		that.callEvent("_showPopup",[item.id]);
		
		if (that.items[item.type] && typeof(that.items[item.type].focus) == "function") {
			that.items[item.type].focus(item, that, e);
		}
	};
	
	this._doOnBlur = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].blur) == "function") {
			that.items[item.type].blur(item, that, e);
		}
	};
	
	this._doOnChange = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].change) == "function") {
			that.items[item.type].change(item, that, e);
		}
	};
	
	this._doOnKeydown = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		var el = that._findItemByNode(target);
		var item = that._items[el._dhx_ribbonId];
		
		if (item.conf.disable) {
			return;
		}
		
		if (that.items[item.type] && typeof(that.items[item.type].keydown) == "function") {
			that.items[item.type].keydown(item, that, e);
		}
	};
	/***   ***/
	this._tabCustomApi = {
		enable: function(actvId, isCall) {
			/* @var this  dhtmlXCellObject */
			var item = null;
			
			isCall = isCall || false;
			if (isCall != true) {
				item = that._items[this._idd];
				for (var q=0; q<item.childIds.length; q++) {
					that.enable(item.childIds[q]);
				}
			}
			
			return that._tabOriginalApi.enable.apply(this,[actvId]);
		},
		
		disable: function(actvId, isCall) {
			/* @var this  dhtmlXCellObject */
			var item = null;
			
			if (isCall != false) {
				item = that._items[this._idd];
				for (var q=0; q<item.childIds.length; q++) {
					that.disable(item.childIds[q]);
				}
			}
			
			return that._tabOriginalApi.disable.apply(this,[actvId]);
		},
		
		close: function(actvId) {
			/* @var this  dhtmlXCellObject */
			var item = that._items[this._idd];
			
			that._removeTab(item);
			
			that._tabOriginalApi.close.apply(this,[actvId]);
			
			for (var key in that._tabOriginalApi) this[key] = null;
			
		}
	};
	/***  ***/
	this._attachEventTabbar  = function() {
		this._tabbar.attachEvent("onSelect",function() {
			return that.callEvent("onSelect",arguments);
		});
		
		this._tabbar.attachEvent("onTabClick",function() {
			return that.callEvent("onTabClick",arguments);
		});
		
		this._tabbar.attachEvent("onTabClose",function() {
			return that.callEvent("onTabClose",arguments);
		});
	};
	
	if (typeof(cont) == "string") {
		this._base = document.getElementById(cont);
	} else if (cont && cont.tagName) {
		this._base = cont;
	} else {
		this._base = document.createElement('div');
		this._base._dhx_remove = true;
		if (document.body.firstChild) {
			document.body.insertBefore(this._base,document.body.firstChild);
		} else {
			document.body.appendChild(this._base);
		}
	}
	
	_skin = dhx4.skin || (typeof(dhtmlx)!="undefined"?dhtmlx.skin:null) || dhx4.skinDetect("dhtmlxribbon") || "material";
	
	if (typeof(struct) == "object" && struct.skin) {
		_skin = struct.skin;
	}
	
	this.setSkin(_skin);
	
	dhx4._eventable(this);
	dhx4._enableDataLoading(this, "_renderData", "_xmlToJson", "ribbon", {struct:true});
	
	this.attachEvent("_onHeightChanged", function() {
		this.conf.inited = true;
	});
	
	this._base.className += " dhxrb_without_tabbar";
	this._base.innerHTML = "<div class='dhxrb_background_area'></div>";
	
	if (struct != null) {
		if (struct.json) {
			this.loadStruct(struct.json, struct.onload);
		} else if (struct.xml) {
			this.loadStruct(struct.xml, struct.onload);
		} else {
			this._renderData(struct);
		}
	}
	
	this.unload = function() {
		var chs = [];
		
		for (var q=0; q<this.childIds.length; q++) {
			chs.push(this.childIds[q]);
		}
		
		dhx4._enableDataLoading(this, null, null, null, "clear");
		dhx4._eventable(this, "clear");
		
		for (var q=0; q<chs.length; q++) {
			if (this._items[chs[q]].type == "tab") {
				this.tabs(chs[q]).close(false);
			} else {
				this.removeItem(chs[q]);
		    }
		}
		
		if (this._tabbar) {
			this._tabbar.unload();
			this._tabbar = null;
		}
		
		this._base.innerHTML = "";
		
		if (this._base._dhx_remove) {
			this._base.parentNode.removeChild(this._base);
		} else {
			this._base.className = this._base.className.replace(/\s?(dhtmlx|dhxrb)(\S*)/ig,"");
		}
		
		for (var a in this) {
			this[a] = null;
		}
		
		that = null;
	};
}

dhtmlXRibbon.prototype.setSizes = function() {
	if (this._tabbar != null && typeof(this.setSizes) == "function") {
		this._tabbar.setSizes();
	}
};

dhtmlXRibbon.prototype._renderData = function(data) {
	var cont = this._base.firstChild;
	
	if (data != null) {
		this.conf.icons_path = data.icons_path || this.conf.icons_path;
		this.conf.icons_css = (data.iconset=="awesome");
		
		if (data.tabs instanceof Array) {
			this._base.className = this._base.className.replace(/\s?dhxrb_without_tabbar/i, "");
			cont.className = "dhxrb_with_tabbar";
			this._tabbar = new dhtmlXTabBar(cont);
			this._attachEventTabbar();
			this._tabbar.setSkin(this.conf.skin);
			if (this.conf.arrows_mode != null) this._tabbar.setArrowsMode(this.conf.arrows_mode);
			
			this.childIds = this._appendTabs(data.tabs);
		} else if (data.items instanceof Array) {
			if (!/\s?dhxrb_without_tabbar/i.test(this._base.className)) {
				this._base.className += " dhxrb_without_tabbar";
			}
			
			if (/\s?dhxrb_background_area/i.test(cont.className)) {
				cont.className = "dhxrb_background_area";
			}
			cont.innerHTML = "<div class='dhxrb_g_area'></div>";
			this.childIds = this._appendBlocks(data.items,cont.firstChild);
		}
		this.callEvent("_onHeightChanged",[]);
	}
};

dhtmlXRibbon.prototype._xmlToJson = function(xml) {
	var root = xml.lastChild || null,
		answer = {}, list = [];
	
	if (root && root.tagName == "ribbon") {
		list = this._convertXmlNodeListIntoObject(root.childNodes);
	}
	
	if (list[0] && list[0].type && list[0].type.toLowerCase() == 'block') {
		answer.items = list;
	} else {
		answer.tabs = list;
	}
	
	return answer;
};

dhtmlXRibbon.prototype._convertXmlNodeListIntoObject = function(nodeList) {
	var i, l, answer = [], item;
	
	l = nodeList.length;
	for (i=0; i<l; i++) {
		item = this._covertXmlNodeToObject(nodeList[i]);
		if (item) answer.push(item);
	}
	
	return answer;
};

dhtmlXRibbon.prototype._covertXmlNodeToObject = function(node) {
	if (!node || !node.tagName || !(node.tagName.toLowerCase() == "item" || node.tagName.toLowerCase() == "tab")) {
		return null;
	}
	
	var i, l, attrs = node.attributes, answer={};
	l = attrs.length;
	
	for (i=0; i<l; i++) {
		switch (attrs[i].name) {
			case "isbig":
				answer["isbig"] = dhx4.s2b(attrs[i].value);
				break;
			case "state":
				answer["state"] = dhx4.s2b(attrs[i].value);
				break;
			default:
				answer[attrs[i].name] = attrs[i].value;
		}
	}
	
	if (node.childNodes.length) {
		if (node.getAttribute('type') == "buttonCombo" || node.getAttribute('type') == "buttonSelect") {
			answer.data = node;
		} else if (node.tagName.toLowerCase() == "tab") {
			answer.items = this._convertXmlNodeListIntoObject(node.childNodes);
		} else {
			answer.list = this._convertXmlNodeListIntoObject(node.childNodes);
		}
	}
	
	return answer;
};

dhtmlXRibbon.prototype._appendTabs = function(data) {
	var i,l,tab, answer = [];
	l = data.length;
	
	for (i=0; i<l; i++) {
		tab = this._addTab(data[i]);
		
		if (data[i].items instanceof Array) {
			tab.childIds = this._appendBlocks(data[i].items,tab.base,tab);
		}
		
		answer.push(tab.id);
	}
	
	return answer;
};

dhtmlXRibbon.prototype._addTab = function(data) {
	var key, tabCall;
	var tab = {
		conf: {
			active: false,
			text: "",
			width: null,
			position: null,
			disable: false
		},
		base: document.createElement('div'),
		type: "tab",
		childIds: []
	};
	
	for (key in data) {
		if (key == "items") continue;
		tab.conf[key] = data[key];
	}
	
	if (!data.id) {
		tab.id = dhx4.newId();
	} else {
		tab.id = data.id;
	}
	while (this._items[tab.id]) tab.id = dhx4.newId();
	
	this._tabbar.addTab(tab.id, tab.conf.text, tab.conf.width, tab.conf.position, tab.conf.active);
	
	tab.base.className = "dhxrb_g_area";
	tab.base._dhx_ribbonId = tab.id;
	
	this.tabs(tab.id).attachObject(tab.base);
	
	this._changeApiForTab(this.tabs(tab.id));
	
	this._items[tab.id] = tab;
	
	return tab;
};

dhtmlXRibbon.prototype._changeApiForTab = function(tab) {
	var key;
	for (key in this._tabOriginalApi) {
		this._tabOriginalApi[key] = this._tabOriginalApi[key] || tab[key];
		tab[key] = this._tabCustomApi[key];
	}
};

dhtmlXRibbon.prototype._tabOriginalApi = {
	enable: null,
	disable: null,
	close: null
};

dhtmlXRibbon.prototype._appendBlocks = function(blocks,cont,tab) {
	var i,l,_block,_list, w, q, answer = [];
	tab = tab || null;
	
	l = blocks.length;
	for (i=0; i<l; i++) {
		if (typeof(blocks[i]) == "object" && blocks[i].type == "block") {
			_block = this._addBlock(blocks[i], cont);
			
			if (blocks[i].list && (blocks[i].list instanceof Array)) {
				_list = blocks[i].list;
				w = _list.length;
				
				for (q=0; q<w; q++) {
					this._addItem(_block.id, null, null, _list[q]);
				}
			}
			
			if (tab != null) {
				_block.parentId = tab.id;
			}
			
			if (_block.conf.disable) {
				this.disable(_block.id);
			}
			answer.push(_block.id);
		}
	}
	
	return answer;
};

dhtmlXRibbon.prototype._addBlock = function (data,parent) {
	var i,l, block, key;
	
	block = {
		conf:  {
			text: "",
			text_pos: "bottom",
			type: "block",
			mode: "cols",
			disable: false
		},
		type: "block",
		childIds: [],
		base: document.createElement('div'),
		contForItems: document.createElement("div"),
		contForText: document.createElement("div")
	};
	
	for (key in data) {
		if (key == "list" || key == "type" || key == "id") continue;
		block.conf[key] = data[key];
	};
	
	if (!data.id) block.id = dhx4.newId();
	else block.id = data.id;
	while (this._items[block.id]) block.id = dhx4.newId();
	
	block.base.className = "dhxrb_block_base";
	parent.appendChild(block.base);
	
	block.contForItems.className = "dhxrb_block_items";
	block.contForText.className = "dhxrb_block_label";
	
	block.base.appendChild(block.contForItems);
	if (block.conf.text) {
		block.contForText.innerHTML = block.conf.text;
		if (block.conf.text_pos == "top") {
			block.base.insertBefore(block.contForText,block.contForItems);
		} else {
			block.base.appendChild(block.contForText);
		}
	}
	
	block.base._dhx_ribbonId = block.id;
	
	this._items[block.id] = block;
	
	return block;
};

dhtmlXRibbon.prototype._addItem = function(blockId, nextToId, itemId, data) {
	var block = this._items[blockId], base, cont_small_item, item = null, that = this, itemData;
	
	if (data.type != "newLevel" && !this.items[data.type]) {
		return null;
	}
	
	if (data.type == "newLevel") {
		this._addNewLevel(block);
	} else if (block.type == "group") {
		base = document.createElement('div');
		base.className = "dhxrb_in_group";
		block.base.appendChild(base);
	} else if (data.isbig) {
		base = document.createElement('div');
		base.className = "dhxrb_big_button";
		block.contForItems.appendChild(base);
	} else {
		cont_small_item = this._getContainerForSmallItem(block);
		base = document.createElement('div');
		base.className = (block.conf.mode == "rows")?"dhxrb_in_row":"dhxrb_3rows_button";
		cont_small_item.appendChild(base);
	}
	
	if (base) {
		this._attachEventForItem(base);
		
		itemData = {
			icons_path: data.icons_path || this.conf.icons_path,
			icons_css: this.conf.icons_css,
			skin: this.conf.skin
		};
		
		for (var key in data) {
			itemData[key] = data[key];
		};
		
		if (!itemData.id) itemData.id = dhx4.newId();
		while (this._items[itemData.id]) itemData.id = dhx4.newId();
		
		item = (this.items[itemData.type] && this.items[itemData.type].render)?this.items[itemData.type].render(base,itemData):null;
	}
	
	if (item != null) {
		this._items[item.id] = item;
		item.parentId = block.id;
		block.childIds.push(item.id);
		base._dhx_ribbonId = item.id;
		
		if (itemData.onclick && (typeof(itemData.onclick) == "function")) {
			this._eventHandlers[item.id] = this._eventHandlers[item.id] || {};
			this._eventHandlers[item.id]["onclick"] = itemData.onclick;
		}
		
		item.callEvent = function() {
			that.callEvent.apply(that,arguments);
		};
		item._callHandler = function() {
			that._callHandler.apply(that,arguments);
		};
		
		if (this.items[itemData.type] && typeof(this.items[itemData.type].callAfterInit) == "function") {
			this.items[itemData.type].callAfterInit.apply(this,[item]);
		}
	}
	
	return item;
};

dhtmlXRibbon.prototype._callHandler = function(id, arg) {
	if (this._eventHandlers[id] && this._eventHandlers[id].onclick) {
		this._eventHandlers[id].onclick.apply(this, arg);
	}
};

/*****************  items  *************************************/

dhtmlXRibbon.prototype.items = {};

dhtmlXRibbon.prototype.items.button = {
	render: function(cont, itemData) {
		var key, item;
		
		item = {
			base: cont,
			id: itemData.id,
			type: itemData.type,
			conf: {
				text: "",
				text_pos: (itemData.isbig)?"bottom":"right",
				img: null,
				imgdis: null,
				isbig: false,
				disable: false,
				skin: itemData.skin,
				icons_css: itemData.icons_css
			}
		};
		
		for (key in itemData) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = itemData[key];
		}
		
		if (item.conf.icons_css == true) {
			var img = "<i class='"+item.conf.icons_path+(item.conf.img||"")+"'></i>";
		} else {
			var img = "<img class='dhxrb_image"+((item.conf.img)?"'":" dhxrb_invisible'")+" src='"+((item.conf.img)?item.conf.icons_path+item.conf.img:"")+"' />"
		}
		
		cont.innerHTML = img+"<div class='dhxrb_label_button'>"+item.conf.text+"</div>";
		
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		if (item.conf.disable) {
			this.disable(item);
		}
		
		return item;
	},
	
	getText: function(item) {
		return item.conf.text;
	},
	
	setText: function(item, text) {
		var contForText = item.base.childNodes[1];
		
		item.conf.text = text;
		contForText.innerHTML = text;
	},
	
	setImage: function(item, img) {
		item.conf.img = img;
		if (item.conf.disable == false) {
			item.base.childNodes[0][item.conf.icons_css?"className":"src"] = item.conf.icons_path+item.conf.img;
		}
	},
	
	setImageDis: function(item, imgdis) {
		item.conf.imgdis = imgdis;
		if (item.conf.disable == true) {
			item.base.childNodes[0][item.conf.icons_css?"className":"src"] = item.conf.icons_path+item.conf.imgdis;
		}
	},
	
	mousedown: function(item,rb,e) {
		return true;
	},
	
	click: function(item,ribbon,e) {
		if (e.button != 0) {
			return false;
		}
		ribbon._callHandler(item.id, [item.id]);
		item.callEvent("onClick",[item.id]);
		return false;
	},
	
	disable: function(item) {
		var contForImage = item.base.childNodes[0],
			contForText = item.base.childNodes[1];
		
		if (item.conf.imgdis) {
			contForImage[item.conf.icons_css?"className":"src"] = item.conf.icons_path+item.conf.imgdis;
			if (/\s?dhxrb_invisible/i.test(contForImage.className)) {
				contForImage.className = contForImage.className.replace(/\s?dhxrb_invisible/i, "");
			}
		}
		
		if (!/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className += " dhxrb_disable_text_style";
		}
		
		return true;
	},
	
	enable: function(item) {
		var contForImage = item.base.childNodes[0],
			contForText = item.base.childNodes[1];
		
		if (item.conf.img) {
			contForImage[item.conf.icons_css?"className":"src"] = item.conf.icons_path+item.conf.img;
		} else {
			if (!/\s?dhxrb_invisible/i.test(contForImage.className)) {
				contForImage.className += " dhxrb_invisible";
			}
		}
		
		if (/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className = contForText.className.replace(/\s?dhxrb_disable_text_style/i, "");
		}
			
		return true;
	}
};

dhtmlXRibbon.prototype.items.buttonTwoState = {
	click: function(item, rb, e) {
		return false;
	},
	
	afterRender: function(item) {
		if (item.conf.state) {
			this.setState(item, item.conf.state);
		}
	},
	
	mouseover: function(item) {
		if (!/dhxrb_highlight0/.test(item.base.className)) {
			item.base.className += " dhxrb_highlight0";
		}
		return false;
	},
	
	mouseout: function(item) {
		if (/dhxrb_highlight0/.test(item.base.className)) {
			item.base.className = item.base.className.replace(/\s?dhxrb_highlight0/, "");
		}
		return false;
	},
	
	mousedown: function(item, rb, e) {
		rb._callHandler(item.id,[item.id,!item.conf.state]);
		this.setState(item, !item.conf.state, true);
		return false;
	},
	
	mouseup: function(item) {
		return false;
	},
	
	setState: function(item, value, callEvent) {
		callEvent = callEvent || false;
		value = dhx4.s2b(value);
		if (value) {
			if (!/dhxrb_highlight1/.test(item.base.className)) {
				item.base.className += " dhxrb_highlight1";
			}
		} else {
			if (/dhxrb_highlight1/.test(item.base.className)) {
				item.base.className = item.base.className.replace(/\s?dhxrb_highlight1/, "");
			}
		}
		
		item.conf.state = value;
		
		if (callEvent) {
			item.callEvent("onStateChange",[item.id, item.conf.state]);
		}
	},
	
	getState: function(item) {
		return (item.conf.state == true);
	}
};

dhtmlXRibbon.prototype.items.buttonSegment = {
	click: function(item, rb, e) {
		return false;
	},
	
	mousedown: function(item, rb, e) {
		this.setState(item, rb, true);
		return false;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		
		if (item.conf.state || dhtmlXRibbon.prototype.items.buttonSegment._getSelectedNeighbor(item, this) == null) {
			if (item.conf.state) {
				item.conf.state = false;
			}
			dhtmlXRibbon.prototype.items.buttonSegment.setState(item, this,  false);
		}
		
	},
	
	afterRender: function() {},
	
	setState: function(item, rb, callEvent) {
		callEvent = callEvent || false;
		var oldItem = null, state = dhx4.s2b(item.conf.state);
		
		if (state == false) {
			oldItem = this._getSelectedNeighbor(item,rb);
			if (oldItem != null) {
				this._unSelect(oldItem);
			}
			
			if (!/dhxrb_highlight1/.test(item.base.className)) {
				item.base.className += " dhxrb_highlight1";
			}
			
			item.conf.state = true;
			
			if (callEvent) {
				rb._callHandler(item.id,[item.id,(oldItem?oldItem.id:null)]);
				rb.callEvent("onStateChange", [item.id, (oldItem?oldItem.id:null)]);
			}
		}
	},
	
	remove: function(item, rb) {
		var state = dhx4.s2b(item.conf.state),
			firstItem = null, i=0, parent = rb._items[item.parentId],
			tempItem;
		if (state) {
			while (parent.childIds[i] && firstItem == null) {
				tempItem = rb._items[parent.childIds[i]];
				if (tempItem.type == "buttonSegment" && tempItem != item) {
					firstItem = tempItem;
				}
				i++;
			}
			
			if (firstItem) this.setState(firstItem, rb);
		}
	},
	
	_unSelect: function(item) {
		var state = dhx4.s2b(item.conf.state);
		if (state) {
			if (/dhxrb_highlight1/.test(item.base.className)) {
				item.base.className = item.base.className.replace(/\s?dhxrb_highlight1/, "");
			}
			
			item.conf.state = false;
		}
	},
	
	_getSelectedNeighbor: function(item, rb) {
		var parent = rb._items[item.parentId], i, l, neighbor, answer = null;
		l = parent.childIds.length;
		for (i=0; i<l; i++) {
			neighbor = rb._items[parent.childIds[i]];
			
			if (neighbor.type == "buttonSegment" && neighbor.conf.state) {
				answer = neighbor;
				break;
			}
		}
		
		return answer;
	}
};

dhtmlXRibbon.prototype.items.buttonSelect = {
	itemCollection: [],
	_isAttachedEventInWindow: false,
	
	afterRender: function(item) {
		var label = item.base.childNodes[1];
		label.innerHTML += "<span class='dhxrb_arrow'>&nbsp;</span>";
		item.menu = null;
		this.itemCollection.push(item);
		
		this._attachEventToWindow();
		
		label = null;
	},
	
	setText: function(item,text) {
		var arrow = item.base.childNodes[1].lastChild;
		
		item.conf.text = text;
		
		item.base.childNodes[1].innerHTML = text;
		item.base.childNodes[1].appendChild(arrow);
	},
	
	setOptionText: function(item, optId, text) {
		if (item.menu != null) {
			item.menu.setItemText(optId, text);
		} else {
			this._loopThroughItems(item.conf.items, optId, text, false); // loop through items
		}
	},
	
	getOptionText: function(item, optId) {
		if (item.menu != null) return item.menu.getItemText(optId);
		return this._loopThroughItems(item.conf.items, optId, null, true); // loop through items
	},
	
	_loopThroughItems: function(items, id, text, retValue) {
		for (var q=0; q<items.length; q++) {
			if (items[q].id == id) {
				if (retValue != true) {
					items[q].text = text; // set value
					return true;
				} else {
					return items[q].text; // get value
				}
			}
			if (items[q].items != null) { // nested
				var t = this._loopThroughItems(items[q].items, id, text, retValue);
				if (t != null) return t;
			}
		}
		return null;
	},
	
	_attachEventToWindow: function() {
		if (this._isAttachedEventInWindow == false) {
			if (typeof(window.addEventListener) == "function") {
				document.body.addEventListener("mousedown",this._hideAllMenus, false);
			} else {
				document.body.attachEvent("onmousedown",this._hideAllMenus);
			}
			this._isAttachedEventInWindow = true;
		}
	},
	
	_detachEventFromWindow: function() {
		if (typeof(window.addEventListener) == "function") {
			document.body.removeEventListener("mousedown", this._hideAllMenus, false);
		} else {
			document.body.detachEvent("onmousedown", this._hideAllMenus);
		}
		this._isAttachedEventInWindow = false;
	},
	
	_hideAllMenus: function(e) {
		e = e || event;
		var t = e.target||e.srcElement;
		var m = true;
		while (t != null && m == true) {
			 if (t.className != null && /SubLevelArea_Polygon/i.test(t.className)) {
				 m = false;
			 } else {
				 t = t.parentNode;
			 }
		}
		
		if (m == false) {
			return;
		}
		
		var items = dhtmlXRibbon.prototype.items.buttonSelect.itemCollection;
		for (var itemId in items) {
			var item = items[itemId];
			
			if (item.menu instanceof dhtmlXMenuObject) {
				if (item._skipHiding) {
					item._skipHiding = false;
				} else {
					dhtmlXRibbon.prototype.items.buttonSelect.hideMenu(item);
				}
			}
		}
	},
	
	mousedown: function(item,rb,e) {
		item._skipHiding = true;
		this.showMenu(item);
		return false;
	},
	
	mouseup: function(item) {
		return false;
	},
	
	click: function() {},
	setState: function() {},
	
	showMenu: function(item) {
		var x = dhx4.absLeft(item.base),
			y = dhx4.absTop(item.base)+item.base.offsetHeight;
		
		if (!(item.menu instanceof dhtmlXMenuObject)) {
			item.menu = new dhtmlXMenuObject({
				parent: item.base,
				icons_path: item.conf.icons_path,
				context: true,
				items: item.conf.items,
				skin: item.conf.skin
			});
			
			if (item.conf.data) {
				item.menu.loadStruct(item.conf.data);
				delete item.conf.data;
			}
			
			item.menu.setAutoHideMode(false);
			
			item.menu.attachEvent("onHide",function(id) {
				if (id == null) dhtmlXRibbon.prototype.items.buttonSelect._doOnHideMenu(item);
			});
			
			item.menu.attachEvent("onShow",function(id) {
				if (id == null) dhtmlXRibbon.prototype.items.buttonSelect._doOnShowMenu(item);
			});
			
			item.menu.attachEvent("onClick", function(id) {
				item.callEvent("onClick",[id, item.id]);
			});
			
			item.base.oncontextmenu = function() { return false; };
			dhtmlXRibbon.prototype.items.buttonSelect.showMenu(item);
		} else {
			item.menu.showContextMenu(x,y);
		}
	},
	
	hideMenu: function(item) {
		if (item.menu instanceof dhtmlXMenuObject) {
			item.menu.hideContextMenu();
		}
	},
	
	remove: function(item) {
		var itemId, l;
		
		if (item.menu instanceof dhtmlXMenuObject) {
			item.menu.unload();
			item.menu = null;
		}
		
		item.base.oncontextmenu = null;
		
		itemId = dhtmlXRibbon.prototype._indexOf(dhtmlXRibbon.prototype.items.buttonSelect.itemCollection, item);
		
		if (itemId != -1) {
			dhtmlXRibbon.prototype.items.buttonSelect.itemCollection.splice(itemId,1);
		}
		
		if (dhtmlXRibbon.prototype.items.buttonSelect.itemCollection.length == 0) {
			this._detachEventFromWindow();
		}
	},
	
	setSkin: function(item, skin) {
		if (item.menu instanceof dhtmlXMenuObject) {
			item.menu.setSkin(skin);
		}
	},
	
	_doOnHideMenu: function(item) {
		if (item._skipHiding) {
			item._skipHiding = false;
		} else {
			if (/dhxrb_highlight1/.test(item.base.className)) {
				item.base.className = item.base.className.replace(/\s?dhxrb_highlight1/, "");
			}
		}
	},
	
	_doOnShowMenu: function(item) {
		if (!/dhxrb_highlight1/.test(item.base.className)) {
			item.base.className += " dhxrb_highlight1";
		}
	}
};

dhtmlXRibbon.prototype.items.group = {
	render: function(cont, itemData) {
		var key, item;
		
		item = {
			base: cont,
			id: itemData.id,
			type: itemData.type,
			conf: {
				disable: false,
				skin: itemData.skin
			},
			childIds: []
		};
		
		cont.className = "dhxrb_group";
		
		for (key in itemData) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = itemData[key];
		}
		
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		this._detachEventFromItem(item.base);
		
		var i,l, list = item.conf.list, child, label, sep;
		l = (list)?list.length:0;
		
		for (i=0; i<l; i++) {
			
			child = this._addItem(item.id,null,null,list[i]);
			if (child == null) continue;
			
			label = child.base.childNodes[1];
			if (label && !label.innerHTML && !/\s?dhxrb_label_hide/i.test(label.className)) {
				label.className += " dhxrb_label_hide";
			}
		}
		
		dhtmlXRibbon.prototype.items.group.normalize(item);
		
		if (item.conf.disable) {
			this.disable(item.id);
		}
		
		list = undefined;
	},
	
	normalize: function(item) {
		var chidren = item.base.children;
		var l = chidren.length, flag = false, d = 0;
		var sep, lastNode;
		
		for (var i=0; i<l; i++) {
			if (!/dhxrb_separator_group/i.test(chidren[i].className)) {
				if (/dhxrb_item_hide/i.test(chidren[i].className)) {
					d++;
					continue;
				}
			}
			if ((Math.ceil((i-d)/2) - Math.floor((i-d)/2)) == 0) {
				if (/dhxrb_separator_group/i.test(chidren[i].className)) {
					chidren[i].parentNode.removeChild(chidren[i]);
					flag = true;
					break;
				}
			} else {
				if (!/dhxrb_separator_group/i.test(chidren[i].className)) {
					sep = document.createElement('div');
					sep.className = 'dhxrb_separator_groupp';
					item.base.insertBefore(sep,chidren[i]);
					flag = true;
					break;
				}
			}
		}
		
		if (flag) {
			this.normalize(item);
		} else {
			lastNode = item.base.lastChild;
			if (lastNode && /dhxrb_separator_group/i.test(lastNode.className)) {
				lastNode.parentNode.removeChild(lastNode);
			}
		}
	},
	
	hideChild: function(item, child) {
		var i = dhtmlXRibbon.prototype._indexOf(item.base.children, child.base);
		if (i == 0) i++;
		else if (i != -1) i--;
		else return;
		
		var sepNode = item.base.children[i];
		if (sepNode && /dhxrb_separator_group/i.test(sepNode.className)) {
			sepNode.parentNode.removeChild(sepNode);
		}
	},
	
	showChild: function(item) {
		this.normalize(item);
	}
};

dhtmlXRibbon.prototype.items.input = {
	render: function(cont, itemData) {
		var key, item;
		
		item = {
			base: cont,
			id: itemData.id,
			type: itemData.type,
			conf: {
				text: "",
				text_pos: (itemData.isbig)?"bottom":"right",
				img: null,
				imgdis: null,
				isbig: false,
				disable: false,
				skin: itemData.skin,
				value: ""
			}
		};
		
		for (key in itemData) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = itemData[key];
		}
		
		cont.innerHTML = "<input type='text' class='dhxrb_input'><div class='dhxrb_label_button'>"+item.conf.text+"</div>";
		
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		if (item.conf.disable) {
			this.disable(item);
		}
		
		if (item.conf.width) {
			this.setWidth(item, item.conf.width);
		}
		
		if (item.conf.value) {
			this.setValue(item, item.conf.value);
		}
		
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		var contForInput = item.base.childNodes[0];
		
		this._detachEventFromItem(item.base);
		this._attachEventsToInput(contForInput);
	},
	
	setText: dhtmlXRibbon.prototype.items.button.setText,
	
	getText: dhtmlXRibbon.prototype.items.button.getText,
	
	change: function(item) {
		var contForInput = item.base.childNodes[0];
		
		item.conf.value = contForInput.value;
	},
	
	keydown: function(item, rb, e) {
		if (e.keyCode == 13) {
			var contForInput = item.base.childNodes[0];
			item.conf.value = contForInput.value;
			rb.callEvent("onEnter",[item.id, item.conf.value]);
		}
	},
	
	remove: function(item, rb) {
		var contForInput = item.base.childNodes[0];
		rb._detachEventsFromInput(contForInput);
	},
	
	getValue: function(item) {
		var contForInput = item.base.childNodes[0], value;
		
		value = contForInput.value;
		contForInput = undefined;
		
		return value;
	},
	
	setValue: function(item, value) {
		var contForInput = item.base.childNodes[0], value;
		
		contForInput.value = value;
		item.conf.value = value;
	},
	
	setWidth: function(item, value) {
		var contForInput = item.base.childNodes[0];
		
		contForInput.style.width = parseInt(value)+"px";
	},
	
	disable: function(item) {
		var contForInput = item.base.childNodes[0],
			contForText = item.base.childNodes[1];
		
		contForInput.disabled = true;
		
		if (!/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className += " dhxrb_disable_text_style";
		}
		
		return true;
	},
	
	enable: function(item) {
		var contForInput = item.base.childNodes[0],
			contForText = item.base.childNodes[1];
		
		contForInput.disabled = false;
		
		if (/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className = contForText.className.replace(/\s?dhxrb_disable_text_style/i, "");
		}
			
		return true;
	},
	
	getInput: function(item){
		return item.base.childNodes[0];
	}
};

dhtmlXRibbon.prototype.getInput = function(id) {
	var item = this._items[id];
	if (item == null || item.type != "input") return null;
	return this.items[item.type].getInput(item);
};

dhtmlXRibbon.prototype.items.checkbox = {
	render: function (cont, itemData) {
		var key, item;
		
		item = {
			base: cont,
			id: itemData.id,
			type: itemData.type,
			conf: {
				text: "",
				text_pos: (itemData.isbig)?"bottom":"right",
				disable: false,
				checked: false
			}
		};
		
		for (key in itemData) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = itemData[key];
		}
		
		cont.innerHTML = "<div class='dhxrb_checkbox'></div><div class='dhxrb_label_checkbox'>"+item.conf.text+"</div>";
		
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		if (item.conf.checked) {
			this.check(item);
		}
		
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		
		if (item.conf.disable) {
			this.disable(item.id);
		}
	},
	
	setText: dhtmlXRibbon.prototype.items.button.setText,
	
	getText: dhtmlXRibbon.prototype.items.button.getText,
	
	mousedown: function(item) {
		return false;
	},
	
	mouseup: function(item) {
		return false;
	},
	
	click: function(item, rb, e) {
		if (e.button != 0) {
			return false;
		}
		if (item.type == "checkbox") {
			if (item.conf.checked) {
				this.uncheck(item, true);
			} else {
				this.check(item, true);
			}
		}
	},
	
	check: function(item, callEvent) {
		callEvent = callEvent || false;
		if (item.type != "checkbox") {
			return;
		}
		
		item.conf.checked = true;
		if (!/\s?dhxrb_checked/i.test(item.base.className)) {
			item.base.className += " dhxrb_checked";
		}
		
		if (callEvent) {
			item.callEvent("onCheck",[item.id, item.conf.checked]);
		}
	},
	
	uncheck: function(item, callEvent) {
		callEvent = callEvent || false;
		if (item.type != "checkbox") {
			return;
		}
		
		item.conf.checked = false;
		if (/\s?dhxrb_checked/i.test(item.base.className)) {
			item.base.className = item.base.className.replace(/\s?dhxrb_checked/i,"");
		}
		
		if (callEvent) {
			item.callEvent("onCheck",[item.id, item.conf.checked]);
		}
	},
	
	isChecked: function(item) {
		if (item.type != "checkbox") return false;
		return (item.conf.checked == true);
	},
	
	disable: function(item) {
		return true;
	},
	
	enable: function(item) {
		return true;
	}
};

dhtmlXRibbon.prototype.items.text = {
	render: function(cont, data) {
		var item = {
			base: cont,
			id: data.id,
			type: data.type,
			conf: {
				text: ""
			}
		};
		
		for (var key in data) {
			if (key == "id" || key == "type") {
				continue;
			}
			item.conf[key] = data[key];
		}
		
		
		cont.innerHTML = "<div class='dhxrb_item_text'>"+item.conf.text+"</div>";
		
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		
		this._detachEventFromItem(item.base);
	},
	
	getText: function(item) {
		return item.conf.text;
	},
	
	setText: function(item, text) {
		item.conf.text = text;
		item.base.firstChild.innerHTML = item.conf.text
	}
};

dhtmlXRibbon.prototype.items.buttonCombo = {
	render: function(cont, dataItem) {
		var key, item, comboConf={};
		
		item = {
			base: cont,
			id: dataItem.id,
			type: dataItem.type,
			conf: {
				text: "",
				text_pos: "right",
				width: 140,
				skin: dataItem.skin,
				callEvent: true,
				mode: dataItem.comboType,
				image_path: dataItem.comboImagePath,
				default_image: dataItem.comboDefaultImage,
				default_image_dis: dataItem.comboDefaultImageDis
			}
		};
		
		for (key in dataItem) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = dataItem[key];
		}
		
		for (key in item.conf) {
			if (key == "text" || key == "text_pos" || key == "disable" || key == "data") {
				continue;
			}
			
			comboConf[key] = item.conf[key];
		}
		
		item.base.className += " dhxrb_buttoncombo_cont";
		item.base.innerHTML = "<div class='dhxrb_buttoncombo'></div><div class='dhxrb_label_button'>"+item.conf.text+"</div>";
		
		comboConf.parent = item.base.firstChild;
		
		item.combo = new dhtmlXCombo(comboConf);
		
		item.combo.setSkin(comboConf.skin); // deprecated;
		
		item.combo.attachEvent("onChange", function(value, text) {
			item._callHandler(item.id, [value, text]);
			if (item.conf.callEvent == true) item.callEvent("onSelectOption", [item.id, value, text]);
			item.conf.callEvent = true;
		});
		
		if (item.conf.data) {
			item.combo.load(item.conf.data);
			delete item.conf.data;
		}
		
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		if (item.conf.disable) {
			this.disable(item);
		}
		
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		this._detachEventFromItem(item.base);
	},
	
	disable: function(item) {
		var contForText = item.base.lastChild;
		
		if (item.combo instanceof dhtmlXCombo) {
			item.combo.disable();
		}
		
		if (!/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className += " dhxrb_disable_text_style";
		}
		
		return true;
	},
	
	enable: function(item) {
		var contForText = item.base.lastChild;
		
		if (item.combo instanceof dhtmlXCombo) {
			item.combo.enable();
		}
		
		if (/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className = contForText.className.replace(/\s?dhxrb_disable_text_style/i, "");
		}
		
		return true;
	},
	
	remove: function(item) {
		if (item.combo instanceof dhtmlXCombo) {
			item.combo.unload();
			item.combo = null;
		}
	},
	
	getValue: function(item) {
		var answer = null;
		if (item.combo instanceof dhtmlXCombo) {
			answer = item.combo.getSelectedValue();
		}
		
		return answer;
	},
	
	setValue: function(item, value, callEvent) {
		if (item.combo instanceof dhtmlXCombo) {
			item.conf.callEvent = callEvent;
			item.combo.setComboValue(value);
			item.conf.callEvent = true;
		}
	},
	
	setSkin: function(item, skin) {
		if (item.combo instanceof dhtmlXCombo) {
			item.combo.setSkin(skin);
		}
	}
};

dhtmlXRibbon.prototype.items.slider = {
	render: function(cont, data) {
		var key, item, sliderConf={};
		
		item = {
			base: cont,
			id: data.id,
			type: data.type,
			conf: {
				text: "",
				text_pos: "right",
				size: 150,
				vertical: false,
				min: 0,
				max: 99,
				value: 0,
				step: 1,
				margin: 10,
				disabled: false,
				enableTooltip: false
			}
		};
		
		for (key in data) {
			if (key == "id" || key == "onclick" || key == "type") {
				continue;
			}
			
			item.conf[key] = data[key];
		}
		
		for (key in item.conf) {
			if (key == "text" || key == "text_pos" || key == "isbig") {
				continue;
			}
			
			sliderConf[key] = item.conf[key];
		}
		
		item.base.innerHTML = "<center><div class='dhxrb_slider'></div></center><div class='dhxrb_label_button'>"+item.conf.text+"</div>";
	
		sliderConf.parent = item.base.firstChild.firstChild;
		
		item.slider = new dhtmlXSlider(sliderConf);
	
		if (typeof(this.afterRender) == "function") {
			this.afterRender(item);
		}
		
		if (item.conf.disable) {
			this.disable(item);
		}
		
		item.slider.attachEvent("onChange", function(value) {
			item._callHandler(item.id, [value]);
			item.callEvent("onValueChange", [item.id, value]);
		});
	
		return item;
	},
	
	callAfterInit: function(item) {
		/* @var this dhtmlXRibbon */
		this._detachEventFromItem(item.base);
	},
	
	setSkin: function(item, skin) {
		if (item.slider instanceof dhtmlXSlider) {
			item.slider.setSkin(skin);
		}
	},
	
	disable: function(item) {
		var contForText = item.base.childNodes[1];
		
		if (item.slider instanceof dhtmlXSlider) {
			item.slider.disable();
		}
		
		if (!/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className += " dhxrb_disable_text_style";
		}
		return true;
	},
	
	enable: function(item) {
		var contForText = item.base.childNodes[1];
		
		if (item.slider instanceof dhtmlXSlider) {
			item.slider.enable();
		}
		
		if (/\s?dhxrb_disable_text_style/i.test(contForText.className)) {
			contForText.className = contForText.className.replace(/\s?dhxrb_disable_text_style/i, "");
		}
		
		return true;
	},
	
	remove: function(item) {
		if (item.slider instanceof dhtmlXSlider) {
			item.slider.unload();
			item.slider = null;
		}
	},
	
	getValue: function(item) {
		var answer = null;
		if (item.slider instanceof dhtmlXSlider) {
			answer = item.slider.getValue();
		}
		
		return answer;
	},
	
	setValue: function(item,value) {
		if (item.slider instanceof dhtmlXSlider) {
			item.slider.setValue(value);
		}
	}
};

/******************************************************/

dhtmlXRibbon.prototype.items._extends = function(item,parent) {
	var key;
	
	for (key in parent) {
		item[key] = item[key] || parent[key];
	}
	
	return item;
};

dhtmlXRibbon.prototype._addNewLevel = function (block) {
	var lastNode,cont;
	if (block.conf.mode == 'rows') {
		lastNode = block.contForItems.lastChild;
		
		if (lastNode && /dhxrb_block_rows/i.test(lastNode.className) && (lastNode.childNodes.length < 3)) {
			
			cont = document.createElement('div');
			cont.className = "dhxrb_block_row";
			lastNode.appendChild(cont);
			
		} else {
			lastNode = document.createElement('div');
			lastNode.className = "dhxrb_block_rows";
			block.contForItems.appendChild(lastNode);
			cont = document.createElement('div');
			cont.className = "dhxrb_block_row";
			lastNode.appendChild(cont);
		}
		
	} else {
		var cont = document.createElement("div");
		cont.className = "dhxrb_3rows_block";
		block.contForItems.appendChild(cont);
	}
};

dhtmlXRibbon.prototype._attachEventForItem = function(cont) {
	if (typeof(window.addEventListener) == "function") {
		cont.addEventListener("mouseover", this._doOnHighlight0, false);
		cont.addEventListener("mouseout", this._doOffHighlight0, false);
		cont.addEventListener("mousedown", this._doOnHighlight1, false);
		cont.addEventListener("mouseup", this._doOffHighlight1, false);
		cont.addEventListener("click", this._doOnClick, false);
	} else {
		cont.attachEvent("onmouseover", this._doOnHighlight0);
		cont.attachEvent("onmouseout", this._doOffHighlight0);
		cont.attachEvent("onmousedown", this._doOnHighlight1);
		cont.attachEvent("onmouseup", this._doOffHighlight1);
		cont.attachEvent("onclick", this._doOnClick);
	}
};

dhtmlXRibbon.prototype._detachEventFromItem = function(cont) {
	if (typeof(window.addEventListener) == "function") {
		cont.removeEventListener("mouseover", this._doOnHighlight0, false);
		cont.removeEventListener("mouseout", this._doOffHighlight0, false);
		cont.removeEventListener("mousedown", this._doOnHighlight1, false);
		cont.removeEventListener("mouseup", this._doOffHighlight1, false);
		cont.removeEventListener("click", this._doOnClick, false);
	} else {
		cont.detachEvent("onmouseover", this._doOnHighlight0);
		cont.detachEvent("onmouseout", this._doOffHighlight0);
		cont.detachEvent("onmousedown", this._doOnHighlight1);
		cont.detachEvent("onmouseup", this._doOffHighlight1);
		cont.detachEvent("onclick", this._doOnClick);
	}
};

dhtmlXRibbon.prototype._attachEventsToInput = function(cont) {
	if (typeof(window.addEventListener) == "function") {
		cont.addEventListener("focus", this._doOnFocus, false);
		cont.addEventListener("blur", this._doOnBlur, false);
		cont.addEventListener("change", this._doOnChange, false);
		cont.addEventListener("keydown", this._doOnKeydown, false);
	} else {
		cont.attachEvent("onfocus", this._doOnFocus);
		cont.attachEvent("onblur", this._doOnBlur);
		cont.attachEvent("onchange", this._doOnChange);
		cont.attachEvent("onkeydown", this._doOnKeydown);
	}
};

dhtmlXRibbon.prototype._detachEventsFromInput = function(cont) {
	if (typeof(window.addEventListener) == "function") {
		cont.removeEventListener("focus", this._doOnFocus, false);
		cont.removeEventListener("blur", this._doOnBlur, false);
		cont.removeEventListener("change", this._doOnChange, false);
		cont.removeEventListener("keydown", this._doOnKeydown, false);
	} else {
		cont.detachEvent("onfocus", this._doOnFocus);
		cont.detachEvent("onblur", this._doOnBlur);
		cont.detachEvent("onchange", this._doOnChange);
		cont.detachEvent("onkeydown", this._doOnKeydown);
	}
};

dhtmlXRibbon.prototype._getContainerForSmallItem = function(parent) {
	var last_el = parent.contForItems.lastChild, cont = null;
	
	if (parent.conf.mode == "rows") {
		
		if (last_el && /\s?dhxrb_block_rows/i.test(last_el.className)) {
			cont = last_el.lastChild;
			if (!cont) {
				cont = document.createElement('div');
				cont.className = "dhxrb_block_row";
				last_el.appendChild(cont);
			}
		} else {
			last_el = document.createElement('div');
			last_el.className = "dhxrb_block_rows";
			parent.contForItems.appendChild(last_el);
			cont = document.createElement('div');
			cont.className = "dhxrb_block_row";
			last_el.appendChild(cont);
		}
	} else {
		
		if (last_el && /dhxrb_3rows_block/i.test(last_el.className) && (last_el.childNodes.length < 3)) {
			cont = last_el;
		} else {
			cont = document.createElement("div");
			cont.className = "dhxrb_3rows_block";
			parent.contForItems.appendChild(cont);
		}
		
	}
	
	return cont;
};

dhtmlXRibbon.prototype._findItemByNode = function(el) {
	while (el && !el._dhx_ribbonId) {
		el = el.parentNode;
	}
	return el;
};

dhtmlXRibbon.prototype._indexOf =  function(arr,item) {
	var i,l;
	l = arr.length;
	for (i=0; i<l; i++) {
		if (arr[i] == item) {
			return i;
		}
	}
	
	return -1;
};

dhtmlXRibbon.prototype._removeItem = function(item) {
	var parentNode, ind=-1, parent = this._items[item.parentId];
	
	if (item.type == "group") {
		this._removeGroup(item);
		return;
	}
	
	delete this._items[item.id];
	parentNode = item.base.parentNode;
	
	this._detachEventFromItem(item.base);
	parentNode.removeChild(item.base);
	
	if (parent.type == "block") {
		if (parentNode != parent.contForItems && parentNode.childNodes.length == 0) {
			parentNode.parentNode.removeChild(parentNode);
		}
	} else if (parent.type == "group") {
		dhtmlXRibbon.prototype.items.group.normalize(parent);
	}
	
	ind = this._indexOf(parent.childIds, item.id);
	
	if (ind != -1) {
		parent.childIds.splice(ind,1);
	}
	
	if (this.items[item.type] && (typeof(this.items[item.type].remove) == "function")) {
		this.items[item.type].remove(item, this);
	}
};

dhtmlXRibbon.prototype._removeGroup = function(item) {
	var items = [], ind, parent = this._items[item.parentId], parentNode = item.base.parentNode;
	
	for (var q=0; q<item.childIds.length; q++) {
		items.push(this._items[item.childIds[q]]);
	}
	
	for (var q=0; q<items.length; q++) {
		this._removeItem(items[q]);
	}
	
	delete this._items[item.id];
	if (item.base.parentNode) parentNode.removeChild(item.base);
	
	if (parentNode.childNodes.length == 0) {
		parentNode.parentNode.removeChild(parentNode);
	}
	
	ind = this._indexOf(parent.childIds, item.id);
	if (ind != -1) {
		parent.childIds.splice(ind,1);
	}
	
	if (this.items[item.type] && (typeof(this.items[item.type].remove) == "function")) {
		this.items[item.type].remove(item);
	}
};

dhtmlXRibbon.prototype._removeBlock = function(block) {
	var items = [], ind, parent;
	
	for (var q=0; q<block.childIds.length; q++) {
		items.push(this._items[block.childIds[q]]);
	}
	
	for (var q=0; q<items.length; q++) {
		this._removeItem(items[q]);
	}
	
	delete this._items[block.id];
	
	block.base.parentNode.removeChild(block.base);
	
	if (block.parentId) {
		parent = this._items[block.parentId];
	} else {
		parent = this;
	}
	
	ind = this._indexOf(parent.childIds,block.id);
	
	if (ind != -1) {
		parent.childIds.splice(ind,1);
	}
};

dhtmlXRibbon.prototype._removeTab = function(tab,activeTabId) {
	var blocks = [], ind;
	
	for (var q=0; q<tab.childIds.length; q++) {
		blocks.push(this._items[tab.childIds[q]]);
	}
	
	for (var q=0; q<blocks.length; q++) {
		this._removeBlock(blocks[q]);
	}
	
	delete this._items[tab.id];
	
	ind = this._indexOf(this.childIds,tab.id);
	
	if (ind != -1) {
		this.childIds.splice(ind,1);
	}
};

dhtmlXRibbon.prototype._skinCollection = {
	dhx_skyblue: true,
	dhx_web: true,
	dhx_terrace: true,
	material: true
};

dhtmlXRibbon.prototype._setSkinForItems = function(value) {
	var key, item;
	
	for (key in this._items) {
		item = this._items[key];
		
		item.conf.skin = value;
		
		if (dhtmlXRibbon.prototype.items[item.type] && typeof(dhtmlXRibbon.prototype.items[item.type].setSkin) == "function") {
			dhtmlXRibbon.prototype.items[item.type].setSkin(item,value);
		}
		
	}
	
	item = undefined, key = undefined;
};

dhtmlXRibbon.prototype._setBlockText = function(item, text) {
	item.conf.text = text;
	item.contForText.innerHTML = text;
	
	if (!text && (text != 0) && item.contForText.parentNode) {
		item.contForText.parentNode.removeChild(item.contForText);
	} else if (!item.contForText.parentNode) {
		if (item.conf.text_pos == "top") {
			item.base.insertBefore(item.contForText, item.contForItems);
		} else {
			item.base.appendChild(item.contForText);
		}
	}
};

/* Public API */

dhtmlXRibbon.prototype.hide = function(id) {
	var item = this._items[id];
	
	if (this.items[item.type] && (typeof(this.items[item.type].hide) == "function")) {
		if (this.items[item.type].hide(item) != true) {
			return;
		}
	}
	
	if (item.type == "tab") {
		return;
	} else if (!/\s?dhxrb_item_hide/i.test(item.base.className)) {
		item.base.className += " dhxrb_item_hide";
	}
	
	var parent = this._items[item.parentId];
	if (parent && this.items[parent.type] && (typeof(this.items[parent.type].hideChild) == "function")) {
		this.items[parent.type].hideChild(parent, item);
	}
};

dhtmlXRibbon.prototype.show = function(id) {
	var item = this._items[id];
	
	if (this.items[item.type] && (typeof(this.items[item.type].show) == "function")) {
		if (this.items[item.type].show(item) != true) {
			return;
		}
	}
	
	if (item.type == "tab") {
		return;
	} else if (/\s?dhxrb_item_hide/i.test(item.base.className)) {
		item.base.className = item.base.className.replace(/\s?dhxrb_item_hide/i, "");
	}
	
	var parent = this._items[item.parentId];
	if (parent && this.items[parent.type] && (typeof(this.items[parent.type].showChild) == "function")) {
		this.items[parent.type].showChild(parent, item);
	}
};

dhtmlXRibbon.prototype.check = function(id,callEvent) {
	callEvent = dhx4.s2b(callEvent);
	var item  = this._items[id];
	
	if (item && !item.conf.checked && typeof(this.items[item.type].check) == "function") {
		this.items[item.type].check(item);
		if (callEvent) this.callEvent("onCheck",[item.id, item.conf.checked]);
	}
};

dhtmlXRibbon.prototype.uncheck = function(id, callEvent) {
	callEvent = dhx4.s2b(callEvent);
	var item  = this._items[id];
	
	if (item && item.conf.checked && typeof(this.items[item.type].uncheck) == "function") {
		this.items[item.type].uncheck(item);
		if (callEvent) this.callEvent("onCheck",[item.id, item.conf.checked]);
	}
};

dhtmlXRibbon.prototype.isChecked = function(id) {
	var item  = this._items[id];
	if (item && typeof(this.items[item.type].isChecked) == "function") {
		return this.items[item.type].isChecked(item);
	}
};

dhtmlXRibbon.prototype.disable = function(id,activetab) {
	var item = this._items[id];
	
	if (this.items[item.type] && (typeof(this.items[item.type].disable) == "function")) {
		if (this.items[item.type].disable(item) != true) return;
	}
	
	if (item.type == "tab") {
		return;
	} else if (item.type == "block" || item.type == "group") {
		for (var q=0; q<item.childIds.length; q++) {
			this.disable(item.childIds[q]);
		}
	}
	
	if (!/\s?dhxrb_item_disable/i.test(item.base.className)) {
		item.base.className += " dhxrb_item_disable";
	}
	
	if (item.base.className.match(/dhxrb_highlight/gi) != null) {
		if (item.type == "buttonTwoState") {
			item.base.className = item.base.className.replace(/\s*dhxrb_highlight0/gi, "");
		} else {
			item.base.className = item.base.className.replace(/\s*dhxrb_highlight\d/gi, "");
		}
	}
	
	item.conf.disable = true;
};

dhtmlXRibbon.prototype.enable = function(id,activetab) {
	var item = this._items[id];
	
	if (this.items[item.type] && (typeof(this.items[item.type].enable) == "function")) {
		if (this.items[item.type].enable(item) != true) {
			return;
		}
	}
	
	if (item.type == "tab") {
		return;
	} else if (item.type == "block" || item.type == "group") {
		for (q=0; q<item.childIds.length; q++) {
			this.enable(item.childIds[q]);
		}
	}
	
	if (/\s?dhxrb_item_disable/i.test(item.base.className)) {
		item.base.className = item.base.className.replace(/\s?dhxrb_item_disable/i, "");
	}
	
	item.conf.disable = false;
};

dhtmlXRibbon.prototype.isEnabled = function(id) {
	var item = this._items[id];
	
	if (this.items[item.type] && typeof(this.items[item.type].isEnabled) == "function") {
		return this.items[item.type].isEnabled(item);
	}
	
	if (item.type == "tab") {
		return;
	} else {
		return item.conf.disable != true;
	}
};

dhtmlXRibbon.prototype.isVisible = function(id) {
	var item = this._items[id];
	
	if (this.items[item.type] && (typeof(this.items[item.type].isVisible) == "function")) {
		return this.items[item.type].isVisible(item);
	}
	
	if (item.type == "tab") {
		return;
	} else {
		return !/\s?dhxrb_item_hide/i.test(item.base.className);
	}
};

dhtmlXRibbon.prototype.setItemState = function(id, value, callEvent) {
	value = dhx4.s2b(value);
	callEvent = dhx4.s2b(callEvent);
	var item = this._items[id];
	
	if (item && (typeof(this.items[item.type].setState) == "function")) {
		switch (item.type) {
			case "buttonSegment":
				this.items[item.type].setState(item, this, callEvent);
				break;
			default:
				this.items[item.type].setState(item,value, callEvent);
		}
	}
};

dhtmlXRibbon.prototype.getItemState = function(id) {
	var item = this._items[id];
	if (item && (typeof(this.items[item.type].getState) == "function")) {
		switch (item.type) {
			case "buttonTwoState":
			case "buttonSegment":
				return this.items[item.type].getState(item);
				break;
		}
	}
	return null;
};

dhtmlXRibbon.prototype.setIconPath = function(str) {
	this.conf.icons_path = str;
};

dhtmlXRibbon.prototype.setIconset = function(name) {
	this.conf.icons_css = (name == "awesome");
};

dhtmlXRibbon.prototype.removeItem = function(id) {
	var item = this._items[id];
	if (item == null) return;
	switch (item.type) {
		case "tab":
			break;
		case "block":
			this._removeBlock(item);
			break;
		case "group":
			this._removeGroup(item);
			break;
		default:
			this._removeItem(item);
	}
};

dhtmlXRibbon.prototype.setSkin = function(skin) {
	skin = (typeof(skin) == "string")?skin.toLowerCase():"";
	
	if (this._skinCollection[skin] != true) {
		return;
	}
	
	var classes, _int = -1, skinName, className = "dhtmlxribbon";
	
	classes = this._base.className.match(/\S\w+/ig);
	
	if (classes instanceof Array) {
		for (skinName in this._skinCollection) {
			if (_int == -1) {
				_int = this._indexOf(classes, className+"_"+skinName);
			} else {
				break;
			}
		}
		
		_int = (_int == -1)?classes.length:_int;
	} else {
		classes = [];
		_int = 0;
	}
	
	classes[_int] = className+"_"+skin;
	
	this._base.className = classes.join(" ");
	this.conf.skin = skin;
	
	if (this._tabbar != null) {
		this._tabbar.setSkin(skin);
	}
	
	this._setSkinForItems(skin);
};

dhtmlXRibbon.prototype.tabs = function(id) {
	if (this._tabbar instanceof dhtmlXTabBar) {
		return this._tabbar.tabs(id);
	} else {
		return undefined;
	}
};

dhtmlXRibbon.prototype.getItemType = function(id) {
	var item = this._items[id];
	if (item) {
		return item.type;
	} else {
		return undefined;
	}
};

dhtmlXRibbon.prototype.getValue = function(id) {
	var item = this._items[id], answer = undefined;
	
	if (item && this.items[item.type] && typeof(this.items[item.type].getValue) == "function") {
		answer = this.items[item.type].getValue(item);
	}
	
	return answer;
};

dhtmlXRibbon.prototype.setValue = function(id, value, callEvent) {
	
	var item = this._items[id];
	callEvent = (callEvent === false ? false : true);
	
	if (item && this.items[item.type] && typeof(this.items[item.type].setValue) == "function") {
		this.items[item.type].setValue(item, value, callEvent);
	}
};

dhtmlXRibbon.prototype.getItemText = function(id) {
	var item = this._items[id];
	
	if (!item) {
		return null;
		
	} else if (item.type == "tab") {
		return this.tabs(item.id).getText();
		
	} else if (item.type == "block") {
		return this.items.button.getText(item);
		
	} else if (this.items[item.type] && typeof(this.items[item.type].getText) == "function") {
		return this.items[item.type].getText(item);
		
	} else {
		return null;
	}
};

dhtmlXRibbon.prototype.setItemText = function(id, text) {
	var item = this._items[id];
	
	if (!item) {
		return;
	} else if (item.type == "tab") {
		this.tabs(item.id).setText(text);
		
	} else if (item.type == "block") {
		this._setBlockText(item, text);
		
	} else if (this.items[item.type] && typeof(this.items[item.type].setText) == "function") {
		this.items[item.type].setText(item,text);
	}
};

// button select's menu
dhtmlXRibbon.prototype.setItemOptionText = function(id, optId, text) {
	var item = this._items[id];
	if (item.type == "buttonSelect") this.items[item.type].setOptionText(item, optId, text);
};

dhtmlXRibbon.prototype.getItemOptionText = function(id, optId) {
	var item = this._items[id];
	if (item.type == "buttonSelect") return this.items[item.type].getOptionText(item, optId);
	return null;
};

dhtmlXRibbon.prototype.setItemImage = function(id, img) {
	var item = this._items[id];
	
	if (item != null && this.items[item.type] != null && typeof(this.items[item.type].setImage) == "function") {
		this.items[item.type].setImage(item, img);
	}
};
dhtmlXRibbon.prototype.setItemImageDis = function(id, imgdis) {
	var item = this._items[id];
	
	if (item != null && this.items[item.type] != null && typeof(this.items[item.type].setImageDis) == "function") {
		this.items[item.type].setImageDis(item, imgdis);
	}
};

// combo's
dhtmlXRibbon.prototype.getCombo = function(id) {
	var item = this._items[id];
	if (item != null && item.type == "buttonCombo") return item.combo;
	return null;
};

(function(){
	var i = dhtmlXRibbon.prototype.items;
	i.buttonTwoState = i._extends(i.buttonTwoState, i.button);
	i.buttonSelect = i._extends(i.buttonSelect, i.buttonTwoState);
	i.buttonSegment = i._extends(i.buttonSegment, i.buttonTwoState);
})();
if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype._createNode_ribbon = function(obj, type, htmlString, append, node) {
		
		if (typeof(node) != "undefined") {
			obj = node;
		} else {
			obj = document.createElement("DIV");
			obj.className = "dhx_cell_ribbon_"+(this.conf.borders?"def":"no_borders");
			obj.appendChild(document.createElement("DIV"));
		}
		
		this.cell.insertBefore(obj, this.cell.childNodes[this.conf.idx.cont]); // before cont only
		this.conf.ofs_nodes.t.ribbon = true;
		this._updateIdx();
		this._adjustCont(this._idd);
		
		return obj;
	};
	
	dhtmlXCellObject.prototype.attachRibbon = function(conf) {
		
		if (!(this.dataNodes.ribbon == null && this.dataNodes.toolbar == null)) return;
		
		this.callEvent("_onBeforeContentAttach", ["ribbon"]);
		
		if (typeof(conf) == "undefined") conf = {};
		if (typeof(conf.skin) == "undefined") conf.skin = this.conf.skin;
		
		conf.parent = this._attachObject("ribbon").firstChild;
		
		this.dataNodes.ribbon = new dhtmlXRibbon(conf);
		
		var t = this;
		this.dataNodes.ribbon.attachEvent("_onHeightChanged", function(){
				t._adjustCont(t._idd);
		});
		
		this._adjustCont();
		
		conf.parent = null;
		conf = null;
		
		this.callEvent("_onContentAttach", []);
		
		return this.dataNodes.ribbon;
		
	};
	
	dhtmlXCellObject.prototype.detachRibbon = function() {
		
		if (!this.dataNodes.ribbon) return;
		this.dataNodes.ribbon.unload();
		this.dataNodes.ribbon = null;
		delete this.dataNodes.ribbon;
		
		this._detachObject("ribbon");
		
	};
	
	dhtmlXCellObject.prototype.showRibbon = function() {
		this._mtbShowHide("ribbon", "");
	};
	
	dhtmlXCellObject.prototype.hideRibbon = function() {
		this._mtbShowHide("ribbon", "none");
	};
	
	dhtmlXCellObject.prototype.getAttachedRibbon = function() {
		return this.dataNodes.ribbon;
	};
	
}

