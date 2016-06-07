/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXCarousel(conf, effect, skin) {
	
	if (typeof(conf) == "string" || (typeof(conf) == "object" && typeof(conf.tagName) != "undefined")) {
		conf = {
			parent: (typeof(conf)=="string"?document.getElementById(conf):conf),
			effect: effect,
			skin: skin,
			delete_conf: true
		};
	} else {
		// object-api
		if (typeof(conf) == "undefined" || conf == null) {
			conf = {};
		}
	}
	
	this.conf = {
		skin: (conf.skin||window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxcarousel")||"material"),
		css: "dhxcarousel", // css prefix for topcell mtb
		// misc
		items_count: 0,
		selected: -1, // selected index
		// dimensions
		item_width: Number(conf.item_width)||"auto",
		item_height: Number(conf.item_height)||"auto",
		ofs_item: Number(conf.offset_item)||1,
		ofs_left: Number(conf.offset_left)||0,
		ofs_top: Number(conf.offset_top)||0,
		// controls
		buttons: (typeof(conf.buttons)=="undefined"?true:window.dhx4.s2b(conf.buttons)), // show left/right arrows
		drops: false, // show rectangle for each cell
		// keys and touch events
		keys: (typeof(conf.keys)=="undefined"?true:window.dhx4.s2b(conf.keys)), // enable crtl+left/right
		key_data: {left: 37, right: 39},
		touch_scroll: (typeof(conf.touch_scroll)!="undefined"?window.dhx4.s2b(conf.touch_scroll):true), // scroll cells with touch
		// arrows
		arw: ["&#9668;", "&#9658;"]
	};
	
	this.conf.autowidth = (this.conf.item_width=="auto");
	this.conf.autoheight = (this.conf.item_height=="auto");
	
	// check for transition support
	var k = window.dhx4.transDetect();
	this.conf.transProp = k.transProp;
	this.conf.transEv = k.transEv;
	
	// load effect
	this.conf.anim_type = (conf.effect||"slide");
	if (this.ef[this.conf.anim_type] == true) {
		var t = this["_"+this.conf.anim_type+"_init"]();
		if (t === false) {
			this.conf.anim_type = "slide";
			t = this["_"+this.conf.anim_type+"_init"]();
		}
		if (typeof(t) == "object") {
			for (var a in t) {
				if (typeof(this.conf[a]) == "undefined") this.conf[a] = t[a];
			};
		}
	}
	this.conf.anim_f = this["_"+this.conf.anim_type+"_f"]();
	
	
	var that = this;
	
	window.dhtmlXCellTop.apply(this, [conf.parent, conf.offsets]);
	
	this.area = document.createElement("DIV");
	this.area.className = "dhxcarousel_area";
	this.cont.appendChild(this.area);
	
	if (typeof(window.addEventListener) == "function" && that.conf.touch_scroll == true) {
		
		this._doOnTouchStart = function(e) {
			
			if (window.dhx4.dnd._mTouch(e) == true) return;
			
			if (that.conf.animating == true) return;
			
			if (e.preventDefault) e.preventDefault();
			
			that.area.className += " dhxcarousel_area_dnd";
			
			that.conf.touch_conf = {
				t: new Date().getTime(),
				dx: null,
				dy: null
			};
			
			if (e.type.match(/^touch/) != null) {
				that.conf.touch_conf.id = e.touches[0].identifier;
				that.conf.touch_conf.x = e.touches[0].clientX;
				that.conf.touch_conf.y = e.touches[0].clientY;
			} else {
				that.area.style.touchAction = that.area.style.msTouchAction = "none";
				that.conf.touch_conf.x = e.clientX;
				that.conf.touch_conf.y = e.clientY;
			}
			
			window.addEventListener(window.dhx4.dnd.evs.move, that._doOnTouchMove, false);
			window.addEventListener(window.dhx4.dnd.evs.end, that._doOnTouchEnd, false);
			
		}
		
		this._doOnTouchMove = function(e) {
			
		}
		
		this._doOnTouchEnd = function(e) {
			
			if (e.type.match(/^touch/) != null) {
				var ofsX = 0;
				for (var q=0; q<e.changedTouches.length; q++) {
					if (e.changedTouches[q].identifier == that.conf.touch_conf.id) {
						ofsX = that.conf.touch_conf.x-e.changedTouches[q].clientX;
					}
				}
			} else {
				var ofsX = that.conf.touch_conf.x-e.clientX;
			}
			
			window.removeEventListener(window.dhx4.dnd.evs.move, that._doOnTouchMove, false);
			window.removeEventListener(window.dhx4.dnd.evs.end, that._doOnTouchEnd, false);
			
			that.area.className = that.area.className.replace(/\s*dhxcarousel_area_dnd$/,"");
			
			if (ofsX == 0 || new Date().getTime() - that.conf.touch_conf.t > 400) return;
			
			var dir = ofsX/Math.abs(ofsX);
			that._animateStart(dir);
			
		}
		
		this.area.addEventListener(window.dhx4.dnd.evs.start, this._doOnTouchStart, false);
		
	}
	
	
	this.cdata = {}; // id -> data
	this.ind = {};   // index -> id
	
	this.addCell = function(id, index) {
		
		this.conf.items_count++;
		
		if (this.conf.selected == -1) this.conf.selected = 0; // force select 1st cell
		
		this.setSizes();
		this._checkControls();
		
		// detect index
		if (typeof(index) == "undefined" || index == null) {
			index = this.conf.items_count-1;
		} else if (index < 0) {
			index = 0;
		} else if (index > this.conf.items_count-1) {
			index = this.conf.items_count-1;
		}
		
		// middle-ins, move items after index to right and change position
		for (var a in this.cdata) {
			if (this.cdata[a].index >= index) {
				this.cdata[a].index++;
				this.ind[this.cdata[a].index] = a;
				this._adjustCell(a);
			}
		}
		
		// insert new
		if (id == null) id = String(window.dhx4.newId());
		while (this.cdata[id] != null) id = String(window.dhx4.newId());
		
		var cell = new dhtmlXCarouselCell(id, this);
		if (this.area.childNodes[index] != null) {
			this.area.insertBefore(cell.cell, this.area.childNodes[index]);
		} else {
			this.area.appendChild(cell.cell);
		}
		
		// add cell
		this.cdata[id] = {index: index, cell: cell};
		this.ind[index] = id;
		
		this._adjustCell(id);
		
		this._addBar();
		this._setBarIndex(this.conf.selected);
		
		cell = null;
		
		this[this.conf.anim_f.cell_added](id);
		
		return id;
		
	}
	
	this._removeCell = function(id) {
		
		var index = this.cdata[id].index;
		
		this.cdata[id].cell._unload();
		this.cdata[id].index = this.cdata[id].cell = null;
		this.cdata[id] = null;
		
		delete this.cdata[id];
		delete this.ind[index];
		
		this.conf.items_count--;
		
		if (this.conf.unloading == true) return;
		
		// recalc index for existing cells
		this.ind = {};
		var m = 0;
		for (var a in this.cdata) {
			if (this.cdata[a].index > index) this.cdata[a].index--;
			this.ind[this.cdata[a].index] = a;
		}
		
		// update selected index
		var upd = false; // update cell if index changed automaticaly
		if (this.conf.selected > index) {
			this.conf.selected--;
		} else if (this.conf.selected == index) {
			this.conf.selected = Math.min(this.conf.selected, this.conf.items_count-1);
			upd = true;
		} else {
			// do nothing
		}
		
		this._removeBar(false);
		this._setBarIndex(this.conf.selected);
		
		if (upd == true) {
			if (this.conf.selected >= 0) {
				this[this.conf.anim_f.update_selected](this.ind[this.conf.selected]); // cell became active, maybe some updates needed
			}
		}
		
		this.setSizes();
		this._checkControls();
	};
	
	this.setSizes = function() {
		
		// celltop
		this._adjustCont();
		
		var sizes = {};
		
		// if parent was resizes
		this.area.style.height = this.cont.offsetHeight-this.controls.offsetHeight+"px";
		
		this.conf.width =  (this.conf.autowidth?this.cont.offsetWidth-this.conf.ofs_left*2:this.conf.item_width);
		this.conf.height = (this.conf.autoheight?this.area.offsetHeight-this.conf.ofs_top*2:this.conf.item_height);
		this.conf.top = Math.max(0, (this.conf.autoheight?this.conf.ofs_top:Math.floor((this.area.offsetHeight-this.conf.height)/2)));
		
		this.area.style.width = this[this.conf.anim_f.detect_aw]()*(this.conf.width+this.conf.ofs_item)+this.conf.ofs_item+"px";
		this.area.style.left = Math.round(this.cont.offsetWidth/2-this.conf.width/2-this.conf.ofs_item)+"px";
		
		// items
		for (var a in this.cdata) {
			var s = {};
			for (var b in this.cdata[a].cell.conf.size) s[b] = this.cdata[a].cell.conf.size[b];
			//
			if (this.conf.autowidth == true) {
				s.w = this.conf.width;
				s.x = this[this.conf.anim_f.detect_x](a);
			}
			if (this.conf.autoheight == true) {
				s.h = this.conf.height;
			}
			//
			sizes[a] = s;
		}
		
		this.area.style.left = Math.round(this.cont.offsetWidth/2-this.conf.width/2-this.conf.ofs_item)-(this.conf.width+this.conf.ofs_item)*this.conf.selected+"px";
		
		// this.controls.style.left = Math.round(this.cont.offsetWidth/2-this.controls.offsetWidth/2)+"px";
		this._adjustControls();
		
		if (this.conf.autoheight != true) {
			this.conf.top = Math.max(0, Math.floor(this.area.offsetHeight-this.conf.height)/2);
			for (var a in sizes) sizes[a].y = this.conf.top;
		}
		
		// resize cells
		for (var a in sizes) {
			this.cdata[a].cell._setSize(sizes[a].x, sizes[a].y, sizes[a].w, sizes[a].h);
		}
		
		this.callEvent("_onSetSizes", []); // if mtb attached
		
	}
	
	this._adjustCell = function(id) {
		this.cdata[id].cell._setSize(this[this.conf.anim_f.detect_x](id), this.conf.top, this.conf.width, this.conf.height);
	}
	
	this._animateStart = function(dir, ef) {
		
		if ((this.conf.selected <= 0 && dir < 0) || (this.conf.selected >= this.conf.items_count-1 && dir > 0)) return;
		
		if (this.conf.animating == true) return;
		this.conf.animating = true;
		
		this[this.conf.anim_f.prepare](dir, ef);
		
	}
	
	this._animateTransEnd = function(e) {
		that[that.conf.anim_f.end](e||event, this);
	}
	
	this._animateEnd = function(dir) {
		
		this.conf.selected = this.conf.selected+dir;
		this._checkControls();
		this._setBarIndex(this.conf.selected);
		this.callEvent("onSelect", [this.ind[this.conf.selected]]);
		
		this.conf.animating = false;
		
	}
	
	this._initControls();
	
	this.setCellSize = function(w, h) {
		this.conf.item_width = (w==null?"auto":w);
		this.conf.item_height = (h==null?"auto":h);
		this.setSizes();
	}
	
	this.setOffset = function(left, top, item) {
		if (left != null) this.conf.ofs_left = left;
		if (top != null) this.conf.ofs_top = top;
		if (item != null) this.conf.ofs_item = item;
		this.setSizes();
	}
	
	this.enableHotKeys = function(mode) {
		this.conf.keys = window.dhx4.s2b(mode);
	}
	
	this.goFirst = function() {
		if (this.conf.selected == 0) return;
		this._animateStart(-this.conf.selected);
	}
	
	this.goLast = function() {
		if (this.conf.selected == this.conf.items_count-1) return;
		this._animateStart(this.conf.items_count-1-this.conf.selected);
	}
	
	this.goNext = function() {
		this._animateStart(1);
	}
	
	this.goPrev = function() {
		this._animateStart(-1);
	}
	
	this.getActiveIndex = function() {
		return this.conf.selected;
	}
	
	this.getActiveId = function() {
		return this.ind[this.conf.selected];
	}
	
	this.getActiveCell = function() {
		var id = this.getActiveId();
		if (id != null) return this.cdata[id].cell;
		return null;
	}
	
	this.unload = function() {
		
		this.conf.unloading = true;
		
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("keydown", this._doOnWinKeyDown, false);
			if (this._doOnTouchStart != null) this.area.removeEventListener(window.dhx4.dnd.evs.start, this._doOnTouchStart, false);
		} else {
			document.body.detachEvent("onkeydown", this._doOnWinKeyDown);
		}
		
		// cells
		for (var a in this.cdata) this._removeCell(a);
		this.cdata[a] = null;
		
		// area
		this.area.parentNode.removeChild(this.area);
		this.area = null;
		
		// controls
		this._unloadControls();
		
		// celltop
		this._unloadTop();
		
		// events
		window.dhx4._eventable(this, "clear");
		
		// the rest
		for (var a in this) this[a] = null;
		
		that = null;
		
	}
	
	// events
	window.dhx4._eventable(this);
	this._callMainEvent = function(name, args) {
		this.callEvent(name, args);
	};
	this.conf.ev_coverclick = this.attachEvent("_cellCoverClick", function(index){
		var d = index-this.conf.selected;
		if (Math.abs(d) == 1) this._animateStart(d);
	});
	
	
	// keys
	this._doOnWinKeyDown = function(e) {
		e = e||event;
		if (that.conf.keys == true) {
			if (e.ctrlKey == true && e.shiftKey != true && e.altKey != true) {
				var code = e.keyCode;
				var k = that.conf.key_data;
				if (code == k.left || code == k.right) {
					if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
					that._animateStart(code==k.left?-1:1);
				}
			}
		}
	}
	
	if (typeof(window.addEventListener) == "function") {
		window.addEventListener("keydown", this._doOnWinKeyDown, false);
	} else {
		document.body.attachEvent("onkeydown", this._doOnWinKeyDown);
	}
	
	if (conf.delete_conf == true) {
		for (var a in conf) conf[a] = null;
		conf = null;
	}
	
	return this;
};

// top-level extensions
dhtmlXCarousel.prototype = new dhtmlXCellTop();

// enabled effects
dhtmlXCarousel.prototype.ef = {};

// cell access
dhtmlXCarousel.prototype.cells = function(id) {
	return this.cdata[id].cell;
};

// iterator
dhtmlXCarousel.prototype.forEachCell = function(handler) {
	for (var a in this.cdata) {
		if (typeof(handler) == "function") {
			handler.apply(window, [this.cdata[a].cell]);
		} else if (typeof(handler) == "string" && typeof(window[handler]) == "function") {
			window[handler].apply(window, [this.cdata[a].cell]);
		}
	}
};

dhtmlXCarousel.prototype._initControls = function() {
	
	var that = this;
	
	var a = (this.conf.skin=="material"?["",""]:this.conf.arw);
	
	this.controls = document.createElement("DIV");
	this.controls.className = "dhx_carousel_controls";
	this.controls.innerHTML = "<div class='dhx_carousel_bars'></div>"+
					"<div class='dhx_carousel_btn dhx_carousel_btn_prev'>"+a[0]+"</div>"+
					"<div class='dhx_carousel_btn dhx_carousel_btn_next'>"+a[1]+"</div>";
	
	this.cont.appendChild(this.controls);
	
	// events
	this._doOnControlClick = function(e) {
		if (that.conf.clear_click == true) {
			that.conf.clear_click = false;
			return;
		}
		e = e||event;
		if (window.dhx4.dnd.evs.start != null && e.type != "click" && that.conf.clear_click != true) { // force animation on touch devices by touch
			if (window.dhx4.dnd._mTouch(e) == true) return; // prevent pointer on desktop in IE
			that.conf.clear_click = true;
		}
		var t = e.target||e.srcElement;
		var anim = null;
		if (t.className != null) {
			if (t.className.match(/btn_prev/) != null) {
				anim = -1;
			} else if (t.className.match(/btn_next/) != null) {
				anim = 1;
			} else if (t.className.match(/dhx_carousel_onebar/) != null && t.className.match(/dhx_carousel_baractv/) == null) {
				for (var q=0; q<t.parentNode.childNodes.length; q++) {
					if (t.parentNode.childNodes[q] == t) anim = q-that.conf.selected;
				}
			}
		}
		if (anim != null) that._animateStart(anim);
		t = null;
	}
	if (typeof(window.addEventListener) == "function") {
		this.controls.addEventListener("click", this._doOnControlClick, false);
		if (window.dhx4.dnd.evs.start != null) this.controls.addEventListener(window.dhx4.dnd.evs.start, this._doOnControlClick, false);
	} else {
		this.controls.attachEvent("onclick", this._doOnControlClick);
		if (window.dhx4.isIE6 || window.dhx4.isIE7 || window.dhx4.isIE8) {
			this.controls.onselectstart = function(e) {
				e = e||event;
				if (e.preventDefault) e.preventDefault();
				e.returnValue = false;
				return false;
			}
		}
	}
	
	// api
	this.showControls = function() {
		this.controls.style.display = "";
		this.setSizes();
	}
	this.hideControls = function() {
		this.controls.style.display = "none";
		this.setSizes();
	}
	
	this._checkControls = function() {
		this.controls.childNodes[1].className = "dhx_carousel_btn dhx_carousel_btn_prev"+(this.conf.selected <= 0 ? "_dis":"");
		this.controls.childNodes[2].className = "dhx_carousel_btn dhx_carousel_btn_next"+(this.conf.selected >= this.conf.items_count-1 || this.conf.items_count == 0 ?"_dis":"");
	}
	
	this._adjustControls = function() {
		this.controls.firstChild.style.left = Math.round(this.cont.offsetWidth/2-this.controls.firstChild.offsetWidth/2)+"px";
	}
	
	this._addBar = function() {
		var t = document.createElement("DIV");
		t.className = "dhx_carousel_onebar";
		t.innerHTML = "<div class='dhx_carousel_barcore'>&nbsp;</div>";
		this.controls.firstChild.appendChild(t);
		t = null;
		this._adjustControls();
	}
	
	this._removeBar = function(adjust) {
		var t = this.controls.firstChild.lastChild;
		if (t != null) {
			t.parentNode.removeChild(t);
			t = null;
			if (adjust !== false) this._adjustControls();
		}
	}
	
	this._setBarIndex = function(i) {
		for (var q=0; q<this.controls.firstChild.childNodes.length; q++) {
			this.controls.firstChild.childNodes[q].className = "dhx_carousel_onebar"+(q==i?" dhx_carousel_baractv":"");
		}
	}
	
	this._unloadControls = function() {
		
		if (typeof(window.addEventListener) == "function") {
			this.controls.removeEventListener("click", this._doOnControlClick, false);
			if (window.dhx4.dnd.evs.start != null) this.controls.removeEventListener(window.dhx4.dnd.evs.start, this._doOnControlClick, false);
		} else {
			this.controls.detachEvent("onclick", this._doOnControlClick);
			if (window.dhx4.isIE6 || window.dhx4.isIE7 || window.dhx4.isIE8) this.controls.onselectstart = null;
		}
		
		// bars
		while (this.controls.firstChild.childNodes.length > 0) {
			this._removeBar(false);
		}
		
		// self
		this.cont.removeChild(this.controls);
		this.controls = null;
		
		that = null;
	}
	
	if (this.conf.buttons != true) this.hideControls();
	
	this._checkControls();
	
};
window.dhtmlXCarouselCell = function(id, carousel) {
	
	dhtmlXCellObject.apply(this, [id, "_carousel"]);
	
	var that = this;
	this.carousel = carousel;
	this.conf.skin = this.carousel.conf.skin;
	
	this.attachEvent("_onCellUnload", function(){
		this.carousel = null;
		that = null;
	});
	
	// onContentLoaded
	this.attachEvent("_onContentLoaded", function() {
		this.carousel._callMainEvent("onContentLoaded", [this._idd]);
	});
	
	// cell cover extended
	this._showCover = function() {
		if (this.conf.cover == true) return;
		this._showCellCover();
		// add click event
		var t = this.cell.childNodes[this.conf.idx.cover];
		t.onclick = function() {
			that.carousel._callMainEvent("_cellCoverClick", [that._idd]);
		};
		t = null;
	};
	this._hideCover = function() {
		if (this.conf.cover != true) return;
		this.cell.childNodes[this.conf.idx.cover].onclick = null;
		this._hideCellCover();
	};
	
	return this;
	
};

dhtmlXCarouselCell.prototype = new dhtmlXCellObject();

dhtmlXCarouselCell.prototype.getId = function() {
	return this._idd;
};

dhtmlXCarouselCell.prototype.getIndex = function() {
	return this.carousel.cdata[this._idd].index;
};

dhtmlXCarouselCell.prototype.setActive = function() {
	var ofs = this.getIndex() - this.carousel.conf.selected;
	if (ofs != 0) this.carousel._animateStart(ofs);
};

dhtmlXCarouselCell.prototype.remove = function() {
	this.carousel._removeCell(this._idd);
};
dhtmlXCellObject.prototype.attachCarousel = function(conf) {
	
	this.callEvent("_onBeforeContentAttach",["carousel"]);
	
	var obj = document.createElement("DIV");
	obj.style.width = "100%";
	obj.style.height = "100%";
	obj.style.position = "relative";
	obj.style.overflow = "hidden";
	this._attachObject(obj);
	
	if (typeof(window.dhtmlXSideBarCell) == "function" && this instanceof window.dhtmlXSideBarCell) {
		if (this.conf.skin == "dhx_terrace") {
			obj._ofs = {t:-1,r:-1,b:-1,l:-1};
		}
	}
	
	if (typeof(conf) == "undefined" || conf == null) conf = {};
	if (typeof(conf.skin) == "undefined") conf.skin = this.conf.skin;
	conf.parent = obj;
	
	this.dataType = "carousel";
	this.dataObj = new dhtmlXCarousel(conf);
	
	conf.parent = null;
	obj = conf = null;
	
	this.callEvent("_onContentAttach",[]);
	
	return this.dataObj;
	
};

// slide effect extension
// allow effect be used for any browser

dhtmlXCarousel.prototype.ef.slide = true;

dhtmlXCarousel.prototype.ef.slide_conf = {
	anim_step: 25,
	anim_timeout: 10,
	anim_slide: "left 0.3s" // for modern
};

dhtmlXCarousel.prototype.ef.slide_f = {
	prepare: "_slide_prepare",
	start: "_slide_start",
	end: "_slide_end",
	update_selected: "_slide_update_selected",
	detect_x: "_slide_detect_x",
	detect_aw: "_slide_detect_area_width",
	cell_added: "_slide_cell_added"
};

dhtmlXCarousel.prototype._slide_init = function() {
	return this.ef.slide_conf;
};

dhtmlXCarousel.prototype._slide_f = function() {
	return this.ef.slide_f;
};

dhtmlXCarousel.prototype._slide_prepare = function(dir, ef) {
	
	var step = this.conf.anim_step;
	var maxX = this.conf.width+this.conf.ofs_item;
	
	if (ef == false) step = maxX+1;
	
	this.area._init = parseInt(this.area.style.left);
	
	var nextId = this.ind[this.conf.selected+dir];
	this._slide_update_selected(nextId);
	
	if (this.conf.transProp !== false && ef != false) {
		if (this.conf.transEvInit != true) {
			this.area.addEventListener(this.conf.transEv, this._animateTransEnd, false);
			this.conf.transEvInit = true;
		}
		this.conf.current_dir = dir;
		this.area.style[this.conf.transProp] = this.conf.anim_slide;
		this.area.style.left = this.area._init+maxX*(-dir)+"px";
	} else {
		this._slide_start(step, 0, maxX, dir);
	}
};

dhtmlXCarousel.prototype._slide_start = function(step, curX, maxX, dir) {
	
	var stop = false;
	curX += step;
	
	if (curX >= maxX) {
		curX = maxX;
		stop = true;
	}
	
	this.area.style.left = this.area._init+curX*(-dir)+"px";
	if (stop != true) {
		var t = this;
		window.setTimeout(function(){t._slide_start(step, curX, maxX, dir); t = null;}, this.conf.anim_timeout);
	} else {
		this.cdata[this.ind[this.conf.selected]].cell._showCover();
		this._animateEnd(dir);
	}
};

dhtmlXCarousel.prototype._slide_end = function(e, obj) {
	if (e.type == this.conf.transEv && obj == this.area) {
		this.area.style[this.conf.transProp] = "";
		if (this.conf.transEvInit == true) {
			this.area.removeEventListener(this.conf.transEv, this._animateTransEnd, false);
			this.conf.transEvInit = false;
		}
		this.cdata[this.ind[this.conf.selected]].cell._showCover();
		this._animateEnd(this.conf.current_dir);
	}
};

dhtmlXCarousel.prototype._slide_update_selected = function(id) {
	this.cdata[id].cell._hideCover();
};

dhtmlXCarousel.prototype._slide_detect_x = function(id) {
	var i = this.cdata[id].index;
	var x = i*(this.conf.width+this.conf.ofs_item)+this.conf.ofs_item;
	return x;
};

dhtmlXCarousel.prototype._slide_cell_added = function(id) {
	if (this.conf.selected != this.cdata[id].index) {
		this.cdata[id].cell._showCover();
	}
	// item was inserted before selected, so selected was moved to right, move it back
	if (this.cdata[id].index <= this.conf.selected && this.conf.items_count > 1) {
		this._animateStart(1, false);
	}
};

dhtmlXCarousel.prototype._slide_detect_area_width = function() {
	return this.conf.items_count;
};
// flip effect extension
// works only for modern

/*

	----[L]----[L]----[S]----[R]----[R]----
	
	L   ietm on left    angle  -87
	S   selected ite    angle  0
	R   ietm on right   angle  87

*/


dhtmlXCarousel.prototype.ef.flip = true;

dhtmlXCarousel.prototype.ef.flip_conf = {
	anim_flip: "transform 0.3s ease-out",
	anim_flip_ang: -87,
	anim_flip_trstyle: "transform"
};

dhtmlXCarousel.prototype.ef.flip_f = {
	prepare: "_flip_prepare",
	start: "_flip_start",
	end: "_flip_end",
	update_selected: "_flip_update_selected",
	detect_x: "_flip_detect_x",
	detect_aw: "_flip_detect_area_width",
	cell_added: "_flip_cell_added"
};

dhtmlXCarousel.prototype._flip_init = function() {
	var t = (this.conf.transProp==false?false:this.ef.flip_conf);
	if (t !== false && window.dhx4.isKHTML == true && t.anim_flip.match("webkit") == null) { // Safari 5.1.7
		t.anim_flip = t.anim_flip.replace(/transform/,"-webkit-transform");
		t.anim_flip_trstyle = "webkitTransform";
	}
	return t;
};

dhtmlXCarousel.prototype._flip_f = function() {
	return this.ef.flip_f;
};

dhtmlXCarousel.prototype._flip_prepare = function(dir) {
	this.conf.flip_data = {
		fromIndex: this.conf.selected,
		toIndex: this.conf.selected+dir,
		mode: 0,
		dir: dir
	};
	this._flip_start();
};

dhtmlXCarousel.prototype._flip_start = function() {
	
	var cell = this.cdata[this.ind[this.conf.flip_data.mode==0?this.conf.flip_data.fromIndex:this.conf.flip_data.toIndex]].cell;
	
	if (this.area.className.match(/dhxcarousel_area_flip/) == null) {
		this.area.className += " dhxcarousel_area_flip";
	}
	
	if (cell.conf.tr_ev != true) {
		cell.cell.addEventListener(this.conf.transEv, this._animateTransEnd, false);
		cell.conf.tr_ev = true;
	}
	
	if (this.conf.flip_data.mode == 0) {
		cell.cell.style[this.conf.anim_flip_trstyle] = "rotateY("+String(this.conf.flip_data.dir>0?this.conf.anim_flip_ang:-this.conf.anim_flip_ang)+"deg)";
	} else {
		cell.cell.style.visibility = "visible";
		cell.cell.style[this.conf.anim_flip_trstyle] = "rotateY(0deg)";
	}
	
	cell.cell.style[this.conf.transProp] = this.conf.anim_flip;
	cell = null;
};

dhtmlXCarousel.prototype._flip_end = function(e, obj) {
	
	if (e.type == this.conf.transEv) {
		
		var cell = this.cdata[this.ind[this.conf.flip_data.mode==0?this.conf.flip_data.fromIndex:this.conf.flip_data.toIndex]].cell;
		
		if (obj == cell.cell) {
			
			cell.cell.removeEventListener(this.conf.transEv, this._animateTransEnd, false);
			cell.conf.tr_ev = false;
			
			if (this.conf.flip_data.mode == 0) {
				
				// step 2
				cell.cell.style[this.conf.transProp] = "";
				cell.cell.style.visibility = "hidden";
				this.conf.flip_data.mode = 1;
				this._flip_start();
				
			} else {
				
				var dir = this.conf.flip_data.dir;
				
				// check if animation jumped via several items,
				// index change can be required, see comment on top
				var m0 = Math.min(this.conf.flip_data.fromIndex, this.conf.flip_data.toIndex)+1;
				var m1 = Math.max(this.conf.flip_data.fromIndex, this.conf.flip_data.toIndex)-1;
				
				for (var q=m0; q<=m1; q++) {
					this.cdata[this.ind[q]].cell.cell.style[this.conf.anim_flip_trstyle] = "rotateY("+String(this.conf.anim_flip_ang*dir/Math.abs(dir))+"deg)";
				}
				
				if (this.area.className.match(/dhxcarousel_area_flip/) != null) {
					this.area.className = String(this.area.className).replace(/\s{0,}dhxcarousel_area_flip/gi, "");
				}
				
				// finish animation
				this.conf.flip_data = null;
				cell.cell.style[this.conf.transProp] = "";
				this._animateEnd(dir);
			}
		}
		cell = null;
	}
};

dhtmlXCarousel.prototype._flip_update_selected = function(id) {
	
};

dhtmlXCarousel.prototype._flip_detect_x = function(id) {
	var i = 0;
	var x = i*(this.conf.width+this.conf.ofs_item)+this.conf.ofs_item;
	return x;
};

dhtmlXCarousel.prototype._flip_cell_added = function(id) {
	if (this.conf.selected != this.cdata[id].index) {
		this.cdata[id].cell.cell.style[this.conf.anim_flip_trstyle] = "rotateY(" + String(this.cdata[id].index < this.conf.selected ? this.conf.anim_flip_ang : -this.conf.anim_flip_ang) + "deg)";
		this.cdata[id].cell.cell.style.visibility = "hidden";
	} else {
		this.cdata[id].cell.cell.style[this.conf.anim_flip_trstyle] = "rotateY(0deg)";
	}
};

dhtmlXCarousel.prototype._flip_detect_area_width = function() {
	return 1;
};
// cards effect extension

dhtmlXCarousel.prototype.ef.cards = true;

dhtmlXCarousel.prototype.ef.cards_conf = {
	anim_cards: "left 0.3s"
};

dhtmlXCarousel.prototype.ef.cards_f = {
	prepare: "_cards_prepare",
	start: "_cards_start",
	end: "_cards_end",
	update_selected: "_cards_update_selected",
	detect_x: "_cards_detect_x",
	detect_aw: "_cards_detect_area_width",
	cell_added: "_cards_cell_added"
};

dhtmlXCarousel.prototype._cards_init = function() {
	return (this.conf.transProp==false?false:this.ef.cards_conf);
};

dhtmlXCarousel.prototype._cards_f = function() {
	return this.ef.cards_f;
};

dhtmlXCarousel.prototype._cards_prepare = function(dir, ef) {
	
	if (dir > 0) {
		
		// move from right to left
		
		var id = this.ind[this.conf.selected+dir];
		var cell = this.cdata[id].cell;
		this._cards_update_selected(id);
		
		if (cell.conf.transEvInit != true) {
			cell.cell.addEventListener(this.conf.transEv, this._animateTransEnd, false);
			cell.conf.transEvInit = true;
		}
		
		this.conf.current_id = id;
		this.conf.current_dir = dir;
		
		cell.cell.style[this.conf.transProp] = this.conf.anim_cards;
		cell.cell.style.left = this._cards_detect_x(id, 0)+"px";
		
		cell = null;
		
	} else {
		
		// move cells to right, if jumped through several cells
		this._cards_adjust_middle(this.conf.selected+dir+1, this.conf.selected-1, 1);
		
		var id = this.ind[this.conf.selected];
		var cell = this.cdata[id].cell;
		cell._hideCover();
		
		if (cell.conf.transEvInit != true) {
			cell.cell.addEventListener(this.conf.transEv, this._animateTransEnd, false);
			cell.conf.transEvInit = true;
		}
		
		this.conf.current_id = id;
		this.conf.current_dir = dir;
		
		this.cdata[this.ind[this.conf.selected+dir]].cell._hideCover();
		
		cell.cell.style[this.conf.transProp] = this.conf.anim_cards;
		cell.cell.style.left = this._cards_detect_x(id, 1)+"px";
		
		cell = null;
		
	}
	
};

dhtmlXCarousel.prototype._cards_start = function(step, curX, maxX, dir) {
	// old browsers?
};

dhtmlXCarousel.prototype._cards_end = function(e, obj) {
	
	if (e.type == this.conf.transEv && this.conf.current_id != null && obj == this.cdata[this.conf.current_id].cell.cell) {
		
		var cell = this.cdata[this.conf.current_id].cell;
		
		cell.cell.style[this.conf.transProp] = "";
		if (cell.conf.transEvInit != true) {
			cell.cell.removeEventListener(this.conf.transEv, this._animateTransEnd, false);
			cell.conf.transEvInit = false;
		}
		
		this.conf.current_id = null;
		
		// move cells to left, if jumped through several cells
		if (this.conf.current_dir > 0) {
			this._cards_adjust_middle(this.conf.selected+1, this.conf.selected+this.conf.current_dir-1, 0);
		}
		
		this.cdata[this.ind[this.conf.selected]].cell._showCover();
		this._animateEnd(this.conf.current_dir);
		
	}
	
};

dhtmlXCarousel.prototype._cards_update_selected = function(id) {
	this.cdata[id].cell._hideCover();
};

dhtmlXCarousel.prototype._cards_adjust_middle = function(fromIndex, toIndex, i) {
	for (var q=fromIndex; q<=toIndex; q++) {
		var id = this.ind[q];
		var cell = this.cdata[id].cell;
		cell.conf.size.x = this._cards_detect_x(id, i);
		cell.cell.style.left = cell.conf.size.x+"px";
		cell = null;
	}
};

dhtmlXCarousel.prototype._cards_detect_x = function(id, i) {
	// i==0 -> item on left, i==1 => item on right
	if (typeof(i) == "undefined" || i == null) i = (this.cdata[id].index <= this.conf.selected ? 0:1);
	var x = i*(this.conf.width+this.conf.ofs_left+this.conf.ofs_item)+this.conf.ofs_item;
	return x;
};

dhtmlXCarousel.prototype._cards_cell_added = function(id) {
	
	this.cdata[id].cell.conf.size.x = this._cards_detect_x(id);
	this.cdata[id].cell.cell.style.left = this.cdata[id].cell.conf.size.x+"px";
	
	if (this.conf.selected != this.cdata[id].index) {
		this.cdata[id].cell._showCover();
	}
};

dhtmlXCarousel.prototype._cards_detect_area_width = function() {
	return 2;
};
