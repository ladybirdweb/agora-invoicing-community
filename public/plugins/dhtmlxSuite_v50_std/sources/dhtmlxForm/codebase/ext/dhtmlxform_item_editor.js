/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.editor = {
	
	editor: {},
	
	render: function(item, data) {
		
		var ta = (!isNaN(data.rows));
		
		item._type = "editor";
		item._enabled = true;
		item._editor_id = item.getForm().idPrefix+item._idd;
		
		this.doAddLabel(item, data);
		this.doAddInput(item, data, "DIV", null, true, true, "dhxform_item_template");
		
		item._value = (data.value||"");
		
		var that = this;
		this.editor[item._editor_id] = new dhtmlXEditor({
			parent: item.childNodes[item._ll?1:0].childNodes[0],
			content: item._value,
			iconsPath: data.iconsPath, // path for toolbar icons
			toolbar: data.toolbar
		});
		
		this.editor[item._editor_id].attachEvent("onAccess",function(t, ev){
			// generate body click to hide menu/toolbar/calendar/combo/other stuff if any
			item.callEvent("_onBeforeEditorAccess", []); // if editor attached to form in popup - do some tricks
			_dhxForm_doClick(document.body, "click");
			// continue
			if (t == "blur") {
				that.doOnBlur(item, this);
				item.callEvent("onBlur", [item._idd]);
				if ({dhx_terrace:1, material: 1}[item.getForm().skin] == 1) {
					var css = item.childNodes[item._ll?1:0].className;
					if (css.indexOf("dhxeditor_focus") >= 0) item.childNodes[item._ll?1:0].className = (css).replace(/\s{0,}dhxeditor_focus/gi,"");
				}
			} else {
				item.callEvent("onEditorAccess", [item._idd, t, ev, this, item.getForm()]);
				item.callEvent("onFocus", [item._idd]);
				if ({dhx_terrace:1, material: 1}[item.getForm().skin] == 1) {
					var css = item.childNodes[item._ll?1:0].className;
					if (css.indexOf("dhxeditor_focus") == -1) item.childNodes[item._ll?1:0].className += " dhxeditor_focus";
				}
			}
		});
		
		this.editor[item._editor_id].attachEvent("onToolbarClick", function(a){
			item.callEvent("onEditorToolbarClick", [item._idd, a, this, item.getForm()]);
		});
		
		if (data.readonly) this.setReadonly(item, true);
		
		// emulate label-for
		item.childNodes[item._ll?0:1].childNodes[0].removeAttribute("for");
		item.childNodes[item._ll?0:1].childNodes[0].onclick = function() {
			that.editor[item._editor_id]._focus();
		}
		
		return this;
		
	},
	
	// destructor for editor needed
	doOnBlur: function(item, editor) {
		var t = editor.getContent();
		if (item._value != t) {
			if (item.checkEvent("onBeforeChange")) {
				if (item.callEvent("onBeforeChange",[item._idd, item._value, t]) !== true) {
					// restore
					editor.setContent(item._value);
					return;
				}
			}
			// accepted
			item._value = t;
			item.callEvent("onChange",[item._idd, t]);
		}
	},
	
	setValue: function(item, value) {
		if (item._value == value) return;
		item._value = value;
		this.editor[item._editor_id].setContent(item._value);
	},
	
	getValue: function(item) {
		item._value = this.editor[item._editor_id].getContent();
		return item._value;
	},
	
	enable: function(item) {
		if (this.isEnabled(item) != true) {
			this.editor[item._editor_id].setReadonly(false);
			this.doEn(item);
		}
	},
	
	disable: function(item) {
		if (this.isEnabled(item) == true) {
			this.editor[item._editor_id].setReadonly(true);
			this.doDis(item);
		}
	},
	
	setReadonly: function(item, mode) {
		this.editor[item._editor_id].setReadonly(mode);
	},
	
	getEditor: function(item) {
		return (this.editor[item._editor_id]||null);
	},
	
	destruct: function(item) {
		
		// custom editor functionality
		item.childNodes[item._ll?0:1].childNodes[0].onclick = null;
		
		// unload editor
		this.editor[item._editor_id].unload();
		this.editor[item._editor_id] = null;
		
		// unload item
		this.d2(item);
		item = null;
		
	},
	
	setFocus: function(item) {
		this.editor[item._editor_id]._focus();
	}
	
};

(function(){
	for (var a in {doAddLabel:1,doAddInput:1,doUnloadNestedLists:1,setText:1,getText:1,setWidth:1,isEnabled:1})
		dhtmlXForm.prototype.items.editor[a] = dhtmlXForm.prototype.items.template[a];
})();

dhtmlXForm.prototype.items.editor.d2 = dhtmlXForm.prototype.items.select.destruct;
dhtmlXForm.prototype.items.editor.doEn = dhtmlXForm.prototype.items.select.enable;
dhtmlXForm.prototype.items.editor.doDis = dhtmlXForm.prototype.items.select.disable;

dhtmlXForm.prototype.getEditor = function(name) {
	return this.doWithItem(name, "getEditor");
};


