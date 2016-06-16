/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

//container - can be a HTML container or it's ID
dhtmlXList = function(container){
	this.name = "List";	//name of component
	
	//enable configuration
	dhtmlx.extend(this, dhtmlx.Settings);
	this._parseContainer(container,"dhx_list");	//assigns parent container
	
	
	
	//behaviors
	dhtmlx.extend(this, dhtmlx.AtomDataLoader);
	dhtmlx.extend(this, dhtmlx.DataLoader);
	dhtmlx.extend(this, dhtmlx.EventSystem);
	dhtmlx.extend(this, dhtmlx.RenderStack);
	dhtmlx.extend(this, dhtmlx.SelectionModel);
	dhtmlx.extend(this, dhtmlx.MouseEvents);
	dhtmlx.extend(this, dhtmlx.KeyEvents);
	dhtmlx.extend(this, dhtmlx.EditAbility);
	dhtmlx.extend(this, dhtmlx.DataMove);
	dhtmlx.extend(this, dhtmlx.DragItem);
	dhtmlx.extend(this, dhtmlx.DataProcessor);
	dhtmlx.extend(this, dhtmlx.AutoTooltip);
	dhtmlx.extend(this, dhtmlx.Destruction);
	
	this._getDimension = function(){
		var t = this.type;
		var d = (t.margin||0)*2;
		//obj.x  - widht, obj.y - height
		return {x : t.width+d, y: t.height+d };
	};
	
	//render self , each time when data is updated
	this.data.attachEvent("onStoreUpdated",dhtmlx.bind(function(){
		this.render.apply(this,arguments);
	},this));

	//default settings
	this._parseSettings(container,{
		drag:false,
		edit:false,
		select:"multiselect", //multiselection is enabled by default
		type:"default"
	});
	
	this.data.provideApi(this,true);

	if (dhtmlx.$customScroll)
		dhtmlx.CustomScroll.enable(this);
};


dhtmlXList.prototype={
	bind:function(){
		dhtmlx.BaseBind.legacyBind.apply(this, arguments);
	},
	sync:function(){
		dhtmlx.BaseBind.legacySync.apply(this, arguments);
	},	
	dragMarker:function(context,ev){
		var el = this._locateHTML(context.target);
		
		if (el && this._settings.auto_scroll){
			//maybe it can be moved to the drag behavior
			var dy = el.offsetTop;
			var dh = el.offsetHeight;
			var py = this._obj.scrollTop;
			var ph = this._obj.offsetHeight;
			//scroll up or down is mouse already pointing on top|bottom visible item
			if (dy-dh >= 0 && dy-dh*0.75 < py)
				py = Math.max(dy-dh, 0);
			else if (dy+dh/0.75 > py+ph)
				py = py+dh;
			
			this._obj.scrollTop = py;
		}
		return true;
	},
	//attribute , which will be used for ID storing
	_id:"dhx_f_id",
	//css class to action map, for onclick event
	on_click:{
		dhx_list_item:function(e,id){ 
			//click on item
			if (this.stopEdit(false,id)){
				if (this._settings.select){
					if (this._settings.select=="multiselect")
						this.select(id, e.ctrlKey, e.shiftKey); 	//multiselection
					else
						this.select(id);
				}
			}
		}	
	},
	//css class to action map, for dblclick event
	on_dblclick:{
		dhx_list_item:function(e,id){ 
			//dblclick on item
			if (this._settings.edit)
				this.edit(id);	//edit it!
		}
	},
	//css class to action map, for mousemove event
	on_mouse_move:{
	},
	types:{
		"default":{
			css:"default",
			//normal state of item
			template:dhtmlx.Template.fromHTML("<div style='padding:10px; white-space:nowrap; overflow:hidden;'>{obj.text}</div>"),
			//template for edit state of item
			template_edit:dhtmlx.Template.fromHTML("<div style='padding:10px; white-space:nowrap; overflow:hidden;'><textarea bind='obj.text'></textarea></div>"),
			//in case of dyn. loading - temporary spacer
			template_loading:dhtmlx.Template.fromHTML("<div style='padding:10px; white-space:nowrap; overflow:hidden;'>Loading...</div>"),
			height:50,
			margin:0,
			padding:10,
			border:1
		}
	},
	template_item_start:dhtmlx.Template.fromHTML("<div dhx_f_id='{-obj.id}' class='dhx_list_item dhx_list_{obj.css}_item{-obj.$selected?_selected:}' style='width:100%; height:{obj.height}px; padding:{obj.padding}px; margin:{obj.margin}px; overflow:hidden;'>"),
	template_item_end:dhtmlx.Template.fromHTML("</div>")
};

dhtmlx.compat("layout");
if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype.attachList = function(conf) {
		
		this.callEvent("_onBeforeContentAttach",["list"]);
		
		var obj = document.createElement("DIV");
		obj.style.width = "100%";
		obj.style.height = "100%";
		obj.style.position = "relative";
		obj.style.overflowX = "hidden";
		this._attachObject(obj);
		
		if (typeof(conf) == "undefined") conf = {};
		obj.id = "ListObject_"+new Date().getTime();
		conf.container = obj.id;
		conf.skin = this.conf.skin;
		
		
		this.dataType = "list";
		this.dataObj = new dhtmlXList(conf);
		
		
		this.dataObj.setSizes = function(){
			this.render();
		}
		
		obj = null;
		
		this.callEvent("_onContentAttach",[]);
		
		return this.dataObj;
	};
	
}

