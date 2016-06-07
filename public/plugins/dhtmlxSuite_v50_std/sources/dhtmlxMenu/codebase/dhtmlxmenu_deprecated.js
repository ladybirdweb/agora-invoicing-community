/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXMenuObject.prototype.loadXML = function(xmlFile, onLoad) {
	this.loadStruct(xmlFile, onLoad);
};
dhtmlXMenuObject.prototype.loadXMLString = function(xmlString, onLoad) {
	this.loadStruct(xmlString, onLoad);
};
dhtmlXMenuObject.prototype.setIconPath = function(path) {
	this.setIconsPath(path);
};
dhtmlXMenuObject.prototype.setImagePath = function() {
	/* no more used, from 90226 */
};

