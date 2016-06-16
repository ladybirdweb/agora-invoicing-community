/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.container = {
	
	render: function(item, data) {
		
		item._type = "container";
		item._enabled = true;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "DIV", null, true, true, "dhxform_container");
		
		return this;
		
	},
	
	getContainer: function(item) {
		return item.childNodes[item._ll?1:0].childNodes[0];
	},
	
	enable: function(item) {
		item._enabled = true;
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		//
		item.callEvent("onEnable",[item._idd]);
	},
	
	disable: function(item) {
		item._enabled = false;
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		//
		item.callEvent("onDisable",[item._idd]);
	},
	
	doAttachEvents: function(){
		
	},
	
	setValue: function(){
		
	},
	
	getValue: function(){
		return null;
	}
	
};

dhtmlXForm.prototype.getContainer = function(name) {
	return this.doWithItem(name, "getContainer");
};

(function(){
	for (var a in dhtmlXForm.prototype.items.input) {
		if (!dhtmlXForm.prototype.items.container[a]) dhtmlXForm.prototype.items.container[a] = dhtmlXForm.prototype.items.input[a];
	}
})();

