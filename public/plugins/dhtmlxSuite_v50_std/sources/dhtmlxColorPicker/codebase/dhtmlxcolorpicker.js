/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXColorPicker(base) {
	
	if (!(this instanceof window.dhtmlXColorPicker)) {
		return new dhtmlXColorPicker(base);
	}
	
	dhx4._eventable(this);
	
	var that = this, base_type = undefined,
	temp_node = null, i,l, temp_conf;
	
	this._nodes = [];
	
	this.activeNode = null;
	this._inputListenerId = null;
	this.base = null;
	this._globalNode = null;
	this.memory = null;
	this.skin = null;
	
	this.conf = {
		cp_id: dhx4.newId(),
		x: 0,
		y: 0,
		c: 0,
		indent: 2,
		position: "right", // right or bottom
		customColors: false,
		selectedColor: null,
		hide: false,
		hideOnSelect: false,
		lang: "en",
		closeable: true // autohide when button cancel clicked, can be used for custom-parent init
	};
	
	this.value = {
		red: -1,
		blue: -1,
		green: -1,
		hue: -1,
		sat: -1,
		lum: -1
	};
	
	this._initMoveSelection = function(e) {
		e = e || event;
		if (typeof(window.addEventListener) == "function") {
			that._controllerNodes.colorArea.addEventListener("mousemove", that._setMoveSelection, false);
			document.body.addEventListener("mouseup", that._cleanMoveSelection, false);
		} else {
			that._controllerNodes.colorArea.attachEvent("onmousemove", that._setMoveSelection);
			document.body.attachEvent("onmouseup", that._cleanMoveSelection);
		}
		
		that._setMoveSelection(e,that._controllerNodes.colorArea);
		return false;
	};
	
	this._cleanMoveSelection = function() {
		if (typeof(window.removeEventListener) == "function") {
			that._controllerNodes.colorArea.removeEventListener("mousemove", that._setMoveSelection, false);
			document.body.removeEventListener("mouseup", that._cleanMoveSelection, false);
		} else {
			that._controllerNodes.colorArea.detachEvent("onmousemove", that._setMoveSelection);
			document.body.detachEvent("onmouseup", that._cleanMoveSelection);
		}
		
		return false;
	};
	
	this._setMoveSelection = function(e) {
		e = e || event;
		var coord = that._getOffsetPosition(e, that._controllerNodes.colorArea);
		if (that._controllerNodes.fr_cover) {
			setTimeout(function() {
					that._setColorAreaXY(coord.x, coord.y);
					that._setColorByXYC();
			},0);
		} else {
			that._setColorAreaXY(coord.x, coord.y);
			that._setColorByXYC();
		}
		return false;
	};
	
	this._initMoveContrast = function(e) {
		e = e || event;
		if (typeof(window.addEventListener) == "function") {
			document.body.addEventListener("mousemove", that._setMoveContrast, false);
			document.body.addEventListener("mouseup", that._cleanMoveContrast, false);
		} else {
			document.body.attachEvent("onmousemove", that._setMoveContrast);
			document.body.attachEvent("onmouseup", that._cleanMoveContrast);
		}
		
		that._setMoveContrast(e,that._controllerNodes.contrastArea);
	};
	
	this._cleanMoveContrast = function() {
		if (typeof(window.removeEventListener) == "function") {
			document.body.removeEventListener("mousemove", that._setMoveContrast, false);
			document.body.removeEventListener("mouseup", that._cleanMoveContrast, false);
		} else {
			document.body.detachEvent("onmousemove", that._setMoveContrast);
			document.body.detachEvent("onmouseup", that._cleanMoveContrast);
		}
	};
	
	this._setMoveContrast = function(e) {
		e = e || event;
		
		var coord = that._getOffsetPosition(e, that._controllerNodes.contrastArea);
		that._setContrastY(coord.y);
		that._setColorByXYC(true);
	};
	
	this._doOnSelectColor = function() {
		var hex = that.colorAIP.rgb2hex({
				r: that.value.red,
				g: that.value.green,
				b: that.value.blue
		});
		
		if (that.activeNode != null) {
			if (that.activeNode.valueCont) {
				that.activeNode.valueCont.value = hex;
			}
			if (that.activeNode.valueColor) {
				that.activeNode.valueColor.style.backgroundColor = hex;
			}
		}
		
		if (that.base._dhx_remove || that.conf.hideOnSelect) {
			that.hide();
		}
		
		that.callEvent("onSelect",[hex,((that.activeNode)? that.activeNode.node: null)]);
	};
	
	this._doOnCancel = function() {
		// if cp isn't closeable - allow event to be fired anyway
		if (that.callEvent("onCancel",[((that.activeNode)? that.activeNode.node: null)]) == true && that.conf.closeable == true) {
			that.hide();
		}
	};
	
	this._doOnFocusByInput = function() {
		var target = (this != window)? this : event.srcElement;
		var object = (that.activeNode && that.activeNode.valueCont && that.activeNode.valueCont == target) ? that.activeNode : that._getNodeByValueCont(target);
		
		that.activeNode = object;
		if (object && object == that.activeNode) {
			that._initListenerInput();
		}
	};
	
	this._doOnBlurByInput = function() {
		var target = (this != window)? this : event.srcElement;
		var object = (that.activeNode && that.activeNode.valueCont && that.activeNode.valueCont == target) ? that.activeNode : that._getNodeByValueCont(target);
		if (object && object == that.activeNode) {
			that._removeListenerInput();
		}
	};
	
	this._doOnClickByNode = function(e) {
		e = e || event;
		var target = (this != window)? this : event.srcElement;
		that.activeNode = (that.activeNode.node != target)
		? that._getNodeByElement(target)
		: that.activeNode;
		if (!that.isVisible()) {
			that.show();
		}
	};
	
	this.saveColor = function() {
		that.memory.setValue(that.value);
	};
	
	this._onSelectMemoryEl = function(contr) {
		var key;
		that._refreshCoordinatesByHSL(contr.value.hue, contr.value.sat, contr.value.lum);
		
		for (key in contr.value) {
			that.value[key] = contr.value[key];
		}
		
		that._refreshContrast();
		that._refreshInputValues();
		that._refreshColorValue();
	};
	
	this._doOnClickByBody = function(e) {
		e = e || event;
		var is_close = true, target =e.target || e.srcElement;
		
		if (that._isBaseNode(target)) {
			is_close = false;
		}
		
		if (is_close && that.activeNode && (that.activeNode.node == target || that.activeNode.valueCont == target)) {
			is_close = false;
		}
		
		if (is_close) {
			that.hide();
		}
	};
	
	this._doOnChangeHSL = function() {
		var hue = parseInt(that._controllerNodes.hue.value),
		sat = parseInt(that._controllerNodes.sat.value),
		lum = parseInt(that._controllerNodes.lum.value),
		rgb;
		
		if (isNaN(hue) || hue > 359 || hue < 0) {
			that._controllerNodes.hue.value = that.value.hue;
		} else {
			that.value.hue = hue;
		}
		
		if (isNaN(sat) || sat > 100 || sat < 0) {
			that._controllerNodes.sat.value = that.value.sat;
		} else {
			that.value.sat = sat;
		}
		
		
		if (isNaN(lum) || lum > 100 || lum < 0) {
			that._controllerNodes.lum.value = that.value.lum;
		} else {
			that.value.lum = lum;
		}
		
		rgb = that.colorAIP.hsl2rgb(that.value.hue, that.value.sat/100, that.value.lum/100);
		that.value.red = Math.round(255*rgb.r);
		that.value.green = Math.round(255*rgb.g);
		that.value.blue = Math.round(255*rgb.b);
		
		that._refreshCoordinatesByHSL(that.value.hue,that.value.sat,that.value.lum);
		that._refreshContrast();
		that._refreshInputValues();
		that._refreshColorValue();
	};
	
	this._doOnChangeRGB = function() {
		var red = parseInt(that._controllerNodes.red.value),
		green = parseInt(that._controllerNodes.green.value),
		blue = parseInt(that._controllerNodes.blue.value),
		hsl;
		
		if (isNaN(red) || red > 255 || red < 0) {
			that._controllerNodes.red.value = that.value.red;
		} else {
			that.value.red = red;
		}
		
		if (isNaN(green) || green > 255 || green < 0) {
			that._controllerNodes.green.value = that.value.green;
		} else {
			that.value.green = green;
		}
		
		if (isNaN(blue) || blue > 255 || blue < 0) {
			that._controllerNodes.blue.value = that.value.blue;
		} else {
			that.value.blue = blue;
		}
		
		hsl = that.colorAIP.rgb2hsl(that.value.red/255, that.value.green/255, that.value.blue/255);
		that.value.hue = Math.round(hsl.h);
		that.value.sat = Math.round(hsl.s*100);
		that.value.lum = Math.round(hsl.l*100);
		
		that._refreshCoordinatesByHSL(that.value.hue,that.value.sat,that.value.lum);
		that._refreshContrast();
		that._refreshInputValues();
		that._refreshColorValue();
	};
	
	this._doOnChangeHSV = function() {
		that._controllerNodes.hsv.value = that.setColor(that._controllerNodes.hsv.value);
	};
	
	this._checkType = function (base) {
		var tempType;
		if (base instanceof Array) {
			tempType = that._checkType(base[0]);
			switch (tempType) {
			case "string":
				return "array_string";
				break;
			case "input":
			case "textarea":
				return "array_input";
				break;
			case "object":
				return "array_object";
				break;
				default:
				return undefined;
			}
			
		} else if (base == undefined) {
			return null;
			
		} else if (typeof(base) == "string") {
			return "string";
			
		} else if (base.tagName && base.tagName.toLowerCase() == "input") {
			return "input";
			
		} else if (base.tagName && base.tagName.toLowerCase() == "textarea") {
			return "textarea";
			
		} else if (base.tagName) {
			return "container";
			
		} else if (typeof(base) == "object") {
			return "object";
			
		} else return undefined;
	};
	
	this._initByObject = function (conf) {
		if (conf.parent && conf.parent.tagName) {
			that.base = conf.parent;
		} else if (typeof(conf.parent) == "string") {
			that.base = document.getElementById(conf.parent);
		} else {
			that.base = document.createElement("div");
			that.base._dhx_remove = true;
		}
		
		if (conf.color) {
			that.conf.selectedColor = conf.color;
		}
		if (typeof(conf.closeable) != "undefined") {
			this.conf.closeable = dhx4.s2b(conf.closeable);
		}
		if (conf.custom_colors) {
			this._tempInitCC = function() {
				var i, l;
				this.initMemoryColors();
				this.conf.customColors = true;
				
				if (conf.custom_colors instanceof Array) {
					l = conf.custom_colors.length;
					for (i=0; i<l; i++) {
						this.setCustomColors(conf.custom_colors[i]);
					}
				}
				
				if (this.base.parentNode) {
					this.showMemory();
				}
				delete this._tempInitCC;
			};
		}
		
		if (conf.hide) {
			that.conf.hide = true;
		}
		
		if (conf.input) {
			that._addNode(conf.input, conf.target_color, conf.target_value);
		}
		
		var skin = conf.skin || window.dhx4.skin || (typeof(dhtmlx) != "undefined"? dhtmlx.skin : null) || window.dhx4.skinDetect("dhxcolorpicker") || "material";
		that.setSkin(skin);
		
		// deprecated
		if (conf.colors) {
			that.initMemoryColors();
			that.conf.customColors = true;
		}
		
		if (conf.link) {
			that._addNode(conf.link);
		}
	};
	
	this.unload = function() {
		var i,l,key;
		if (this.isVisible()) {
			this.hide();
		}
		this.destructMemory();
		
		l = this._nodes.length;
		for (i=0; i<l; i++) {
			this._detachEventsFromNode(this._nodes[i]);
			for (key in this._nodes[i]) {
				this._nodes[i][key] = null;
			}
			delete this._nodes[i];
		}
		this._nodes = null;
		
		if (!this.base._dhx_remove) {
			this.base.className = this.base.className.replace(/\s?dhtmlxcp_\S*/, "");
		} else {
			delete this.base._dhx_remove;
		}
		
		if (typeof(window.addEventListener) == "function") {
			this._controllerNodes.colorArea.removeEventListener("mousedown", this._initMoveSelection, false);
			this._controllerNodes.colorArea.removeEventListener("dblclick", this._doOnSelectColor, false);
			this._controllerNodes.contrastArea.removeEventListener("mousedown", this._initMoveContrast, false);
			
			this._controllerNodes.button_save.removeEventListener("click", this._doOnSelectColor, false);
			this._controllerNodes.button_cancel.removeEventListener("click", this._doOnCancel, false);
			
			this._controllerNodes.hue.removeEventListener("change", this._doOnChangeHSL, false);
			this._controllerNodes.sat.removeEventListener("change", this._doOnChangeHSL, false);
			this._controllerNodes.lum.removeEventListener("change", this._doOnChangeHSL, false);
			
			this._controllerNodes.red.removeEventListener("change", this._doOnChangeRGB, false);
			this._controllerNodes.green.removeEventListener("change", this._doOnChangeRGB, false);
			this._controllerNodes.blue.removeEventListener("change", this._doOnChangeRGB, false);
			
			this._controllerNodes.hsv.removeEventListener("change", this._doOnChangeHSV, false);
			
		} else {
			this._controllerNodes.colorArea.detachEvent("onmousedown", this._initMoveSelection);
			this._controllerNodes.colorArea.detachEvent("ondblclick", this._doOnSelectColor);
			this._controllerNodes.contrastArea.detachEvent("onmousedown", this._initMoveContrast);
			
			this._controllerNodes.button_save.detachEvent("onclick", this._doOnSelectColor);
			this._controllerNodes.button_cancel.detachEvent("onclick", this._doOnCancel);
			
			this._controllerNodes.hue.detachEvent("onchange", this._doOnChangeHSL);
			this._controllerNodes.sat.detachEvent("onchange", this._doOnChangeHSL);
			this._controllerNodes.lum.detachEvent("onchange", this._doOnChangeHSL);
			
			this._controllerNodes.red.detachEvent("onchange", this._doOnChangeRGB);
			this._controllerNodes.green.detachEvent("onchange", this._doOnChangeRGB);
			this._controllerNodes.blue.detachEvent("onchange", this._doOnChangeRGB);
			
			this._controllerNodes.hsv.detachEvent("onchange", this._doOnChangeHSV);
		}
		
		if (this._controllerNodes.fr_cover) {
			if (this._controllerNodes.fr_cover.parentNode) {
				this._controllerNodes.fr_cover.parentNode.removeChild(this._controllerNodes.fr_cover);
			}
			delete this._controllerNodes.fr_cover;
		}
		
		dhx4.zim.clear(this.conf.cp_id);
		dhx4._eventable(this, "clear");
		
		for (key in this) {
			this[key] = null;
		}
		
		that = null, temp_node = null, temp_conf = null;
	};
	
	base_type = this._checkType(base);
	switch (base_type) {
		case "object":
			that._initByObject(base);
			break;
			
		case "input":
		case "textarea":
			that._initByObject({});
			this._addNode(base);
			break;
			
		case "string":
			temp_node = document.getElementById(base);
			return new dhtmlXColorPicker(temp_node);
			break;
			
		case "container":
			that._initByObject({
				parent: base
			});
			break;
			
		case null:
			that._initByObject({});
			break;
			
		case "array_string":
		case "array_input":
			that._initByObject({});
			l = base.length;
			for (i=0; i<l; i++) this._addNode(base[i]);
			break;
			
		case "array_object":
			that._initByObject({});
			l = base.length;
			for (i=0; i<l; i++) {
				temp_conf = this._addNode(base[i].input, base[i].target_color, base[i].target_value).conf;
				temp_conf.customColors = (base[i].custom_colors != undefined)? dhx4.s2b(base[i].custom_colors): temp_conf.customColors;
				temp_conf.selectedColor = (base[i].color != undefined)? base[i].color: temp_conf.selectedColor;
			}
			break;
	}
	
	this.base.innerHTML = "<div class='dhxcp_g_area'>"+
					"<div class='dhxcp_sub_area'>"+
						"<div class='dhxcp_g_color_area'>"+
							"<div class='dhxcp_color_selector'>"+
								"<div class='dhxcp_v_line'></div>"+
								"<div class='dhxcp_h_line'></div>"+
							"</div>"+
							"<div class='dhxcp_contrast_area'>"+
								"<div class='dhxcp_h_line'></div>"+
							"</div>"+
						"</div>"+
						//+
						"<div class='dhxcp_g_input_area'>"+
							"<div class='dhxcp_value_cont'>"+
								"<div class='dhxcp_value_color'></div>"+
								"<input type='text' class='dhxcp_value'/>"+
							"</div>"+
							"<table class='dhxcp_inputs_cont' cellpadding='0' cellspacing='0' border='0'>"+
								"<tr>"+
									"<td class='dhxcp_label_hsl'>"+this.i18n[this.conf.lang].labelHue+"</td>"+
									"<td class='dhxcp_input_hsl'><input type='text' class='dhxcp_input_hsl'/></td>"+
									"<td class='dhxcp_label_rgb'>"+this.i18n[this.conf.lang].labelRed+"</td>"+
									"<td class='dhxcp_input_rgb'><input type='text' class='dhxcp_input_rgb'/></td>"+
								"</tr>"+
								"<tr>"+
									"<td class='dhxcp_label_hsl'>"+this.i18n[this.conf.lang].labelSat+"</td>"+
									"<td class='dhxcp_input_hsl'><input type='text' class='dhxcp_input_hsl'/></td>"+
									"<td class='dhxcp_label_rgb'>"+this.i18n[this.conf.lang].labelGreen+"</td>"+
									"<td class='dhxcp_input_rgb'><input type='text' class='dhxcp_input_rgb'/></td>"+
								"</tr>"+
								"<tr>"+
									"<td class='dhxcp_label_hsl'>"+this.i18n[this.conf.lang].labelLum+"</td>"+
									"<td class='dhxcp_input_hsl'><input type='text' class='dhxcp_input_hsl'/></td>"+
									"<td class='dhxcp_label_rgb'>"+this.i18n[this.conf.lang].labelBlue+"</td>"+
									"<td class='dhxcp_input_rgb'><input type='text' class='dhxcp_input_rgb'/></td>"+
								"</tr>"+
							"</table>"+
						"</div>"+
						"<div class='dhxcp_g_memory_area'></div>"+
						"<div class='dhxcp_buttons_area'>"+
							"<button class='dhx_button_save'>"+this.i18n[this.conf.lang].btnSelect+"</button>"+
							"<button class='dhx_button_cancel'>"+this.i18n[this.conf.lang].btnCancel+"</button>"+
						"</div>"+
					"</div>"
				"</div>";
	
	this._globalNode = this.base.firstChild;
	
	this._controllerNodes = {
		colorArea: this._globalNode.firstChild.firstChild.firstChild,
		v_line   : this._globalNode.firstChild.firstChild.firstChild.childNodes[0],
		h_line   : this._globalNode.firstChild.firstChild.firstChild.childNodes[1],
		
		contrastArea : this._globalNode.firstChild.firstChild.childNodes[1],
		contrast_line: this._globalNode.firstChild.firstChild.childNodes[1].firstChild,
		
		color: this._globalNode.firstChild.childNodes[1].childNodes[0].firstChild,
		hsv  : this._globalNode.firstChild.childNodes[1].childNodes[0].childNodes[1],
		
		hue: this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[0].childNodes[1].firstChild,
		sat: this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[1].childNodes[1].firstChild,
		lum: this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[2].childNodes[1].firstChild,
		
		red  : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[0].childNodes[3].firstChild,
		green: this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[1].childNodes[3].firstChild,
		blue : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[2].childNodes[3].firstChild,
		
		memory_block: this._globalNode.firstChild.childNodes[2],
		
		button_save  : this._globalNode.firstChild.childNodes[3].firstChild,
		button_cancel: this._globalNode.firstChild.childNodes[3].childNodes[1]
	};
	
	this._labelNodes = {
		labelHue   : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[0].firstChild,
		labelSat   : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[1].firstChild,
		labelLum   : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[2].firstChild,
		labelRed   : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[0].childNodes[2],
		labelGreen : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[1].childNodes[2],
		labelBlue  : this._globalNode.firstChild.childNodes[1].childNodes[1].firstChild.childNodes[2].childNodes[2],
		btnAddColor: null,
		btnSelect  : this._globalNode.firstChild.childNodes[3].firstChild,
		btnCancel  : this._globalNode.firstChild.childNodes[3].childNodes[1]
	};
	
	if (typeof(this._tempInitCC) == "function") {
		this._tempInitCC();
	};
	
	if (typeof(window.addEventListener) == "function") {
		this._controllerNodes.colorArea.addEventListener("mousedown", this._initMoveSelection, false);
		this._controllerNodes.colorArea.addEventListener("dblclick", this._doOnSelectColor, false);
		this._controllerNodes.contrastArea.addEventListener("mousedown", this._initMoveContrast, false);
		
		this._controllerNodes.button_save.addEventListener("click", this._doOnSelectColor, false);
		this._controllerNodes.button_cancel.addEventListener("click", this._doOnCancel, false);
		
		this._controllerNodes.hue.addEventListener("change", this._doOnChangeHSL, false);
		this._controllerNodes.sat.addEventListener("change", this._doOnChangeHSL, false);
		this._controllerNodes.lum.addEventListener("change", this._doOnChangeHSL, false);
		
		this._controllerNodes.red.addEventListener("change", this._doOnChangeRGB, false);
		this._controllerNodes.green.addEventListener("change", this._doOnChangeRGB, false);
		this._controllerNodes.blue.addEventListener("change", this._doOnChangeRGB, false);
		
		this._controllerNodes.hsv.addEventListener("change", this._doOnChangeHSV, false);
	} else {
		this._controllerNodes.colorArea.attachEvent("onmousedown", this._initMoveSelection);
		this._controllerNodes.colorArea.attachEvent("ondblclick", this._doOnSelectColor);
		this._controllerNodes.contrastArea.attachEvent("onmousedown", this._initMoveContrast);
		
		this._controllerNodes.button_save.attachEvent("onclick", this._doOnSelectColor);
		this._controllerNodes.button_cancel.attachEvent("onclick", this._doOnCancel);
		
		this._controllerNodes.hue.attachEvent("onchange", this._doOnChangeHSL);
		this._controllerNodes.sat.attachEvent("onchange", this._doOnChangeHSL);
		this._controllerNodes.lum.attachEvent("onchange", this._doOnChangeHSL);
		
		this._controllerNodes.red.attachEvent("onchange", this._doOnChangeRGB);
		this._controllerNodes.green.attachEvent("onchange", this._doOnChangeRGB);
		this._controllerNodes.blue.attachEvent("onchange", this._doOnChangeRGB);
		
		this._controllerNodes.hsv.attachEvent("onchange", this._doOnChangeHSV);
	}
	
	this.setColor(this.conf.selectedColor||"#ffffff");
	
	if (this._nodes.length) {
		for (var i=0; i<this._nodes.length; i++) {
			this._attachEventsToNode(this._nodes[i]);
		}
	}
	
	if (this.conf.hide) {
		this.hide();
	}
	
	if (typeof(this._cpInitFRM) == "function") {
		this._cpInitFRM();
	}
}

dhtmlXColorPicker.prototype.linkTo = function(colorValue, element, value) {
	if (arguments.length == 1) {
		element = value = colorValue;
	}
	
	var object;
	colorValue = colorValue || null;
	value = value || null;
	
	if (typeof(element) == "string") {
		element = document.getElementById(element);
	}
	
	object = this._addNode(element,colorValue,value);
	if (object) {
		this._attachEventsToNode(object);
	}
	
	return object;
};

dhtmlXColorPicker.prototype._isBaseNode = function(node) {
	if (node == this.base) {
		return true;
	}
	
	if (node.parentElement == document.body) {
		return false;
	} else if (!node.parentElement) {
		return false;
	} else {
		return this._isBaseNode(node.parentElement);
	}
};

dhtmlXColorPicker.prototype._hasInput = function(node) {
	var i,l, answer = false;
	l = this._nodes.length;
	for (i=0; i<l; i++) {
		if (this._nodes[i].valueCont == node) {
			answer = true;
			break;
		}
	}
	
	return answer;
};

dhtmlXColorPicker.prototype._findNodesByArray = function(data) {
	var i, l, temp, answer = [];
	l = data.length;
	for (i = 0; i<l; i++) {
		if (typeof(data[i]) == "string") {
			temp = document.getElementById(data[i]);
		} else {
			temp = data[i];
		}
		
		if (temp) {
			this._addNode(temp);
		}
	}
};

dhtmlXColorPicker.prototype._addNode = function(node, valueColor, value) {
	var _node, _newCont, _valueColor, object;
	if (typeof(node) == "string") {
		_node = document.getElementById(node);
	} else {
		_node = node;
	}
	
	if (typeof(valueColor) == "string") {
		valueColor = document.getElementById(valueColor);
	}
	
	if (typeof(value) == "string") {
		value = document.getElementById(value);
	}
	
	if (!_node) return null;
	
	if (dhx4.s2b(_node.getAttribute("colorbox"))) {
		_newCont = document.createElement('div');
		_newCont.style.width=_node.offsetWidth+"px";
		_newCont.style.height=_node.offsetHeight+"px";
		_node.style.width=_node.offsetWidth-(_node.offsetHeight+8)+"px";
		_node.parentNode.insertBefore(_newCont,_node);
		_newCont.style.position="relative";
		_valueColor=document.createElement("div");
		_newCont.appendChild(_node);
		_newCont.appendChild(_valueColor);
		_valueColor.className="dhxcp_colorBox";
		_node.className+=" dhxcp_colorInput";
		_valueColor.style.width=_valueColor.style.height= _node.offsetHeight+"px";
	}
	
	object = {
		node: _node,
		valueColor: (valueColor != undefined)? valueColor : _valueColor || _node,
		valueCont: (value != undefined)? value : _node,
		conf: {
			customColors: (_node.getAttribute("customcolors") != null) ? dhx4.s2b(_node.getAttribute("customcolors")) : null,
			selectedColor: _node.getAttribute("selectedcolor")
		}
	};
	
	this._nodes.push(object);
	
	if (!this.activeNode) {
		this.activeNode = object;
	}
	
	return object;
};

dhtmlXColorPicker.prototype.getNode = function(base) {
	var element = null, node = null;
	
	if (typeof(base) == "string") {
		element = document.getElementById(base);
	} else {
		element = base;
	}
	
	if (element.tagName != undefined) {
		node = this._getNodeByElement(element);
	}
	
	return node;
};

dhtmlXColorPicker.prototype._getNodeByElement = function(element) {
	var answer = null,i,l;
	l = this._nodes.length;
	for (i=0; i<l; i++) {
		if (this._nodes[i].node == element) {
			answer = this._nodes[i];
		}
	}
	
	return answer;
};

dhtmlXColorPicker.prototype._getNodeByValueCont = function(element) {
	var answer = null,i,l;
	l = this._nodes.length;
	for (i=0; i<l; i++) {
		if (this._nodes[i].valueCont &&  this._nodes[i].valueCont == element) {
			answer = this._nodes[i];
		}
	}
	
	return answer;
};

dhtmlXColorPicker.prototype.initMemoryColors = function() {
	
	var that = this;
	
	this._controllerNodes.memory_block.innerHTML = "<div class='dhxcp_memory_button_cont'>"+
								"<button class='dhxcp_save_to_memory'>"+
									"<div class='dhxcp_label_bm'>"+this.i18n[this.conf.lang].btnAddColor+"</div>"+
								"</button>"+
							"</div>"+
							"<div class='dhxcp_memory_els_cont'>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
								"<a class='dhxcp_memory_el'></a>"+
							"</div>";
	
	this.memory = new this.Memory(this._controllerNodes.memory_block.childNodes[1]);
	this.memory.onSelect = this._onSelectMemoryEl;
	this.memory.onSave = function(value) {
		var color = that.colorAIP.rgb2hex({r: value.red, g: value.green, b: value.blue});
		that.callEvent("onSaveColor", [color]);
	};
	
	var button = this._controllerNodes.memory_block.childNodes[0].firstChild;
	this._labelNodes.btnAddColor = this._controllerNodes.memory_block.childNodes[0].firstChild.firstChild;
	if (typeof(window.addEventListener) == "function") {
		button.addEventListener("click", this.saveColor, false);
	} else {
		button.attachEvent("onclick", this.saveColor);
	}
};

dhtmlXColorPicker.prototype._refreshCoordinatesByHSL = function(h,s,l) {
	var x,y1,y2;
	x = Math.round((this.configColorArea.maxX - this.configColorArea.minX)*h/359)+this.configColorArea.minX;
	y1 = Math.round((this.configColorArea.maxY - this.configColorArea.minY)*(100-l)/100) + this.configColorArea.minY;
	y2 = Math.round((this.configColorArea.maxY - this.configColorArea.minY)*(100-s)/100) + this.configColorArea.minY;
	
	this._setColorAreaXY(x,y1);
	this._setContrastY(y2);
};

dhtmlXColorPicker.prototype._parseColor = function(value) {
	if (value instanceof Array) {
		var rgb = {
			r: parseInt(value[0]),
			g: parseInt(value[1]),
			b: parseInt(value[2])
		};
	} else if (typeof(value) == "string") {
		value = value.replace(/\s/g,"");
		if (/^rgb\((\d{1,3})\,(\d{1,3})\,(\d{1,3})\)$/i.test(value)) {
			var temp = value.match(/^rgb\((\d{1,3})\,(\d{1,3})\,(\d{1,3})\)$/i);
			var rgb = {
				r: parseInt(temp[1]),
				g: parseInt(temp[2]),
				b: parseInt(temp[3])
			};
		} else {
			var rgb = this.colorAIP.hex2rgb(value);
		}
	}
	return rgb;
};

dhtmlXColorPicker.prototype.setColor = function(value) {
	
	var old_value = this.colorAIP.rgb2hex({ r:this.value.red, g:this.value.green, b:this.value.blue});
	var rgb = this._parseColor(value);
	
	var is_check = (rgb instanceof Object);
	is_check = is_check && (0 <= rgb.r && rgb.r <= 255);
	is_check = is_check && (0 <= rgb.g && rgb.g <= 255);
	is_check = is_check && (0 <= rgb.b && rgb.b <= 255);
	
	if (!is_check) {
		return old_value;
	}
	
	var new_value = this.colorAIP.rgb2hex({ r:rgb.r, g:rgb.g, b:rgb.b });
	
	if (new_value == old_value) {
		return old_value;
	}
	
	this.value.red = rgb.r;
	this.value.green = rgb.g;
	this.value.blue = rgb.b;
	
	var hsl = this.colorAIP.rgb2hsl(rgb.r/255,rgb.g/255,rgb.b/255);
	this.value.hue = Math.round(hsl.h);
	this.value.sat = Math.round(hsl.s*100);
	this.value.lum = Math.round(hsl.l*100);
	
	this._refreshCoordinatesByHSL(this.value.hue, this.value.sat, this.value.lum);
	this._refreshContrast();
	this._refreshInputValues();
	this._refreshColorValue();
	
	return new_value;
};

dhtmlXColorPicker.prototype.getSelectedColor = function() {
	return [
		this.colorAIP.rgb2hex({ r:this.value.red, g:this.value.green, b:this.value.blue }),
		[this.value.red, this.value.green, this.value.blue],
		[this.value.hue, this.value.sat, this.value.lum]
	];
};

dhtmlXColorPicker.prototype._attachEventsToNode = function(object) {
	if (typeof(window.addEventListener) == "function") {
		object.node.addEventListener("click", this._doOnClickByNode, false);
	} else {
		object.node.attachEvent("onclick", this._doOnClickByNode);
	}
	
	if (object.valueCont && object.valueCont.tagName.toLowerCase() == "input") {
		if (typeof(window.addEventListener) == "function") {
			object.valueCont.addEventListener("focus", this._doOnFocusByInput, false);
			object.valueCont.addEventListener("blur", this._doOnBlurByInput, false);
		} else {
			object.valueCont.attachEvent("onfocus", this._doOnFocusByInput);
			object.valueCont.attachEvent("onblur", this._doOnBlurByInput);
		}
	}
};

dhtmlXColorPicker.prototype._detachEventsFromNode = function(object) {
	if (typeof(window.addEventListener) == "function") {
		object.node.removeEventListener("click", this._doOnClickByNode, false);
	} else {
		object.node.detachEvent("onclick", this._doOnClickByNode);
	}
	
	if (object.valueCont && object.valueCont.tagName.toLowerCase() == "input") {
		if (typeof(window.addEventListener) == "function") {
			object.valueCont.removeEventListener("focus", this._doOnFocusByInput, false);
			object.valueCont.removeEventListener("blur", this._doOnBlurByInput, false);
		} else {
			object.valueCont.detachEvent("onfocus", this._doOnFocusByInput);
			object.valueCont.detachEvent("onblur", this._doOnBlurByInput);
		}
	}
};

dhtmlXColorPicker.prototype.show = function(node) {
	var is_show_memory = false;
	
	if (node != undefined) {
		this.activeNode = this.getNode(node) || this.activeNode;
	}
	
	if (this.activeNode && this.activeNode.valueCont && this.activeNode.valueCont.value) {
		this.setColor(this.activeNode.valueCont.value);
	}
	
	if (this.activeNode) {
		is_show_memory = (this.activeNode.conf.customColors!=null?this.activeNode.conf.customColors:this.conf.customColors);
		this.setColor(this.activeNode.conf.selectedColor);
	} else {
		is_show_memory = this.conf.customColors;
	}
	
	if (is_show_memory) {
		this.showMemory();
	} else {
		this.hideMemory();
	}
	
	if (this.base._dhx_remove) {
		this.base.firstChild.style.zIndex = dhx4.zim.reserve(this.conf.cp_id);
		
		this.base.style.visibility = "hidden";
		if (document.body.firstChild) {
			document.body.insertBefore(this.base, document.body.firstChild);
		} else {
			document.body.appendChild(this.base);
		}
		
		this._refreshPosition();
		this.base.style.visibility = "visible";
		
		if (typeof(window.addEventListener) == "function") {
			document.body.addEventListener("mousedown", this._doOnClickByBody, false);
		} else {
			document.body.attachEvent("onmousedown", this._doOnClickByBody);
		}
		
	} else {
		this.base.appendChild(this._globalNode);
	}
	
	if (this._controllerNodes.fr_cover) {
		this.base.insertBefore(this._controllerNodes.fr_cover, this._globalNode);
	}
	
	this.callEvent("onShow",[((this.activeNode)? this.activeNode.node: null) ]);
};

dhtmlXColorPicker.prototype.setPosition = function(x,y) {
	var pos = null;
	var cx = parseInt(x);
	var cy = parseInt(y);
	if (isNaN(cx)) pos = ({right:"right",bottom:"bottom"}[x.toLowerCase()]?x:null);
	
	if (this.base._dhx_remove) {
		if (pos == null) {
			
		} else {
			this.conf.position = pos;
			this._refreshPosition(pos);
		}
		
	} else {
		if (isNaN(cx) || isNaN(cy)) {
			
		} else {
			this._globalNode.style.left = cx + "px";
			this._globalNode.style.top = cy + "px";
			
			if (this._controllerNodes.fr_cover) {
				this._controllerNodes.fr_cover.style.left = this._globalNode.style.left;
				this._controllerNodes.fr_cover.style.top = this._globalNode.style.top;
			}
		}
	}
};

dhtmlXColorPicker.prototype._initListenerInput = function() {
	var that = this;
	this._inputListenerId = this._inputListenerId || setInterval(function() {
			that._refreshValueByInput();
	},70);
};

dhtmlXColorPicker.prototype._removeListenerInput = function() {
	if (this._inputListenerId) {
		clearInterval(this._inputListenerId);
		this._inputListenerId = null;
	}
};

dhtmlXColorPicker.prototype._refreshValueByInput = function() {
	var value = this.activeNode.valueCont.value,
	oldValue = this.getSelectedColor()[0];
	
	if (this._inputListenerId) {
		if (/^#[\da-f]{6}$/i.test(value) && value != oldValue) {
			this.setColor(value);
			this.callEvent("onSelect",[value, this.activeNode.node]);
		}
	}
};

dhtmlXColorPicker.prototype._refreshPosition = function(position) {
	if (this.activeNode == null) return;
	
	var topInp = dhx4.absTop(this.activeNode.node);
	var leftInp = dhx4.absLeft(this.activeNode.node);
	var sizeWindow = dhx4.screenDim();
	var widthCP = this._globalNode.offsetWidth;
	var heigthCP = this._globalNode.offsetHeight;
	var top = 0;
	
	position = position || this.conf.position;
	
	switch (position) {
		case "bottom":
			var top = topInp+this.activeNode.node.offsetHeight+this.conf.indent;
			var left = leftInp;
			
			// no space on right
			if (left+widthCP > sizeWindow.left+sizeWindow.right) {
				left = leftInp+this.activeNode.node.offsetWidth-widthCP;
			}
			//no space on left
			if (left < sizeWindow.left) {
				left = leftInp;
			}
			
			// no space at bottom
			if (top+heigthCP > sizeWindow.top+sizeWindow.bottom) {
				top = topInp-heigthCP-this.conf.indent;
			}
			
			// on top also no more space (browser's height too small?)
			if (top - sizeWindow.top < 0) {
				top = sizeWindow.top + this.conf.indent;
			}
			
			this._globalNode.style.top = top+"px";
			this._globalNode.style.left = left+"px";
			
			
			break;
		default:
			var left = leftInp+this.activeNode.node.offsetWidth+this.conf.indent;
			top = topInp;
			
			if (position == "right") {
				// no space on right, open to left
				if (left+widthCP > sizeWindow.left+sizeWindow.right) {
					left = leftInp-widthCP-this.conf.indent;
				}
				// no space on left, open to right
				if (left < sizeWindow.left) {
					left = leftInp+this.activeNode.node.offsetWidth+this.conf.indent;
				}
			}
			
			if (sizeWindow.bottom - (top + heigthCP) <= 0) {
				top = topInp+this.activeNode.node.offsetHeight-heigthCP;
			}
			
			if (top - sizeWindow.top < 0) {
				top = sizeWindow.top + this.conf.indent;
			}
			this._globalNode.style.left = left+"px";
			this._globalNode.style.top = top+"px";
	}
	
	if (this._controllerNodes.fr_cover) {
		this._controllerNodes.fr_cover.style.left = this._globalNode.style.left;
		this._controllerNodes.fr_cover.style.top = this._globalNode.style.top;
	}
};

dhtmlXColorPicker.prototype.isVisible = function() {
	var answer = false;
	if (this.base._dhx_remove) {
		answer = this.base.parentNode == document.body;
	} else {
		answer = this._globalNode.parentNode == this.base;
	}
	
	return answer;
};

dhtmlXColorPicker.prototype.hide = function() {
	if (this.base._dhx_remove) {
		if (this.base.parentNode) {
			this.base.parentNode.removeChild(this.base);
			dhx4.zim.clear(this.conf.cp_id);
			if (typeof(window.addEventListener) == "function") {
				document.body.removeEventListener("mousedown", this._doOnClickByBody, false);
			} else {
				document.body.detachEvent("onmousedown", this._doOnClickByBody);
			}
		}
	} else {
		if (this.isVisible() == false) return;
		this.base.removeChild(this._globalNode);
	}
	
	if (this._controllerNodes.fr_cover && this._controllerNodes.fr_cover.parentNode) {
		this._controllerNodes.fr_cover.parentNode.removeChild(this._controllerNodes.fr_cover);
	}
	
	if (this.callEvent != undefined) {
		this.callEvent("onHide",[((this.activeNode)? this.activeNode.node: null) ]);
	}
};

dhtmlXColorPicker.prototype.configColorArea = {
	minX: 1,
	maxX: 209,
	minY: 1,
	maxY: 119
};

dhtmlXColorPicker.prototype._skinCollection = {
	dhx_skyblue: true,
	dhx_web: true,
	dhx_terrace: true,
	material: true
};

dhtmlXColorPicker.prototype.i18n = {
	en: {
		labelHue   : "Hue",
		labelSat   : "Sat",
		labelLum   : "Lum",
		labelRed   : "Red",
		labelGreen : "Green",
		labelBlue  : "Blue",
		btnAddColor: "Save the color",
		btnSelect  : "Select",
		btnCancel  : "Cancel"
	}
};

dhtmlXColorPicker.prototype.loadUserLanguage = function(ln) {
	// deprecated
	if (typeof(this._mergeLangModules) == "function") { 
		this._mergeLangModules();
	}
	//
	
	this.conf.lang = ln;
	this._refreshLanguage();
};

dhtmlXColorPicker.prototype._refreshLanguage = function() {
	var key, hash= this.i18n[this.conf.lang];
	for (key in hash) {
		if (this._labelNodes[key]) {
			this._labelNodes[key].innerHTML = hash[key];
		}
	}
};

dhtmlXColorPicker.prototype._setColorAreaXY = function(x,y) {
	var config = this.configColorArea;
	
	x = parseInt(x);
	if (config.minX > x) {
		this.conf.x = config.minX;
	} else if (x > config.maxX) {
		this.conf.x = config.maxX;
	} else if (!isNaN(x)) {
		this.conf.x = x;
	}
	
	y = parseInt(y);
	if (config.minY > y) {
		this.conf.y = config.minY;
	} else if (y > config.maxY) {
		this.conf.y = config.maxY;
	} else if (!isNaN(y)) {
		this.conf.y = y;
	}
	
	this._refreshLines();
};

dhtmlXColorPicker.prototype._setColorByXYC = function(notRefreshC) {
	notRefreshC = notRefreshC || false;
	
	this.value.hue = Math.round((359*(this.conf.x - this.configColorArea.minX)) / (this.configColorArea.maxX - this.configColorArea.minX));
	this.value.lum = Math.round(100 - (100*(this.conf.y - this.configColorArea.minY)) / (this.configColorArea.maxY - this.configColorArea.minY));
	this.value.sat = Math.round(100 - (100*(this.conf.c - this.configColorArea.minY)) / (this.configColorArea.maxY - this.configColorArea.minY));
	
	var rgb = this.colorAIP.hsl2rgb(this.value.hue, this.value.sat/100, this.value.lum/100);
	
	this.value.red = Math.round(255*rgb.r);
	this.value.green = Math.round(255*rgb.g);
	this.value.blue = Math.round(255*rgb.b);
	
	if (!notRefreshC) {
		this._refreshContrast();
	} 
	
	this._refreshInputValues();
	this._refreshColorValue();
};

dhtmlXColorPicker.prototype._setContrastY = function(y) {
	var config = this.configColorArea;
	
	y = parseInt(y);
	if (!isNaN(y)) {
		this.conf.c = Math.min(Math.max(config.minY, y), config.maxY);
	}
	
	this._refreshContrastLine();
};

dhtmlXColorPicker.prototype._refreshInputValues = function() {
	this._controllerNodes.hue.value = this.value.hue;
	this._controllerNodes.sat.value = this.value.sat;
	this._controllerNodes.lum.value = this.value.lum;
	
	this._controllerNodes.red.value = this.value.red;
	this._controllerNodes.green.value = this.value.green;
	this._controllerNodes.blue.value = this.value.blue;
};

dhtmlXColorPicker.prototype._refreshColorValue = function() {
	this._controllerNodes.color.style.backgroundColor = "rgb("+[this.value.red, this.value.green, this.value.blue].join(", ")+")";
	
	var hex = this.colorAIP.rgb2hex({
		r: this.value.red,
		g: this.value.green,
		b: this.value.blue
	});
	
	this._controllerNodes.hsv.value = hex;
	this.callEvent("onChange",[hex]);
};

dhtmlXColorPicker.prototype._refreshContrast = function() {
	var rgb_top = this.colorAIP.hsl2rgb(this.value.hue, 0, this.value.lum/100);
	var rgb_bot = this.colorAIP.hsl2rgb(this.value.hue, 1, this.value.lum/100);
	
	var ieV = this._checkIeVersion();
	if (ieV && ieV<=9) {
		var ie_gradient = this._controllerNodes.contrastArea.firstChild;
		if (ie_gradient == this._controllerNodes.contrast_line) {
			ie_gradient = document.createElement("div");
			ie_gradient.className += "dhxcp_ie_gradient";
			this._controllerNodes.contrastArea.appendChild(ie_gradient);
			this._controllerNodes.contrastArea.appendChild(this._controllerNodes.contrast_line);
		}
		var hex_bot = this.colorAIP.rgb2hex({r: Math.round(255*rgb_top.r) ,g: Math.round(255*rgb_top.g) ,b: Math.round(255*rgb_top.b)});
		var hex_top = this.colorAIP.rgb2hex({r: Math.round(255*rgb_bot.r) ,g: Math.round(255*rgb_bot.g) ,b: Math.round(255*rgb_bot.b)});
		ie_gradient.style.filter = "progid:DXImageTransform.Microsoft.gradient(startColorstr='"+hex_top+"', endColorstr='"+hex_bot+"', GradientType=0)";
	} else {
		rgb_top = [Math.round(255*rgb_top.r), Math.round(255*rgb_top.g), Math.round(255*rgb_top.b)];
		rgb_bot = [Math.round(255*rgb_bot.r), Math.round(255*rgb_bot.g), Math.round(255*rgb_bot.b)];
		var bg_img = "linear-gradient(rgb("+rgb_bot.join(",")+"), rgb("+rgb_top.join(",")+"))";
		if (window.dhx4.isKHTML == true && navigator.userAgent.match(/Windows/gi) != null) bg_img = "-webkit-"+bg_img; // for win/safari 5.1.7
		this._controllerNodes.contrastArea.style.backgroundImage = bg_img;
	}
};

dhtmlXColorPicker.prototype._refreshLines = function() {
	this._controllerNodes.v_line.style.left = this.conf.x+"px";
	this._controllerNodes.h_line.style.top = this.conf.y+"px";
};

dhtmlXColorPicker.prototype._refreshContrastLine = function() {
	this._controllerNodes.contrast_line.style.top = this.conf.c+"px";
};

dhtmlXColorPicker.prototype._getOffsetPosition = function(e, node) {
	var answer = {
		x: NaN,
		y: NaN
	}, target = e.target || e.srcElement;
	if (target == node) {
		answer.x = (e.offsetX != undefined)? e.offsetX: e.layerX;
		answer.y = (e.offsetY != undefined)? e.offsetY: e.layerY;
	} else if (target == this._controllerNodes.v_line) {
		answer.y = (e.offsetY != undefined)? e.offsetY: e.layerY;
	} else {
		answer.x = (e.offsetX != undefined)? e.offsetX: e.layerX;
	}
	
	return answer;
};

dhtmlXColorPicker.prototype.colorAIP = {
	hex2rgb: function(str) {
		var data = str.match(/^(#)([\da-f]{2})([\da-f]{2})([\da-f]{2})$/i);
		if (data != null) {
			return {
				r: parseInt("0x"+data[2]),
				g: parseInt("0x"+data[3]),
				b: parseInt("0x"+data[4])
			};
		} else {
			return null;
		}
	},
	// data {r:.., g:.., b:..}
	rgb2hex: function(data) {
		var r = parseInt(data.r), g = parseInt(data.g), b = parseInt(data.b);
		r = r||0, g = g||0, b = b||0;
		return "#"+((r)? ((r<16)? "0"+r.toString(16):r.toString(16)): "00") +((g)? ((g<16)? "0"+g.toString(16):g.toString(16)): "00") +((b)? ((b<16)? "0"+b.toString(16):b.toString(16)): "00");
	},
	
	// {float} r [0..1], g [0..1], b [0..1], returns {object} hsl
	rgb2hsl: function(r,g,b) {
		var H, S, L;
		var max = Math.max(r,g,b), min = Math.min(r,g,b);
		
		//** L **
		L = 0.5*(max + min);
		
		//** H **
		if (max == min) H = 0;
		else if (max == r) {
			H = 60*(g - b)/(max-min);
			if (g < b) H += 360;
		} else if (max == g) H = 60*(b - r)/(max - min) + 120;
		else H = 60*(r-g)/(max - min) + 240;
		
		//** S **
		if (L == 0 || max == min) S = 0;
		else if (L <= 0.5) S = 0.5*(max - min)/L;
		else S = 0.5*(max - min)/(1 - L);
		
		return {
			h: H,
			s: S,
			l: L
		};
	},
	
	// {int} H [0..359], {float} S [0..1], L [0..1] returns {object} rgb [0..1]
	hsl2rgb: function(H,S,L) {
		var Q,P,_H;
		var T = [], RGB = [];
		
		// ** Q **
		if (L <= 0.5) Q = L*(1+S);
		else Q = L + S - (L*S);
		
		//** P **
		P = 2*L - Q;
		
		//** H ***
		_H = H/360;
		
		T.push(_H + 1/3);
		T.push(_H);
		T.push(_H - 1/3);
		
		for (var i = 0; i<3; i++) {
			if (T[i]<0) T[i] += 1;
			else if (T[i]>1) T[i] -= 1;
			
			if (T[i] < 1/6) RGB.push(P + (Q - P)*6*T[i]);
			else if (T[i] < 0.5) RGB.push(Q);
			else if (T[i] < 2/3) RGB.push(P + (Q - P)*(2/3 - T[i])*6);
			else RGB.push(P);
		}
		
		return {
			r: RGB[0],
			g: RGB[1],
			b: RGB[2]
		};
	}
};

dhtmlXColorPicker.prototype._checkIeVersion = function() {
	var answer;
	var str  = navigator.userAgent.match(/(MSIE)\s(\d\.\d)/i);
	answer = (str && str[2])? parseInt(str[2]): null;
	return answer;
};

dhtmlXColorPicker.prototype.setCustomColors = function() {
	if (this.memory == null) {
		this.initMemoryColors();
		this.conf.customColors = true;
	}
	
	var i, l, value,colors,
	q,w;
	l = arguments.length;
	for (i=0; i<l; i++) {
		if (arguments[i] instanceof Array) {
			value = this._rgb2value(arguments[i][0],arguments[i][1],arguments[i][2]);
			this.memory.setValue(value,null,false);
			continue;
			
		} else if (typeof(arguments[i]) == "string") {
			colors = arguments[i].match(/^rgb\((\d{1,3})\,(\d{1,3})\,(\d{1,3})\)$/i);
			if (colors instanceof Array) {
				value = this._rgb2value(colors[1],colors[2],colors[3]);
				this.memory.setValue(value,null,false);
				continue;
			}
			
			colors = arguments[i].match(/(#[\da-f]{6})/ig);
			if (colors instanceof Array) {
				w = colors.length;
				for (q=0; q<w; q++) {
					value = this._hex2value(colors[q]);
					this.memory.setValue(value,null,false);
				}
			} 
			
		}
	}
};

dhtmlXColorPicker.prototype.getCustomColors = function() {
	var k = [];
	if (this.memory != null && this.conf.customColors == true) {
		for (var q=0; q<this.memory.controllers.length; q++) {
			var v = this.memory.controllers[q].value;
			k.push(this.colorAIP.rgb2hex({r: v.red, g: v.green, b: v.blue}));
		}
	}
	return k;
};

dhtmlXColorPicker.prototype._rgb2value = function(r,g,b) {
	var hsl = this.colorAIP.rgb2hsl(r/255,g/255,b/255);
	
	return {
		red: r,
		green: g,
		blue: b,
		hue: Math.round(hsl.h),
		sat: Math.round(hsl.s*100),
		lum: Math.round(hsl.l*100)
	};
};

dhtmlXColorPicker.prototype._hex2value = function(str) {
	var rgb, hsl;
	rgb = this.colorAIP.hex2rgb(str);
	hsl = this.colorAIP.rgb2hsl(rgb.r/255,rgb.g/255,rgb.b/255);
	
	return {
		red: rgb.r,
		green: rgb.g,
		blue: rgb.b,
		hue: Math.round(hsl.h),
		sat: Math.round(hsl.s*100),
		lum: Math.round(hsl.l*100)
	};
};

dhtmlXColorPicker.prototype.showMemory = function() {
	if (this.memory == null) {
		this.initMemoryColors();
	}
	
	if (!this._globalNode.className.match(/dhxcp_add_memory/)) {
		this._globalNode.className += " dhxcp_add_memory";
	}
	
	this.conf.customColors = true;
};

dhtmlXColorPicker.prototype.hideMemory = function() {
	if (this.memory != null) {
		this._globalNode.className = this._globalNode.className.replace(/\sdhxcp_add_memory/, "");
	}
	
	this.conf.customColors = false;
};

dhtmlXColorPicker.prototype.setSkin = function (skin) {
	
	if (this._skinCollection[skin] != true) return;
	
	var r = "dhtmlxcp_"+(this.skin||"dummy");
	this.base.className = this.base.className.replace(new RegExp(r), "");
	this.base.className += " dhtmlxcp_"+skin;
	this.skin = skin;
	
	if (this.skin == "material") {
		var t = {labelHue: "H", labelSat: "S", labelLum: "L", labelRed: "R", labelGreen: "G", labelBlue: "B"};
		for (var a in t) this.i18n.en[a] = t[a];
	}
	
	if (this.base.className.match(/dhxcp_shadow/) == null && !(dhx4.isIE6 || dhx4.isIE7 || dhx4.isIE8)) {
		this.base.className += " dhxcp_shadow";
	}
};

dhtmlXColorPicker.prototype.hideOnSelect = function(value) {
	value = dhx4.s2b(value);
	this.conf.hideOnSelect = value;
};

dhtmlXColorPicker.prototype._indexOf = function(arr, el) {
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

dhtmlXColorPicker.prototype.destructMemory = function() {
	if (this.memory == null) {
		return;
	} 
	this.hideMemory();
	
	var button = this._controllerNodes.memory_block.childNodes[0].firstChild;
	this._labelNodes.btnAddColor =  null;
	if (typeof(window.addEventListener) == "function") {
		button.removeEventListener("click", this.saveColor, false);
	} else {
		button.detachEvent("onclick", this.saveColor);
	}
	
	this.memory.remove();
	
	this._controllerNodes.memory_block.innerHTML = "";
	this.memory = null;
};

dhtmlXColorPicker.prototype.Memory = function(base) {
	var  that = this, selected = null,
	dfValue = {
		red: 255,
		blue: 255,
		green: 255,
		hue: 0,
		sat: 0,
		lum: 100
	};
	
	this.select = function() {
		var target  = (this != window)? this: event.srcElement;
		var contr = contr || target.dhxpc_memory;
		
		if (selected != null) {
			that.unSelect();
		}
		selected = contr;
		contr.domElement.className += " dhxcp_memory_el_select";
		
		if (typeof(that.onSelect) == "function") {
			that.onSelect(contr);
		}
	};
	
	this.onSelect = null;
	this.onSave = null;
	
	this._createMemoryController = function (el) {
		var data = {
			domElement: el,
			value: dhx4._copyObj(dfValue)
		};
		
		el.dhxpc_memory = data;
		
		if (typeof(window.addEventListener) == "function") {
			el.addEventListener("click", that.select, false);
		} else {
			el.attachEvent("onclick", that.select);
		}
		
		return data;
	};
	
	this._findMemoryControllers = function (base) {
		var divs = base.getElementsByTagName("a"),i,l,
		answer = [];
		l = divs.length;
		for (i=0; i<l; i++) {
			answer.push(this._createMemoryController(divs[i]));
		}
		
		return answer;
	};
	
	this.controllers = this._findMemoryControllers(base);
	
	this.unSelect = function() {
		if (selected) selected.domElement.className = selected.domElement.className.replace(/\s.*$/i, "");
		selected = null;
	};
	
	this.setActiveNext = function() {
		var ind = null, l;
		if (selected == null) {
			selected = this.controllers[0];
		} else {
			ind = this.getIndex(selected);
			l = this.controllers.length;
			this.unSelect();
			selected = (ind+1 < l)?  this.controllers[ind+1]:this.controllers[0];
		}
		selected.domElement.className += " dhxcp_memory_el_next";
		return selected;
	};
	
	this.setValue = function(value, contr, callEvent) {
		selected = contr || selected;
		if (selected == null) {
			selected = this.controllers[0];
		}
		
		selected.value.red = value.red;
		selected.value.blue = value.blue;
		selected.value.green = value.green;
		selected.value.hue = value.hue;
		selected.value.sat = value.sat;
		selected.value.lum = value.lum;
		
		selected.domElement.style.backgroundColor = "rgb("+[value.red, value.green, value.blue].join(", ")+")";
		
		this.setActiveNext();
		
		if ((callEvent != false) && (typeof(this.onSave) == "function")) this.onSave(value);
	};
	
	this.clean = function() {
		var l = this.controllers.length,
		item;
		for (var i=0; i<l; i++) {
			item = this.controllers[i];
			item.value = dhx4._copyObj(dfValue);
			item.domElement.style.backgroundColor = "rgb("+[dfValue.red, dfValue.green, dfValue.blue].join(", ")+")";
		}
		this.unSelect();
		selected = this.controllers[0];
	};
	
	this.getIndex = function(selected) {
		var i,l,answer = -1;
		
		l = this.controllers.length;
		for (i=0; i<l; i++) {
			if (this.controllers[i] == selected) {
				answer = i;
				break;
			}
		}
		
		return answer;
	};
	
	this.remove = function() {
		var i,l;
		l = this.controllers.length;
		for (i=0; i<l; i++) {
			if (typeof(window.addEventListener) == "function") {
				this.controllers[i].domElement.removeEventListener("click", this.select, false);
			} else {
				this.controllers[i].domElement.detachEvent("onclick", this.select);
			}
			
			delete this.controllers[i].domElement.dhxpc_memory;
		}
		delete this.onSelect;
		delete this.controllers;
		delete this.onSave;
	};
};

if (window.dhx4.isIE6) {
	dhtmlXColorPicker.prototype._cpInitFRM = function() {
		var f;
		if (!this._controllerNodes.fr_cover) {
			f = document.createElement("IFRAME");
			f.className = "dhxcp_frm";
			f.border = 0;
			f.frameBorder = 0;
			this._controllerNodes.fr_cover = f;
		}
		
		if (!this.base._dhx_remove) {
			this.base.insertBefore(f, this._globalNode);
		}
	};
};
