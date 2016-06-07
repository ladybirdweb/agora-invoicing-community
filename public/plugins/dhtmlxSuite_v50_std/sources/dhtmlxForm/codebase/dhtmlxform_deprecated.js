/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.getItemsList = function() {
	// returns array of item names (without doubling)
	// deprecated from 3.6.2
	var list = [];
	var exist = [];
	for (var a in this.itemPull) {
		var id = null;
		if (this.itemPull[a]._group) {
			id = this.itemPull[a]._group;
		} else {
			id = a.replace(this.idPrefix, "");
		}
		if (exist[id] != true)
			list.push(id);
		exist[id] = true;
	}
	return list;
};

dhtmlXForm.prototype.setItemText = function() {
	// deprecated from 3.6.2
	this.setItemLabel.apply(this, arguments);
};

dhtmlXForm.prototype.getItemText = function() {
	// deprecated from 3.6.2
	return this.getItemLabel.apply(this, arguments);
};

dhtmlXForm.prototype.loadStructString = function(xmlString, onLoad) {
	this.loadStruct(xmlString, onLoad);
};

