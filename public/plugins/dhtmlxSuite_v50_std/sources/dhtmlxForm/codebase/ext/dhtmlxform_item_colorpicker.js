/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.colorpicker = {
	
	colorpicker: {}, // colorpicker instances
	
	render: function(item, data) {
		
		var t = this;
		
		item._type = "colorpicker";
		item._enabled = true;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "INPUT", "TEXT", true, true, "dhxform_textarea");
		
		item._value = (data.value||"");
		item.childNodes[item._ll?1:0].childNodes[0].value = item._value;
		
		var conf = {
			input: item.childNodes[item._ll?1:0].childNodes[0],
			custom_colors: (window.dhx4.s2b(data.enableCustomColors) == true),
			skin: item.getForm().skin
		};
		
		this.colorpicker[item._idd] = new dhtmlXColorPicker(conf);
		this.colorpicker[item._idd]._nodes[0].valueColor = null; // disable input's bg change
		this.colorpicker[item._idd].base.className += " dhtmlxcp_in_form";
		
		if (typeof(data.customColors) != "undefined") {
			this.colorpicker[item._idd].setCustomColors(data.customColors);
		}
		
		if (typeof(data.cpPosition) == "string") {
			this.colorpicker[item._idd].setPosition(data.cpPosition);
		}
		
		// select handler
		this.colorpicker[item._idd].attachEvent("onSelect", function(color){
			if (item._value != color) {
				// call some events
				if (item.checkEvent("onBeforeChange")) {
					if (item.callEvent("onBeforeChange",[item._idd, item._value, color]) !== true) {
						// do not allow set new value
						item.childNodes[item._ll?1:0].childNodes[0].value = item._value;
						return;
					}
				}
				// accepted
				item._value = color;
				t.setValue(item, color);
				item.callEvent("onChange", [item._idd, item._value]);
			}
		});
		this.colorpicker[item._idd].attachEvent("onHide", function(color){
			var i = item.childNodes[item._ll?1:0].childNodes[0];
			if (i.value != item._value) i.value = item._value;
			i = null;
		});
		
		
		item.childNodes[item._ll?1:0].childNodes[0]._idd = item._idd;
		
		return this;
		
	},
	
	getColorPicker: function(item) {
		return this.colorpicker[item._idd];
	},
	
	destruct: function(item) {
		
		// unload color picker instance
		if (this.colorpicker[item._idd].unload) this.colorpicker[item._idd].unload();
		this.colorpicker[item._idd] = null;
		try {delete this.colorpicker[item._idd];} catch(e){}
		
		// remove custom events/objects
		item.childNodes[item._ll?1:0].childNodes[0]._idd = null;
		
		// unload item
		this.d2(item);
		item = null;
	},
	
	setSkin: function(item, skin) {
		this.colorpicker[item._idd].setSkin(skin);
	}
	
};


(function(){
	for (var a in {doAddLabel:1,doAddInput:1,doUnloadNestedLists:1,setText:1,getText:1,enable:1,disable:1,isEnabled:1,setWidth:1,setReadonly:1,isReadonly:1,setValue:1,getValue:1,updateValue:1,setFocus:1,getInput:1})
		dhtmlXForm.prototype.items.colorpicker[a] = dhtmlXForm.prototype.items.input[a];
})();

dhtmlXForm.prototype.items.colorpicker.d2 = dhtmlXForm.prototype.items.input.destruct;


dhtmlXForm.prototype.getColorPicker = function(name) {
	return this.doWithItem(name, "getColorPicker");
};


