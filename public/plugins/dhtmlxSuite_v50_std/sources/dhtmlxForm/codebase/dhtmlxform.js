/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXForm(parentObj, data, skin) {
	
	this.idef = {
		position:	"label-left",
		labelWidth:	"auto",
		labelHeight:	"auto",
		inputWidth:	"auto",
		inputHeight:	"auto",
		labelAlign:	"left",
		noteWidth:	"auto",
		offsetTop:	0,
		offsetLeft:	0,
		blockOffset:	20 // block only
	};
	this.idef_const = {
		offsetNested:	20 // sub_level
	};
	this.apos_css = {
		"label-left":	"dhxform_item_label_left",
		"label-right":	"dhxform_item_label_right",
		"label-top":	"dhxform_item_label_top",
		"label-bottom":	"dhxform_item_label_bottom", // new
		"absolute":	"dhxform_item_absolute"
	};
	this.align_css = {
		left:		"dhxform_label_align_left",
		center:		"dhxform_label_align_center",
		right:		"dhxform_label_align_right"
	};
	
	var that = this;
	
	// define skin
	
	// 1) skin 3rd arg [new]
	// 2) dhtmlx.skin
	// 3) autodetect skin
	// 4) default skyblue
	
	this.setSkin = function(skin) {
		this.skin = skin;
		this.cont.className = "dhxform_obj_"+this.skin;
		this.cont.style.fontSize = (skin=="material"?"14px":(skin=="dhx_terrace"?"13px":"12px"));
		this._updateBlocks();
		// update calendar skin
		this.forEachItem(function(id){
			var t = that.getItemType(id);
			if (typeof(that.items[t]) != "undefined" && typeof(that.items[t].setSkin) == "function") {
				that.doWithItem(id, "setSkin", skin);
			}
		});
	}
	
	this.skin = (skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhx_form")||"material");
	
	this.separator = ",";
	this.live_validate = false;
	
	this._type = "checkbox";
	this._rGroup = "default";
	
	this._idIndex = {};
	this._indexId = [];
	
	this.cont = (typeof(parentObj)=="object"?parentObj:document.getElementById(parentObj));
	
	if (!parentObj._isNestedForm) {
		
		this._parentForm = true;
		
		this.cont.style.fontSize = (this.skin=="material"?"14px":(this.skin=="dhx_terrace"?"13px":"12px"));
		this.cont.className = "dhxform_obj_"+this.skin;
		
		this.setFontSize = function(fs) {
			this.cont.style.fontSize = fs;
			this._updateBlocks();
		}
		
		this.getForm = function() {
			return this;
		}
		
		this.cont.onkeypress = function(e) {
			e = (e||event);
			if (e.keyCode == 13) {
				var t = (e.target||e.srcElement);
				if (typeof(t.tagName) != "undefined" && String(t.tagName).toLowerCase() == "textarea" && !e.ctrlKey) return;
				that.callEvent("onEnter",[]);
			}
		}
		
	}
	
	this.b_index = null;
	this.base = [];
	this._prepare = function(ofsLeft, pos) {
		
		if (this.b_index == null) this.b_index = 0; else this.b_index++;
		
		// if pos specified, check all items inside all bases,
		var insBeforeBase = null; // base
		var insBeforeItem = null; // items from start to move next-items within single base
		
		if (pos != null) {
			if (pos < 0) pos = 0;
			var i = 0;
			for (var w=0; w<this.cont.childNodes.length; w++) { // bases sit here, 1 base = 1 newcolumn
				for (var q=0; q<this.cont.childNodes[w].childNodes.length; q++) { // items inside single base, i.e.between two nearest newcolumns/form_start/form_end
					if (insBeforeItem == null && this.cont.childNodes[w].childNodes[q]._isNestedForm != true) {
						if (i == pos) {
							insBeforeBase = this.cont.childNodes[w].nextSibling; // insert new column before this base
							insBeforeItem = this.cont.childNodes[w].childNodes[q]; // move all items within single base from this item to end of base to new column
						}
						i++;
					}
				}
			}
		}
		
		this.base[this.b_index] = document.createElement("DIV");
		this.base[this.b_index].className = "dhxform_base";
		
		if (typeof(ofsLeft) != "undefined") this.base[this.b_index].style.cssText += " margin-left:"+ofsLeft+"px!important;";
		
		// add block
		if (insBeforeBase != null) {
			this.cont.insertBefore(this.base[this.b_index], insBeforeBase);
			insBeforeBase = null;
		} else {
			this.cont.appendChild(this.base[this.b_index]);
		}
		
		// move items if any
		if (insBeforeItem != null) {
			while (insBeforeItem != null) {
				var t = insBeforeItem;
				insBeforeItem = insBeforeItem.nextSibling;
				this.base[this.b_index].appendChild(t);
				t = null;
			}
		}
	}
	
	
	this.setSizes = function() {
		/*
		for (var q=0; q<this.base.length; q++) {
			this.base.style.height = this.cont.offsetHeight+"px";
			this.base.style.overflow = "auto";
		}
		*/
	}
	
	this._mergeSettings = function(data) {
		
		var u = -1;
		var i = {type: "settings"};
		for (var a in this.idef) i[a] = this.idef[a];
		
		for (var q=0; q<data.length; q++) {
			if (typeof(data[q]) != "undefined" && data[q].type == "settings") {
				for (var a in data[q]) i[a] = data[q][a];
				u = q;
			}
		}
		data[u>=0?u:data.length] = i;
		return data;
	}
	
	this._genStr = function(w) {
		var s = "dhxId_";
		var z = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for (var q=0; q<w; q++) s += z.charAt(Math.floor(Math.random() * z.length));
		return s;
	}
	
	this.idPrefix = "dhxForm_"+this._genStr(12)+"_";
	
	this._rId = (this._parentForm?this._genStr(12)+"_":parentObj._rId);
	
	this.objPull = {};
	this.itemPull = {};
	this._ic = 0;
	
	this._addItem = function(type, id, data, sId, lp, pos, insertAfter) {
		
		// id-index
		if (this.items[type]._index) {
			this.getForm()._indexId.push(id);
			this.getForm()._idIndex[id] = {ind: this.getForm()._indexId.length-1};
		}
		
		if (!type) type = this._type;
		
		if (type == "list" && lp != null && this.itemPull[this.idPrefix+lp] != null && typeof(this.itemPull[this.idPrefix+lp]._addSubListNode) == "function") {
			var tr = this.itemPull[this.idPrefix+lp]._addSubListNode();
		} else {
			if (type == "newcolumn") {
				var tr = {};
			} else {
				var insBeforeBase = this.base[this.b_index];
				var insBeforeItem = null;
				if (typeof(pos) != "undefined" && !isNaN(pos) && type != "list") {
					pos = Math.max(parseInt(pos), 0)+1;
					for (var w=0; w<this.cont.childNodes.length; w++) {
						for (var q=0; q<this.cont.childNodes[w].childNodes.length; q++) {
							if (insBeforeItem == null && this.cont.childNodes[w].childNodes[q]._isNestedForm != true) {
								pos--;
								if (pos == 0) {
									insBeforeItem = this.cont.childNodes[w].childNodes[q];
									insBeforeBase = this.cont.childNodes[w];
								}
							}
						}
					}
				} else if (type == "list") { //??
					for (var a in this.itemPull) {
						if (a == this.idPrefix+id) {
							insBeforeItem = this.itemPull[a].nextSibling;
							if (this.itemPull[a]._listBase != null && this.itemPull[a]._listBase.length > 0) {
								insBeforeItem = this.itemPull[a]._listBase[this.itemPull[a]._listBase.length-1];
							}
							
						}
					}
				}
				var tr = document.createElement("DIV");
				if (insertAfter == true && insBeforeItem != null) insBeforeItem = insBeforeItem.nextSibling;
				if (insBeforeItem != null) {
					insBeforeBase.insertBefore(tr, insBeforeItem);
				} else {
					insBeforeBase.appendChild(tr);
				}
			}
		}
		
		tr._idd = id;
		tr._rId = this._rId;
		
		if (typeof(tr.style) != "undefined") {
			// read from settings if not set
			if (typeof(data.offsetLeft) == "undefined" && this.idef.offsetLeft > 0) data.offsetLeft = this.idef.offsetLeft;
			if (typeof(data.offsetTop) == "undefined" && this.idef.offsetTop > 0) data.offsetTop = this.idef.offsetTop;
			//
			var k = "";
			if (typeof(data.offsetLeft) != "undefined") k += " padding-left:"+data.offsetLeft+"px!important;";
			if (typeof(data.offsetTop) != "undefined") k += " padding-top:"+data.offsetTop+"px!important;";
			tr.style.cssText += k;
		}
		
		if (type == "block") {
			if (isNaN(data.blockOffset)) data.blockOffset = this.idef.blockOffset;
		}
		
		if (type == "list") {
			
			if (typeof(tr._ofsNested) == "undefined") tr._ofsNested = this.idef_const.offsetNested;
			
			if (sId != null) tr._sId = sId;
			
			var listData = this.items[type].render(tr, this.skin);
			
			if (!this.itemPull[this.idPrefix+id]._listObj) this.itemPull[this.idPrefix+id]._listObj = [];
			if (!this.itemPull[this.idPrefix+id]._list) this.itemPull[this.idPrefix+id]._list = [];
			if (!this.itemPull[this.idPrefix+id]._listBase) this.itemPull[this.idPrefix+id]._listBase = [];
			
			(this.itemPull[this.idPrefix+id]._listObj).push(listData[0]);
			(this.itemPull[this.idPrefix+id]._list).push(listData[1]);
			(this.itemPull[this.idPrefix+id]._listBase).push(tr);
			
			listData[1].checkEvent = function(evName) {
				return that.checkEvent(evName);
			}
			listData[1].callEvent = function(evName, evData) {
				return that.callEvent(evName, evData);
			}
			listData[1].getForm = function() {
				return that.getForm();
			}
			listData[1]._initObj(this._mergeSettings(data));
			
			if (tr._inBlcok) tr.className += " in_block";
			
			return listData[1];
			
		}
		
		if (type == "newcolumn") {
			this._prepare(data.offset, pos);
			return;
		}
		
		if (type == "label" && this._ic++ == 0) data._isTopmost = true;
		
		data.position = this.apos_css[(!data.position||!this.apos_css[data.position]?this.idef.position:data.position)];
		tr.className = data.position+(typeof(data.className)=="string"?" "+data.className:"");
		
		if (!data.labelWidth) data.labelWidth = this.idef.labelWidth;
		if (!data.labelHeight) data.labelHeight = this.idef.labelHeight;
		
		if (typeof(data.wrap) != "undefined") data.wrap = window.dhx4.s2b(data.wrap);
			
		data.labelAlign = (this.align_css[data.labelAlign]?this.align_css[data.labelAlign]:this.align_css[this.idef.labelAlign]);
		
		data.inputWidth = (data.width?data.width:(data.inputWidth?data.inputWidth:this.idef.inputWidth));
		if (!data.inputHeight) data.inputHeight = this.idef.inputHeight;
		
		if (typeof(data.note) != "undefined") {
			if (data.note.length != null && data.note[0] != null) data.note = data.note[0]; // probably array from xml conversion
			if (typeof(data.note.width) == "undefined") data.note.width = this.idef.noteWidth;
			if (data.note.width == "auto") data.note.width = data.inputWidth;
		}
		
		tr.checkEvent = function(evName) {
			return that.checkEvent(evName);
		}
		tr.callEvent = function(evName, evData) {
			return that.callEvent(evName, evData);
		}
		tr.getForm = function() {
			return that.getForm();
		}
		tr._autoCheck = function(t) {
			that._autoCheck(t);
		}
		
		// convert r/o
		if (typeof(data.readonly) == "string") data.readonly = window.dhx4.s2b(data.readonly);
		if (typeof(data.autoStart) == "string") data.autoStart = window.dhx4.s2b(data.autoStart);
		if (typeof(data.autoRemove) == "string") data.autoRemove = window.dhx4.s2b(data.autoRemove);
		if (typeof(data.titleScreen) == "string") data.titleScreen = window.dhx4.s2b(data.titleScreen);
		if (typeof(data.info) == "string") data.info = window.dhx4.s2b(data.info);
		if (typeof(data.hidden) == "string") data.hidden = window.dhx4.s2b(data.hidden);
		if (typeof(data.checked) == "string") data.checked = window.dhx4.s2b(data.checked);
		
		// userdata
		if (typeof(data.userdata) != "undefined") {
			for (var a in data.userdata) this.getForm().setUserData(id,a,data.userdata[a]);
		}
		
		// validate
		if (data.validate) {
			if (typeof(data.validate != "undefined") && (typeof(data.validate) == "function" || typeof(window[data.validate]) == "function")) {
				tr._validate = [data.validate];
			} else {
				tr._validate = String(data.validate).split(this.separator);
			}
		}
		if (typeof(data.required) != "undefined") {
			if (typeof(data.required) == "string") data.required = window.dhx4.s2b(data.required);
			tr._required = (data.required==true);
		}
		if (tr._required) {
			if (!tr._validate) tr._validate = [];
			var p = false;
			for (q=0; q<tr._validate.length; q++) p = (p||(tr._validate[q]=="NotEmpty"));
			if (!p) tr._validate.push("NotEmpty");
		}
		
		tr._ll = (data.position == this.apos_css["label-left"] || data.position == this.apos_css["label-top"]);
		
		this.objPull[this.idPrefix+id] = this.items[type].render(tr, data);
		this.itemPull[this.idPrefix+id] = tr;
		
	}
	
	/*********************************************************************************************************************************************
		OBJECT INIT
	*********************************************************************************************************************************************/
	
	this._initObj = function(data, url) {
		
		if (typeof(data.data) != "undefined") {
			// data loading
			var id = null;
			if (typeof(url) != "undefined") {
				id = url.match(/(\?|\&)id\=([a-z0-9_\-]*)/i);
				if (id != null && id[0] != null) id = id[0].split("=")[1];
			}
			if (this.callEvent("onBeforeDataLoad", [id, window.dhx4._copyObj(data.data)]) === true) {
				this.formId = id;
				this._last_load_data = data;
				this.setFormData(data.data);
				this.resetDataProcessor("updated");
			}
			
			return;
		}
		
		// struct
		this._prepare();
		
		// search form settings
		for (var q=0; q<data.length; q++) {
			// add check for incorrect values:
			// position - allow only predefined, this.apos_css
			// labelAlign - allow only predefined, this.align_css
			// input/label top/left/width/height - numeric or auto
			if (typeof(data[q]) != "undefined" && data[q].type == "settings") for (var a in data[q]) this.idef[a] = data[q][a];
		}
		
		for (var q=0; q<data.length; q++) this._prepareItem(data[q]);
		
		this._autoCheck();
	}
	
	this._prepareItem = function(data, pos, insertAfter) {
		
		var type = (data!=null && data.type!=null ? data.type : "");
		
		if (this.items[type]) {
			
			if (!data.name) data.name = this._genStr(12);
			var id = data.name;
			if (this.objPull[this.idPrefix+id] != null || type=="radio") id = this._genStr(12);
			
			var obj = data;
			obj.label = obj.label||"";
			//obj.value = obj.value||"";
			obj.value = obj.value;
			obj.checked = window.dhx4.s2b(obj.checked);
			obj.disabled = window.dhx4.s2b(obj.disabled);
			obj.name = obj.name||this._genStr(12);
			obj.options = obj.options||[];
			obj.rows = obj.rows||"none";
			obj.uid = this._genStr(12);
			
			this._addItem(type, id, obj, null, null, pos, insertAfter);
			pos = null;
			
			if (this._parentEnabled === false) this._disableItem(id);
			
			for (var w=0; w<obj.options.length; w++) {
				if (obj.options[w].list != null) {
					if (!obj.options[w].value) obj.options[w].value = this._genStr();
					var subList = this._addItem("list", id, obj.options[w].list, obj.options[w].value, null);
					subList._subSelect = true;
					subList._subSelectId = obj.options[w].value;
				}
			}
			
			
			if (data.list != null) {
				if (!data.listParent) data.listParent = obj.name;//data[q].name;
				var subList = this._addItem("list", id, data.list, null, data.listParent);
			}
		}
	}
	
	/*********************************************************************************************************************************************
		XML
	*********************************************************************************************************************************************/
	
	this._xmlSubItems = {item: "list", option: "options", note: "note", userdata: "_userdata"};
	
	this._xmlToObject = function(xml, rootLevel) {
		
		if (typeof(rootLevel) == "undefined") rootLevel = true;
		
		if (rootLevel) {
			
			// try struct
			var xmlStruct = xml.getElementsByTagName("items");
			xmlStruct = (xmlStruct != null && xmlStruct[0] != null ? xmlStruct[0] : null);
			// try data
			var xmlData = xml.getElementsByTagName("data");
			xmlData = (xmlData != null && xmlData[0] != null ? xmlData[0] : null);
			
		} else {
			xmlStruct = xml;
		}
		
		var data = (rootLevel?[]:{});
		
		if (xmlStruct != null) {
			
			for (var q=0; q<xmlStruct.childNodes.length; q++) {
				
				if (typeof(xmlStruct.childNodes[q].tagName) != "undefined") {
					
					var tg = xmlStruct.childNodes[q].tagName;
					
					if (this._xmlSubItems[tg] != null) {
						
						var node = this._xmlSubItems[tg];
						if (typeof(data[node]) == "undefined") data[node] = [];
						
						var xn = xmlStruct.childNodes[q];
						
						// parse attributes
						var k = {};
						for (var w=0; w<xn.attributes.length; w++) {
							var attrName = xn.attributes[w].name;
							var attrValue = xn.attributes[w].value;
							k[attrName] = attrValue;
						}
						
						// parse custom data
						if (node == "note") k.text = xn.firstChild.nodeValue;
						
						// pasrse userdata value
						if (node == "_userdata") k.value = (xn.firstChild != null && xn.firstChild.nodeValue != null ? xn.firstChild.nodeValue : "");
						
						// parse nested items, merge with current
						var data2 = this._xmlToObject(xn, false);
						for (var a in data2) {
							if (a == "_userdata") {
								if (!k.userdata) k.userdata = {};
								for (var w=0; w<data2[a].length; w++) k.userdata[data2[a][w].name] = data2[a][w].value;
							} else {
								k[a] = data2[a];
							}
						}
						xn = null;
						if (rootLevel) data.push(k); else data[node].push(k);
						
					}
				}
			}
		}
		
		if (xmlData != null) {
			data = {data:{}};
			for (var q=0; q<xmlData.childNodes.length; q++) {
				if (typeof(xmlData.childNodes[q].tagName) != "undefined") {
					var name = xmlData.childNodes[q].tagName;
					var value = (xmlData.childNodes[q].firstChild!=null?xmlData.childNodes[q].firstChild.nodeValue:"");
					data.data[name] = value;
				}
			}
		}
		
		return data;
		
	}
	
	/*********************************************************************************************************************************************
		AUTOCHECK (Global enable/disable functionality)
	*********************************************************************************************************************************************/
	
	this._autoCheck = function(enabled) {
		if (this._locked === true) {
			enabled = false;
		} else {
			if (typeof(enabled) == "undefined") enabled = true;
		}
		for (var a in this.itemPull) {
			var isEnabled = (enabled&&(this.itemPull[a]._udis!==true));
			this[isEnabled?"_enableItem":"_disableItem"](this.itemPull[a]._idd);
			
			// id-index state
			if (this.getForm()._idIndex[this.itemPull[a]._idd] != null) {
				this.getForm()._idIndex[this.itemPull[a]._idd].enabled = isEnabled;
			}
			
			// nested forms
			var pEnabled = (isEnabled&&(typeof(this.itemPull[a]._checked)=="boolean"?this.itemPull[a]._checked:true));
			if (this.itemPull[a]._list) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) {
					var f = true;
					if (this.itemPull[a]._list[q]._subSelect == true) {
						f = false
						var v = this.getItemValue(this.itemPull[a]._idd);
						if (!(typeof(v) == "object" && typeof(v.length) == "number")) v = [v];
						for (var w=0; w<v.length; w++) f = (v[w]==this.itemPull[a]._list[q]._subSelectId)||f;
						this.itemPull[a]._listObj[q][f?"show":"hide"](this.itemPull[a]._listBase[q]);
					}
					this.itemPull[a]._list[q]._autoCheck(pEnabled&&f);
				}
			}
		}
	}
	
	/*********************************************************************************************************************************************
		PUBLIC API
	*********************************************************************************************************************************************/
	
	this.doWithItem = function(id, method, a, b, c, d) {
		// radio
		//console.log(method)
		
		if (typeof(id) == "object") {
			var group = id[0];
			var value = id[1];
			var item = null;
			var res = null;
			for (var k in this.itemPull) {
				if ((this.itemPull[k]._value == value || value === null) && this.itemPull[k]._group == group) return this.objPull[k][method](this.itemPull[k], a, b, c, d);
				if (this.itemPull[k]._list != null && !res) {
					for (var q=0; q<this.itemPull[k]._list.length; q++) {
						res = this.itemPull[k]._list[q].doWithItem(id, method, a, b, c);
					}
				}
			}
			if (res != null) {
				return res;
			} else {
				if (method == "getType") return this.doWithItem(id[0], "getType");
			}
		// checkbox, input, select, label
		} else {
			if (!this.itemPull[this.idPrefix+id]) {
				var res = null;
				for (var k in this.itemPull) {
					if (this.itemPull[k]._list && !res) {
						for (var q=0; q<this.itemPull[k]._list.length; q++) {
							if (res == null) res = this.itemPull[k]._list[q].doWithItem(id, method, a, b, c, d);
						}
					}
				}
				return res;
			} else {
				return this.objPull[this.idPrefix+id][method](this.itemPull[this.idPrefix+id], a, b, c, d);
			}
		}
	}
	
	this._removeItem = function(id, value) {
		if (value != null) id = this.doWithItem([id, value], "destruct"); else this.doWithItem(id, "destruct");
		this._clearItemData(id);
	}
	
	this._clearItemData = function(id) {
		if (this.itemPull[this.idPrefix+id]) {
			id = this.idPrefix+id;
			try {
				this.objPull[id] = null;
				this.itemPull[id] = null;
				delete this.objPull[id];
				delete this.itemPull[id];
			} catch(e) {}
		} else {
			for (var k in this.itemPull) {
				if (this.itemPull[k]._list) {
					for (var q=0; q<this.itemPull[k]._list.length; q++) this.itemPull[k]._list[q]._clearItemData(id);
				}
			}
		}
	}
	
	this.isItem = function(id, value) {
		if (value != null) id = [id, value];
		return this.doWithItem(id, "isExist");
	}
	
	this.getItemType = function(id, value) {
		id = [id, (value||null)];
		return this.doWithItem(id, "getType");
	}

	/* iterator */
	this.forEachItem = function(handler) {
		for (var a in this.objPull) {
			if (this.objPull[a].t == "radio") {
				handler(this.itemPull[a]._group, this.itemPull[a]._value);
			} else {
				handler(String(a).replace(this.idPrefix,""));
			}
			if (this.itemPull[a]._list) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) this.itemPull[a]._list[q].forEachItem(handler);
			}
		}
	}
	
	/* text */
	this.setItemLabel = function(id, value, text) {
		if (text != null) id = [id, value]; else text = value;
		this.doWithItem(id, "setText", text);
	}
	
	this.getItemLabel = function(id, value) {
		if (value != null) id = [id, value];
		return this.doWithItem(id, "getText");
	}
	
	/* state */
	this._enableItem = function(id) {
		this.doWithItem(id, "enable");
	}
	
	this._disableItem = function(id) {
		this.doWithItem(id, "disable");
	}
	
	this._isItemEnabled = function(id) {
		return this.doWithItem(id, "isEnabled");
	}
	
	/* selection */
	this.checkItem = function(id, value) {
		if (value != null) id = [id, value];
		this.doWithItem(id, "check");
		this._autoCheck();
	}
	
	this.uncheckItem = function(id, value) {
		if (value != null) id = [id, value];
		this.doWithItem(id, "unCheck");
		this._autoCheck();
	}
	
	this.isItemChecked = function(id, value) {
		if (value != null) id = [id, value];
		return this.doWithItem(id, "isChecked");
	}
	
	this.getCheckedValue = function(id) {
		return this.doWithItem([id, null], "getChecked");
	}
	
	/* value */
	
	// get radio group by id
	this._getRGroup = function(id, val) {
		for (var a in this.itemPull) {
			if (this.itemPull[a]._group == id && (val == null || this.itemPull[a]._value == val)) return this.itemPull[a]._idd;
			if (this.itemPull[a]._list != null) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) {
					var r = this.itemPull[a]._list[q]._getRGroup(id, val);
					if (r != null) return r;
				}
			}
		}
		return null;
	}
	
	this.setItemValue = function(id, value) {
		this.resetValidateCss(id, value);
		if (this.getItemType(id) == "radio") {
			if (this._getRGroup(id, value) != null) this.checkItem(id, value); else this.uncheckItem(id, this.getCheckedValue(id));
			return null;
		}
		return this.doWithItem(id, "setValue", value);
	}
	
	this.getItemValue = function(id, param) {
		if (this.getItemType(id) == "radio") return this.getCheckedValue(id);
		return this.doWithItem(id, "getValue", param);
	}
	
	this.updateValues = function() {
		this._updateValues();
	}
	
	/* visibility */
	this.showItem = function(id, value) {
		if (value != null) id = [id,value];
		this.doWithItem(id, "show");
	}
	
	this.hideItem = function(id, value) {
		if (value != null) id = [id,value];
		this.doWithItem(id, "hide");
	}
	
	this.isItemHidden = function(id, value) {
		if (value != null) id = [id,value];
		return this.doWithItem(id, "isHidden");
	}
	
	/* options (select only) */
	this.getOptions = function(id) {
		return this.doWithItem(id, "getOptions");
	}
	
	/* width/height */
	this.setItemWidth = function(id, width) {
		this.doWithItem(id, "setWidth", width);
	}
	
	this.getItemWidth = function(id) {
		return this.doWithItem(id, "getWidth");
	}
	
	this.setItemHeight = function(id, height) { // textarea
		this.doWithItem(id, "setHeight", height);
	}
	
	this.setItemFocus = function(id, value) {
		if (value != null) id = [id,value];
		this.doWithItem(id, "setFocus");
	}
	
	/* validation */
	
	// required before validate and data sending for updating values for input, password
	// datasending call validation inside
	this._updateValues = function() {
		for (var a in this.itemPull) {
			if (this.objPull[a] && typeof(this.objPull[a].updateValue) == "function") {
				this.objPull[a].updateValue(this.itemPull[a]);
			}
			if (this.itemPull[a]._list) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) {
					this.itemPull[a]._list[q]._updateValues();
				}
			}
		}
	}
	
	// css
	this._getItemByName = function(id) {
		for (var a in this.itemPull) {
			if (this.itemPull[a]._idd == id) return this.itemPull[a];
			if (this.itemPull[a]._list != null) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) {
					var r = this.itemPull[a]._list[q]._getItemByName(id);
					if (r != null) return r;
				}
			}
		}
		return null;
	}
	this._resetValidateCss = function(item) {
		item.className = (item.className).replace(item._vcss,"");
		item._vcss = null;
	}
	this.setValidateCss = function(name, state, custom) {
		var item = this._getItemByName(name);
		if (!item) return;
		if (item._vcss != null) this._resetValidateCss(item);
		item._vcss = (typeof(custom)=="string"?custom:"validate_"+(state===true?"ok":"error"));
		item.className += " "+item._vcss;
	}
	this.resetValidateCss = function(name) {
		for (var a in this.itemPull) {
			if (this.itemPull[a]._vcss != null) this._resetValidateCss(this.itemPull[a]);
			if (this.itemPull[a]._list != null) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) this.itemPull[a]._list[q].resetValidateCss();
			}
		}
	}
	this._validateLoop = function(handler) { // same as forEach only omit radio button value
		for (var a in this.objPull) {
			handler(String(a).replace(this.idPrefix,""));
			if (this.itemPull[a]._list) {
				for (var q=0; q<this.itemPull[a]._list.length; q++) this.itemPull[a]._list[q]._validateLoop(handler);
			}
		}
	}
	// action
	this.validate = function(type) {
		
		if (this.callEvent("onBeforeValidate",[]) == false) return;
		
		var completed = true;
		
		this._validateLoop(function(name, value){
			var k = that.doWithItem(name, "_validate");
			if (typeof(k) != "boolean") k = true;
			completed = k && completed;
		}, true);
		
		this.callEvent("onAfterValidate",[completed]);
		return completed;
		
	}
	
	this.validateItem = function(name, value) {
		if (typeof(value) != "undefined") name = [name,value];
		return this.doWithItem(name,"_validate");
	}
	
	this.enableLiveValidation = function(state) {
		this.live_validate = (state==true);
	}
	
	
	/* readonly */
	
	this.setReadonly = function(id, state) {
		this.doWithItem(id, "setReadonly", state);
	}
	
	this.isReadonly = function(id) {
		return this.doWithItem(id, "isReadonly");
	}
	
	/* index */
	
	this.getFirstActive = function(withFocus) {
		for (var q=0; q<this._indexId.length; q++) {
			var k = true;
			if (withFocus == true) {
				var t = this.getItemType(this._indexId[q]);
				if (!dhtmlXForm.prototype.items[t].setFocus) k = false;
			}
			if (k && this._idIndex[this._indexId[q]].enabled) return this._indexId[q];
		}
		return null;
	}
	
	this.setFocusOnFirstActive = function() {
		var k = this.getFirstActive(true);
		if (k != null) this.setItemFocus(k);
	}
	
	/* enable/disable */
	
	this.enableItem = function(id, value) {
		if (value != null) id = [id,value];
		this.doWithItem(id, "userEnable");
		this._autoCheck();
	}
	
	this.disableItem = function(id, value) {
		if (value != null) id = [id,value];
		this.doWithItem(id, "userDisable");
		this._autoCheck();
	}
	
	this.isItemEnabled = function(id, value) {
		if (value != null) id = [id,value];
		return this.doWithItem(id, "isUserEnabled");
	}
	
	this.clear = function() {
		var usedRAs = {};
		this.formId = (new Date()).valueOf();//remove form id, so next operation will be insert
		this.resetDataProcessor("inserted");
		
		for (var a in this.itemPull) {
			var t = this.itemPull[a]._idd;
			// checkbox
			if (this.itemPull[a]._type == "ch") this.uncheckItem(t);
			// input/textarea
			if (this.itemPull[a]._type in {"ta":1,"editor":1,"calendar":1,"pw":1,"hd":1})
				this.setItemValue(t, "");
			// dhxcombo
			if (this.itemPull[a]._type == "combo") {
				this.itemPull[a]._apiChange = true;
				var combo = this.getCombo(t);
				combo.selectOption(0);
				combo = null;
				this.itemPull[a]._apiChange = false;
			}
			// select
			if (this.itemPull[a]._type == "se") {
				var opts = this.getOptions(t);
				if (opts.length > 0) opts[0].selected = true;
			}
			// radiobutton
			if (this.itemPull[a]._type == "ra") {
				var g = this.itemPull[a]._group;
				if (!usedRAs[g]) { this.checkItem(g, this.doWithItem(t, "_getFirstValue")); usedRAs[g] = true; }
			}
			// nested lists
			if (this.itemPull[a]._list) for (var q=0; q<this.itemPull[a]._list.length; q++) this.itemPull[a]._list[q].clear();
			// check for custom cell
			if (this["setFormData_"+this.itemPull[a]._type]) {
				this["setFormData_"+this.itemPull[a]._type](t,"");
			}
		}
		usedRAs = null;
		if (this._parentForm) this._autoCheck();
		
		// validate
		this.resetValidateCss();
		
	}
	
	this.unload = function() {
		
		window.dhx4._enableDataLoading(this, null, null, null, "clear");
		window.dhx4._eventable(this, "clear");
		
		for (var a in this.objPull) this._removeItem(String(a).replace(this.idPrefix,""));
		
		if (this._ccTm) window.clearTimeout(this._ccTm);
		this._formLS = null;
		
		for (var q=0; q<this.base.length; q++) {
			while (this.base[q].childNodes.length > 0) this.base[q].removeChild(this.base[q].childNodes[0]);
			if (this.base[q].parentNode) this.base[q].parentNode.removeChild(this.base[q]);
			this.base[q] = null;
		}
		this.base = null;
		
		this.cont.onkeypress = null;
		this.cont.className = "";
		this.cont = null;
		
		for (var a in this) this[a] = null;
		
		that = null;
		
	}
	
	for (var a in this.items) {
		
		this.items[a].t = a;
		
		if (typeof(this.items[a]._index) == "undefined") {
			this.items[a]._index = true;
		}
		
		if (!this.items[a].show) {
			this.items[a].show = function(item) {
				item.style.display = "";
				if (item._listObj) for (var q=0; q<item._listObj.length; q++) item._listObj[q].show(item._listBase[q]);
			}
		}
		
		if (!this.items[a].hide) {
			this.items[a].hide = function(item) {
				item.style.display = "none";
				if (item._listObj) for (var q=0; q<item._listObj.length; q++) item._listObj[q].hide(item._listBase[q]);
			}
		}
		
		if (!this.items[a].isHidden) {
			this.items[a].isHidden = function(item) {
				return (item.style.display == "none");
			}
		}
		
		if (!this.items[a].userEnable) {
			this.items[a].userEnable = function(item) {
				item._udis = false;
			}
		}
			
		if (!this.items[a].userDisable) {
			this.items[a].userDisable = function(item) {
				item._udis = true;
			}
		}
		
		if (!this.items[a].isUserEnabled) {
			this.items[a].isUserEnabled = function(item) {
				return (item._udis!==true);
			}
		}
		
		if (!this.items[a].getType) {
			this.items[a].getType = function() {
				return this.t;
			}
		}
		
		if (!this.items[a].isExist) {
			this.items[a].isExist = function() {
				return true;
			}
		}
		
		if (!this.items[a]._validate) {
			this.items[a]._validate = function(item) {
				
				if (!item._validate || !item._enabled) return true;
				
				if (item._type == "ch" || item._type == "ra") {
					var val = (this.isChecked(item)?this.getValue(item):0);
					if (item._type == "ra" && typeof(val) == "undefined") val = 0;
				} else {
					var val = this.getValue(item);
				}
				
				var r = true;
				
				for (var q=0; q<item._validate.length; q++) {
					
					var v = "is"+item._validate[q];
					
					if ((val == null || val.length == 0) && v != "isNotEmpty" && item._type != "container") {
						// field not required or empty (+ validate not set to NotEmpty)
					} else {
						var f = dhtmlxValidation[v];
						
						if (item._type == "container" && typeof(f) == "function") f = function(){return true;}
						
						if (typeof(f) != "function" && typeof(item._validate[q]) == "function") f = item._validate[q];
						if (typeof(f) != "function" && typeof(window[item._validate[q]]) == "function") f = window[item._validate[q]];
						r = ((typeof(f)=="function"?f(val,item._idd):new RegExp(item._validate[q]).test(val)) && r);
						f = null;
					}
				}
				
				if (!(item.callEvent("onValidate"+(r?"Success":"Error"),[item._idd,val,r])===false)) item.getForm().setValidateCss(item._idd, r);
				
				return r;
			}
		}
		
		
	}
	
	// lock/unlock form
	this._locked = false;
	this._doLock = function(state) {
		var t = (state===true?true:false);
		if (this._locked == t) return; else this._locked = t;
		this._autoCheck(!this._locked);
	}
	this.lock = function() {
		this._doLock(true);
	}
	this.unlock = function() {
		this._doLock(false);
	}
	this.isLocked = function() {
		return this._locked;
	}
	
	// date format for inputs
	this.setNumberFormat = function(id, format, g_sep, d_sep) {
		// return false if format incorrect and true if it successfuly applied
		return this.doWithItem(id, "setNumberFormat", format, g_sep, d_sep);
	}
	
	window.dhx4._enableDataLoading(this, "_initObj", "_xmlToObject", "items", {struct: true, data: true});
	window.dhx4._eventable(this);
	
	this.attachEvent("_onButtonClick", function(name, cmd){
		this.callEvent("onButtonClick", [name, cmd]);
	});
	
	this._updateBlocks = function() {
		this.forEachItem(function(id){
			if (that.getItemType(id) == "block" || that.getItemType(id) == "combo") {
				that.doWithItem(id,"_setCss",that.skin,that.cont.style.fontSize);
			}
		});
	}
	
	// copy init data to prevent init obj extension
	this._isObj = function(k) {
		return (k != null && typeof(k) == "object" && typeof(k.length) == "undefined");
	}
	this._copyObj = function(r) {
		if (this._isObj(r)) {
			var t = {};
			for (var a in r) {
				if (typeof(r[a]) == "object" && r[a] != null) t[a] = this._copyObj(r[a]); else t[a] = r[a];
			}
		} else {
			var t = [];
			for (var a=0; a<r.length; a++) {
				if (typeof(r[a]) == "object" && r[a] != null) t[a] = this._copyObj(r[a]); else t[a] = r[a];
			}
		}
		return t;
	}
	//
	
	if (data != null && typeof(data) == "object") {
		this._initObj(this._copyObj(data));
	};
	
	if (this._parentForm) {
		this._updateBlocks();
	}
	
	// ls for input change, affected: input, select, pwd, calendar, colorpicker
	this._ccActive = false;
	this._ccTm = null;
	
	return this;
	
};

dhtmlXForm.prototype.getInput = function(id) {
	return this.doWithItem(id, "getInput");
};

dhtmlXForm.prototype.getSelect = function(id) {
	return this.doWithItem(id, "getSelect");
};


dhtmlXForm.prototype.items = {};

/* checkbox */
dhtmlXForm.prototype.items.checkbox = {
	
	render: function(item, data) {
		
		item._type = "ch";
		item._enabled = true;
		item._checked = false;
		item._value = (typeof(data.value)=="undefined"?null:String(data.value));
		item._ro = (data.readonly==true);
		
		if (data._autoInputWidth !== false) data.inputWidth = 14;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "INPUT", "TEXT", true, true, "dhxform_textarea");
		
		item.childNodes[item._ll?1:0].className += " dhxform_img_node";
		
		var p = document.createElement("DIV");
		p.className = "dhxform_img chbx0";
		item.appendChild(p);
		
		if (!isNaN(data.inputLeft)) item.childNodes[item._ll?1:0].style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) item.childNodes[item._ll?1:0].style.top = parseInt(data.inputTop)+"px";
		
		item.childNodes[item._ll?1:0].appendChild(p);
		item.childNodes[item._ll?1:0].firstChild.value = String(data.value);
		
		item._updateImgNode = function(item, state) {
			var t = item.childNodes[item._ll?1:0].lastChild;
			t.className = (state?"dhxform_actv_c":"dhxform_img")+" "+(item._checked?"chbx1":"chbx0");
			item = t = null;
		}
		
		item._doOnFocus = function(item) {
			item.getForm().callEvent("onFocus",[item._idd]);
		}
		
		item._doOnBlur = function(item) {
			item.getForm().callEvent("onBlur",[item._idd]);
		}
		
		item._doOnKeyUpDown = function(evName, evObj) {
			this.callEvent(evName, [this.childNodes[this._ll?0:1].childNodes[0], evObj, this._idd]);
		}
		
		if (data.checked == true) this.check(item);
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		this.doAttachEvents(item);
		
		return this;
	},
	
	destruct: function(item) {
		item._doOnFocus = item._doOnBlur = item._updateImgNode = null;
		this.doUnloadNestedLists(item);
		this.doDestruct(item);
	},
	
	doAddLabel: function(item, data) {
		
		var t = document.createElement("DIV");
		t.className = "dhxform_label "+data.labelAlign;
		
		if (data.wrap == true) t.style.whiteSpace = "normal";
		
		if (item._ll) {
			item.insertBefore(t,item.firstChild);
		} else {
			item.appendChild(t);
		}
		
		if (typeof(data.tooltip) != "undefined") t.title = data.tooltip;
		
		t.innerHTML = "<div class='dhxform_label_nav_link' "+
				"onfocus='if(this.parentNode.parentNode._updateImgNode)this.parentNode.parentNode._updateImgNode(this.parentNode.parentNode,true);this.parentNode.parentNode._doOnFocus(this.parentNode.parentNode);' "+
				"onblur='if(this.parentNode.parentNode._updateImgNode)this.parentNode.parentNode._updateImgNode(this.parentNode.parentNode,false);this.parentNode.parentNode._doOnBlur(this.parentNode.parentNode);' "+
				"onkeypress='var e=event||window.arguments[0];if(e.keyCode==32||e.charCode==32){e.cancelBubble=true;if(e.preventDefault)e.preventDefault();else e.returnValue=false;_dhxForm_doClick(this,\"mousedown\");return false;}' "+
				"onkeyup='var e=event||window.arguments[0];this.parentNode.parentNode._doOnKeyUpDown(\"onKeyUp\",e);' "+
				"onkeydown='var e=event||window.arguments[0];this.parentNode.parentNode._doOnKeyUpDown(\"onKeyDown\",e);' "+
				(window.dhx4.isIPad?"ontouchstart='var e=event;e.preventDefault();_dhxForm_doClick(this,\"mousedown\");' ":"")+
				"role='link' tabindex='0'>"+data.label+(data.info?"<span class='dhxform_info'>[?]</span>":"")+(item._required?"<span class='dhxform_item_required'>*</span>":"")+'</div>';
		
		if (!isNaN(data.labelWidth)) t.firstChild.style.width = parseInt(data.labelWidth)+"px";
		if (!isNaN(data.labelHeight)) t.firstChild.style.height = parseInt(data.labelHeight)+"px";
		
		if (!isNaN(data.labelLeft)) t.style.left = parseInt(data.labelLeft)+"px";
		if (!isNaN(data.labelTop)) t.style.top = parseInt(data.labelTop)+"px";
		
	},
	
	doAddInput: function(item, data, el, type, pos, dim, css) {
		
		var p = document.createElement("DIV");
		p.className = "dhxform_control";
		
		if (item._ll) {
			item.appendChild(p);
		} else {
			item.insertBefore(p,item.firstChild);
		}
		
		var t = document.createElement(el);
		t.className = css;
		t.name = item._idd;
		t._idd = item._idd;
		t.id = data.uid;
		
		if (typeof(type) == "string") t.type = type;
		
		if (el == "INPUT" || el == "TEXTAREA") {
			t.onkeyup = function(e) {
				e = e||event;
				item.callEvent("onKeyUp",[this,e,this._idd]);
			};
			t.onkeydown = function(e) {
				e = e||event;
				item.callEvent("onKeyDown",[this,e,this._idd]);
			};
		}
		
		if (el == "SELECT" && data.type == "select" && item.getForm().skin == "material") {
			if (window.dhx4.isOpera || window.dhx4.isChrome) {
				t.className += " dhxform_arrow_fix_webkit";
			} else if (window.dhx4.isEdge) {
				t.className += " dhxform_arrow_fix_edge";
			} else if (window.dhx4.isFF) {
				t.className += " dhxform_fix_ff";
			}
		}
		
		p.appendChild(t);
		
		if (pos) {
			if (!isNaN(data.inputLeft)) p.style.left = parseInt(data.inputLeft)+"px";
			if (!isNaN(data.inputTop)) p.style.top = parseInt(data.inputTop)+"px";
		}
		
		var u = "";
		
		var dimFix = false;
		if (dim) {
			if (!isNaN(data.inputWidth)) { u += "width:"+parseInt(data.inputWidth)+"px;"; dimFix=true; }
			if (!isNaN(data.inputHeight)) u += "height:"+parseInt(data.inputHeight)+"px;";
		}
		if (typeof(data.style) == "string") u += data.style;
		t.style.cssText = u;
		
		if (data.maxLength) t.setAttribute("maxLength", data.maxLength);
		if (data.connector) t.setAttribute("connector",data.connector);
		
		var i = (dhtmlXForm.prototype.items[this.t] != null ? dhtmlXForm.prototype.items[this.t]._dimFix == true : false);
		if (dimFix && ({input: 1, password:1, select:1, multiselect:1, calendar:1, colorpicker:1}[this.t] == 1 || i)) {
			if (dhtmlXForm.prototype.items[this.t]._dim == null) dhtmlXForm.prototype.items[this.t]._dim = item.getForm()._checkDim(p, t);
			t.style.width = parseInt(t.style.width)-dhtmlXForm.prototype.items[this.t]._dim+"px";
		}
		
		if (typeof(data.note) == "object") {
			var note = document.createElement("DIV");
			note.className = "dhxform_note";
			note.style.width = (isNaN(data.note.width)?t.offsetWidth:parseInt(data.note.width))+"px";
			note._w = data.note.width;
			note.innerHTML = data.note.text;
			p.appendChild(note);
			note = null;
		}
		
		if (data.readonly) this.setReadonly(item, true);
		if (data.disabled == true) this.userDisable(item);
		if (data.hidden == true && this.t != "combo") this.hide(item);
		
	},
	
	doUnloadNestedLists: function(item) {
		
		if (!item._list) return;
		for (var q=0; q<item._list.length; q++) {
			item._list[q].unload();
			item._list[q] = null;
			item._listObj[q] = null;
			item._listBase[q].parentNode.removeChild(item._listBase[q]);
			item._listBase[q] = null;
		}
		item._list = null;
		item._listObj = null;
		item._listBase = null;
	},
	
	doDestruct: function(item) {
		
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		item._autoCheck = null;
		item._checked = null;
		item._enabled = null;
		item._idd = null;
		item._type = null;
		item._value = null;
		item._group = null;
		
		item.onselectstart = null;
		
		item.childNodes[item._ll?1:0].onmousedown = null;
		item.childNodes[item._ll?1:0].ontouchstart = null;
		
		item.childNodes[item._ll?0:1].onmousedown = null;
		item.childNodes[item._ll?0:1].ontouchstart = null;
		
		item.childNodes[item._ll?0:1].childNodes[0].onfocus = null;
		item.childNodes[item._ll?0:1].childNodes[0].onblur = null;
		item.childNodes[item._ll?0:1].childNodes[0].onkeypress = null;
		item.childNodes[item._ll?0:1].childNodes[0].onkeyup = null;
		item.childNodes[item._ll?0:1].childNodes[0].onkeydown = null;
		item.childNodes[item._ll?0:1].childNodes[0].onmousedown = null;
		item.childNodes[item._ll?0:1].childNodes[0].ontouchstart = null;
		item.childNodes[item._ll?0:1].removeChild(item.childNodes[item._ll?0:1].childNodes[0]);
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item.parentNode.removeChild(item);
		item = null;
		
	},
	
	doAttachEvents: function(item) {
		var that = this;
		// image click
		item.childNodes[item._ll?1:0][window.dhx4.isIPad?"ontouchstart":"onmousedown"] = function(e) {
			e = e||event;
			if (e.preventDefault) e.preventDefault();
			var t = (e.target||e.srcElement); // need to skip "note" if exists
			if (!this.parentNode._enabled || this.parentNode._ro || (typeof(t.className) != "undefined" && t.className == "dhxform_note")) {
				e.cancelBubble = true;
				if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
				return false;
			}
			that.doClick(this.parentNode);
		}
		// label click
		item.childNodes[item._ll?0:1].childNodes[0][window.dhx4.isIPad?"ontouchstart":"onmousedown"] = function(e) {
			e = e||event;
			if (e.preventDefault) e.preventDefault();
			// do not check if r/o here, allow item's be highlighted, check for r/o added into doClick
			if (!this.parentNode.parentNode._enabled) {
				e.cancelBubble = true;
				if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
				return false;
			}
			// check if "info" clicked (checkbox/radio only)
			var t = e.target||e.srcElement;
			if (typeof(t.className) != "undefined" && t.className == "dhxform_info") {
				this.parentNode.parentNode.callEvent("onInfo",[this.parentNode.parentNode._idd, e]);
				e.cancelBubble = true;
				if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
				return false;
			}
			that.doClick(this.parentNode.parentNode);
		}
	},
	
	doClick: function(item) {
		
		item.childNodes[item._ll?0:1].childNodes[0].focus();
		
		if (!item._enabled || item._ro) return;
		
		if (item.checkEvent("onBeforeChange")) if (item.callEvent("onBeforeChange", [item._idd, item._value, item._checked]) !== true) return;
		
		this.setChecked(item, !item._checked);
		item._autoCheck();
		item.callEvent("onChange", [item._idd, item._value, item._checked]);
	},
	
	doCheckValue: function(item) {
		if (item._checked && item._enabled) {
			item.childNodes[item._ll?1:0].firstChild.name = String(item._idd);
			item.childNodes[item._ll?1:0].firstChild.value = this.getValue(item);
		} else {
			item.childNodes[item._ll?1:0].firstChild.name = "";
			item.childNodes[item._ll?1:0].firstChild.value = "";
		}
	},
	
	setChecked: function(item, state) {
		item._checked = (state===true?true:false);
		//item.childNodes[item._ll?1:0].lastChild.className = "dhxform_img "+(item._checked?"chbx1":"chbx0");
		item.childNodes[item._ll?1:0].lastChild.className = item.childNodes[item._ll?1:0].lastChild.className.replace(/chbx[0-1]{1}/gi,"")+(item._checked?" chbx1":" chbx0");
		this.doCheckValue(item);
	},
	
	check: function(item) {
		this.setChecked(item, true);
	},
	
	unCheck: function(item) {
		this.setChecked(item, false);
	},
	
	isChecked: function(item) {
		return item._checked;
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
		item.childNodes[item._ll?0:1].childNodes[0].tabIndex = 0;
		item.childNodes[item._ll?0:1].childNodes[0].removeAttribute("disabled");
		this.doCheckValue(item);
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
		if (item._updateImgNode != null) item._updateImgNode(item, false); // clear focus on disable fix
		item.childNodes[item._ll?0:1].childNodes[0].tabIndex = -1;
		item.childNodes[item._ll?0:1].childNodes[0].setAttribute("disabled", "true");
		this.doCheckValue(item);
	},
	
	isEnabled: function(item) {
		return item._enabled;
	},
	
	setText: function(item, text) {
		item.childNodes[item._ll?0:1].childNodes[0].innerHTML = text+(item._required?"<span class='dhxform_item_required'>*</span>":"");
	},
	
	getText: function(item) {
		return item.childNodes[item._ll?0:1].childNodes[0].innerHTML.replace(/<span class=\"dhxform_item_required\">[^<]*<\/span>/g,"");
	},
	
	setValue: function(item, value) {
		this.setChecked(item,(value===true||parseInt(value)==1||value=="true"||item._value===value));
	},
	
	getValue: function(item, mode) {
		if (mode == "realvalue") return item._value;
		return ((typeof(item._value)=="undefined"||item._value==null)?(item._checked?1:0):item._value);
	},
	
	setReadonly: function(item, state) {
		item._ro = (state===true);
	},
	
	isReadonly: function(item) {
		return item._ro;
	},
	
	setFocus: function(item) {
		item.childNodes[item._ll?0:1].childNodes[0].focus();
	}
	
};

/* radio */
dhtmlXForm.prototype.items.radio = {
	
	input: {},
	
	r: {},
	
	firstValue: {},
	
	render: function(item, data, uid) {
		
		item._type = "ra";
		item._enabled = true;
		item._checked = false;
		item._group = data.name;
		item._value = data.value;
		item._uid = uid;
		item._ro = (data.readonly==true);
		item._rName = item._rId+item._group;
		
		this.r[item._idd] = item;
		
		data.inputWidth = 14;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "INPUT", "TEXT", true, true, "dhxform_textarea");
		
		item.childNodes[item._ll?1:0].className += " dhxform_img_node";
		
		// radio img
		var p = document.createElement("DIV");
		p.className = "dhxform_img rdbt0";
		item.appendChild(p);
		
		if (!isNaN(data.inputLeft)) item.childNodes[item._ll?1:0].style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) item.childNodes[item._ll?1:0].style.top = parseInt(data.inputTop)+"px";
		
		item.childNodes[item._ll?1:0].appendChild(p);
		
		// hidden input needed just to keep common logic, name-value should be empty to prevent sending to server from real form
		item.childNodes[item._ll?1:0].firstChild.name = "";
		item.childNodes[item._ll?1:0].firstChild.value = "";
		
		item._updateImgNode = function(item, state) {
			var t = item.childNodes[item._ll?1:0].lastChild;
			t.className = (state?"dhxform_actv_r":"dhxform_img")+" "+(item._checked?"rdbt1":"rdbt0");
			item = t = null;
		}
		
		item._doOnFocus = function(item) {
			item.getForm().callEvent("onFocus",[item._group, item._value]);
		}
		
		item._doOnBlur = function(item) {
			item.getForm().callEvent("onBlur",[item._group, item._value]);
		}
		
		item._doOnKeyUpDown = function(evName, evObj) {
			this.callEvent(evName, [this.childNodes[this._ll?0:1].childNodes[0], evObj, this._group, this._value]);
		}
		
		// input
		if (this.input[item._rName] == null) {
			var k = document.createElement("INPUT");
			k.type = "HIDDEN";
			k.name = data.name;
			k.firstValue = item._value;
			item.appendChild(k);
			this.input[item._rName] = k;
		}
		
		if (!this.firstValue[item._rName]) this.firstValue[item._rName] = data.value;
		
		if (data.checked == true) this.check(item);
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		this.doAttachEvents(item);
		
		return this;
	},
	
	destruct: function(item, value) {
		
		// check if any items will left to keep hidden input on page
		
		if (item.lastChild == this.input[item._rName]) {
			var done = false;
			for (var a in this.r) {
				if (!done && this.r[a]._group == item._group && this.r[a]._idd != item._idd) {
					this.r[a].appendChild(this.input[item._rName]);
					done = true;
				}
			}
			if (!done) {
				// remove hidden input
				this.input[item._rName].parentNode.removeChild(this.input[item._rName]);
				this.input[item._rName] = null;
				this.firstValue[item._rName] = null;
			}
		}
		
		this.r[item._idd] = null;
		delete this.r[item._idd];
		
		item._doOnFocus = item._doOnBlur = item._updateImgNode = null;
		this.doUnloadNestedLists(item);
		this.doDestruct(item);
		
		var id = item._idd;
		item = null;
		
		return id;
		
	},
	
	doClick: function(item) {
		
		item.childNodes[item._ll?0:1].childNodes[0].focus();
		
		if (!(item._enabled && !item._checked)) return;
		if (item._ro) return;
		
		var args = [item._group, item._value, true];
		if (item.checkEvent("onBeforeChange")) if (item.callEvent("onBeforeChange", args) !== true) return;
		this.setChecked(item, true);
		item.getForm()._autoCheck();
		item.callEvent("onChange", args);
		
	},
	
	doCheckValue: function(item) {
		var value = null;
		for (var a in this.r) {
			if (this.r[a]._checked && this.r[a]._group == item._group && this.r[a]._rId == item._rId) value = this.r[a]._value; // allow getChecked for disabled, v3.6.2
		}
		if (value != null && this.r[a]._enabled) {
			this.input[item._rName].name = String(item._group);
			this.input[item._rName].value = value;
		} else {
			this.input[item._rName].name = "";
			this.input[item._rName].value = "";
		}
		this.input[item._rName]._value = value;
	},
	
	setChecked: function(item, state) {
		state = (state===true);
		for (var a in this.r) {
			if (this.r[a]._group == item._group && this.r[a]._rId == item._rId) {
				var needCheck = false;
				if (this.r[a]._idd == item._idd) {
					if (this.r[a]._checked != state) { this.r[a]._checked = state; needCheck = true; }
				} else {
					if (this.r[a]._checked) { this.r[a]._checked = false; needCheck = true; }
				}
				if (needCheck) {
					var t = this.r[a].childNodes[this.r[a]._ll?1:0].childNodes[1];
					t.className = t.className.replace(/rdbt[0-1]{1}/gi,"")+(this.r[a]._checked?" rdbt1":" rdbt0");
					t = null;
				}
			}
		}
		this.doCheckValue(item);
	},
	
	getChecked: function(item) {
		return this.input[item._rName]._value;
	},
	
	_getFirstValue: function(item) {
		return this.firstValue[item._rName];
	},
	
	_getId: function(item) {
		return item._idd; // return inner id by name/value
	},
	
	setValue: function(item, value) {
		// this method will never called at all
	}
	
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,doDestruct:1,doUnloadNestedLists:1,doAttachEvents:1,check:1,unCheck:1,isChecked:1,enable:1,disable:1,isEnabled:1,setText:1,getText:1,getValue:1,setReadonly:1,isReadonly:1,setFocus:1})
		dhtmlXForm.prototype.items.radio[a] = dhtmlXForm.prototype.items.checkbox[a];
})();


/* select */
dhtmlXForm.prototype.items.select = {
	
	render: function(item, data) {
		
		item._type = "se";
		item._enabled = true;
		item._value = null;
		item._newValue = null;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "SELECT", null, true, true, "dhxform_select");
		this.doAttachEvents(item);
		
		this.doLoadOpts(item, data);
		if (data.connector != null) this.doLoadOptsConnector(item, data.connector);
		
		if (typeof(data.value) != "undefined" && data.value != null) {
			this.setValue(item, data.value);
		}
		
		return this;
	},
	
	destruct: function(item) {
		
		this.doUnloadNestedLists(item);
		
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		item._autoCheck = null;
		item._enabled = null;
		item._idd = null;
		item._type = null;
		item._value = null;
		item._newValue = null;
		
		item.onselectstart = null;
		
		item.childNodes[item._ll?1:0].childNodes[0].onclick = null;
		item.childNodes[item._ll?1:0].childNodes[0].onkeydown = null;
		item.childNodes[item._ll?1:0].childNodes[0].onchange = null;
		item.childNodes[item._ll?1:0].childNodes[0].onfocus = null;
		item.childNodes[item._ll?1:0].childNodes[0].onblur = null;
		item.childNodes[item._ll?1:0].childNodes[0].onkeyup = null;
		item.childNodes[item._ll?1:0].removeChild(item.childNodes[item._ll?1:0].childNodes[0]);
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item.parentNode.removeChild(item);
		item = null;
		
	},
	
	doAddLabel: function(item, data) {
		
		var j = document.createElement("DIV");
		j.className = "dhxform_label "+data.labelAlign;
		j.innerHTML = "<label for='"+data.uid+"'>"+
				data.label+
				(data.info?"<span class='dhxform_info'>[?]</span>":"")+
				(item._required?"<span class='dhxform_item_required'>*</span>":"")+
				"</label>";
		//
		if (data.wrap == true) j.style.whiteSpace = "normal";
		
		if (typeof(data.tooltip) != "undefined") j.title = data.tooltip;
		
		item.appendChild(j);
		
		if (typeof(data.label) == "undefined" || data.label == null || data.label.length == 0) j.style.display = "none";
		
		if (!isNaN(data.labelWidth)) j.style.width = parseInt(data.labelWidth)+"px";
		if (!isNaN(data.labelHeight)) j.style.height = parseInt(data.labelHeight)+"px";
		
		if (!isNaN(data.labelLeft)) j.style.left = parseInt(data.labelLeft)+"px";
		if (!isNaN(data.labelTop)) j.style.top = parseInt(data.labelTop)+"px";
		
		if (data.info) {
			j.onclick = function(e) {
				e = e||event;
				var t = e.target||e.srcElement;
				if (typeof(t.className) != "undefined" && t.className == "dhxform_info") {
					this.parentNode.callEvent("onInfo",[this.parentNode._idd, e]);
					e.cancelBubble = true;
					if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
					return false;
				}
			}
		}
	},
	
	doAttachEvents: function(item) {
		
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		var that = this;
		
		t.onclick = function() {
			that.doOnChange(this);
		}
		t.onkeydown = function(e) {
			e = e||event;
			that.doOnChange(this);
			this.parentNode.parentNode.callEvent("onKeyDown",[this,e,this.parentNode.parentNode._idd]);
		}
		t.onchange = function() {
			that.doOnChange(this);
		}
		t.onkeyup = function(e) {
			e = e||event;
			this.parentNode.parentNode.callEvent("onKeyUp",[this,e,this.parentNode.parentNode._idd]);
		}
		t = null;
		
		this.doAttachChangeLS(item);
	},
	
	doAttachChangeLS: function(item) {
		
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		t.onfocus = function() {
			var i = this.parentNode.parentNode;
			i.getForm()._ccActivate(i._idd, this, i.getForm().getItemValue(i._idd,true));
			i.getForm().callEvent("onFocus",[i._idd]);
			i = null;
		}
		t.onblur = function() {
			var i = this.parentNode.parentNode;
			i.getForm()._ccDeactivate(i._idd);
			i.getForm().callEvent("onBlur",[i._idd]);
			i = null;
		}
		t = null;
	},
	
	doValidate: function(item) {
		if (item.getForm().live_validate) this._validate(item);
	},
	
	doLoadOpts: function(item, data, callEvent) {
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		var opts = data.options;
		var k = false;
		for (var q=0; q<opts.length; q++) {
			var t0 = opts[q].text||opts[q].label;
			if (!t0 || typeof(t0) == "undefined") t0 = "";
			var opt = new Option(t0, opts[q].value);
			if (typeof(opts[q].img_src) == "string") opt.setAttribute("img_src", opts[q].img_src);
			t.options.add(opt);
			// selected
			if (typeof(opts[q].selected) != "undefined" && window.dhx4.s2b(opts[q].selected) == true) {
				opt.selected = true;
				item._value = opts[q].value;
				k = true;
			}
			// cehcked (combo only)
			if (typeof(opts[q].checked) != "undefined" && window.dhx4.s2b(opts[q].checked) == true) {
				opt.setAttribute("checked", "1");
			}
			// images (combo only)
			if (typeof(opts[q].img) != "undefined") opt.setAttribute("img", opts[q].img);
			if (typeof(opts[q].img_dis) != "undefined") opt.setAttribute("img_dis", opts[q].img_dis);
			// cehcked (combo only)
			if (typeof(opts[q].css) != "undefined") opt.setAttribute("css", opts[q].css);
		}
		// if "selected" option was not specified, check selected in control
		if (!k && t.selectedIndex >= 0) item._value = t.options[t.selectedIndex].value;
		
		if (callEvent === true) item.callEvent("onOptionsLoaded", [item._idd]);
		// fix note if width set to auto
		this._checkNoteWidth(item);
	},
	
	doLoadOptsConnector: function(item, url) {
		
		var that = this;
		item._connector_working = true;
		
		window.dhx4.ajax.get(url, function(r) {
			
			r = r.xmlDoc.responseXML;
			if (r == null) return;
			
			var root = r.getElementsByTagName("data");
			if (root == null || root[0] == null) return;
			
			root = root[0];
			
			var opts = [];
			for (var q=0; q<root.childNodes.length; q++) {
				if (typeof(root.childNodes[q].tagName) != "undefined" && String(root.childNodes[q].tagName).toLowerCase() == "item") {
					var option = root.childNodes[q];
					opts.push({
						label: option.getAttribute("label"),
						value: option.getAttribute("value"),
						selected: (option.getAttribute("selected") != null)
					});
					option = null;
				}
			}
			
			that.doLoadOpts(item, {options:opts}, true);
			
			// try to set value if it was called while options loading was in progress
			
			item._connector_working = false;
			if (item._connector_value != null) {
				that.setValue(item, item._connector_value);
				item._connector_value = null;
			}
			
			that = item = null;
		});
	},
	
	doOnChange: function(sel) {
		var item = sel.parentNode.parentNode;
		item._newValue = (sel.selectedIndex>=0?sel.options[sel.selectedIndex].value:null);
		if (item._newValue != item._value) {
			if (item.checkEvent("onBeforeChange")) {
				if (item.callEvent("onBeforeChange", [item._idd, item._value, item._newValue]) !== true) {
					// restore last value
					for (var q=0; q<sel.options.length; q++) if (sel.options[q].value == item._value) sel.options[q].selected = true;
					return;
				}
			}
			item._value = item._newValue;
			item.callEvent("onChange", [item._idd, item._value]);
			if (item._type == "se" && item.getForm().live_validate) this._validate(item);
		}
		item._autoCheck();
	},
	
	setText: function(item, text) {
		if (!text) text = "";
		item.childNodes[item._ll?0:1].childNodes[0].innerHTML = text+(item._required?"<span class='dhxform_item_required'>*</span>":"");
		item.childNodes[item._ll?0:1].style.display = (text.length==0||text==null?"none":"");
	},
	
	getText: function(item) {
		return item.childNodes[item._ll?0:1].childNodes[0].innerHTML.replace(/<span class=\"dhxform_item_required\">[^<]*<\/span>/g,"");
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
		item.childNodes[item._ll?1:0].childNodes[0].removeAttribute("disabled");
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
		item.childNodes[item._ll?1:0].childNodes[0].setAttribute("disabled", true);
	},
	
	getOptions: function(item) {
		return item.childNodes[item._ll?1:0].childNodes[0].options;
	},
	
	setValue: function(item, val) {
		if (item._connector_working) { // attemp to set value while optins not yet loaded (connector used)
			item._connector_value = val;
			return;
		}
		var opts = this.getOptions(item);
		for (var q=0; q<opts.length; q++) {
			if (opts[q].value == val) {
				opts[q].selected = true;
				item._value = opts[q].value;
			}
		}
		if (item._list != null && item._list.length > 0) {
			item.getForm()._autoCheck();
		}
		
		item.getForm()._ccReload(item._idd, item._value); // selected option id
		
	},
	
	getValue: function(item) {
		var k = -1;
		var opts = this.getOptions(item);
		for (var q=0; q<opts.length; q++) if (opts[q].selected) k = opts[q].value;
		return k;
	},
	
	setWidth: function(item, width) {
		item.childNodes[item._ll?1:0].childNodes[0].style.width = width+"px";
	},
	
	getSelect: function(item) {
		return item.childNodes[item._ll?1:0].childNodes[0];
	},
	
	setFocus: function(item) {
		item.childNodes[item._ll?1:0].childNodes[0].focus();
	},
	
	_checkNoteWidth: function(item) {
		var t;
		if (item.childNodes[item._ll?1:0].childNodes[1] != null) {
			t = item.childNodes[item._ll?1:0].childNodes[1];
			if (t.className != null && t.className.search(/dhxform_note/gi) >= 0 && t._w == "auto") t.style.width = item.childNodes[item._ll?1:0].childNodes[0].offsetWidth+"px";
		}
		t = null;
	}
	
};
(function(){
	for (var a in {doAddInput:1,doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.select[a] = dhtmlXForm.prototype.items.checkbox[a];
})();

/* multiselect */
dhtmlXForm.prototype.items.multiselect = {
	
	doLoadOpts: function(item, data, callEvent) {
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		t.multiple = true;
		if (!isNaN(data.size)) t.size = Number(data.size);
		item._value = [];
		item._newValue = [];
		var opts = data.options;
		for (var q=0; q<opts.length; q++) {
			var opt = new Option(opts[q].text||opts[q].label, opts[q].value);
			t.options.add(opt);
			if (opts[q].selected == true || opts[q].selected == "true") {
				opt.selected = true;
				item._value.push(opts[q].value);
			}
		}
		if (callEvent === true) item.callEvent("onOptionsLoaded", [item._idd]);
		//
		this._checkNoteWidth(item);
	},
	
	doAttachEvents: function(item) {
		
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		var that = this;
		
		t.onfocus = function() {
			that.doOnChange(this);
			var i = this.parentNode.parentNode;
			i.getForm().callEvent("onFocus",[i._idd]);
			i = null;
		}
		
		t.onblur = function() {
			that.doOnChange(this);
			var i = this.parentNode.parentNode;
			i.getForm().callEvent("onBlur",[i._idd]);
			i = null;
		}
		
		t.onclick = function() {
			that.doOnChange(this);
			var i = this.parentNode.parentNode;
			i._autoCheck();
			i = null;
		}
		
	},
	
	doOnChange: function(sel) {
		
		var item = sel.parentNode.parentNode;
		
		item._newValue = [];
		for (var q=0; q<sel.options.length; q++) if (sel.options[q].selected) item._newValue.push(sel.options[q].value);
		
		if ((item._value).sort().toString() != (item._newValue).sort().toString()) {
			if (item.checkEvent("onBeforeChange")) {
				if (item.callEvent("onBeforeChange", [item._idd, item._value, item._newValue]) !== true) {
					// restore last value
					var k = {};
					for (var q=0; q<item._value.length; q++) k[item._value[q]] = true;
					for (var q=0; q<sel.options.length; q++) sel.options[q].selected = (k[sel.options[q].value] == true);
					k = null;
					return;
				}
			}
			item._value = [];
			for (var q=0; q<item._newValue.length; q++) item._value.push(item._newValue[q]);
			item.callEvent("onChange", [item._idd, item._value]);
		}
		
		// check autocheck for multiselect
		item._autoCheck();
		
	},
	
	setValue: function(item, val) {
		
		var k = {};
		if (typeof(val) == "string") val = val.split(",");
		if (typeof(val) != "object") val = [val];
		for (var q=0; q<val.length; q++) k[val[q]] = true;
		
		var opts = this.getOptions(item);
		for (var q=0; q<opts.length; q++) opts[q].selected = (k[opts[q].value] == true);
		
		item._autoCheck();
	},
	
	getValue: function(item) {
		
		var k = [];
		
		var opts = this.getOptions(item);
		for (var q=0; q<opts.length; q++) if (opts[q].selected) k.push(opts[q].value);
		return k;
	}
};

(function() {
	for (var a in dhtmlXForm.prototype.items.select) {
		if (!dhtmlXForm.prototype.items.multiselect[a]) dhtmlXForm.prototype.items.multiselect[a] = dhtmlXForm.prototype.items.select[a];
	}
})();

/* input */
dhtmlXForm.prototype.items.input = {
	
	render: function(item, data) {
		
		var ta = (!isNaN(data.rows));
		
		item._type = "ta";
		item._enabled = true;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, (ta?"TEXTAREA":"INPUT"), (ta?null:"TEXT"), true, true, "dhxform_textarea");
		this.doAttachEvents(item);
		
		if (ta) item.childNodes[item._ll?1:0].childNodes[0].rows = Number(data.rows)+(window.dhx4.isIE6?1:0);
		
		if (typeof(data.numberFormat) != "undefined") {
			var a,b=null,c=null;
			if (typeof(data.numberFormat) != "string") {
				a = data.numberFormat[0];
				b = data.numberFormat[1]||null;
				c = data.numberFormat[2]||null;
			} else {
				a = data.numberFormat;
				if (typeof(data.groupSep) == "string") b = data.groupSep;
				if (typeof(data.decSep) == "string") c = data.decSep;
			}
			this.setNumberFormat(item, a, b, c, false);
		}
		
		this.setValue(item, data.value);
		
		return this;
		
	},
	
	doAttachEvents: function(item) {
		
		var node = item.childNodes[item._ll?1:0].childNodes[0];
		
		if (typeof(node.tagName) != "undefined" && {"input":1, "textarea":1, "select":1}[node.tagName.toLowerCase()] == 1) {
			
			var that = this;
			node.onfocus = function() {
				var i = this.parentNode.parentNode;
				if (i._df != null) this.value = i._value||"";
				i.getForm()._ccActivate(i._idd, this, this.value);
				i.getForm().callEvent("onFocus",[i._idd]);
				i = null;
			}
			node.onblur = function() {
				var i = this.parentNode.parentNode;
				i.getForm()._ccDeactivate(i._idd);
				that.updateValue(i, true);
				if (i.getForm().live_validate) that._validate(i);
				i.getForm().callEvent("onBlur",[i._idd]);
				i = null;
			}
			
		}
		node = null;
		
	},
	
	updateValue: function(item, foc) {
		
		var value = item.childNodes[item._ll?1:0].childNodes[0].value;
		
		var form = item.getForm();
		var in_focus = (form._ccActive == true && form._formLS != null && form._formLS[item._idd] != null);
		form = null;
		
		if (!in_focus && item._df != null && value == window.dhx4.template._getFmtValue(item._value, item._df)) return; // if item not in focus
		
		if (!foc && item._df != null && item._value == value && value == window.dhx4.template._getFmtValue(value, item._df)) return;
		
		var t = this;
		if (item._value != value) {
			if (item.checkEvent("onBeforeChange")) if (item.callEvent("onBeforeChange",[item._idd, item._value, value]) !== true) {
				// restore
				if (item._df != null) t.setValue(item, item._value); else item.childNodes[item._ll?1:0].childNodes[0].value = item._value;
				return;
			}
			// accepted
			if (item._df != null && foc) t.setValue(item, value); else item._value = value;
			item.callEvent("onChange",[item._idd, value]);
			return;
		}
		if (item._df != null && foc) this.setValue(item, item._value);
	},
	
	setValue: function(item, value) {
		
		// str only
		item._value = (typeof(value) != "undefined" && value != null ? value : "");
		
		var v = (String(item._value)||"");
		var k = item.childNodes[item._ll?1:0].childNodes[0];
		
		// check if formatting available
		if (item._df != null) v = window.dhx4.template._getFmtValue(v, item._df);
		
		if (k.value != v) {
			k.value = v;
			item.getForm()._ccReload(item._idd, v);
		}
		
		k = null;
	},
	
	getValue: function(item) {
		// update value if item have focus
		var f = item.getForm();
		if (f._formLS && f._formLS[item._idd] != null) this.updateValue(item);
		f = null;
		// str only
		return (typeof(item._value) != "undefined" && item._value != null ? item._value : "");
	},
	
	setReadonly: function(item, state) {
		item._ro = (state===true);
		if (item._ro) {
			item.childNodes[item._ll?1:0].childNodes[0].setAttribute("readOnly", "true");
		} else {
			item.childNodes[item._ll?1:0].childNodes[0].removeAttribute("readOnly");
		}
	},
	
	isReadonly: function(item) {
		if (!item._ro) item._ro = false;
		return item._ro;
	},
	
	getInput: function(item) {
		return item.childNodes[item._ll?1:0].childNodes[0];
	},
	
	setNumberFormat: function(item, format, g_sep, d_sep, refresh) {
		
		if (typeof(refresh) != "boolean") refresh = true;
		
		if (format == "") {
			item._df = null;
			if (refresh) this.setValue(item, item._value);
			return true;
		}
		
		if (typeof(format) != "string") return;
		
		var fmt = window.dhx4.template._parseFmt(format, g_sep, d_sep);
		if (fmt == false) return false; else item._df = fmt;
		
		if (refresh) this.setValue(item, item._value);
		
		return true;
		
	}
	
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,destruct:1,doUnloadNestedLists:1,setText:1,getText:1,enable:1,disable:1,isEnabled:1,setWidth:1,setFocus:1})
		dhtmlXForm.prototype.items.input[a] = dhtmlXForm.prototype.items.select[a];
})();


/* password */
dhtmlXForm.prototype.items.password = {
	
	render: function(item, data) {
		
		item._type = "pw";
		item._enabled = true;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "INPUT", "PASSWORD", true, true, "dhxform_textarea");
		this.doAttachEvents(item);
		
		this.setValue(item, data.value);
		
		return this;
		
	}
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,doAttachEvents:1,destruct:1,doUnloadNestedLists:1,setText:1,getText:1,setValue:1,getValue:1,updateValue:1,enable:1,disable:1,isEnabled:1,setWidth:1,setReadonly:1,isReadonly:1,setFocus:1,getInput:1})
		dhtmlXForm.prototype.items.password[a] = dhtmlXForm.prototype.items.input[a];
})();

/* file */
dhtmlXForm.prototype.items.file = {
	
	render: function(item, data) {
		
		item._type = "fl";
		item._enabled = true;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "INPUT", "FILE", true, false, "dhxform_textarea");
		
		var t = item.childNodes[item._ll ? 1 : 0].childNodes[0];
		var that = this;
		t.onfocus = function() {
			var i = this.parentNode.parentNode;
			i.getForm().callEvent("onFocus",[i._idd]);
			i = null;
		};
		t.onblur = function () {
			var i = this.parentNode.parentNode;
			if (i.getForm().live_validate) that._validate(i);
			i.getForm().callEvent("onBlur", [i._idd]);
			i = null;
		};
		t = null;
		
		item.childNodes[item._ll?1:0].childNodes[0].onchange = function() {
			item.callEvent("onChange", [item._idd, this.value]);
		}
		
		return this;
		
	},
	
	setValue: function(){},
	
	getValue: function(item) {
		return item.childNodes[item._ll?1:0].childNodes[0].value;
	}
	
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,destruct:1,doUnloadNestedLists:1,setText:1,getText:1,getInput:1,enable:1,disable:1,isEnabled:1,setWidth:1})
		dhtmlXForm.prototype.items.file[a] = dhtmlXForm.prototype.items.input[a];
})();

/* label */
dhtmlXForm.prototype.items.label = {
	
	_index: false,
	
	render: function(item, data) {
		
		item._type = "lb";
		item._enabled = true;
		item._checked = true;
		
		var t = document.createElement("DIV");
		t.className = "dhxform_txt_label2"+(data._isTopmost?" topmost":"");
		t.innerHTML = data.label;
		item.appendChild(t);
		
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		if (!isNaN(data.labelWidth)) t.style.width = parseInt(data.labelWidth)+"px";
		if (!isNaN(data.labelHeight)) t.style.height = parseInt(data.labelHeight)+"px";
		
		if (!isNaN(data.labelLeft)) t.style.left = parseInt(data.labelLeft)+"px";
		if (!isNaN(data.labelTop)) t.style.top = parseInt(data.labelTop)+"px";
		
		return this;
	},
	
	destruct: function(item) {
		
		this.doUnloadNestedLists(item);
		
		item._autoCheck = null;
		item._enabled = null;
		item._type = null;
		
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		item.onselectstart = null;
		item.parentNode.removeChild(item);
		item = null;
		
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
	},
	
	setText: function(item, text) {
		item.firstChild.innerHTML = text;
	},

	getText: function(item) {
		return item.firstChild.innerHTML;
	}
	
};

(function(){
	for (var a in {doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.label[a] = dhtmlXForm.prototype.items.checkbox[a];
})();


/* button */
dhtmlXForm.prototype.items.button = {
	
	render: function(item, data) {
		
		item._type = "bt";
		item._enabled = true;
		item._name = data.name;
		
		item.className = String(item.className).replace("item_label_top","item_label_left").replace("item_label_right","item_label_left");
		
		item._doOnKeyUpDown = function(evName, evObj) {
			this.callEvent(evName, [this.childNodes[0].childNodes[0], evObj, this._idd]);
		}
		
		item.innerHTML = '<div class="dhxform_btn" role="link" tabindex="0" dir="ltr">'+
					'<div class="dhxform_btn_txt">'+data.value+'</div>'+
					'<div class="dhxform_btn_filler" disabled="true"></div>'+ // IE click w/o focus loss
				'</div>';
		
		if (!isNaN(data.width)) {
			var w = Math.max(data.width,10);
			if (dhtmlXForm.prototype.items[this.t]._dim == null) {
				item.firstChild.style.width = w+"px";
				dhtmlXForm.prototype.items[this.t]._dim = item.getForm()._checkDim(item, item.firstChild);
			}
			item.firstChild.style.width = w-dhtmlXForm.prototype.items[this.t]._dim+"px";
			item.firstChild.firstChild.className += " dhxform_btn_txt_autowidth";
		}
		
		
		
		if (!isNaN(data.inputLeft)) item.childNodes[0].style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) item.childNodes[0].style.top = parseInt(data.inputTop)+"px";
		
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		if (typeof(data.tooltip) != "undefined") item.firstChild.title = data.tooltip;
		
		// item onselectstart also needed once
		// will reconstructed!
		
		item.onselectstart = function(e){
			e = e||event;
			e.cancelBubble = true;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
		item.firstChild.onselectstart = function(e){
			e = e||event;
			e.cancelBubble = true;
			if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
			return false;
		}
		
		item.firstChild.onkeypress = function(e) {
			e = e||event
			if ((e.keyCode == 32 || e.charCode == 32 || e.keyCode == 13 || e.charCode == 13) && !this.parentNode._busy) {
				this.parentNode._busy = true;
				e.cancelBubble = true;
				if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
				_dhxForm_doClick(this.childNodes[0], ["mousedown", "mouseup"]);
				return false;
			}
		}
		
		item.firstChild.onfocus = function() {
			this.parentNode._doOnFocus(this.parentNode);
		}
		
		item.firstChild.onblur = function() {
			_dhxForm_doClick(this.childNodes[0], "mouseout");
			this.parentNode._doOnBlur(this.parentNode);
		}
		
		item.firstChild.onkeyup = function(e) {
			this.parentNode._doOnKeyUpDown("onKeyUp", e||event);
		}
		item.firstChild.onkeydown = function(e) {
			this.parentNode._doOnKeyUpDown("onKeyDown", e||event);
		}
		item.firstChild.onmouseover = function(){
			var t = this.parentNode;
			if (!t._enabled) return;
			this._isOver = true;
			this.className = "dhxform_btn dhxform_btn_over";
			t = null;
		}
		item.firstChild.onmouseout = function(){
			var t = this.parentNode;
			if (!t._enabled) return;
			this.className = "dhxform_btn";
			this._allowClick = false;
			this._pressed = false;
			this._isOver = false;
			t = null;
		}
		item.firstChild.ontouchstart = item.firstChild.onmousedown = function(e){
			e = e||event;
			if (e.type == "touchstart" && e.preventDefault) e.preventDefault();
			if (e.button >= 2) return; // ie=0/other=1
			if (this._pressed) return;
			var t = this.parentNode;
			if (!t._enabled) return;
			this.className = "dhxform_btn dhxform_btn_pressed";
			this._allowClick = true;
			this._pressed = true;
			t = null;
		}
		
		item.firstChild.ontouchend = item.firstChild.onmouseup = function(e){
			e = e||event;
			if (e.button >= 2) return;
			if (!this._pressed) return;
			var t = this.parentNode;
			if (!t._enabled) return;
			t._busy = false;
			this.className = "dhxform_btn"+(this._isOver?" dhxform_btn_over":"");
			if (this._pressed && this._allowClick) t.callEvent("_onButtonClick", [t._name, t._cmd]);
			this._allowClick = false;
			this._pressed = false;
			t = null;
		}
		
		item._doOnFocus = function(item) {
			item.getForm().callEvent("onFocus",[item._idd]);
		}
		
		item._doOnBlur = function(item) {
			item.getForm().callEvent("onBlur",[item._idd]);
		}
		
		return this;
	},
	
	destruct: function(item) {
		
		this.doUnloadNestedLists(item);
		
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		item._autoCheck = null;
		item._type = null;
		item._enabled = null;
		item._cmd = null;
		item._name = null;
		item._doOnFocus = null;
		item._doOnBlur = null;
		item._doOnKeyUpDown = null;
		
		item.onselectstart = null;
		
		item.firstChild.onselectstart = null;
		item.firstChild.onkeypress = null;
		item.firstChild.ontouchstart = null;
		item.firstChild.ontouchend = null;
		item.firstChild.onfocus = null;
		item.firstChild.onblur = null;
		item.firstChild.onkeyup = null;
		item.firstChild.onkeydown = null;
		item.firstChild.onmouseover = null;
		item.firstChild.onmouseout = null;
		item.firstChild.onmousedown = null;
		item.firstChild.onmouseup = null;
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item.parentNode.removeChild(item);
		item = null;
		
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
		item.childNodes[0].removeAttribute("disabled");
		item.childNodes[0].setAttribute("role", "link");
		item.childNodes[0].setAttribute("tabIndex", "0");
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
		item.childNodes[0].setAttribute("disabled", "true");
		item.childNodes[0].removeAttribute("role");
		item.childNodes[0].removeAttribute("tabIndex");
	},
	
	setText: function(item, text) {
		item.childNodes[0].childNodes[0].innerHTML = text;
	},

	getText: function(item) {
		return item.childNodes[0].childNodes[0].innerHTML;
	},
	
	setFocus: function(item) {
		item.childNodes[0].focus();
	}
	
};

(function(){
	for (var a in {doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.button[a] = dhtmlXForm.prototype.items.checkbox[a];
})();

/* hidden item */
dhtmlXForm.prototype.items.hidden = {
	
	_index: false,
	
	render: function(item, data) {
		
		item.style.display = "none";
		
		item._name = data.name;
		item._type = "hd";
		item._enabled = true;
		
		var t = document.createElement("INPUT");
		t.type = "HIDDEN";
		t.name = data.name;
		t.value = (data.value||"")
		item.appendChild(t);
		
		return this;
	},
	
	destruct: function(item) {
		
		
		this.doUnloadNestedLists(item);
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item._autoCheck = null;
		item._name = null;
		item._type = null;
		item._enabled = null;
		item.onselectstart = null;
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		item.parentNode.removeChild(item);
		item = null;
		
	},
	
	enable: function(item) {
		item._enabled = true;
		item.childNodes[0].setAttribute("name", item._name);
	},
	
	disable: function(item) {
		item._enabled = false;
		item.childNodes[0].removeAttribute("name");
	},
	
	show: function() {
		
	},
	
	hide: function() {
		
	},
	
	isHidden: function() {
		return true;
	},
	
	setValue: function(item, val) {
		item.childNodes[0].value = val;
	},
	
	getValue: function(item) {
		return item.childNodes[0].value;
	},
	
	getInput: function(item) {
		return item.childNodes[0];
	}
	
};

(function(){
	for (var a in {doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.hidden[a] = dhtmlXForm.prototype.items.checkbox[a];
})();

/* sub list */
dhtmlXForm.prototype.items.list = {
	
	_index: false,
	
	render: function(item, skin) {
		
		item._type = "list";
		item._enabled = true;
		item._isNestedForm = true;
		item.style.paddingLeft = item._ofsNested+"px";
		
		item.className = "dhxform_base_nested"+(item._custom_css||"");
		
		return [this, new dhtmlXForm(item, null, skin)];
	},
	
	destruct: function(item) {
		
		// linked to _listBase
		// automaticaly cleared when parent item unloaded
		
	}
};

/* fieldset */
dhtmlXForm.prototype.items.fieldset = {
	
	_index: false,
	
	render: function(item, data) {
		
		item._type = "fs";
		
		if (typeof(parseInt(data.inputWidth)) == "number") {
			// if (window.dhx4.isFF||window.dhx4.isOpera) data.inputWidth -= 12;
			// chrome-11/ie9 - ok
		}
		
		item._width = data.width;
		
		item._enabled = true;
		item._checked = true; // required for authoCheck
		
		item.className = "fs_"+data.position+(typeof(data.className)=="string"?" "+data.className:"");
		
		var f = document.createElement("FIELDSET");
		f.className = "dhxform_fs";
		var align = String(data.labelAlign).replace("align_","");
		f.innerHTML = "<legend class='fs_legend' align='"+align+"' style='text-align:"+align+"'>"+data.label+"</legend>";
		item.appendChild(f);
		
		if (!isNaN(data.inputLeft)) f.style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) f.style.top = parseInt(data.inputTop)+"px";
		if (data.inputWidth != "auto") {
			if (!isNaN(data.inputWidth)) {
				f.style.width = parseInt(data.inputWidth)+"px";
				var w = parseInt(f.style.width);
				if (f.offsetWidth > w) f.style.width = w+(w-f.offsetWidth)+"px";
			}
		}
		
		item._addSubListNode = function() {
			var t = document.createElement("DIV");
			t._custom_css = " dhxform_fs_nested";
			this.childNodes[0].appendChild(t);
			return t;
		}
		
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		return this;
	},
	
	destruct: function(item) {
		
		this.doUnloadNestedLists(item);
		
		item._checked = null;
		item._enabled = null;
		item._idd = null;
		item._type = null;
		item._width = null;
		
		item.onselectstart = null;
		
		item._addSubListNode = null;
		item._autoCheck = null;
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item.parentNode.removeChild(item);
		item = null;
	
	},
	
	setText: function(item, text) {
		item.childNodes[0].childNodes[0].innerHTML = text;
	},
	
	getText: function(item) {
		return item.childNodes[0].childNodes[0].innerHTML;
	},
	
	enable: function(item) {
		item._enabled = true;
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
	},
	
	disable: function(item) {
		item._enabled = false;
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
	},
	
	setWidth: function(item, width) {
		item.childNodes[0].style.width = width+"px";
		item._width = width;
	},
	
	getWidth: function(item) {
		return item._width;
	}
	
};

(function(){
	for (var a in {doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.fieldset[a] = dhtmlXForm.prototype.items.checkbox[a];
})();


/* block */
dhtmlXForm.prototype.items.block = {
	
	_index: false,
	
	render: function(item, data) {
		
		item._type = "bl";
		
		item._width = data.width;
		
		item._enabled = true;
		item._checked = true; // required for authoCheck
		
		item.className = "block_"+data.position+(typeof(data.className)=="string"?" "+data.className:"");
		
		var b = document.createElement("DIV");
		b.className = "dhxform_obj_"+item.getForm().skin+" dhxform_block";
		b.style.fontSize = item.getForm().cont.style.fontSize;
		if (data.style) b.style.cssText = data.style;
		
		if (typeof(data.id) != "undefined") b.id = data.id;
		
		item.appendChild(b);
		
		if (!isNaN(data.inputLeft)) b.style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) b.style.top = parseInt(data.inputTop)+"px";
		if (data.inputWidth != "auto") if (!isNaN(data.inputWidth)) b.style.width = parseInt(data.inputWidth)+"px";
		
		if (!isNaN(data.blockOffset)) {
			item._ofsNested = data.blockOffset;
		}
		
		item._addSubListNode = function() {
			var t = document.createElement("DIV");
			t._inBlcok = true;
			if (typeof(this._ofsNested) != "undefined") t._ofsNested = this._ofsNested;
			this.childNodes[0].appendChild(t);
			return t;
		}
		
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		return this;
	},
	
	_setCss: function(item, skin, fontSize) {
		item.firstChild.className = "dhxform_obj_"+skin+" dhxform_block";
		item.firstChild.style.fontSize = fontSize;
	}
};

(function(){
	for (var a in {enable:1,disable:1,isEnabled:1,setWidth:1,getWidth:1,doUnloadNestedLists:1,destruct:1})
		dhtmlXForm.prototype.items.block[a] = dhtmlXForm.prototype.items.fieldset[a];
})();

/* new column */
dhtmlXForm.prototype.items.newcolumn = {
	_index: false
};

/* template */
dhtmlXForm.prototype.items.template = {
	
	render: function(item, data) {
		
		var ta = (!isNaN(data.rows));
		
		item._type = "tp";
		item._enabled = true;
		
		if (data.format != null) {
			if (typeof(data.format) == "function") {
				item.format = data.format;
			} else if (typeof(data.format) == "string" && typeof(window[data.format]) == "function") {
				item.format = window[data.format];
			}
		}
		if (item.format == null) {
			item.format = function(name, value) { return value; }
		}
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "DIV", null, true, true, "dhxform_item_template");
		
		this.setValue(item, data.value||"");
		
		return this;
		
	},
	
	destruct: function(item) {
		item.format = null;
		this.d2(item);
		item = null;
	},
	
	setValue: function(item, value) {
		item._value = value;
		item.childNodes[item._ll?1:0].childNodes[0].innerHTML = item.format(item._idd, item._value);
	},
	
	getValue: function(item) {
		return item._value;
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
	}
	
};

(function(){
	dhtmlXForm.prototype.items.template.d2 = dhtmlXForm.prototype.items.input.destruct;
	for (var a in {doAddLabel:1,doAddInput:1,doUnloadNestedLists:1,setText:1,getText:1,isEnabled:1,setWidth:1})
		dhtmlXForm.prototype.items.template[a] = dhtmlXForm.prototype.items.select[a];
})();

//loading from UL list

dhtmlXForm.prototype._ulToObject = function(ulData, a) {
	var obj = [];
	for (var q=0; q<ulData.childNodes.length; q++) {
		if (String(ulData.childNodes[q].tagName||"").toLowerCase() == "li") {
			var p = {};
			var t = ulData.childNodes[q];
			for (var w=0; w<a.length; w++) if (t.getAttribute(a[w]) != null) p[String(a[w]).replace("ftype","type")] = t.getAttribute(a[w]);
			if (!p.label) try { p.label = t.firstChild.nodeValue; } catch(e){}
			var n = t.getElementsByTagName("UL");
			if (n[0] != null) p[(p.type=="select"?"options":"list")] = dhtmlXForm.prototype._ulToObject(n[0], a);
			// userdata
			for (var w=0; w<t.childNodes.length; w++) {
				if (String(t.childNodes[w].tagName||"").toLowerCase() == "userdata") {
					if (!p.userdata) p.userdata = {};
					p.userdata[t.childNodes[w].getAttribute("name")] = t.childNodes[w].firstChild.nodeValue;
				}
			}
			obj[obj.length] = p;
		}
		if (String(ulData.childNodes[q].tagName||"").toLowerCase() == "div") {
			var p = {};
			p.type = "label";
			try { p.label = ulData.childNodes[q].firstChild.nodeValue; } catch(e){}
			obj[obj.length] = p;
		}
	}
	return obj;
};

dhtmlXForm.prototype.setUserData = function(id, name, value, rValue) {
	if (typeof(rValue) != "undefined") { // radiobutton: name,value,ud_name,ud_value
		var k = this.doWithItem([id,name], "_getId");
		if (k != null) { id = k; name = value; value = rValue; }
	}
	if (!this._userdata) this._userdata = {};
	this._userdata[id] = (this._userdata[id]||{});
	this._userdata[id][name] = value;
};

dhtmlXForm.prototype.getUserData = function(id, name, rValue) {
	if (typeof(rValue) != "undefined") { // radiobutton: name,value,ud_name
		var k = this.doWithItem([id,name], "_getId");
		if (k != null) { id = k; name = rValue; }
	}
	if (this._userdata != null && typeof(this._userdata[id]) != "undefined" && typeof(this._userdata[id][name]) != "undefined") return this._userdata[id][name];
	return "";
};

dhtmlXForm.prototype.setRTL = function(state) {
	this._rtl = (state===true?true:false);
	if (this._rtl) {
		if (String(this.cont).search(/dhxform_rtl/gi) < 0) this.cont.className += " dhxform_rtl";
	} else {
		if (String(this.cont).search(/dhxform_rtl/gi) >= 0) this.cont.className = String(this.cont.className).replace(/dhxform_rtl/gi,"");
	}
};

_dhxForm_doClick = function(obj, evType) {
	if (typeof(evType) == "object") {
		var t = evType[1];
		evType = evType[0];
	}
	if (document.createEvent) {
		var e = document.createEvent("MouseEvents");
		e.initEvent(evType, true, false);
		obj.dispatchEvent(e);
	} else if (document.createEventObject) {
		var e = document.createEventObject();
		e.button = 1;
		obj.fireEvent("on"+evType, e);
	}
	if (t) window.setTimeout(function(){_dhxForm_doClick(obj,t);},100);
}

dhtmlXForm.prototype.setFormData = function(t) {
	for (var a in t) {
		var r = this.getItemType(a);
		switch (r) {
			case "checkbox":
				this[t[a]==true||parseInt(t[a])==1||t[a]=="true"||t[a]==this.getItemValue(a, "realvalue")?"checkItem":"uncheckItem"](a);
				break;
			case "radio":
				this.checkItem(a,t[a]);
				break;
			case "input":
			case "textarea":
			case "password":
			case "select":
			case "multiselect":
			case "hidden":
			case "template":
			case "combo":
			case "calendar":
			case "colorpicker":
			case "editor":
				this.setItemValue(a,t[a]);
				break;
			default:
				if (this["setFormData_"+r]) {
					// check for custom cell
					this["setFormData_"+r](a,t[a]);
				} else {
					// if item with specified name not found, keep value in userdata
					if (!this.hId) this.hId = this._genStr(12);
					this.setUserData(this.hId, a, t[a]);
				}
				break;
		}
	}
};

dhtmlXForm.prototype.getFormData = function(p0, only_fields) {
	
	var r = {};
	var that = this;
	for (var a in this.itemPull) {
		var i = this.itemPull[a]._idd;
		var t = this.itemPull[a]._type;
		if (t == "ch") r[i] = (this.isItemChecked(i)?this.getItemValue(i):0);
		if (t == "ra" && !r[this.itemPull[a]._group]) r[this.itemPull[a]._group] = this.getCheckedValue(this.itemPull[a]._group);
		if (t in {se:1,ta:1,pw:1,hd:1,tp:1,fl:1,calendar:1,combo:1,editor:1,colorpicker:1}) r[i] = this.getItemValue(i,p0);
		// check for custom cell
		if (this["getFormData_"+t]) r[i] = this["getFormData_"+t](i);
		// merge with files/uploader
		if (t == "up") {
			var r0 = this.getItemValue(i);
			for (var a0 in r0) r[a0] = r0[a0];
		}
		//
		if (this.itemPull[a]._list) {
			for (var q=0; q<this.itemPull[a]._list.length; q++) {
				var k = this.itemPull[a]._list[q].getFormData(p0,only_fields);
				for (var b in k) r[b] = k[b];
			}
		}
	}
	// collecr hId userdata
	if (!only_fields && this.hId && this._userdata[this.hId]) {
		for (var a in this._userdata[this.hId]) {
			if (!r[a]) r[a] = this._userdata[this.hId][a];
		}
	}
	return r;
};

dhtmlXForm.prototype.adjustParentSize = function() {
	
	var kx = 0;
	var ky = -1;
	for (var q=0; q<this.base.length; q++) {
		kx += this.base[q].firstChild.offsetWidth;
		if (this.base[q].offsetHeight > ky) ky = this.base[q].offsetHeight;
	}
	
	// check if layout
	var isLayout = false;
	try {
		isLayout = (this.cont.parentNode.parentNode.parentNode.parentNode._isCell==true);
		if (isLayout) var layoutCell = this.cont.parentNode.parentNode.parentNode.parentNode;
	} catch(e){};
	
	if (isLayout && typeof(layoutCell) != "undefined") {
		
		if (kx > 0) layoutCell.setWidth(kx+10);
		if (ky > 0) layoutCell.setHeight(ky+layoutCell.firstChild.firstChild.offsetHeight+5);
		
		isLayout = layoutCell = null;
		return;
	}
	
	// check if window
	var isWindow = false;
	try {
		isWindow = (this.cont.parentNode.parentNode.parentNode._isWindow == true);
		if (isWindow) {
			var winCell = this.cont.parentNode.parentNode;
			if (typeof(winCell.callEvent) == "function") {
				this.cont.style.display = "none";
				winCell.callEvent("_setCellSize", [kx+15,ky+15]);
				this.cont.style.display = "";
			}
		}
	} catch(e){};
	
};

// dataproc
dhtmlXForm.prototype.reset = function() {
	if (this.callEvent("onBeforeReset", [this.formId, this.getFormData()])) {
		if (this._last_load_data) this.setFormData(this._last_load_data);
		this.callEvent("onAfterReset", [this.formId]);
	}
};

dhtmlXForm.prototype.send = function(url, mode, callback, skipValidation) {
	
	if (typeof mode == "function") {
		callback = mode;
		mode = "post";
	} else {
		mode = (mode=="get"?"get":"post");
	}
	
	if (skipValidation !== true && !this.validate()) return;
	var formData = this.getFormData(true);
	
	var data = [];
	for (var key in formData) data.push(key+"="+encodeURIComponent(formData[key]));
	
	var afterload = function(loader) {
		if (callback) callback.call(this, loader, loader.xmlDoc.responseText);
	};
	
	if (mode == "get") {
		window.dhx4.ajax.get(url+(url.indexOf("?")==-1?"?":"&")+data.join("&"), afterload);
	} else {
		window.dhx4.ajax.post(url, data.join("&"), afterload);
	}
	
};

dhtmlXForm.prototype.save = function(url, type){};

dhtmlXForm.prototype.dummy = function(){};

dhtmlXForm.prototype._changeFormId = function(oldid, newid) {
	this.formId = newid;
};

dhtmlXForm.prototype._dp_init = function(dp) {
	
	dp._methods = ["dummy", "dummy", "_changeFormId", "dummy"];
	
	dp._getRowData = function(id, pref) {
		var data = this.obj.getFormData(true);
		data[this.action_param] = this.obj.getUserData(id, this.action_param);
		return data;
	};
	dp._clearUpdateFlag = function(){};
	
	dp.attachEvent("onAfterUpdate", function(sid, action, tid, tag){
		if (action == "inserted" || action == "updated" || action == "error" || action == "invalid")
			this.obj.resetDataProcessor("updated");
		if (action == "inserted" || action == "updated")
			this.obj._last_load_data = this.obj.getFormData(true);
					
		this.obj.callEvent("onAfterSave",[this.obj.formId, tag]);
		return true;
	});
	
	dp.autoUpdate = false;
	dp.setTransactionMode("POST", true);
	
	this.dp = dp;
	
	this.formId = (new Date()).valueOf();
	this.resetDataProcessor("inserted");
	
	this.save = function(){
		if (!this.callEvent("onBeforeSave", [this.formId, this.getFormData()])) return;
		if (!this.validate()) return;
		dp.sendData();
	};
};


dhtmlXForm.prototype.resetDataProcessor = function(mode){
	if (!this.dp) return;
	this.dp.updatedRows = []; this.dp._in_progress = [];
	this.dp.setUpdated(this.formId, true, mode);
};

// cc listener
dhtmlXForm.prototype._ccActivate = function(id, inp, val) {
	
	if (!this._formLS) this._formLS = {};
	if (!this._formLS[id]) this._formLS[id] = {input: inp, value: val};
	if (!this._ccActive) {
		this._ccActive = true;
		this._ccDo();
	}
	inp = null;
};

dhtmlXForm.prototype._ccDeactivate = function(id) {
	
	if (this._ccTm) window.clearTimeout(this._ccTm);
	this._ccActive = false;
	if (this._formLS != null && this._formLS[id] != null) {
		this._formLS[id].input = null;
		this._formLS[id] = null;
		delete this._formLS[id];
	}
};

dhtmlXForm.prototype._ccDo = function() {
	
	if (this._ccTm) window.clearTimeout(this._ccTm);
	
	for (var a in this._formLS) {
		
		var inp = this._formLS[a].input;
		
		if (String(inp.tagName).toLowerCase() == "select") {
			var v = "";
			if (inp.selectedIndex >= 0 && inp.selectedIndex < inp.options.length) v = inp.options[inp.selectedIndex].value;
		} else {
			var v = inp.value;
		}
		if (v != this._formLS[a].value) {
			this._formLS[a].value = v;
			this.callEvent("onInputChange",[inp._idd,v,this]);
		}
		inp = null;
		
	}
	
	if (this._ccActive) {
		var t = this;
		this._ccTm = window.setTimeout(function(){t._ccDo();t=null;},100);
	}
	
};
	
dhtmlXForm.prototype._ccReload = function(id, value) { // update item's value while item have focus
	if (this._formLS && this._formLS[id]) {
		this._formLS[id].value = value;
	}
};

dhtmlXForm.prototype._checkDim = function(formNode, inpObj) {
	
	var testNode = document.createElement("DIV");
	testNode.className = "dhxform_obj_"+this.skin;
	testNode.style.cssText += (dhx4.isIE6==true?"visibility:hidden;":"position:absolute;left:-2000px;top:-1000px;");
	document.body.appendChild(testNode);
	
	var pNode = formNode.parentNode;
	var sNode = formNode.nextSibling;
	testNode.appendChild(formNode);
	
	var w = parseInt(inpObj.style.width);
	var w2 = (dhx4.isFF || dhx4.isIE || dhx4.isChrome || dhx4.isOpera ? inpObj.offsetWidth : inpObj.clientWidth);
	var dim = w2-w;
	
	if (sNode != null) pNode.insertBefore(formNode, sNode); else pNode.appendChild(formNode);
	testNode.parentNode.removeChild(testNode);
	
	pNode = sNode = testNode = formNode = inpObj = null;
	
	return dim;
};

dhtmlXForm.prototype._autoload = function() {
	var a = [
		"ftype", "name", "value", "label", "check", "checked", "disabled", "text", "rows", "select", "selected", "width", "style", "className",
		"labelWidth", "labelHeight", "labelLeft", "labelTop", "inputWidth", "inputHeight", "inputLeft", "inputTop", "position", "size"
	];
	var k = document.getElementsByTagName("UL");
	var u = [];
	for (var q=0; q<k.length; q++) {
		if (k[q].className == "dhtmlxForm") {
			var formNode = document.createElement("DIV");
			u[u.length] = {nodeUL:k[q], nodeForm:formNode, data:dhtmlXForm.prototype._ulToObject(k[q], a), name:(k[q].getAttribute("name")||null)};
		}
	}
	for (var q=0; q<u.length; q++) {
		u[q].nodeUL.parentNode.insertBefore(u[q].nodeForm, u[q].nodeUL);
		var listObj = new dhtmlXForm(u[q].nodeForm, u[q].data);
		if (u[q].name !== null) window[u[q].name] = listObj;
		var t = (u[q].nodeUL.getAttribute("oninit")||null);
		u[q].nodeUL.parentNode.removeChild(u[q].nodeUL);
		u[q].nodeUL = null;
		u[q].nodeForm = null;
		u[q].data = null;
		u[q] = null;
		// oninit call
		if (t) { if (typeof(t) == "function") t(); else if (typeof(window[t]) == "function") window[t](); }
	}
	if (typeof(window.addEventListener) == "function") {
		window.removeEventListener("load", dhtmlXForm.prototype._autoload, false);
	} else {
		window.detachEvent("onload", dhtmlXForm.prototype._autoload);
	};
	
};

if (typeof(window.addEventListener) == "function") {
	window.addEventListener("load", dhtmlXForm.prototype._autoload, false);
} else {
	window.attachEvent("onload", dhtmlXForm.prototype._autoload);
};

if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype.attachForm = function(data) {
		
		this.callEvent("_onBeforeContentAttach",["form"]);
		
		var obj = document.createElement("DIV");
		obj.style.width = "100%";
		obj.style.height = "100%";
		obj.style.position = "relative";
		
		if (window.dhtmlx && dhtmlx.$customScroll) dhtmlx.CustomScroll.enable(obj); else obj.style.overflow = "auto";
		
		this._attachObject(obj);
		
		this.dataType = "form";
		this.dataObj = new dhtmlXForm(obj, data);
		this.dataObj.setSkin(this.conf.skin);
		
		obj = null;
		
		this.callEvent("_onContentAttach",[]);
		
		return this.dataObj;
		
	};
	
}

