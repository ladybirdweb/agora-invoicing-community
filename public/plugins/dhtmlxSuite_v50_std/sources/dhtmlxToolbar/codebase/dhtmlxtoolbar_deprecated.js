/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXToolbarObject.prototype.loadXML = function(xmlFile, onLoad) {
	this.loadStruct(xmlFile, onLoad);
};
dhtmlXToolbarObject.prototype.loadXMLString = function(xmlString, onLoad) {
	this.loadStruct(xmlString, onLoad);
};
dhtmlXToolbarObject.prototype.setIconPath = function(path) {
	this.setIconsPath(path);
};

/*
misc:
conf.icon_path => conf.icons_path for objectr api init
*/

