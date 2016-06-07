/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/* dhtmlx.com */

if (typeof(window.dhx) == "undefined") {
	
	window.dhx = window.dhx4 = {
		
		version: "5.0",
		
		skin: null, // allow to be set by user
		
		skinDetect: function(comp) {
			return {10:"dhx_skyblue",20:"dhx_web",30:"dhx_terrace",40:"material"}[this.readFromCss(comp+"_skin_detect")]||null;
		},
		
		// read value from css
		readFromCss: function(className, property, innerHTML) {
			var t = document.createElement("DIV");
			t.className = className;
			if (document.body.firstChild != null) document.body.insertBefore(t, document.body.firstChild); else document.body.appendChild(t);
			if (typeof(innerHTML) == "string") t.innerHTML = innerHTML;
			var w = t[property||"offsetWidth"];
			t.parentNode.removeChild(t);
			t = null;
			return w;
		},
		
		// id manager
		lastId: 1,
		newId: function() {
			return this.lastId++;
		},
		
		// z-index manager
		zim: {
			data: {},
			step: 5,
			first: function() {
				return 100;
			},
			last: function() {
				var t = this.first();
				for (var a in this.data) t = Math.max(t, this.data[a]);
				return t;
			},
			reserve: function(id) {
				this.data[id] = this.last()+this.step;
				return this.data[id];
			},
			clear: function(id) {
				if (this.data[id] != null) {
					this.data[id] = null;
					delete this.data[id];
				}
			}
		},
		
		// string to boolean
		s2b: function(r) {
			if (typeof(r) == "string") r = r.toLowerCase();
			return (r == true || r == 1 || r == "true" || r == "1" || r == "yes" || r == "y" || r == "on");
		},
		
		// string to json
		s2j: function(s) {
			var obj = null;
			dhx4.temp = null;
			try { eval("dhx4.temp="+s); } catch(e) { dhx4.temp = null; }
			obj = dhx4.temp;
			dhx4.temp = null;
			return obj;
		},
		
		// absolute top/left position on screen
		absLeft: function(obj) {
			if (typeof(obj) == "string") obj = document.getElementById(obj);
			return this.getOffset(obj).left;
		},
		absTop: function(obj) {
			if (typeof(obj) == "string") obj = document.getElementById(obj);
			return this.getOffset(obj).top;
		},
		_aOfs: function(elem) {
			var top = 0, left = 0;
			while (elem) {
				top = top + parseInt(elem.offsetTop);
				left = left + parseInt(elem.offsetLeft);
				elem = elem.offsetParent;
			}
			return {top: top, left: left};
		},
		_aOfsRect: function(elem) {
			var box = elem.getBoundingClientRect();
			var body = document.body;
			var docElem = document.documentElement;
			var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop;
			var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft;
			var clientTop = docElem.clientTop || body.clientTop || 0;
			var clientLeft = docElem.clientLeft || body.clientLeft || 0;
			var top  = box.top +  scrollTop - clientTop;
			var left = box.left + scrollLeft - clientLeft;
			return { top: Math.round(top), left: Math.round(left) };
		},
		getOffset: function(elem) {
			if (elem.getBoundingClientRect) {
				return this._aOfsRect(elem);
			} else {
				return this._aOfs(elem);
			}
		},
		
		// copy obj
		_isObj: function(k) {
			return (k != null && typeof(k) == "object" && typeof(k.length) == "undefined");
		},
		_copyObj: function(r) {
			if (this._isObj(r)) {
				var t = {};
				for (var a in r) {
					if (typeof(r[a]) == "object" && r[a] != null) t[a] = this._copyObj(r[a]); else t[a] = r[a];
				}
			} else {
				var t = [];
				for (var a=0; a<r.length; a++) {
					if (typeof(r[a]) == "object" && r[a] != null) t[a] = this._copyObj(r[a]); else t[a] = r[a];
				}
			}
			return t;
		},
		
		// screen dim
		screenDim: function() {
			var isIE = (navigator.userAgent.indexOf("MSIE") >= 0);
			var dim = {};
			dim.left = document.body.scrollLeft;
			dim.right = dim.left+(window.innerWidth||document.body.clientWidth);
			dim.top = Math.max((isIE?document.documentElement:document.getElementsByTagName("html")[0]).scrollTop, document.body.scrollTop);
			dim.bottom = dim.top+(isIE?Math.max(document.documentElement.clientHeight||0,document.documentElement.offsetHeight||0):window.innerHeight);
			return dim;
		},
		
		// input/textarea range selection
		selectTextRange: function(inp, start, end) {
			
			inp = (typeof(inp)=="string"?document.getElementById(inp):inp);
			
			var len = inp.value.length;
			start = Math.max(Math.min(start, len), 0);
			end = Math.min(end, len);
			
			if (inp.setSelectionRange) {
				try {inp.setSelectionRange(start, end);} catch(e){}; // combo in grid under IE requires try/catch
			} else if (inp.createTextRange) {
				var range = inp.createTextRange();
				range.moveStart("character", start);
				range.moveEnd("character", end-len);
				try {range.select();} catch(e){};
			}
		},
		// transition
		transData: null,
		transDetect: function() {
			
			if (this.transData == null) {
				
				this.transData = {transProp: false, transEv: null};
				
				// transition, MozTransition, WebkitTransition, msTransition, OTransition
				var k = {
					"MozTransition": "transitionend",
					"WebkitTransition": "webkitTransitionEnd",
					"OTransition": "oTransitionEnd",
					"msTransition": "transitionend",
					"transition": "transitionend"
				};
				
				for (var a in k) {
					if (this.transData.transProp == false && document.documentElement.style[a] != null) {
						this.transData.transProp = a;
						this.transData.transEv = k[a];
					}
				}
				k = null;
			}
			
			return this.transData;
			
		},
		
		// xml parser
		_xmlNodeValue: function(node) {
			var value = "";
			for (var q=0; q<node.childNodes.length; q++) {
				value += (node.childNodes[q].nodeValue!=null?node.childNodes[q].nodeValue.toString().replace(/^[\n\r\s]{0,}/,"").replace(/[\n\r\s]{0,}$/,""):"");
			}
			return value;
		}
		
	};
	
	// browser
	window.dhx4.isIE = (navigator.userAgent.indexOf("MSIE") >= 0 || navigator.userAgent.indexOf("Trident") >= 0);
	window.dhx4.isIE6 = (window.XMLHttpRequest == null && navigator.userAgent.indexOf("MSIE") >= 0);
	window.dhx4.isIE7 = (navigator.userAgent.indexOf("MSIE 7.0") >= 0 && navigator.userAgent.indexOf("Trident") < 0);
	window.dhx4.isIE8 = (navigator.userAgent.indexOf("MSIE 8.0") >= 0 && navigator.userAgent.indexOf("Trident") >= 0);
	window.dhx4.isIE9 = (navigator.userAgent.indexOf("MSIE 9.0") >= 0 && navigator.userAgent.indexOf("Trident") >= 0);
	window.dhx4.isIE10 = (navigator.userAgent.indexOf("MSIE 10.0") >= 0 && navigator.userAgent.indexOf("Trident") >= 0 && window.navigator.pointerEnabled != true);
	window.dhx4.isIE11 = (navigator.userAgent.indexOf("Trident") >= 0 && window.navigator.pointerEnabled == true);
	window.dhx4.isEdge = (navigator.userAgent.indexOf("Edge") >= 0);
	window.dhx4.isOpera = (navigator.userAgent.indexOf("Opera") >= 0);
	window.dhx4.isChrome = (navigator.userAgent.indexOf("Chrome") >= 0) && !window.dhx4.isEdge;
	window.dhx4.isKHTML = (navigator.userAgent.indexOf("Safari") >= 0 || navigator.userAgent.indexOf("Konqueror") >= 0) && !window.dhx4.isEdge;
	window.dhx4.isFF = (navigator.userAgent.indexOf("Firefox") >= 0);
	window.dhx4.isIPad = (navigator.userAgent.search(/iPad/gi) >= 0);
	
	// dnd data
	window.dhx4.dnd = {
		evs: {},
		p_en: ((window.dhx4.isIE || window.dhx4.isEdge) && (window.navigator.pointerEnabled || window.navigator.msPointerEnabled)), // touch/pointer
		_mTouch: function(e) {
			// mouse touch type in ie10/11/Edge
			return (window.dhx4.isIE10 && e.pointerType == e.MSPOINTER_TYPE_MOUSE || window.dhx4.isIE11 && e.pointerType == "mouse" || window.dhx4.isEdge && e.pointerType == "mouse");
		},
		_touchOn: function(obj) {
			if (obj == null) obj = document.body;
			obj.style.touchAction = obj.style.msTouchAction = "";
			obj = null;
		},
		_touchOff: function(obj) {
			if (obj == null) obj = document.body;
			obj.style.touchAction = obj.style.msTouchAction = "none";
			obj = null;
		}
	};
	
	// dnd events
	if (window.navigator.pointerEnabled == true) { // edge/ie11
		window.dhx4.dnd.evs = {start: "pointerdown", move: "pointermove", end: "pointerup"};
	} else if (window.navigator.msPointerEnabled == true) { // ie10-
		window.dhx4.dnd.evs = {start: "MSPointerDown", move: "MSPointerMove", end: "MSPointerUp"};
	} else if (typeof(window.addEventListener) != "undefined") { // rest touch devices
		window.dhx4.dnd.evs = {start: "touchstart", move: "touchmove", end: "touchend"};
	};
	
};

if (typeof(window.dhx4.template) == "undefined") {
	
	// trim
	window.dhx4.trim = function(t) {
		return String(t).replace(/^\s{1,}/,"").replace(/\s{1,}$/,"");
	};
	
	// template parsing
	window.dhx4.template = function(tpl, data, trim) {
		
		// tpl - template text, #value|func:param0:param1:paramX#
		// data - object with key-value
		// trim - true/false, trim values
		return tpl.replace(/#([a-z0-9_-]{1,})(\|([^#]*))?#/gi, function(){
			
			var key = arguments[1];
			
			var t = window.dhx4.trim(arguments[3]);
			var func = null;
			var args = [data[key]];
			
			if (t.length > 0) {
				
				t = t.split(":");
				var k = [];
				
				// check escaped colon
				for (var q=0; q<t.length; q++) {
					if (q > 0 && k[k.length-1].match(/\\$/) != null) {
						k[k.length-1] = k[k.length-1].replace(/\\$/,"")+":"+t[q];
					} else {
						k.push(t[q]);
					}
				}
				
				func = k[0];
				for (var q=1; q<k.length; q++) args.push(k[q]);
				
			}
			
			// via inner function
			if (typeof(func) == "string" && typeof(window.dhx4.template[func]) == "function") {
				return window.dhx4.template[func].apply(window.dhx4.template, args);
			}
			
			// value only
			if (key.length > 0 && typeof(data[key]) != "undefined") {
				if (trim == true) return window.dhx4.trim(data[key]);
				return String(data[key]);
			}
			
			// key not found
			return "";
			
		});
		
	};
	
	window.dhx4.template.date = function(value, format) {
		// Date obj + format	=> convert to string
		// timestamp + format	=> convert to string
		// string		=> no convert
		// any other value	=> empty string
		if (value != null) {
			if (value instanceof Date) {
				return window.dhx4.date2str(value, format);
			} else {
				value = value.toString();
				if (value.match(/^\d*$/) != null) return window.dhx4.date2str(new Date(parseInt(value)), format);
				return value;
			}
		}
		return "";
	};
	
	window.dhx4.template.maxlength = function(value, limit) {
		return String(value).substr(0, limit);
	};
	
	window.dhx4.template.number_format = function(value, format, group_sep, dec_sep) {
		var fmt = window.dhx4.template._parseFmt(format, group_sep, dec_sep);
		if (fmt == false) return value;
		return window.dhx4.template._getFmtValue(value, fmt);
	};
	
	window.dhx4.template.lowercase = function(value) {
		if (typeof(value) == "undefined" || value == null) value = "";
		return String(value).toLowerCase();
	};
	window.dhx4.template.uppercase = function(value) {
		if (typeof(value) == "undefined" || value == null) value = "";
		return String(value).toUpperCase();
	};
	
	// number format helpers
	window.dhx4.template._parseFmt = function(format, group_sep, dec_sep) {
		
		var t = format.match(/^([^\.\,0-9]*)([0\.\,]*)([^\.\,0-9]*)/);
		if (t == null || t.length != 4) return false; // invalid format
		
		var fmt = {
			// int group
			i_len: false,
			i_sep: (typeof(group_sep)=="string"?group_sep:","),
			// decimal
			d_len: false,
			d_sep: (typeof(dec_sep)=="string"?dec_sep:"."),
			// chars before and after
			s_bef: (typeof(t[1])=="string"?t[1]:""),
			s_aft: (typeof(t[3])=="string"?t[3]:"")
		};
		
		var f = t[2].split(".");
		if (f[1] != null) fmt.d_len = f[1].length;
		
		var r = f[0].split(",");
		if (r.length > 1) fmt.i_len = r[r.length-1].length;
		
		return fmt;
		
	};
	
	window.dhx4.template._getFmtValue = function(value, fmt) {
		
		var r = String(value).match(/^(-)?([0-9]{1,})(\.([0-9]{1,}))?$/); // r = [complete value, minus sign, integer, full decimal, decimal]
		
		if (r != null && r.length == 5) {
			var v0 = "";
			// minus sign
			if (r[1] != null) v0 += r[1];
			// chars before
			v0 += fmt.s_bef;
			// int part
			if (fmt.i_len !== false) {
				var i = 0; var v1 = "";
				for (var q=r[2].length-1; q>=0; q--) {
					v1 = ""+r[2].charAt(q)+v1;
					if (++i == fmt.i_len && q > 0) { v1=fmt.i_sep+v1; i=0; }
				}
				v0 += v1;
			} else {
				v0 += r[2];
			}
			// dec part
			if (fmt.d_len !== false) {
				if (r[4] == null) r[4] = "";
				while (r[4].length < fmt.d_len) r[4] += "0";
				eval("dhx4.temp = new RegExp(/\\d{"+fmt.d_len+"}/);");
				var t1 = (r[4]).match(dhx4.temp);
				if (t1 != null) v0 += fmt.d_sep+t1;
				dhx4.temp = t1 = null;
			}
			// chars after
			v0 += fmt.s_aft;
			
			return v0;
		}
		
		return value;
	};
	
};

if (typeof(window.dhx4.dateLang) == "undefined") {
	
	window.dhx4.dateLang = "en";
	window.dhx4.dateStrings = {
		en: {
			monthFullName:	["January","February","March","April","May","June","July","August","September","October","November","December"],
			monthShortName:	["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
			dayFullName:	["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
			dayShortName:	["Su","Mo","Tu","We","Th","Fr","Sa"]
		}
	};
	window.dhx4.dateFormat = {
		en: "%Y-%m-%d"
	};
	
	window.dhx4.date2str = function(val, format, strings) {
		
		if (format == null || typeof(format) == "undefined") format = window.dhx4.dateFormat[window.dhx4.dateLang];
		if (strings == null || typeof(strings) == "undefined") strings = window.dhx4.dateStrings[window.dhx4.dateLang];
		
		if (val instanceof Date) {
			var z = function(t) {
				return (String(t).length==1?"0"+String(t):t);
			}
			var k = function(t) {
				switch(t) {
					case "%d": return z(val.getDate());
					case "%j": return val.getDate();
					case "%D": return strings.dayShortName[val.getDay()];
					case "%l": return strings.dayFullName[val.getDay()];
					case "%m": return z(val.getMonth()+1);
					case "%n": return val.getMonth()+1;
					case "%M": return strings.monthShortName[val.getMonth()];
					case "%F": return strings.monthFullName[val.getMonth()];
					case "%y": return z(val.getYear()%100);
					case "%Y": return val.getFullYear();
					case "%g": return (val.getHours()+11)%12+1;
					case "%h": return z((val.getHours()+11)%12+1);
					case "%G": return val.getHours();
					case "%H": return z(val.getHours());
					case "%i": return z(val.getMinutes());
					case "%s": return z(val.getSeconds());
					case "%a": return (val.getHours()>11?"pm":"am");
					case "%A": return (val.getHours()>11?"PM":"AM");
					case "%%": return "%";
					case "%u": return val.getMilliseconds();
					case "%P":
						if (window.dhx4.temp_calendar != null && window.dhx4.temp_calendar.tz != null) return window.dhx4.temp_calendar.tz;
						var ofs = val.getTimezoneOffset();
						var h = Math.abs(Math.floor(ofs/60));
						var m = Math.abs(ofs)-h*60;
						return (ofs>0?"-":"+")+z(h)+":"+z(m);
					default: return t;
				}
			}
			var t = String(format||window.dhx4.dateFormat).replace(/%[a-zA-Z]/g, k);
		}
		
		return (t||String(val));
		
	};
	
	window.dhx4.str2date = function(val, format, strings) {
		
		if (format == null || typeof(format) == "undefined") format = window.dhx4.dateFormat[window.dhx4.dateLang];
		if (strings == null || typeof(strings) == "undefined") strings = window.dhx4.dateStrings[window.dhx4.dateLang];
		
		// escape custom chars
		format = format.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\\:|]/g, "\\$&");
		
		var v = [];
		var f = [];
		
		// escape required chars
		format = format.replace(/%[a-z]/gi, function(t){
			switch (t) {
				case "%d": case "%m": case "%y": case "%h": case "%H": case "%i": case "%s": f.push(t); return "(\\d{2})"; // 2 digits
				case "%D": case "%l": case "%M": case "%F": f.push(t); return "([a-zéûä\u0430-\u044F\u0451]{1,})"; // chars
				case "%j": case "%n": case "%g": case "%G": f.push(t); return "(\\d{1,2})"; // 1-2 digits
				case "%Y": f.push(t); return "(\\d{4})"; // 4 digits
				case "%a": f.push(t); return "([a|p]m)"; // am/pm
				case "%A": f.push(t); return "([A|P]M)"; // AM/PM
				case "%u": f.push(t); return "(\\d{1,6})"; // 1-6 digits, micro/milliseconds
				case "%P": f.push(t); return "([+-]\\d{1,2}:\\d{1,2})"; // zone offset
			}
			return t;
		});
		
		var re = new RegExp(format, "i");
		var e = val.match(re);
		
		if (e == null || e.length-1 != f.length) return "Invalid Date";
		
		// sorting
		/*
		Year	y,Y	1
		Month	n,m,M,F	2
		Day	d,j	3
		AM/PM	a,A	4
		Hours	H,G,h,g	5
		Minutes	i	6
		Seconds	s	7
		MSec	u	7
		Zone 	P	7
		*/
		
		for (var q=1; q<e.length; q++) v.push(e[q]);
		
		var p = {"%y":1,"%Y":1,"%n":2,"%m":2,"%M":2,"%F":2,"%d":3,"%j":3,"%a":4,"%A":4,"%H":5,"%G":5,"%h":5,"%g":5,"%i":6,"%s":7,"%u":7,"%P":7};
		var v2 = {};
		var f2 = {};
		for (var q=0; q<f.length; q++) {
			if (typeof(p[f[q]]) != "undefined") {
				var ind = p[f[q]];
				if (!v2[ind]){v2[ind]=[];f2[ind]=[];}
				v2[ind].push(v[q]);
				f2[ind].push(f[q]);
			}
		}
		v = [];
		f = [];
		for (var q=1; q<=7; q++) {
			if (v2[q] != null) {
				for (var w=0; w<v2[q].length; w++) {
					v.push(v2[q][w]);
					f.push(f2[q][w]);
				}
			}
		}
		
		// parsing date
		var r = new Date();
		r.setDate(1); // fix for 31th
		r.setHours(0);
		r.setMinutes(0);
		r.setSeconds(0);
		r.setMilliseconds(0);
		
		// get index by value
		var getInd = function(val, ar) {
			for (var q=0; q<ar.length; q++) if (ar[q].toLowerCase() == val) return q;
			return -1;
		}
		
		for (var q=0; q<v.length; q++) {
			switch (f[q]) {
				case "%d": case "%j": case "%n": case "%m": case "%Y": case "%H": case "%G": case "%i": case "%s": case "%u":
					if (!isNaN(v[q])) r[{"%d":"setDate","%j":"setDate","%n":"setMonth","%m":"setMonth","%Y":"setFullYear","%H":"setHours","%G":"setHours","%i":"setMinutes","%s":"setSeconds","%u":"setMilliseconds"}[f[q]]](Number(v[q])+(f[q]=="%m"||f[q]=="%n"?-1:0));
					break;
				//
				case "%M": case "%F":
					var k = getInd(v[q].toLowerCase(),strings[{"%M":"monthShortName","%F":"monthFullName"}[f[q]]]);
					if (k >= 0) r.setMonth(k);
					break;
				//
				case "%y":
					if (!isNaN(v[q])) {
						var v0 = Number(v[q]);
						r.setFullYear(v0+(v0>50?1900:2000));
					}
					break;
				//
				case "%g": case "%h":
					if (!isNaN(v[q])) {
						var v0 = Number(v[q]);
						if (v0 <= 12 && v0 >= 0) r.setHours(v0+(getInd("pm",v)>=0?(v0==12?0:12):(v0==12?-12:0))); // 12:00 AM -> midnight, 12:00 PM -> noon
					}
					break;
				//
				case "%P":
					if (window.dhx4.temp_calendar != null) {
						window.dhx4.temp_calendar.tz = v[q];
					}
					break;
			}
		}
		return r;
	};
	
};

if (typeof(window.dhx4.ajax) == "undefined") {
	
	window.dhx4.ajax = {
		
		// if false - dhxr param will added to prevent caching on client side (default),
		// if true - do not add extra params
		cache: false,
		
		// default method for load/loadStruct, post/get allowed
		// get - since 4.1.1, this should fix 412 error for macos safari
		method: "get",
		
		parse: function(data) {
			if (typeof data !== "string") return data;
			
			data = data.replace(/^[\s]+/,"");
			if (window.DOMParser && !dhx4.isIE) { // ff,ie9
				var obj = (new window.DOMParser()).parseFromString(data, "text/xml");
			} else if (window.ActiveXObject !== window.undefined) {
				var obj = new window.ActiveXObject("Microsoft.XMLDOM");
				obj.async = "false";
				obj.loadXML(data);
			}
			return obj;
		},
		xmltop: function(tagname, xhr, obj) {
			if (typeof xhr.status == "undefined" || xhr.status < 400) {
				xml = (!xhr.responseXML) ? dhx4.ajax.parse(xhr.responseText || xhr) : (xhr.responseXML || xhr);
				if (xml && xml.documentElement !== null) {
					try {
						if (!xml.getElementsByTagName("parsererror").length)
							return xml.getElementsByTagName(tagname)[0];
					} catch(e){}
				}
			}
			if (obj !== -1) dhx4.callEvent("onLoadXMLError",["Incorrect XML", arguments[1], obj]);
			return document.createElement("DIV");
		},
		xpath: function(xpathExp, docObj) {
			if (!docObj.nodeName) docObj = docObj.responseXML || docObj;
			if (dhx4.isIE) {
				try {
					return docObj.selectNodes(xpathExp)||[];
				} catch(e){ return []; }
			} else {
				var rows = [];
				var first;
				var col = (docObj.ownerDocument||docObj).evaluate(xpathExp, docObj, null, XPathResult.ANY_TYPE, null);
				while (first = col.iterateNext()) rows.push(first);
				return rows;
			}
		},
		query: function(config) {
			dhx4.ajax._call(
				(config.method || "GET"),
				config.url,
				config.data || "",
				(config.async || true),
				config.callback,
				null,
				config.headers
			);
		},
		get: function(url, onLoad) {
			return this._call("GET", url, null, true, onLoad);
		},
		getSync: function(url) {
			return this._call("GET", url, null, false);
		},
		put: function(url, postData, onLoad) {
			return this._call("PUT", url, postData, true, onLoad);
		},
		del: function(url, postData, onLoad) {
			return this._call("DELETE", url, postData, true, onLoad);
		},
		post: function(url, postData, onLoad) {
			if (arguments.length == 1) {
				postData = "";
			} else if (arguments.length == 2 && (typeof(postData) == "function" || typeof(window[postData]) == "function")) {
				onLoad = postData;
				postData = "";
			} else {
				postData = String(postData);
			}
			return this._call("POST", url, postData, true, onLoad);
		},
		postSync: function(url, postData) {
			postData = (postData == null ? "" : String(postData));
			return this._call("POST", url, postData, false);
		},
		getLong: function(url, onLoad) {
			this._call("GET", url, null, true, onLoad, {url:url});
		},
		postLong: function(url, postData, onLoad) {
			if (arguments.length == 2 && (typeof(postData) == "function" || typeof(window[postData]))) {
				onLoad = postData;
				postData = "";
			}
			this._call("POST", url, postData, true, onLoad, {url:url, postData:postData});
		},
		_call: function(method, url, postData, async, onLoad, longParams, headers) {
			
			var t = (window.XMLHttpRequest && !dhx4.isIE ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP"));
			var isQt = (navigator.userAgent.match(/AppleWebKit/) != null && navigator.userAgent.match(/Qt/) != null && navigator.userAgent.match(/Safari/) != null);
			
			if (async == true) {
				t.onreadystatechange = function() {
					if ((t.readyState == 4) || (isQt == true && t.readyState == 3)) { // what for long response and status 404?
						if (t.status != 200 || t.responseText == "")
							if (!dhx4.callEvent("onAjaxError", [{xmlDoc:t, filePath:url, async:async}])) return;

						window.setTimeout(function(){
							if (typeof(onLoad) == "function") {
								onLoad.apply(window, [{xmlDoc:t, filePath:url, async:async}]); // dhtmlx-compat, response.xmlDoc.responseXML/responseText
							}
							if (longParams != null) {
								if (typeof(longParams.postData) != "undefined") {
									dhx4.ajax.postLong(longParams.url, longParams.postData, onLoad);
								} else {
									dhx4.ajax.getLong(longParams.url, onLoad);
								}
							}
							onLoad = null;
							t = null;
						},1);
					}
				}
			}
			
			if (method == "GET") {
				url += this._dhxr(url);
			}
			
			t.open(method, url, async);
			
			if (headers != null) {
				for (var key in headers) t.setRequestHeader(key, headers[key]);
			} else if (method == "POST" || method == "PUT" || method == "DELETE") {
				t.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			} else if (method == "GET") {
				postData = null;
			}
			
			t.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			
			t.send(postData);
			
			if (async != true) {
				if ((t.readyState == 4) || (isQt == true && t.readyState == 3)) {
					if (t.status != 200 || t.responseText == "") dhx4.callEvent("onAjaxError", [{xmlDoc:t, filePath:url, async:async}]);
				}
			}
			
			return {xmlDoc:t, filePath:url, async:async}; // dhtmlx-compat, response.xmlDoc.responseXML/responseText
			
		},
		
		_dhxr: function(sign, value) {
			if (this.cache != true) {
				if (sign.match(/^[\?\&]$/) == null) sign = (sign.indexOf("?")>=0?"&":"?");
				if (typeof(value) == "undefined") value = true;
				return sign+"dhxr"+new Date().getTime()+(value==true?"=1":"");
			}
			return "";
		}
	};
	
};

if (typeof(window.dhx4._enableDataLoading) == "undefined") {
	
	window.dhx4._enableDataLoading = function(obj, initObj, xmlToJson, xmlRootTag, mode) {
		
		if (mode == "clear") {
			
			// clear attached functionality
			
			for (var a in obj._dhxdataload) {
				obj._dhxdataload[a] = null;
				delete obj._dhxdataload[a];
			};
			
			obj._loadData = null;
			obj._dhxdataload = null;
			obj.load = null;
			obj.loadStruct = null;
			
			obj = null;
			
			return;
			
		}
		
		obj._dhxdataload = { // move to obj.conf?
			initObj: initObj,
			xmlToJson: xmlToJson,
			xmlRootTag: xmlRootTag,
			onBeforeXLS: null
		};
		
		obj._loadData = function(data, loadParams, onLoad) {
			
			if (arguments.length == 2) {
				onLoad = loadParams;
				loadParams = null;
			}
			
			var obj = null;
			
			// deprecated from 4.0, compatability with version (url, type[json|xml], onLoad)
			if (arguments.length == 3) onLoad = arguments[2];
			
			if (typeof(data) == "string") {
				
				var k = data.replace(/^\s{1,}/,"").replace(/\s{1,}$/,"");
				
				var tag = new RegExp("^<"+this._dhxdataload.xmlRootTag);
				
				// xml
				if (tag.test(k.replace(/^<\?xml[^\?]*\?>\s*/, ""))) { // remove leading <?xml ...?> if any, \n can be also present
					obj = dhx4.ajax.parse(data);
					if (obj != null) obj = this[this._dhxdataload.xmlToJson].apply(this, [obj]); // xml to json
				}
				
				if (obj == null && (k.match(/^[\s\S]*{[.\s\S]*}[\s\S]*$/) != null || k.match(/^[\s\S]*\[[.\s\S]*\][\s\S]*$/) != null)) { // check for '{...}' or '[...]', cut leading/trailing \n\r with \s\S
					obj = dhx4.s2j(k);
				}
				
				if (obj == null) {
					
					this.callEvent("onXLS",[]);
					
					var params = [];
					
					// allow to modify url and add params
					if (typeof(this._dhxdataload.onBeforeXLS) == "function") {
						var k = this._dhxdataload.onBeforeXLS.apply(this,[data]);
						if (k != null && typeof(k) == "object") {
							if (k.url != null) data = k.url;
							if (k.params != null) { for (var a in k.params) params.push(a+"="+encodeURIComponent(k.params[a])); }
						}
					}
					
					var t = this;
					var callBack = function(r) {
						
						var obj = null;
						
						if ((r.xmlDoc.getResponseHeader("Content-Type")||"").search(/xml/gi) >= 0 || (r.xmlDoc.responseText.replace(/^\s{1,}/,"")).match(/^</) != null) {
							obj = t[t._dhxdataload.xmlToJson].apply(t,[r.xmlDoc.responseXML]);
						} else {
							obj = dhx4.s2j(r.xmlDoc.responseText);
						}
						
						// init
						if (obj != null) t[t._dhxdataload.initObj].apply(t,[obj,data]); // data => url
						
						t.callEvent("onXLE",[]);
						
						if (onLoad != null) {
							if (typeof(onLoad) == "function") {
								onLoad.apply(t,[]);
							} else if (typeof(window[onLoad]) == "function") {
								window[onLoad].apply(t,[]);
							}
						}
						
						callBack = onLoad = null;
						obj = r = t = null;
						
					};
					
					params = params.join("&")+(typeof(loadParams)=="string"?"&"+loadParams:"");
					
					if (dhx4.ajax.method == "post") {
						dhx4.ajax.post(data, params, callBack);
					} else if (dhx4.ajax.method == "get") {
						dhx4.ajax.get(data+(params.length>0?(data.indexOf("?")>0?"&":"?")+params:""), callBack);
					}
					
					return;
				}
				
			} else {
				if (typeof(data.documentElement) == "object" || (typeof(data.tagName) != "undefined" && typeof(data.getElementsByTagName) != "undefined" && data.getElementsByTagName(this._dhxdataload.xmlRootTag).length > 0)) { // xml
					obj = this[this._dhxdataload.xmlToJson].apply(this, [data]);
				} else { // json
					obj = window.dhx4._copyObj(data);
				}
				
			}
			
			// init
			if (obj != null) this[this._dhxdataload.initObj].apply(this,[obj]);
			
			if (onLoad != null) {
				if (typeof(onLoad) == "function") {
					onLoad.apply(this, []);
				} else if (typeof(window[onLoad]) == "function") {
					window[onLoad].apply(this, []);
				}
				onLoad = null;
			}
			
		};
		
		// loadStruct for hdr/conf
		// load for data
		if (mode != null) {
			var k = {struct: "loadStruct", data: "load"};
			for (var a in mode) {
				if (mode[a] == true) obj[k[a]] = function() {return this._loadData.apply(this, arguments);}
			}
		}
		
		obj = null;
		
	};
};

if (typeof(window.dhx4._eventable) == "undefined") {
	
	window.dhx4._eventable = function(obj, mode) {
		
		if (mode == "clear") {
			
			obj.detachAllEvents();
			
			obj.dhxevs = null;
			
			obj.attachEvent = null;
			obj.detachEvent = null;
			obj.checkEvent = null;
			obj.callEvent = null;
			obj.detachAllEvents = null;
			
			obj = null;
			
			return;
			
		}
		
		obj.dhxevs = { data: {} };
		
		obj.attachEvent = function(name, func) {
			name = String(name).toLowerCase();
			if (!this.dhxevs.data[name]) this.dhxevs.data[name] = {};
			var eventId = window.dhx4.newId();
			this.dhxevs.data[name][eventId] = func;
			return eventId;
		}
		
		obj.detachEvent = function(eventId) {
			for (var a in this.dhxevs.data) {
				var k = 0;
				for (var b in this.dhxevs.data[a]) {
					if (b == eventId) {
						this.dhxevs.data[a][b] = null;
						delete this.dhxevs.data[a][b];
					} else {
						k++;
					}
				}
				if (k == 0) {
					this.dhxevs.data[a] = null;
					delete this.dhxevs.data[a];
				}
			}
		}
		
		obj.checkEvent = function(name) {
			name = String(name).toLowerCase();
			return (this.dhxevs.data[name] != null);
		}
		
		obj.callEvent = function(name, params) {
			name = String(name).toLowerCase();
			if (this.dhxevs.data[name] == null) return true;
			var r = true;
			for (var a in this.dhxevs.data[name]) {
				r = this.dhxevs.data[name][a].apply(this, params) && r;
			}
			return r;
		}
		
		obj.detachAllEvents = function() {
			for (var a in this.dhxevs.data) {
				for (var b in this.dhxevs.data[a]) {
					this.dhxevs.data[a][b] = null;
					delete this.dhxevs.data[a][b];
				}
				this.dhxevs.data[a] = null;
				delete this.dhxevs.data[a];
			}
		}
		
		obj = null;
	};
	
	dhx4._eventable(dhx4);
	
};

// validation
// all purpose set of rules, based on http://code.google.com/p/validation-js
if (!window.dhtmlxValidation) {
	dhtmlxValidation = function(){};
	dhtmlxValidation.prototype = {
		isEmpty: function(value) {
			return value == '';
		},
		isNotEmpty: function(value) {
			return (value instanceof Array?value.length>0:!value == ''); // array in case of multiselect
		},
		isValidBoolean: function(value) {
			return !!value.toString().match(/^(0|1|true|false)$/);
		},
		isValidEmail: function(value) {
			return !!value.toString().match(/(^[a-z0-9]([0-9a-z\-_\.]*)@([0-9a-z_\-\.]*)([.][a-z]{3})$)|(^[a-z]([0-9a-z_\.\-]*)@([0-9a-z_\-\.]*)(\.[a-z]{2,5})$)/i);
		},
		isValidInteger: function(value) {
			return !!value.toString().match(/(^-?\d+$)/);
		},
		isValidNumeric: function(value) {
			return !!value.toString().match(/(^-?\d\d*[\.|,]\d*$)|(^-?\d\d*$)|(^-?[\.|,]\d\d*$)/);
		},
		isValidAplhaNumeric: function(value) {
			return !!value.toString().match(/^[_\-a-z0-9]+$/gi);
		},
		// 0000-00-00 00:00:00 to 9999:12:31 59:59:59 (no it is not a "valid DATE" function)
		isValidDatetime: function(value) {
			var dt = value.toString().match(/^(\d{4})-(\d{2})-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$/);
			return dt && !!(dt[1]<=9999 && dt[2]<=12 && dt[3]<=31 && dt[4]<=59 && dt[5]<=59 && dt[6]<=59) || false;
		},
		// 0000-00-00 to 9999-12-31
		isValidDate: function(value) {
			var d = value.toString().match(/^(\d{4})-(\d{2})-(\d{2})$/);
			return d && !!(d[1]<=9999 && d[2]<=12 && d[3]<=31) || false;
		},
		// 00:00:00 to 59:59:59
		isValidTime: function(value) {
			var t = value.toString().match(/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/);
			return t && !!(t[1]<=24 && t[2]<=59 && t[3]<=59) || false;
		},
		// 0.0.0.0 to 255.255.255.255
		isValidIPv4: function(value) { 
			var ip = value.toString().match(/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/);
			return ip && !!(ip[1]<=255 && ip[2]<=255 && ip[3]<=255 && ip[4]<=255) || false;
		},
		isValidCurrency: function(value) { // Q: Should I consider those signs valid too ? : ¢|€|₤|₦|¥
			return value.toString().match(/^\$?\s?\d+?([\.,\,]?\d+)?\s?\$?$/) && true || false;
		},
		// Social Security Number (999-99-9999 or 999999999)
		isValidSSN: function(value) {
			return value.toString().match(/^\d{3}\-?\d{2}\-?\d{4}$/) && true || false;
		},
		// Social Insurance Number (999999999)
		isValidSIN: function(value) {
			return value.toString().match(/^\d{9}$/) && true || false;
		}
	};
	dhtmlxValidation = new dhtmlxValidation();
};


if (typeof(window.dhtmlx) == "undefined") {
	window.dhtmlx={
		extend:function(a, b){
			for (var key in b)
				if (!a[key])
					a[key]=b[key];
			return a;
		},
		extend_api:function(name,map,ext){
			var t = window[name];
			if (!t) return; //component not defined
			window[name]=function(obj){
				if (obj && typeof obj == "object" && !obj.tagName){
					var that = t.apply(this,(map._init?map._init(obj):arguments));
					//global settings
					for (var a in dhtmlx)
						if (map[a]) this[map[a]](dhtmlx[a]);			
					//local settings
					for (var a in obj){
						if (map[a]) this[map[a]](obj[a]);
						else if (a.indexOf("on")===0){
							this.attachEvent(a,obj[a]);
						}
					}
				} else
					var that = t.apply(this,arguments);
				if (map._patch) map._patch(this);
				return that||this;
			};
			window[name].prototype=t.prototype;
			if (ext)
				dhtmlx.extend(window[name].prototype,ext);
		},
		url:function(str){
			if (str.indexOf("?") != -1)
				return "&";
			else
				return "?";
		}
	};
};

/**  
*     @desc: find out what symbol to use as url param delimiters in further params
*     @type: private
*     @param: str - current url string
*     @topic: 0  
*/


function dhtmlDragAndDropObject(){
	if (window.dhtmlDragAndDrop)
		return window.dhtmlDragAndDrop;

	this.lastLanding=0;
	this.dragNode=0;
	this.dragStartNode=0;
	this.dragStartObject=0;
	this.tempDOMU=null;
	this.tempDOMM=null;
	this.waitDrag=0;
	window.dhtmlDragAndDrop=this;

	return this;
};

dhtmlDragAndDropObject.prototype.removeDraggableItem=function(htmlNode){
	htmlNode.onmousedown=null;
	htmlNode.dragStarter=null;
	htmlNode.dragLanding=null;
}
dhtmlDragAndDropObject.prototype.addDraggableItem=function(htmlNode, dhtmlObject){
	htmlNode.onmousedown=this.preCreateDragCopy;
	htmlNode.dragStarter=dhtmlObject;
	this.addDragLanding(htmlNode, dhtmlObject);
}
dhtmlDragAndDropObject.prototype.addDragLanding=function(htmlNode, dhtmlObject){
	htmlNode.dragLanding=dhtmlObject;
}
dhtmlDragAndDropObject.prototype.preCreateDragCopy=function(e){
	if ((e||window.event) && (e||event).button == 2)
		return;

	if (window.dhtmlDragAndDrop.waitDrag){
		window.dhtmlDragAndDrop.waitDrag=0;
		document.body.onmouseup=window.dhtmlDragAndDrop.tempDOMU;
		document.body.onmousemove=window.dhtmlDragAndDrop.tempDOMM;
		return false;
	}
	
	if (window.dhtmlDragAndDrop.dragNode)
		window.dhtmlDragAndDrop.stopDrag(e);	

	window.dhtmlDragAndDrop.waitDrag=1;
	window.dhtmlDragAndDrop.tempDOMU=document.body.onmouseup;
	window.dhtmlDragAndDrop.tempDOMM=document.body.onmousemove;
	window.dhtmlDragAndDrop.dragStartNode=this;
	window.dhtmlDragAndDrop.dragStartObject=this.dragStarter;
	document.body.onmouseup=window.dhtmlDragAndDrop.preCreateDragCopy;
	document.body.onmousemove=window.dhtmlDragAndDrop.callDrag;
	window.dhtmlDragAndDrop.downtime = new Date().valueOf();
	

	if ((e)&&(e.preventDefault)){
		e.preventDefault();
		return false;
	}
	return false;
};
dhtmlDragAndDropObject.prototype.callDrag=function(e){
	if (!e)
		e=window.event;
	dragger=window.dhtmlDragAndDrop;
	if ((new Date()).valueOf()-dragger.downtime<100) return;

	//if ((e.button == 0)&&(_isIE))
	//	return dragger.stopDrag();

	if (!dragger.dragNode){
		if (dragger.waitDrag){
			dragger.dragNode=dragger.dragStartObject._createDragNode(dragger.dragStartNode, e);
	
			if (!dragger.dragNode)
				return dragger.stopDrag();
	
			dragger.dragNode.onselectstart=function(){return false;}
			dragger.gldragNode=dragger.dragNode;
			document.body.appendChild(dragger.dragNode);
			document.body.onmouseup=dragger.stopDrag;
			dragger.waitDrag=0;
			dragger.dragNode.pWindow=window;
			dragger.initFrameRoute();
		} 
		else return dragger.stopDrag(e, true);
	}

	if (dragger.dragNode.parentNode != window.document.body && dragger.gldragNode){
		var grd = dragger.gldragNode;

		if (dragger.gldragNode.old)
			grd=dragger.gldragNode.old;

		//if (!document.all) dragger.calculateFramePosition();
		grd.parentNode.removeChild(grd);
		var oldBody = dragger.dragNode.pWindow;

		if (grd.pWindow &&	grd.pWindow.dhtmlDragAndDrop.lastLanding)
			grd.pWindow.dhtmlDragAndDrop.lastLanding.dragLanding._dragOut(grd.pWindow.dhtmlDragAndDrop.lastLanding);	
			
		//		var oldp=dragger.dragNode.parentObject;
		if (_isIE){
			var div = document.createElement("Div");
			div.innerHTML=dragger.dragNode.outerHTML;
			dragger.dragNode=div.childNodes[0];
		} else
			dragger.dragNode=dragger.dragNode.cloneNode(true);

		dragger.dragNode.pWindow=window;
		//		dragger.dragNode.parentObject=oldp;

		dragger.gldragNode.old=dragger.dragNode;
		document.body.appendChild(dragger.dragNode);
		oldBody.dhtmlDragAndDrop.dragNode=dragger.dragNode;
	}

	dragger.dragNode.style.left=e.clientX+15+(dragger.fx
		? dragger.fx*(-1)
		: 0)
		+(document.body.scrollLeft||document.documentElement.scrollLeft)+"px";
	dragger.dragNode.style.top=e.clientY+3+(dragger.fy
		? dragger.fy*(-1)
		: 0)
		+(document.body.scrollTop||document.documentElement.scrollTop)+"px";

	if (!e.srcElement)
		var z = e.target;
	else
		z=e.srcElement;
	dragger.checkLanding(z, e);
}

dhtmlDragAndDropObject.prototype.calculateFramePosition=function(n){
	//this.fx = 0, this.fy = 0;
	if (window.name){
		var el = parent.frames[window.name].frameElement.offsetParent;
		var fx = 0;
		var fy = 0;

		while (el){
			fx+=el.offsetLeft;
			fy+=el.offsetTop;
			el=el.offsetParent;
		}

		if ((parent.dhtmlDragAndDrop)){
			var ls = parent.dhtmlDragAndDrop.calculateFramePosition(1);
			fx+=ls.split('_')[0]*1;
			fy+=ls.split('_')[1]*1;
		}

		if (n)
			return fx+"_"+fy;
		else
			this.fx=fx;
		this.fy=fy;
	}
	return "0_0";
}
dhtmlDragAndDropObject.prototype.checkLanding=function(htmlObject, e){
	if ((htmlObject)&&(htmlObject.dragLanding)){
		if (this.lastLanding)
			this.lastLanding.dragLanding._dragOut(this.lastLanding);
		this.lastLanding=htmlObject;
		this.lastLanding=this.lastLanding.dragLanding._dragIn(this.lastLanding, this.dragStartNode, e.clientX,
			e.clientY, e);
		this.lastLanding_scr=(_isIE ? e.srcElement : e.target);
	} else {
		if ((htmlObject)&&(htmlObject.tagName != "BODY"))
			this.checkLanding(htmlObject.parentNode, e);
		else {
			if (this.lastLanding)
				this.lastLanding.dragLanding._dragOut(this.lastLanding, e.clientX, e.clientY, e);
			this.lastLanding=0;

			if (this._onNotFound)
				this._onNotFound();
		}
	}
}
dhtmlDragAndDropObject.prototype.stopDrag=function(e, mode){
	dragger=window.dhtmlDragAndDrop;

	if (!mode){
		dragger.stopFrameRoute();
		var temp = dragger.lastLanding;
		dragger.lastLanding=null;

		if (temp)
			temp.dragLanding._drag(dragger.dragStartNode, dragger.dragStartObject, temp, (_isIE
				? event.srcElement
				: e.target));
	}
	dragger.lastLanding=null;

	if ((dragger.dragNode)&&(dragger.dragNode.parentNode == document.body))
		dragger.dragNode.parentNode.removeChild(dragger.dragNode);
	dragger.dragNode=0;
	dragger.gldragNode=0;
	dragger.fx=0;
	dragger.fy=0;
	dragger.dragStartNode=0;
	dragger.dragStartObject=0;
	document.body.onmouseup=dragger.tempDOMU;
	document.body.onmousemove=dragger.tempDOMM;
	dragger.tempDOMU=null;
	dragger.tempDOMM=null;
	dragger.waitDrag=0;
}

dhtmlDragAndDropObject.prototype.stopFrameRoute=function(win){
	if (win)
		window.dhtmlDragAndDrop.stopDrag(1, 1);

	for (var i = 0; i < window.frames.length; i++){
		try{
		if ((window.frames[i] != win)&&(window.frames[i].dhtmlDragAndDrop))
			window.frames[i].dhtmlDragAndDrop.stopFrameRoute(window);
		} catch(e){}
	}

	try{
	if ((parent.dhtmlDragAndDrop)&&(parent != window)&&(parent != win))
		parent.dhtmlDragAndDrop.stopFrameRoute(window);
	} catch(e){}
}
dhtmlDragAndDropObject.prototype.initFrameRoute=function(win, mode){
	if (win){
		window.dhtmlDragAndDrop.preCreateDragCopy();
		window.dhtmlDragAndDrop.dragStartNode=win.dhtmlDragAndDrop.dragStartNode;
		window.dhtmlDragAndDrop.dragStartObject=win.dhtmlDragAndDrop.dragStartObject;
		window.dhtmlDragAndDrop.dragNode=win.dhtmlDragAndDrop.dragNode;
		window.dhtmlDragAndDrop.gldragNode=win.dhtmlDragAndDrop.dragNode;
		window.document.body.onmouseup=window.dhtmlDragAndDrop.stopDrag;
		window.waitDrag=0;

		if (((!_isIE)&&(mode))&&((!_isFF)||(_FFrv < 1.8)))
			window.dhtmlDragAndDrop.calculateFramePosition();
	}
	try{
	if ((parent.dhtmlDragAndDrop)&&(parent != window)&&(parent != win))
		parent.dhtmlDragAndDrop.initFrameRoute(window);
	}catch(e){}

	for (var i = 0; i < window.frames.length; i++){
		try{
		if ((window.frames[i] != win)&&(window.frames[i].dhtmlDragAndDrop))
			window.frames[i].dhtmlDragAndDrop.initFrameRoute(window, ((!win||mode) ? 1 : 0));
		} catch(e){}
	}
}

 _isFF = false;
 _isIE = false;
 _isOpera = false;
 _isKHTML = false;
 _isMacOS = false;
 _isChrome = false;
 _FFrv = false;
 _KHTMLrv = false;
 _OperaRv = false;

if (navigator.userAgent.indexOf('Macintosh') != -1)
	_isMacOS=true;


if (navigator.userAgent.toLowerCase().indexOf('chrome')>-1)
	_isChrome=true;

if ((navigator.userAgent.indexOf('Safari') != -1)||(navigator.userAgent.indexOf('Konqueror') != -1)){
	 _KHTMLrv = parseFloat(navigator.userAgent.substr(navigator.userAgent.indexOf('Safari')+7, 5));

	if (_KHTMLrv > 525){ //mimic FF behavior for Safari 3.1+
		_isFF=true;
		 _FFrv = 1.9;
	} else
		_isKHTML=true;
} else if (navigator.userAgent.indexOf('Opera') != -1){
	_isOpera=true;
	_OperaRv=parseFloat(navigator.userAgent.substr(navigator.userAgent.indexOf('Opera')+6, 3));
}


else if (navigator.appName.indexOf("Microsoft") != -1){
	_isIE=true;
	if ((navigator.appVersion.indexOf("MSIE 8.0")!= -1 || 
		 navigator.appVersion.indexOf("MSIE 9.0")!= -1 || 
		 navigator.appVersion.indexOf("MSIE 10.0")!= -1 ||
		 document.documentMode > 7) && 
			document.compatMode != "BackCompat"){
		_isIE=8;
	}
} else if (navigator.appName  == 'Netscape' && navigator.userAgent.indexOf("Trident") != -1){
	//ie11
	_isIE=8;
} else {
	_isFF=true;
	 _FFrv = parseFloat(navigator.userAgent.split("rv:")[1])
}

if (typeof(window.dhtmlxEvent) == "undefined") {
	
	function dhtmlxEvent(el, event, handler){
		if (el.addEventListener)
			el.addEventListener(event, handler, false);
	
		else if (el.attachEvent)
			el.attachEvent("on"+event, handler);
	}
};

if (dhtmlxEvent.touchDelay == null) {
	dhtmlxEvent.touchDelay = 2000;
};

if (typeof(dhtmlxEvent.initTouch) == "undefined") {
	
	dhtmlxEvent.initTouch = function(){
		var longtouch;
		var target;
		var tx, ty;
	
		dhtmlxEvent(document.body, "touchstart", function(ev){
			target = ev.touches[0].target;
			tx = ev.touches[0].clientX;
			ty = ev.touches[0].clientY;
			longtouch = window.setTimeout(touch_event, dhtmlxEvent.touchDelay);
		});
		function touch_event(){
			if (target){
				var ev = document.createEvent("HTMLEvents"); // for chrome and firefox
				ev.initEvent("dblclick", true, true);
				target.dispatchEvent(ev);
				longtouch = target = null;
			}
		};
		dhtmlxEvent(document.body, "touchmove", function(ev){
			if (longtouch){
				if (Math.abs(ev.touches[0].clientX - tx) > 50 || Math.abs(ev.touches[0].clientY - ty) > 50 ){
					window.clearTimeout(longtouch);
					longtouch = target = false;
				}
			}
		});
		dhtmlxEvent(document.body, "touchend", function(ev){
			if (longtouch){
				window.clearTimeout(longtouch);
				longtouch = target = false;
			}
		});
	
		dhtmlxEvent.initTouch = function(){};
	};
};
