/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function dhtmlXTabBarInitFromHTML() {
	
	var z = document.getElementsByTagName("div");
	
	for (var i=0; i<z.length; i++) {
		
		if (z[i].className.indexOf("dhtmlxTabBar") != -1) {
			
			var conf = { settings: {}, tabs: [] };
			
			var n = z[i];
			var id = n.id;
			n.className = "";
			
			var k = new Array();
			for (var j=0; j<n.childNodes.length; j++) {
				if (n.childNodes[j].tagName && n.childNodes[j].tagName != "!") k[k.length] = n.childNodes[j];
			}
			
			var skin = n.getAttribute("skin");
			if (skin != null) conf.settings.skin = skin;
			
			var w = new dhtmlXTabBar({parent: id, mode: n.getAttribute("mode")});
			
			window[id] = w;
			acs = n.getAttribute("onbeforeinit");
			if (acs) eval(acs);
			
			align = n.getAttribute("align");
			if (align) conf.settings.align = align;
			
			var cont = {};
			
			for (var j=0; j<k.length; j++) {
				
				var m = k[j];
				
				var tab = {
					id: m.id,
					text: m.getAttribute("name"),
					width: m.getAttribute("width"),
					selected: m.getAttribute("selected"),
					active: m.getAttribute("active"),
					close: m.getAttribute("close")
				};
				
				var href = m.getAttribute("href");
				if (href) cont[m.id] = {href: href}; else cont[m.id] = {cont: m};
				
				conf.tabs.push(tab);
				
			}
			
			w.loadStruct(conf);
			for (var a in cont) {
				if (cont[a].href) {
					w.cells(a).attachURL(cont[a].href);
					cont[a].href = null;
				} else {
					w.cells(a).attachObject(cont[a].cont);
					if (cont[a].cont.style.display == "none") cont[a].cont.style.display = "";
					cont[a].cont = null;
				}
				cont[a] = null;
			}
			
			var selId = n.getAttribute("select");
			if (selId != null) {
				w.tabs(selId).setActive();
			} else if (w.getActiveTab() == null) {
				var v = w._getFirstVisible();
				if (v != null) w.cells(v).setActive();
			}
			
			acs = n.getAttribute("oninit");
			if (acs) eval(acs);
		}
	}
	
	if (typeof(window.addEventListener) == "function") {
		window.removeEventListener("load", dhtmlXTabBarInitFromHTML, false);
	} else {
		window.detachEvent("onload", dhtmlXTabBarInitFromHTML);
	};
};

if (typeof(window.addEventListener) == "function") {
	window.addEventListener("load", dhtmlXTabBarInitFromHTML, false);
} else {
	window.attachEvent("onload", dhtmlXTabBarInitFromHTML);
};


