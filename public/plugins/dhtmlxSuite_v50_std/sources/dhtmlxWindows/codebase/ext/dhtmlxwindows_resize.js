/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXWindowsCell.prototype._initResize = function() {
	
	var that = this;
	var n = navigator.userAgent;
	
	this.conf.resize = {
		b_width: 6,
		c_type: (n.indexOf("MSIE 10.0")>0||n.indexOf("MSIE 9.0")>0||n.indexOf("MSIE 8.0")>0||n.indexOf("MSIE 7.0")>0||n.indexOf("MSIE 6.0")>0),
		btn_left: ((window.dhx4.isIE6||window.dhx4.isIE7||window.dhx4.isIE8) && typeof(window.addEventListener) == "undefined" ? 1:0)
	};
	
	this._rOnCellMouseMove = function(e) {
		
		if (that.wins.conf.resize_actv == true || that.wins.w[that._idd].conf.allow_resize == false || that.conf.progress == true || that.wins.w[that._idd].conf.maxed == true || that.wins.w[that._idd].conf.fs_mode == true) {
			var k = that.wins.w[that._idd].brd;
			if (k.style.cursor != "default") k.style.cursor = "default";
			k = null;
			return;
		}
		
		
		e = e||event;
		
		var cont = that.wins.w[that._idd].brd;
		var r = that.conf.resize;
		
		var no_header = (that.wins.w[that._idd].conf.header==false);
		
		var x = e.clientX;
		var y = e.clientY;
		
		// body/html scrolls
		x += (document.documentElement.scrollLeft||document.body.scrollLeft||0);
		y += (document.documentElement.scrollTop||document.body.scrollTop||0);
		
		var x0 = window.dhx4.absLeft(cont);
		var y0 = window.dhx4.absTop(cont);
		
		var mode = "";
		if (x <= x0+r.b_width) { // left
			mode = "w";
		} else if (x >= x0+cont.offsetWidth-r.b_width) { // right
			mode = "e";
		}
		if (y >= y0+cont.offsetHeight-r.b_width) { // bottom
			mode = "s"+mode;
		} else if (no_header && y <= y0+r.b_width) { // top (only for no_header mode)
			mode = "n"+mode;
		}
		
		if (mode == "") mode = false;
		if (r.mode != mode) {
			r.mode = mode;
			if (mode == false) {
				cont.style.cursor = "default";
			} else {
				cont.style.cursor = mode+"-resize";
			}
		}
		
		cont = r = null;
	};
	
	this._rOnCellMouseDown = function(e) {
		
		e = e||event;
		if (typeof(e.button) != "undefined" && e.button != that.conf.resize.btn_left) return;
		
		if (that.conf.resize.mode == false) return;
		if (that.conf.progress == true) return; // if progress is on - deny
		if (that.wins.w[that._idd].conf.allow_resize == false) return;
		if (that.wins.w[that._idd].conf.fs_mode == true) return; // fullscreened window
		
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		
		if (that.wins._callMainEvent("onBeforeResizeStart", that._idd) !== true) return;
		
		var w = that.wins.w[that._idd];
		var r = that.conf.resize;
		
		that.wins.conf.resize_actv = true;
		
		r.min_w = w.conf.min_w;
		r.min_h = w.conf.min_h;
		r.max_w = w.conf.max_w||+Infinity;
		r.max_h = w.conf.max_h||+Infinity;
		
		// if layout attached - check custom min w/h
		if (w.cell.dataType == "layout" && w.cell.dataObj != null && typeof(w.cell.dataObj._getWindowMinDimension) == "function") {
			var t = w.cell.dataObj._getWindowMinDimension(w.cell);
			r.min_w = Math.max(t.w, r.min_w);
			r.min_h = Math.max(t.h, r.min_h);
		}
		
		r.vp_l = that.wins.conf.vp_pos_ofs;
		r.vp_r = that.wins.vp.clientWidth-that.wins.conf.vp_pos_ofs;
		r.vp_b = that.wins.vp.clientHeight-that.wins.conf.vp_pos_ofs;
		
		r.x = e.clientX;
		r.y = e.clientY;
		
		// start resize
		if (typeof(window.addEventListener) == "function") {
			window.addEventListener("mousemove", that._rOnWinMouseMove, false);
			window.addEventListener("mouseup", that._rOnWinMouseUp, false);
			window.addEventListener("selectstart", that._rOnSelectStart, false);
		} else {
			document.body.attachEvent("onmousemove", that._rOnWinMouseMove);
			document.body.attachEvent("onmouseup", that._rOnWinMouseUp);
			document.body.attachEvent("onselectstart", that._rOnSelectStart);
		}
		
		r.resized = false;
		
		r.vp_cursor = that.wins.vp.style.cursor;
		that.wins.vp.style.cursor = r.mode+"-resize";
		
		w = r = null;
	};
	
	this._rOnCellContextMenu = function(e) {
		e = e||event;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		return false;
	};
	
	this._rOnWinMouseMove = function(e) {
		
		// resize in progress
		e = e||event;
		
		var w = that.wins.w[that._idd];
		var r = that.conf.resize;
		
		if (!r.resized) {
			w.fr_cover.className += " dhxwin_fr_cover_resize";
			r.resized = true;
		}
		
		var x = e.clientX-r.x;
		var y = e.clientY-r.y;
		
		if (r.mode.indexOf("e") >= 0) { // right win side dragged
			
			r.rw = Math.min(Math.max(w.conf.w+x, r.min_w), r.max_w);
			r.rx = null;
			
			if (w.conf.x+r.rw < r.vp_l) { // check overflow to left
				r.rw = r.vp_l-w.conf.x;
			} else if (w.conf.x+r.rw > that.wins.vp.clientWidth) { // and right
				r.rw = that.wins.vp.clientWidth-w.conf.x;
			}
			
		} else if (r.mode.indexOf("w") >= 0) { // left win side dragged
			
			r.rw = Math.min(Math.max(w.conf.w-x,r.min_w),r.max_w);
			r.rx = w.conf.x+w.conf.w-r.rw;
			
			if (r.rx < 0) { // check overflow to left
				r.rw = r.rw+r.rx;
				r.rx = 0;
			} else if (r.rx > r.vp_r) { // and right
				r.rw = r.rw-r.vp_r;
				r.rx = r.vp_r;
			}
			
		}
		
		if (r.mode.indexOf("s") >= 0) { // bottom win side (can be together with left or right)
			
			r.rh = Math.min(Math.max(w.conf.h+y, r.min_h),r.max_h);
			r.ry = null;
			
			if (w.conf.y+r.rh > that.wins.vp.clientHeight) { // bottom overflow
				r.rh = that.wins.vp.clientHeight-w.conf.y;
			}
			
		} else if (r.mode.indexOf("n") >= 0) { // top win side (can be together with left or right) (only for no_header mode)
			
			r.rh = Math.min(Math.max(w.conf.h-y, r.min_h),r.max_h);
			r.ry = w.conf.y+w.conf.h-r.rh;
			
			if (r.ry < 0) { // top overflow
				r.rh = r.rh+r.ry;
				r.ry = 0;
			} else if (r.ry > r.vp_b) { // and bottom
				r.rh = r.rh-r.vp_b;
				r.ry = r.vp_b;
			}
		}
		
		that._rAdjustSizer();
		
		w = r = null;
	}
	this._rOnWinMouseUp = function() {
		
		// stop resize
		
		var r = that.conf.resize;
		var w = that.wins.w[that._idd];
		
		that.wins.conf.resize_actv = false;
		that.wins.vp.style.cursor = r.vp_cursor;
		
		w.fr_cover.className = String(w.fr_cover.className).replace(/\s{0,}dhxwin_fr_cover_resize/gi,"");
		
		if (r.resized) {
			that.wins._winSetSize(that._idd, r.rw, r.rh);
			if (r.rx == null) r.rx = w.conf.x;
			if (r.ry == null) r.ry = w.conf.y;
			if (r.rx != w.conf.x || r.ry != w.conf.y) that.wins._winSetPosition(that._idd, r.rx, r.ry);
		}
		
		if (r.obj != null) {
			r.obj.parentNode.removeChild(r.obj);
			r.obj = null;
		}
		if (r.objFR != null) {
			r.objFR.parentNode.removeChild(r.objFR);
			r.objFR = null;
		}
		
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("mousemove", that._rOnWinMouseMove, false);
			window.removeEventListener("mouseup", that._rOnWinMouseUp, false);
			window.removeEventListener("selectstart", that._rOnSelectStart, false);
		} else {
			document.body.detachEvent("onmousemove", that._rOnWinMouseMove);
			document.body.detachEvent("onmouseup", that._rOnWinMouseUp);
			document.body.detachEvent("onselectstart", that._rOnSelectStart);
		}
		
		if (r.resized == true) {
			if (that.dataType == "layout" && that.dataObj != null) that.dataObj.callEvent("onResize",[]); // deprecated, 3.6 compat
			that.wins._callMainEvent("onResizeFinish", that._idd);
		} else {
			that.wins._callMainEvent("onResizeCancel", that._idd);
		}
		
		r.mode = "";
		
		w = r = null;
	}
	
	this._rOnSelectStart = function(e) {
		e = e||event;
		if (e.preventDefault) e.preventDefault(); else e.returnValue = false;
		return false;
	}
	
	this._rInitSizer = function() {
		
		var r = that.conf.resize;
		var w = that.wins.w[that._idd];
		
		r.obj = document.createElement("DIV");
		r.obj.className = "dhxwin_resize";
		r.obj.style.zIndex = w.win.style.zIndex;
		r.obj.style.cursor = r.mode+"-resize";
		that.wins.vp.appendChild(r.obj);
		
		if (that.wins.conf.fr_cover == true) {
			r.objFR = document.createElement("IFRAME");
			r.objFR.className = "dhxwin_resize_fr_cover";
			r.objFR.style.zIndex = r.obj.style.zIndex;
			that.wins.vp.insertBefore(r.objFR, r.obj);
		}
		
		r.rx = w.conf.x;
		r.ry = w.conf.y;
		r.rw = w.conf.w;
		r.rh = w.conf.h;
		r = null;
	}
	
	this._rAdjustSizer = function() {
		var r = that.conf.resize;
		if (!r.obj) this._rInitSizer();
		// dim
		r.obj.style.width = r.rw+"px";
		r.obj.style.height = r.rh+"px";
		
		// pos, optional
		if (r.rx != null) r.obj.style.left = r.rx+"px";
		if (r.ry != null) r.obj.style.top = r.ry+"px";
		
		if (r.objFR != null) {
			r.objFR.style.width = r.obj.style.width;
			r.objFR.style.height = r.obj.style.height;
			if (r.rx != null) r.objFR.style.left = r.obj.style.left;
			if (r.ry != null) r.objFR.style.top = r.obj.style.top;
		}
		
		r = null;
	}
	
	if (typeof(window.addEventListener) == "function") {
		this.wins.w[this._idd].brd.addEventListener("mousemove", this._rOnCellMouseMove, false);
		this.wins.w[this._idd].brd.addEventListener("mousedown", this._rOnCellMouseDown, false);
		this.wins.w[this._idd].brd.addEventListener("contextmenu", this._rOnCellContextMenu, false);
	} else {
		this.wins.w[this._idd].brd.attachEvent("onmousemove", this._rOnCellMouseMove);
		this.wins.w[this._idd].brd.attachEvent("onmousedown", this._rOnCellMouseDown);
		this.wins.w[this._idd].brd.attachEvent("oncontextmenu", this._rOnCellContextMenu);
	}
	
	this._unloadResize = function() {
		
		if (typeof(window.addEventListener) == "function") {
			this.wins.w[this._idd].brd.removeEventListener("mousemove", this._rOnCellMouseMove, false);
			this.wins.w[this._idd].brd.removeEventListener("mousedown", this._rOnCellMouseDown, false);
			this.wins.w[this._idd].brd.removeEventListener("contextmenu", this._rOnCellContextMenu, false);
		} else {
			this.wins.w[this._idd].brd.detachEvent("onmousemove", this._rOnCellMouseMove);
			this.wins.w[this._idd].brd.detachEvent("onmousedown", this._rOnCellMouseDown);
			this.wins.w[this._idd].brd.detachEvent("oncontextmenu", this._rOnCellContextMenu);
		}
		
		this._initResize = null;
		this._rOnCellMouseMove = null;
		this._rOnCellMouseDown = null;
		this._rOnWinMouseMove = null;
		this._rOnWinMouseUp = null;
		this._rOnSelectStart = null;
		this._rInitSizer = null;
		this._rAdjustSizer = null;
		this._unloadResize = null;
		
		this.conf.resize = null;
		that = null;
	};

};

