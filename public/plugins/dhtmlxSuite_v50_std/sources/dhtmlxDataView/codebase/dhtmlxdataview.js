/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/



/* DHX DEPEND FROM FILE 'pager.js'*/


/*
	UI:paging control
*/

/*DHX:Depend template.js*/

dhtmlx.ui.pager=function(container){
	this.name = "Pager";
	
	if (dhtmlx.assert_enabled()) this._assert();
	
	dhtmlx.extend(this, dhtmlx.Settings);
	this._parseContainer(container,"dhx_pager");
	
	dhtmlx.extend(this, dhtmlx.EventSystem);
	dhtmlx.extend(this, dhtmlx.SingleRender);
	dhtmlx.extend(this, dhtmlx.MouseEvents);
	
	this._parseSettings(container,{
		size:10,	//items on page
		page:-1,	//current page
		group:5,	//pages in group
		count:0,	//total count of items
		type:"default"
	});
	
	this.data = this._settings;
	this.refresh();
};

dhtmlx.ui.pager.prototype={
	_id:"dhx_p_id",
	on_click:{
		//on paging button click
		"dhx_pager_item":function(e,id){
			this.select(id);
		}
	},
	select:function(id){
		//id - id of button, number for page buttons
		switch(id){
			case "next":
				id = this._settings.page+1;
				break;
			case "prev":
				id = this._settings.page-1;
				break;
			case "first":
				id = 0;
				break;
			case "last":
				id = this._settings.limit-1;
				break;
			default:
				//use incoming id
				break;
		}
		if (id<0) id=0;
		if (id>=this.data.limit) id=this.data.limit-1;
		if (this.callEvent("onBeforePageChange",[this._settings.page,id])){
			this.data.page = id*1; //must be int
			this.refresh();
			this.callEvent("onAfterPageChange",[id]);	
		}
	},
	types:{
		"default":{ 
			template:dhtmlx.Template.fromHTML("{common.pages()}"),
			//list of page numbers
			pages:function(obj){
				var html="";
				//skip rendering if paging is not fully initialized
				if (obj.page == -1) return "";
				//current page taken as center of view, calculate bounds of group
				obj.min = obj.page-Math.round((obj.group-1)/2);
				obj.max = obj.min + obj.group-1;
				if (obj.min<0){
					obj.max+=obj.min*(-1);
					obj.min=0;
				}
				if (obj.max>=obj.limit){
					obj.min -= Math.min(obj.min,obj.max-obj.limit+1);
					obj.max = obj.limit-1;
				}
				//generate HTML code of buttons
				for (var i=(obj.min||0); i<=obj.max; i++)
					html+=this.button({id:i, index:(i+1), selected:(i == obj.page ?"_selected":"")});
				return html;
			},
			page:function(obj){
				return obj.page+1;
			},
			//go-to-first page button
			first:function(){
				return this.button({ id:"first", index:" &lt;&lt; ", selected:""});
			},
			//go-to-last page button
			last:function(){
				return this.button({ id:"last", index:" &gt;&gt; ", selected:""});
			},
			//go-to-prev page button
			prev:function(){
				return this.button({ id:"prev", index:"&lt;", selected:""});
			},
			//go-to-next page button
			next:function(){
				return this.button({ id:"next", index:"&gt;", selected:""});
			},
			button:dhtmlx.Template.fromHTML("<div dhx_p_id='{obj.id}' class='dhx_pager_item{obj.selected}'>{obj.index}</div>")
			
		}
	},
	//update settings and repaint self
	refresh:function(){
		var s = this._settings;
		//max page number
		s.limit = Math.ceil(s.count/s.size);
		
		//correct page if it is out of limits
		if (s.limit && s.limit != s.old_limit)
			s.page = Math.min(s.limit-1, s.page);
		
		var id = s.page;
		if (id!=-1 && (id!=s.old_page) || (s.limit != s.old_limit)){ 
			//refresh self only if current page or total limit was changed
			this.render();
			this.callEvent("onRefresh",[]);
			s.old_limit = s.limit;	//save for onchange check in next iteration
			s.old_page = s.page;
		}
	},
	template_item_start:dhtmlx.Template.fromHTML("<div>"),
	template_item_end:dhtmlx.Template.fromHTML("</div>")
};


/* DHX DEPEND FROM FILE 'dataprocessor_hook.js'*/


/*
	Behaviour:DataProcessor - translates inner events in dataprocessor calls
	
	@export
		changeId
		setItemStyle
		setUserData
		getUserData
*/

/*DHX:Depend compatibility.js*/
/*DHX:Depend dhtmlx.js*/

dhtmlx.DataProcessor={
	//called from DP as part of dp.init
	_dp_init:function(dp){
		//map methods
		var varname = "_methods";
		dp[varname]=["setItemStyle","","changeId","remove"];
		//after item adding - trigger DP
		this.attachEvent("onAfterAdd",function(id){
			dp.setUpdated(id,true,"inserted");
		});
		this.data.attachEvent("onStoreLoad",dhtmlx.bind(function(driver, data){
			if (driver.getUserData)
				driver.getUserData(data,this._userdata);
		},this));
		
		//after item deleting - trigger DP
		this.attachEvent("onBeforeDelete",function(id){
			if (dp._silent_mode) return true;
			
	        var z=dp.getState(id);
			if (z=="inserted") {  dp.setUpdated(id,false);		return true; }
			if (z=="deleted")  return false;
	    	if (z=="true_deleted")  return true;
	    	
			dp.setUpdated(id,true,"deleted");
	      	return false;
		});
		//after editing - trigger DP
		this.attachEvent("onAfterEditStop",function(id){
			dp.setUpdated(id,true,"updated");
		});
		this.attachEvent("onBindUpdate",function(data){
			window.setTimeout(function(){
				dp.setUpdated(data.id,true,"updated");	
			},1);
		});
		
		varname = "_getRowData";
		//serialize item's data in URL
		dp[varname]=function(id,pref){
			var ev=this.obj.data.get(id);
			var data = {};
			for (var a in ev){
				if (a.indexOf("_")===0) continue;
					data[a]=ev[a];
			}
			
			return data;
		};
		varname = "_clearUpdateFlag";
		dp[varname]=function(){};
		this._userdata = {};
		
		dp.attachEvent("insertCallback", this._dp_callback);
		dp.attachEvent("updateCallback", this._dp_callback);
		dp.attachEvent("deleteCallback", function(upd, id) {
			this.obj.setUserData(id, this.action_param, "true_deleted");
			this.obj.remove(id);
		});
				
		//enable compatibility layer - it will allow to use DP without dhtmlxcommon
		dhtmlx.compat("dataProcessor",dp);
	},
	_dp_callback:function(upd,id){
		this.obj.data.set(id,dhtmlx.DataDriver.xml.getDetails(upd.firstChild));
		this.obj.data.refresh(id);
	},
	//marks item in question with specific styles, not purposed for public usage
	setItemStyle:function(id,style){
		var node = this._locateHTML(id);
		if (node) node.style.cssText+=";"+style; //style is not persistent
	},
	//change ID of item
	changeId:function(oldid, newid){
		this.data.changeId(oldid, newid);
		this.refresh();
	},
	//sets property value, not purposed for public usage
	setUserData:function(id,name,value){
		if (id)
			this.data.get(id)[name]=value;
		else
			this._userdata[name]=value;
	},
	//gets property value, not purposed for public usage
	getUserData:function(id,name){
		return id?this.data.get(id)[name]:this._userdata[name];
	}
};
(function(){
	var temp = "_dp_init";
	dhtmlx.DataProcessor[temp]=dhtmlx.DataProcessor._dp_init;
})();



/* DHX DEPEND FROM FILE 'compatibility_drag.js'*/


/*
	Compatibility hack for DND
	Allows dnd between dhtmlx.dnd and dhtmlxcommon based dnd
	When dnd items - related events will be correctly triggered. 
	onDrag event must define final moving logic, if it is absent - item will NOT be moved automatically
	
	to activate this functionality , next command need to be called
		dhtmlx.compat("dnd");
*/

/*DHX:Depend compatibility.js*/

dhtmlx.compat.dnd = function(){
	//if dhtmlxcommon.js included on the page
	if (window.dhtmlDragAndDropObject){
		var _dragged = "_dragged"; //fake for code compression utility, do not change!
		
		//wrap methods of dhtmlxcommon to inform dhtmlx.dnd logic
		var old_ocl = dhtmlDragAndDropObject.prototype.checkLanding;
		dhtmlDragAndDropObject.prototype.checkLanding=function(node,e,skip){
			old_ocl.apply(this,arguments);
			if (!skip){ 
				var c = dhtmlx.DragControl._drag_context = dhtmlx.DragControl._drag_context||{};
				if (!c.from)
					c.from = this.dragStartObject;
				dhtmlx.DragControl._checkLand(node,e,true);
			}
		};
		
		var old_odp = dhtmlDragAndDropObject.prototype.stopDrag;
		dhtmlDragAndDropObject.prototype.stopDrag=function(e,dot,skip){
			if (!skip){
				if (dhtmlx.DragControl._last){
					dhtmlx.DragControl._active = dragger.dragStartNode;
					dhtmlx.DragControl._stopDrag(e,true);
				}
			}
			old_odp.apply(this,arguments);
		};
		
		
		//pre-create dnd object from dhtmlxcommon
		var dragger = new dhtmlDragAndDropObject();
		
		//wrap drag start process
		var old_start = dhtmlx.DragControl._startDrag;
		dhtmlx.DragControl._startDrag=function(){ 
			old_start.apply(this,arguments);	
			//build list of IDs and fake objects for dhtlmxcommon support
			var c = dhtmlx.DragControl._drag_context;
			if (!c) return;
			var source = [];
			var tsource = [];
			for (var i=0; i < c.source.length; i++){
				source[i]={idd:c.source[i]};
				tsource.push(c.source[i]);
			}
			
			dragger.dragStartNode = {	
				parentNode:{}, 
				parentObject:{ 
					idd:source, 
					id:(tsource.length == 1?tsource[0]:tsource),
					treeNod:{
						object:c.from
					}
				}
			};
			
			//prevent code compression of "_dragged"
			dragger.dragStartNode.parentObject.treeNod[_dragged]=source;
			dragger.dragStartObject = c.from;
		};
		//wrap drop landing checker
		var old_check = dhtmlx.DragControl._checkLand;
		dhtmlx.DragControl._checkLand = function(node,e,skip){
			old_check.apply(this,arguments);
			if (!this._last && !skip){
				//we are in middle of nowhere, check old drop landings
				node = dragger.checkLanding(node,e,true);
			}
		};
		
		//wrap drop routine
		var old_drop = dhtmlx.DragControl._stopDrag;
		dhtmlx.DragControl._stopDrag=function(e,skip){
			old_drop.apply(this,arguments);
			if (dragger.lastLanding && !skip)
				dragger.stopDrag(e,false,true);
		};
		//extend getMaster, so they will be able to recognize master objects from dhtmlxcommon.js
		var old_mst = 	dhtmlx.DragControl.getMaster;
		dhtmlx.DragControl.getMaster = function(t){
			var master = null;
			if (t)
				master = old_mst.apply(this,arguments);
			if (!master){
				master = dragger.dragStartObject;
				var src = [];
				var from = master[_dragged];
				for (var i=0; i < from.length; i++) {
					src.push(from[i].idd||from[i].id);
				}
				dhtmlx.DragControl._drag_context.source = src;
			}
			return master;
		};
		
	}
};


/* DHX DEPEND FROM FILE 'move.js'*/


/*
	Behavior:DataMove - allows to move and copy elements, heavily relays on DataStore.move
	@export
		copy
		move
*/
dhtmlx.DataMove={
	_init:function(){
		dhtmlx.assert(this.data, "DataMove :: Component doesn't have DataStore");
	},
	//creates a copy of the item
	copy:function(sid,tindex,tobj,tid){
		var data = this.get(sid);
		if (!data){
			dhtmlx.log("Warning","Incorrect ID in DataMove::copy");
			return;
		}
		
		//make data conversion between objects
		if (tobj){
			dhtmlx.assert(tobj.externalData,"DataMove :: External object doesn't support operation");	
			data = tobj.externalData(data);
		}
		tobj = tobj||this;
		//adds new element same as original
		return tobj.add(tobj.externalData(data,tid),tindex);
	},
	//move item to the new position
	move:function(sid,tindex,tobj,tid){
		//can process an arrya - it allows to use it from onDrag 
		if (sid instanceof Array){
			for (var i=0; i < sid.length; i++) {
				//increase index for each next item in the set, so order of insertion will be equal to order in the array
				var new_index = (tobj||this).indexById(this.move(sid[i], tindex, tobj, sid[i]));
				if (sid[i+1])
					tindex = new_index+(this.indexById(sid[i+1])<new_index?0:1);
				
			}
			return;
		}
		
		nid = sid; //id after moving
		if (tindex<0){
			dhtmlx.log("Info","DataMove::move - moving outside of bounds is ignored");
			return;
		}
		
		var data = this.get(sid);
		if (!data){
			dhtmlx.log("Warning","Incorrect ID in DataMove::move");
			return;
		}
		
		if (!tobj || tobj == this)
			this.data.move(this.indexById(sid),tindex);	//move inside the same object
		else {
			dhtmlx.assert(tobj.externalData, "DataMove :: External object doesn't support operation");
			//copy to the new object
			nid=tobj.add(tobj.externalData(data,tid),tindex);
			this.remove(sid);//delete in old object
		}
		return nid;	//return ID of item after moving
	},
	//move item on one position up
	moveUp:function(id,step){
		return this.move(id,this.indexById(id)-(step||1));
	},
	//move item on one position down
	moveDown:function(id,step){
		return this.moveUp(id, (step||1)*-1);
	},
	//move item to the first position
	moveTop:function(id){
		return this.move(id,0);
	},
	//move item to the last position
	moveBottom:function(id){
		return this.move(id,this.data.dataCount()-1);
	},
	/*
		this is a stub for future functionality
		currently it just makes a copy of data object, which is enough for current situation
	*/
	externalData:function(data,id){
		//FIXME - will not work for multi-level data
		var newdata = dhtmlx.extend({},data);
		newdata.id = id||dhtmlx.uid();
		
		newdata.$selected=newdata.$template=null;
		return newdata;
	}
};


/* DHX DEPEND FROM FILE 'dnd.js'*/


/*
	Behavior:DND - low-level dnd handling
	@export
		getContext
		addDrop
		addDrag
		
	DND master can define next handlers
		onCreateDrag
		onDragIng
		onDragOut
		onDrag
		onDrop
	all are optional
*/

/*DHX:Depend dhtmlx.js*/

dhtmlx.DragControl={
	//has of known dnd masters
	_drag_masters : dhtmlx.toArray(["dummy"]),
	/*
		register drop area
		@param node 			html node or ID
		@param ctrl 			options dnd master
		@param master_mode 		true if you have complex drag-area rules
	*/
	addDrop:function(node,ctrl,master_mode){
		node = dhtmlx.toNode(node);
		node.dhx_drop=this._getCtrl(ctrl);
		if (master_mode) node.dhx_master=true;
	},
	//return index of master in collection
	//it done in such way to prevent dnd master duplication
	//probably useless, used only by addDrop and addDrag methods
	_getCtrl:function(ctrl){
		ctrl = ctrl||dhtmlx.DragControl;
		var index = this._drag_masters.find(ctrl);
		if (index<0){
			index = this._drag_masters.length;
			this._drag_masters.push(ctrl);
		}
		return index;
	},
	/*
		register drag area
		@param node 	html node or ID
		@param ctrl 	options dnd master
	*/
	addDrag:function(node,ctrl){
	    node = dhtmlx.toNode(node);
	    node.dhx_drag=this._getCtrl(ctrl);
		dhtmlx.event(node,"mousedown",this._preStart,node);
	},
	//logic of drag - start, we are not creating drag immediately, instead of that we hears mouse moving
	_preStart:function(e){
		if (dhtmlx.DragControl._active){
			dhtmlx.DragControl._preStartFalse();
			dhtmlx.DragControl.destroyDrag();
		}
		dhtmlx.DragControl._active=this;
		dhtmlx.DragControl._start_pos={x:e.pageX, y:e.pageY};
		dhtmlx.DragControl._dhx_drag_mm = dhtmlx.event(document.body,"mousemove",dhtmlx.DragControl._startDrag);
		dhtmlx.DragControl._dhx_drag_mu = dhtmlx.event(document.body,"mouseup",dhtmlx.DragControl._preStartFalse);
		//http://code.google.com/p/chromium/issues/detail?id=14204
		dhtmlx.DragControl._dhx_drag_sc = dhtmlx.event(this,"scroll",dhtmlx.DragControl._preStartFalse);

		e.cancelBubble=true;
		return false;
	},
	//if mouse was released before moving - this is not a dnd, remove event handlers
	_preStartFalse:function(e){
		dhtmlx.DragControl._dhx_drag_mm = dhtmlx.eventRemove(dhtmlx.DragControl._dhx_drag_mm);
		dhtmlx.DragControl._dhx_drag_mu = dhtmlx.eventRemove(dhtmlx.DragControl._dhx_drag_mu);		
		dhtmlx.DragControl._dhx_drag_sc = dhtmlx.eventRemove(dhtmlx.DragControl._dhx_drag_sc);		
	},
	//mouse was moved without button released - dnd started, update event handlers
	_startDrag:function(e){
		//prevent unwanted dnd
		var pos = {x:e.pageX, y:e.pageY};
		if (Math.abs(pos.x-dhtmlx.DragControl._start_pos.x)<5 && Math.abs(pos.y-dhtmlx.DragControl._start_pos.y)<5)
			return;

		dhtmlx.DragControl._preStartFalse();
		if (!dhtmlx.DragControl.createDrag(e)) return;
		
		dhtmlx.DragControl.sendSignal("start"); //useless for now
		dhtmlx.DragControl._dhx_drag_mm = dhtmlx.event(document.body,"mousemove",dhtmlx.DragControl._moveDrag);
		dhtmlx.DragControl._dhx_drag_mu = dhtmlx.event(document.body,"mouseup",dhtmlx.DragControl._stopDrag);
		dhtmlx.DragControl._moveDrag(e);
	},
	//mouse was released while dnd is active - process target
	_stopDrag:function(e){
		dhtmlx.DragControl._dhx_drag_mm = dhtmlx.eventRemove(dhtmlx.DragControl._dhx_drag_mm);
		dhtmlx.DragControl._dhx_drag_mu = dhtmlx.eventRemove(dhtmlx.DragControl._dhx_drag_mu);
		if (dhtmlx.DragControl._last){	//if some drop target was confirmed
			dhtmlx.DragControl.onDrop(dhtmlx.DragControl._active,dhtmlx.DragControl._last,this._landing,e);
			dhtmlx.DragControl.onDragOut(dhtmlx.DragControl._active,dhtmlx.DragControl._last,null,e);
		}
		dhtmlx.DragControl.destroyDrag();
		dhtmlx.DragControl.sendSignal("stop");	//useless for now
	},
	//dnd is active and mouse position was changed
	_moveDrag:function(e){
		var pos = dhtmlx.html.pos(e);
		//adjust drag marker position
		dhtmlx.DragControl._html.style.top=pos.y+dhtmlx.DragControl.top +"px";
		dhtmlx.DragControl._html.style.left=pos.x+dhtmlx.DragControl.left+"px";
		
		if (dhtmlx.DragControl._skip)
			dhtmlx.DragControl._skip=false;
		else
			dhtmlx.DragControl._checkLand((e.srcElement||e.target),e);
		
		e.cancelBubble=true;
		return false;		
	},
	//check if item under mouse can be used as drop landing
	_checkLand:function(node,e){ 
		while (node && node.tagName!="BODY"){
			if (node.dhx_drop){	//if drop area registered
				if (this._last && (this._last!=node || node.dhx_master))	//if this area with complex dnd master
					this.onDragOut(this._active,this._last,node,e);			//inform master about possible mouse-out
				if (!this._last || this._last!=node || node.dhx_master){	//if this is new are or area with complex dnd master
				    this._last=null;										//inform master about possible mouse-in
					this._landing=this.onDragIn(dhtmlx.DragControl._active,node,e);
					if (this._landing)	//landing was rejected
						this._last=node;
					return;				
				} 
				return;
			}
			node=node.parentNode;
		}
		if (this._last)	//mouse was moved out of previous landing, and without finding new one 
			this._last = this._landing = this.onDragOut(this._active,this._last,null,e);
	},
	//mostly useless for now, can be used to add cross-frame dnd
	sendSignal:function(signal){
		dhtmlx.DragControl.active=(signal=="start");
	},
	
	//return master for html area
	getMaster:function(t){
		return this._drag_masters[t.dhx_drag||t.dhx_drop];
	},
	//return dhd-context object
	getContext:function(t){
		return this._drag_context;
	},
	//called when dnd is initiated, must create drag representation
	createDrag:function(e){ 
		var a=dhtmlx.DragControl._active;
		var master = this._drag_masters[a.dhx_drag];
		var drag_container;
		
		//if custom method is defined - use it
		if (master.onDragCreate){
			drag_container=master.onDragCreate(a,e);
			drag_container.style.position='absolute';
			drag_container.style.zIndex=dhtmlx.zIndex.drag;
			drag_container.onmousemove=dhtmlx.DragControl._skip_mark;
		} else {
		//overvise use default one
			var text = dhtmlx.DragControl.onDrag(a,e);
			if (!text) return false;
			var drag_container = document.createElement("DIV");
			drag_container.innerHTML=text;
			drag_container.className="dhx_drag_zone";
			drag_container.onmousemove=dhtmlx.DragControl._skip_mark;
			document.body.appendChild(drag_container);
		}
		dhtmlx.DragControl._html=drag_container;
		return true;
	},
	//helper, prevents unwanted mouse-out events
	_skip_mark:function(){
		dhtmlx.DragControl._skip=true;
	},
	//after dnd end, remove all traces and used html elements
	destroyDrag:function(){
		var a=dhtmlx.DragControl._active;
		var master = this._drag_masters[a.dhx_drag];
		
		if (master && master.onDragDestroy)
			master.onDragDestroy(a,dhtmlx.DragControl._html);
		else dhtmlx.html.remove(dhtmlx.DragControl._html);
		
		dhtmlx.DragControl._landing=dhtmlx.DragControl._active=dhtmlx.DragControl._last=dhtmlx.DragControl._html=null;
	},
	top:5,	 //relative position of drag marker to mouse cursor
	left:5,
	//called when mouse was moved in drop area
	onDragIn:function(s,t,e){
		var m=this._drag_masters[t.dhx_drop];
		if (m.onDragIn && m!=this) return m.onDragIn(s,t,e);
		t.className=t.className+" dhx_drop_zone";
		return t;
	},
	//called when mouse was moved out drop area
	onDragOut:function(s,t,n,e){
		var m=this._drag_masters[t.dhx_drop];
		if (m.onDragOut && m!=this) return m.onDragOut(s,t,n,e);
		t.className=t.className.replace("dhx_drop_zone","");
		return null;
	},
	//called when mouse was released over drop area
	onDrop:function(s,t,d,e){
		var m=this._drag_masters[t.dhx_drop];
		dhtmlx.DragControl._drag_context.from = dhtmlx.DragControl.getMaster(s);
		if (m.onDrop && m!=this) return m.onDrop(s,t,d,e);
		t.appendChild(s);
	},
	//called when dnd just started
	onDrag:function(s,e){
		var m=this._drag_masters[s.dhx_drag];
		if (m.onDrag && m!=this) return m.onDrag(s,e);
		dhtmlx.DragControl._drag_context = {source:s, from:s};
		return "<div style='"+s.style.cssText+"'>"+s.innerHTML+"</div>";
	}	
};


/* DHX DEPEND FROM FILE 'drag.js'*/


/*
	Behavior:DragItem - adds ability to move items by dnd
	
	dnd context can have next properties
		from - source object
		to - target object
		source - id of dragged item(s)
		target - id of drop target, null for drop on empty space
		start - id from which DND was started
*/

/*DHX:Depend dnd.js*/		/*DHX:Depend move.js*/		/*DHX:Depend compatibility_drag.js*/ 	
/*DHX:Depend dhtmlx.js*/



dhtmlx.DragItem={
	_init:function(){
		dhtmlx.assert(this.move,"DragItem :: Component doesn't have DataMove interface");
		dhtmlx.assert(this.locate,"DragItem :: Component doesn't have RenderStack interface");
		dhtmlx.assert(dhtmlx.DragControl,"DragItem :: DragControl is not included");
		
		if (!this._settings || this._settings.drag)
			dhtmlx.DragItem._initHandlers(this);
		else if (this._settings){
			//define setter, which may be triggered by config call
			this.drag_setter=function(value){
				if (value){
					this._initHandlers(this);
					delete this.drag_setter;	//prevent double initialization
				}
				return value;
			};
		}
		//if custom dnd marking logic is defined - attach extra handlers
		if (this.dragMarker){
			this.attachEvent("onBeforeDragIn",this.dragMarker);
			this.attachEvent("onDragOut",this.dragMarker);
		}
			
	},
	//helper - defines component's container as active zone for dragging and for dropping
	_initHandlers:function(obj){
		dhtmlx.DragControl.addDrop(obj._obj,obj,true);
		dhtmlx.DragControl.addDrag(obj._obj,obj);	
	},
	/*
		s - source html element
		t - target html element
		d - drop-on html element ( can be not equal to the target )
		e - native html event 
	*/
	//called when drag moved over possible target
	onDragIn:function(s,t,e){
		var id = this.locate(e) || null;
		var context = dhtmlx.DragControl._drag_context;
		var to = dhtmlx.DragControl.getMaster(s);
		//previous target
		var html = (this._locateHTML(id)||this._obj);
		//prevent double processing of same target
		if (html == dhtmlx.DragControl._landing) return html;
		context.target = id;
		context.to = to;
		
		if (!this.callEvent("onBeforeDragIn",[context,e])){
			context.id = null;
			return null;
		}
		
		dhtmlx.html.addCss(html,"dhx_drag_over"); //mark target
		return html;
	},
	//called when drag moved out from possible target
	onDragOut:function(s,t,n,e){ 
		var id = this.locate(e) || null;
        if (n != this._dataobj)
            id = null;
		//previous target
		var html = (this._locateHTML(id)||(n?dhtmlx.DragControl.getMaster(n)._obj:window.undefined));
		if (html == dhtmlx.DragControl._landing) return null;
		//unmark previous target
		var context = dhtmlx.DragControl._drag_context;
		dhtmlx.html.removeCss(dhtmlx.DragControl._landing,"dhx_drag_over");
		context.target = context.to = null;
		this.callEvent("onDragOut",[context,e]);
		return null;
	},
	//called when drag moved on target and button is released
	onDrop:function(s,t,d,e){ 
		var context = dhtmlx.DragControl._drag_context;
		
		//finalize context details
		context.to = this;
		context.index = context.target?this.indexById(context.target):this.dataCount();
		context.new_id = dhtmlx.uid();
		if (!this.callEvent("onBeforeDrop",[context,e])) return;
		//moving
		if (context.from==context.to){
			this.move(context.source,context.index);	//inside the same component
		} else {
			if (context.from)	//from different component
				context.from.move(context.source,context.index,context.to,context.new_id);
			else
				dhtmlx.error("Unsopported d-n-d combination");
		}
		this.callEvent("onAfterDrop",[context,e]);
	},
	//called when drag action started
	onDrag:function(s,e){
		var id = this.locate(e);
		var list = [id];
		if (id){
			if (this.getSelected){ //has selection model
				var selection = this.getSelected();	//if dragged item is one of selected - drag all selected
				if (dhtmlx.PowerArray.find.call(selection,id)!=-1)
					list = selection;
			}
			//save initial dnd params
			var context = dhtmlx.DragControl._drag_context= { source:list, start:id };
			context.from = this;
			
			if (this.callEvent("onBeforeDrag",[context,e]))
				return context.html||this._toHTML(this.get(id));	//set drag representation
		}
		return null;
	}
	//returns dnd context object
	/*getDragContext:function(){
		return dhtmlx.DragControl._drag_context;
	}*/
};


/* DHX DEPEND FROM FILE 'edit.js'*/


/*
	Behavior:EditAbility - enables item operation for the items
	
	@export
		edit
		stopEdit
*/
dhtmlx.EditAbility={
	_init: function(id){
		this._edit_id = null;		//id of active item 
		this._edit_bind = null;		//array of input-to-property bindings

		dhtmlx.assert(this.data,"EditAbility :: Component doesn't have DataStore");
		dhtmlx.assert(this._locateHTML,"EditAbility :: Component doesn't have RenderStack");
				
		this.attachEvent("onEditKeyPress",function(code, ctrl, shift){
			if (code == 13 && !shift)
				this.stopEdit();
			else if (code == 27) 
				this.stopEdit(true);
		});
		this.attachEvent("onBeforeRender", function(){
			this.stopEdit();
		});
    	
	},
	//returns id of item in edit state, or null if none
	isEdit:function(){
		return this._edit_id;
	},
	//switch item to the edit state
	edit:function(id){
		//edit operation can be blocked from editStop - when previously active editor can't be closed			
		if (this.stopEdit(false, id)){
			if (!this.callEvent("onBeforeEditStart",[id])) 
				return;			
			var data = this.data.get(id);			
			//object with custom templates is not editable
			if (data.$template) return;
			
			//item must have have "edit" template
 			data.$template="edit";	
			this.data.refresh(id);
			this._edit_id = id;
			
			//parse templates and save input-property mapping
			this._save_binding(id);
			this._edit_bind(true,data);	//fill inputs with data
			
			this.callEvent("onAfterEditStart",[id]);	
		}
	},
	//close currently active editor
	stopEdit:function(mode, if_not_id){
		if (!this._edit_id) return true;
		if (this._edit_id == if_not_id) return false;

		var values = {};
		if (!mode) this._edit_bind(false,values);
		else values = null;

		if (!this.callEvent("onBeforeEditStop",[this._edit_id, values]))
			return false;
			
		var data=this.data.get(this._edit_id);
		data.$template=null;	//set default template
		
		//load data from inputs
		//if mode is set - close editor without saving
		if (!mode) this._edit_bind(false,data);
		var id = this._edit_id;
		this._edit_bind=this._edit_id=null;
		
		this.data.refresh(id);
		
		this.callEvent("onAfterEditStop",[id, values]);
		return true;
	},
	//parse template and save inputs which need to be mapped to the properties
	_save_binding:function(id){
		var cont = this._locateHTML(id);
		var code = "";			//code of prop-to-inp method
		var back_code = "";		//code of inp-to-prop method
		var bind_elements = [];	//binded inputs
		if (cont){
			var elements = cont.getElementsByTagName("*");		//all sub-tags
			var bind = "";
			for (var i=0; i < elements.length; i++) {
				if(elements[i].nodeType==1 && (bind = elements[i].getAttribute("bind"))){	//if bind present
					//code for element accessing 
					code+="els["+bind_elements.length+"].value="+bind+";";
					back_code+=bind+"=els["+bind_elements.length+"].value;";
					bind_elements.push(elements[i]);
					//clear block-selection for the input
					elements[i].className+=" dhx_allow_selection";
					elements[i].onselectstart=this._block_native;
				}
			}
			elements = null;
		}
		//create accessing methods, for later usage
		code = Function("obj","els",code);
		back_code = Function("obj","els",back_code);
		this._edit_bind = function(mode,obj){
			if (mode){	//property to input
				code(obj,bind_elements);	
				if (bind_elements.length && bind_elements[0].select) //focust first html input, if possible
					bind_elements[0].select();						 
			}
			else 		//input to propery
				back_code(obj,bind_elements);
		};
	},
	//helper - blocks event bubbling, used to stop click event on editor level
	_block_native:function(e){ (e||event).cancelBubble=true; return true; }
};


/* DHX DEPEND FROM FILE 'selection.js'*/


/*
	Behavior:SelectionModel - manage selection states
	@export
		select
		unselect
		selectAll
		unselectAll
		isSelected
		getSelected
*/
dhtmlx.SelectionModel={
	_init:function(){
		//collection of selected IDs
		this._selected = dhtmlx.toArray();
		dhtmlx.assert(this.data, "SelectionModel :: Component doesn't have DataStore");
         	
		//remove selection from deleted items
		this.data.attachEvent("onStoreUpdated",dhtmlx.bind(this._data_updated,this));
		this.data.attachEvent("onStoreLoad", dhtmlx.bind(this._data_loaded,this));
		this.data.attachEvent("onAfterFilter", dhtmlx.bind(this._data_filtered,this));
		this.data.attachEvent("onIdChange", dhtmlx.bind(this._id_changed,this));
	},
	_id_changed:function(oldid, newid){
		for (var i = this._selected.length - 1; i >= 0; i--)
			if (this._selected[i]==oldid)
				this._selected[i]=newid;
	},
	_data_filtered:function(){
		for (var i = this._selected.length - 1; i >= 0; i--){
			if (this.data.indexById(this._selected[i]) < 0){
				var id = this._selected[i];
				var item = this.item(id);
				if (item)
					delete item.$selected;
				this._selected.splice(i,1);
				this.callEvent("onSelectChange",[id]);
			}
		}	
	},
	//helper - linked to onStoreUpdated
	_data_updated:function(id,obj,type){
		if (type == "delete")				//remove selection from deleted items
			this._selected.remove(id);
		else if (!this.data.dataCount() && !this.data._filter_order){	//remove selection for clearAll
			this._selected = dhtmlx.toArray();
		}
	},
	_data_loaded:function(){
		if (this._settings.select)
			this.data.each(function(obj){
				if (obj.$selected) this.select(obj.id);
			}, this);
	},
	//helper - changes state of selection for some item
	_select_mark:function(id,state,refresh){
		if (!refresh && !this.callEvent("onBeforeSelect",[id,state])) return false;
		
		this.data.item(id).$selected=state;	//set custom mark on item
		if (refresh)
			refresh.push(id);				//if we in the mass-select mode - collect all changed IDs
		else{
			if (state)
				this._selected.push(id);		//then add to list of selected items
		else
				this._selected.remove(id);
			this._refresh_selection(id);	//othervise trigger repainting
		}
			
		return true;
	},
	//select some item
	select:function(id,non_modal,continue_old){
		//if id not provide - works as selectAll
		if (!id) return this.selectAll();

		//allow an array of ids as parameter
		if (id instanceof Array){
			for (var i=0; i < id.length; i++)
				this.select(id[i], non_modal, continue_old);
			return;
		}

		if (!this.data.exists(id)){
			dhtmlx.error("Incorrect id in select command: "+id);
			return;
		}
		
		//block selection mode
		if (continue_old && this._selected.length)
			return this.selectAll(this._selected[this._selected.length-1],id);
		//single selection mode
		if (!non_modal && (this._selected.length!=1 || this._selected[0]!=id)){
			this._silent_selection = true; //prevent unnecessary onSelectChange event
			this.unselectAll();
			this._silent_selection = false;
		}
		if (this.isSelected(id)){
			if (non_modal) this.unselect(id);	//ctrl-selection of already selected item
			return;
		}
		
		if (this._select_mark(id,true)){	//if not blocked from event
			this.callEvent("onAfterSelect",[id]);
		}
	},
	//unselect some item
	unselect:function(id){
		//if id is not provided  - unselect all items
		if (!id) return this.unselectAll();
		if (!this.isSelected(id)) return;
		
		this._select_mark(id,false);
	},
	//select all items, or all in defined range
	selectAll:function(from,to){
		var range;
		var refresh=[];
		
		if (from||to)
			range = this.data.getRange(from||null,to||null);	//get limited set if bounds defined
		else
			range = this.data.getRange();			//get all items in other case
												//in case of paging - it will be current page only
		range.each(function(obj){ 
			var d = this.data.item(obj.id);
			if (!d.$selected){	
				this._selected.push(obj.id);	
				this._select_mark(obj.id,true,refresh);
			}
			return obj.id; 
		},this);
		//repaint self
		this._refresh_selection(refresh);
	},
	//remove selection from all items
	unselectAll:function(){
		var refresh=[];
		
		this._selected.each(function(id){
			this._select_mark(id,false,refresh);	//unmark selected only
		},this);
		
		this._selected=dhtmlx.toArray();
		this._refresh_selection(refresh);	//repaint self
	},
	//returns true if item is selected
	isSelected:function(id){
		return this._selected.find(id)!=-1;
	},
	/*
		returns ID of selected items or array of IDs
		to make result predictable - as_array can be used, 
			with such flag command will always return an array 
			empty array in case when no item was selected
	*/
	getSelected:function(as_array){	
		switch(this._selected.length){
			case 0: return as_array?[]:"";
			case 1: return as_array?[this._selected[0]]:this._selected[0];
			default: return ([].concat(this._selected)); //isolation
		}
	},
	//detects which repainting mode need to be used
	_is_mass_selection:function(obj){
		 // crappy heuristic, but will do the job
		return obj.length>100 || obj.length > this.data.dataCount/2;
	},
	_refresh_selection:function(refresh){
		if (typeof refresh != "object") refresh = [refresh];
		if (!refresh.length) return;	//nothing to repaint
		
		if (this._is_mass_selection(refresh))	
			this.data.refresh();	//many items was selected - repaint whole view
		else
			for (var i=0; i < refresh.length; i++)	//repaint only selected
				this.render(refresh[i],this.data.item(refresh[i]),"update");
			
		if (!this._silent_selection)	
		this.callEvent("onSelectChange",[refresh]);
	}
};




/* DHX DEPEND FROM FILE 'render.js'*/


/*
	Renders collection of items
	Behavior uses plain strategy which suits only for relative small datasets
	
	@export
		locate
		show
		render
*/
dhtmlx.RenderStack={
	_init:function(){
		dhtmlx.assert(this.data,"RenderStack :: Component doesn't have DataStore");
        dhtmlx.assert(dhtmlx.Template,"dhtmlx.Template :: dhtmlx.Template is not accessible");

		//used for temporary HTML elements
		//automatically nulified during destruction
		this._html = document.createElement("DIV");

	},
	//convert single item to HTML text (templating)
	_toHTML:function(obj){
			//check if related template exist
			dhtmlx.assert((!obj.$template || this.type["template_"+obj.$template]),"RenderStack :: Unknown template: "+obj.$template);
                        
			/*mm: fix allows to call the event for all objects (PropertySheet)*/	
			//if (obj.$template) //custom template
				this.callEvent("onItemRender",[obj]);
			/*
				$template property of item, can contain a name of custom template
			*/	
			return this.type._item_start(obj,this.type)+(obj.$template?this.type["template_"+obj.$template]:this.type.template)(obj,this.type)+this.type._item_end;
	},
	//convert item to HTML object (templating)
	_toHTMLObject:function(obj){
		this._html.innerHTML = this._toHTML(obj);
		return this._html.firstChild;
	},
	//return html container by its ID
	//can return undefined if container doesn't exists
	_locateHTML:function(search_id){
		if (this._htmlmap)
			return this._htmlmap[search_id];
			
		//fill map if it doesn't created yet
		this._htmlmap={};
		
		var t = this._dataobj.childNodes;
		for (var i=0; i < t.length; i++){
			var id = t[i].getAttribute(this._id); //get item's
			if (id) 
				this._htmlmap[id]=t[i];
		}
		//call locator again, when map is filled
		return this._locateHTML(search_id);
	},
	//return id of item from html event
	locate:function(e){ return dhtmlx.html.locate(e,this._id); },
	//change scrolling state of top level container, so related item will be in visible part
	show:function(id){
		var html = this._locateHTML(id);
		if (html)
			this._dataobj.scrollTop = html.offsetTop-this._dataobj.offsetTop;
	},
	//update view after data update
	//method calls low-level rendering for related items
	//when called without parameters - all view refreshed
	render:function(id,data,type,after){
		if (id){
			var cont = this._locateHTML(id); //get html element of updated item
			switch(type){
				case "update":
					//in case of update - replace existing html with updated one
					if (!cont) return;
					var t = this._htmlmap[id] = this._toHTMLObject(data);
					dhtmlx.html.insertBefore(t, cont); 
					dhtmlx.html.remove(cont);
					break;
				case "delete":
					//in case of delete - remove related html
					if (!cont) return;
					dhtmlx.html.remove(cont);
					delete this._htmlmap[id];
					break;
				case "add":
					//in case of add - put new html at necessary position
					var t = this._htmlmap[id] = this._toHTMLObject(data);
					dhtmlx.html.insertBefore(t, this._locateHTML(this.data.next(id)), this._dataobj);
					break;
				case "move":
					//in case of move , simulate add - delete sequence
					//it will affect only rendering 
					this.render(id,data,"delete");
					this.render(id,data,"add");
					break;
				default:
					dhtmlx.error("Unknown render command: "+type);
					break;
			}
		} else {
			//full reset
			if (this.callEvent("onBeforeRender",[this.data])){
				//getRange - returns all elements
				this._dataobj.innerHTML = this.data.getRange().map(this._toHTML,this).join("");
				this._htmlmap = null; //clear map, it will be filled at first _locateHTML
			}
		}
		this.callEvent("onAfterRender",[]);
	},
	//pager attachs handler to onBeforeRender, to limit visible set of data 
	//data.min and data.max affect result of data.getRange()
	pager_setter:function(value){ 
		this.attachEvent("onBeforeRender",function(){
			var s = this._settings.pager._settings;
			//initial value of pager = -1, waiting for real value
			if (s.page == -1) return false;	
			
			this.data.min = s.page*s.size;	//affect data.getRange
			this.data.max = (s.page+1)*s.size-1;
			return true;
		});
	
		var pager = new dhtmlx.ui.pager(value);
		//update functor
		var update = dhtmlx.bind(function(){
			this.data.refresh();
		},this);
		
		//when values of pager are changed - repaint view
		pager.attachEvent("onRefresh",update);
		//when something changed in DataStore - update configuration of pager
		//during refresh - pager can call onRefresh method which will cause repaint of view
		this.data.attachEvent("onStoreUpdated",function(data){
			var count = this.dataCount();
			if (count != pager._settings.count){
				pager._settings.count = count;
				//set first page
				//it is called first time after data loading
				//until this time pager is not rendered
				if (pager._settings.page == -1)
					pager._settings.page = 0;
					
				pager.refresh();
			}
		});
		return pager;
	},
	//can be used only to trigger auto-height
	height_setter:function(value){
		if (value=="auto"){
			//react on resize of window and self-repainting
			this.attachEvent("onAfterRender",this._correct_height);
			dhtmlx.event(window,"resize",dhtmlx.bind(this._correct_height,this));
		}
		return value;
	},
	//update height of container to remove inner scrolls
	_correct_height:function(){
		//disable scrolls - if we are using auto-height , they are not necessary
		this._dataobj.style.overflow="hidden";
		//set min. size, so we can fetch real scroll height
		this._dataobj.style.height = "1px";
		
		var t = this._dataobj.scrollHeight;
		this._dataobj.style.height = t+"px";
		// FF has strange issue with height caculation 
		// it incorrectly detects scroll height when only small part of item is invisible
		if (dhtmlx._isFF){ 
			var t2 = this._dataobj.scrollHeight;
			if (t2!=t)
				this._dataobj.style.height = t2+"px";
		}
		this._obj.style.height = this._dataobj.style.height;
	},
	//get size of single item
	_getDimension:function(){
		var t = this.type;
		var d = (t.border||0)+(t.padding||0)*2+(t.margin||0)*2;
		//obj.x  - widht, obj.y - height
		return {x : t.width+d, y: t.height+d };
	},
	//x_count propery sets width of container, so N items can be placed on single line
	x_count_setter:function(value){
		var dim = this._getDimension();
		var scrfix = dhtmlx.$customScroll ? 0 : 18;
		this._dataobj.style.width = dim.x*value+(this._settings.height != "auto" ? scrfix : 0)+"px";
		return value;
	},
	//x_count propery sets height of container, so N items a visible in one column
	//column will have scroll if real count of lines is greater than N
	y_count_setter:function(value){
		var dim = this._getDimension();
		this._dataobj.style.height = dim.y*value+"px";
		return value;
	}
};


/* DHX DEPEND FROM FILE 'virtual_render.js'*/


/*
	Renders collection of items
	Always shows y-scroll
	Can be used with huge datasets
	
	@export
		show
		render
*/

/*DHX:Depend render.js*/ 

dhtmlx.VirtualRenderStack={
	_init:function(){
		dhtmlx.assert(this.render,"VirtualRenderStack :: Object must use RenderStack first");
        	
        this._htmlmap={}; //init map of rendered elements
        //in this mode y-scroll is always visible
        //it simplifies calculations a lot
        this._dataobj.style.overflowY="scroll";
        
        //we need to repaint area each time when view resized or scrolling state is changed
        dhtmlx.event(this._dataobj,"scroll",dhtmlx.bind(this._render_visible_rows,this));
        dhtmlx.event(window,"resize",dhtmlx.bind(function(){ this.render(); },this));

		//here we store IDs of elemenst which doesn't loadede yet, but need to be rendered
		this.data._unrendered_area=[];
		this.data.getIndexRange=this._getIndexRange;
	},
	//return html object by item's ID. Can return null for not-rendering element
	_locateHTML:function(search_id){
		//collection was filled in _render_visible_rows
		return this._htmlmap[search_id];
	},
	//adjust scrolls to make item visible
	show:function(id){
		range = this._getVisibleRange();
		var ind = this.data.indexById(id);
		//we can't use DOM method for not-rendered-yet items, so fallback to pure math
		var dy = Math.floor(ind/range._dx)*range._y;
		this._dataobj.scrollTop = dy;
	},	
	_getIndexRange:function(from,to){
		if (to !== 0)
			to=Math.min((to||Infinity),this.dataCount()-1);
		
		var ret=dhtmlx.toArray(); //result of method is rich-array
		for (var i=(from||0); i <= to; i++){
			var item = this.item(this.order[i]);
			if(this.order.length>i){
                if (!item){
                    this.order[i]=dhtmlx.uid();
                    item = { id:this.order[i], $template:"loading" };
                    this._unrendered_area.push(this.order[i]);	//store item ID for later loading
                } else if (item.$template == "loading")
                    this._unrendered_area.push(this.order[i]);
                ret.push(item);
            }

		}
		return ret;
	},	
	//repain self after changes in DOM
	//for add, delete, move operations - render is delayed, to minify performance impact
	render:function(id,data,type,after){ 
		if (id){
			var cont = this._locateHTML(id);	//old html element
			switch(type){
				case "update":
					if (!cont) return;
					//replace old with new
					var t = this._htmlmap[id] = this._toHTMLObject(data);
					dhtmlx.html.insertBefore(t, cont); 
					dhtmlx.html.remove(cont);
					break;
				default: // "move", "add", "delete"
					/*
						for all above operations, full repainting is necessary
						but from practical point of view, we need only one repainting per thread
						code below initiates double-thread-rendering trick
					*/
					this._render_delayed();
					break;
			}
		} else {
			//full repainting
			if (this.callEvent("onBeforeRender",[this.data])){
				this._htmlmap = {}; 					//nulify links to already rendered elements
				this._render_visible_rows(null, true);	
				// clear delayed-rendering, because we already have repaint view
				this._wait_for_render = false;			
				this.callEvent("onAfterRender",[]);
			}
		}
	},
	//implement double-thread-rendering pattern
	_render_delayed:function(){
		//this flag can be reset from outside, to prevent actual rendering 
		if (this._wait_for_render) return;
		this._wait_for_render = true;	
		
		window.setTimeout(dhtmlx.bind(function(){
			this.render();
		},this),1);
	},
	//create empty placeholders, which will take space before rendering
	_create_placeholder:function(height){
		var node = document.createElement("DIV");
			node.className = "dhxdataview_placeholder";
			node.style.cssText = "height:"+height+"px; width:100%; overflow:hidden;";
		return node;
	},
	/*
		Methods get coordinatest of visible area and checks that all related items are rendered
		If, during rendering, some not-loaded items was detected - extra data loading is initiated.
		reset - flag, which forces clearing of previously rendered elements
	*/
	_render_visible_rows:function(e,reset){
		this.data._unrendered_area=[]; //clear results of previous calls
		
		var viewport = this._getVisibleRange();	//details of visible view
		if (!this._dataobj.firstChild || reset){	//create initial placeholder - for all view space
			this._dataobj.innerHTML="";
			this._dataobj.appendChild(this._create_placeholder(viewport._max));
			//register placeholder in collection
			this._htmlrows = [this._dataobj.firstChild];
		}
		
		/*
			virtual rendering breaks all view on rows, because we know widht of item
			we can calculate how much items can be placed on single row, and knowledge 
			of that, allows to calculate count of such rows
			
			each time after scrolling, code iterate through visible rows and render items 
			in them, if they are not rendered yet
			
			both rendered rows and placeholders are registered in _htmlrows collection
		*/

		//position of first visible row
		var t = Math.max(viewport._from, 0);
		//max can be 0, in case of 1 row per page
		var max_row = (this.data.max || this.data.max === 0)?this.data.max:Infinity;
		
		while(t<=viewport._height){	//loop for all visible rows
			//skip already rendered rows
			while(this._htmlrows[t] && this._htmlrows[t]._filled && t<=viewport._height){
				t++; 
			}
			//go out if all is rendered
			if (t>viewport._height) break;
			
			//locate nearest placeholder
			var holder = t;
			while (!this._htmlrows[holder]) holder--;
			var holder_row = this._htmlrows[holder];
			
			//render elements in the row			
			var base = t*viewport._dx+(this.data.min||0);	//index of rendered item
			if (base > max_row) break;	//check that row is in virtual bounds, defined by paging
			var nextpoint =  Math.min(base+viewport._dx-1,max_row);
			var node = this._create_placeholder(viewport._y);
			//all items in rendered row
			var range = this.data.getIndexRange(base, nextpoint);
			if (!range.length) break; 
			
			node.innerHTML=range.map(this._toHTML,this).join(""); 	//actual rendering
			for (var i=0; i < range.length; i++)					//register all new elements for later usage in _locateHTML
				this._htmlmap[this.data.idByIndex(base+i)]=node.childNodes[i];
			
			//correct placeholders
			var h = parseInt(holder_row.style.height,10);
			var delta = (t-holder)*viewport._y;
			var delta2 = (h-delta-viewport._y);
			
			//add new row to the DOOM
			dhtmlx.html.insertBefore(node,delta?holder_row.nextSibling:holder_row,this._dataobj);
			this._htmlrows[t]=node;
			node._filled = true;
			
			/*
				if new row is at start of placeholder - decrease placeholder's height
				else if new row takes whole placeholder - remove placeholder from DOM
				else 
					we are inserting row in the middle of existing placeholder
					decrease height of existing one, and add one more, 
					before the newly added row
			*/
			if (delta <= 0 && delta2>0){
				holder_row.style.height = delta2+"px";
				this._htmlrows[t+1] = holder_row;
			} else {
				if (delta<0)
					dhtmlx.html.remove(holder_row);
				else
					holder_row.style.height = delta+"px";
				if (delta2>0){ 
					var new_space = this._htmlrows[t+1] = this._create_placeholder(delta2);
					dhtmlx.html.insertBefore(new_space,node.nextSibling,this._dataobj);
				}
			}
			
			
			t++;
		}
		//when all done, check for non-loaded items
		if (this.data._unrendered_area.length){
			//we have some data to load
			//detect borders
			var from = this.indexById(this.data._unrendered_area[0]);
			var to = this.indexById(this.data._unrendered_area.pop())+1;
			if (to>from){
				//initiate data loading
				if (!this.callEvent("onDataRequest",[from, to-from])) return false;
				dhtmlx.assert(this.data.feed,"Data feed is missed");
				this.data.feed.call(this,from,to-from);
			}
		}
		if (dhtmlx._isIE){
				var viewport2 = this._getVisibleRange();
				if (viewport2._from != viewport._from)
					this._render_visible_rows();
		}
	},
	//calculates visible view
	_getVisibleRange:function(){
		var scrfix = dhtmlx.$customScroll ? 0 : 18;
		var top = this._dataobj.scrollTop;
		var width = this._dataobj.scrollWidth; 	// opera returns zero scrollwidth for the empty object
		var height = this._dataobj.offsetHeight;									// 18 - scroll
		//size of single item
		var t = this.type;
		var dim = this._getDimension();

		var dx = Math.floor(width/dim.x)||1; //at least single item per row
		
		var min = Math.floor(top/dim.y);				//index of first visible row
		var dy = Math.ceil((height+top)/dim.y)-1;		//index of last visible row
		//total count of items, paging can affect this math
		var count = this.data.max?(this.data.max-this.data.min):this.data.dataCount();
		var max = Math.ceil(count/dx)*dim.y;			//size of view in rows
		
		return { _from:min, _height:dy, _top:top, _max:max, _y:dim.y, _dx:dx};
	}
};



/* DHX INITIAL FILE 'C:\http\legacy/dhtmlxCore/sources//dataview.js'*/


/*
	UI:DataView
*/

/*DHX:Depend dataview.css*/
/*DHX:Depend types*/
/*DHX:Depend ../imgs/dataview*/

/*DHX:Depend compatibility_layout.js*/
/*DHX:Depend compatibility_drag.js*/

/*DHX:Depend datastore.js*/
/*DHX:Depend load.js*/ 		/*DHX:Depend virtual_render.js*/ 		/*DHX:Depend selection.js*/
/*DHX:Depend mouse.js*/ 	/*DHX:Depend key.js*/ 					/*DHX:Depend edit.js*/ 
/*DHX:Depend drag.js*/		/*DHX:Depend dataprocessor_hook.js*/ 	/*DHX:Depend autotooltip.js*/ 
/*DHX:Depend pager.js*/		/*DHX:Depend destructor.js*/			/*DHX:Depend dhtmlx.js*/
/*DHX:Depend config.js*/




//container - can be a HTML container or it's ID
dhtmlXDataView = function(container){
	//next data is only for debug purposes
	this.name = "DataView";	//name of component
	
	if (dhtmlx.assert_enabled()) this._assert();

	//enable configuration
	dhtmlx.extend(this, dhtmlx.Settings);
	this._parseContainer(container,"dhx_dataview");	//assigns parent container
	
	
	
	//behaviors
	dhtmlx.extend(this, dhtmlx.AtomDataLoader);
	dhtmlx.extend(this, dhtmlx.DataLoader);	//includes creation of DataStore
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
	
	//in case of auto-height we use plain rendering
	if (this._settings.height!="auto"&&!this._settings.renderAll)
		dhtmlx.extend(this, dhtmlx.VirtualRenderStack);	//extends RenderStack behavior
	
	//map API of DataStore on self
	this.data.provideApi(this,true);

	if (this.config.autowidth){
		this.attachEvent("onBeforeRender", function(){
			this.type.width = Math.floor((this._dataobj.scrollWidth) / (this.config.autowidth*1 || 1))-this.type.padding*2 - this.type.margin*2 - this.type.border*2;
			this.type._item_start = dhtmlx.Template.fromHTML(this.template_item_start(this.type));
			this.type._item_end = this.template_item_end(this.type);
		});
		dhtmlx.event(window, "resize", function(){
			this.refresh();
		}, this)
	}

	if (dhtmlx.$customScroll)
		dhtmlx.CustomScroll.enable(this);
};
dhtmlXDataView.prototype={
	bind:function(){
		dhtmlx.BaseBind.legacyBind.apply(this, arguments);
	},
	sync:function(){
		dhtmlx.BaseBind.legacySync.apply(this, arguments);
	},
	/*
		Called each time when dragIn or dragOut situation occurs
		context - drag context object
		ev - native event
	*/
	dragMarker:function(context,ev){
		//get HTML element by item ID
		//can be null - when item is not rendered yet
		var el = this._locateHTML(context.target);
		
		//ficon and some other types share common bg marker
		if (this.type.drag_marker){
			if (this._drag_marker){
				//clear old drag marker position
				this._drag_marker.style.backgroundImage="";
				this._drag_marker.style.backgroundRepeat="";
			}
			
			// if item already rendered
			if (el) {
				//show drag marker
				el.style.backgroundImage="url("+(dhtmlx.image_path||"")+this.type.drag_marker+")";
				el.style.backgroundRepeat="no-repeat";
				this._drag_marker = el;
			}
		}
		
		//auto-scroll during d-n-d, only if related option is enabled
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
		dhx_dataview_item:function(e,id){ 
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
		dhx_dataview_item:function(e,id){ 
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
			template_edit:dhtmlx.Template.fromHTML("<div style='padding:10px; white-space:nowrap; overflow:hidden;'><textarea style='width:100%; height:100%;' bind='obj.text'></textarea></div>"),
			//in case of dyn. loading - temporary spacer
			template_loading:dhtmlx.Template.fromHTML("<div style='padding:10px; white-space:nowrap; overflow:hidden;'>Loading...</div>"),
			width:210,
			height:115,
			margin:0,
			padding:10,
			border:1
		}
	},
	template_item_start:dhtmlx.Template.fromHTML("<div dhx_f_id='{-obj.id}' class='dhx_dataview_item dhx_dataview_{obj.css}_item{-obj.$selected?_selected:}' style='width:{obj.width}px; height:{obj.height}px; padding:{obj.padding}px; margin:{obj.margin}px; float:left; overflow:hidden;'>"),
	template_item_end:dhtmlx.Template.fromHTML("</div>")
};

dhtmlx.compat("layout");
if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype.attachDataView = function(conf) {
		
		this.callEvent("_onBeforeContentAttach",["dataview"]);
		
		var obj = document.createElement("DIV");
		obj.style.width = "100%";
		obj.style.height = "100%";
		obj.style.position = "relative";
		obj.style.overflow = "hidden";
		this._attachObject(obj);
		
		if (typeof(conf) == "undefined") conf = {};
		obj.id = "DataViewObject_"+new Date().getTime();
		conf.container = obj.id;
		conf.skin = this.conf.skin;
		
		
		this.dataType = "dataview";
		this.dataObj = new dhtmlXDataView(conf);
		
		
		this.dataObj.setSizes = function(){
			this.render();
		}
		
		obj = null;
		
		this.callEvent("_onContentAttach",[]);
		
		return this.dataObj;
	};
	
}

