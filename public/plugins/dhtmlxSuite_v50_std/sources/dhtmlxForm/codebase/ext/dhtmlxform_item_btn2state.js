/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.btn2state = {
	setChecked: function(item, state) {
		item._checked = (state===true?true:false);
		item.childNodes[item._ll?1:0].lastChild.className = "dhxform_img "+item._cssName+"_"+(item._checked?"1":"0");
		this.doCheckValue(item);
	}
};

(function() {
	for (var a in dhtmlXForm.prototype.items.checkbox) {
		if (!dhtmlXForm.prototype.items.btn2state[a]) dhtmlXForm.prototype.items.btn2state[a] = dhtmlXForm.prototype.items.checkbox[a];
	}
})();


dhtmlXForm.prototype.items.btn2state.render2 = dhtmlXForm.prototype.items.btn2state.render;
dhtmlXForm.prototype.items.btn2state.render = function(item, data) {
	data._autoInputWidth = false;
	this.render2(item, data);
	item._type = "btn2state";
	item._cssName = (typeof(data.cssName)=="undefined"?"btn2state":data.cssName);
	item._updateImgNode = function(){};
	item._doOnFocus = function() {
		item.getForm().callEvent("onFocus",[item._idd]);
	}
	item._doOnBlur = function() {
		item.getForm().callEvent("onBlur",[item._idd]);
	}
	item._doOnKeyUpDown = function(evName, evObj, inp) {
		this.callEvent(evName, [this.childNodes[this._ll?0:1].childNodes[0], evObj, this._idd]);
	}
	this.setChecked(item, item._checked);
	return this;
};

dhtmlXForm.prototype.setFormData_btn2state = function(name, value) {
	this[value==true||parseInt(value)==1||value=="true"||value==this.getItemValue(name)?"checkItem":"uncheckItem"](name);
};
dhtmlXForm.prototype.getFormData_btn2state = function(name) {
	return (this.isItemChecked(name)?this.getItemValue(name):0);
};

