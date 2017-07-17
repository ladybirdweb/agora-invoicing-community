/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXSlider(data) {
	
	var that = this;
	
	this.conf = {
		size: null,
		skin: null,
		vertical: false,
		min: 0,
		max: 99,
		value: 0,
		step: 1,
		decimals: 0,
		margin: 2,
		border: 1,
		inverse: false,
		disabled: false,
		tooltip: false,
		visible: true,
		linkTo: null,
		range: false,	// new in 4.3, range-mode
		bg: null,	// new in 4.3, render bg-div inside track, false for single, true for range
		bg_mode: "left"	// new in 4.3, left or right, if range-mode - between runners
	};
	
	this._attachedNode = {};
	this._movingInitialValues = null;
	
	this.base = null;
	
	if (arguments.length > 1) { // init by arguments
		return new dhtmlXSlider(this._renderArgumets(arguments));
	}else if (typeof(data) == "string" || (typeof(data) == "object" && data.tagName)) { // init by node
		return new dhtmlXSlider({parent: data});
	}
	
	if (typeof(data.parent) == "string") {
		this.base = document.getElementById(data.parent);
	} else {
		this.base = data.parent;
	}
	
	this._mergeConfig(this._readAttFormNode(this.base));
	this._mergeConfig(data);
	
	if (this.conf.bg == null) {
		this.conf.bg = (this.conf.range==true);
	}
	
	// fix value/linkto for range-mode
	if (this.conf.range == true) {
		if (!(this.conf.value instanceof Array)) this.conf.value = [this.conf.value];
		if (this.conf.value.length < 2) this.conf.value.push(this.conf.value[0]);
		if (!(this.conf.linkTo instanceof Array)) this.conf.linkTo = [this.conf.linkTo];
	}
	
	this._detectDecimals();
	
	if (this.conf.size == null || typeof(this.conf.size) == "undefined") {
		if (this.conf.vertical) {
			this.conf.size = this.base.offsetHeight;
		} else {
			this.conf.size = this.base.offsetWidth;
		}
	}
	
	var skin = this.conf.skin || window.dhx4.skin || (typeof(dhtmlx) != "undefined"? dhtmlx.skin : null) || window.dhx4.skinDetect("dhxslider") || "material";
	
	this.setSkin(skin);
	
	this.base.innerHTML = "<div class='dhxsl_container'>"+
					"<div class='dhxsl_track'>"+
						(this.conf.bg==true?"<div class='dhxsl_track_bg'></div>":"")+
					"</div>"+
					"<div class='dhxsl_runner'></div>"+
					(this.conf.range==true?"<div class='dhxsl_runner'></div>":"")+
				"</div>";
	
	this._nodes = {
		cont: this.base.firstChild,
		track: this.base.firstChild.firstChild,
		runner: this.base.firstChild.childNodes[1],
		runner2: this.base.firstChild.childNodes[2] // for range-mode
	};
	
	this._nodes.cont.onmousedown = 
	this._nodes.track.onmousedown = 
	this._nodes.cont.onselectstart = 
	this._nodes.track.onselectstart = function(e) {
		e = e || event;
		if (typeof(e.preventDefault) == "function") {
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
		return false;
	};
	
	this._r_actv = null; // active runner
	
	if (this.conf.range == true) {
		this.conf.value = this._normalizeRange(this.conf.value, this.conf.min, this.conf.max);
	} else {
		this.conf.value = this._normalize(this.conf.value, this.conf.min, this.conf.max);
	}
	
	this._setOrient(this.conf.vertical);
	this.setSize(this.conf.size);
	
	// events start
	this._initMover = function(e, index) {
		
		if (that.conf.disabled) return;
		
		e = e || event;
		if (typeof(e.preventDefault) == "function") e.preventDefault();
		
		if (index != null) {
			that._r_actv = that._nodes[index==0?"runner":"runner2"];
		} else {
			that._r_actv = e.target||e.srcElement;
			if (that._r_actv.className.indexOf("dhxsl_runner") == -1) that._r_actv = that._nodes.runner;
		}
		
		that._r_actv.className = "dhxsl_runner dhxsl_runner_actv";
		
		that._movingInitialValues = {};
		if (that.conf.range == true) {
			that._movingInitialValues.index = (that._r_actv==that._nodes.runner?0:1);
			that._movingInitialValues.value = that.conf.value[that._movingInitialValues.index];
		} else {
			that._movingInitialValues.value = that.conf.value;
		}
		that._movingInitialValues.coord = that._getTouchCoord(e);
		
		if (that.conf.disabled == false) {
			if (typeof(window.addEventListener) == "function") {
				window.addEventListener("mousemove", that._move, false);
				window.addEventListener("mouseup", that._cleanMove, false);
				window.addEventListener("touchmove", that._move, false);
				window.addEventListener("touchend", that._cleanMove, false);
			} else {
				document.body.attachEvent("onmousemove", that._move);
				document.body.attachEvent("onmouseup", that._cleanMove);
			}
		}
		that.callEvent("onMouseDown",[that._r_actv==that._nodes.runner?0:1]);
		return false;
	};
	
	this._move = function(e) {
		
		if (that.conf.disabled) return;
		
		e = e || event;
		if (typeof(e.preventDefault) == "function") e.preventDefault();
		
		var runner = (that.conf.vertical) ? that._r_actv.offsetHeight : that._r_actv.offsetWidth;
		var range = that.conf.max - that.conf.min;
		var n_cord = that._getTouchCoord(e);
		var new_value = that._movingInitialValues.value + (n_cord - that._movingInitialValues.coord)*range/(that.conf.size - runner)*(that.conf.inverse?-1:1);
		
		if (that.conf.range == true) {
			var v = [that.conf.value[0], that.conf.value[1]];
			// limits
			if (that._movingInitialValues.index == 0) {
				v[0] = Math.min(v[1], new_value);
			} else if (that._movingInitialValues.index == 1) {
				v[1] = Math.max(v[0], new_value);
			}
			that.setValue(v, true);
		} else {
			that.setValue(new_value, true);
		}
	};
	
	this._cleanMove = function(e) {
		if (typeof(window.addEventListener) == "function") {
			window.removeEventListener("mousemove", that._move, false);
			window.removeEventListener("mouseup", that._cleanMove, false);
			window.removeEventListener("touchmove", that._move, false);
			window.removeEventListener("touchend", that._cleanMove, false);
		} else {
			document.body.detachEvent("onmousemove", that._move);
			document.body.detachEvent("onmouseup", that._cleanMove);
		}
		that._movingInitialValues = null;
		that._r_actv.className = that._r_actv.className.replace(/\s{0,}dhxsl_runner_actv/gi,"");
		that.callEvent("onSlideEnd", [that.conf.value]);
		that.callEvent("onMouseUp", [that._r_actv==that._nodes.runner?0:1]);
		that._r_actv = null;
	};
	
	this._doOnSetValue = function(e) {
		
		if (that.conf.disabled) return;
		
		if (that._movingInitialValues != null) return false;
		
		e = e || event;
		if (typeof(e.preventDefault) == "function") e.preventDefault();
		
		var ofs = 0;
		var t = e.target||e.srcElement;
		if (t.className.match(/dhxsl_track_bg/) != null) ofs = parseInt(t.style[that.conf.vertical?"top":"left"]);
		
		if (e.type.match(/touch/) != null) {
			var n_coord = that._getTouchCoord(e) + ofs;
		} else {
			var n_coord = (that.conf.vertical ? (e.offsetY || e.layerY) : (e.offsetX || e.layerX)) + ofs;
		}
		var runner = (that.conf.vertical ? that._nodes.runner.offsetHeight : that._nodes.runner.offsetWidth) + ofs;
		var range = that.conf.max - that.conf.min;
		
		var index = null;
		var new_value = null;
		
		if (that.conf.inverse == true) {
			new_value = that.conf.max-(n_coord*range/(that.conf.size));
		} else {
			new_value = (n_coord*range/(that.conf.size) + that.conf.min);
		}
		
		if (that.conf.range == true) {
			index = (Math.abs(that.conf.value[0]-new_value) <= Math.abs(that.conf.value[1]-new_value) ? 0 : 1); // select nearest runner
			new_value = [index==0?new_value:that.conf.value[0], index==1?new_value:that.conf.value[1]];
		}
		
		that.conf.init_index = index;
		that.setValue(new_value, true);
		that.conf.init_index = null;
		
		if (that._movingInitialValues == null) that._initMover(e, index);
		
		return false;
	};
	
	this._doOnChangeInput = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		that.setValue(target.value);
	};
	
	this._doOnKeyDown = function(e) {
		e = e || event;
		var target = e.target || e.srcElement;
		if (e.keyCode == 13) that.setValue(target.value);
	};
	
	// events end
	
	this._attachEvents(this._nodes);
	
	this.unload = function() {
		
		dhx4._eventable(this, "clear");
		
		this._detachNode();
		this._detachEvents(this._nodes);
		
		this.base.removeChild(this._nodes.cont);
		
		this._nodes.cont.onmousedown = 
		this._nodes.track.onmousedown = 
		this._nodes.cont.onselectstart = 
		this._nodes.track.onselectstart = null;
		
		delete this._nodes.cont;
		delete this._nodes.track;
		delete this._nodes.max;
		delete this._nodes.min;
		delete this._nodes.runner;
		if (this._nodes.runner2) delete this._nodes.runner2;
		
		if (/\s?dhtmlxslider_\S*/.test(this.base.className)) {
			this.base.className = this.base.className.replace(/\s?dhtmlxslider_\S*/, "");
		}
		
		for (var key in this) this[key] = null;
		
		that = null;
	};
	
	dhx4._eventable(this);
	
	if (this.conf.disabled) {
		this.disable();
	}
	
	if (this.conf.tooltip) {
		this.enableTooltip();
	}
	
	if (!this.conf.visible) {
		this.hide();
	}
	
	if (this.conf.linkTo) {
		this.linkTo(this.conf.linkTo);
	}
	
	return this;
	
};

dhtmlXSlider.prototype._setOrient = function(vertical) {
	vertical = vertical || false;
	
	if (/\s?dhxsl_cont_hr/i.test(this._nodes.cont.className)) {
		this._nodes.cont.className = this._nodes.cont.className.replace(/\s?dhxsl_cont_hr/i, "");
	}
	
	if (/\s?dhxsl_cont_vr/i.test(this._nodes.cont.className)) {
		this._nodes.cont.className = this._nodes.cont.className.replace(/\s?dhxsl_cont_vr/i, "");
	}
	
	if (vertical) {
		this._nodes.cont.className += " dhxsl_cont_vr";
	} else {
		this._nodes.cont.className += " dhxsl_cont_hr";
	}
};

dhtmlXSlider.prototype._getTouchCoord = function(e) {
	var type = (e.type.match(/mouse/) != null ? "client":"page") + (this.conf.vertical == true ? "Y":"X");
	var coord = (typeof(e[type]) != "undefined" && e[type] != 0 ? e[type] : (e.touches != null && e.touches[0] != null ? e.touches[0][type]||0:0));
	return coord;
};

dhtmlXSlider.prototype._attachEvents = function(nodes) {
	if (typeof(window.addEventListener) == "function") {
		nodes.runner.addEventListener("mousedown", this._initMover, false);
		nodes.runner.addEventListener("touchstart", this._initMover, false);
		nodes.cont.addEventListener("mousedown", this._doOnSetValue, false);
		nodes.cont.addEventListener("touchstart", this._doOnSetValue, false);
		if (nodes.runner2) {
			nodes.runner2.addEventListener("mousedown", this._initMover, false);
			nodes.runner2.addEventListener("touchstart", this._initMover, false);
		}
	} else {
		nodes.runner.attachEvent("onmousedown", this._initMover);
		nodes.cont.attachEvent("onmousedown", this._doOnSetValue);
		if (nodes.runner2) {
			nodes.runner2.attachEvent("onmousedown", this._initMover);
		}
	}
};

dhtmlXSlider.prototype._detachEvents = function(nodes) {
	if (typeof(window.addEventListener) == "function") {
		nodes.runner.removeEventListener("mousedown", this._initMover, false);
		nodes.runner.removeEventListener("touchstart", this._initMover, false);
		nodes.cont.removeEventListener("mousedown", this._doOnSetValue, false);
		nodes.cont.removeEventListener("touchstart", this._doOnSetValue, false);
		if (nodes.runner2) {
			nodes.runner2.removeEventListener("mousedown", this._initMover, false);
			nodes.runner2.removeEventListener("touchstart", this._initMover, false);
		}
	} else {
		nodes.runner.detachEvent("onmousedown", this._initMover);
		nodes.cont.detachEvent("onmousedown", this._doOnSetValue);
		if (nodes.runner2) {
			nodes.runner2.detachEvent("onmousedown", this._initMover);
		}
	}
};

dhtmlXSlider.prototype._mergeConfig = function(data) {
	for (var key in data) {
		switch (key.toLowerCase()) {
			case "min":
			case "max":
			case "size":
			case "step":
			case "value":
			case "inverse":
				this.conf[key] = data[key];
				break;
			case "tooltip":
			case "visible":
			case "vertical":
			case "disabled":
			case "range":
			case "bg":
				this.conf[key] = dhx4.s2b(data[key]);
				break;
			case "bg_mode":
				this.conf[key] = ({"left":"left","right":"right"}[data[key]])||"left";
				break;
			case "parent":
				continue;
				break;
			case "skin":
				this.conf[key] = (this._skinCollection[data[key]] == true ? data[key] : null); // reset not supported 3.6 skins to default
				break;
			default:
				this.conf[key] = data[key];
		}
	}
};

dhtmlXSlider.prototype._readAttFormNode = function(node) {
	var atts = node.attributes, l = atts.length, i, answer = {}, att;
	
	for (i=0; i<l; i++) {
		att = atts[i];
		switch (att.name.toLowerCase()) {
			case "size":
			case "min":
			case "max":
			case "value":
			case "step":
				answer[att.name] = Number(att.value);
				break;
			case "skin":
				answer[att.name] = att.value;
				break;
			case "vertical":
			case "disabled":
			case "visible":
			case "range":
			case "bg":
				answer[att.name] = dhx4.s2b(att.value);
				break;
			case "linkto":
				answer[att.name] = att.value;
				break;
			case "tooltip":
				answer[att.name] = dhx4.s2b(att.value);
				break;
			case "bg_mode":
				answer[att.name] = ({"left":"left","right":"right"}[att.value])||"left";
				break;
		}
	}
	
	return answer;
};

dhtmlXSlider.prototype._renderArgumets = function(arg) {
	var answer = {}, i,l;
	l = arg.length;
	
	for (i=0; i<l; i++) {
		switch (i) {
			case 0:
				answer.parent = arg[i];
				break;
			case 1:
				answer.size = arg[i];
				break;
			case 2:
				answer.skin = arg[i];
				break;
			case 3:
				answer.vertical = arg[i];
				break;
			case 4:
				answer.min = arg[i];
				break;
			case 5:
				answer.max = arg[i];
				break;
			case 6:
				answer.value = arg[i];
				break;
			case 7:
				answer.step = arg[i];
				break;
		}
	}
	
	return answer;
};

dhtmlXSlider.prototype._skinCollection = {
	dhx_skyblue: true,
	dhx_web: true,
	dhx_terrace: true,
	material: true
};

dhtmlXSlider.prototype._indexOf = function(arr, el) {
	var i,l,answer = -1;
	l = arr.length;
	for (i=l; i>=0; i--) {
		if (arr[i] == el) {
			answer = i;
			break;
		}
	}
	
	return answer;
};

dhtmlXSlider.prototype._refreshRunner = function(index) {
	
	var k = (this.conf.vertical == true ? {x: "top", y: "left", ofs_w: "offsetHeight", ofs_h: "offsetWidth"} : {x: "left", y: "top", ofs_w: "offsetWidth", ofs_h: "offsetHeight"});
	
	var cmax = this._nodes.cont[k.ofs_w] - this._nodes.runner[k.ofs_w];
	
	var r1 = this._nodes.runner;
	var r2 = this._nodes.runner2;
	
	// single or left
	if (index == null || index == 0) {
		var cp = this._getCoord(cmax, (this.conf.value instanceof Array?this.conf.value[0]:this.conf.value));
		r1.style[k.x] = cp + this.conf.border + "px";
		r1.style[k.y] = Math.round((this._nodes.cont[k.ofs_h] - r1[k.ofs_h])/2) + "px";
	}
	
	// right
	if (this.conf.range == true && (index == null || index == 1)) {
		var cp = this._getCoord(cmax, this.conf.value[1]);
		r2.style[k.x] = cp + this.conf.border + "px";
		r2.style[k.y] = Math.round((this._nodes.cont[k.ofs_h] - r1[k.ofs_h])/2) + "px";
	}
	
	// check z-index
	if (this.conf.range == true) {
		if (r1.style[k.x] == r2.style[k.x] && this.conf.value[1] == this.conf.max) {
			if (r1.style.zIndex != 2) r1.style.zIndex = 2;
		} else {
			if (r1.style.zIndex == 2) r1.style.zIndex = 1;
		}
	}
	
	r1 = r2 = null;
	
	this._refreshBG();
};

dhtmlXSlider.prototype._setValueByCoord = function(data) {
	var cx = dhx4.absLeft(this._nodes.cont),
	cy = dhx4.absTop(this._nodes.cont),
	value, k;
	
	if (this.conf.vertical) {
		k = (data.y - cy - this._nodes.runner.offsetHeight/2)/(this._nodes.cont.offsetHeight - this._nodes.runner.offsetHeight);
	} else {
		k = (data.x - cx - this._nodes.runner.offsetWidth/2)/(this._nodes.cont.offsetWidth - this._nodes.runner.offsetWidth);
	}
	
	value = (this.conf.max-this.conf.min)*k+this.conf.min;
	
	this.setValue(value, true);
};

dhtmlXSlider.prototype._getCoord = function(max, value) {
	var v = (this.conf.inverse?this._inverseValue(value):value);
	var k = (v-this.conf.min)/(this.conf.max - this.conf.min);
	return Math.round(max*k);
};

dhtmlXSlider.prototype._normalize = function(value, min, max) {
	value = Number(value); // for decimals
	value = Math.round(value/this.conf.step)*this.conf.step;
	var ten = Math.pow(10, this.conf.decimals);
	value = Math.round(value*ten)/ten;
	value = Math.max(min, Math.min(max, value));
	return value;
};

dhtmlXSlider.prototype._normalizeRange = function(value, min, max) {
	if (value[1] < value[0]) value[1] = value[0];
	value[0] = this._normalize(value[0], min, Math.min(max, value[1]));
	value[1] = this._normalize(value[1], Math.max(min, value[0]), max);
	return value;
};

dhtmlXSlider.prototype._refreshBG = function() {
	
	if (this.conf.bg != true) return;
	
	var p = this._nodes.track.firstChild;
	var r = this._nodes.runner;
	var r2 = r.nextSibling;
	
	var k = (this.conf.vertical == true ? {x: "top", w: "height", ofs: "offsetHeight"} : {x: "left", w: "width", ofs: "offsetWidth"});
	
	if (this.conf.range == true) {
		p.style[k.x] = Math.floor(parseInt(r.style[k.x])+r[k.ofs]/2)+"px";
		p.style[k.w] = Math.max(Math.floor(parseInt(r2.style[k.x])+r2[k.ofs]/2)-parseInt(p.style[k.x]), 0)+"px";
	} else {
		var mode = (this.conf.inverse == true? {"left":"right", "right":"left"}[this.conf.bg_mode] : this.conf.bg_mode);
		p.style[k.x] = (mode == "left" ? "0" : Math.floor(parseInt(r.style[k.x])+r[k.ofs]/2)) + "px";
		p.style[k.w] = (mode == "left" ? Math.floor(parseInt(r.style[k.x])+r[k.ofs]/2) : this._nodes.track[k.ofs]-parseInt(p.style[k.x])) + "px";
	}
	
	p = r = r2 = null;
	
};

dhtmlXSlider.prototype._attachNode = function(node, index) {
	
	this._detachNode(index);
	
	var tagName = node.tagName.toLowerCase();
	if (!tagName) return;
	
	// node._dhxsl_mode = mode;
	this._attachedNode["node_"+index] = node;
	
	switch (tagName) {
		case "input":
		case "select":
			if (typeof(window.addEventListener) == "function") {
				node.addEventListener("change", this._doOnChangeInput, false);
				node.addEventListener("keydown", this._doOnKeyDown, false);
			} else {
				node.attachEvent("onchange", this._doOnChangeInput);
				node.attachEvent("onkeydown", this._doOnKeyDown);
			}
			
			this._attachedNode.setValue = function(value, decimals, index) {
				var v = (value instanceof Array?value[index||0]:value);
				this["node_"+(index||0)].value = dhtmlXSlider.prototype._atatchedNodeFixDec(v, decimals);
			};
			break;
		default:
			this._attachedNode.setValue = function(value, decimals, index) {
				var v = (value instanceof Array?value[index||0]:value);
				this["node_"+(index||0)].innerHTML = dhtmlXSlider.prototype._atatchedNodeFixDec(v, decimals);
			};
	}
	
	this._attachedNode.setValue(this.conf.value, this.conf.decimals, index);
};

dhtmlXSlider.prototype._detachNode = function(index) {
	
	var node = this._attachedNode["node_"+index];
	
	if (!node) return;
	
	var tagName = node.tagName;
	
	switch (tagName) {
		case "input":
		case "select":
			if (typeof(window.addEventListener) == "function") {
				node.removeEventListener("change", this._doOnChangeInput, false);
				node.removeEventListener("keydown", this._doOnChangeInput, false);
			} else {
				node.detachEvent("change", this._doOnChangeInput);
				node.detachEvent("keydown", this._doOnChangeInput);
			}
			break;
	}
	
	delete this._attachedNode["node_"+index];
	delete this._attachedNode.setValue;
	node = null;
};

dhtmlXSlider.prototype._atatchedNodeFixDec = function(value, decimals) {
	value = String(value);
	if (decimals > 0) {
		var k = value.match(/\.\d{1,}$/);
		if (k != null) decimals = Math.max(decimals-k[0].length+1);
		value += (value.indexOf(".")<0?".":"");
		for (var q=0; q<decimals; q++) value += "0";
	}
	return value;
};

dhtmlXSlider.prototype._detectDecimals = function() {
	var k = this.conf.step.toString().match(/\.(\d*)$/);
	this.conf.decimals = (k!=null?k[1].length:0);
};

dhtmlXSlider.prototype.setSize = function(size) {
	if (!isNaN(size)) {
		if (this.conf.vertical) {
			if (this._nodes.cont.style.width) delete this._nodes.cont.style.width;
			this._nodes.cont.style.height = size-this.conf.margin + "px";
		} else {
			if (this._nodes.cont.style.height) delete this._nodes.cont.style.height;
			this._nodes.cont.style.width = size-this.conf.margin + "px";
		}
		this._refreshRunner();
	}
};

dhtmlXSlider.prototype.setSkin = function (skin) {
	skin = skin.toLowerCase();
	
	var classes, _int = -1, skinName, className="dhtmlxslider";
	
	classes = this.base.className.match(/\S\w+/ig);
	
	if (classes instanceof  Array) {    
		for (skinName in this._skinCollection) {
			if (_int == -1) {
				_int = this._indexOf(classes, className + "_" + skinName);
			} else {           
				break;
			}
		}
		
		_int = (_int == -1) ? classes.length : _int;
	} else {
		classes = [];
		_int = 0;
	}
	
	
	
	classes[_int] = className + "_" + skin;
	
	this.base.className = classes.join(" ");
	this.conf.skin = skin;
	
	if (this._nodes) this._refreshRunner();
};

dhtmlXSlider.prototype.setValue = function(value, callEvent) {
	
	callEvent = callEvent || false;
	
	var index = null;
	var refresh = false;
	
	if (this.conf.range == true) {
		if (this._r_actv != null) index = (this._r_actv==this._nodes.runner?0:1);
		value = this._normalizeRange(value, this.conf.min, this.conf.max);
		refresh = (this.conf.value[0] != value[0] || this.conf.value[1] != value[1]);
	} else {
		value = this._normalize(value, this.conf.min, this.conf.max);
		refresh = (this.conf.value != value);
	}
	
	if (refresh = true) {
		
		this.conf.value = value;
		this._refreshRunner(index);
		
		this._refreshTooltip();
		
		if (callEvent) {
			var args = [value, this];
			if (this.conf.range == true) {
				if (this._r_actv != null) {
					args.push(this._r_actv==this._nodes.runner?0:1);
				} else if (this.conf.init_index != null) {
					args.push(this.conf.init_index.valueOf());
				}
			}
			this.callEvent("onChange", args);
		}
	}
	
	if (typeof(this._attachedNode.setValue) == "function") {
		if (index == null) index = this.conf.init_index;
		this._attachedNode.setValue(this.conf.value, this.conf.decimals, index);
	}
};

dhtmlXSlider.prototype.getValue = function() {
	if (this.conf.range == true) {
		return [this.conf.value[0].valueOf(),this.conf.value[1].valueOf()];
	} else {
		return this.conf.value.valueOf();
	}
};

dhtmlXSlider.prototype._inverseValue = function(value) {
	return this.conf.max+this.conf.min-value;
};

dhtmlXSlider.prototype.disable = function(mode) {
	mode = (mode == false) ? false : true; // deprecated
	var reg = null;
	if (mode) {
		for (var nm in this._nodes) {
			if (nm == "cont") continue;
			var a = (nm == "runner2"?"runner":nm);
			if (this._nodes[nm] != null) {
				reg = new RegExp("\\s?dhxsl_"+a+"_dis","i");
				if (!reg.test(this._nodes[nm].className)) this._nodes[nm].className += " dhxsl_"+a+"_dis";
			}
		}
		
		this.conf.disabled = true;
	} else {
		this.enable();
	}
};

dhtmlXSlider.prototype.enable = function() {
	var reg;
	for (var nm in this._nodes) {
		if (nm == "cont") continue;
		var a = (nm == "runner2"?"runner":nm);
		if (this._nodes[nm] != null) {
			reg = new RegExp("\\s?dhxsl_"+a+"_dis","i");
			if (reg.test(this._nodes[nm].className)) this._nodes[nm].className = this._nodes[nm].className.replace(reg,"");
		}
	}
	
	this.conf.disabled = false;
};

dhtmlXSlider.prototype.isEnabled = function() {
	return !this.conf.disabled;
};

dhtmlXSlider.prototype.disableTooltip = function() {
	this._nodes.cont.removeAttribute("title");
	this.conf.tooltip = false;
};

dhtmlXSlider.prototype.enableTooltip = function(mode) {
	if (typeof(mode) == "undefined") mode = true; else mode = dhx4.s2b(mode);
	if (mode) {
		this.conf.tooltip = true;
		this._refreshTooltip();
	} else {
		this.disableTooltip();
	}
};

dhtmlXSlider.prototype.setMax = function(value) {
	if (!isNaN(value) && this.conf.min < value) {
		this.conf.max = value;
		this.setValue(this.conf.value);
	}
};

dhtmlXSlider.prototype.getMax = function() {
	return this.conf.max;
};

dhtmlXSlider.prototype.setMin = function(value) {
	if (!isNaN(value) && this.conf.max > value) {
		this.conf.min = value;
		this.setValue(this.conf.value);
	}
};

dhtmlXSlider.prototype.getMin = function() {
	return this.conf.min;
};

dhtmlXSlider.prototype.setStep = function(value) {
	var maxValue = this.conf.max - this.conf.min;
	if (!isNaN(value) && value < maxValue) {
		this.conf.step = value;
		this._detectDecimals();
		this.setValue(this.conf.value);
	}
};

dhtmlXSlider.prototype.getStep = function() {
	return this.conf.step;
};

dhtmlXSlider.prototype.show = function() {
	if (/\s?dhxsl_hidden/i.test(this._nodes.cont.className)) {
		this._nodes.cont.className = this._nodes.cont.className.replace(/\s?dhxsl_hidden/i, "");
	}
	
	this.conf.visible = true;
};

dhtmlXSlider.prototype.hide = function() {
	if (!/\s?dhxsl_hidden/i.test(this._nodes.cont.className)) {
		this._nodes.cont.className += " dhxsl_hidden";
	}
	
	this.conf.visible = false;
};

dhtmlXSlider.prototype.isVisible = function() {
	return this.conf.visible;
};

dhtmlXSlider.prototype.linkTo = function(node) {
	if (!(node instanceof Array)) node = [node];
	for (var q=0; q<node.length && q<2; q++) {
		if (node[q] != null) {
			if (typeof(node[q]) == "string") node[q] = document.getElementById(node[q]);
			this._attachNode(node[q], q);
		}
	}
};

dhtmlXSlider.prototype._refreshTooltip = function() {
	if (this.conf.tooltip == true) {
		if (this.conf.value instanceof Array) {
			this._nodes.cont.title = this.conf.value.join(", ");
		} else {
			this._nodes.cont.title = this.conf.value;
		}
	}
};

dhtmlXSlider.prototype.getRunnerIndex = function() {
	if (this._r_actv == null) return -1;
	return (this._r_actv==this._nodes.runner?0:1);
};
