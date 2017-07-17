/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

// global context menu
dhtmlXWindows.prototype.attachContextMenu = function(conf) {
	return this._renderContextMenu("icon", null, null, conf);
};
dhtmlXWindows.prototype.getContextMenu = function() {
	if (this.cm != null && this.cm.global != null) return this.cm.global;
	return null;
};
dhtmlXWindows.prototype.detachContextMenu = function() {
	this._detachContextMenu("icon", null, null);
};

// custom menu
dhtmlXWindowsCell.prototype.attachContextMenu = function(conf) {
	return this.wins._renderContextMenu("icon", this._idd, null, conf);
};
dhtmlXWindowsCell.prototype.getContextMenu = function() {
	if (this.wins.cm != null && this.wins.cm.icon[this._idd] != null) return this.wins.cm.icon[this._idd];
	return null;
};
dhtmlXWindowsCell.prototype.detachContextMenu = function() {
	this.wins._detachContextMenu("icon", this._idd, null);
};

// menu for button
dhtmlXWindowsButton.prototype.attachContextMenu = function(conf) {
	return this.conf.wins._renderContextMenu("button", this.conf.winId, this.conf.name, conf);
};
dhtmlXWindowsButton.prototype.getContextMenu = function() {
	if (this.conf.wins.cm == null || this.conf.wins.cm.button[this.conf.winId] == null) return null;
	if (this.conf.wins.cm.button[this.conf.winId][this.conf.name] != null) return this.conf.wins.cm.button[this.conf.winId][this.conf.name];
	return null;
};
dhtmlXWindowsButton.prototype.detachContextMenu = function() {
	this.conf.wins._detachContextMenu("button", this.conf.winId, this.conf.name);
};

dhtmlXWindows.prototype._renderContextMenu = function(mode, wId, bId, conf) {
	
	var that = this;
	var firstInit = false;
	
	if (this.cm == null) {
		this.cm = {
			global:	null,	// global context menu for icon
			icon:	{},	// custom for icon, {winId:menuInst, winId2:menuInst2}
			button: {}	// custom foc button, {winId:{buttonId:menuInst, buttonId2:menuInst2}, winId2:{..}}
		};
		firstInit = true;
	}
	
	// check if already attached
	if (wId == null) {
		if (this.cm.global != null) return;
	} else if (mode == "icon") {
		if (this.cm.icon[wId] != null) return;
	} else if (mode == "button") {
		if (this.cm.button[wId] != null && this.cm.button[wId][bId] != null) return;
	}
	
	
	// init
	if (conf == null) conf = {};
	conf.parent = null;
	conf.context = true;
	
	var menu = new dhtmlXMenuObject(conf);
	menu.setAutoHideMode(false);
	
	menu.attachEvent("onShow", function() {
		this.conf.wins_menu_open = true;
	});
	
	menu.attachEvent("onHide", function() {
		this.conf.wins_menu_open = false;
		that.conf.opened_menu = null;
	});
	
	if (wId == null) {
		this.cm.global = menu;
	} else if (mode == "icon") {
		this.cm.icon[wId] = menu;
	} else if (mode == "button") {
		if (this.cm.button[wId] == null) this.cm.button[wId] = {};
		this.cm.button[wId][bId] = menu;
	}
	
	if (firstInit) {
		
		this._showContextMenu = function(e, data) {
			
			if (e.button >= 2) return;
			
			if (data.mode == "icon" && data.id != null && data.press_type == "mousedown") {
				
				var menu = this.cm.icon[data.id]||this.cm.global;
				if (menu == null) return;
				
				e.cancelBubble = true;
				
				var icon = this.w[data.id].hdr.firstChild;
				
				if (menu.conf.wins_menu_open && this.conf.opened_menu == data.id) {
					menu.hideContextMenu();
				} else {
					this._hideContextMenu();
					menu.showContextMenu(window.dhx4.absLeft(icon), window.dhx4.absTop(icon)+icon.offsetHeight);
					this.conf.opened_menu = data.id;
				}
				menu = icon = null;
				
			}
			
			if (data.mode == "button" && data.id != null && data.press_type == "mousedown") {
				
				if (this.cm.button[data.id] == null || this.cm.button[data.id][data.button_name] == null) return;
				
				e.cancelBubble = true;
				
				this.conf.button_last = null; // cancel button click
				
				var menu = this.cm.button[data.id][data.button_name];
				var button = this.w[data.id].b[data.button_name].button;
				
				if (menu.conf.wins_menu_open && this.conf.opened_menu == data.id) {
					menu.hideContextMenu();
				} else {
					this._hideContextMenu();
					menu.showContextMenu(window.dhx4.absLeft(button), window.dhx4.absTop(button)+button.offsetHeight);
					this.conf.opened_menu = data.id;
				}
				menu = button = null;
				
			}
			
		}
		
		this._hideContextMenu = function(e) {
			
			if (e != null) {
				e = e||event;
				if (e.type == "keydown" && e.keyCode != 27) return;
				
				var t = e.target||e.srcElement;
				var m = true;
				while (t != null && m == true) {
					if (t.className != null && t.className.search(/SubLevelArea_Polygon/) >= 0) {
						m = false;
					} else {
						t = t.parentNode;
					}
				}
			}
			
			if (m || e == null) {
				if (that.cm.global != null) that.cm.global.hideContextMenu();
				for (var a in that.cm.icon) {
					if (that.cm.icon[a] != null) that.cm.icon[a].hideContextMenu();
				}
				for (var a in that.cm.button) {
					for (var b in that.cm.button[a]) {
						if (that.cm.button[a][b] != null) that.cm.button[a][b].hideContextMenu();
					}
				}
			}
			
		}
		
		this._detachContextMenu = function(mode, wId, bId) {
			if (this.cm == null) return;
			if (wId == null) {
				if (this.cm.global != null) {
					this.cm.global.unload();
					this.cm.global = null;
				}
			} else if (mode == "icon") {
				if (this.cm.icon[wId] != null) {
					this.cm.icon[wId].unload();
					this.cm.icon[wId] = null;
				}
			} else if (mode == "button") {
				if (this.cm.button[wId] != null && this.cm.button[wId][bId] != null) {
					this.cm.button[wId][bId].unload();
					this.cm.button[wId][bId] = null;
				}
			}
			
		}
		
		this.attachEvent("_winMouseDown", this._showContextMenu);
		
		if (typeof(window.addEventListener) == "function") {
			window.addEventListener("mousedown", this._hideContextMenu, false);
			window.addEventListener("keydown", this._hideContextMenu, false);
		} else {
			document.body.attachEvent("onmousedown", this._hideContextMenu);
			document.body.attachEvent("onkeydown", this._hideContextMenu);
		}
		
		this._unloadContextMenu = function() {
			
			// remove only global menu if any, other will removed from win/button unload
			this._detachContextMenu("icon", null, null);
			this.cm = null;
			
			if (typeof(window.addEventListener) == "function") {
				window.removeEventListener("mousedown", this._hideContextMenu, false);
				window.removeEventListener("keydown", this._hideContextMenu, false);
			} else {
				document.body.detachEvent("onmousedown", this._hideContextMenu);
				document.body.detachEvent("onkeydown", this._hideContextMenu);
			}
			
			that = null;
		}
		
	}
	
	return menu;
};

