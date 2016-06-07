/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.upload = {
	
	render: function(item, data) {
		
		item._type = "up";
		
		item._enabled = true;
		item._checked = true; // required for authoCheck
		
		item.className = data.position+(typeof(data.className)=="string"?" "+data.className:"");
		
		var k = document.createElement("DIV");
		item.appendChild(k);
		
		if (!isNaN(data.inputLeft)) item.style.left = parseInt(data.inputLeft)+"px";
		if (!isNaN(data.inputTop)) item.style.top = parseInt(data.inputTop)+"px";
		if (data.inputWidth != "auto") if (!isNaN(data.inputWidth)) k.style.width = parseInt(data.inputWidth)+"px";
		
		item._uploader = new dhtmlXFileUploader(k, data.swfPath||"", data.swfUrl||"", data.mode||null, data.swfLogs, data.slXap||"", data.slUrl||"", data.slLogs||"", data.multiple);
		item._uploader.setURL(data.url||"");
		item._uploader.callEvent = item.callEvent;
		
		if (typeof(data.autoStart) != "undefined") item._uploader.setAutoStart(data.autoStart);
		if (typeof(data.autoRemove) != "undefined") item._uploader.setAutoRemove(data.autoRemove);
		if (typeof(data.titleScreen) != "undefined") item._uploader.enableTitleScreen(data.titleScreen);
		if (typeof(data.titleText) != "undefined") item._uploader.setTitleText(data.titleText);
		
		if (data.hidden == true) this.hide(item);
		if (data.disabled == true) this.userDisable(item);
		
		if (!(data.inputHeight == "auto" || parseInt(data.inputHeight) == NaN))
			item._uploader.p_files.style.height = parseInt(data.inputHeight)+"px";
		
		return this;
	},
	
	destruct: function(item) {
		
		this.doUnloadNestedLists(item);
		
		item._uploader.callEvent = function(){return true;};
		item._uploader.unload();
		item._uploader.callEvent = null;
		item._uploader = null;
		
		item._checked = null;
		item._enabled = null;
		item._idd = null;
		item._type = null;
		
		item.onselectstart = null;
		
		item._autoCheck = null;
		item.callEvent = null;
		item.checkEvent = null;
		item.getForm = null;
		
		while (item.childNodes.length > 0) item.removeChild(item.childNodes[0]);
		
		item.parentNode.removeChild(item);
		item = null;
	
	},
	
	setText: function(item, text) {
		
	},
	
	getText: function(item) {
		
	},
	
	enable: function(item) {
		item._enabled = true;
		if (String(item.className).search("disabled") >= 0) item.className = String(item.className).replace(/disabled/gi,"");
		item._uploader.enable();
	},
	
	disable: function(item) {
		item._enabled = false;
		if (String(item.className).search("disabled") < 0) item.className += " disabled";
		item._uploader.disable();
	},
	
	setWidth: function(item, width) {
		item.childNodes[0].style.width = width+"px";
		item._width = width;
	},
	
	getWidth: function(item) {
		return item._width||parseInt(item.childNodes[0].style.width);
	},
	
	setValue: function(item) {
		item._uploader.clear();
	},
	
	getValue: function(item) {
		var t = item._uploader.getData();
		var r = {};
		var i = 0;
		for (var a in t) {
			r[item._idd+"_r_"+i] = t[a].realName;
			r[item._idd+"_s_"+i] = t[a].serverName;
			i++;
		}
		r[item._idd+"_count"] = i;
		return r;
	},
	
	getUploader: function(item) {
		return item._uploader;
	},
	
	getStatus: function(item) {
		return item._uploader.getStatus();
	}
};

(function(){
	for (var a in {doUnloadNestedLists:1,isEnabled:1})
		dhtmlXForm.prototype.items.upload[a] = dhtmlXForm.prototype.items.checkbox[a];
})();

dhtmlXForm.prototype.setFormData_upload = function(name) {
	this.doWithItem(name, "setValue");
};

dhtmlXForm.prototype.getUploader = function(name) {
	return this.doWithItem(name, "getUploader");
};

dhtmlXForm.prototype.getUploaderStatus = function(name) {
	return this.doWithItem(name, "getStatus");
};

/* uploader */

function dhtmlXFileUploader(p, swfPath, swfUrl, mode, swfLogs, slXap, slUrl, slLogs, multiple) {
	
	var that = this;
	
	if (typeof(mode) == "string" && typeof(this[mode]) == "function") {
		this.engine = mode;
	} else {
		this.engine = "html4";
		
		var k = null;
		if (typeof(window.FormData) != "undefined" && typeof(window.XMLHttpRequest) != "undefined") {
			k = new XMLHttpRequest();
			if (typeof(k.upload) == "undefined") k = null;
		}
		
		if (k != null) {
			// IE10, IE11, FF, Chrome, Opera
			this.engine = "html5";
		} else if (typeof(window.swfobject) != "undefined" || k === false) {
			var k = swfobject.getFlashPlayerVersion();
			if (k.major >= 10) this.engine = "flash";
		} else {
			// check if silverlight installed
			this._sl_v = this.getSLVersion();
			if (this._sl_v) this.engine = "sl";
		}
		k = null;
	}
	
	if (typeof(p) == "string") p = document.getElementById(p);
	
	this._upload_mp = (typeof(multiple) != "undefined" ? multiple==true : true); // multiple file select
	this._upload_dnd = true;
	
	
	// swf-file path
	this._swf_file_url = swfPath||"";
	this._swf_upolad_url = swfUrl||"";
	this._swf_logs = swfLogs;
	
	// sl-data
	this._sl_xap = slXap;
	this._sl_upload_url = slUrl;
	this._sl_logs = slLogs;
	
	// main cont
	this.p = document.createElement("DIV");
	this.p.className += " dhx_file_uploader";
	p.appendChild(this.p);
	
	// files
	this.p_files = document.createElement("DIV");
	this.p_files.className = "dhx_upload_files";
	this.p.appendChild(this.p_files);
	
	// buttons
	this.p_controls = document.createElement("DIV");
	this.p_controls.className = "dhx_upload_controls";
	this.p.appendChild(this.p_controls);
	
	// init engine
	
	/* upload */
	
	this._files = {};
	this._items = {};
	
	this._data = {}; // uploaded files
	
	this._autoStart = false;
	this._autoRemove = false;
	this._titleScreen = true;
	
	this._enabled = true;
	
	this._uploaded_count = 0;
	
	this._initToolbar = function() {
		
		// add
		this.b_opts = {
			info:	{ onclick: null },
			browse:	{ onclick: null, tooltip: "Browse" },
			upload:	{ onclick: function() { if (!that._enabled) return; if (!that._uploading) { that._uploadStart(); } }, tooltip: "Upload" },
			cancel:	{ onclick: function() { if (!that._enabled) return; that._uploadStop(); that._switchButton(false); }, tooltip: "Stop" },
			clear:	{ onclick: function() { if (!that._enabled) return; that.clear(); }, tooltip: "Clear list" }
		};
		
		this.buttons = {};
		
		for (var a in this.b_opts) {
			var k = document.createElement("DIV");
			k.innerHTML = "&nbsp;";
			k.className = "dhx_file_uploader_button button_"+a;
			k.onclick = this.b_opts[a].onclick;
			if (this.b_opts[a].tooltip) k.title = this.b_opts[a].tooltip;
			this.p_controls.appendChild(k);
			this.buttons[a] = k;
			k = null;
		}
		
		this.buttons["cancel"].style.display = "none";
	}
	
	this._readableSize = function(t) {
		var i = false;
		var b = ["b","Kb","Mb","Gb","Tb","Pb","Eb"];
		for (var q=0; q<b.length; q++) if (t > 1024) t = t / 1024; else if (i === false) i = q;
		if (i === false) i = b.length-1;
		return Math.round(t*100)/100+" "+b[i];
	}
	
	this._beforeAddFileToList = function(name, size) {
		return (this.callEvent("onBeforeFileAdd", [name, size])===true);
	}
	
	this._addFileToList = function(id, name, size, state, progress) {
		
		this._checkTitleScreen();
		
		var t = document.createElement("DIV");
		t._idd = id;
		t.className = "dhx_file dhx_file_"+state;
		t.innerHTML =   "<div class='dhx_file_param dhx_file_name'>&nbsp;</div>"+
				"<div class='dhx_file_param dhx_file_progress'>"+progress+"%</div>"+
				"<div class='dhx_file_param dhx_file_delete' title='Remove from list'>&nbsp;</div>";
		
		this.p_files.appendChild(t);
		
		// filename area width
		t.childNodes[0].style.width = t.offsetWidth-127+"px";
		
		this._items[id] = t;
		
		this._updateFileNameSize(id);
		
		t.childNodes[2].onclick = function() {
			if (!that._enabled) return;
			var id = this.parentNode._idd;
			that._removeFileFromQueue(id);
		}
		
		this.callEvent("onFileAdd", [name]);
	}
	
	this._removeFileFromList = function(id) {
		
		if (!this._items[id]) return;
		
		this._items[id].childNodes[2].onclick = null;
		this._items[id].parentNode.removeChild(this._items[id]);
		this._items[id] = null;
		delete this._items[id];
		
		if (this._data[id]) {
			this._data[id] = null;
			delete this._data[id];
		}
		
		this._checkTitleScreen();
	}
	
	this._updateFileNameSize = function(id) {
		this._items[id].childNodes[0].innerHTML = this._files[id].name+(!isNaN(this._files[id].size)?" ("+this._readableSize(this._files[id].size)+")":"&nbsp;");
		this._items[id].childNodes[0].title = this._files[id].name+(!isNaN(this._files[id].size)?" ("+this._readableSize(this._files[id].size)+")":"");
	}
	
	this._updateFileInList = function(id, state, progress) {
		if (!this._items[id]) return;
		this._items[id].className = "dhx_file dhx_file_"+state;
		// progress
		this._updateProgress(id, state, progress);
		this._updateFileNameSize(id);
	}
	
	this._updateProgress = function(id, state, progress) {
		if (state == "uploading" && progress < 100 && this._progress_type == "loader") {
			this._items[id].childNodes[1].className = "dhx_file_param dhx_file_uploading";
			this._items[id].childNodes[1].innerHTML = "&nbsp;";
		} else {
			this._items[id].childNodes[1].className = "dhx_file_param dhx_file_progress";
			this._items[id].childNodes[1].innerHTML = progress+"%";
		}
		this._updateFileNameSize(id);
	}
	
	this._removeFilesByState = function(state) {
		for (var a in this._files) {
			if (state === true || this._files[a].state == state) {
				this._removeFileFromQueue(a);
			}
		}
	}
	
	this._switchButton = function(state) {
		if (state == true) {
			this.buttons["upload"].style.display = "none";
			this.buttons["cancel"].style.display = "";
		} else {
			var t = this._uploaded_count;
			this.buttons["upload"].style.display = "";
			this.buttons["cancel"].style.display = "none";
			this._uploaded_count = 0;
			if (t > 0) this.callEvent("onUploadComplete",[t]);
		}
	}
	
	this._uploadStart = function() {
		
		this._switchButton(true);
		
		// change status for prev fail auploads if any
		if (!this._uploading) {
			for (var a in this._files) {
				if (this._files[a].state == "fail") {
					this._files[a].state = "added";
					this._updateFileInList(a, "added", 0);
				}
			}
		}
		
		this._uploading = true;
		
		var t = false;
		
		for (var a in this._files) {
			if (!t && [this._files[a].state] == "added") {
				t = true;
				this._files[a].state = "uploading";
				this._updateFileInList(a, "uploading", 0);
				this._doUploadFile(a);
			}
		}
		if (!t) {
			this._uploading = false;
			this._switchButton(false);
		}
		
	}
	
	this._onUploadSuccess = function(id, serverName, r, extra) {
		
		// flash mode
		if (typeof(r) != "undefined" && this.engine == "flash") {
			var t = dhx4.s2j(r.data);
			if (t != null && t.state == true && t.name != null) {
				serverName = t.name;
				if (t.extra != null) extra = t.extra;
			} else {
				this._onUploadFail(id, (t!=null&&t.extra!=null?t.extra:null));
				return;
			}
		}
		
		//
		this._uploaded_count++;
		this._data[id] = {realName: this._files[id].name, serverName: serverName};
		this._files[id].state = "uploaded";
		this._updateFileInList(id, "uploaded", 100);
		this.callEvent("onUploadFile", [this._files[id].name, serverName, extra]);
		if (this._autoRemove) this._removeFileFromQueue(id);
		if (this._uploading) this._uploadStart();
	}
	
	this._onUploadFail = function(id, extra) {
		this._files[id].state = "fail";
		this._updateFileInList(id, "fail", 0);
		this.callEvent("onUploadFail", [this._files[id].name, extra]);
		if (this._uploading) this._uploadStart();
	}
	
	this._onUploadAbort = function(id) {
		this._uploading = false;
		this._files[id].state = "added";
		this._updateFileInList(id, "added", 0);
		this.callEvent("onUploadCancel",[this._files[id].name]);
	}
	
	this._checkTitleScreen = function() {
		var k = 0;
		for (var a in this._files) k++;
		
		if (k == 0 && this.p.className.search("dhx_file_uploader_title") < 0 && this._titleScreen) {
			// show title screen
			this.p.className += " dhx_file_uploader_title";
			this.buttons["info"].innerHTML = this._titleText;
			this.buttons["info"].style.width = Math.max(this.p_controls.offsetWidth-134, 0)+"px";
		}
		if ((k > 0 || !this._titleScreen) && this.p.className.search("dhx_file_uploader_title") >= 0) {
			// hide title screen
			this.p.className = this.p.className.replace(/dhx_file_uploader_title/g,"");
			this.buttons["info"].innerHTML = "";
		}
	}
	
	// events
	this.callEvent = function(){}
	
	// public
	this.upload = function() {
		if (!this._uploading) this._uploadStart();
	}
	
	this.setAutoStart = function(state) {
		this._autoStart = (state==true);
	}
	
	this.setAutoRemove = function(state) {
		this._autoRemove = (state==true);
	}
	
	this.enableTitleScreen = function(state) {
		this._titleScreen = (state==true);
		this._checkTitleScreen();
	}
	
	this.setTitleText = function(text) {
		this._titleText = text;
		if (this.p.className.search("dhx_file_uploader_title") >= 0) this.buttons["info"].innerHTML = this._titleText;
	}
	
	this.setURL = function(url) {
		this._url = url;
	}
	
	this.setSWFURL = function(url) {
		this._swf_upolad_url = url;
	}
	
	this.enable = function() {
		this._enabled = true;
		this.p_files.className = "dhx_upload_files";
		this.p_controls.className = "dhx_upload_controls";
	}
	
	this.disable = function() {
		this._enabled = false;
		this.p_files.className = "dhx_upload_files dhx_uploader_dis";
		this.p_controls.className = "dhx_upload_controls dhx_uploader_dis";
	}
	
	this.getStatus = function() {
		// 0 - filelist is empty
		// 1 - all files in filelist uploaded
		//-1 - not all files uploaded
		var t = 0;
		for (var a in this._files) {
			if (this._files[a].state != "uploaded") return -1;
			t = 1;
		}
		return t;
	}
	
	this.getData = function() {
		// return struct of uploaded files
		return this._data;
	}
	
	this.clear = function() {
		if (this.callEvent("onBeforeClear", []) !== true) return;
		if (this._uploading) that._uploadStop();
		that._switchButton(false);
		that._removeFilesByState(true);
		this.callEvent("onClear",[]);
	}
	
	this.unload = function() {
		
		// remove all files from queue/list
		this._removeFilesByState(true);
		this._data = null;
		this._files = null;
		this._items = null;
		
		// custom engine stuff
		this._unloadEngine();
		
		// buttons
		for (var a in this.buttons) {
			this.buttons[a].onclick = null;
			this.buttons[a].parentNode.removeChild(this.buttons[a]);
			this.buttons[a] = null;
			delete this.buttons[a];
		}
		this.buttons = null;
		
		// buttons settings
		for (var a in this.b_opts) {
			this.b_opts[a].onclick = null;
			this.b_opts[a] = null;
			delete this.b_opts[a];
		}
		this.b_opts = null;
		
		this.p_controls.parentNode.removeChild(this.p_controls);
		this.p_files.parentNode.removeChild(this.p_files);
		
		// main container
		this.p.className = this.p.className.replace(/dhx_file_uploader_title/gi,"").replace(/dhx_file_uploader/gi,"");
		
		for (var a in this) this[a] = null;
		
		that = a = null;
		
	}
	
	// init engine-relative funcs
	var e = new this[this.engine]();
	for (var a in e) { this[a] = e[a]; e[a] = null; }
	a = e = p = null;
	
	// init app
	this._initToolbar();
	this._initEngine();
	this._checkTitleScreen();
	
	return this;
	
}

// html5 engine

dhtmlXFileUploader.prototype.html5 = function(){};

dhtmlXFileUploader.prototype.html5.prototype = {

	_initEngine: function() {
		
		var that = this;
		this.buttons["browse"].onclick = function(){
			if (that._enabled) that.f.click();
		}
		
		this._progress_type = "percentage";
		
		// Safari on Windows sometimes have problem with multiple file selections
		// file length set to zero, do not allow multiple file selecting
		// d-n-d seems works fine
		
		var k = window.navigator.userAgent;
		if (k.match(/Windows/gi) != null && k.match(/AppleWebKit/gi) != null && k.match(/Safari/gi) != null) {
			if (k.match(/Version\/5\.1\.5/gi)) this._upload_mp = false;
			if (k.match(/Version\/5\.1[^\.\d{1,}]/gi)) this._upload_dnd = false;
			if (k.match(/Version\/5\.1\.1/gi)) {
				this._upload_mp = false;
				this._upload_dnd = false;
			}
			if (k.match(/Version\/5\.1\.2/gi)) this._upload_dnd = false;
			if (k.match(/Version\/5\.1\.7/gi)) this._upload_mp = false;
		}
		
		// "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-EN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.1 Safari/533.17.8"	// ok, no dnd
		// "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-EN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.2 Safari/533.18.5"	// ok, no dnd
		// "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-EN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.3 Safari/533.19.4"	// ok, no dnd
		// "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-EN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27"	// ok, no dnd
		// "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-EN) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1"	// ok, no dnd
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50"				// ok, dnd partialy fail, disabled
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.52.7 (KHTML, like Gecko) Version/5.1.1 Safari/534.51.22"			// multiple files add - fail, dnd partialy fail, disabled
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.52.7 (KHTML, like Gecko) Version/5.1.2 Safari/534.52.7"			// ok, dnd partialy fail, disabled
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.54.16 (KHTML, like Gecko) Version/5.1.4 Safari/534.54.16"			// ok
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.55.3 (KHTML, like Gecko) Version/5.1.5 Safari/534.55.3"			// multiple files add - fail
		// "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2"			// dnd - ok, multiselect - fail (Windows 8)
		
		// input
		this._addFileInput();
		
		// dnd
		if (this._upload_dnd) {
			
			this.p.ondragenter = function(e){
				if (!e.dataTransfer) return;
				e.stopPropagation();
				e.preventDefault();
			}
			this.p.ondragover = function(e){
				if (!e.dataTransfer) return;
				e.stopPropagation();
				e.preventDefault();
			}
			this.p.ondrop = function(e) {
				if (!e.dataTransfer) return;
				e.stopPropagation();
				e.preventDefault();
				if (that._enabled) that._parseFilesInInput(e.dataTransfer.files);
			}
			
			this._titleText = "Drag-n-Drop files here or<br>click to select files for upload.";
			
		} else {
			this._titleText = "Click to select files for upload.";
		}
	},
	
	_addFileInput: function() {
		
		// complete input reload, opera needs
		if (this.f != null) {
			this.f.onchange = null;
			this.f.parentNode.removeChild(this.f);
			this.f = null;
		}
		
		var that = this;
		
		this.f = document.createElement("INPUT");
		this.f.type = "file";
		
		if (this._upload_mp) this.f.multiple = "1";
		this.f.className = "dhx_uploader_input";
		this.p_controls.appendChild(this.f);
		
		this.f.onchange = function() {
			that._parseFilesInInput(this.files);
			if (window.dhx4.isOpera) that._addFileInput(); else this.value = "";
		}
	},

	_doUploadFile: function(id) {
		
		var that = this;
		
		if (!this._loader) {
			this._loader = new XMLHttpRequest();
			this._loader.upload.onprogress = function(e) {
				if (that._files[this._idd].state == "uploading") that._updateFileInList(this._idd, "uploading", Math.round(e.loaded*100/e.total));
			}
			this._loader.onload = function(e) {
				var r = dhx4.s2j(this.responseText);
				if (typeof(r) == "object" && r != null && typeof(r.state) != "undefined" && r.state == true) {
					that._onUploadSuccess(this.upload._idd, r.name, null, r.extra);
					r = null;
				} else {
					that._onUploadFail(this.upload._idd, (r!=null&&r.extra!=null?r.extra:null));
				}
			}
			this._loader.onerror = function(e) {
				that._onUploadFail(this.upload._idd);
			}
			this._loader.onabort = function(e) {
				that._onUploadAbort(this.upload._idd);
			}
		}
		
		this._loader.upload._idd = id;
		
		var form = new FormData();
		form.append("file", this._files[id].file);
		
		this._loader.open("POST", this._url+(String(this._url).indexOf("?")<0?"?":"&")+"mode=html5"+window.dhx4.ajax._dhxr("&"), true);
		this._loader.setRequestHeader("X-Requested-With", "XMLHttpRequest");
		this._loader.send(form);
		
	},
	
	_uploadStop: function() {
		if (!this._uploading || !this._loader) return;
		this._loader.abort();
	},
	
	_parseFilesInInput: function(f) {
		for (var q=0; q<f.length; q++) this._addFileToQueue(f[q]);
	},
	
	_addFileToQueue: function(f) {
		if (!this._beforeAddFileToList(f.name, f.size)) return;
		var id = (f._idd||window.dhx4.newId());
		this._files[id] = {file: f, name: f.name, size: f.size, state: "added"};
		this._addFileToList(id, f.name, f.size, "added", 0);
		if (this._autoStart && !this._uploading) this._uploadStart(true);
	},
	
	_removeFileFromQueue: function(id) {
		
		if (!this._files[id]) return;
		
		var name = this._files[id].name;
		var serverName = (this._data!=null&&this._data[id]!=null?this._data[id].serverName:null);
		
		if (this.callEvent("onBeforeFileRemove",[name,serverName]) !== true) return;
		
		var k = false;
		if (this._uploading && id == this._loader.upload._idd && this._files[id].state == "uploading") {
			this._uploadStop();
			k = true;
		}
		
		this._files[id].file = null;
		this._files[id].name = null;
		this._files[id].size = null;
		this._files[id].state = null;
		this._files[id] = null;
		delete this._files[id];
		
		this._removeFileFromList(id);
		
		this.callEvent("onFileRemove",[name,serverName]);
		
		if (k) this._uploadStart();

	},
	
	_unloadEngine: function() {
		
		this.buttons["browse"].onclick = null;
		
		this.f.onchange = null;
		this.f.parentNode.removeChild(this.f);
		this.f = null;
		
		this.p.ondragenter = null;
		this.p.ondragover = null;
		this.p.ondrop = null;
		
		if (this._loader) {
			this._loader.upload.onprogress = null;
			this._loader.onload = null;
			this._loader.onerror = null;
			this._loader.onabort = null;
			this._loader.upload._idd = null;
			this._loader = null;
		}
		
		this._initEngine = null;
		this._doUploadFile = null;
		this._uploadStop = null;
		this._parseFilesInInput = null;
		this._addFileToQueue = null;
		this._removeFileFromQueue = null;
		this._unloadEngine = null;
		
	}
	
};

// html4 engine

dhtmlXFileUploader.prototype.html4 = function(){};

dhtmlXFileUploader.prototype.html4.prototype = {

	_initEngine: function() {
		
		this._addForm();
		this._progress_type = "loader";
		
		this._titleText = "Click button<br>to select files for upload.";
	},
	
	_addForm: function() {
		
		var that = this;
		var id = window.dhx4.newId();
		
		if (!this.k) {
			
			this.k = document.createElement("DIV");
			this.k.className = "dhx_file_form_cont";
			this.buttons["browse"].appendChild(this.k);
			
			this.fr_name = "dhx_file_"+window.dhx4.newId();
			this.k.innerHTML = '<iframe name="'+this.fr_name+'" style="height:0px;width:0px;" frameBorder="0"></iframe>';
			
			this.fr = this.k.firstChild;
			
			if (window.navigator.userAgent.indexOf("MSIE") >= 0) {
				this.fr.onreadystatechange = function() {
					if (this.readyState == "complete") that._onLoad();
				}
			} else {
				this.fr.onload = function() {
					that._onLoad();
				}
			}
			
		}
		
		var f = document.createElement("DIV");
		f.innerHTML = "<form method='POST' enctype='multipart/form-data' target='"+this.fr_name+"' class='dhx_file_form' name='dhx_file_form_"+window.dhx4.newId()+"'>"+
				"<input type='hidden' name='mode' value='html4'>"+
				"<input type='hidden' name='uid' value='"+id+"'>"+
				"<input type='file' name='file' class='dhx_file_input'>"+
				"</form>";
		this.k.appendChild(f);
		
		f.firstChild.lastChild._idd = id;
		
		f.firstChild.lastChild.onchange = function(){
			if (!that._beforeAddFileToList(this.value, null)) return;
			that._addFileToQueue(this);
			this.onchange = null;
			this.parentNode.parentNode.style.display = "none";
			that._addForm();
		}
		
		f = null;
	},
	
	_onLoad: function() {
		if (this._uploading) {
			var r = dhx4.s2j(this.fr.contentWindow.document.body.innerHTML);
			//this.fr.contentWindow.document.body.innerHTML = "";
			if (typeof(r) == "object" && r != null) {
				if (typeof(r.state) != "undefined") {
					if (r.state == "cancelled") {
						this._onUploadAbort(this.fr._idd);
						r = null;
						return;
					} else if (r.state == true) {
						if (typeof(r.size) != "undefined" && !isNaN(r.size)) this._files[this.fr._idd].size = r.size;
						this._onUploadSuccess(this.fr._idd, r.name, null, r.extra);
						r = null;
						return;
					}
				}
			}
			this._onUploadFail(this.fr._idd, (r!=null && r.extra != null ? r.extra:null));
		}
		
	},
	
	_addFileToQueue: function(t) {
		var v = t.value.match(/[^\\\/]*$/g);
		if (v[0] != null) v = v[0]; else v = t.value;
		//
		this._files[t._idd] = { name: v, form: t.parentNode, node: t.parentNode.parentNode, input: t, state: "added"};
		this._addFileToList(t._idd, t.value, false, "added", 0);
		if (this._autoStart && !this._uploading) this._uploadStart(true);
	},
	
	_removeFileFromQueue: function(id) {
		
		var name = this._files[id].name;
		var serverName = (this._data!=null&&this._data[id]!=null?this._data[id].serverName:null);
		
		if (this.callEvent("onBeforeFileRemove",[name,serverName]) !== true) return;
		
		this._files[id].input.onchange = null;
		this._files[id].form.removeChild(this._files[id].input);
		this._files[id].node.removeChild(this._files[id].form);
		this._files[id].node.parentNode.removeChild(this._files[id].node);
		this._files[id].input = null;
		this._files[id].name = null;
		this._files[id].form = null;
		this._files[id].node = null;
		this._files[id].size = null;
		this._files[id].state = null;
		this._files[id] = null;
		delete this._files[id];
		
		this._removeFileFromList(id);
		
		this.callEvent("onFileRemove",[name,serverName]);
	},
	
	_doUploadFile: function(id) {
		this.fr._idd = id;
		this._files[id].form.action = this._url;
		this._files[id].form.submit();
	},
	
	_uploadStop: function() {
		if (!this._uploading) return;
		this.fr.contentWindow.location.href = (this._url)+(this._url.indexOf("?")<0?"?":"&")+"mode=html4&action=cancel"+window.dhx4.ajax._dhxr("&");
	},
	
	_unloadEngine: function() {
		
		if (this.k) {
			
			this.fr_name = null;
			this.fr.onreadystatechange = null;
			this.fr.onload = null;
			this.fr.parentNode.removeChild(this.fr);
			this.fr = null;
			
			// remove empty form
			this.k.firstChild.firstChild.lastChild.onchange = null;
			
			this.k.parentNode.removeChild(this.k);
			this.k = null;
			
		}
		
		this._initEngine = null;
		this._addForm = null;
		this._onLoad = null;
		this._addFileToQueue = null;
		this._removeFileFromQueue = null;
		this._doUploadFile = null;
		this._uploadStop = null;
		this._unloadEngine = null;
		
	}
	
};


dhtmlXFileUploader.prototype.flash = function(){};

dhtmlXFileUploader.prototype.flash.prototype = {
	
	_initEngine: function() {
		
		if (window.dhtmlXSWFObjectsPull == null) {
			window.dhtmlXSWFObjectsPull = {
				items: {},
				callEvent: function(id, name, params) {
					return window.dhtmlXSWFObjectsPull.items[id].uploader[name].apply(window.dhtmlXSWFObjectsPull.items[id].uploader,params);
				}
			};
		}
		
		var that = this;
		
		this._swf_obj_id = "dhtmlXFileUploaderSWFObject_"+window.dhx4.newId();
		this._swf_file_url = this._swf_file_url+window.dhx4.ajax._dhxr(this._swf_file_url);
		this.buttons["browse"].innerHTML = "<div id='"+this._swf_obj_id+"' style='width:100%;height:100%;'></div>";
		swfobject.embedSWF(this._swf_file_url, this._swf_obj_id, "100%", "100%", "9", null, {ID:this._swf_obj_id,enableLogs:this._swf_logs}, {wmode:"transparent"});
		
		var v = swfobject.getFlashPlayerVersion();
		this._titleText = "Engine successfuly inited<br>Flash Player: "+v.major+"."+v.minor+"."+v.release;
		
		this._progress_type = "percentage";
		
		window.dhtmlXSWFObjectsPull.items[this._swf_obj_id] = {id: this._swf_obj_id, uploader: this};
	},
	
	_beforeAddFileToQueue: function(name, size) {
		return (this.callEvent("onBeforeFileAdd", [name, size])===true);
	},
	
	_addFileToQueue: function(id, name, size) {
		if (window.dhx4.isIE) {
			// focus+hide fix for IE
			var k = document.createElement("INPUT");
			k.type = "TEXT";
			k.style.position = "absolute";
			k.style.left = "0px";
			k.style.top = window.dhx4.absTop(this.buttons["browse"])+"px";
			k.style.width = "10px";
			document.body.appendChild(k);
			k.focus();
			document.body.removeChild(k);
			k = null;
		}
		this._files[id] = {name: name, size: size, state: "added"};
		this._addFileToList(id, name, size, "added", 0);
		if (this._autoStart && !this._uploading) this._uploadStart(true);
	},
		
	_removeFileFromQueue: function(id) {
		
		if (!this._files[id]) return;
		
		var name = this._files[id].name;
		var serverName = (this._data!=null&&this._data[id]!=null?this._data[id].serverName:null);
		
		if (this.callEvent("onBeforeFileRemove",[name,serverName]) !== true) return;
		
		var k = false;
		if (this._uploading && this._files[id].state == "uploading") {
			this._uploadStop();
			k = true;
		}
		
		swfobject.getObjectById(this._swf_obj_id).removeFileById(id);
		
		this._files[id].name = null;
		this._files[id].size = null;
		this._files[id].state = null;
		this._files[id] = null;
		delete this._files[id];
		
		this._removeFileFromList(id);
		
		this.callEvent("onFileRemove",[name,serverName]);
		
		if (k) this._uploadStart();

	},
	
	_doUploadFile: function(id) {
		swfobject.getObjectById(this._swf_obj_id).upload(id, this._swf_upolad_url);
	},
	
	_uploadStop: function(id) {
		for (var a in this._files) if (this._files[a].state == "uploading") swfobject.getObjectById(this._swf_obj_id).uploadStop(a);
	},
	
	_unloadEngine: function() {
		
		// remove instance from global storage
		
		if (window.dhtmlXSWFObjectsPull.items[this._swf_obj_id]) {
			window.dhtmlXSWFObjectsPull.items[this._swf_obj_id].id = null;
			window.dhtmlXSWFObjectsPull.items[this._swf_obj_id].uploader = null;
			window.dhtmlXSWFObjectsPull.items[this._swf_obj_id] = null
			delete window.dhtmlXSWFObjectsPull.items[this._swf_obj_id];
		}
		
		this._swf_obj_id = null;
		
		this._initEngine = null;
		this._addFileToQueue = null;
		this._removeFileFromQueue = null;
		this._doUploadFile = null;
		this._uploadStop = null;
		this._unloadEngine = null;
	}
	
};

dhtmlXFileUploader.prototype.sl = function(){};
		
dhtmlXFileUploader.prototype.sl.prototype = {
	
	_initEngine: function() {
		
		if (typeof(this._sl_v) == "undefined") this._sl_v = this.getSLVersion();
		
		if (!window.dhtmlXFileUploaderSLObjects) {
			window.dhtmlXFileUploaderSLObjects = {
				items: {},
				callEvent: function(id, name, params) {
					//console.log(arguments)
					window.dhtmlXFileUploaderSLObjects.items[id].uploader[name].apply(window.dhtmlXFileUploaderSLObjects.items[id].uploader,params);
				}
			};
		}
		
		//var that = this;
		
		this._sl_obj_id = "dhtmlXFileUploaderSLObject_"+window.dhx4.newId();
		
		if (this._sl_v != false) {
			this._titleText = "Engine successfuly inited<br>Silverlight version: "+this._sl_v[0]+"."+this._sl_v[1];
			this.buttons["browse"].innerHTML = '<div style="width:100%;height:100%;">'+
									'<object data="data:application/x-silverlight-2," type="application/x-silverlight-2" width="100%" height="100%" id="'+this._sl_obj_id+'">'+
										'<param name="source" value="'+this._sl_xap+'"/>'+
										'<param name="background" value="Transparent"/>'+
										'<param name="windowless" value="true"/>'+
										'<param name="initParams" value="SLID='+this._sl_obj_id+',LOGS='+this._sl_logs+'"/>'+
										'<param name="minRuntimeVersion" value="5.0"/>'+
									'</object>'+
								'</div>';
		} else {
			this._titleText = "Silverlight plugin not found<br>or version less than 4.0";
			this.buttons["browse"].style.cursor = "wait";
			this.buttons["browse"].title = "";
		}
		
		this._progress_type = "percentage";
		
		window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id] = {id: this._sl_obj_id, uploader: this};
	},
	
	_addFileToQueue: function(id, name, size) {
		this._files[id] = {name: name, size: size, state: "added"};
		this._addFileToList(id, name, size, "added", 0);
		if (this._autoStart && !this._uploading) this._uploadStart(true);
	},
	
	_removeFileFromQueue: function(id) {
		if (!this._files[id]) return;
		
		var k = false;
		if (this._uploading && this._files[id].state == "uploading") {
			this._uploadStop();
			k = true;
		}
		
		document.getElementById([this._sl_obj_id]).Content.a.removeFileById(id);
		
		this._files[id].name = null;
		this._files[id].size = null;
		this._files[id].state = null;
		this._files[id] = null;
		delete this._files[id];
		
		this._removeFileFromList(id);
		
		if (k) this._uploadStart();
		
	},
	
	_doUploadFile: function(id) {
		// sl have inner url parser and params will cut,
		// sho should be passed via 3rd param
		var p = this._sl_upload_url.split("?");
		p = (p[1]!=null?"&"+p[1]:"");
		//
		document.getElementById(this._sl_obj_id).Content.a.upload(id, this._sl_upload_url, p+"&mode=sl"+window.dhx4.ajax._dhxr("&")); // leading "&" required!
	},
	
	_uploadStop: function(id) {
		this._uploading = false;
		for (var a in this._files) if (this._files[a].state == "uploading") document.getElementById(this._sl_obj_id).Content.a.uploadStop(a);
	},
	
	_unloadEngine: function() {
		
		// remove instance from global storage
		
		if (window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id]) {
			window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id].id = null;
			window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id].uploader = null;
			window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id] = null
			delete window.dhtmlXFileUploaderSLObjects.items[this._sl_obj_id];
		}
		
		this._sl_obj_id = null;
		
		this._initEngine = null;
		this._addFileToQueue = null;
		this._removeFileFromQueue = null;
		this._doUploadFile = null;
		this._uploadStop = null;
		this._unloadEngine = null;
	}
	
};

dhtmlXFileUploader.prototype.setSLURL = function(url) {
	this._sl_upload_url = url;
};

dhtmlXFileUploader.prototype.getSLVersion = function() {
	var v = false;
	if (window.dhx4.isIE) {
		try {
			var t = new ActiveXObject('AgControl.AgControl');
			if (t != null) {
				// loop through [4-x, 0-9] until supported
				var k1 = 4, k2 = 0;
				while (t.isVersionSupported([k1,k2].join("."))) {
					v = [k1,k2];
					if (++k2 > 9) { k1++; k2=0; }
				}
			}
			t = null;
		} catch(e) {};
	} else {
		if (navigator.plugins["Silverlight Plug-In"] != null) {
			v = navigator.plugins["Silverlight Plug-In"].description.split(".");
		}
	}
	return v;
};

