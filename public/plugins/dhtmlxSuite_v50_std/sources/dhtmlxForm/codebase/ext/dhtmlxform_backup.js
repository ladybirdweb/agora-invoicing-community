/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.saveBackup = function() {
	if (!this._backup) {
		this._backup = {};
		this._backupId = new Date().getTime();
	}
	this._backup[++this._backupId] = this.getFormData();
	return this._backupId;
};

dhtmlXForm.prototype.restoreBackup = function(id) {
	if (this._backup != null && this._backup[id] != null) {
		this.setFormData(this._backup[id]);
	}
};

dhtmlXForm.prototype.clearBackup = function(id) {
	if (this._backup != null && this._backup[id] != null) {
		this._backup[id] = null;
		delete this._backup[id];
	}
};

