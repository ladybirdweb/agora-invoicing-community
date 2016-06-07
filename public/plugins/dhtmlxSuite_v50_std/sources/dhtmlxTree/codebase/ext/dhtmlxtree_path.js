/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXTreeObject.prototype.addPath=function(sid,tid,style,id){ 
	//sid on top, tid in deep
	this.activatePaths();
	style=style||{};
	var path=[];
	var to=null;
	var from=this._idpull[tid];
	var end=this._idpull[sid];
	while (end!=to){
		path.push({open:this._getOpenState(from),
				from:from.id,
				size:(to?this._getIndex(to):0),
				to:(to?to.id:null),
				style:"border-left:"+(style.width||1)+"px "+(style.mode||"solid")+" "+(style.color||"red")+"; border-bottom:"+(style.width||1)+"px "+(style.mode||"solid")+" "+(style.color||"red")+";"})
		to=from;
		from=from.parentObject;
	}
		
	while (!id || this._pathspull[id]) id=(id||0)+1;
	
	this._pathspull[id]={path:path,id:id};
	this._paths.push(this._pathspull[id]);
	this._renderPath(this._pathspull[id])
};
dhtmlXTreeObject.prototype.activatePaths=function(height){
	var that=this;
	this.attachEvent("onOpenEnd",function(){ 
		for (var i=0; i<that._paths.length; i++){
			that._clearPath(that._paths[i])
			that._renderPath(that._paths[i]);
		}
		});
	this.attachEvent("onXLE",function(xml){
		var ends=dhx4.ajax.xpath("//pathend", xml);
		var starts=dhx4.ajax.xpath("//pathstart", xml);
		var stpull={};
		for (var i=0; i<starts.length; i++)
			stpull[starts[i].getAttribute("id")]=starts[i];
			
		for (var i=0; i<starts.length; i++){
			var end=ends[i].parentNode;
			var start=stpull[ends[i].getAttribute("id")];
			this.addPath(start.parentNode.getAttribute("id"),end.getAttribute("id"),{color:start.getAttribute("color"),mode:start.getAttribute("mode"),width:start.getAttribute("width")},start.getAttribute("id"));
		}
		
	});

	if (height) this._halfHeight=height;
	else if (this._idpull[0].childsCount)
		this._halfHeight=Math.floor(this._idpull[0].childNodes[0].span.parentNode.offsetHeight/2);
		
	if (!this._halfHeight)
		this._halfHeight=9;
		
	this.activatePaths=function(){}
}
dhtmlXTreeObject.prototype._clearPath=function(obj){
	for (var i=obj.path.length-1; i>0; i--){
		var t=obj.path[i];
		if (t._html)
			t._html.parentNode.removeChild(t._html);
		t._html=null;
	}
}
dhtmlXTreeObject.prototype._renderPath=function(obj){
	var c=this._idpull[obj.path[obj.path.length-1].from].span.parentNode.parentNode;
	var top=(_isIE?9:8)+this._halfHeight;	
	
	var left=(_isIE?27:27);
	while(c.offsetParent!=this.allTree){
		top+=c.offsetTop;
		left+=c.offsetLeft;
		c=c.offsetParent;
	}
	
	
	for (var i=obj.path.length-1; i>0; i--){
		var t=obj.path[i];
		var d=document.createElement("div");
		if (!this._idpull[t.to].tr.offsetHeight) return;
		var pos=this._idpull[t.to].tr.offsetTop;
		d.style.cssText='position:absolute; z-index:1; width:'+(_isIE?10:8)+'px; height:'+(pos-9)+'px; left:'+left+'px; top:'+top+'px;'+t.style;
		top+=pos;
		left+=18;
		this.allTree.appendChild(d);
		t._html=d;
		
	}
}
dhtmlXTreeObject.prototype.deletePath=function(id){
	var z=this._pathspull[id];
	if (z){
		this._clearPath(z);
		delete this._pathspull[id];
		for (var i=0; i<this._paths.length; i++)
			if (this._paths[i]==z)
				return this._paths.splice(i,1);
	}	
};

dhtmlXTreeObject.prototype.deleteAllPaths=function(id){
	for (var i=this._paths.length-1; i>=0; i--)
		this.deletePath(this._paths[i].id);
};

dhtmlXTreeObject.prototype._paths=[];
dhtmlXTreeObject.prototype._pathspull={};
//(c)dhtmlx ltd. www.dhtmlx.com
