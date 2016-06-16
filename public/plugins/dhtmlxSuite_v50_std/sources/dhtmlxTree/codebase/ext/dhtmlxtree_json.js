/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function jsonPointer(data,parent){
	this.d=data;
	this.dp=parent;
}
jsonPointer.prototype={
	text:function(){ var afff=function(n){ var p=[]; for(var i=0; i<n.length; i++) p.push("{"+sfff(n[i])+"}"); return p.join(","); }; var sfff=function(n){ var p=[]; for (var a in n) if (typeof(n[a])=="object"){ if (a.length) p.push('"'+a+'":['+afff(n[a])+"]");  else p.push('"'+a+'":{'+sfff(n[a])+"}"); }else p.push('"'+a+'":"'+n[a]+'"'); return p.join(","); }; return "{"+sfff(this.d)+"}"; },
	get:function(name){return this.d[name]; },
	exists:function(){return !!this.d },
	content:function(){return this.d.content; },
	each:function(name,f,t){  var a=this.d[name]; var c=new jsonPointer(); if (a) for (var i=0; i<a.length; i++) { c.d=a[i]; f.apply(t,[c,i]); } },
	get_all:function(){ return this.d; },
	sub:function(name){ return new jsonPointer(this.d[name],this.d) },
	sub_exists:function(name){ return !!this.d[name]; },
	each_x:function(name,rule,f,t,i){  var a=this.d[name]; var c=new jsonPointer(0,this.d); if (a) for (i=i||0; i<a.length; i++) if (a[i][rule]) { c.d=a[i]; if(f.apply(t,[c,i])==-1) return; } },
	up:function(name){ return new jsonPointer(this.dp,this.d);  },
	set:function(name,val){ this.d[name]=val;  },
	clone:function(name){ return new jsonPointer(this.d,this.dp); },
	through:function(name,rule,v,f,t){  var a=this.d[name]; if (a.length) for (var i=0; i<a.length; i++) { if (a[i][rule]!=null && a[i][rule]!="" &&  (!v || a[i][rule]==v )) { 
		var c=new jsonPointer(a[i],this.d);  f.apply(t,[c,i]); }  var w=this.d; this.d=a[i]; 
		if (this.sub_exists(name)) this.through(name,rule,v,f,t); this.d=w;   } }
}

/**
*     @desc: load tree from js array file|stream
*     @type: public
*     @param: file - link to JSArray file
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
dhtmlXTreeObject.prototype.loadJSArrayFile=function(file,callback){
  if (window.console && window.console.info)
    window.console.info("loadJSArrayFile was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
	return this._loadJSArrayFile(file, callback);
};
   dhtmlXTreeObject.prototype._loadJSArrayFile=function(file,callback){
      if (!this.parsCount) this.callEvent("onXLS",[this,this._ld_id]); this._ld_id=null; this.xmlstate=1;
      var that=this;
      
      this.XMLLoader=function(xml, callback){
      	eval("var z="+xml.responseText);
      	this._loadJSArray(z);
      	if (callback) callback.call(this, xml);
      };

      dhx4.ajax.get(file, function(obj){
      	that.XMLLoader(obj.xmlDoc, callback);
      });
   };
   
/**
*     @desc: load tree from csv file|stream
*     @type: public
*     @param: file - link to CSV file
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
dhtmlXTreeObject.prototype.loadCSV=function(file,callback){
  if (window.console && window.console.info)
    window.console.info("loadCSV was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
	return this._loadCSV(file, callback);
};
   dhtmlXTreeObject.prototype._loadCSV=function(file,callback){
      if (!this.parsCount) this.callEvent("onXLS",[this,this._ld_id]); this._ld_id=null; this.xmlstate=1;
      var that=this;
	  this.XMLLoader=function(xml, callback){
      	this._loadCSVString(xml.responseText);
      	if (callback) callback.call(this, xml);
      };

      dhx4.ajax.get(file, function(obj){
      	that.XMLLoader(obj.xmlDoc, callback);
      });
   };
   
/**
*     @desc: load tree from js array object
*     @type: public
*     @param: ar - js array
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/  
dhtmlXTreeObject.prototype.loadJSArray=function(file,callback){
  if (window.console && window.console.info)
    window.console.info("loadJSArray was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
	return this._loadJSArray(file, callback);
};
dhtmlXTreeObject.prototype._loadJSArray=function(ar,afterCall){

	//array id,parentid,text
	var z=[];
	for (var i=0; i<ar.length; i++){
		if (!z[ar[i][1]]) z[ar[i][1]]=[];
		z[ar[i][1]].push({id:ar[i][0],text:ar[i][2]});
	}
	
	var top={id: this.rootId};
	var f=function(top,f){
		if (z[top.id]){
			top.item=z[top.id];
			for (var j=0; j<top.item.length; j++)
				f(top.item[j],f);
		}
	}
	f(top,f);
	this._loadJSONObject(top,afterCall);
}


/**
*     @desc: load tree from csv string
*     @type: public
*     @param: csv - csv string 
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
dhtmlXTreeObject.prototype.loadCSVString=function(file,callback){
  if (window.console && window.console.info)
    window.console.info("loadCSVString was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
	return this._loadCSVString(file, callback);
};
dhtmlXTreeObject.prototype._loadCSVString=function(csv,afterCall){
	//array id,parentid,text
	var z=[];
	var ar=csv.split("\n");
	for (var i=0; i<ar.length; i++){
		var t=ar[i].split(",");
		if (!z[t[1]]) z[t[1]]=[];
		z[t[1]].push({id:t[0],text:t[2]});
	}
	
	var top={id: this.rootId};
	var f=function(top,f){
		if (z[top.id]){
			top.item=z[top.id];
			for (var j=0; j<top.item.length; j++)
				f(top.item[j],f);
		}
	}
	f(top,f);
	this._loadJSONObject(top,afterCall);
}


/**
*     @desc: load tree from json object
*     @type: public
*     @param: json - json object
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.loadJSONObject=function(file,callback){
	  if (window.console && window.console.info)
        window.console.info("loadJSONObject was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
    	return this._loadJSONObject(file, callback);
   };
   dhtmlXTreeObject.prototype._loadJSONObject=function(json,afterCall){
      if (!this.parsCount) this.callEvent("onXLS",[this,null]);this.xmlstate=1;
      var p=new jsonPointer(json);
	  this._parse(p);
	  this._p=p;
      if (afterCall) afterCall();
   };
   

/**   
*     @desc: load tree from json file
*     @type: public
*     @param: file - link to JSON file
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.loadJSON=function(file,callback){
	  if (window.console && window.console.info)
        window.console.info("loadJSON was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
    	return this._loadJSON(file, callback);
   };
   dhtmlXTreeObject.prototype._loadJSON=function(file,callback){
	  if (!this.parsCount) this.callEvent("onXLS",[this,this._ld_id]); this._ld_id=null; this.xmlstate=1;
      var that=this;
      
      this.XMLLoader=function(xml, callback){
      	try {
			eval("var t="+xml.responseText);
		} catch(e){
				dhx4.callEvent("onLoadXMLerror",["Incorrect JSON",
					(xml),
					this
				]);
				return;
		}
      	var p=new jsonPointer(t);
      	this._parse(p);
      	this._p=p;
      	if (callback) callback.call(this, xml);
      };
      
      dhx4.ajax.get(file, function(obj){
      	that.XMLLoader(obj.xmlDoc, callback);
      });
   };   
   
   
/**   
*     @desc: return tree as json string
*     @type: public
*     @topic: 0
*/
dhtmlXTreeObject.prototype.serializeTreeToJSON=function(){
	var out=['{"id":"'+this.rootId+'", "item":['];
	var p=[];
		for (var i=0; i<this.htmlNode.childsCount; i++)
			p.push(this._serializeItemJSON(this.htmlNode.childNodes[i]));
	out.push(p.join(","));
	out.push("]}");	
	return out.join("");
};
dhtmlXTreeObject.prototype._serializeItemJSON=function(itemNode){
	var out=[];
	if (itemNode.unParsed)
			return (itemNode.unParsed.text());
  
	if (this._selected.length)
		var lid=this._selected[0].id;
	else lid="";
    var text=itemNode.span.innerHTML;

	text=text.replace(/\"/g, "\\\"", text);

	if (!this._xfullXML)
		out.push('{ "id":"'+itemNode.id+'", '+(this._getOpenState(itemNode)==1?' "open":"1", ':'')+(lid==itemNode.id?' "select":"1",':'')+' "text":"'+text+'"'+( ((this.XMLsource)&&(itemNode.XMLload==0))?', "child":"1" ':''));
	else
		out.push('{ "id":"'+itemNode.id+'", '+(this._getOpenState(itemNode)==1?' "open":"1", ':'')+(lid==itemNode.id?' "select":"1",':'')+' "text":"'+text+'", "im0":"'+itemNode.images[0]+'", "im1":"'+itemNode.images[1]+'", "im2":"'+itemNode.images[2]+'" '+(itemNode.acolor?(', "aCol":"'+itemNode.acolor+'" '):'')+(itemNode.scolor?(', "sCol":"'+itemNode.scolor+'" '):'')+(itemNode.checkstate==1?', "checked":"1" ':(itemNode.checkstate==2?', "checked":"-1"':''))+(itemNode.closeable?', "closeable":"1" ':'')+( ((this.XMLsource)&&(itemNode.XMLload==0))?', "child":"1" ':''));

	if ((this._xuserData)&&(itemNode._userdatalist))
		{
			out.push(', "userdata":[');
			var names=itemNode._userdatalist.split(",");
			var p=[];
			for  (var i=0; i<names.length; i++)
				p.push('{ "name":"'+names[i]+'" , "content":"'+itemNode.userData["t_"+names[i]]+'" }');
			out.push(p.join(",")); out.push("]");
		}
		
		if (itemNode.childsCount){
			out.push(', "item":[');
			var p=[];
		for (var i=0; i<itemNode.childsCount; i++)
			p.push(this._serializeItemJSON(itemNode.childNodes[i]));
			out.push(p.join(","));
			out.push("]\n");
		}
			
		out.push("}\n")
	return out.join("");
}   
//(c)dhtmlx ltd. www.dhtmlx.com
