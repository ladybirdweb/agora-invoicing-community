/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

window.dhtmlxAjax = {
	get: function(url, callback, sync) {
		if (sync) return dhx4.ajax.getSync(url); else dhx4.ajax.get(url, callback);
	},
	post: function(url, post, callback, sync) {
		if (sync) return dhx4.ajax.postSync(url, post); else dhx4.ajax.post(url, post, callback);
	},
	getSync: function(url) {
		return dhx4.ajax.getSync(url);
	},
	postSync: function(url, post) {
		return dhx4.ajax.postSync(url, post);
	}
};
