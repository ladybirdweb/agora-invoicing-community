/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/* deprecated */
dhtmlXAccordion.prototype.setEffect = function(mode) {
	// expand/collapse, dnd
	// enabled for browsers which support html5 by default
};
dhtmlXAccordion.prototype.setIcon = function(id, icon) {
	this.cells(id).setIcon(icon);
};
dhtmlXAccordion.prototype.clearIcon = function(id) {
	this.cells(id).clearIcon();
};
dhtmlXAccordion.prototype.setActive = function(id) {
	this.cells(id).open();
};
dhtmlXAccordion.prototype.isActive = function(id) {
	return this.cells(id).isOpened();
};
dhtmlXAccordion.prototype.openItem = function(id) {
	this.cells(id).open();
};
dhtmlXAccordion.prototype.closeItem = function(id) {
	this.cells(id).close();
};
dhtmlXAccordion.prototype.moveOnTop = function(id) {
	this.cells(id).moveOnTop();
};
dhtmlXAccordion.prototype.setItemHeight = function(h) {
	this.cells(id).setHeight(h);
};
dhtmlXAccordion.prototype.setText = function(id, text) {
	this.cells(id).setText(text);
};
dhtmlXAccordion.prototype.getText = function() {
	return this.cells(id).getText();
};
dhtmlXAccordion.prototype.showItem = function(id) {
	this.cells(id).show();
};
dhtmlXAccordion.prototype.hideItem = function(id) {
	this.cells(id).hide();
};
dhtmlXAccordion.prototype.isItemHidden = function(id) {
	return !this.cells(id).isVisible();
};
dhtmlXAccordion.prototype.loadJSON = function(data, callback) {
	this.loadStruct(data, callback);
};
dhtmlXAccordion.prototype.loadXML = function(data, callback) {
	this.loadStruct(data, callback);
};
dhtmlXAccordion.prototype.setSkinParameters = function(ofsBetween, ofsCont) {
	if (ofsBetween != null) this.setOffset(ofsBetween);
};

