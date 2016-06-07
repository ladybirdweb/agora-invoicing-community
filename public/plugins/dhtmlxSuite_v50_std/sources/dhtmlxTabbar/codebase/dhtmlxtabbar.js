/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXTabBar(conf, mode) { // skin? data?
	
	// console.log("context menu for tabs?");
	// console.log("first tab open event?");
	
	var that = this;
	
	this.conf = {
		skin: (window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxtabbar")||"material"),
		css: "dhxtabbar", // css prefix for topcell mtb
		lastActive: null,
		closeButton: false,
		align: "left", // tabs aligned to right side, inversed, default "right" align
		tabsMode: (mode=="bottom"?"bottom":"top"), // top/bottom
		tabsContCss: "", // fix for Safari 5.1.7 desktop or Safari iOS 7.x mobile
		contZone: true,
		transSpeed: "0.15s",
		arwMode: "always", // always (def), auto
		tabsOfs: 1, // dhx_skyblue and dhx_terrace have margin-left:-1px for tabs, should be included
		tabsTop: 0, // tabs top position, used in attachObject to hide border
		url_demand: false,
		urls: {},
		autoload: {},
		tabsWidth: {dhx_terrace: [44,14], dhx_web: [35,9], dhx_skyblue: [35,9], material: [44,14]} // extra width for tabs/tab_with_close_icon, for tab width detect, since 4.2.1
	};
	
	if (this.conf.skin == "material") this.conf.arwMode = "auto";
	
	// safari fix
	var a = navigator.userAgent;
	if (a.indexOf("Safari") >= 0 && (a.indexOf("5.1.7") >= 0 || (a.match(/7[\.\d]* mobile/gi) != null && a.match(/AppleWebKit/gi) != null))) {
		this.conf.tabsContCss = " safari_517_fix";
	}
	
	var base;
	
	// check if api init
	if (conf != null && typeof(conf) == "object" && typeof(conf.tagName) == "undefined") {
		base = conf.parent;
		if (typeof(conf.skin) != "undefined") this.conf.skin = conf.skin;
		if (typeof(conf.mode) != "undefined") this.conf.tabsMode = (conf.mode=="bottom"?"bottom":"top");
		if (typeof(conf.align) != "undefined") this.conf.align = (conf.align=="right"?"right":"left");
		if (typeof(conf.close_button) != "undefined") this.conf.closeButton = window.dhx4.s2b(conf.close_button);
		if (typeof(conf.content_zone) != "undefined") this.conf.contZone = window.dhx4.s2b(conf.content_zone);
		if (typeof(conf.xml) != "undefined") this.conf.autoload.xml = conf.xml;
		if (typeof(conf.json) != "undefined") this.conf.autoload.xml = conf.json; // new in 4.0
		if (typeof(conf.tabs) != "undefined") this.conf.autoload.tabs = conf.tabs;
		if (typeof(conf.onload) != "undefined") this.conf.autoload.onload = conf.onload; // new in 4.0
		if (typeof(conf.arrows_mode) != "undefined") this.conf.autoload.arrows_mode = conf.arrows_mode; // new in 4.1.2
		// deprecated from 4.0
		// conf { height, offset, margin, image_path, href_mode, scroll, forced, size_by_content, auto_size }
	} else {
		base = conf;
	}
	
	// init top container
	window.dhtmlXCellTop.apply(this, [base, conf.offsets]);
	
	// tabsTop override
	if (this.base._ofs != null && this.base._ofs.t != null) this.conf.tabsTop = this.base._ofs.t;
	
	this.tabsMode = document.createElement("DIV");
	this.tabsMode.className = "dhxtabbar_tabs_"+this.conf.tabsMode;
	this.cont.appendChild(this.tabsMode);
	
	this.tabsArea = document.createElement("DIV");
	this.tabsArea.className = "dhxtabbar_tabs dhxtabbar_tabs_"+this.conf.tabsMode;
	this.tabsArea.innerHTML = "<div class='dhxtabbar_tabs_ar_left'><div class='dhxtabbar_arrow_img'></div></div>"+
					"<div class='dhxtabbar_tabs_base'>"+
						"<div class='dhxtabbar_tabs_cont_"+this.conf.align+this.conf.tabsContCss+"'>"+
							"<div class='dhxtabbar_tabs_line'></div>"+
						"</div>"+
					"</div>"+
					"<div class='dhxtabbar_tabs_ar_right'><div class='dhxtabbar_arrow_img'></div></div>";
	
	this.tabsArea.style.top = (this.conf.tabsMode=="top"?this.conf.tabsTop+"px":"auto");
	this.tabsMode.appendChild(this.tabsArea);
	
	// area to move tabs
	this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align] = "0px";
	
	this.tabsArea.childNodes[0].onclick = function() {
		if (that.conf.align == "left") {
			that._moveTabs(1);
		} else {
			that._moveTabs(-1);
		}
	}
	this.tabsArea.childNodes[2].onclick = function() {
		if (that.conf.align == "left") {
			that._moveTabs(-1);
		} else {
			that._moveTabs(1);
		}
	}
	
	this._onTabsAreaClick = function(id) {
		return this._callMainEvent("onTabClose",[id]);
	}
	
	this.tabsArea.onclick = function(e) {
		e = e||event;
		var t = (e.target||e.srcElement);
		while (t != null) {
			if (typeof(t._tabCloseId) != "undefined") {
				if (that._onTabsAreaClick(t._tabCloseId) !== true) return;
				that.t[t._tabCloseId].conf.remove = true;
				that._hideTab(t._tabCloseId);
				t = null;
			} else if (typeof(t._tabId) != "undefined") {
				that._doOnClick(t._tabId);
				t = null;
			}
			if (t != null) {
				t = t.parentNode;
				if (t == this) t = null;
			}
		}
	}
	
	this.tabsArea.onselectstart = function(e) {
		e = e||event;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
	}
	
	this._doOnClick = function(id) {
		// onBeforeEvent here
		this.callEvent("onTabClick", [id, this.conf.lastActive]);
		if (this.t[id].conf.enabled) this._setTabActive(id);
	}
	
	this.t = {};
	
	this.addTab = function(id, text, width, position, active, close) {
		
		// close = show close button, false by default
		// true - show_closeButton
		// false - do not show (ignoring enableTabCloseButton)
		// not set - depending on enableTabCloseButton
		close = (typeof(close)=="undefined"?(this.conf.closeButton==true):window.dhx4.s2b(close));
		active = window.dhx4.s2b(active);
		
		var tab = document.createElement("DIV");
		tab.className = "dhxtabbar_tab";
		tab.innerHTML = "<div class='dhxtabbar_tab_text"+(close?" dhxtabbar_tab_text_close":"")+"'>"+text+"</div>"+
				(close?"<div class='dhxtabbar_tab_close'></div>":"");
		
		tab._tabId = id;
		if (close) tab.childNodes[1]._tabCloseId = id; // close button
		
		var p = this.tabsArea.childNodes[1].firstChild;
		
		if (position != null && position+1 > 0 && position+1 < p.childNodes.length) { // 1st item - line
			p.insertBefore(tab, p.childNodes[position+1]);
		} else {
			p.appendChild(tab);
		}
		
		
		// width
		var autowidth = false;
		if (typeof(width) == "undefined" || width == null || width == "*") {
			width = this._getLabelWidth(text, close);
			autowidth = true;
		} else {
			width = parseInt(width);
		}
		
		tab.style.width = width+"px";
		
		var cell = new dhtmlXTabBarCell(id, this);
		this.tabsMode.appendChild(cell.cell);
		
		this.t[id] = {
			tab: tab,
			cell: cell,
			conf: {
				text: text,
				visible: true,
				active: false,
				enabled: true,
				close: close,
				width: width,
				autowidth: autowidth
			}
		};
		
		p = cell = null;
		
		if (active) {
			this._setTabActive(id);
		} else {
			this._adjustCell(id);
		}
		
		if (this.conf.initSeq != true && this.conf.arwMode == "auto") this.setSizes();
	}
	
	this.setSizes = function() {
		
		// celltop
		this._adjustCont();
		
		if (this.conf.tabsAreaOfs == null) {
			this.tabsArea.style.width = this.cont.offsetWidth+"px";
			this.conf.tabsAreaOfs = parseInt(this.tabsArea.style.width)-this.tabsArea.offsetWidth;
		}
		this.tabsArea.style.width = this.cont.offsetWidth+this.conf.tabsAreaOfs+"px";
		
		this._adjustCell(this.conf.lastActive);
		this._adjustTabs(true);
		
		this.callEvent("_onSetSizes", []);
		
	}
	
	this._adjustCell = function(id) {
		
		// adjust specified cell or active
		
		if (!this.conf.contZone || id == null) return;
		
		var y = (this.conf.tabsMode=="top"?this.tabsArea.offsetHeight:0)+this.conf.tabsTop;
		var h = this.cont.offsetHeight-this.tabsArea.offsetHeight-this.conf.tabsTop;
		
		// if layout attached - move a bit
		var t = this.t[id].cell.dataType;
		if (this.conf.skin == "dhx_skyblue" && (t == "layout" || t == "tabbar" || t == "acc")) {
			if (this.conf.tabsMode == "top") y = y - 1; // only for top
			h = h + 1; // always
		}
		
		if (id != this.conf.lastActive) {
			y = -5000;
			this.t[id].cell.cell.style.visibility = "hidden";
			this.t[id].cell.cell.style.zIndex = 0;
		}
		this.t[id].cell._setSize(0, y, this.cont.offsetWidth, h);

	}
	
	this.setTabsMode = function(mode) {
		// new
		this.conf.tabsMode = (mode=="bottom"?"bottom":"top");
		this.tabsMode.className = "dhxtabbar_tabs_"+this.conf.tabsMode;
		this.tabsArea.className = "dhxtabbar_tabs dhxtabbar_tabs_"+this.conf.tabsMode;
		this.tabsArea.style.top = (this.conf.tabsMode=="top"?this.conf.tabsTop+"px":"auto");
		this.setSizes();
	}
	
	// generate tab css depending on actv/en state
	this._tabCss = function(id, hidden) {
		var a = this.t[id].conf.active;
		var d = !this.t[id].conf.enabled;
		var h = !this.t[id].conf.visible;
		return "dhxtabbar_tab"+(h?" dhxtabbar_tab_hidden":(a||d?" dhxtabbar_tab"+(a?"_actv":"")+(d?"_dis":""):""));
	}
	
	// calculate tab width depending on text and close button
	this._getLabelWidth = function(text, close) {
		
		if (this.tabsTextTest == null) {
			this.tabsTextTest = document.createElement("SPAN");
			this.tabsTextTest.className = "dhxtabbar_tabs_text_test_"+this.conf.skin;
		}
		
		document.body.appendChild(this.tabsTextTest);
		this.tabsTextTest.innerHTML = text;
		var w = this.tabsTextTest.offsetWidth;
		if (window.dhx4.isIE && w == 0) w = this.tabsTextTest.offsetWidth; // strange IE bug
		
		// some extra width
		w += this.conf.tabsWidth[this.conf.skin][0];
		if (close == true) w += this.conf.tabsWidth[this.conf.skin][1];
		
		//
		document.body.removeChild(this.tabsTextTest);
		return w;
	}
	
	// if tabs overflow left/right side, adjust active tab position
	this._adjustTabs = function(fixTabsArea) {
		
		if (this._checkArrows() == true || fixTabsArea == true) {
			this.tabsArea.childNodes[1].style.left = this.tabsArea.childNodes[0].offsetWidth-1+"px";
			this.tabsArea.childNodes[1].style.width = Math.max(0, this.tabsArea.clientWidth-this.tabsArea.childNodes[0].offsetWidth-this.tabsArea.childNodes[2].offsetWidth)+this.conf.tabsOfs*2+"px"; // minus 2 arrows
		}
		
		var p = this.tabsArea.childNodes[1];
		if (p.offsetWidth < 5) {
			p = null;
			return;
		}
		
		var x = parseInt(p.childNodes[0].style[this.conf.align]);
		
		var k = null;
		for (var q=0; q<p.childNodes[0].childNodes.length; q++) {
			var id = p.childNodes[0].childNodes[q]._tabId;
			if (id != null && this.t[id].conf.visible) {
				var w = this.t[id].tab.offsetWidth-this.conf.tabsOfs;
				if (this.t[id].conf.active) {
					if (x < 0 || p.offsetWidth<w) {
						k = {d: 1, id: id}; // tab hidden on left side, move to right OR tab width less than space available
					} else if (x+w > p.offsetWidth) {
						k = {d:-1, id: id}; // overflow on right
					}
				}
				x += w;
			}
		}
		
		if (k != null) {
			// move selected tab to visible space
			this._moveTabs(k.d, k.id);
		} else if (p.offsetWidth > x+1) {
			// check space on right side
			p.childNodes[0].style[this.conf.align] = Math.min(0, parseInt(p.childNodes[0].style[this.conf.align])+(p.offsetWidth-x))+"px";
		}
		
		p = k = null;
		
	}
	
	// tabs scrolling
	this._moveTabs = function(d, tabId) {
		
		// get all visible tabs
		var p = this.tabsArea.childNodes[1].childNodes[0];
		var i = 0;
		var tabs = [];
		var tabInd = null; // index of tabId
		for (var q=0; q<p.childNodes.length; q++) {
			var id = p.childNodes[q]._tabId;
			if (id != null && this.t[id].conf.visible) {
				tabs.push({id: id, w: this.t[id].tab.offsetWidth-this.conf.tabsOfs, ind: i});
				if (id == tabId) tabInd = i;
				i++;
			}
		}
		
		// find 1st/last full visible tabs or null
		var x = parseInt(this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align]);
		var totalSpace = this.tabsArea.clientWidth-this.tabsArea.childNodes[0].offsetWidth-this.tabsArea.childNodes[2].offsetWidth+this.conf.tabsOfs;
		
		var f = null;
		var l = null;
		
		for (var q=0; q<tabs.length; q++) {
			tabs[q].x = x;
			if (f == null && x >= 0 && x+tabs[q].w > 0) f = tabs[q];
			if (x < totalSpace && x+tabs[q].w <= totalSpace) l = tabs[q];
			x += tabs[q].w;
		}
		
		if (tabInd != null) {
			
			var t = tabs[tabInd];
			
		} else {
			
			var t = null;
			if (d > 0) {
				// left arrow clicked
				// find prev tab (for 1st visible) or last (if 1st is null)
				if (f == null) {
					if (tabs.length > 0) t = tabs[tabs.length-1];
				} else {
					if (f.ind > 0 && tabs.length >= f.ind) t = tabs[f.ind-1];
				}
				
			} else {
				// right arrow clicked
				// find next tab (for last visible) or first (if last-visible is null)
				if (l == null) {
					if (tabs.length > 0) t = tabs[0];
				} else {
					if (tabs.length > l.ind) t = tabs[l.ind+1];
				}
				
			}
		}
		
		// move prev/last tab to 1st position
		if (t != null) {
			if (d > 0) {
				if (x < totalSpace) {
					// some tabs are on left and some space left on right
					p.style[this.conf.align] = Math.min(0, parseInt(p.style[this.conf.align])+(totalSpace-x))+"px";
				} else {
					// show tab on left
					p.style[this.conf.align] = parseInt(p.style[this.conf.align])-t.x+"px";
				}
			} else {
				p.style[this.conf.align] = parseInt(p.style[this.conf.align])-t.x+totalSpace-t.w+"px";
			}
		}
		
		p = t = tabs = null;
		
	}
	
	// return next visible related to tab-id
	this._getNextVisible = function(id, getFirst) {
		return this._getNearVisible(id, getFirst, "next");
	}
	
	// return prev visible related to tab-id
	this._getPrevVisible = function(id, getFirst) {
		return this._getNearVisible(id, getFirst, "previous");
	}
	
	// get first visible
	this._getFirstVisible = function() {
		return this._getNearVisible(null, false, "first");
	}
	
	this._getNearVisible = function(id, getFirst, mode) {
		
		if (mode == "first") {
			var node = this.tabsArea.childNodes[1].childNodes[0].childNodes[1]; // firstChild is line
			mode = "next";
		} else {
			if (id == null || this.t[id] == null) return (getFirst?this._getFirstVisible():null);
			var node = this.t[id].tab[mode+"Sibling"];
		}
		
		var tabId = null;
		
		while (node != null && tabId == null) {
			var k = node._tabId;
			if (k != null && tabId == null && this.t[k].conf.visible) {
				tabId = k;
			} else {
				node = node[mode+"Sibling"];
			}
		}
		
		node = null;
		
		return tabId;
	}
	
	
	
	this._showTab = function(id, activate) { // activate true/false
		
		if (this.t[id] == null) return;
		
		if (this.t[id].conf.transActv == true) {
			if (this.t[id].conf.transMode == "show") return;
		} else {
			if (this.t[id].conf.visible == true) return;
		}
		
		// get next tab
		// move
		// show prev+set marg to -1
		// get next/prev tabs
		
		if (this.conf.transProp !== false) {
			
			// slide effect
			this.t[id].conf.transActv = true;
			this.t[id].conf.transMode = "show";
			this.t[id].conf.transProp = this.conf.transProp;
			this.t[id].conf.transActvId = (activate?id:null);
			
			if (!this.t[id].conf.transEv) {
				this.t[id].tab.addEventListener(this.conf.transEv, this._doOnTrEnd, false);
				this.t[id].conf.transEv = true;
			}
			
			this.t[id].conf.visible = true;
			this.t[id].tab.className = this._tabCss(id);
			
			this.t[id].tab.style[this.conf.transProp] = this.conf.transValueWidth;
			this.t[id].tab.style.width = this.t[id].conf.width+"px";
			
			if (this.t[id].tab.clientWidth >= this.t[id].conf.width) {
				this.t[id].tab.style.visibility = "visible";
			}
			
		} else {
			this.t[id].conf.visible = true;
			this.t[id].tab.style.display = "";
			
			if (activate || this.t[id].conf.active) {
				this.t[id].conf.active = false;
				this._setTabActive(id);
			} else {
				this._adjustTabs();
			}
		}
	}
	
	this._hideTab = function(id, activateId) { // activateId - tab to activate
		
		// activateId
		// if set to true, selection jump from current tab to nearest one (old logic)
		// activateId can also be id of any other tab (new logic)
		
		if (this.t[id] == null) return;
		
		if (this.t[id].conf.transActv == true) {
			if (this.t[id].conf.transMode == "hide") return;
		} else {
			if (this.t[id].conf.visible != true) return;
		}
		
		// if tab was active clear flags/css
		var lastActive = false;
		if (this.conf.lastActive == id) {
			this.conf.lastActive = null;
			this.t[id].conf.active = false;
			this.t[id].tab.className = this._tabCss(id);
			lastActive = true;
		}
		
		// get next/prev tabs
		var prev = this._getPrevVisible(id);
		var next = this._getNextVisible(id);
		
		var actvId = (lastActive == true && activateId !== false ? (activateId==true?null:activateId)||next||prev : null);
		
		// hide and move next tab to left if any
		if (this.conf.transProp !== false) {
			
			this.t[id].conf.transActv = true;
			this.t[id].conf.transMode = "hide";
			this.t[id].conf.transProp = this.conf.transProp;
			this.t[id].conf.transActvId = actvId;
			this.t[id].conf.visible = false;
			
			if (!this.t[id].conf.transEv) {
				this.t[id].tab.addEventListener(this.conf.transEv, this._doOnTrEnd, false);
				this.t[id].conf.transEv = true;
			}
			
			this.t[id].tab.style.visibility = "hidden";
			this.t[id].tab.className = that._tabCss(id);
			this.t[id].tab.style[this.conf.transProp] = this.conf.transValueWidth;
			this.t[id].tab.style.width = "0px";
			
		} else {
			
			this.t[id].tab.style.display = "none";
			this.t[id].conf.visible = false;
			if (this.conf.contZone) {
				this.t[id].cell.cell.style.visibility = "hidden";
				this.t[id].cell.cell.style.top = "-5000px"; // "vis:hid" > "vis:vis" http://www.w3.org/TR/CSS2/visufx.html#visibility
			}
			
			if (actvId != null) this._setTabActive(actvId);
			this._adjustTabs();
			
			if (this.t[id].conf.remove) this._removeTab(id);
		}
		
	}
	
	this._isTabVisible = function(id) {
		return (this.t[id].conf.visible==true);
	}
	
	this._doOnTrEnd = function() {
		
		var id = this._tabId; // this points to tab
		
		if (that.t[id] == null) return;
		
		var t = that.t[id];
		var actvId = t.conf.transActvId;
		
		if (t.conf.transMode == "hide") {
			
			// remove if any
			if (t.conf.remove) {
				that._removeTab(id);
			} else {
				
				t.tab.style[t.conf.transProp] = "";
				
				if (that.conf.contZone) {
					t.cell.cell.style.visibility = "hidden";
					t.cell.cell.style.top = "-5000px";
				}
				
				t.conf.transActv = false;
				
			}
			
		} else if (t.conf.transMode == "show") {
			
			t.tab.style[t.conf.transProp] = "";
			t.tab.style.visibility = "visible";
			
			t.conf.transMode = null;
			t.conf.transActv = false;
			
		}
		
		if (actvId != null) {
			that._setTabActive(actvId);
		} else {
			that._adjustTabs();
		}
		
		t = null;
		
	}
	
	
	this.enableTabCloseButton = function(mode) {
		this.conf.closeButton = window.dhx4.s2b(mode);
	}
	
	this.unload = function() {
		
		this.conf.unloading = true;
		
		this.clearAll(); // remove all tabs
		this.t = null;
		
		if (this.tabsTextTest != null) {
			if (this.tabsTextTest.parentNode) this.tabsTextTest.parentNode.removeChild(this.tabsTextTest);
			this.tabsTextTest = null;
		}
		
		// clear data loading
		window.dhx4._enableDataLoading(this, null, null, null, "clear");
		
		this.tabsArea.childNodes[0].onclick = null;
		this.tabsArea.childNodes[2].onclick = null;
		this.tabsArea.onclick = null;
		this.tabsArea.onselectstart = null;
		this.tabsArea.parentNode.removeChild(this.tabsArea);
		this.tabsArea = null;
		
		this.tabsMode.parentNode.removeChild(this.tabsMode);
		this.tabsMode = null;
		
		this._unloadTop();
		
		// clear events
		window.dhx4._eventable(this, "clear");
		
		for (var a in this) this[a] = null;
		that = null;
	}
	
	this.enableContentZone = function(mode) {
		// enables/disables the content zone (enabled by default)
		// call before tabs adding
		this.conf.contZone = (mode==true);
	}
	
	this.setSkin = function(skin) {
		
		// sets style used for tabbar
		this._setBaseSkin(skin);
		this.conf.skin = skin;
		
		if (this.tabsTextTest != null) this.tabsTextTest.className = "dhxtabbar_tabs_text_test_"+this.conf.skin;
		
		for (var a in this.t) {
			
			// reset autosaved data for padding/border
			this.t[a].cell._resetSizeState();
			
			// tab width
			if (this.t[a].conf.autowidth == true) {
				this.t[a].conf.width = this._getLabelWidth(this.t[a].conf.text, this.t[a].conf.close);
				if (this.t[a].conf.visible) this.t[a].tab.style.width = this.t[a].conf.width+"px";
			}
			
		}
		
		this.conf.tabsAreaOfs = null;
		this._fixTabsOfs();
		
		this.setSizes();
	}
	
	this.setAlign = function(align) {
		
		align = (align=="left"?"left":"right");
		if (align == this.conf.align) {
			this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align] = "0px";
			return;
		}
			
		if (this.conf.transProp !== false) {
			this.tabsArea.childNodes[1].childNodes[0].style[this.conf.transProp] = "";
		}
		this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align] = "";
		
		this.conf.align = align;
		this.tabsArea.childNodes[1].childNodes[0].className = "dhxtabbar_tabs_cont_"+this.conf.align+this.conf.tabsContCss;
		this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align] = "0px";
		
		if (this.conf.transProp !== false) {
			this.conf.transValuePos = this.conf.align+" "+this.conf.transSpeed;
			this.tabsArea.childNodes[1].childNodes[0].style[this.conf.transProp] = this.conf.transValuePos;
		}
	}
	
	this._initObj = function(data) {
		
		this.conf.initSeq = true;
		
		this.clearAll();
		
		var viaAjax = false;
		
		// settings
		if (data.settings != null) {
			if (data.settings.skin != null) this.setSkin(data.settings.skin);
			if (data.settings.close_button != null) { // added in 4.6
				this.enableTabCloseButton(window.dhx4.s2b(data.settings.close_button));
			} else if (data.settings.closeButton != null) { // deprecated from 4.6
				this.enableTabCloseButton(window.dhx4.s2b(data.settings.closeButton));
			}
			if (data.settings.align != null) this.setAlign(data.settings.align);
			if (data.settings.hrefmode == "ajax") viaAjax = true;
			if (data.settings.hrefmode == "ajax-html") { viaAjax = true; this.conf.url_demand = true; } // ajax-html back in 4.2
		}

		// tabs
		if (data.tabs != null) {
			for (var q=0; q<data.tabs.length; q++) {
				var t = data.tabs[q];
				if (typeof(t.id) == "undefined") t.id = window.dhx4.newId();
				if (!isNaN(parseInt(t.width))) { t.width = parseInt(t.width); } else { t.width = null; }
				this.addTab(t.id, t.text||t.label||"", t.width, t.index, window.dhx4.s2b(t.selected)||window.dhx4.s2b(t.active), t.close);
				if (t.content != null) {
					this.cells(t.id).attachHTMLString(t.content);
				} else if (t.href != null) {
					if (this.conf.url_demand == true) {
						this.conf.urls[t.id] = {href: t.href, ajax: viaAjax};
					} else {
						this.cells(t.id).attachURL(t.href, viaAjax);
					}
				}
				if (typeof(t.enabled) != "undefined" && window.dhx4.s2b(t.enabled) == false) {
					this.tabs(t.id).disable();
				} else if (typeof(t.disabled) != "undefined" && window.dhx4.s2b(t.disabled) == true) {
					this.tabs(t.id).disable();
				}
			}
		}
		
		this.conf.initSeq = false;
		if (this.conf.arwMode == "auto") this.setSizes();
		
		// check url on demand to load
		if (this.conf.url_demand == true) this._loadURLOnDemand(this.conf.lastActive);
		
	}
	
	this._xmlToObj = function(data) {
		
		var obj = { settings: {}, tabs: [] };
		var r = data.getElementsByTagName("tabbar")[0];
		
		if (r != null) {
			
			// settings
			for (var a in {skin:1, align:1, closeButton:1, hrefmode:1}) {
				if (r.getAttribute(a) != null) obj.settings[a] = r.getAttribute(a);
			}
			
			// tabs
			var t = r.getElementsByTagName("tab");
			for (var q=0; q<t.length; q++) {
				
				var tab = { text: (t[q].firstChild.nodeValue||"") };
				
				// attrs
				for (var a in {id:1, width:1, close:1, selected:1, active:1, enabled:1, disabled:1, href:1}) {
					if (t[q].getAttribute(a) != null) tab[a] = t[q].getAttribute(a);
				}
				
				// content
				var cont = t[q].getElementsByTagName("content")[0];
				if (cont != null) {
					tab.content = "";
					for (var w=0; w<cont.childNodes.length; w++) tab.content += (cont.childNodes[w].nodeValue||"");
				}
				
				obj.tabs.push(tab);
			}
		}
		
		return obj;
	}
	
	dhx4._enableDataLoading(this, "_initObj", "_xmlToObj", "tabbar", {struct:true});
	
	// check for transition support
	var k = window.dhx4.transDetect();
	
	this.conf.transProp = k.transProp;
	this.conf.transEv = k.transEv;
	this.conf.transValueWidth = "width "+this.conf.transSpeed;
	
	k = null;
	
	if (this.conf.transProp !== false) {
		this.conf.transValuePos = this.conf.align+" "+this.conf.transSpeed;
		this.tabsArea.childNodes[1].childNodes[0].style[this.conf.transProp] = this.conf.transValuePos;
	}
	
	this._callMainEvent = function(name, args) {
		return this.callEvent(name, args);
	}
	
	window.dhx4._eventable(this);
	
	if (this.conf.autoload.json != null) {
		this.loadStruct(this.conf.autoload.json, this.conf.autoload.onload);
	} else if (this.conf.autoload.xml != null) {
		this.loadStruct(this.conf.autoload.xml, this.conf.autoload.onload);
	} else if (this.conf.autoload.tabs != null) {
		this.loadStruct({tabs:this.conf.autoload.tabs}, this.conf.autoload.onload);
	}
	if (this.conf.autoload.arrows_mode != null) {
		this.setArrowsMode(this.conf.autoload.arrows_mode);
	}
	
	
	this._fixTabsOfs();
	this.setSizes();
	
	return this;
	
};

// top-level extensions
dhtmlXTabBar.prototype = new dhtmlXCellTop();

dhtmlXTabBar.prototype._fixTabsOfs = function() {
	this.conf.tabsOfs = ({dhx_skyblue:1,dhx_web:0,dhx_terrace:1,material:0}[this.conf.skin]);
};

/* cell access */
dhtmlXTabBar.prototype.cells = dhtmlXTabBar.prototype.tabs = function(id) {
	if (this.t[id]) return this.t[id].cell;
	return null;
};

dhtmlXTabBar.prototype.getAllTabs = function() {
	var t = [];
	for (var a in this.t) t.push(a);
	return t;
};

/* set/get active, tab switch */
dhtmlXTabBar.prototype._setTabActive = function(id, mode) {
	
	// mode - if set to true - call onSelect event handler (true by default)
	
	if (!this.t[id] || this.t[id].conf.active) return;
	
	if (typeof(mode) == "undefined") mode = true;
	if (mode == true && this.callEvent("onSelect", [id, this.conf.lastActive]) !== true) return;
	
	this.setTabInActive();
	
	this.t[id].conf.active = true;
	if (this.conf.contZone) {
		this.t[id].cell.cell.style.visibility = "visible";
		this.t[id].cell.cell.style.top = "0px";
		this.t[id].cell.cell.style.zIndex = 1;
	}
	this.t[id].tab.className = this._tabCss(id);
	this.conf.lastActive = id;
	this.setSizes();
	
	if (this.conf.url_demand == true) this._loadURLOnDemand(id);
	
};

dhtmlXTabBar.prototype.setTabInActive = function() {
	
	if (this.conf.lastActive != null && this.t[this.conf.lastActive]) {
		this.t[this.conf.lastActive].conf.active = false;
		if (this.conf.contZone) {
			this.t[this.conf.lastActive].cell.cell.style.visibility = "hidden";
			this.t[this.conf.lastActive].cell.cell.style.top = "-5000px";
			this.t[this.conf.lastActive].cell.cell.style.zIndex = 0;
		}
		this.t[this.conf.lastActive].tab.className = this._tabCss(this.conf.lastActive);
		this.conf.lastActive = null;
	}
	
};

dhtmlXTabBar.prototype._isTabActive = function(id) {
	return (id == this.conf.lastActive && this.conf.lastActive != null);
};

dhtmlXTabBar.prototype.getActiveTab = function() {
	return this.conf.lastActive;
};

dhtmlXTabBar.prototype.goToNextTab = function() {
	var id = this._getNextVisible(this.conf.lastActive, true);
	if (id != null) this._setTabActive(id);
};

dhtmlXTabBar.prototype.goToPrevTab = function() {
	var id = this._getPrevVisible(this.conf.lastActive, true);
	if (id != null) this._setTabActive(id);
};



/* enable/disable */
dhtmlXTabBar.prototype._enableTab = function(id, mode) {
	
	// mode - set to true to select tab
	
	if (!this.t[id] || this.t[id].conf.enabled) return;
	this.t[id].conf.enabled = true;
	this.t[id].tab.className = this._tabCss(id);
	
	if (mode == true) this._setTabActive(id);
	
};

dhtmlXTabBar.prototype._disableTab = function(id, activateId) {
	
	// old code have 2nd param in descr but not in script, will added (logic the same as for hideTab)
	// activateId - if set to true, selection jump from current tab to nearest one, or you can specify tab id
	
	if (!this.t[id] || !this.t[id].conf.enabled) return;
	this.t[id].conf.enabled = false;
	this.t[id].tab.className = this._tabCss(id);
	
	if (activateId !== false && this.conf.lastActive == id) {
		if (activateId == true) activateId = this._getNextVisible(id)||this._getPrevVisible(id);
		this._setTabActive(activateId);
	}
	
};

dhtmlXTabBar.prototype._isTabEnabled = function(id) {
	return (this.t[id] != null && this.t[id].conf.enabled==true);
};

/* set/get label */
dhtmlXTabBar.prototype._setTabText = function(id, text, width) {
	
	if (!this.t[id]) return;
	
	var autowidth = false;
	if (typeof(width) == "undefined" || width == null) {
		width = this._getLabelWidth(text, this.t[id].conf.close);
		autowidth = true;
	}
	
	this.t[id].tab.style.width = width+"px";
	this.t[id].tab.childNodes[0].innerHTML = text;
	
	this.t[id].conf.text = text;
	this.t[id].conf.width = width;
	this.t[id].conf.autowidth = autowidth;
	
};

dhtmlXTabBar.prototype._getTabText = function(id) {
	if (!this.t[id]) return null;
	return this.t[id].conf.text;
};

/* remove tab/all tabs */
dhtmlXTabBar.prototype._removeTab = function(id, activateId, force) { // force = w/o effect, private?
	
	if (!this.t[id]) return;
	
	if (force != true && this.t[id].conf.remove != true) {
		this.t[id].conf.remove = true;
		this._hideTab(id, activateId);
		return;
	}
	
	if (typeof(activateId) == "undefined") activateId = true;
	
	var next = this._getNextVisible(id);
	var prev = this._getPrevVisible(id);
	
	if (this.t[id].conf.transEv == true) {
		this.t[id].tab.removeEventListener(this.conf.transEv, this._doOnTrEnd, false);
		this.t[id].conf.transEv = false;
	}
	
	for (var a in this.t[id].conf) this.t[id].conf[a] = null;
	this.t[id].conf = null;
	delete this.t[id].conf;
	
	this.t[id].cell._unload();
	this.t[id].cell = null;
	
	this.t[id].tab.parentNode.removeChild(this.t[id].tab);
	this.t[id].tab = null;
	
	this.t[id] = null;
	delete this.t[id];
	
	this.conf.urls[id] = null;
	delete this.conf.urls[id];
	
	if (this.conf.lastActive == id) {
		this.conf.lastActive = null;
		if (activateId != false) {
			var actvId = (activateId == true ? (next||prev||this._getFirstVisible()) : activateId);
			if (actvId != null) this._setTabActive(actvId);
		}
	} else if (force != true) {
		this._adjustTabs();
	}
};

dhtmlXTabBar.prototype.clearAll = function() {
	// remove all tabs
	for (var a in this.t) this._removeTab(a, false, true);
	this.tabsArea.childNodes[1].childNodes[0].style[this.conf.align] = "0px";
};


/* positionig */
dhtmlXTabBar.prototype.moveTab = function(id, index) {
	
	if (!this.t[id] || index < 0) return;
	index += 1; // firstChild is line
	
	var p = this.tabsArea.childNodes[1].firstChild;
	
	if (p.childNodes[index] != this.t[id].tab) {
		p.removeChild(this.t[id].tab);
		if (index >= p.childNodes.length) {
			p.appendChild(this.t[id].tab);
		} else {
			p.insertBefore(this.t[id].tab, p.childNodes[index]);
		}
	}
	p = null;
};

dhtmlXTabBar.prototype._getIndex = function(id) {
	var i = -1;
	var p = this.tabsArea.childNodes[1].firstChild;
	for (var q=1; q<p.childNodes.length; q++) {
		if (p.childNodes[q]._tabId == id) i = q-1; // firstChild is line
	}
	p = null;
	return i;
};

dhtmlXTabBar.prototype.getNumberOfTabs = function(mode) {
	// mode - set to true for visible only (new)
	var p = 0;
	for (var a in this.t) {
		p += (mode!=true?1:(this.t[a].conf.visible==true?1:0));
	}
	return p;
};

dhtmlXTabBar.prototype.forEachCell = dhtmlXTabBar.prototype.forEachTab = function(func) {
	for (var a in this.t) func.apply(window, [this.t[a].cell]);
};

dhtmlXTabBar.prototype.enableAutoReSize = function() {
	this._initFSResize();
};

// added in 4.1
dhtmlXTabBar.prototype.setArrowsMode = function(mode) {
	mode = {auto: "auto", always: "always"}[String(mode)];
	if (mode == null || mode == this.conf.mode) return;
	this.conf.arwMode = mode;
	
	if (mode == "always") {
		this.tabsArea.childNodes[0].className = "dhxtabbar_tabs_ar_left";
		this.tabsArea.childNodes[2].className = "dhxtabbar_tabs_ar_right";
	}
	
	this.setSizes();
};

dhtmlXTabBar.prototype._checkArrows = function() {
	
	var adj = false;
	
	if (this.conf.arwMode == "auto") {
		
		var w = 0;
		for (var a in this.t) w+= this.t[a].tab.offsetWidth;
		
		var arLeft = this.tabsArea.childNodes[0];
		var arRight = this.tabsArea.childNodes[2];
		
		if (w > this.cont.offsetWidth) {
			// show arows
			if (arLeft.className.search(/dhxtabbar_tabs_ar_hidden/) >= 0) {
				arLeft.className = arLeft.className.replace(/\s{0,}dhxtabbar_tabs_ar_hidden/, "");
				arRight.className = arRight.className.replace(/\s{0,}dhxtabbar_tabs_ar_hidden/, "");
				adj = true;
			}
		} else {
			// hide arrows
			if (arLeft.className.search(/dhxtabbar_tabs_ar_hidden/) < 1) {
				arLeft.className += " dhxtabbar_tabs_ar_hidden";
				arRight.className += " dhxtabbar_tabs_ar_hidden";
				adj = true;
			}
		}
		arLeft = arRight = null;
		
	}
	
	return adj;
	
};

// load url on demand, added in 4.2
dhtmlXTabBar.prototype._loadURLOnDemand = function(id) {
	if (id != null && this.conf.urls[id] != null) {
		this.cells(id).attachURL(this.conf.urls[id].href, this.conf.urls[id].ajax);
		this.conf.urls[id] = null;
	}
};
window.dhtmlXTabBarCell = function(id, tabbar) {
	
	dhtmlXCellObject.apply(this, [id, "_tabbar"]);
	
	this.tabbar = tabbar;
	
	this.conf.skin = this.tabbar.conf.skin;
	
	this.conf.tabbar_funcs = {
		show: "_showTab",
		hide: "_hideTab",
		isVisible: "_isTabVisible",
		enable: "_enableTab",
		disable: "_disableTab",
		isEnabled: "_isTabEnabled",
		getIndex: "_getIndex",
		getText: "_getTabText",
		setText: "_setTabText",
		setActive: "_setTabActive",
		isActive: "_isTabActive",
		close: "_removeTab"
	};
	
	this._tabbarCall = function(name) {
		return function(){
			var t = [this._idd];
			for (var q=0; q<arguments.length; q++) t.push(arguments[q]);
			return this.tabbar[name].apply(this.tabbar, t);
		};
	};
	
	for (var a in this.conf.tabbar_funcs) {
		if (typeof(this[a]) != "function") this[a] = this._tabbarCall(this.conf.tabbar_funcs[a]);
	};
	
	
	this.attachEvent("_onCellUnload", function(){
		this.tabbar = null;
		for (var a in this.conf.tabbar_funcs) {
			this[a] = null;
			this.conf.tabbar_funcs[a] = null;
		}
		this.conf.tabbar_funcs = null;
	});
	
	// "onTabContentLoaded" DEPRECATED
	this.attachEvent("_onContentLoaded", function() {
		this.tabbar._callMainEvent("onContentLoaded", arguments);
		this.tabbar._callMainEvent("onTabContentLoaded", arguments);
	});
	
	this.attachEvent("_onContentAttach", function(){
		this.tabbar._adjustCell(this.tabbar.conf.lastActive);
	});
	this.attachEvent("_onBeforeContentAttach", function(dataType) {
		if (dataType == "sidebar" && this.conf.skin != "dhx_skyblue") {
			this._hideBorders();
		}
	});
	
};

window.dhtmlXTabBarCell.prototype = new dhtmlXCellObject();

dhtmlXCellObject.prototype.attachTabbar = function(conf) {
	
	this.callEvent("_onBeforeContentAttach",["tabbar"]);
	
	// 3.6 init - attachTabbar(mode)
	if (typeof(conf) == "string") {
		conf = {mode:conf};
	} else if (typeof(conf) != "object" || conf == null) {
		conf = {};
	}
	
	var obj = document.createElement("DIV");
	obj.style.width = "100%";
	obj.style.height = "100%";
	obj.style.position = "relative";
	obj.style.overflow = "hidden";
	
	// acc, move tabbar 1px-up to hide top borders
	if (typeof(window.dhtmlXAccordionCell) == "function" && this instanceof window.dhtmlXAccordionCell) {
		if (this.conf.skin == "material") {
			obj._ofs = {t:-1,r:-1,b:-1,l:-1}; // attach tabbar to acc
		} else {
			obj._ofs = {t:-1};
		}
	}
	
	if (typeof(window.dhtmlXTabBarCell) == "function" && this instanceof window.dhtmlXTabBarCell) {
		if (this.conf.skin == "dhx_skyblue") obj._ofs = {t:-1,r:-1,b:-1,l:-1};
		if (this.conf.skin == "material") obj._ofs = {t:8,r:8,b:8,l:8};
	}
	
	// sidebar, move tabbar 1px-left
	if (typeof(window.dhtmlXSideBarCell) == "function" && this instanceof window.dhtmlXSideBarCell) {
		obj._ofs = {l:-1};
		if (this.conf.skin == "dhx_web" && this.sidebar.conf.autohide == true) obj._ofs.l = 0;
		if (this.conf.skin == "dhx_terrace") {
			if (this.sidebar.conf.autohide == true) obj._ofs.l = 0;
			if (this.sidebar.conf.header == true) obj._ofs.t = -1;
		}
	}
	
	// carousel
	if (typeof(window.dhtmlXCarouselCell) == "function" && this instanceof window.dhtmlXCarouselCell) {
		this._hideBorders();
	}
	
	this._attachObject(obj);
	
	conf.skin = this.conf.skin;
	conf.parent = obj;
	
	this.dataType = "tabbar";
	this.dataObj = new dhtmlXTabBar(conf);
	
	conf.parent = obj = null;
	conf = null;
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
};

