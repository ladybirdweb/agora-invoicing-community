/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXAccordion.prototype.enableDND = function() {
	
	if (this.conf.multi_mode == false || this._dnd != null) return;
	
	var that = this;
	
	this._dnd = {
		tr_count: 0,
		tr_items: {}
	};
	
	this._dndAttachEvent = function(id) {
		var t = this.t[id].cell;
		if (t.conf.dnd_inited != true) {
			if (typeof(window.addEventListener) == "function") {
				t.cell.childNodes[t.conf.idx.hdr].addEventListener("mousedown", this._dndOnMouseDown, false);
			} else {
				t.cell.childNodes[t.conf.idx.hdr].attachEvent("onmousedown", this._dndOnMouseDown);
			}
			t.conf.dnd_inited = true;
		}
		t = null;
	}
	
	this._dndDetachEvent = function(id) {
		var t = this.t[id].cell;
		if (t.conf.dnd_inited == true) {
			if (typeof(window.addEventListener) == "function") {
				t.cell.childNodes[t.conf.idx.hdr].removeEventListener("mousedown", this._dndOnMouseDown, false);
			} else {
				t.cell.childNodes[t.conf.idx.hdr].detachEvent("onmousedown", this._dndOnMouseDown);
			}
			t.conf.dnd_inited = false;
		}
		t = null;
	}
	
	this._dndOnMouseDown = function(e) {
		e = e||event;
		if (e.preventDefault) e.preventDefault(); // selection in chrome
		var t = (e.target||e.srcElement);
		while (t != null && t.parentNode != that.cont) t = t.parentNode;
		if (t != null) that._dndDragStart(e, t);
		t = null;
	}
	
	this._dndDragStart = function(e,t) {
		
		if (this._dnd.tr_waiting == true) return;
		
		// cell index
		var ind0 = -1;
		for (var q=0; q<t.parentNode.childNodes.length; q++) {
			if (t.parentNode.childNodes[q] == t) ind0 = q;
		}
		
		if (this.callEvent("onBeforeDrag",[t._accId, ind0]) !== true) return; // added in 4.2
		
		if (typeof(window.addEventListener) == "function") {
			document.body.addEventListener("mousemove", this._dndOnMouseMove, false);
			document.body.addEventListener("mouseup", this._dndOnMouseUp, false);
		} else {
			document.body.attachEvent("onmousemove", this._dndOnMouseMove, false);
			document.body.attachEvent("onmouseup", this._dndOnMouseUp, false);
		}
		
		this._dnd.dragObj = t;
		
		this._dnd.dy = e.clientY;
		
		// define index and min/max offset for dragged object
		var u = 0;
		
		for (var q=0; q<this._dnd.dragObj.parentNode.childNodes.length; q++) {
			this._dnd.dragObj.parentNode.childNodes[q]._ind = q; // recalculate indecies
			if (this._dnd.dragObj.parentNode.childNodes[q] == this._dnd.dragObj) {
				this._dnd.dragObj._k0 = u;
				if (q > 0) this._dnd.dragObj._k0 += this.ofs.m.between-this.ofs.m.first; // include margins for non-top cells
				u = 0;
			} else {
				u += this._dnd.dragObj.parentNode.childNodes[q].offsetHeight+
					parseInt(this._dnd.dragObj.parentNode.childNodes[q].style.marginTop);
			}
		}
		this._dnd.dragObj._k1 = u;
		
		this._dnd.h = this._dnd.dragObj.offsetHeight;
		
		this._dnd.ofs = false; // check if mouse was realy moved over screen
	}
	
	this._dndDoDrag = function(e) {
		
		if (!this._dnd.dragObj) return;
		if (this._dnd.tr_waiting == true) return;
		
		var r = e.clientY-this._dnd.dy;
		
		if (this._dnd.ofs == false && Math.abs(r) > 5) {
			this._dnd.dragObj.className += " acc_cell_dragged";
			this._dnd.ofs = true;
		}
		
		// overlaying left/right
		if (r < 0) {
			if (r < -this._dnd.dragObj._k0) r = -this._dnd.dragObj._k0;
		} else {
			if (r > this._dnd.dragObj._k1) r = this._dnd.dragObj._k1;
		}
		
		this._dnd.dragObj.style.top = r+"px";
		
		// prev
		
		// get offset
		var ofs = e.clientY-this._dnd.dy;
		var s0 = 0;
		var i = 0;
		for (var q=this._dnd.dragObj._ind+1; q<=this._dnd.dragObj.parentNode.lastChild._ind; q++) {
			var w0 = this._dnd.dragObj.parentNode.childNodes[q].offsetHeight;
			if (ofs > s0+w0*2/3) i++;
			s0 += w0;
		}
		
		// loop through siblings
		var s = this._dnd.dragObj.nextSibling;
		var q = 0;
		
		while (s != null) {
			
			if (++q<=i && s != null) {
				// move to left if not moved yet
				if (!s._ontop) {
					if (s._tm) window.clearTimeout(s._tm);
					this._dndAnim(s, false, parseInt(s.style.top||0), -this._dnd.h-this.ofs.m.between); // margin-top always "between", index here will never equal 0
					s._ontop = true;
				}
			} else {
				// move to right (to orig position) if moved to left
				if (s._ontop) {
					if (s._tm) window.clearTimeout(s._tm);
					this._dndAnim(s, true, parseInt(s.style.top||0), 0);
					s._ontop = false;
				}
			}
			
			s = s.nextSibling;
		}
		
		// next
		
		// get offset
		var ofs = this._dnd.dy-e.clientY;
		var s0 = 0;
		var i = 0;
		for (var q=this._dnd.dragObj._ind-1; q>=this._dnd.dragObj.parentNode.firstChild._ind; q--) {
			var w0 = this._dnd.dragObj.parentNode.childNodes[q].offsetHeight;
			if (ofs > s0+w0*2/3) i++;
			s0 += w0;
		}
		
		// loop through siblings
		var s = this._dnd.dragObj.previousSibling;
		var q = 0;
		
		while (s != null) {
			
			if (++q<=i && s != null) {
				if (!s._onbottom) {
					if (s._tm) window.clearTimeout(s._tm);
					this._dndAnim(s, true, parseInt(s.style.top||0), this._dnd.h+this.ofs.m.between);
					s._onbottom = true;
				}
			} else {
				if (s._onbottom) {
					if (s._tm) window.clearTimeout(s._tm);
					this._dndAnim(s, false, parseInt(s.style.top), 0);
					s._onbottom = false;
				}
			}
			
			s = s.previousSibling;
		}
		
	}
	
	this._dndDragStop = function(e, force) {
		
		if (force) {
			// console.log("tr ended, fix drop");
		} else {
			if (this._dnd.tr_count > 0) {
				this._dnd.tr_waiting = true;
				// console.log("still moving", this._dnd.tr_count);
				return;
			}
		}
		
		if (!this._dnd.dragObj) return;
		
		this._dnd.dragObj.className = String(this._dnd.dragObj.className).replace(/\s{0,}acc_cell_dragged/gi,"");
		this._dnd.dragObj.style.top = "0px";
		
		var p = false;
		
		for (var q=0; q<this._dnd.dragObj.parentNode.childNodes.length; q++) {
			var s = this._dnd.dragObj.parentNode.childNodes[q];
			
			if (s != this._dnd.dragObj) {
				if (s._tm) window.clearTimeout(s._tm);
				s.style.top = "0px";
				if (s._ontop && ((s.nextSibling != null && s.nextSibling._ontop != true) || !s.nextSibling)) {
					p = (s.nextSibling||null);
				}
				if (s._onbottom && ((s.previousSibling != null && s.previousSibling._onbottom != true) || !s.previousSibling)) {
					p = s;
				}
			}
			s = null;
		}
		for (var q=0; q<this._dnd.dragObj.parentNode.childNodes.length; q++) {
			this._dnd.dragObj.parentNode.childNodes[q]._ontop = null;
			this._dnd.dragObj.parentNode.childNodes[q]._onbottom = null;
		}
		
		if (p !== false) {
			if (p == null) {
				this._dnd.dragObj.parentNode.appendChild(this._dnd.dragObj);
			} else {
				this._dnd.dragObj.parentNode.insertBefore(this._dnd.dragObj, p);
			}
		}
		
		var id = this._dnd.dragObj._accId;
		var ind0 = this._dnd.dragObj._ind;
		var ind1 = ind0;
		for (var q=0; q<this._dnd.dragObj.parentNode.childNodes.length; q++) {
			if (this._dnd.dragObj.parentNode.childNodes[q] == this._dnd.dragObj) ind1 = q;
		}
		
		this._dnd.dragObj = null;
		this._dnd.tr_waiting = false;
		
		this._updateCellsMargin();
		if (ind0 != ind1) {
			this.setSizes();
			this.callEvent("onDrop", [id, ind0, ind1]);
		}
		
		if (typeof(window.addEventListener) == "function") {
			document.body.removeEventListener("mousemove", this._dndOnMouseMove, false);
			document.body.removeEventListener("mouseup", this._dndOnMouseUp, false);
		} else {
			document.body.detachEvent("onmousemove", this._dndOnMouseMove, false);
			document.body.detachEvent("onmouseup", this._dndOnMouseUp, false);
		}		
	}
	
	this._dndAnim = function(obj, dir, f, t) {
		
		if (this.conf.tr.prop != false) {
			
			if (!obj._dnd_ev) {
				obj._dnd_ev = true;
				obj._dnd_tr_prop = this.conf.tr.prop;
				obj.addEventListener(this.conf.tr.ev, this._dndOnTrEnd, false);
			}
			
			if (this._dnd.tr_items[obj._accId] != true) {
				this._dnd.tr_items[obj._accId] = true;
				this._dnd.tr_count++;
			}
			
			obj.style[this.conf.tr.prop] = this.conf.tr.dnd_top;
			obj.style.top = t+"px";
			return;
		}
		
		var stop = false;
		if (dir) {
			f += 5;
			if (f >= t) { f = t; stop = true; }
		} else {
			f -= 5;
			if (f <= t) { f = t; stop = true; }
		}
		obj.style.top = f+"px";
		if (obj._tm) window.clearTimeout(obj._tm);
		if (!stop) {
			obj._tm = window.setTimeout(function(){that._dndAnim(obj, dir, f, t);},5);
		} else {
			obj._tm = null;
		}
		
	}
	
	this._dndOnTrEnd = function(ev) {
		if (ev.stopPropagation) ev.stopPropagation();
		if (ev.propertyName == "top") {
			// clear cache
			if (that._dnd.tr_items[this._accId] == true) {
				that._dnd.tr_count--;
				that._dnd.tr_items[this._accId] = false;
			}
			// remove prop
			this.style[this._dnd_tr_prop] = "";
			//
			if (that._dnd.tr_count == 0 && that._dnd.tr_waiting == true) {
				that._dndDragStop(null, true);
			}
		}
	}
	
	this._dndOnMouseMove = function(e) {
		that._dndDoDrag(e||event);
	}
	
	this._dndOnMouseUp = function(e) {
		that._dndDragStop(e||event);
	}
	
	this._dndClearCell = function(id) {
		if (this.t[id].cell.cell._dnd_ev) this.t[id].cell.cell.addEventListener(this.conf.tr.ev, this._dndOnTrEnd, false);
		this._dndDetachEvent(id);
	}
	
	this._unloadDND = function() {
		
		// functions
		for (var a in this) {
			if (String(a).indexOf("_dnd") == 0 && typeof(this[a]) == "function") this[a] = null;
		}
		
		// cell-clear will called from removeItem()
		this._dnd = null;
		that = null;
	}
	
	// update cells
	for (var a in this.t) this._dndAttachEvent(a);
	
};

