/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

window.dhtmlXColorPickerInput = function() {
	return dhtmlXColorPicker.apply(window, arguments);
};

dhtmlXColorPicker.prototype.init = function(){
	// inited automatically
};

dhtmlXColorPicker.prototype.setOnSelectHandler = function(fn) {
	if (typeof fn == "function") this.attachEvent("onSelect", fn);
};

dhtmlXColorPicker.prototype.setOnCancelHandler = function(fn) {
	if (typeof fn == "function") this.attachEvent("onCancel", fn);
};

dhtmlXColorPicker.prototype._mergeLangModules = function(){
	if (typeof dhtmlxColorPickerLangModules != "object") return;
	for (var key in dhtmlxColorPickerLangModules) {
		this.i18n[key] = dhtmlxColorPickerLangModules[key];
	}
};

window.dhtmlxColorPickerLangModules = dhtmlXColorPicker.prototype.i18n;

dhtmlXColorPicker.prototype.close = function() {
	this.hide();
};

dhtmlXColorPicker.prototype.setImagePath = function(path){
	// no longer used
};

