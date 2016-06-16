/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.combo = {
	
	render: function(item, data) {
		
		item._type = "combo";
		item._enabled = true;
		item._value = null;
		item._newValue = null;
		
		var skin = item.getForm().skin;
		if (typeof(data.inputWidth) != "undefined" && skin == "material" && String(data.inputWidth).match(/^\d*$/) != null) {
			data.inputWidth = parseInt(data.inputWidth)+2;
		}
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "SELECT", null, true, true, "dhxform_select");
		this.doAttachEvents(item);
		this.doLoadOpts(item, data);
		
		// allow selection to prevent broking combo logic
		item.onselectstart = function(e){return true;}
		
		// item.childNodes[1].childNodes[0].opt_type = data.comboType||"";
		item.childNodes[item._ll?1:0].childNodes[0].setAttribute("mode", data.comboType||"");
		if (data.comboImagePath) item.childNodes[item._ll?1:0].childNodes[0].setAttribute("imagePath", data.comboImagePath);
		if (data.comboDefaultImage) item.childNodes[item._ll?1:0].childNodes[0].setAttribute("defaultImage", data.comboDefaultImage);
		if (data.comboDefaultImageDis) item.childNodes[item._ll?1:0].childNodes[0].setAttribute("defaultImageDis", data.comboDefaultImageDis);
		
		item._combo = new dhtmlXComboFromSelect(item.childNodes[item._ll?1:0].childNodes[0]);
		item._combo.setSkin(skin);
		item._combo._currentComboValue = item._combo.getSelectedValue();
		item._combo.getInput().id = data.uid;
		
		if (skin == "material") item._combo.list.className += " dhxform_obj_"+skin;
		
		var k = this;
		item._combo.attachEvent("onChange", function(){
			k.doOnChange(this);
		});
		
		if (data.connector) this.doLoadOptsConnector(item, data.connector);
		
		if (data.filtering) {
			item._combo.enableFilteringMode(true);
		} else if (data.serverFiltering) {
			item._combo.enableFilteringMode(true, data.serverFiltering, data.filterCache, data.filterSubLoad);
		}
		
		if (data.readonly == true) this.setReadonly(item, true);
		if (data.hidden == true) this.hide(item);
		
		if (data.style) item._combo.DOMelem_input.style.cssText += data.style;
		
		item._combo.attachEvent("onFocus", function(){
			var item = this.cont.parentNode.parentNode;
			var f = item.getForm();
			if ((f.skin == "dhx_terrace" || f.skin == "material") && this.cont.className.search(/combo_in_focus/) < 0) this.cont.className += " combo_in_focus";
			f.callEvent("onFocus", [item._idd]);
			f = item = null;
		});
		
		item._combo.attachEvent("onBlur", function(){
			var item = this.cont.parentNode.parentNode;
			var f = item.getForm();
			if ((f.skin == "dhx_terrace" || f.skin == "material") && this.cont.className.search(/combo_in_focus/) >= 0) this.cont.className = this.cont.className.replace(/\s{0,}combo_in_focus/gi,"");
			f.callEvent("onBlur", [item._idd]);
			f = item = null;
		});
		
		return this;
	},
	
	destruct: function(item) {
		
		// unload combo
		item.childNodes[item._ll?1:0].childNodes[0].onchange = null;
		
		item._combo._currentComboValue = null;
		item._combo.unload();
		item._combo = null;
		
		// unload item
		item._apiChange = null;
		this.d2(item);
		item = null;
		
	},
	
	doAttachEvents: function(item) {
		
		var that = this;
		
		item.childNodes[item._ll?1:0].childNodes[0].onchange = function() {
			that.doOnChange(this);
			that.doValidate(this.DOMParent.parentNode.parentNode);
		}
	},
	
	doValidate: function(item) {
		if (item.getForm().hot_validate) this._validate(item);
	},
	
	doOnChange: function(combo) {
		var item = combo.base.parentNode.parentNode.parentNode;
		if (item._apiChange) return;
		combo._newComboValue = combo.getSelectedValue();
		if (combo._newComboValue != combo._currentComboValue) {
			if (item.checkEvent("onBeforeChange")) {
				if (item.callEvent("onBeforeChange", [item._idd, combo._currentComboValue, combo._newComboValue]) !== true) {
					// restore last value
					// not the best solution, should be improved
					window.setTimeout(function(){combo.setComboValue(combo._currentComboValue);},1);
					return false;
				}
			}
			combo._currentComboValue = combo._newComboValue;
			item.callEvent("onChange", [item._idd, combo._currentComboValue]);
		}
		item._autoCheck();
	},
	
	doLoadOptsConnector: function(item, url) {
		var that = this;
		var i = item;
		item._connector_working = true;
		item._apiChange = true;
		item._combo.load(url, function(){
			// try to set value if it was called while options loading was in progress
			i.callEvent("onOptionsLoaded", [i._idd]);
			i._connector_working = false;
			if (i._connector_value != null) {
				that.setValue(i, i._connector_value);
				i._connector_value = null;
			}
			i._apiChange = false;
			that = i = null;
		});
	},
	
	enable: function(item) {
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._enabled = true;
		item._combo.enable();
	},
	
	disable: function(item) {
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._enabled = false;
		item._combo.disable();
	},
	
	getCombo: function(item) {
		return item._combo;
	},
	
	setValue: function(item, val) {
		if (item._connector_working) { // attemp to set value while optins not yet loaded (connector used)
			item._connector_value = val;
			return;
		}
		item._apiChange = true;
		item._combo.setComboValue(val);
		item._combo._currentComboValue = item._combo.getActualValue();
		item._apiChange = false;
	},
	
	getValue: function(item) {
		return item._combo.getActualValue();
	},
	
	setWidth: function(item, width) {
		item.childNodes[item._ll?1:0].childNodes[0].style.width = width+"px";
	},
	
	setReadonly: function(item, state) {
		if (!item._combo) return;
		item._combo_ro = state;
		item._combo.readonly(item._combo_ro);
	},

	isReadonly: function(item, state) {
		return item._combo_ro||false;
	},
	
	setFocus: function(item) {
		if (item._enabled) item._combo.setFocus();
	},
	
	_setCss: function(item, skin, fontSize) {
		// update font-size for input and list-options div
		item._combo.setFontSize(fontSize, fontSize);
	}
	
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,doLoadOpts:1,doUnloadNestedLists:1,setText:1,getText:1,isEnabled:1,_checkNoteWidth:1})
		dhtmlXForm.prototype.items.combo[a] = dhtmlXForm.prototype.items.select[a];
})();

dhtmlXForm.prototype.items.combo.d2 = dhtmlXForm.prototype.items.select.destruct;

dhtmlXForm.prototype.getCombo = function(name) {
	return this.doWithItem(name, "getCombo");
};


