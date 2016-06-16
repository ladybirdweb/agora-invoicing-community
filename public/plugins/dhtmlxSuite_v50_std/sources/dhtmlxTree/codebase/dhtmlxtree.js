/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/*_TOPICS_
@0:Initialization
@1:Selection control
@2:Add/delete
@3:Private
@4:Node/level control
@5:Checkboxes/user data manipulation
@6:Appearence control
@7: Handlers
*/

function xmlPointer(data){
	this.d=data;
}
xmlPointer.prototype={
	text:function(){ if (!_isFF) return this.d.xml; var x = new XMLSerializer();   return x.serializeToString(this.d); },
	get:function(name){return this.d.getAttribute(name); },
	exists:function(){return !!this.d },
	content:function(){return this.d.firstChild?(this.d.firstChild.wholeText||this.d.firstChild.data):""; }, // <4k in FF
	each:function(name,f,t,i){  var a=this.d.childNodes; var c=new xmlPointer(); if (a.length) for (i=i||0; i<a.length; i++) if (a[i].tagName==name) { c.d=a[i]; if(f.apply(t,[c,i])==-1) return; } },
	get_all:function(){ var a={}; var b=this.d.attributes; for (var i=0; i<b.length; i++) a[b[i].name]=b[i].value; return a; },
	sub:function(name){ var a=this.d.childNodes; var c=new xmlPointer(); if (a.length) for (var i=0; i<a.length; i++) if (a[i].tagName==name) { c.d=a[i]; return c; } },
	up:function(name){ return new xmlPointer(this.d.parentNode);  },
	set:function(name,val){ this.d.setAttribute(name,val);  },
	clone:function(name){ return new xmlPointer(this.d); },
	sub_exists:function(name){ var a=this.d.childNodes; if (a.length) for (var i=0; i<a.length; i++) if (a[i].tagName==name) return true;  return false;  },
	through:function(name,rule,v,f,t){  var a=this.d.childNodes; if (a.length) for (var i=0; i<a.length; i++) { if (a[i].tagName==name && a[i].getAttribute(rule)!=null && a[i].getAttribute(rule)!="" &&  (!v || a[i].getAttribute(rule)==v )) { var c=new xmlPointer(a[i]);  f.apply(t,[c,i]); } var w=this.d; this.d=a[i]; this.through(name,rule,v,f,t); this.d=w;  } }
}



/**
*     @desc: tree constructor
*     @param: htmlObject - parent html object or id of parent html object
*     @param: width - tree width
*     @param: height - tree height
*     @param: rootId - id of virtual root node (same as tree node id attribute in xml)
*     @type: public
*     @topic: 0
*/
function dhtmlXTreeObject(htmlObject, width, height, rootId){
  if (dhtmlxEvent.initTouch)
    dhtmlxEvent.initTouch();

	if (_isIE) try { document.execCommand("BackgroundImageCache", false, true); } catch (e){}
	if (typeof(htmlObject)!="object")
      this.parentObject=document.getElementById(htmlObject);
	else
      this.parentObject=htmlObject;

	this.parentObject.style.overflow="hidden";
   	this._itim_dg=true;
    this.dlmtr=",";
    this.dropLower=false;
	  this.enableIEImageFix(true);

   this.xmlstate=0;
   this.mytype="tree";
   this.smcheck=true;   //smart checkboxes
   this.width=width;
   this.height=height;
   this.rootId=rootId;
   this.childCalc=null;
      this.def_img_x="18px";
      this.def_img_y="18px";
      this.def_line_img_x="18px";
      this.def_line_img_y="24px";

    this._dragged=new Array();
   this._selected=new Array();

   this.style_pointer="pointer";
   
   this._aimgs=true;
   this.htmlcA=" [";
   this.htmlcB="]";
   this.lWin=window;
   this.cMenu=0;
   this.mlitems=0;
   this.iconURL="";
   this.dadmode=0;
   this.slowParse=false;
   this.autoScroll=true;
   this.hfMode=0;
   this.nodeCut=new Array();
   this.XMLsource=0;
   this.XMLloadingWarning=0;
   this._idpull={};
   this._pullSize=0;
   this.treeLinesOn=true;
   this.tscheck=false;
   this.timgen=true;
   this.dpcpy=false;
   this._ld_id=null;
   this._dynDeleteBranches={};
	this._oie_onXLE=[];
   this.imPath=window.dhx_globalImgPath||""; 
   this.checkArray=new Array("iconUncheckAll.gif","iconCheckAll.gif","iconCheckGray.gif","iconUncheckDis.gif","iconCheckDis.gif","iconCheckDis.gif");
   this.radioArray=new Array("radio_off.gif","radio_on.gif","radio_on.gif","radio_off.gif","radio_on.gif","radio_on.gif");

   this.lineArray=new Array("line2.gif","line3.gif","line4.gif","blank.gif","blank.gif","line1.gif");
   this.minusArray=new Array("minus2.gif","minus3.gif","minus4.gif","minus.gif","minus5.gif");
   this.plusArray=new Array("plus2.gif","plus3.gif","plus4.gif","plus.gif","plus5.gif");
   this.imageArray=new Array("leaf.gif","folderOpen.gif","folderClosed.gif");
   this.cutImg= new Array(0,0,0);
   this.cutImage="but_cut.gif";
   
   dhx4._eventable(this);

   this.dragger= new dhtmlDragAndDropObject();
//create root
   this.htmlNode=new dhtmlXTreeItemObject(this.rootId,"",0,this);
   this.htmlNode.htmlNode.childNodes[0].childNodes[0].style.display="none";
   this.htmlNode.htmlNode.childNodes[0].childNodes[0].childNodes[0].className="hiddenRow";
//init tree structures
   this.allTree=this._createSelf();
   this.allTree.appendChild(this.htmlNode.htmlNode);

   if (dhtmlx.$customScroll)
      dhtmlx.CustomScroll.enable(this);

    if(_isFF){
         this.allTree.childNodes[0].width="100%";
         this.allTree.childNodes[0].style.overflow="hidden";
    }

   var self=this;
   this.allTree.onselectstart=new Function("return false;");
   if (_isMacOS)
		this.allTree.oncontextmenu = function(e){ 
			return self._doContClick(e||window.event, true); 
		};   
   this.allTree.onmousedown = function(e){ return self._doContClick(e||window.event); };  
   
   this.XMLLoader=this._parseXMLTree;
   if (_isIE) this.preventIECashing(true);

//#__pro_feature:01112006{
//#complex_move:01112006{
   this.selectionBar=document.createElement("DIV");
   this.selectionBar.className="selectionBar";
   this.selectionBar.innerHTML="&nbsp;";
   this.selectionBar.style.display="none";
   this.allTree.appendChild(this.selectionBar);
//#}
//#}

    
    if (window.addEventListener) window.addEventListener("unload",function(){try{  self.destructor(); } catch(e){}},false);
    if (window.attachEvent) window.attachEvent("onunload",function(){ try{ self.destructor(); } catch(e){}});

	this.setImagesPath=this.setImagePath;
	this.setIconsPath=this.setIconPath;

	this.setSkin(window.dhx4.skin||(typeof(dhtmlx)!="undefined"?dhtmlx.skin:null)||window.dhx4.skinDetect("dhxtree")||"material");
	if (dhtmlx.image_path) {
		var path = dhtmlx.image_path;
		var sk = this.parentObject.className.match(/dhxtree_dhx_([a-z_]*)/i);
		if (sk != null && sk[1] != null) path += "dhxtree_"+sk[1]+"/";
		this.setImagePath(path);
	}

   return this;
};


/**
*     @desc: set default data transfer mode 
*     @param: mode - data mode (json,xml,csv)
*     @type: public
*     @topic: 0
*/
dhtmlXTreeObject.prototype.setDataMode=function(mode){
		this._datamode=mode;
}


	
dhtmlXTreeObject.prototype._doContClick=function(ev, force){
	if (!force && ev.button!=2) {
		if(this._acMenu){
			if (this._acMenu.hideContextMenu)
				this._acMenu.hideContextMenu()
			else
				this.cMenu._contextEnd();
		}
		return true;
	}
	
 	

	
	var el=(_isIE?ev.srcElement:ev.target);
	while ((el)&&(el.tagName!="BODY")) {
		if (el.parentObject) break;
    	 el=el.parentNode;
	 }
    	
    if ((!el)||(!el.parentObject)) return true;
    
    var obj=el.parentObject;
    
    if (!this.callEvent("onRightClick",[obj.id,ev]))
        (ev.srcElement||ev.target).oncontextmenu = function(e){ (e||event).cancelBubble=true; return false; };
        
    	this._acMenu=(obj.cMenu||this.cMenu);
        if (this._acMenu){
       		if (!(this.callEvent("onBeforeContextMenu", [
					obj.id
				]))) return true; 	
				if(!_isMacOS)
	        (ev.srcElement||ev.target).oncontextmenu = function(e){ (e||event).cancelBubble=true; return false; };
	               
			if (this._acMenu.showContextMenu){

var dEl0=window.document.documentElement;
var dEl1=window.document.body;
var corrector = new Array((dEl0.scrollLeft||dEl1.scrollLeft),(dEl0.scrollTop||dEl1.scrollTop));
if (_isIE){
	var x= ev.clientX+corrector[0];
	var y = ev.clientY+corrector[1];
} else {
	var x= ev.pageX;
	var y = ev.pageY;
}
				
				this._acMenu.showContextMenu(x-1,y-1)
				this.contextID=obj.id;
				ev.cancelBubble=true;
				this._acMenu._skip_hide=true;
			} else {
				el.contextMenuId=obj.id;
				el.contextMenu=this._acMenu;
				el.a=this._acMenu._contextStart;
				el.a(el, ev);
				el.a=null;
			}
	        	
			return false;           
    	}
    return true;
}


/**
*     @desc: replace IMG tag with background images - solve problem with IE image caching , not works for IE6 SP1
*     @param: mode - true/false - enable/disable fix
*     @type: public
*     @topic: 0
*/
dhtmlXTreeObject.prototype.enableIEImageFix=function(mode){
	if (!mode){

	this._getImg=function(id){ return document.createElement((id==this.rootId)?"div":"img"); }
	//this._setSrc=function(a,b){ a.src=b; }
	this._setSrc=function(a,b){ a.src=b; }
	this._getSrc=function(a){ return a.src; }
	}	else	{

	this._getImg=function(){ var z=document.createElement("DIV"); z.innerHTML="&nbsp;"; z.className="dhx_bg_img_fix"; return z; }
	this._setSrc=function(a,b){ a.style.backgroundImage="url("+b+")"; }
	this._getSrc=function(a){ var z=a.style.backgroundImage;  return z.substr(4,z.length-5).replace(/(^")|("$)/g,""); }
	}
}

/**
*	@desc: deletes tree and clears memory
*	@type: public
*/
dhtmlXTreeObject.prototype.destructor=function(){
    for (var a in this._idpull){
        var z=this._idpull[a];
		if (!z) continue;
        z.parentObject=null;z.treeNod=null;z.childNodes=null;z.span=null;z.tr.nodem=null;z.tr=null;z.htmlNode.objBelong=null;z.htmlNode=null;
        this._idpull[a]=null;
        }
    this.parentObject.innerHTML="";
    
    this.allTree.onselectstart = null;
    this.allTree.oncontextmenu = null;
    this.allTree.onmousedown = null;
        
    for(var a in this){
        this[a]=null;
        }
}

function cObject(){
    return this;
}
cObject.prototype= new Object;
cObject.prototype.clone = function () {
       function _dummy(){};
       _dummy.prototype=this;
       return new _dummy();
    }

/**
*   @desc: tree node constructor
*   @param: itemId - node id
*   @param: itemText - node label
*   @param: parentObject - parent item object
*   @param: treeObject - tree object
*   @param: actionHandler - onclick event handler(optional)
*   @param: mode - do not show images
*   @type: private
*   @topic: 0
*/
function dhtmlXTreeItemObject(itemId,itemText,parentObject,treeObject,actionHandler,mode){
   this.htmlNode="";
   this.acolor="";
   this.scolor="";
   this.tr=0;
   this.childsCount=0;
   this.tempDOMM=0;
   this.tempDOMU=0;
   this.dragSpan=0;
   this.dragMove=0;
   this.span=0;
   this.closeble=1;
   this.childNodes=new Array();
   this.userData=new cObject();


   this.checkstate=0;
   this.treeNod=treeObject;
   this.label=itemText;
   this.parentObject=parentObject;
   this.actionHandler=actionHandler;
   this.images=new Array(treeObject.imageArray[0],treeObject.imageArray[1],treeObject.imageArray[2]);


   this.id=treeObject._globalIdStorageAdd(itemId,this);
   if (this.treeNod.checkBoxOff ) this.htmlNode=this.treeNod._createItem(1,this,mode);
   else  this.htmlNode=this.treeNod._createItem(0,this,mode);

   this.htmlNode.objBelong=this;
   return this;
   };   


/**
*     @desc: register node
*     @type: private
*     @param: itemId - node id
*     @param: itemObject - node object
*     @topic: 3  
*/
   dhtmlXTreeObject.prototype._globalIdStorageAdd=function(itemId,itemObject){
      if (this._globalIdStorageFind(itemId,1,1)) {   itemId=itemId +"_"+(new Date()).valueOf(); return this._globalIdStorageAdd(itemId,itemObject); }
	  	 this._idpull[itemId]=itemObject;
         this._pullSize++;
      return itemId;
   };

/**
*     @desc: unregister node
*     @type: private
*     @param: itemId - node id
*     @topic: 3
*/
   dhtmlXTreeObject.prototype._globalIdStorageSub=function(itemId){
        if (this._idpull[itemId]){
		    this._unselectItem(this._idpull[itemId]);
			this._idpull[itemId]=null;
			this._pullSize--;
        }
		if ((this._locker)&&(this._locker[itemId])) this._locker[itemId]=false;
   };
   
/**
*     @desc: return node object
*     @param: itemId - node id
*     @type: private
*     @topic: 3
*/
   dhtmlXTreeObject.prototype._globalIdStorageFind=function(itemId,skipXMLSearch,skipParsing,isreparse){
		var z=this._idpull[itemId];
        if (z){
//#__pro_feature:01112006{
//#smart_parsing:01112006{
            if ((z.unParsed)&&(!skipParsing))
                    {
                    this.reParse(z,0);
                    }
            if (this._srnd && !z.htmlNode) this._buildSRND(z,skipParsing);
                if ((isreparse)&&(this._edsbpsA)){
                    for (var j=0; j<this._edsbpsA.length; j++)
                        if (this._edsbpsA[j][2]==itemId){
                            dhx4.callEvent("ongetItemError",["Requested item still in parsing process.",itemId]);
                            return null;
                        }
                    }
//#}
//#}
            return z;
            }
//#__pro_feature:01112006{
//#smart_parsing:01112006{
      if ((this.slowParse)&&(itemId!=0)&&(!skipXMLSearch)) return this.preParse(itemId);
      else
//#}
//#}
	  	return null;
   };
//#__pro_feature:01112006{
//#smart_parsing:01112006{
dhtmlXTreeObject.prototype._getSubItemsXML=function(p){
      var z=[];
      p.each("item",function(c){
      	z.push(c.get("id"));
      },this)
      return z.join(this.dlmtr);
    }

/**
*     @desc: enable/disable smart XML parsing mode (usefull for big, well structured XML)
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableSmartXMLParsing=function(mode) { this.slowParse=dhx4.s2b(mode); };
   dhtmlXTreeObject.prototype.findXML=function(node,par,val){  }

dhtmlXTreeObject.prototype._getAllCheckedXML=function(p,list,mode){
	var z=[];
	
	if (mode==2)
		p.through("item","checked",-1,function(c){
			z.push(c.get("id"));
  		},this);
  	
  	if (mode==1)
  		p.through("item","id",null,function(c){
  			if (c.get("checked") && (c.get("checked") !=-1))
			z.push(c.get("id"));
  		},this);
  		
	if (mode==0)
  		p.through("item","id",null,function(c){
			if (!c.get("checked") || c.get("checked")==0 )
			z.push(c.get("id"));
  		},this);  		
    if(z.length)
  		return list+(list?this.dlmtr:"")+z.join(this.dlmtr);
      if (list) return list; else return "";
   };


/**
*     @desc: change state of node's checkbox and all childnodes checkboxes
*     @type: private
*     @param: itemId - target node id
*     @param: state - checkbox state
*     @param: sNode - target node object (optional, used by private methods)
*     @topic: 5
*/
   dhtmlXTreeObject.prototype._setSubCheckedXML=function(state,p){
	   	var val= state?"1":"";
	   	p.through("item","id",null,function(c){
			if (!c.get("disabled") || c.get("disabled")==0 )
	   		    c.set("checked",val);
	   	},this);
}

       dhtmlXTreeObject.prototype._getAllScraggyItemsXML=function(p,x){
        var z=[];
        var fff=function(c){
	   		if (!c.sub_exists("item"))
	   			z.push(c.get("id"));
	   		else
	   			c.each("item",fff,this);
		   	}
	    fff(p);
        return z.join(",");
    }
    
   dhtmlXTreeObject.prototype._getAllFatItemsXML=function(p,x){
        var z=[];
        var fff=function(c){
	   		if (!c.sub_exists("item"))
	   			return;
   			z.push(c.get("id"));
   			c.each("item",fff,this);
		   	}
	    fff(p);
        return z.join(",");
    }

dhtmlXTreeObject.prototype._getAllSubItemsXML=function(itemId,z,p){
      var z=[];
      p.through("item","id",null,function(c){
      	z.push(c.get("id"));
      },this)
      return z.join(",");
    }

/**
*     @desc: parse stored xml
*     @param: node - XML node
*     @type: private
*     @edition: Professional
*     @topic: 3  
*/
 dhtmlXTreeObject.prototype.reParse=function(node){
        var that=this;
      if (!this.parsCount) that.callEvent("onXLS",[that,node.id]);
      this.xmlstate=1;

      var tmp=node.unParsed;
      node.unParsed=0;
//               if (confirm("reParse "+node.id)) { window.asdasd.asdasd(); }
      this.XMLloadingWarning=1;
        var oldpid=this.parsingOn;
		var oldmd=this.waitUpdateXML;
		var oldpa=this.parsedArray;

		this.parsedArray=new Array();
	  	this.waitUpdateXML=false;
      this.parsingOn=node.id;
      this.parsedArray=new Array();

         this.setCheckList="";
         this._parse(tmp,node.id,2);
         var chArr=this.setCheckList.split(this.dlmtr);

      for (var i=0; i<this.parsedArray.length; i++)
         node.htmlNode.childNodes[0].appendChild(this.parsedArray[i]);
      
	  if (tmp.get("order") && tmp.get("order")!="none")
	  	 	this._reorderBranch(node,tmp.get("order"),true);
	  	 	
            this.oldsmcheck=this.smcheck;
            this.smcheck=false;

         for (var n=0; n<chArr.length; n++)
            if (chArr[n])  this.setCheck(chArr[n],1);
            this.smcheck=this.oldsmcheck;

      this.parsingOn=oldpid;
	  this.waitUpdateXML=oldmd;
	  this.parsedArray=oldpa;  	  
      this.XMLloadingWarning=0;
      this._redrawFrom(this,node);
      if (this._srnd && !node._sready)
      	this.prepareSR(node.id);
      this.xmlstate=0;
      return true;
   }

/**
*     @desc: search for item in unparsed chunks
*     @param: itemId - item ID
*     @type: private
*     @edition: Professional
*     @topic: 3
*/
dhtmlXTreeObject.prototype.preParse=function(itemId){
   if (!itemId || !this._p) return null; 
   var result=false;
   this._p.clone().through("item","id",itemId,function(c){
   		this._globalIdStorageFind(c.up().get("id"));
   		return result=true;
   	},this);
   	if (result){
   		var n=this._globalIdStorageFind(itemId,true,false);
   		if (!n)
   			dhx4.callEvent("ongetItemError",["The item "+itemId+" not operable. Seems you have non-unique|incorrect IDs in tree's XML.",itemId]);
   	}
   	return n;
}

//#}
//#}

/**
*     @desc: escape string
*     @param: itemId - item ID
*     @type: private
*     @topic: 3
*/
   dhtmlXTreeObject.prototype._escape=function(str){
        switch(this.utfesc){
        case "none":
            return str;
            break;
        case "utf8":
         return encodeURIComponent(str);
            break;
        default:
         return escape(str);
            break;
        }
   }



/**
*     @desc: create and return  new line in tree
*     @type: private
*     @param: htmlObject - parent Node object
*     @param: node - item object
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype._drawNewTr=function(htmlObject,node)
   {
      var tr =document.createElement('tr');
      var td1=document.createElement('td');
      var td2=document.createElement('td');
      td1.appendChild(document.createTextNode(" "));
      td2.colSpan=3;
      td2.appendChild(htmlObject);
      tr.appendChild(td1);  tr.appendChild(td2);
      return tr;
   };
/**
*     @desc: load tree from xml string
*     @type: public
*     @param: xmlString - XML string
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.parse=function(xmlString,afterCall,type){
      if (typeof afterCall == "string"){
        type = afterCall;
        afterCall = null;
      }

      if (type === "json")
        return this._loadJSONObject(xmlString, afterCall)
      else if (type === "csv")
        return this._loadCSVString(xmlString, afterCall);
      else if (type === "jsarray")
        return this._loadJSArray(xmlString, afterCall);

      var that=this;
      if (!this.parsCount) this.callEvent("onXLS",[that,null]);
      this.xmlstate=1;
      this.XMLLoader({ responseXML:dhx4.ajax.parse(xmlString)}, afterCall);
    };

    dhtmlXTreeObject.prototype.loadXMLString = function(){
      if (window.console && window.console.info)
        window.console.info("loadXMLString was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
      return this.parse.apply(this, arguments);
    }

/**
*     @desc: load tree from xml file
*     @type: public
*     @param: file - link to XML file
*     @param: afterCall - function which will be called after xml loading
*     @topic: 0
*/
	dhtmlXTreeObject.prototype.load=function(file,afterCall,type){ 
    if (typeof afterCall == "string"){
      type = afterCall;
      afterCall = null;
    }

    type = type || this._datamode;
    if (type === "json")
      return this._loadJSON(file, afterCall)
    else if (type === "csv")
      return this._loadCSV(file, afterCall);
    else if (type === "jsarray")
      return this._loadJSArrayFile(xmlString, afterCall);

      var that=this;
      if (!this.parsCount) this.callEvent("onXLS",[that,this._ld_id]);
      this._ld_id=null;
      this.xmlstate=1;
      this.XMLLoader=this._parseXMLTree;

      var self = this;
      dhx4.ajax.get(file, function(data){
        self.XMLLoader(data.xmlDoc, afterCall);
        self = null;
      });
   };
   dhtmlXTreeObject.prototype.loadXML = function(){
      if (window.console && window.console.info)
        window.console.info("loadXML was deprecated", "http://docs.dhtmlx.com/migration__index.html#migrationfrom43to44");
      return this.load.apply(this, arguments);
   }
/**
*     @desc: create new child node
*     @type: private
*     @param: parentObject - parent node object
*     @param: itemId - new node id
*     @param: itemText - new node text
*     @param: itemActionHandler - function fired on node select event
*     @param: image1 - image for node without children;
*     @param: image2 - image for closed node;
*     @param: image3 - image for opened node
*     @param: optionStr - string of otions
*     @param: childs - node childs flag (for dynamical trees) (optional)
*     @param: beforeNode - node, after which new node will be inserted (optional)
*     @topic: 2
*/
   dhtmlXTreeObject.prototype._attachChildNode=function(parentObject,itemId,itemText,itemActionHandler,image1,image2,image3,optionStr,childs,beforeNode,afterNode){

         if (beforeNode && beforeNode.parentObject) parentObject=beforeNode.parentObject;
         if (((parentObject.XMLload==0)&&(this.XMLsource))&&(!this.XMLloadingWarning))
         {
            parentObject.XMLload=1;
                this._loadDynXML(parentObject.id);

         }

         var Count=parentObject.childsCount;
         var Nodes=parentObject.childNodes;


            if (afterNode && afterNode.tr.previousSibling){
            if (afterNode.tr.previousSibling.previousSibling){
               beforeNode=afterNode.tr.previousSibling.nodem;
               }
            else
               optionStr=optionStr.replace("TOP","")+",TOP";
               }

         if (beforeNode)
            {
            var ik,jk;
            for (ik=0; ik<Count; ik++)
               if (Nodes[ik]==beforeNode)
               {
               for (jk=Count; jk!=ik; jk--)
                  Nodes[1+jk]=Nodes[jk];
               break;
               }
            ik++;
            Count=ik;
            }


         if (optionStr) {
             var tempStr=optionStr.split(",");
            for (var i=0; i<tempStr.length; i++)
            {
               switch(tempStr[i])
               {
                  case "TOP": if (parentObject.childsCount>0) { beforeNode=new Object; beforeNode.tr=parentObject.childNodes[0].tr.previousSibling; }
				  	 parentObject._has_top=true;
                     for  (ik=Count; ik>0; ik--)
                        Nodes[ik]=Nodes[ik-1];
                        Count=0;
                     break;
               }
            };
          };

        	var n;
		if (!(n=this._idpull[itemId]) || n.span!=-1){
         	n=Nodes[Count]=new dhtmlXTreeItemObject(itemId,itemText,parentObject,this,itemActionHandler,1);
         	itemId = Nodes[Count].id;
         	parentObject.childsCount++;
     	}
        
        if(!n.htmlNode) {
           n.label=itemText;
		   n.htmlNode=this._createItem((this.checkBoxOff?1:0),n);
   		   n.htmlNode.objBelong=n;
   		  }

         if(image1) n.images[0]=image1;
         if(image2) n.images[1]=image2;
         if(image3) n.images[2]=image3;

		
         var tr=this._drawNewTr(n.htmlNode);
         if ((this.XMLloadingWarning)||(this._hAdI))
            n.htmlNode.parentNode.parentNode.style.display="none";

           
            if ((beforeNode)&&beforeNode.tr&&(beforeNode.tr.nextSibling))
               parentObject.htmlNode.childNodes[0].insertBefore(tr,beforeNode.tr.nextSibling);
            else
               if (this.parsingOn==parentObject.id){
                  this.parsedArray[this.parsedArray.length]=tr;
                        }
               else
                   parentObject.htmlNode.childNodes[0].appendChild(tr);


               if ((beforeNode)&&(!beforeNode.span)) beforeNode=null;

            if (this.XMLsource) if ((childs)&&(childs!=0)) n.XMLload=0; else n.XMLload=1;
            n.tr=tr;
            tr.nodem=n;

            if (parentObject.itemId==0)
                tr.childNodes[0].className="hiddenRow";

            if ((parentObject._r_logic)||(this._frbtr))
                this._setSrc(n.htmlNode.childNodes[0].childNodes[0].childNodes[1].childNodes[0],this.imPath+this.radioArray[0]);


          if (optionStr) {
             var tempStr=optionStr.split(",");

            for (var i=0; i<tempStr.length; i++)
            {
               switch(tempStr[i])
               {
                     case "SELECT": this.selectItem(itemId,false); break;
                  case "CALL": this.selectItem(itemId,true);   break;
                  case "CHILD":  n.XMLload=0;  break;
                  case "CHECKED":
                     if (this.XMLloadingWarning)
                        this.setCheckList+=this.dlmtr+itemId;
                     else
                        this.setCheck(itemId,1);
                        break;
                  case "HCHECKED":
                        this._setCheck(n,"unsure");
                        break;                        
                  case "OPEN": n.openMe=1;  break;
               }
            };
          };

      if (!this.XMLloadingWarning)
      {
             if ((this._getOpenState(parentObject)<0)&&(!this._hAdI)) this.openItem(parentObject.id);

             if (beforeNode)
                {
             this._correctPlus(beforeNode);
             this._correctLine(beforeNode);
                }
             this._correctPlus(parentObject);
             this._correctLine(parentObject);
             this._correctPlus(n);
             if (parentObject.childsCount>=2)
             {
                   this._correctPlus(Nodes[parentObject.childsCount-2]);
                   this._correctLine(Nodes[parentObject.childsCount-2]);
             }
             if (parentObject.childsCount!=2) this._correctPlus(Nodes[0]);

         if (this.tscheck) this._correctCheckStates(parentObject);

            if (this._onradh){
				if (this.xmlstate==1){
					var old=this.onXLE;
					this.onXLE=function(id){ this._onradh(itemId); if (old) old(id); }
					}
				else
					this._onradh(itemId);
			}

      }
   return n;
};


//#__pro_feature:01112006{
//#context_menu:01112006{

/**
*     @desc: enable context menu
*     @param: menu - dhtmlXMenu object
*     @edition: Professional
*     @type: public
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableContextMenu=function(menu){  if (menu) this.cMenu=menu; };

/**
*     @desc: set context menu to individual nodes
*     @type: public
*     @param: itemId - node id
*     @param: cMenu - context menu object
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.setItemContextMenu=function(itemId,cMenu){
   var l=itemId.toString().split(this.dlmtr);
   for (var i=0; i<l.length; i++)
      {
      var temp=this._globalIdStorageFind(l[i]);
      if (!temp) continue;
      temp.cMenu=cMenu;
      }
}

//#}
//#}

/**
*     @desc: create new node as a child to specified with parentId
*     @type: deprecated
*     @param: parentId - parent node id
*     @param: itemId - new node id
*     @param: itemText - new node text
*     @param: itemActionHandler - function fired on node select event (optional)
*     @param: image1 - image for node without children; (optional)
*     @param: image2 - image for closed node; (optional)
*     @param: image3 - image for opened node (optional)
*     @param: optionStr - options string (optional)            
*     @param: children - node children flag (for dynamical trees) (optional)
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype.insertNewItem=function(parentId,itemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children){
      var parentObject=this._globalIdStorageFind(parentId);
      if (!parentObject) return (-1);
      var nodez=this._attachChildNode(parentObject,itemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children);
      if(!this._idpull[this.rootId].XMLload)
         this._idpull[this.rootId].XMLload = 1;
//#__pro_feature:01112006{
//#child_calc:01112006{
      if ((!this.XMLloadingWarning)&&(this.childCalc))  this._fixChildCountLabel(parentObject);
//#}
//#}
        return nodez;
   };
/**
*     @desc: create new node as a child to specified with parentId
*     @type: public
*     @param: parentId - parent node id
*     @param: itemId - new node id
*     @param: itemText - new node label
*     @param: itemActionHandler - function fired on node select event (optional)
*     @param: image1 - image for node without children; (optional)
*     @param: image2 - image for closed node; (optional)
*     @param: image3 - image for opened node (optional)
*     @param: optionStr - options string (optional)            
*     @param: children - node children flag (for dynamical trees) (optional)
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype.insertNewChild=function(parentId,itemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children){
      return this.insertNewItem(parentId,itemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children);
   }   
/**  
*     @desc: parse xml
*     @type: private
*     @param: dhtmlObject - jsTree object
*     @param: node - top XML node
*     @param: parentId - parent node id
*     @param: level - level of tree
*     @topic: 2
*/
	dhtmlXTreeObject.prototype._parseXMLTree=function(xml, callback){
		var p=new xmlPointer(dhx4.ajax.xmltop("tree", xml));
		this._parse(p);
		this._p=p;
    if (callback) callback.call(this, xml);
	}
	
	dhtmlXTreeObject.prototype._parseItem=function(c,temp,preNode,befNode){ 
		var id;
		if (this._srnd && (!this._idpull[id=c.get("id")] || !this._idpull[id].span))
		{
			this._addItemSRND(temp.id,id,c);
			return; 
		}
		
  var a=c.get_all();
        
        if ((typeof(this.waitUpdateXML)=="object")&&(!this.waitUpdateXML[a.id])){
			this._parse(c,a.id,1);
			return;
		}    

//#__pro_feature:01112006{
			if ((a.text===null)||(typeof(a.text)=="undefined")){
				a.text=c.sub("itemtext");
				if (a.text) a.text=a.text.content();
			}
//#}
              



                  var zST=[];
                  if (a.select) zST.push("SELECT");
                  if (a.top) zST.push("TOP");
                  if (a.call) this.nodeAskingCall=a.id;
                  if (a.checked==-1) zST.push("HCHECKED");
                     else if (a.checked) zST.push("CHECKED");
                  if (a.open) zST.push("OPEN");
	
    	          if (this.waitUpdateXML){
				  		if (this._globalIdStorageFind(a.id))
	    	            	var newNode=this.updateItem(a.id,a.text,a.im0,a.im1,a.im2,a.checked,a.child);
						else{
							if (this.npl==0) zST.push("TOP");
							else preNode=temp.childNodes[this.npl];

		                    var newNode=this._attachChildNode(temp,a.id,a.text,0,a.im0,a.im1,a.im2,zST.join(","),a.child,0,preNode);
                        a.id = newNode.id;
							preNode=null;
						}
					 }
                  else
                     var newNode=this._attachChildNode(temp,a.id,a.text,0,a.im0,a.im1,a.im2,zST.join(","),a.child,(befNode||0),preNode);
                  if (a.tooltip)
					newNode.span.parentNode.parentNode.title=a.tooltip;

                  if (a.style)
                            if (newNode.span.style.cssText)
                                newNode.span.style.cssText+=(";"+a.style);
                            else
                                newNode.span.setAttribute("style",newNode.span.getAttribute("style")+"; "+a.style);

                        if (a.radio) newNode._r_logic=true;

                  if (a.nocheckbox){
                  	 var check_node=newNode.span.parentNode.previousSibling.previousSibling;
                     check_node.style.display="none";
                     newNode.nocheckbox=true;
                  }
                        if (a.disabled){
                            if (a.checked!=null) this._setCheck(newNode,a.checked);
                            this.disableCheckbox(newNode,1);
                            }

				
                  newNode._acc=a.child||0;

                  if (this.parserExtension) this.parserExtension._parseExtension.call(this,c,a,(temp?temp.id:0));

                  this.setItemColor(newNode,a.aCol,a.sCol);
                  if (a.locked=="1")    this.lockItem(newNode.id,true,true);

                  if ((a.imwidth)||(a.imheight))   this.setIconSize(a.imwidth,a.imheight,newNode);
                  if ((a.closeable=="0")||(a.closeable=="1"))  this.setItemCloseable(newNode,a.closeable);
                  var zcall="";
                  if (a.topoffset) this.setItemTopOffset(newNode,a.topoffset);
                  if ((!this.slowParse)||(typeof(this.waitUpdateXML)=="object")){ 
                  	if (c.sub_exists("item"))
                    	zcall=this._parse(c,a.id,1);
                  }
//#__pro_feature:01112006{
//#smart_parsing:01112006{
                  else {
                  	if ((!newNode.childsCount) && c.sub_exists("item"))
                      newNode.unParsed=c.clone();
                     
						c.each("userdata",function(u){
							this.setUserData(a.id,u.get("name"),u.content());
						},this);
     	  	      }
//#}                     
//#}
                  if (zcall!="") this.nodeAskingCall=zcall;

   
        c.each("userdata",function(u){
    	  		this.setUserData(c.get("id"),u.get("name"),u.content());
 	  	  },this)
		
		
	}
   	dhtmlXTreeObject.prototype._parse=function(p,parentId,level,start){ 
   		if (this._srnd && !this.parentObject.offsetHeight) {
   			var self=this;
   			return window.setTimeout(function(){
   				self._parse(p,parentId,level,start);
   			},100);
   		}
		if (!p.exists()) return;
		
		this.skipLock=true; //disable item locking
		//loading flags
		
		
		if (!parentId) {          //top level  
			parentId=p.get("id");
			
	  // deleting child items for refreshed branches
	  if(this._dynDeleteBranches[parentId]){
		 this.deleteChildItems(parentId);
		 this._dynDeleteBranches[parentId]--;
		 if(!this._dynDeleteBranches[parentId]){
			delete this._dynDeleteBranches[parentId];
		 }	
	  }
	  
      var skey = p.get("dhx_security");
      if (skey)
          dhtmlx.security_key = skey;

			if (p.get("radio"))
				this.htmlNode._r_logic=true;
			this.parsingOn=parentId;                 
			this.parsedArray=new Array();
			this.setCheckList="";
			this.nodeAskingCall="";
		}
		
		var temp=this._globalIdStorageFind(parentId);
		if (!temp) return dhx4.callEvent("onDataStructureError",["XML refers to not existing parent"]);

		this.parsCount=this.parsCount?(this.parsCount+1):1;
		this.XMLloadingWarning=1;

		if ((temp.childsCount)&&(!start)&&(!this._edsbps)&&(!temp._has_top))
            var preNode=0;//temp.childNodes[temp.childsCount-1];
        else
            var preNode=0;

        this.npl=0;

		p.each("item",function(c,i){
				
		temp.XMLload=1;
				
          this._parseItem(c,temp,0,preNode); 
 	  	  
//#__pro_feature:01112006{
//#distributed_load:01112006{
              if ((this._edsbps)&&(this.npl==this._edsbpsC)){
                this._distributedStart(p,i+1,parentId,level,temp.childsCount);
                return -1;
              }
//#}
//#}
              this.npl++;
         

 	  	  
      },this,start);


      if (!level) {
      	  p.each("userdata",function(u){
    	  		this.setUserData(p.get("id"),u.get("name"),u.content());
 	  	  },this);
 	  	  
	  	 temp.XMLload=1;
         if (this.waitUpdateXML){
            this.waitUpdateXML=false;
			for (var i=temp.childsCount-1; i>=0; i--)
				if (temp.childNodes[i]._dmark)
					this.deleteItem(temp.childNodes[i].id);
			}

         var parsedNodeTop=this._globalIdStorageFind(this.parsingOn);

         for (var i=0; i<this.parsedArray.length; i++)
               temp.htmlNode.childNodes[0].appendChild(this.parsedArray[i]);
		this.parsedArray = [];
		
         this.lastLoadedXMLId=parentId;
         this.XMLloadingWarning=0;

         var chArr=this.setCheckList.split(this.dlmtr);
         for (var n=0; n<chArr.length; n++)
            if (chArr[n]) this.setCheck(chArr[n],1);

               if ((this.XMLsource)&&(this.tscheck)&&(this.smcheck)&&(temp.id!=this.rootId)){
                if (temp.checkstate===0)
                    this._setSubChecked(0,temp);
                else if (temp.checkstate===1)
                    this._setSubChecked(1,temp);
            }

         this._redrawFrom(this,null,start)
		 if (p.get("order") && p.get("order")!="none")
	  	 	this._reorderBranch(temp,p.get("order"),true);
	  	 	
	  	 if (this.nodeAskingCall!="") this.callEvent("onClick",[this.nodeAskingCall,this.getSelectedItemId()]); 
         if (this._branchUpdate) this._branchUpdateNext(p);
	     }


      if (this.parsCount==1) {
      	 this.parsingOn=null;
//#__pro_feature:01112006{
//#smart_parsing:01112006{
          //if ((this.slowParse)&&(this.parsingOn==this.rootId))
         if (this._srnd && temp.id!=this.rootId){
 	  	 	this.prepareSR(temp.id);
 	  	 	if (this.XMLsource) this.openItem(temp.id)
 	  	 }
 	  	 
            p.through("item","open",null,function(c){
            	this.openItem(c.get("id"));
            	},this);
//#}
//#}

         
         if ((!this._edsbps)||(!this._edsbpsA.length)){
         		var that=this;
               	window.setTimeout( function(){  that.callEvent("onXLE",[that,parentId]); },1);
                this.xmlstate=0;
                }
             this.skipLock=false;
         }
      this.parsCount--;

//#__pro_feature:01112006{
//#distributed_load:01112006{
		var that=this;
        if (this._edsbps) window.setTimeout(function(){ that._distributedStep(parentId); },this._edsbpsD);
//#}
//#}

        
		
		if (!level && this.onXLE) this.onXLE(this,parentId);
      return this.nodeAskingCall;
  };
  

dhtmlXTreeObject.prototype._branchUpdateNext=function(p){
	p.each("item",function(c){
		var nid=c.get("id");
		if (this._idpull[nid] && (!this._idpull[nid].XMLload))  return;
		this._branchUpdate++;
		this.smartRefreshItem(c.get("id"),c);
	},this)
	this._branchUpdate--;
} 

  dhtmlXTreeObject.prototype.checkUserData=function(node,parentId){
      if ((node.nodeType==1)&&(node.tagName == "userdata"))
      {
         var name=node.getAttribute("name");
            if ((name)&&(node.childNodes[0]))
               this.setUserData(parentId,name,node.childNodes[0].data);
      }
  }




/**  
*     @desc: reset tree images from selected level
*     @type: private
*     @param: dhtmlObject - tree
*     @param: itemObject - current item
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._redrawFrom=function(dhtmlObject,itemObject,start,visMode){
      if (!itemObject) {
      var tempx=dhtmlObject._globalIdStorageFind(dhtmlObject.lastLoadedXMLId);
      dhtmlObject.lastLoadedXMLId=-1;
      if (!tempx) return 0;
      }
      else tempx=itemObject;
      var acc=0;
      for (var i=(start?start-1:0); i<tempx.childsCount; i++)
      {
	  	 if ((!this._branchUpdate)||(this._getOpenState(tempx)==1))
	         if ((!itemObject)||(visMode==1)) tempx.childNodes[i].htmlNode.parentNode.parentNode.style.display="";
         if (tempx.childNodes[i].openMe==1)
            {
            this._openItem(tempx.childNodes[i]);
            tempx.childNodes[i].openMe=0;
            }

         dhtmlObject._redrawFrom(dhtmlObject,tempx.childNodes[i]);
//#__pro_feature:01112006{
//#child_calc:01112006{
      if (this.childCalc!=null){

      if ((tempx.childNodes[i].unParsed)||((!tempx.childNodes[i].XMLload)&&(this.XMLsource)))
      {

         if (tempx.childNodes[i]._acc)
         tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label+this.htmlcA+tempx.childNodes[i]._acc+this.htmlcB;
         else
         tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label;
      }
         if ((tempx.childNodes[i].childNodes.length)&&(this.childCalc))
         {
            if (this.childCalc==1)
               {
               tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label+this.htmlcA+tempx.childNodes[i].childsCount+this.htmlcB;
               }
            if (this.childCalc==2)
               {
               var zCount=tempx.childNodes[i].childsCount-(tempx.childNodes[i].pureChilds||0);
               if (zCount)
                  tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label+this.htmlcA+zCount+this.htmlcB;
               if (tempx.pureChilds) tempx.pureChilds++; else tempx.pureChilds=1;
               }
            if (this.childCalc==3)
               {
               tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label+this.htmlcA+tempx.childNodes[i]._acc+this.htmlcB;
               }
            if (this.childCalc==4)
               {
               var zCount=tempx.childNodes[i]._acc;
               if (zCount)
                  tempx.childNodes[i].span.innerHTML=tempx.childNodes[i].label+this.htmlcA+zCount+this.htmlcB;
               }               
         }
            else if (this.childCalc==4)   {
               acc++;
               }   
            
         acc+=tempx.childNodes[i]._acc;
         
         if (this.childCalc==3){
            acc++;
         }

         }
//#}
//#}

      };

      if ((!tempx.unParsed)&&((tempx.XMLload)||(!this.XMLsource)))
      tempx._acc=acc;
      dhtmlObject._correctLine(tempx);
      dhtmlObject._correctPlus(tempx);
//#__pro_feature:01112006{
//#child_calc:01112006{
      if ((this.childCalc)&&(!itemObject)) dhtmlObject._fixChildCountLabel(tempx);
//#}
//#}
   };

/**
*     @desc: create and return main html element of tree
*     @type: private
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype._createSelf=function(){
      var div=document.createElement('div');
      div.className="containerTableStyle";
      div.style.width=this.width;
      div.style.height=this.height;
      this.parentObject.appendChild(div);
      return div;
   };

/**
*     @desc: collapse target node
*     @type: private
*     @param: itemObject - item object
*     @topic: 4  
*/
   dhtmlXTreeObject.prototype._xcloseAll=function(itemObject)
   {
        if (itemObject.unParsed) return;
      if (this.rootId!=itemObject.id) {
      		if (!itemObject.htmlNode) return;//srnd
          var Nodes=itemObject.htmlNode.childNodes[0].childNodes;
            var Count=Nodes.length;

          for (var i=1; i<Count; i++)
             Nodes[i].style.display="none";

          this._correctPlus(itemObject);
      }

       for (var i=0; i<itemObject.childsCount; i++)
            if (itemObject.childNodes[i].childsCount)
             this._xcloseAll(itemObject.childNodes[i]);
   };
/**
*     @desc: expand target node
*     @type: private
*     @param: itemObject - item object
*     @topic: 4
*/      
   dhtmlXTreeObject.prototype._xopenAll=function(itemObject)
   {
      this._HideShow(itemObject,2);
      for (var i=0; i<itemObject.childsCount; i++)
         this._xopenAll(itemObject.childNodes[i]);
   };      
/**  
*     @desc: set correct tree-line and node images
*     @type: private
*     @param: itemObject - item object
*     @topic: 6  
*/
dhtmlXTreeObject.prototype._correctPlus=function(itemObject){
	if (!itemObject.htmlNode) return;
        var imsrc=itemObject.htmlNode.childNodes[0].childNodes[0].childNodes[0].lastChild;
        var imsrc2=itemObject.htmlNode.childNodes[0].childNodes[0].childNodes[2].childNodes[0];
        
        var workArray=this.lineArray;
        if ((this.XMLsource)&&(!itemObject.XMLload)) {
        	var workArray=this.plusArray;
        	this._setSrc(imsrc2,this.iconURL+itemObject.images[2]);
                if (this._txtimg) return (imsrc.innerHTML="[+]");
        } else if ((itemObject.childsCount)||(itemObject.unParsed)) {
        	if ((itemObject.htmlNode.childNodes[0].childNodes[1])&&( itemObject.htmlNode.childNodes[0].childNodes[1].style.display!="none" )) {
        		if (!itemObject.wsign) var workArray=this.minusArray;
        		this._setSrc(imsrc2,this.iconURL+itemObject.images[1]);
        		if (this._txtimg) return (imsrc.innerHTML="[-]");
        	} else {
        		if (!itemObject.wsign) var workArray=this.plusArray;
        		this._setSrc(imsrc2,this.iconURL+itemObject.images[2]);
        		if (this._txtimg) return (imsrc.innerHTML="[+]");
        	}
        } else {
        	this._setSrc(imsrc2,this.iconURL+itemObject.images[0]);
        }
        
        
        var tempNum=2;
        if (!itemObject.treeNod.treeLinesOn) {
        	this._setSrc(imsrc,this.imPath+workArray[3]);
        } else {
        	if (itemObject.parentObject) tempNum=this._getCountStatus(itemObject.id,itemObject.parentObject);
        	this._setSrc(imsrc,this.imPath+workArray[tempNum]);
        }
};

/**
*     @desc: set correct tree-line images
*     @type: private
*     @param: itemObject - item object
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._correctLine=function(itemObject){
   	  if (!itemObject.htmlNode) return;
      var sNode=itemObject.parentObject;
      if (sNode)
         if ((this._getLineStatus(itemObject.id,sNode)==0)||(!this.treeLinesOn))
               for(var i=1; i<=itemObject.childsCount; i++){
                  if (!itemObject.htmlNode.childNodes[0].childNodes[i]) break;
                  itemObject.htmlNode.childNodes[0].childNodes[i].childNodes[0].style.backgroundImage="";
                  itemObject.htmlNode.childNodes[0].childNodes[i].childNodes[0].style.backgroundRepeat="";
                }
            else
               for(var i=1; i<=itemObject.childsCount; i++){
               	 if (!itemObject.htmlNode.childNodes[0].childNodes[i]) break;
               	 itemObject.htmlNode.childNodes[0].childNodes[i].childNodes[0].style.backgroundImage="url("+this.imPath+this.lineArray[5]+")";
               	 itemObject.htmlNode.childNodes[0].childNodes[i].childNodes[0].style.backgroundRepeat="repeat-y";
	     }
   };
/**
*     @desc: return type of node
*     @type: private
*     @param: itemId - item id
*     @param: itemObject - parent node object
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getCountStatus=function(itemId,itemObject){
      if (itemObject.childsCount<=1) { if (itemObject.id==this.rootId) return 4; else  return 0; }

      if (itemObject.childNodes[0].id==itemId) if (itemObject.id==this.rootId) return 2; else return 1;
      if (itemObject.childNodes[itemObject.childsCount-1].id==itemId) return 0;

      return 1;
   };
/**
*     @desc: return type of node
*     @type: private
*     @param: itemId - node id        
*     @param: itemObject - parent node object
*     @topic: 6
*/      
   dhtmlXTreeObject.prototype._getLineStatus =function(itemId,itemObject){
         if (itemObject.childNodes[itemObject.childsCount-1].id==itemId) return 0;
         return 1;
      }

/**  
*     @desc: open/close node 
*     @type: private
*     @param: itemObject - node object        
*     @param: mode - open/close mode [1-close 2-open](optional)
*     @topic: 6
*/      
   dhtmlXTreeObject.prototype._HideShow=function(itemObject,mode){
      if (this._locker && !this.skipLock && this._locker[itemObject.id]) return;
      if ((this.XMLsource)&&(!itemObject.XMLload)) {
            if (mode==1) return; //close for not loaded node - ignore it
            itemObject.XMLload=1;
            this._loadDynXML(itemObject.id);
            return; };
//#__pro_feature:01112006{
//#smart_parsing:01112006{
        if (itemObject.unParsed) this.reParse(itemObject);
//#}
//#}
      var Nodes=itemObject.htmlNode.childNodes[0].childNodes; var Count=Nodes.length;
      if (Count>1){
         if ( ( (Nodes[1].style.display!="none") || (mode==1) ) && (mode!=2) ) {
//nb:solves standard doctype prb in IE
          this.allTree.childNodes[0].border = "1";
          this.allTree.childNodes[0].border = "0";
         nodestyle="none";
         }
         else  nodestyle="";

      for (var i=1; i<Count; i++)
         Nodes[i].style.display=nodestyle;
      }
      this._correctPlus(itemObject);
   }

/**
*     @desc: return node state
*     @type: private
*     @param: itemObject - node object        
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getOpenState=function(itemObject){
   	  if (!itemObject.htmlNode) return 0; //srnd
   	  var z=itemObject.htmlNode.childNodes[0].childNodes;
      if (z.length<=1) return 0;
      if    (z[1].style.display!="none") return 1;
      else return -1;
   }

   

/**  
*     @desc: ondblclick item  event handler
*     @type: private
*     @topic: 0  
*/      
   dhtmlXTreeObject.prototype.onRowClick2=function(){
   	  var that=this.parentObject.treeNod;
      if (!that.callEvent("onDblClick",[this.parentObject.id,that])) return false;
      if ((this.parentObject.closeble)&&(this.parentObject.closeble!="0"))
         that._HideShow(this.parentObject);
      else
         that._HideShow(this.parentObject,2);

   	if    (that.checkEvent("onOpenEnd"))
           if (!that.xmlstate)
				that.callEvent("onOpenEnd",[this.parentObject.id,that._getOpenState(this.parentObject)]);
            else{
                that._oie_onXLE.push(that.onXLE);
                that.onXLE=that._epnFHe;
                }
    	return false;
   };
/**
*     @desc: onclick item event handler
*     @type: private
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.onRowClick=function(){ 
    var that=this.parentObject.treeNod;
	  if (!that.callEvent("onOpenStart",[this.parentObject.id,that._getOpenState(this.parentObject)])) return 0;
      if ((this.parentObject.closeble)&&(this.parentObject.closeble!="0"))
         that._HideShow(this.parentObject);
      else
         that._HideShow(this.parentObject,2);

	
   if    (that.checkEvent("onOpenEnd"))
           if (!that.xmlstate)
				that.callEvent("onOpenEnd",[this.parentObject.id,that._getOpenState(this.parentObject)]);
            else{
                that._oie_onXLE.push(that.onXLE);
                that.onXLE=that._epnFHe;
                }

   };

      dhtmlXTreeObject.prototype._epnFHe=function(that,id,flag){
      	if (id!=this.rootId)
	  		this.callEvent("onOpenEnd",[id,that.getOpenState(id)]);
        that.onXLE=that._oie_onXLE.pop();
        
        if (!flag && !that._oie_onXLE.length)
			if (that.onXLE) that.onXLE(that,id);
    }



/**
*     @desc: onclick item image event handler
*     @type: private
*     @edition: Professional
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.onRowClickDown=function(e){
            e=e||window.event;
         var that=this.parentObject.treeNod;
         that._selectItem(this.parentObject,e);
      };


/*****
SELECTION
*****/

/**
*     @desc: retun selected item id
*     @type: public
*     @return: id of selected item
*     @topic: 1
*/
   dhtmlXTreeObject.prototype.getSelectedItemId=function()
   {
        var str=new Array();
        for (var i=0; i<this._selected.length; i++) str[i]=this._selected[i].id;
      return (str.join(this.dlmtr));
   };

/**
*     @desc: visual select item in tree
*     @type: private
*     @param: node - tree item object
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype._selectItem=function(node,e){
   		if (this.checkEvent("onSelect")) this._onSSCFold=this.getSelectedItemId();
//#__pro_feature:01112006{
//#multiselect:01112006{
        if ((!this._amsel)||(!e)||((!e.ctrlKey)&&(!e.metaKey)&&(!e.shiftKey)))
//#}
//#}
            this._unselectItems();
//#__pro_feature:01112006{
//#multiselect:01112006{
            if ((node.i_sel)&&(this._amsel)&&(e)&&(e.ctrlKey || e.metaKey))
                this._unselectItem(node);
            else
            if ((!node.i_sel)&&((!this._amselS)||(this._selected.length==0)||(this._selected[0].parentObject==node.parentObject)))
                if ((this._amsel)&&(e)&&(e.shiftKey)&&(this._selected.length!=0)&&(this._selected[this._selected.length-1].parentObject==node.parentObject)){
                    var a=this._getIndex(this._selected[this._selected.length-1]);
                    var b=this._getIndex(node);
                    if (b<a) { var c=a; a=b; b=c; }
                    for (var i=a; i<=b; i++)
                        if (!node.parentObject.childNodes[i].i_sel)
                            this._markItem(node.parentObject.childNodes[i]);
                    }
                else
//#}
//#}
					this._markItem(node);
		if (this.checkEvent("onSelect")) {
		   	var z=this.getSelectedItemId();
			if (z!=this._onSSCFold)
				this.callEvent("onSelect",[z]);
		}
    }
    dhtmlXTreeObject.prototype._markItem=function(node){
              if (node.scolor)  node.span.style.color=node.scolor;
              node.span.className = "selectedTreeRow";
              node.span.parentNode.parentNode.className = "selectedTreeRowFull";
             node.i_sel=true;
             this._selected[this._selected.length]=node;
    }

/**
*     @desc: retun node index in children collection by Id
*     @type: public
*     @param: itemId - node id
*     @return: node index
*     @topic: 2
*/
   dhtmlXTreeObject.prototype.getIndexById=function(itemId){
         var z=this._globalIdStorageFind(itemId);
         if (!z) return null;
         return this._getIndex(z);
   };
   dhtmlXTreeObject.prototype._getIndex=function(w){
        var z=w.parentObject;
        for (var i=0; i<z.childsCount; i++)
            if (z.childNodes[i]==w) return i;
   };





/**
*     @desc: visual unselect item in tree
*     @type: private
*     @param: node - tree item object
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype._unselectItem=function(node){
        if ((node)&&(node.i_sel))
            {

          node.span.className="standartTreeRow";
          node.span.parentNode.parentNode.className = "";
          if (node.acolor)  node.span.style.color=node.acolor;
            node.i_sel=false;
            for (var i=0; i<this._selected.length; i++)
                    if (!this._selected[i].i_sel) {
                        this._selected.splice(i,1);
                        break;
                 }
            }
       }

/**
*     @desc: visual unselect items in tree
*     @type: private
*     @param: node - tree item object
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype._unselectItems=function(){
      for (var i=0; i<this._selected.length; i++){
            var node=this._selected[i];
         node.span.className="standartTreeRow";
         node.span.parentNode.parentNode.className = "";
          if (node.acolor)  node.span.style.color=node.acolor;
         node.i_sel=false;
         }
         this._selected=new Array();
       }


/**  
*     @desc: select node text event handler
*     @type: private
*     @param: e - event object
*     @param: htmlObject - node object     
*     @param: mode - if false - call onSelect event
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.onRowSelect=function(e,htmlObject,mode){
      e=e||window.event;

        var obj=this.parentObject;
      if (htmlObject) obj=htmlObject.parentObject;
        var that=obj.treeNod;

        var lastId=that.getSelectedItemId();
		if ((!e)||(!e.skipUnSel))
	        that._selectItem(obj,e);

      if (!mode) {	 	
         if (obj.actionHandler) obj.actionHandler(obj.id,lastId);
		 else that.callEvent("onClick",[obj.id,lastId]);
         }
   };




   
/**
*     @desc: fix checkbox state
*     @type: private
*     @topic: 0
*/
dhtmlXTreeObject.prototype._correctCheckStates=function(dhtmlObject){
	
   if (!this.tscheck) return;
   if (!dhtmlObject) return;
   if (dhtmlObject.id==this.rootId) return;
   //calculate state
   var act=dhtmlObject.childNodes;
   var flag1=0; var flag2=0;
   if (dhtmlObject.childsCount==0) return;
   for (var i=0; i<dhtmlObject.childsCount; i++){
   	  if (act[i].dscheck) continue;
      if (act[i].checkstate==0) flag1=1;
      else if (act[i].checkstate==1) flag2=1;
         else { flag1=1; flag2=1; break; }
		 }

   if ((flag1)&&(flag2)) this._setCheck(dhtmlObject,"unsure");
   else if (flag1)  this._setCheck(dhtmlObject,false);
      else  this._setCheck(dhtmlObject,true);

      this._correctCheckStates(dhtmlObject.parentObject);
}

/**
*     @desc: checbox select action
*     @type: private
*     @topic: 0
*/   
   dhtmlXTreeObject.prototype.onCheckBoxClick=function(e){
	   	  if (!this.treeNod.callEvent("onBeforeCheck",[this.parentObject.id,this.parentObject.checkstate]))
	   	  	return;
   	  
      if (this.parentObject.dscheck) return true;
      if (this.treeNod.tscheck)
         if (this.parentObject.checkstate==1) this.treeNod._setSubChecked(false,this.parentObject);
         else this.treeNod._setSubChecked(true,this.parentObject);
      else
         if (this.parentObject.checkstate==1) this.treeNod._setCheck(this.parentObject,false);
         else this.treeNod._setCheck(this.parentObject,true);
      this.treeNod._correctCheckStates(this.parentObject.parentObject);

      return this.treeNod.callEvent("onCheck",[this.parentObject.id,this.parentObject.checkstate]);
   };
/**
*     @desc: create HTML elements for tree node
*     @type: private
*     @param: acheck - enable/disable checkbox
*     @param: itemObject - item object
*     @param: mode - mode
*     @topic: 0
*/
dhtmlXTreeObject.prototype._createItem=function(acheck,itemObject,mode){
	
	var table=document.createElement('table');
	table.cellSpacing = 0;
	table.cellPadding = 0;
	table.border = 0;
	
	if (this.hfMode) table.style.tableLayout="fixed";
	table.style.margin = 0;
	table.style.padding = 0;
	
	var tbody=document.createElement('tbody');
	var tr=document.createElement('tr');
	
	var td1=document.createElement('td');
	td1.className="standartTreeImage";
	
	if(this._txtimg){
		var img0=document.createElement("div");
		td1.appendChild(img0);
		img0.className="dhx_tree_textSign";
	} else {
		var img0 = this._getImg(itemObject.id);
		img0.border = "0";
		if (img0.tagName == "IMG") {
			img0.align="absmiddle";
		}
		td1.appendChild(img0);
		img0.style.padding = 0;
		img0.style.margin = 0;
    img0.style.width = this.def_line_img_x;
    //img0.style.height = this.def_line_img_y;
	}
	
	var td11=document.createElement('td');
	//         var inp=document.createElement("input");            inp.type="checkbox"; inp.style.width="12px"; inp.style.height="12px";
	var inp=this._getImg(this.cBROf?this.rootId:itemObject.id);
	inp.checked=0; this._setSrc(inp,this.imPath+this.checkArray[0]); inp.style.width="18px"; inp.style.height="18px";
	//can cause problems with hide/show check
	
	if (!acheck) td11.style.display="none";
	
	// td11.className="standartTreeImage";
	//if (acheck)
	td11.appendChild(inp);
	if ((!this.cBROf)&&(inp.tagName=="IMG")) inp.align="absmiddle";
	inp.onclick=this.onCheckBoxClick;
	inp.treeNod=this;
	inp.parentObject=itemObject;
	if (!window._KHTMLrv) td11.width="20px";
	else td11.width="16px";
	
	var td12=document.createElement('td');
	td12.className="standartTreeImage";
	var img=this._getImg(this.timgen?itemObject.id:this.rootId);
	img.onmousedown=this._preventNsDrag; img.ondragstart=this._preventNsDrag;
	img.border="0";
	if (this._aimgs){
		img.parentObject=itemObject;
		if (img.tagName=="IMG") img.align="absmiddle";
	img.onclick=this.onRowSelect; }
	if (!mode) this._setSrc(img,this.iconURL+this.imageArray[0]);
	td12.appendChild(img); img.style.padding=0; img.style.margin=0;
	if (this.timgen)
	{  
	td12.style.width=img.style.width=this.def_img_x; img.style.height=this.def_img_y; }
	else
	{
                img.style.width="0px"; img.style.height="0px";
                if (_isOpera || window._KHTMLrv )    td12.style.display="none";
        }
        
        
        var td2=document.createElement('td');
        td2.className="dhxTextCell standartTreeRow";
        
        itemObject.span=document.createElement('span');
        itemObject.span.className="standartTreeRow";
        if (this.mlitems) {
        	itemObject.span.style.width=this.mlitems;
        	//	if (!_isIE)
        	itemObject.span.style.display="block";
        }
        else td2.noWrap=true;
        if (dhx4.isIE8) td2.style.width="99999px";
        else if (!window._KHTMLrv) td2.style.width="100%";
        
        //      itemObject.span.appendChild(document.createTextNode(itemObject.label));
        itemObject.span.innerHTML=itemObject.label;
        td2.appendChild(itemObject.span);
        td2.parentObject=itemObject;        td1.parentObject=itemObject;
        td2.onclick=this.onRowSelect; td1.onclick=this.onRowClick; td2.ondblclick=this.onRowClick2;
        if (this.ettip)
        	tr.title=itemObject.label;
        
        if (this.dragAndDropOff) {
        	if (this._aimgs) { this.dragger.addDraggableItem(td12,this); td12.parentObject=itemObject; }
        	this.dragger.addDraggableItem(td2,this);
        }
        
        itemObject.span.style.paddingLeft="5px";      itemObject.span.style.paddingRight="5px";   td2.style.verticalAlign="";
        td2.style.fontSize="10pt";       td2.style.cursor=this.style_pointer;
        tr.appendChild(td1);            tr.appendChild(td11);            tr.appendChild(td12);
        tr.appendChild(td2);
        tbody.appendChild(tr);
        table.appendChild(tbody);
        
        if (this.ehlt || this.checkEvent("onMouseIn") || this.checkEvent("onMouseOut")){//highlighting
		tr.onmousemove=this._itemMouseIn;
		tr[(_isIE)?"onmouseleave":"onmouseout"]=this._itemMouseOut;
	}
	return table;
};
   

/**  
*     @desc: set path to images directory
*     @param: newPath - path to images directory (related to the page with tree or absolute http url)
*     @type: public
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.setImagePath=function( newPath ){ this.imPath=newPath; this.iconURL=newPath; };
    /**
	*   @desc: set path to external images used as tree icons
	*   @type: public
	*   @param: path - url (or relative path) of images folder with closing "/"
	*   @topic: 0,7
	*/
	dhtmlXTreeObject.prototype.setIconPath=function(path){
		this.iconURL=path;
	}	   

//#__pro_feature:01112006{
//#child_calc:01112006{

/**
*     @desc: return count of leafs
*     @param: itemNode -  node object
*     @type: private
*     @edition: Professional
*     @topic: 4
*/
   dhtmlXTreeObject.prototype._getLeafCount=function(itemNode){
      var a=0;
      for (var b=0; b<itemNode.childsCount; b++)
         if (itemNode.childNodes[b].childsCount==0) a++;
      return a;
   }

/**
*     @desc: get value of child counter (child counter must be enabled)
*     @type: private
*     @param: itemId - id of selected item
*     @edition: Professional
*     @return: counter value (related to counter mode)
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getChildCounterValue=function(itemId){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      if ((temp.unParsed)||((!temp.XMLload)&&(this.XMLsource)))
      return temp._acc
      switch(this.childCalc)
      {
         case 1: return temp.childsCount; break;
         case 2: return this._getLeafCount(temp); break;
         case 3: return temp._acc; break;
         case 4: return temp._acc; break;
      }
   }

  /**
*     @desc: fix node child counter
*     @param: itemNode -  node object
*     @type: private
*     @edition: Professional
*     @topic: 4
*/
   dhtmlXTreeObject.prototype._fixChildCountLabel=function(itemNode,index){
      if (this.childCalc==null) return;
      if ((itemNode.unParsed)||((!itemNode.XMLload)&&(this.XMLsource)))
      {
         if (itemNode._acc)
         itemNode.span.innerHTML=itemNode.label+this.htmlcA+itemNode._acc+this.htmlcB;
         else
         itemNode.span.innerHTML=itemNode.label;

      return;
      }

      switch(this.childCalc){
         case 1:
            if (itemNode.childsCount!=0)
               itemNode.span.innerHTML=itemNode.label+this.htmlcA+itemNode.childsCount+this.htmlcB;
            else itemNode.span.innerHTML=itemNode.label;
            break;
         case 2:
            var z=this._getLeafCount(itemNode);
            if (z!=0)
               itemNode.span.innerHTML=itemNode.label+this.htmlcA+z+this.htmlcB;
            else itemNode.span.innerHTML=itemNode.label;
            break;
         case 3:
            if (itemNode.childsCount!=0)
               {
               var bcc=0;
               for (var a=0; a<itemNode.childsCount; a++)   {
                  if (!itemNode.childNodes[a]._acc) itemNode.childNodes[a]._acc=0;
                  bcc+=itemNode.childNodes[a]._acc*1;      }
                  bcc+=itemNode.childsCount*1;

               itemNode.span.innerHTML=itemNode.label+this.htmlcA+bcc+this.htmlcB;
               itemNode._acc=bcc;
               }
            else { itemNode.span.innerHTML=itemNode.label;   itemNode._acc=0; }
            if ((itemNode.parentObject)&&(itemNode.parentObject!=this.htmlNode))
               this._fixChildCountLabel(itemNode.parentObject);
            break;
         case 4:
            if (itemNode.childsCount!=0)
               {
               var bcc=0;
               for (var a=0; a<itemNode.childsCount; a++)   {
                  if (!itemNode.childNodes[a]._acc) itemNode.childNodes[a]._acc=1;
                  bcc+=itemNode.childNodes[a]._acc*1;      }

               itemNode.span.innerHTML=itemNode.label+this.htmlcA+bcc+this.htmlcB;
               itemNode._acc=bcc;
               }
            else { itemNode.span.innerHTML=itemNode.label;   itemNode._acc=1; }
            if ((itemNode.parentObject)&&(itemNode.parentObject!=this.htmlNode))
               this._fixChildCountLabel(itemNode.parentObject);
            break;
      }
   }

/**
*     @desc: set children calculation mode
*     @param: mode - mode name as string . Possible values: child - children, no recursive; leafs - children without subchildren, no recursive;  ,childrec - children, recursive; leafsrec - children without subchildren, recursive; disabled (disabled by default)
*     @type: public
*     @edition: Professional
*     @topic: 0
*/ 
   dhtmlXTreeObject.prototype.setChildCalcMode=function( mode ){
      switch(mode){
         case "child": this.childCalc=1; break;
         case "leafs": this.childCalc=2; break;
         case "childrec": this.childCalc=3; break;
         case "leafsrec": this.childCalc=4; break;
         case "disabled": this.childCalc=null; break;
         default: this.childCalc=4;
      }
    }
/**
*     @desc: set children calculation prefix and postfix
*     @param: htmlA - postfix ([ - by default)
*     @param: htmlB - postfix (] - by default)
*     @type: public
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.setChildCalcHTML=function( htmlA,htmlB ){
      this.htmlcA=htmlA;      this.htmlcB=htmlB;
    }
//#}
//#}

/**
*     @desc: set function called when tree node selected
*     @param: (function) func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onRightClick
*     @depricated: use grid.attachEvent("onRightClick",func); instead
*     @eventdesc:  Event occurs after right mouse button was clicked.
         Assigning this handler can disable default context menu, and incompattible with dhtmlXMenu integration.
*     @eventparam: (string) ID of clicked item
*     @eventparam: (object) event object
*/
   dhtmlXTreeObject.prototype.setOnRightClickHandler=function(func){  this.attachEvent("onRightClick",func);   };

/**
*     @desc: set function called when tree node clicked, also can be forced to call from API
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onClick
*     @depricated: use grid.attachEvent("onClick",func); instead
*     @eventdesc: Event raises immideatly after text part of item in tree was clicked, but after default onClick functionality was processed.
              Richt mouse button click can be catched by onRightClick event handler.
*     @eventparam:  ID of clicked item
*     @eventparam:  ID of previously selected item
*/
   dhtmlXTreeObject.prototype.setOnClickHandler=function(func){  this.attachEvent("onClick",func);  };

/**
*     @desc: set function called when tree node selected or unselected, include any select change caused by any functionality
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onSelect
*     @depricated: use grid.attachEvent("onSelect",func); instead
*     @eventdesc: Event raises immideatly after selection in tree was changed
*     @eventparam:  selected item ID ( list of IDs in case of multiselection)
*/
   dhtmlXTreeObject.prototype.setOnSelectStateChange=function(func){  this.attachEvent("onSelect",func); };


/**
*     @desc: enables dynamic loading from XML
*     @type: public
*     @param: filePath - name of script returning XML; in case of virtual loading - user defined function
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.setXMLAutoLoading=function(filePath){  this.XMLsource=filePath; };

   /**
*     @desc: set function called before checkbox checked/unchecked
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onCheck
*     @depricated: use tree.attachEvent("onCheck",func); instead
*     @eventdesc: Event raises right before item in tree was checked/unchecked. can be canceled (return false from event handler)
*     @eventparam: ID of item which will be checked/unchecked
*     @eventparam: Current checkbox state. 1 - item checked, 0 - item unchecked.
*		@eventreturn: true - confirm changing checked state; false - deny chaning checked state;
*/
   dhtmlXTreeObject.prototype.setOnCheckHandler=function(func){ this.attachEvent("onCheck",func);  };


/**
*     @desc: set function called before tree node opened/closed
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event:  onOpen
*     @depricated: use grid.attachEvent("onOpenStart",func); instead
*     @eventdesc: Event raises immideatly after item in tree got command to open/close , and before item was opened//closed. Event also raised for unclosable nodes and nodes without open/close functionality - in that case result of function will be ignored.
            Event does not occur if node was opened by dhtmlXtree API.
*     @eventparam: ID of node which will be opened/closed
*     @eventparam: Current open state of tree item. 0 - item has not children, -1 - item closed, 1 - item opened.
*     @eventreturn: true - confirm opening/closing; false - deny opening/closing;
*/
   dhtmlXTreeObject.prototype.setOnOpenHandler=function(func){  this.attachEvent("onOpenStart",func);   };
/**
*     @desc: set function called before tree node opened/closed
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event:  onOpenStart
*     @depricated: use grid.attachEvent("onOpenStart",func); instead
*     @eventdesc: Event raises immideatly after item in tree got command to open/close , and before item was opened//closed. Event also raised for unclosable nodes and nodes without open/close functionality - in that case result of function will be ignored.
            Event not raised if node opened by dhtmlXtree API.
*     @eventparam: ID of node which will be opened/closed
*     @eventparam: Current open state of tree item. 0 - item has not children, -1 - item closed, 1 - item opened.
*     @eventreturn: true - confirm opening/closing; false - deny opening/closing;
*/
   dhtmlXTreeObject.prototype.setOnOpenStartHandler=function(func){  this.attachEvent("onOpenStart",func);    };

/**
*     @desc: set function called after tree node opened/closed
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event:  onOpenEnd
*     @depricated: use grid.attachEvent("onOpenEnd",func); instead
*     @eventdesc: Event raises immideatly after item in tree was opened//closed. Event also raised for unclosable nodes and nodes without open/close functionality - in that case result of function will be ignored.
            Event not raised if node opened by dhtmlXtree API.
*     @eventparam: ID of node which will be opened/closed
*     @eventparam: Current open state of tree item. 0 - item has not children, -1 - item closed, 1 - item opened.
*/
   dhtmlXTreeObject.prototype.setOnOpenEndHandler=function(func){  this.attachEvent("onOpenEnd",func);  };

   /**
*     @desc: set function called when tree node double clicked
*     @param: func - event handling function
*     @type: public
*     @topic: 0,7
*     @event: onDblClick
*     @depricated: use grid.attachEvent("onDblClick",func); instead
*     @eventdesc: Event raised immideatly after item in tree was doubleclicked, before default onDblClick functionality was processed.
         Beware using both onClick and onDblClick events, because component can  generate onClick event before onDblClick event while doubleclicking item in tree.
         ( that behavior depend on used browser )
*     @eventparam:  ID of item which was doubleclicked
*     @eventreturn:  true - confirm opening/closing; false - deny opening/closing;
*/
   dhtmlXTreeObject.prototype.setOnDblClickHandler=function(func){ this.attachEvent("onDblClick",func);   };









   /**
*     @desc: expand target node and all sub nodes
*     @type: public
*     @param: itemId - node id
*     @topic: 4
*/
   dhtmlXTreeObject.prototype.openAllItems=function(itemId)
   {
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      this._xopenAll(temp);
   };
   
/**
*     @desc: return open/close state
*     @type: public
*     @param: itemId - node id
*     @return: -1 - close, 1 - opened, 0 - node doesn't have children
*     @topic: 4
*/   
   dhtmlXTreeObject.prototype.getOpenState=function(itemId){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return "";
      return this._getOpenState(temp);
   };

/**  
*     @desc: collapse target node and all sub nodes
*     @type: public
*     @param: itemId - node id
*     @topic: 4  
*/
   dhtmlXTreeObject.prototype.closeAllItems=function(itemId)
   {
        if (itemId===window.undefined) itemId=this.rootId;
        
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      this._xcloseAll(temp);

//nb:solves standard doctype prb in IE
         this.allTree.childNodes[0].border = "1";
       this.allTree.childNodes[0].border = "0";

   };
   
   
/**
*     @desc: set user data for target node
*     @type: public
*     @param: itemId - target node id
*     @param: name - key for user data
*     @param: value - user data value
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.setUserData=function(itemId,name,value){
      var sNode=this._globalIdStorageFind(itemId,0,true);
         if (!sNode) return;
         if(name=="hint")
			 sNode.htmlNode.childNodes[0].childNodes[0].title=value;
            if (typeof(sNode.userData["t_"+name])=="undefined"){
                 if (!sNode._userdatalist) sNode._userdatalist=name;
                else sNode._userdatalist+=","+name;
            }
            sNode.userData["t_"+name]=value;
   };
   
/**  
*     @desc: get user data from target node
*     @type: public
*     @param: itemId - target node id
*     @param: name - key for user data
*     @return: value of user data
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.getUserData=function(itemId,name){
      var sNode=this._globalIdStorageFind(itemId,0,true);
      if (!sNode) return;
      return sNode.userData["t_"+name];
   };




/**
*     @desc: get node color (text color)
*     @param: itemId - id of node
*     @type: public
*     @return: color of node (empty string for default color);
*     @topic: 6  
*/   
   dhtmlXTreeObject.prototype.getItemColor=function(itemId)
   {
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;

      var res= new Object();
      if (temp.acolor) res.acolor=temp.acolor;
      if (temp.scolor) res.scolor=temp.scolor;      
      return res;
   };
/**  
*     @desc: set node text color
*     @param: itemId - id of node
*     @param: defaultColor - node color
*     @param: selectedColor - selected node color
*     @type: public
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.setItemColor=function(itemId,defaultColor,selectedColor)
   {
      if ((itemId)&&(itemId.span))
         var temp=itemId;
      else
         var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
         else {
         if (temp.i_sel)
            {  if (selectedColor || defaultColor) temp.span.style.color=selectedColor || defaultColor; }
         else
            {  if (defaultColor) temp.span.style.color=defaultColor;  }

         if (selectedColor) temp.scolor=selectedColor;
         if (defaultColor) temp.acolor=defaultColor;
         }
   };

/**
*     @desc: return node text
*     @param: itemId - id of node
*     @type: public
*     @return: text of item (with HTML formatting, if any)
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.getItemText=function(itemId)
   {
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      return(temp.htmlNode.childNodes[0].childNodes[0].childNodes[3].childNodes[0].innerHTML);
   };
/**  
*     @desc: return parent item id
*     @param: itemId - id of node
*     @type: public
*     @return: id of parent item
*     @topic: 4
*/         
   dhtmlXTreeObject.prototype.getParentId=function(itemId)
   {
      var temp=this._globalIdStorageFind(itemId);
      if ((!temp)||(!temp.parentObject)) return "";
      return temp.parentObject.id;
   };



/**  
*     @desc: change item id
*     @type: public
*     @param: itemId - old node id
*     @param: newItemId - new node id        
*     @topic: 4
*/    
   dhtmlXTreeObject.prototype.changeItemId=function(itemId,newItemId)
   {
   	if (itemId==newItemId) return;
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
        temp.id=newItemId;
        temp.span.contextMenuId=newItemId;
        this._idpull[newItemId]=this._idpull[itemId];
        delete this._idpull[itemId];
   };


/**
*     @desc: mark selected item as cut
*     @type: public
*     @topic: 2  
*/    
   dhtmlXTreeObject.prototype.doCut=function(){
      if (this.nodeCut) this.clearCut();
      this.nodeCut=(new Array()).concat(this._selected);
        for (var i=0; i<this.nodeCut.length; i++){
          var tempa=this.nodeCut[i];
            tempa._cimgs=new Array();
          tempa._cimgs[0]=tempa.images[0];
          tempa._cimgs[1]=tempa.images[1];
          tempa._cimgs[2]=tempa.images[2];
          tempa.images[0]=tempa.images[1]=tempa.images[2]=this.cutImage;
          this._correctPlus(tempa);
        }
   };

/**
*     @desc: insert previously cut branch
*     @param: itemId - id of new parent node
*     @type: public
*     @topic: 2  
*/    
   dhtmlXTreeObject.prototype.doPaste=function(itemId){
      var tobj=this._globalIdStorageFind(itemId);
      if (!tobj) return 0;
        for (var i=0; i<this.nodeCut.length; i++){
               if (this._checkPNodes(tobj,this.nodeCut[i])) continue;
                this._moveNode(this.nodeCut[i],tobj);
               }
      this.clearCut();
   };

/**  
*     @desc: clear cut
*     @type: public
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype.clearCut=function(){
      for (var i=0; i<this.nodeCut.length; i++)
         {
          var tempa=this.nodeCut[i];
          tempa.images[0]=tempa._cimgs[0];
          tempa.images[1]=tempa._cimgs[1];
          tempa.images[2]=tempa._cimgs[2];
          this._correctPlus(tempa);
         }
          this.nodeCut=new Array();
   };
   


   /**  
*     @desc: move node with subnodes
*     @type: private
*     @param: itemObject - moved node object
*     @param: targetObject - new parent node
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype._moveNode=function(itemObject,targetObject){
//#__pro_feature:01112006{
//#complex_move:01112006{
      var mode=this.dadmodec;
      if (mode==1)
        {
            var z=targetObject;
         if (this.dadmodefix<0)
         {

                while (true){
            z=this._getPrevNode(z);
            if ((z==-1)) { z=this.htmlNode; break; }
                if ((z.tr==0)||(z.tr.style.display=="")||(!z.parentObject)) break;
                }

                var nodeA=z;
                var nodeB=targetObject;

            }
            else
            {
				if ((z.tr)&&(z.tr.nextSibling)&&(z.tr.nextSibling.nodem)&&(this._getOpenState(z)<1)){
  				 	z = z.tr.nextSibling.nodem;
				}
           		else{
            	   z=this._getNextNode(z);
				   if ((z==-1)) z=this.htmlNode; 
				}
				
                var nodeB=z;
                var nodeA=targetObject;
            }


            if (this._getNodeLevel(nodeA,0)>this._getNodeLevel(nodeB,0))
                {
                if (!this.dropLower)
                    return this._moveNodeTo(itemObject,nodeA.parentObject);
                else
                    if  (nodeB.id!=this.rootId)
                        return this._moveNodeTo(itemObject,nodeB.parentObject,nodeB);
                    else
                        return this._moveNodeTo(itemObject,this.htmlNode,null);
                }
            else
                {
                return this._moveNodeTo(itemObject,nodeB.parentObject,nodeB);
                }


      }
      else
//#}
//#}
	  return this._moveNodeTo(itemObject,targetObject);

   }

   /**
*     @desc: fix order of nodes in collection
*     @type: private
*     @param: target - parent item node
*     @param: zParent - before node
*     @edition: Professional
*     @topic: 2
*/

dhtmlXTreeObject.prototype._fixNodesCollection=function(target,zParent){
      var flag=0; var icount=0;
      var Nodes=target.childNodes;
      var Count=target.childsCount-1;

      if (zParent==Nodes[Count]) return;
      for (var i=0; i<Count; i++)
         if (Nodes[i]==Nodes[Count]) {  Nodes[i]=Nodes[i+1]; Nodes[i+1]=Nodes[Count]; }

//         Count=target.childsCount;
      for (var i=0; i<Count+1; i++)      
         {
         if (flag) { 
            var temp=Nodes[i];
            Nodes[i]=flag; 
            flag=temp; 
               }
         else 
         if (Nodes[i]==zParent) {   flag=Nodes[i]; Nodes[i]=Nodes[Count];  }
         }
   };
   
/**  
*     @desc: recreate branch
*     @type: private
*     @param: itemObject - moved node object
*     @param: targetObject - new parent node
*     @param: level - top level flag
*     @param: beforeNode - node for sibling mode
*     @mode: mode - DragAndDrop mode (0 - as child, 1 as sibling)
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype._recreateBranch=function(itemObject,targetObject,beforeNode,level){
    var i; var st="";
    if (beforeNode){
    for (i=0; i<targetObject.childsCount; i++)
        if (targetObject.childNodes[i]==beforeNode) break;

    if (i!=0)
        beforeNode=targetObject.childNodes[i-1];
    else{
        st="TOP";
        beforeNode="";
        }
    }

   var t2=this._onradh; this._onradh=null;
   var newNode=this._attachChildNode(targetObject,itemObject.id,itemObject.label,0,itemObject.images[0],itemObject.images[1],itemObject.images[2],st,0,beforeNode);

   //copy user data
   newNode._userdatalist=itemObject._userdatalist;
   newNode.userData=itemObject.userData.clone();
   if(itemObject._attrs){
	   newNode._attrs={};
	   for(var attr in itemObject._attrs)
		   newNode._attrs[attr] = itemObject._attrs[attr];
	}

   newNode.XMLload=itemObject.XMLload;
   if (t2){
   	this._onradh=t2; this._onradh(newNode.id); }

//#__pro_feature:01112006{
//#smart_parsing:01112006{
   //copy unparsed chunk
      if (itemObject.treeNod.dpcpy) itemObject.treeNod._globalIdStorageFind(itemObject.id);
      else newNode.unParsed=itemObject.unParsed;
      this._correctPlus(newNode);
      //this._correctLine(newNode);
   
//#}
//#}
   for (var i=0; i<itemObject.childsCount; i++)
      this._recreateBranch(itemObject.childNodes[i],newNode,0,1);

//#__pro_feature:01112006{
//#child_calc:01112006{
      if ((!level)&&(this.childCalc)) { this._redrawFrom(this,targetObject);  }
//#}
//#}
   return newNode;
}

/**
*     @desc: move single node
*     @type: private
*     @param: itemObject - moved node object
*     @param: targetObject - new parent node
*     @mode: mode - DragAndDrop mode (0 - as child, 1 as sibling)
*     @topic: 2
*/
   dhtmlXTreeObject.prototype._moveNodeTo=function(itemObject,targetObject,beforeNode){
    //return;
    if   (itemObject.treeNod._nonTrivialNode)
        return itemObject.treeNod._nonTrivialNode(this,targetObject,beforeNode,itemObject);

	if (this._checkPNodes(targetObject,itemObject))
   		return false;
                           		
    if    (targetObject.mytype)
       var framesMove=(itemObject.treeNod.lWin!=targetObject.lWin);
    else
          var framesMove=(itemObject.treeNod.lWin!=targetObject.treeNod.lWin);

   if (!this.callEvent("onDrag",[itemObject.id,targetObject.id,(beforeNode?beforeNode.id:null),itemObject.treeNod,targetObject.treeNod])) return false;
      if ((targetObject.XMLload==0)&&(this.XMLsource))
         {
         targetObject.XMLload=1;
            this._loadDynXML(targetObject.id);
         }
	this.openItem(targetObject.id);

   var oldTree=itemObject.treeNod;
   var c=itemObject.parentObject.childsCount;
   var z=itemObject.parentObject;
   

   if ((framesMove)||(oldTree.dpcpy)) {//interframe drag flag
        var _otiid=itemObject.id;
      itemObject=this._recreateBranch(itemObject,targetObject,beforeNode);
        if (!oldTree.dpcpy) oldTree.deleteItem(_otiid);
        }
   else
      {
	
      var Count=targetObject.childsCount; var Nodes=targetObject.childNodes;
      	   	if (Count==0) targetObject._open=true;
      		oldTree._unselectItem(itemObject);
           Nodes[Count]=itemObject;
            itemObject.treeNod=targetObject.treeNod;
            targetObject.childsCount++;         
			
            var tr=this._drawNewTr(Nodes[Count].htmlNode);

            if (!beforeNode)
               {
                  targetObject.htmlNode.childNodes[0].appendChild(tr);
               if (this.dadmode==1) this._fixNodesCollection(targetObject,beforeNode);
               }
            else
               {
               targetObject.htmlNode.childNodes[0].insertBefore(tr,beforeNode.tr);
               this._fixNodesCollection(targetObject,beforeNode);
               Nodes=targetObject.childNodes;
               }

			
         }

            if ((!oldTree.dpcpy)&&(!framesMove))   {
                var zir=itemObject.tr;

                if ((document.all)&&(navigator.appVersion.search(/MSIE\ 5\.0/gi)!=-1))
                    {
                    window.setTimeout(function() { zir.parentNode.removeChild(zir); } , 250 );
                    }
                else   //if (zir.parentNode) zir.parentNode.removeChild(zir,true);

                itemObject.parentObject.htmlNode.childNodes[0].removeChild(itemObject.tr);

                //itemObject.tr.removeNode(true);
            if ((!beforeNode)||(targetObject!=itemObject.parentObject)){
               for (var i=0; i<z.childsCount; i++){
                  if (z.childNodes[i].id==itemObject.id) {
                  z.childNodes[i]=0;
                  break;            }}}
               else z.childNodes[z.childsCount-1]=0;

            oldTree._compressChildList(z.childsCount,z.childNodes);
            z.childsCount--;
            }


      if ((!framesMove)&&(!oldTree.dpcpy)) {
       itemObject.tr=tr;
      tr.nodem=itemObject;
      itemObject.parentObject=targetObject;

      if (oldTree!=targetObject.treeNod) {
	    if(itemObject.treeNod._registerBranch(itemObject,oldTree)) return;      this._clearStyles(itemObject);  this._redrawFrom(this,itemObject.parentObject);
		if(this._onradh) this._onradh(itemObject.id);
		   };

      this._correctPlus(targetObject);
      this._correctLine(targetObject);

      this._correctLine(itemObject);
      this._correctPlus(itemObject);

         //fix target siblings
      if (beforeNode)
      {

         this._correctPlus(beforeNode);
         //this._correctLine(beforeNode);
      }
      else 
      if (targetObject.childsCount>=2)
      {

         this._correctPlus(Nodes[targetObject.childsCount-2]);
         this._correctLine(Nodes[targetObject.childsCount-2]);
      }
      
      this._correctPlus(Nodes[targetObject.childsCount-1]);
      //this._correctLine(Nodes[targetObject.childsCount-1]);


      if (this.tscheck) this._correctCheckStates(targetObject);
      if (oldTree.tscheck) oldTree._correctCheckStates(z);

      }

      //fix source parent

      if (c>1) { oldTree._correctPlus(z.childNodes[c-2]);
               oldTree._correctLine(z.childNodes[c-2]);
               }


//      if (z.childsCount==0)
          oldTree._correctPlus(z);
            oldTree._correctLine(z);

//#__pro_feature:01112006{
//#child_calc:01112006{
      this._fixChildCountLabel(targetObject);
      oldTree._fixChildCountLabel(z);
//#}
//#}
      this.callEvent("onDrop",[itemObject.id,targetObject.id,(beforeNode?beforeNode.id:null),oldTree,targetObject.treeNod]);
      return itemObject.id;
   };

   

/**
*     @desc: recursive set default styles for node
*     @type: private
*     @param: itemObject - target node object
*     @topic: 6  
*/   
   dhtmlXTreeObject.prototype._clearStyles=function(itemObject){
   		if (!itemObject.htmlNode) return; //some weird case in SRND mode
         var td1=itemObject.htmlNode.childNodes[0].childNodes[0].childNodes[1];
         var td3=td1.nextSibling.nextSibling;

         itemObject.span.innerHTML=itemObject.label;
		 itemObject.i_sel=false;

   		 if (itemObject._aimgs)
	         this.dragger.removeDraggableItem(td1.nextSibling);

         if (this.checkBoxOff) {
		 	td1.childNodes[0].style.display="";
			td1.childNodes[0].onclick=this.onCheckBoxClick;
			this._setSrc(td1.childNodes[0],this.imPath+this.checkArray[itemObject.checkstate]);
			}
         else td1.style.display="none";
         td1.childNodes[0].treeNod=this;

         this.dragger.removeDraggableItem(td3);
         if (this.dragAndDropOff) this.dragger.addDraggableItem(td3,this);
		 if (this._aimgs) this.dragger.addDraggableItem(td1.nextSibling,this);
		 		 
         td3.childNodes[0].className="standartTreeRow";
         td3.parentNode.className = "";
         td3.onclick=this.onRowSelect; td3.ondblclick=this.onRowClick2;
         td1.previousSibling.onclick=this.onRowClick;

         this._correctLine(itemObject);
         this._correctPlus(itemObject);
         for (var i=0; i<itemObject.childsCount; i++) this._clearStyles(itemObject.childNodes[i]); 

   };
/**
*     @desc: register node and all children nodes
*     @type: private
*     @param: itemObject - node object
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype._registerBranch=function(itemObject,oldTree){
      if (oldTree) oldTree._globalIdStorageSub(itemObject.id);
      itemObject.id=this._globalIdStorageAdd(itemObject.id,itemObject);
      itemObject.treeNod=this;
         for (var i=0; i<itemObject.childsCount; i++)
            this._registerBranch(itemObject.childNodes[i],oldTree);
      return 0;
   };

   
/**  
*     @desc: enable three state checkboxes
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.enableThreeStateCheckboxes=function(mode) { this.tscheck=dhx4.s2b(mode); };


/**
*     @desc: set function called when mouse is over tree node
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onMouseIn
*     @depricated: use grid.attachEvent("onMouseIn",func); instead
*     @eventdesc: Event raised immideatly after mouse started moving over item
*     @eventparam:  ID of item
*/
   dhtmlXTreeObject.prototype.setOnMouseInHandler=function(func){
    	this.ehlt=true;
   		this.attachEvent("onMouseIn",func);
	};

/**
*     @desc: set function called when mouse is out of tree node
*     @param: func - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event: onMouseOut
*     @depricated: use grid.attachEvent("onMouseOut",func); instead
*     @eventdesc: Event raised immideatly after mouse moved out of item
*     @eventparam:  ID of clicked item
*/
   dhtmlXTreeObject.prototype.setOnMouseOutHandler=function(func){
		this.ehlt=true;
   		this.attachEvent("onMouseOut",func);
	};





//#__pro_feature:01112006{
/**
*     @desc: enable drag without removing (copy instead of move)
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @edition:Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableMercyDrag=function(mode){ this.dpcpy=dhx4.s2b(mode); };
//#}



/**
*     @desc: enable tree images
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @topic: 0  
*/         
   dhtmlXTreeObject.prototype.enableTreeImages=function(mode) { this.timgen=dhx4.s2b(mode); };
   

   
/**
*     @desc: enable mode with fixed tables (looks better, but has no horisontal scrollbar)
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: private
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.enableFixedMode=function(mode) { this.hfMode=dhx4.s2b(mode); };
   
/**  
*     @desc: show/hide checkboxes (all checkboxes in tree)
*     @type: public
*     @param: mode - true/false
*     @param: hidden - if set to true, checkboxes not rendered but can be shown by showItemCheckbox
*     @topic: 0  
*/
   dhtmlXTreeObject.prototype.enableCheckBoxes=function(mode, hidden){ this.checkBoxOff=dhx4.s2b(mode); this.cBROf=(!(this.checkBoxOff||dhx4.s2b(hidden))); 
   	};
/**
*     @desc: set default images for nodes (must be called before XML loading)
*     @type: public
*     @param: a0 - image for node without children;
*     @param: a1 - image for closed node;
*     @param: a2 - image for opened node                  
*     @topic: 6  
*/
   dhtmlXTreeObject.prototype.setStdImages=function(image1,image2,image3){
                  this.imageArray[0]=image1; this.imageArray[1]=image2; this.imageArray[2]=image3;};

/**
*     @desc: enable/disable tree lines (parent-child threads)
*     @type: public
*     @param: mode - enable/disable tree lines
*     @topic: 6
*/                  
   dhtmlXTreeObject.prototype.enableTreeLines=function(mode){
      this.treeLinesOn=dhx4.s2b(mode);
   }

/**
*     @desc: set images used for parent-child threads drawing (lines, plus, minus)
*     @type: public
*     @param: arrayName - name of array: plus, minus
*     @param: image1 - line crossed image
*     @param: image2 - image with top line
*     @param: image3 - image with bottom line
*     @param: image4 - image without line
*     @param: image5 - single root image
*     @topic: 6
*/      
   dhtmlXTreeObject.prototype.setImageArrays=function(arrayName,image1,image2,image3,image4,image5){
      switch(arrayName){
      case "plus": this.plusArray[0]=image1; this.plusArray[1]=image2; this.plusArray[2]=image3; this.plusArray[3]=image4; this.plusArray[4]=image5; break;
      case "minus": this.minusArray[0]=image1; this.minusArray[1]=image2; this.minusArray[2]=image3; this.minusArray[3]=image4;  this.minusArray[4]=image5; break;
      }
   };

/**  
*     @desc: expand node
*     @param: itemId - id of node
*     @type: public
*     @topic: 4
*/ 
   dhtmlXTreeObject.prototype.openItem=function(itemId){
     this.skipLock = true;
	   var temp=this._globalIdStorageFind(itemId);
	   if (!temp) return 0;
	   else return this._openItem(temp);
	   this.skipLock = false;
   };

/**  
*     @desc: expand node
*     @param: item - tree node object
*     @type: private
*     @editing: pro
*     @topic: 4  
*/ 
   dhtmlXTreeObject.prototype._openItem=function(item){
   		   var state=this._getOpenState(item);
		   if ((state<0)||(((this.XMLsource)&&(!item.XMLload)))){
	           if    (!this.callEvent("onOpenStart",[item.id,state])) return 0;
	           this._HideShow(item,2);
				   if    (this.checkEvent("onOpenEnd")){ 
						   if (this.onXLE==this._epnFHe) this._epnFHe(this,item.id,true);
	                       if (!this.xmlstate || !this.XMLsource)
	                       		this.callEvent("onOpenEnd",[item.id,this._getOpenState(item)]);
	                        else{
	                            this._oie_onXLE.push(this.onXLE);
	                            this.onXLE=this._epnFHe;
	                            }
							}
			   } else if (this._srnd) this._HideShow(item,2);
           if (item.parentObject && !this._skip_open_parent) this._openItem(item.parentObject);
   };
   
/**  
*     @desc: collapse node
*     @param: itemId - id of node
*     @type: public
*     @topic: 4  
*/
   dhtmlXTreeObject.prototype.closeItem=function(itemId){
	   if (this.rootId==itemId) return 0;
	   this.skipLock = true;
	   var temp=this._globalIdStorageFind(itemId);
	   if (!temp) return 0;
	   if (temp.closeble)
		   this._HideShow(temp,1);
	   this.skipLock = false;
   };
   
   

   
   
   
   
   

   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
      
/**
*     @desc: get node level (position in hierarchy)
*     @param: itemId - id of node
*     @type: public
*     @return: node level (0 if no such item in hierarchy - probably super root)
*     @topic: 4
*/
   dhtmlXTreeObject.prototype.getLevel=function(itemId){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      return this._getNodeLevel(temp,0);
   };
   
      

/**  
*     @desc: prevent node from closing
*     @param: itemId - id of node
*     @param: flag -  if 0 - node can't be closed, else node can be closed
*     @type: public
*     @topic: 4  
*/ 
   dhtmlXTreeObject.prototype.setItemCloseable=function(itemId,flag)
   {
      flag=dhx4.s2b(flag);
      if ((itemId)&&(itemId.span)) 
         var temp=itemId;
      else      
         var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
         temp.closeble=flag;
   };

   /**  
*     @desc: recursive function used for node level calculation
*     @param: itemObject - pointer to node object
*     @param: count - counter of levels        
*     @type: private
*     @topic: 4  
*/   
   dhtmlXTreeObject.prototype._getNodeLevel=function(itemObject,count){
      if (itemObject.parentObject) return this._getNodeLevel(itemObject.parentObject,count+1);
      return(count);
   };
   
   /**  
*     @desc: return number of children
*     @param: itemId - id of node
*     @type: public
*     @return: number of child items for loaded branches; true - for not loaded branches
*     @topic: 4
*/
   dhtmlXTreeObject.prototype.hasChildren=function(itemId){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      else 
         {
            if ( (this.XMLsource)&&(!temp.XMLload) ) return true;
            else 
               return temp.childsCount;
         };
   };
   

   /**
*     @desc: get number of leafs (nodes without children)
*     @param: itemNode -  node object
*     @type: private
*     @edition: Professional
*     @topic: 4
*/
   dhtmlXTreeObject.prototype._getLeafCount=function(itemNode){
      var a=0;
      for (var b=0; b<itemNode.childsCount; b++)
         if (itemNode.childNodes[b].childsCount==0) a++;
      return a;
   }

   
/**
*     @desc: set new node text (HTML allowed)
*     @param: itemId - id of node
*     @param: newLabel - node text
*     @param: newTooltip - (optional)tooltip for the node
*     @type: public
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.setItemText=function(itemId,newLabel,newTooltip)
   {
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
      temp.label=newLabel;
      temp.span.innerHTML=newLabel;
//#__pro_feature:01112006{
//#child_calc:01112006{
        if (this.childCalc) this._fixChildCountLabel(temp);
//#}
//#}
	      temp.span.parentNode.parentNode.title=newTooltip||"";
   };

/**
*     @desc: get item's tooltip
*     @param: itemId - id of node
*     @type: public
*     @topic: 6
*/
    dhtmlXTreeObject.prototype.getItemTooltip=function(itemId){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return "";
	  return (temp.span.parentNode.parentNode._dhx_title||temp.span.parentNode.parentNode.title||"");
   };

/**  
*     @desc: refresh tree branch from xml (XML with child nodes rerequested from server)
*     @param: itemId - id of node, if not defined tree super root used.
*     @type: public
*     @topic: 6  
*/
   dhtmlXTreeObject.prototype.refreshItem=function(itemId){
      if (!itemId) itemId=this.rootId;
      var temp=this._globalIdStorageFind(itemId);
	  this._dynDeleteBranches[itemId] = (this._dynDeleteBranches[itemId]||0) + 1;
      this._loadDynXML(itemId);
   };

   /**  
*     @desc: set item images
*     @param: itemId - id of node
*     @param: image1 - node without children icon
*     @param: image2 - closed node icon          
*     @param: image3 - open node icon         
*     @type: public
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.setItemImage2=function(itemId, image1,image2,image3){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
            temp.images[1]=image2;
            temp.images[2]=image3;
            temp.images[0]=image1;
      this._correctPlus(temp);
   };
/**
*     @desc: set item icons (mostly usefull for childless nodes)
*     @param: itemId - id of node
*     @param: image1 - node without children icon or closed node icon (if image2 specified)
*     @param: image2 - open node icon (optional)        
*     @type: public
*     @topic: 6  
*/   
   dhtmlXTreeObject.prototype.setItemImage=function(itemId,image1,image2)
   {
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;
         if (image2)
         {
            temp.images[1]=image1;
            temp.images[2]=image2;
         }
         else temp.images[0]=image1;
      this._correctPlus(temp);
   };


/**
*     @desc: Returns the list of all subitems Ids from the next level of tree, separated by commas.
*     @param: itemId - id of node
*     @type: public
*     @return: list of all subitems from the next level of tree, separated by commas.
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.getSubItems =function(itemId)
   {
      var temp=this._globalIdStorageFind(itemId,0,1);
      if (!temp) return 0;
//#__pro_feature:01112006{
//#smart_parsing:01112006{
        if(temp.unParsed)
            return (this._getSubItemsXML(temp.unParsed));
//#}
//#}
      var z="";
      for (i=0; i<temp.childsCount; i++){
         if (!z) z= ""+temp.childNodes[i].id;
            else z+=this.dlmtr+temp.childNodes[i].id;

                                                         }

      return z;
   };




/**
*     @desc: Returns the list of all sub items from all next levels of tree, separated by commas.
*     @param: itemId - id of node
*     @edition: Professional
*     @type: private
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getAllScraggyItems =function(node)
   {
      var z="";
      for (var i=0; i<node.childsCount; i++)
        {
            if ((node.childNodes[i].unParsed)||(node.childNodes[i].childsCount>0))
            {
                    if (node.childNodes[i].unParsed)
                        var zb=this._getAllScraggyItemsXML(node.childNodes[i].unParsed,1);
                    else
                       var zb=this._getAllScraggyItems(node.childNodes[i])

                 if (zb)
                        if (z) z+=this.dlmtr+zb;
                        else z=zb;
         }
            else
               if (!z) z=""+node.childNodes[i].id;
             else z+=this.dlmtr+node.childNodes[i].id;
         }
          return z;
   };





/**
*     @desc: Returns the list of all children items from all next levels of tree, separated by commas.
*     @param: itemId - id of node
*     @type: private
*     @edition: Professional
*     @topic: 6
*/

   dhtmlXTreeObject.prototype._getAllFatItems =function(node)
   {
      var z="";
      for (var i=0; i<node.childsCount; i++)
        {
            if ((node.childNodes[i].unParsed)||(node.childNodes[i].childsCount>0))
            {
             if (!z) z=""+node.childNodes[i].id;
                else z+=this.dlmtr+node.childNodes[i].id;

                    if (node.childNodes[i].unParsed)
                        var zb=this._getAllFatItemsXML(node.childNodes[i].unParsed,1);
                    else
                       var zb=this._getAllFatItems(node.childNodes[i])

                 if (zb) z+=this.dlmtr+zb;
         }
         }
          return z;
   };


/**
*     @desc: Returns the list of all children items from all next levels of tree, separated by commas.
*     @param: itemId - id of node
*     @type: private
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getAllSubItems =function(itemId,z,node)
   {
      if (node) temp=node;
      else {
      var temp=this._globalIdStorageFind(itemId);
         };
      if (!temp) return 0;

      z="";
      for (var i=0; i<temp.childsCount; i++)
         {
         if (!z) z=""+temp.childNodes[i].id;
            else z+=this.dlmtr+temp.childNodes[i].id;
         var zb=this._getAllSubItems(0,z,temp.childNodes[i])

         if (zb) z+=this.dlmtr+zb;
         }

//#__pro_feature:01112006{
//#smart_parsing:01112006{
        if (temp.unParsed)
            z=this._getAllSubItemsXML(itemId,z,temp.unParsed);
//#}
//#}
          return z;
   };




   
/**  
*     @desc: select node ( and optionaly fire onselect event)
*     @type: public
*     @param: itemId - node id
*     @param: mode - If true, script function for selected node will be called.
*     @param: preserve - preserve earlier selected nodes
*     @topic: 1
*/
   dhtmlXTreeObject.prototype.selectItem=function(itemId,mode,preserve){
      mode=dhx4.s2b(mode);
         var temp=this._globalIdStorageFind(itemId);
      if ((!temp)||(!temp.parentObject)) return 0;

            if (this.XMLloadingWarning)
                temp.parentObject.openMe=1;
            else
             	this._openItem(temp.parentObject);

      //temp.onRowSelect(0,temp.htmlNode.childNodes[0].childNodes[0].childNodes[3],mode);
        var ze=null;
        if (preserve)  {
			ze=new Object; ze.ctrlKey=true;
			if (temp.i_sel) ze.skipUnSel=true;
		}
      if (mode)
         this.onRowSelect(ze,temp.htmlNode.childNodes[0].childNodes[0].childNodes[3],false);
      else
         this.onRowSelect(ze,temp.htmlNode.childNodes[0].childNodes[0].childNodes[3],true);
   };
   
/**
*     @desc: retun selected node text
*     @type: public
*     @return: text of selected node (or list of all selected nodes text if more than one selected)
*     @topic: 1
*/
   dhtmlXTreeObject.prototype.getSelectedItemText=function()
   {
        var str=new Array();
        for (var i=0; i<this._selected.length; i++) str[i]=this._selected[i].span.innerHTML;
      return (str.join(this.dlmtr));
   };




/**  
*     @desc: correct childNode list after node deleting
*     @type: private
*     @param: Count - childNodes collection length        
*     @param: Nodes - childNodes collection
*     @topic: 4  
*/   
   dhtmlXTreeObject.prototype._compressChildList=function(Count,Nodes)
   {
      Count--;
      for (var i=0; i<Count; i++)
      {
         if (Nodes[i]==0) { Nodes[i]=Nodes[i+1]; Nodes[i+1]=0;}
      };
   };
/**  
*     @desc: delete node
*     @type: private
*     @param: itemId - target node id
*     @param: htmlObject - target node object        
*     @param: skip - node unregistration mode (optional, used by private methods)
*     @topic: 2
*/      
   dhtmlXTreeObject.prototype._deleteNode=function(itemId,htmlObject,skip){
   if ((!htmlObject)||(!htmlObject.parentObject)) return 0;
   var tempos=0; var tempos2=0;
   if (htmlObject.tr.nextSibling)  tempos=htmlObject.tr.nextSibling.nodem;
   if (htmlObject.tr.previousSibling)  tempos2=htmlObject.tr.previousSibling.nodem;
   
      var sN=htmlObject.parentObject;
      var Count=sN.childsCount;
      var Nodes=sN.childNodes;
            for (var i=0; i<Count; i++)
            {
               if (Nodes[i].id==itemId) { 
               if (!skip) sN.htmlNode.childNodes[0].removeChild(Nodes[i].tr);
               Nodes[i]=0;
               break;
               }
            }
      this._compressChildList(Count,Nodes);
      if (!skip) {
        sN.childsCount--;
                 }

      if (tempos) {
      this._correctPlus(tempos);
      this._correctLine(tempos);
               }
      if (tempos2) {
      this._correctPlus(tempos2);
      this._correctLine(tempos2);
               }
      if (this.tscheck) this._correctCheckStates(sN);

      if (!skip) {
        this._globalIdStorageRecSub(htmlObject);
                 }
   };
/**
*     @desc: set state of node's checkbox
*     @type: public
*     @param: itemId - target node id
*     @param: state - checkbox state (0/1/"unsure")
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.setCheck=function(itemId,state){
      var sNode=this._globalIdStorageFind(itemId,0,1);
      if (!sNode) return;

        if (state==="unsure")
            this._setCheck(sNode,state);
        else
        {
      state=dhx4.s2b(state);
        if ((this.tscheck)&&(this.smcheck)) this._setSubChecked(state,sNode);
      else this._setCheck(sNode,state);
        }
      if (this.smcheck)
         this._correctCheckStates(sNode.parentObject);
   };

   dhtmlXTreeObject.prototype._setCheck=function(sNode,state){
   		if (!sNode) return;
        if (((sNode.parentObject._r_logic)||(this._frbtr))&&(state))
			if (this._frbtrs){
				if (this._frbtrL)   this.setCheck(this._frbtrL.id,0);
				this._frbtrL=sNode;
			} else
    	        for (var i=0; i<sNode.parentObject.childsCount; i++)
	                this._setCheck(sNode.parentObject.childNodes[i],0);

      var z=sNode.htmlNode.childNodes[0].childNodes[0].childNodes[1].childNodes[0];

      if (state=="unsure") sNode.checkstate=2;
      else if (state) sNode.checkstate=1; else sNode.checkstate=0;
      if (sNode.dscheck) sNode.checkstate=sNode.dscheck;
      this._setSrc(z,this.imPath+((sNode.parentObject._r_logic||this._frbtr)?this.radioArray:this.checkArray)[sNode.checkstate]);
   };

/**
*     @desc: change state of node's checkbox and all children checkboxes
*     @type: public
*     @param: itemId - target node id
*     @param: state - checkbox state
*     @topic: 5
*/
dhtmlXTreeObject.prototype.setSubChecked=function(itemId,state){
   var sNode=this._globalIdStorageFind(itemId);
   this._setSubChecked(state,sNode);
   this._correctCheckStates(sNode.parentObject);
}



/**  
*     @desc: change state of node's checkbox and all childnodes checkboxes
*     @type: private
*     @param: itemId - target node id
*     @param: state - checkbox state
*     @param: sNode - target node object (optional, used by private methods)
*     @topic: 5  
*/
   dhtmlXTreeObject.prototype._setSubChecked=function(state,sNode){
      state=dhx4.s2b(state);
      if (!sNode) return;
        if (((sNode.parentObject._r_logic)||(this._frbtr))&&(state))
            for (var i=0; i<sNode.parentObject.childsCount; i++)
                this._setSubChecked(0,sNode.parentObject.childNodes[i]);

//#__pro_feature:01112006{
//#smart_parsing:01112006{
      if (sNode.unParsed)
         this._setSubCheckedXML(state,sNode.unParsed)
//#}
//#}
        if (sNode._r_logic||this._frbtr)
           this._setSubChecked(state,sNode.childNodes[0]);
        else
      for (var i=0; i<sNode.childsCount; i++)
         {
             this._setSubChecked(state,sNode.childNodes[i]);
         };
      var z=sNode.htmlNode.childNodes[0].childNodes[0].childNodes[1].childNodes[0];

      if (state) sNode.checkstate=1;
      else    sNode.checkstate=0;
      if (sNode.dscheck)  sNode.checkstate=sNode.dscheck;



      this._setSrc(z,this.imPath+((sNode.parentObject._r_logic||this._frbtr)?this.radioArray:this.checkArray)[sNode.checkstate]);
   };

/**
*     @desc: get state of nodes's checkbox
*     @type: public
*     @param: itemId - target node id
*     @return: node state (0 - unchecked,1 - checked, 2 - third state)
*     @topic: 5  
*/      
   dhtmlXTreeObject.prototype.isItemChecked=function(itemId){
      var sNode=this._globalIdStorageFind(itemId);
      if (!sNode) return;      
      return   sNode.checkstate;
   };







/**
*     @desc: delete all children of node
*     @type: public
*     @param: itemId - node id
*     @topic: 2
*/
    dhtmlXTreeObject.prototype.deleteChildItems=function(itemId)
   {
      var sNode=this._globalIdStorageFind(itemId);
      if (!sNode) return;
      var j=sNode.childsCount;
      for (var i=0; i<j; i++)
      {
         this._deleteNode(sNode.childNodes[0].id,sNode.childNodes[0]);
      };
   };

/**
*     @desc: delete node
*     @type: public
*     @param: itemId - node id
*     @param: selectParent - If true parent of deleted item get selection, else no selected items leaving in tree.
*     @topic: 2  
*/      
dhtmlXTreeObject.prototype.deleteItem=function(itemId,selectParent){
    if ((!this._onrdlh)||(this._onrdlh(itemId))){
		var z=this._deleteItem(itemId,selectParent);
//#__pro_feature:01112006{
//#child_calc:01112006{
    if (z)
        this._fixChildCountLabel(z);
//#}
//#}
	}

    //nb:solves standard doctype prb in IE
      this.allTree.childNodes[0].border = "1";
      this.allTree.childNodes[0].border = "0";
}
/**
*     @desc: delete node
*     @type: private
*     @param: id - node id
*     @param: selectParent - If true parent of deleted item get selection, else no selected items leaving in tree.
*     @param: skip - unregistering mode (optional, used by private methods)        
*     @topic: 2  
*/
dhtmlXTreeObject.prototype._deleteItem=function(itemId,selectParent,skip){
      selectParent=dhx4.s2b(selectParent);
      var sNode=this._globalIdStorageFind(itemId);
      if (!sNode) return;
        var pid=this.getParentId(itemId);

      var zTemp=sNode.parentObject;
      this._deleteNode(itemId,sNode,skip);
      if(this._editCell&&this._editCell.id==itemId)
     	this._editCell = null;
      this._correctPlus(zTemp);
      this._correctLine(zTemp);

      if  ((selectParent)&&(pid!=this.rootId)) this.selectItem(pid,1);
      return    zTemp;
   };

/**
*     @desc: uregister all child nodes of target node
*     @type: private
*     @param: itemObject - node object
*     @topic: 3  
*/      
   dhtmlXTreeObject.prototype._globalIdStorageRecSub=function(itemObject){
      for(var i=0; i<itemObject.childsCount; i++)
      {
         this._globalIdStorageRecSub(itemObject.childNodes[i]);
         this._globalIdStorageSub(itemObject.childNodes[i].id);
      };
      this._globalIdStorageSub(itemObject.id);

      	  /*anti memory leaking*/
	  	var z=itemObject;
//		var par=z.span.parentNode.parentNode.childNodes;
//		par[0].parentObject=null;
//		par[1].childNodes[0].parentObject=null;
//		par[2].childNodes[0].parentObject=null;
//		par[2].childNodes[0].treeNod=null;
//		par[2].parentObject=null;
//		par[3].parentObject=null;
		z.span=null;
		z.tr.nodem=null;
		z.tr=null;
		z.htmlNode=null;
   };

/**  
*     @desc: create new node next to specified
*     @type: public
*     @param: itemId - node id
*     @param: newItemId - new node id
*     @param: itemText - new node text
*     @param: itemActionHandler - function fired on node select event (optional)
*     @param: image1 - image for node without children; (optional)
*     @param: image2 - image for closed node; (optional)
*     @param: image3 - image for opened node (optional)
*     @param: optionStr - options string (optional)            
*     @param: children - node children flag (for dynamical trees) (optional)
*     @topic: 2  
*/
   dhtmlXTreeObject.prototype.insertNewNext=function(itemId,newItemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children){
      var sNode=this._globalIdStorageFind(itemId);
      if ((!sNode)||(!sNode.parentObject)) return (0);

      var nodez=this._attachChildNode(0,newItemId,itemText,itemActionHandler,image1,image2,image3,optionStr,children,sNode);
//#__pro_feature:01112006{
//#child_calc:01112006{
      if ((!this.XMLloadingWarning)&&(this.childCalc))  this._fixChildCountLabel(sNode.parentObject);
//#}
//#}
        return nodez;
   };


   
/**
*     @desc: retun node id by index
*     @type: public
*     @param: itemId - parent node id
*     @param: index - index of node, 0 based
*     @return: node id
*     @topic: 1
*/
   dhtmlXTreeObject.prototype.getItemIdByIndex=function(itemId,index){
       var z=this._globalIdStorageFind(itemId);
       if ((!z)||(index>=z.childsCount)) return null;
          return z.childNodes[index].id;
   };

/**
*     @desc: retun child node id by index
*     @type: public
*     @param: itemId - parent node id        
*     @param: index - index of child node
*     @return: node id
*     @topic: 1
*/      
   dhtmlXTreeObject.prototype.getChildItemIdByIndex=function(itemId,index){
       var z=this._globalIdStorageFind(itemId);
       if ((!z)||(index>=z.childsCount)) return null;
          return z.childNodes[index].id;
   };



   

/**
*     @desc: set function called when drag-and-drop event occured
*     @param: aFunc - event handling function
*     @type: deprecated
*     @topic: 0,7
*     @event:    onDrag
*     @depricated: use grid.attachEvent("onDrag",func); instead
*     @eventdesc: Event occured after item was dragged and droped on another item, but before item moving processed.
      Event also raised while programmatic moving nodes.
*     @eventparam:  ID of source item
*     @eventparam:  ID of target item
*     @eventparam:  if node droped as sibling then contain id of item before whitch source node will be inserted
*     @eventparam:  source Tree object
*     @eventparam:  target Tree object
*     @eventreturn:  true - confirm drag-and-drop; false - deny drag-and-drop;
*/
   dhtmlXTreeObject.prototype.setDragHandler=function(func){ this.attachEvent("onDrag",func); };
   
   /**
*     @desc: clear selection from node
*     @param: htmlNode - pointer to node object
*     @type: private
*     @topic: 1
*/
    dhtmlXTreeObject.prototype._clearMove=function(){
		if (this._lastMark){
	   		this._lastMark.className=this._lastMark.className.replace(/dragAndDropRow/g,"");
	   		this._lastMark=null;
		}
//#__pro_feature:01112006{
//#complex_move:01112006{
		this.selectionBar.style.display="none";
//#}
//#}
		this.allTree.className=this.allTree.className.replace(" selectionBox","");
   };

   /**  
*     @desc: enable/disable drag-and-drop
*     @type: public
*     @param: mode - enabled/disabled [ can be true/false/temporary_disabled - last value mean that tree can be D-n-D can be switched to true later ]
*     @param: rmode - enabled/disabled drag and drop on super root
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableDragAndDrop=function(mode,rmode){
        if  (mode=="temporary_disabled"){
            this.dADTempOff=false;
            mode=true;                  }
        else
            this.dADTempOff=true;

      this.dragAndDropOff=dhx4.s2b(mode);
         if (this.dragAndDropOff) this.dragger.addDragLanding(this.allTree,this);
        if (arguments.length>1)
            this._ddronr=(!dhx4.s2b(rmode));
       };   

/**
*     @desc: set selection on node
*     @param: node - pointer to node object
*     @type: private
*     @topic: 1
*/    
   dhtmlXTreeObject.prototype._setMove=function(htmlNode,x,y){
      if (htmlNode.parentObject.span) {
      //window.status=x;
      var a1=dhx4.absTop(htmlNode);
      var a2=dhx4.absTop(this.allTree)-this.allTree.scrollTop;

      this.dadmodec=this.dadmode;//this.dadmode;
      this.dadmodefix=0;
//#__pro_feature:01112006{
//#complex_move:01112006{
      if (this.dadmode==2)
      {

      var z=y-a1+(document.body.scrollTop||document.documentElement.scrollTop)-2-htmlNode.offsetHeight/2;
      if ((Math.abs(z)-htmlNode.offsetHeight/6)>0)
      {
         this.dadmodec=1;
         //sibbling zone
         if (z<0)
            this.dadmodefix=0-htmlNode.offsetHeight;
      }
      else this.dadmodec=0;

      }
      if (this.dadmodec==0)
         {
//#}
//#} 

			var zN=htmlNode.parentObject.span;
			zN.className+=" dragAndDropRow";
			this._lastMark=zN;
//#__pro_feature:01112006{
//#complex_move:01112006{
         }
      else{
 	  	 this._clearMove();
         this.selectionBar.style.top=(a1-a2+((parseInt(htmlNode.parentObject.span.parentNode.parentNode.offsetHeight)||18)-1)+this.dadmodefix)+"px";
         this.selectionBar.style.left="5px";
           if (this.allTree.offsetWidth>20)
                this.selectionBar.style.width=(this.allTree.offsetWidth-(_isFF?30:25))+"px";
         this.selectionBar.style.display="";
         }
//#}
//#}
         this._autoScroll(null,a1,a2);

      }
   };

dhtmlXTreeObject.prototype._autoScroll=function(node,a1,a2){
         if (this.autoScroll)
         {
		 	if (node){
				a1=dhx4.absTop(node);
	      		a2=dhx4.absTop(this.allTree)-this.allTree.scrollTop;
			}
            //scroll down
            if ( (a1-a2-parseInt(this.allTree.scrollTop))>(parseInt(this.allTree.offsetHeight)-50) )
               this.allTree.scrollTop=parseInt(this.allTree.scrollTop)+20;
            //scroll top
            if ( (a1-a2)<(parseInt(this.allTree.scrollTop)+30) )
               this.allTree.scrollTop=parseInt(this.allTree.scrollTop)-20;
         }
}

/**
*     @desc: create html element for dragging
*     @type: private
*     @param: htmlObject - html node object
*     @topic: 1
*/
dhtmlXTreeObject.prototype._createDragNode=function(htmlObject,e){
      if (!this.dADTempOff) return null;

     var obj=htmlObject.parentObject;
     if (!this.callEvent("onBeforeDrag",[obj.id, e])) return null;
    if (!obj.i_sel){

         this._selectItem(obj,e);
}
//#__pro_feature:01112006{
//#multiselect:01112006{
      this._checkMSelectionLogic();
//#}
//#}
      var dragSpan=document.createElement('div');

            var text=new Array();
            if (this._itim_dg)
                    for (var i=0; i<this._selected.length; i++)
                        text[i]="<table cellspacing='0' cellpadding='0'><tr><td><img width='18px' height='18px' src='"+this._getSrc(this._selected[i].span.parentNode.previousSibling.childNodes[0])+"'></td><td>"+this._selected[i].span.innerHTML+"</td></tr></table>";
            else
                text=this.getSelectedItemText().split(this.dlmtr);

            dragSpan.innerHTML=text.join("");
         dragSpan.style.position="absolute";
         dragSpan.className="dragSpanDiv";
      this._dragged=(new Array()).concat(this._selected);
     return dragSpan;
}



/**  
*     @desc: focus item in tree
*     @type: private
*     @param: item - node object
*     @edition: Professional
*     @topic: 0  
*/
dhtmlXTreeObject.prototype._focusNode=function(item){
	var z=dhx4.absTop(item.htmlNode)-dhx4.absTop(this.allTree);
	if ((z>(this.allTree.offsetHeight-30)) || (z<0))
		this.allTree.scrollTop=z+this.allTree.scrollTop;
};




              








///DragAndDrop

dhtmlXTreeObject.prototype._preventNsDrag=function(e){
   if ((e)&&(e.preventDefault)) { e.preventDefault(); return false; }
   return false;
}

dhtmlXTreeObject.prototype._drag=function(sourceHtmlObject,dhtmlObject,targetHtmlObject){
      if (this._autoOpenTimer) clearTimeout(this._autoOpenTimer);

      if (!targetHtmlObject.parentObject){
            targetHtmlObject=this.htmlNode.htmlNode.childNodes[0].childNodes[0].childNodes[1].childNodes[0];
            this.dadmodec=0;
            }

      this._clearMove();
      var z=sourceHtmlObject.parentObject.treeNod;
        if ((z)&&(z._clearMove))   z._clearMove("");

       if ((!this.dragMove)||(this.dragMove()))
          {
              if ((!z)||(!z._clearMove)||(!z._dragged)) var col=new Array(sourceHtmlObject.parentObject);
              else var col=z._dragged;
				var trg=targetHtmlObject.parentObject;

                for (var i=0; i<col.length; i++){
                   var newID=this._moveNode(col[i],trg);
				   if ((this.dadmodec)&&(newID!==false)) trg=this._globalIdStorageFind(newID,true,true);
                   if ((newID)&&(!this._sADnD)) this.selectItem(newID,0,1);
                }

         }
        if (z) z._dragged=new Array();


}

dhtmlXTreeObject.prototype._dragIn=function(htmlObject,shtmlObject,x,y){

                    if (!this.dADTempOff) return 0;
                    var fobj=shtmlObject.parentObject;
                    var tobj=htmlObject.parentObject;
	                if ((!tobj)&&(this._ddronr)) return;
                    if (!this.callEvent("onDragIn",[fobj.id,tobj?tobj.id:null,fobj.treeNod,this])){
                    	if (tobj) this._autoScroll(htmlObject);
                    	return 0;
                    }
						

					if (!tobj) 
		            	this.allTree.className+=" selectionBox";
					else
					{
	                    if (fobj.childNodes==null){
		                	this._setMove(htmlObject,x,y);
        	             	return htmlObject;
                    	}

	                    var stree=fobj.treeNod;
    	                for (var i=0; i<stree._dragged.length; i++)
                        	if (this._checkPNodes(tobj,stree._dragged[i])){
						   		this._autoScroll(htmlObject);
                           		return 0;
							}
//#__pro_feature:01112006{
//#complex_move:01112006{	 
				this.selectionBar.parentNode.removeChild(this.selectionBar);
				tobj.span.parentNode.appendChild(this.selectionBar);
//#}
//#}
                       this._setMove(htmlObject,x,y);
                       if (this._getOpenState(tobj)<=0){
                           var self = this;
                           this._autoOpenId=tobj.id;
                           this._autoOpenTimer=window.setTimeout(function(){
                             self._autoOpenItem(null, self);
                             self = null;
                           }, 1000);
                       }
					}
					
				return htmlObject;

}
dhtmlXTreeObject.prototype._autoOpenItem=function(e,treeObject){
   treeObject.openItem(treeObject._autoOpenId);
};
dhtmlXTreeObject.prototype._dragOut=function(htmlObject){
this._clearMove();
if (this._autoOpenTimer) clearTimeout(this._autoOpenTimer);
 }


//#__pro_feature:01112006{

/**  
*     @desc: return next node
*     @type: private
*     @param: item - node object
*     @param: mode - inner flag
*     @return: next node or -1
*     @topic: 2
*/
dhtmlXTreeObject.prototype._getNextNode=function(item,mode){
   if ((!mode)&&(item.childsCount)) return item.childNodes[0];
   if (item==this.htmlNode)
      return -1;
   if ((item.tr)&&(item.tr.nextSibling)&&(item.tr.nextSibling.nodem))
   return item.tr.nextSibling.nodem;

   return this._getNextNode(item.parentObject,true);
};

/**  
*     @desc: return last child of item (include all sub-child collections)
*     @type: private
*     @param: item - node object
*     @topic: 2  
*/
dhtmlXTreeObject.prototype._lastChild=function(item){
   if (item.childsCount)
      return this._lastChild(item.childNodes[item.childsCount-1]);
   else return item;
};

/**  
*     @desc: return previous node
*     @type: private
*     @param: item - node object
*     @param: mode - inner flag
*     @return: previous node or -1
*     @topic: 2  
*/
dhtmlXTreeObject.prototype._getPrevNode=function(node,mode){
   if ((node.tr)&&(node.tr.previousSibling)&&(node.tr.previousSibling.nodem))
   return this._lastChild(node.tr.previousSibling.nodem);

   if (node.parentObject)
      return node.parentObject;
   else return -1;
};



//#find_item:01112006{

/**
*     @desc: find tree item by text, select and focus it
*     @type: public
*     @param: searchStr - search text
*     @param: direction - 0: top -> bottom; 1: bottom -> top
*     @param: top - 1: start searching from top
*     @return: node id
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.findItem=function(searchStr,direction,top){
   var z=this._findNodeByLabel(searchStr,direction,(top?this.htmlNode:null));
   if (z){
      this.selectItem(z.id,true);
      this._focusNode(z);
      return z.id;
      }
      else return null;
}

/**  
*     @desc: find tree item by text
*     @type: public
*     @param: searchStr - search text
*     @param: direction - 0: top -> bottom; 1: bottom -> top
*     @param: top - 1: start searching from top
*     @return: node id
*     @edition: Professional
*     @topic: 2  
*/
dhtmlXTreeObject.prototype.findItemIdByLabel=function(searchStr,direction,top){
   var z=this._findNodeByLabel(searchStr,direction,(top?this.htmlNode:null));
   if (z)
      return z.id
   else return null;
}

//#smart_parsing:01112006{
/**  
*     @desc: find tree item by text in unParsed XML
*     @type: private
*     @param: node - start xml node
*     @param: field - name of xml attribute
*     @param: cvalue - search text
*     @return: true/false
*     @topic: 2  
*/
dhtmlXTreeObject.prototype.findStrInXML=function(node,field,cvalue){ 
   if (!node.childNodes && node.item) return this.findStrInJSON(node,field,cvalue);
   if(!node.childNodes)
       return false;
   for (var i=0; i<node.childNodes.length; i++)
   {
   if (node.childNodes[i].nodeType==1)
      {
      	
        var z=node.childNodes[i].getAttribute(field);
        if (!z && node.childNodes[i].tagName=="itemtext")  z=node.childNodes[i].firstChild.data; 
      if ((z)&&(z.toLowerCase().search(cvalue)!=-1))
         return true;
      if (this.findStrInXML(node.childNodes[i],field,cvalue)) return true;
      }
   }
   return false;
}
dhtmlXTreeObject.prototype.findStrInJSON=function(node,field,cvalue){ 
   for (var i=0; i<node.item.length; i++)
   {
	    var z=node.item[i].text;
        if ((z)&&(z.toLowerCase().search(cvalue)!=-1))
         return true;
      if (node.item[i].item && this.findStrInJSON(node.item[i],field,cvalue)) return true;
   }
   return false;
}
//#}

/**  
*     @desc: find tree item by text
*     @type: private
*     @param: searchStr - search text
*     @param: direction - 0: top -> bottom; 1: bottom -> top
*     @param: fromNode - node from which search begin
*     @return: node id
*     @topic: 2  
*/
dhtmlXTreeObject.prototype._findNodeByLabel=function(searchStr,direction,fromNode){
   //trim
   var searchStr=searchStr.replace(new RegExp("^( )+"),"").replace(new RegExp("( )+$"),"");
   searchStr =  new RegExp(searchStr.replace(/([\^\.\?\*\+\\\[\]\(\)]{1})/gi,"\\$1").replace(/ /gi,".*"),"gi");

   //get start node
   if (!fromNode)
      {
      fromNode=this._selected[0];
      if (!fromNode) fromNode=this.htmlNode;
      }

   var startNode=fromNode;

   //first step
   if (!direction){
      if ((fromNode.unParsed)&&(this.findStrInXML(fromNode.unParsed.d,"text",searchStr)))
      this.reParse(fromNode);
   fromNode=this._getNextNode(startNode);
   if (fromNode==-1) fromNode=this.htmlNode.childNodes[0];
   }
   else
   {
      var z2=this._getPrevNode(startNode);
      if (z2==-1) z2=this._lastChild(this.htmlNode);
      if ((z2.unParsed)&&(this.findStrInXML(z2.unParsed.d,"text",searchStr)))
      {   this.reParse(z2); fromNode=this._getPrevNode(startNode); }
      else fromNode=z2;
      if (fromNode==-1) fromNode=this._lastChild(this.htmlNode);
   }



   while ((fromNode)&&(fromNode!=startNode)){
      if ((fromNode.label)&&(fromNode.label.search(searchStr)!=-1))
            return (fromNode);

      if (!direction){
      if (fromNode==-1) { if (startNode==this.htmlNode) break; fromNode=this.htmlNode.childNodes[0]; }
      if ((fromNode.unParsed)&&(this.findStrInXML(fromNode.unParsed.d,"text",searchStr)))
         this.reParse(fromNode);
      fromNode=this._getNextNode(fromNode);
      if (fromNode==-1) fromNode=this.htmlNode;
      }
      else
      {
      var z2=this._getPrevNode(fromNode);
      if (z2==-1) z2=this._lastChild(this.htmlNode);
      if ((z2.unParsed)&&(this.findStrInXML(z2.unParsed.d,"text",searchStr)))
         {   this.reParse(z2); fromNode=this._getPrevNode(fromNode); }
      else fromNode=z2;
      if (fromNode==-1) fromNode=this._lastChild(this.htmlNode);
      }
   }
   return null;
};

//#}
//#}


//#complex_move:01112006{

/**
*     @desc: move item (inside of tree)
*     @type:  public
*     @param: itemId - item Id
*     @param: mode - moving mode (left,up,down,item_child,item_sibling,item_sibling_next,up_strict,down_strict)
*     @param: targetId - target Node in item_child and item_sibling mode
*     @param: targetTree - used for moving between trees (optional)
*     @return: node id
*     @topic: 2
*/
dhtmlXTreeObject.prototype.moveItem=function(itemId,mode,targetId,targetTree)
{
	var sNode=this._globalIdStorageFind(itemId);
	if (!sNode) return (0);
	var resultId = null;
	switch(mode){
		case "right":
			alert('Not supported yet');
			break;
		case "item_child":
			var tNode=(targetTree||this)._globalIdStorageFind(targetId);
			if (!tNode) return (0);
			resultId = (targetTree||this)._moveNodeTo(sNode,tNode,0);
			break;
		case "item_sibling":
			var tNode=(targetTree||this)._globalIdStorageFind(targetId);
			if (!tNode) return (0);
			resultId = (targetTree||this)._moveNodeTo(sNode,tNode.parentObject,tNode);
			break;
		case "item_sibling_next":
			var tNode=(targetTree||this)._globalIdStorageFind(targetId);
			if (!tNode) return (0);
			if ((tNode.tr)&&(tNode.tr.nextSibling)&&(tNode.tr.nextSibling.nodem))
				resultId = (targetTree||this)._moveNodeTo(sNode,tNode.parentObject,tNode.tr.nextSibling.nodem);
			else
				resultId = (targetTree||this)._moveNodeTo(sNode,tNode.parentObject);
			break;
		case "left":
			if (sNode.parentObject.parentObject)
				resultId = this._moveNodeTo(sNode,sNode.parentObject.parentObject,sNode.parentObject);
			break;
		case "up":
			var z=this._getPrevNode(sNode);
			if ((z==-1)||(!z.parentObject)) return null;
			resultId = this._moveNodeTo(sNode,z.parentObject,z);
			break;
		case "up_strict":
			var z=this._getIndex(sNode);
			if (z!=0)
				resultId = this._moveNodeTo(sNode,sNode.parentObject,sNode.parentObject.childNodes[z-1]);
			break;
		case "down_strict":
			var z=this._getIndex(sNode);
			var count=sNode.parentObject.childsCount-2;
			if (z==count)
				resultId = this._moveNodeTo(sNode,sNode.parentObject);
			else if (z<count)
				resultId = this._moveNodeTo(sNode,sNode.parentObject,sNode.parentObject.childNodes[z+2]);
			break;
		case "down":
			var z=this._getNextNode(this._lastChild(sNode));
			if ((z==-1)||(!z.parentObject)) return;
			if (z.parentObject==sNode.parentObject)
				var z=this._getNextNode(z);
			if (z==-1){
				resultId = this._moveNodeTo(sNode,sNode.parentObject);
			}
			else{
				if ((z==-1)||(!z.parentObject)) return;
				resultId = this._moveNodeTo(sNode,z.parentObject,z);
			}
			break;
	}
	if (_isIE && _isIE<8){
		this.allTree.childNodes[0].border = "1";
		this.allTree.childNodes[0].border = "0";
	}
	return resultId;
}

//#__pro_feature:01112006{

/**
*     @desc: set Drag-And-Drop behavior (child - drop as chils, sibling - drop as sibling, complex - complex drop behaviour )
*     @type: public
*     @edition: Professional
*     @param: mode - behavior name (child,sibling,complex)
*     @param: select - select droped node after drag-n-drop, true by default
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.setDragBehavior=function(mode,select){
		this._sADnD=(!dhx4.s2b(select));
		switch (mode) {
			case "child": this.dadmode=0; break;
			case "sibling": this.dadmode=1; break;
			case "complex": this.dadmode=2; break;
		}    };




//#}
//#}







/**
*     @desc: load xml for tree branch
*     @param: id - id of parent node
*     @param: src - path to xml, optional
*     @type: private
*     @topic: 1
*/
   dhtmlXTreeObject.prototype._loadDynXML=function(id,src) {
   		src=src||this.XMLsource;
        var sn=(new Date()).valueOf();
        this._ld_id=id;
//#__pro_feature:01112006{
        if (this.xmlalb=="function"){
            if (src) src(this._escape(id));
            }
        else
        if (this.xmlalb=="name")
            this.load(src+this._escape(id));
        else
        if (this.xmlalb=="xmlname")
            this.load(src+this._escape(id)+".xml?uid="+sn);
        else
//#}
            this.load(src+dhtmlx.url(src)+"uid="+sn+"&id="+this._escape(id));
        };


//#__pro_feature:01112006{
//#multiselect:01112006{
/**
*     @desc: enable multiselection
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @param: strict - 1 - on, 0 - off; in strict mode only items on the same level can be selected
*     @type: public
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableMultiselection=function(mode,strict) {
        this._amsel=dhx4.s2b(mode);
        this._amselS=dhx4.s2b(strict);
        };

/**
*     @desc: check logic of selection
*     @type: private
*     @edition: Professional
*     @topic: 0
*/
dhtmlXTreeObject.prototype._checkMSelectionLogic=function() {
            var usl=new Array();
         for (var i=0; i<this._selected.length; i++)
            for (var j=0; j<this._selected.length; j++)
                  if ((i!=j)&&(this._checkPNodes(this._selected[j],this._selected[i])))
                            usl[usl.length]=this._selected[j];

         for (var i=0; i<usl.length; i++)
             this._unselectItem(usl[i]);

         };
//#}
//#}




/**
*     @desc: check possibility of drag-and-drop
*     @type: private
*     @param: itemId - draged node id
*     @param: htmlObject - droped node object
*     @param: shtmlObject - sourse node object
*     @topic: 6
*/
    dhtmlXTreeObject.prototype._checkPNodes=function(item1,item2){
      if (this._dcheckf) return false;
      if (item2==item1) return 1
      if (item1.parentObject) return this._checkPNodes(item1.parentObject,item2); else return 0;
   };
   dhtmlXTreeObject.prototype.disableDropCheck = function(mode){
      this._dcheckf = dhx4.s2b(mode);
   };


//#__pro_feature:01112006{
//#distributed_load:01112006{

/**
*     @desc: enable distributed parsing of big tree (items loaded portion by portion with some timeouts)
*     @type: public
*     @edition: Professional
*     @param: mode - true/false
*     @param: count - critical count to start distibuting (optional)
*     @param: delay - delay between distributed calls, ms (optional)
*     @topic: 2
*/
dhtmlXTreeObject.prototype.enableDistributedParsing=function(mode,count,delay){
    this._edsbps=dhx4.s2b(mode);
    this._edsbpsA=new Array();
    this._edsbpsC=count||10;
    this._edsbpsD=delay||250;
}
/**
*     @desc: get current state of distributed parsing
*     @type: public
*     @edition: Professional
*     @returns: true - still parsing; false - parsing finished
*     @topic: 2
*/
dhtmlXTreeObject.prototype.getDistributedParsingState=function(){
    return (!((!this._edsbpsA)||(!this._edsbpsA.length)));
}
/**
*     @desc: get current parsing state of item
*     @type: public
*     @edition: Professional
*     @returns: 1 - item already parsed; 0 - item not parsed yet; -1 - item in parsing process
*     @topic: 2
*/
dhtmlXTreeObject.prototype.getItemParsingState=function(itemId){
    var z=this._globalIdStorageFind(itemId,true,true)
    if (!z) return 0;
    if (this._edsbpsA)
        for (var i=0; i<this._edsbpsA.length; i++)
            if (this._edsbpsA[i][2]==itemId) return -1;

    return 1;
}

dhtmlXTreeObject.prototype._distributedStart=function(node,start,parentId,level,start2){
    if (!this._edsbpsA)
        this._edsbpsA=new Array();
    this._edsbpsA[this._edsbpsA.length]=[node,start,parentId,level,start2];
}

dhtmlXTreeObject.prototype._distributedStep=function(pId){
    var self=this;
    if ((!this._edsbpsA)||(!this._edsbpsA.length)) {
         self.XMLloadingWarning=0;
         return;
         }
    var z=this._edsbpsA[0];
    this.parsedArray=new Array();
    this._parse(z[0],z[2],z[3],z[1]);
    var zkx=this._globalIdStorageFind(z[2]);
    this._redrawFrom(this,zkx,z[4],this._getOpenState(zkx));
    var chArr=this.setCheckList.split(this.dlmtr);
   for (var n=0; n<chArr.length; n++)
      if (chArr[n]) this.setCheck(chArr[n],1);

    this._edsbpsA=(new Array()).concat(this._edsbpsA.slice(1));


    if ((!this._edsbpsA.length)){
         window.setTimeout( function(){ if (self.onXLE) self.onXLE(self,pId); self.callEvent("onXLE",[self,pId]); },1);
            self.xmlstate=0;
            }
}

//#}
//#}




//#__pro_feature:01112006{

/**
*     @desc: replace images with text signs
*     @type: public
*     @param: mode - true/false
*     @edition: Professional
*     @topic: 1
*/
dhtmlXTreeObject.prototype.enableTextSigns=function(mode){
    this._txtimg=dhx4.s2b(mode);
}

//#}

/**
*   @desc:  prevent caching in IE  by adding random value to URL string
*   @param: mode - enable/disable random value ( disabled by default )
*   @type: public
*   @topic: 0
*/
dhtmlXTreeObject.prototype.preventIECaching=function(mode){
      dhx4.ajax.cache = !mode;
}
dhtmlXTreeObject.prototype.preventIECashing=dhtmlXTreeObject.prototype.preventIECaching;





/**
*     @desc: disable checkbox
*     @param: itemId - Id of tree item
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.disableCheckbox=function(itemId,mode) {
            if (typeof(itemId)!="object")
             var sNode=this._globalIdStorageFind(itemId,0,1);
            else
                var sNode=itemId;
         if (!sNode) return;
            sNode.dscheck=dhx4.s2b(mode)?(((sNode.checkstate||0)%3)+3):((sNode.checkstate>2)?(sNode.checkstate-3):sNode.checkstate);
            this._setCheck(sNode);
                if (sNode.dscheck<3) sNode.dscheck=false;
         };

//#__pro_feature:01112006{


/**
*     @desc: refresh specified tree branch (get XML from server, add new nodes, remove not used nodes)
*     @param: itemId -  top node in branch
*     @param: source - server side script , optional
*     @type: public
*     @edition: Professional
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.smartRefreshBranch=function(itemId,source){
   		this._branchUpdate=1;
		this.smartRefreshItem(itemId,source);
   }

/**
*     @desc: refresh specified tree item (get XML from server, add new nodes, remove not used nodes)
*     @param: itemId -  top node in branch
*     @param: source - server side script , optional
*     @type: public
*     @edition: Professional
*     @topic: 6
*/
dhtmlXTreeObject.prototype.smartRefreshItem=function(itemId,source){
		var sNode=this._globalIdStorageFind(itemId);
		for (var i=0; i<sNode.childsCount; i++)
			sNode.childNodes[i]._dmark=true;

		this.waitUpdateXML=true;
		if (source && source.exists)
			this._parse(source,itemId);
		else
			this._loadDynXML(itemId,source);
};


/**
*     @desc: refresh specified tree nodes (get XML from server and updat only nodes included in itemIdList)
*     @param: itemIdList - list of node identificators
*     @param: source - server side script
*     @type: public
*     @edition: Professional
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.refreshItems=function(itemIdList,source){
   		var z=itemIdList.toString().split(this.dlmtr);
		this.waitUpdateXML=new Array();
		for (var i=0; i<z.length; i++)
			this.waitUpdateXML[z[i]]=true;
        this.load((source||this.XMLsource)+dhtmlx.url(source||this.XMLsource)+"ids="+this._escape(itemIdList));
   };


/**
*     @desc: update item properties
*     @param: itemId - list of node identificators
*     @param: name - list of node identificators, optional
*     @param: im0 - list of node identificators, optional
*     @param: im1 - list of node identificators, optional
*     @param: im2 - list of node identificators, optional
*     @param: achecked - list of node identificators, optional
*     @param: child - child attribute for dynamic loading
*     @type: public
*     @edition: Professional
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.updateItem=function(itemId,name,im0,im1,im2,achecked,child){
      var sNode=this._globalIdStorageFind(itemId);
	  sNode.userData=new cObject(); 
      if (name) sNode.label=name;
      sNode.images=new Array(im0||this.imageArray[0],im1||this.imageArray[1],im2||this.imageArray[2]);
	  this.setItemText(itemId,name);
      if (achecked) this._setCheck(sNode,true);
	  if(child=="1" && !this.hasChildren(itemId)) sNode.XMLload = 0;
      this._correctPlus(sNode);
	  sNode._dmark=false;
      return sNode;
   };

/**
*     @desc: set function called after drag-and-drap event occured
*     @param: func - event handling function
*     @type: deprecated
*     @edition: Professional
*     @topic: 0,7
*     @event:  onDrop
*     @depricated: use grid.attachEvent("onDrop",func); instead
*     @eventdesc:  Event raised after drag-and-drop processed. Event also raised while programmatic moving nodes.
*     @eventparam:  ID of source item (ID after inserting in tree, my be not equal to initial ID)
*     @eventparam:  ID of target item
*     @eventparam:  if node droped as sibling then contain id of item before whitch source node will be inserted
*     @eventparam:  source Tree object
*     @eventparam:  target Tree object
*/
   dhtmlXTreeObject.prototype.setDropHandler=function(func){  this.attachEvent("onDrop",func);  };

/**
*     @desc: set function called before xml loading/parsing started
*     @param: func - event handling function
*     @type: deprecated
*     @edition: Professional
*     @topic: 0,7
*     @event:  onXLS
*     @depricated: use grid.attachEvent("onXLS",func); instead
*     @eventdesc: event fired simultaneously with starting XML parsing
*     @eventparam: tree object
*     @eventparam: item id, for which xml loaded
*/
   dhtmlXTreeObject.prototype.setOnLoadingStart=function(func){    this.attachEvent("onXLS",func);  };
      /**
*     @desc: set function called after xml loading/parsing ended
*     @param: func - event handling function
*     @type: deprecated
*     @edition: Professional
*     @topic: 0,7
*     @event:  onXLE
*     @depricated: use grid.attachEvent("onXLE",func); instead
*     @eventdesc: event fired simultaneously with ending XML parsing, new items already available in tree
*     @eventparam: tree object
*     @eventparam: last parsed parent id
*/
     dhtmlXTreeObject.prototype.setOnLoadingEnd=function(func){  this.attachEvent("onXLE",func); };



/**
*     @desc: define which script be called on dynamic loading
*     @param: mode - id for some_script?id=item_id ;  name for  some_scriptitem_id, xmlname for  some_scriptitem_id.xml ; function for calling user defined handler
*     @type: public
*     @edition: Professional
*     @topic: 1
*/
   dhtmlXTreeObject.prototype.setXMLAutoLoadingBehaviour=function(mode) {
            this.xmlalb=mode;
         };


/**
*     @desc: enable smart checkboxes ,true by default (auto checking children and parents for 3-state checkboxes)
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableSmartCheckboxes=function(mode) { this.smcheck=dhx4.s2b(mode); };

/**
*     @desc: return current state of XML loading
*     @type: public
*     @edition: Professional
*     @return: current state, true - xml loading now
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.getXMLState=function(){ return (this.xmlstate==1); };

/**
*     @desc: set top offset for item
*     @type: public
*     @param: itemId - id of item
*     @param: value - value of top offset in px
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.setItemTopOffset=function(itemId,value){
    var node;
    if (typeof(itemId)!="object")
        node=this._globalIdStorageFind(itemId);
    else
        node=itemId;
    var z=node.span.parentNode.parentNode;
    node.span.style.paddingBottom="1px";
  
    for (var i=0; i<z.childNodes.length; i++){
        if (i!=0){
      
            if (_isIE){
                z.childNodes[i].style.height="18px";
                z.childNodes[i].style.paddingTop=parseInt(value)+"px";
            }else
                z.childNodes[i].style.height=18+parseInt(value)+"px";
        }
        else{
            var w=z.childNodes[i].firstChild;
            if (z.childNodes[i].firstChild.tagName!='DIV'){
              w=document.createElement("DIV");
              z.childNodes[i].insertBefore(w,z.childNodes[i].firstChild);
            }
            
            if ((node.parentObject.id!=this.rootId || node.parentObject.childNodes[0]!=node) && this.treeLinesOn){
                z.childNodes[i].style.backgroundImage="url("+this.imPath+this.lineArray[5]+")";
            }
            w.innerHTML="&nbsp;";
            w.style.overflow='hidden';
            
        }
        
        w.style.verticalAlign = z.childNodes[i].style.verticalAlign="bottom";
        if (_isIE){
            this.allTree.childNodes[0].border = "1";
            this.allTree.childNodes[0].border = "0";
        }
    }
}

/**
*     @desc: set size of icons
*     @type:  public
*     @param: newWidth - new icon width
*     @param: newHeight - new icon height
*     @param: itemId - item Id, if skipped set default value for all new icons, optional
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.setIconSize=function(newWidth,newHeight,itemId)
{
      if (itemId){
         if ((itemId)&&(itemId.span))
            var sNode=itemId;
         else
            var sNode=this._globalIdStorageFind(itemId);

         if (!sNode) return (0);
         var img=sNode.span.parentNode.previousSibling.childNodes[0];
            if (newWidth) {
            	img.style.width=newWidth+"px";
            	if (window._KHTMLrv) img.parentNode.style.width=newWidth+"px";
        	}
            if (newHeight) {
            	img.style.height=newHeight+"px";
            	if (window._KHTMLrv) img.parentNode.style.height=newHeight+"px";
        	}
         }
      else{
         this.def_img_x=newWidth+"px";
         this.def_img_y=newHeight+"px";
      }
}

/**
*     @desc: get url of item image
*     @type: public
*     @param: itemId - id of item
*     @param: imageInd - index of image ( 0 - leaf, 1 - closed folder, 2 - opened folder)
*     @param: value - value of top offset
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.getItemImage=function(itemId,imageInd,fullPath){
    var node=this._globalIdStorageFind(itemId);
    if (!node) return "";
    var img=node.images[imageInd||0];
    if (fullPath) img=this.iconURL+img;
    return img;
}

/**
*     @desc: replace checkboxes with radio buttons
*     @type: public
*     @param: mode - true/false
*     @param: itemId - node for which replacement called (optional)
*     @edition: Professional
*     @topic: 1
*/
dhtmlXTreeObject.prototype.enableRadioButtons=function(itemId,mode){
    if (arguments.length==1){
        this._frbtr=dhx4.s2b(itemId);
        this.checkBoxOff=this.checkBoxOff||this._frbtr;
        return;
        }


    var node=this._globalIdStorageFind(itemId);
    if (!node) return "";
    mode=dhx4.s2b(mode);
    if ((mode)&&(!node._r_logic)){
            node._r_logic=true;
            for (var i=0; i<node.childsCount; i++)
                this._setCheck(node.childNodes[i],node.childNodes[i].checkstate);
        }

    if ((!mode)&&(node._r_logic)){
            node._r_logic=false;
            for (var i=0; i<node.childsCount; i++)
                this._setCheck(node.childNodes[i],node.childNodes[i].checkstate);
        }
}
/**
*     @desc: replace checkboxes with radio buttons
*     @type: public
*     @param: mode - true/false
*     @param: itemId - node for which replacement called (optional)
*     @edition: Professional
*     @topic: 1
*/
dhtmlXTreeObject.prototype.enableSingleRadioMode=function(mode){
     this._frbtrs=dhx4.s2b(mode);
}


/**
*     @desc: configure if parent node will be expanded immideatly after child item added
*     @type: public
*     @param: mode - true/false
*     @edition: Professional
*     @topic: 2
*/
dhtmlXTreeObject.prototype.openOnItemAdded=function(mode){
    this._hAdI=!dhx4.s2b(mode);
}
dhtmlXTreeObject.prototype.openOnItemAdding=function(mode){
    this._hAdI=!dhx4.s2b(mode);
}

/**
*     @desc: enable multi line items
*     @beforeInit: 1
*     @param: width - text width, if equls zero then use single lines items;
*     @type: public
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableMultiLineItems=function(width) { if (width===true) this.mlitems="100%"; else this.mlitems=width; }

/**
*     @desc: enable auto tooltips (node text as tooltip)
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @edition:Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableAutoTooltips=function(mode) { this.ettip=dhx4.s2b(mode); };


/**
*     @desc: unselect item in tree
*     @type: public
*     @param: itemId - used in multi selection tree (optional)
*     @edition: Professional
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.clearSelection=function(itemId){
       if (itemId)
            this._unselectItem(this._globalIdStorageFind(itemId));
            else
            this._unselectItems();
            }

/**
*     @desc: show/hide (+/-) icon (works only for individual items, not for entire tree )
*     @type: public
*     @param: itemId - id of selected item
*     @param: state - show state : 0/1
*     @edition: Professional
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.showItemSign=function(itemId,state){
      var temp=this._globalIdStorageFind(itemId);
      if (!temp) return 0;

      var z=temp.span.parentNode.previousSibling.previousSibling.previousSibling;
      if (!dhx4.s2b(state)){
         this._openItem(temp)
         temp.closeble=false;
         temp.wsign=true;
      }
      else
      {
         temp.closeble=true;
         temp.wsign=false;
      }
      this._correctPlus(temp);
   }
/**
*     @desc: show/hide checkbox for tree item (works only for individual items, not for entire tree )
*     @type: public
*     @param: itemId - id of selected item, optional, set null to change states of all items
*     @param: state - checkbox show state : 0/1
*     @edition: Professional
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.showItemCheckbox=function(itemId,state){
      if (!itemId)
		for (var a in this._idpull)
			this.showItemCheckbox(this._idpull[a],state);

      if (typeof(itemId)!="object")
	      itemId=this._globalIdStorageFind(itemId,0,0);

      if (!itemId) return 0;
   	  itemId.nocheckbox=!dhx4.s2b(state);
      var t=itemId.span.parentNode.previousSibling.previousSibling.childNodes[0];
      t.parentNode.style.display=(!itemId.nocheckbox)?"":"none";
   }

/**
*     @desc: set list separator ("," by default)
*     @type: public
*     @param: separator - char or string to use for separating items in lists
*     @edition: Professional
*     @topic: 0
*/
dhtmlXTreeObject.prototype.setListDelimeter=function(separator){
    this.dlmtr=separator;
}

//#}


/**
*     @desc: set escaping mode (used for escaping ID in requests)
*     @param: mode - escaping mode ("utf8" for UTF escaping)
*     @type: public
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.setEscapingMode=function(mode){
        this.utfesc=mode;
        }


/**
*     @desc: enable item highlighting (item text highlited on mouseover)
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableHighlighting=function(mode) { this.ehlt=true; this.ehlta=dhx4.s2b(mode); };

/**
*     @desc: called on mouse out
*     @type: private
*     @topic: 0
*/
   dhtmlXTreeObject.prototype._itemMouseOut=function(){
   		var that=this.childNodes[3].parentObject;
		var tree=that.treeNod;
 		tree.callEvent("onMouseOut",[that.id]);
		if (that.id==tree._l_onMSI) tree._l_onMSI=null;
        if (!tree.ehlta) return;
 	    that.span.className=that.span.className.replace("_lor","");
   }
/**
*     @desc: called on mouse in
*     @type: private
*     @topic: 0
*/
   dhtmlXTreeObject.prototype._itemMouseIn=function(){
   		var that=this.childNodes[3].parentObject;
		var tree=that.treeNod;

		if (tree._l_onMSI!=that.id) tree.callEvent("onMouseIn",[that.id]);
		tree._l_onMSI=that.id;
        if (!tree.ehlta) return;
 	    that.span.className=that.span.className.replace("_lor","");
 	    that.span.className=that.span.className.replace(/((standart|selected)TreeRow)/,"$1_lor");
   }

/**
*     @desc: enable active images (clickable and dragable). By default only text part of the node is active
*     @beforeInit: 1
*     @param: mode - 1 - on, 0 - off;
*     @type: public
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableActiveImages=function(mode){this._aimgs=dhx4.s2b(mode); };

/**
*     @desc: focus item in tree (scroll to it if necessary)
*     @type: public
*     @param: itemId - item Id
*     @topic: 0
*/
dhtmlXTreeObject.prototype.focusItem=function(itemId){
      var sNode=this._globalIdStorageFind(itemId);
      if (!sNode) return (0);
      this._focusNode(sNode);
   };


/**
*     @desc: Returns the list of all children from all next levels of tree, separated by default delimiter.
*     @param: itemId - id of node
*     @type: public
*     @return: list of all children items from all next levels of tree, separated by default delimiter
*     @topic: 6
*/
   dhtmlXTreeObject.prototype.getAllSubItems =function(itemId){
      return this._getAllSubItems(itemId);
   }

/**
*     @desc: Returns the list of all items which doesn't have child nodes.
*     @type: public
*     @return: list of all items which doesn't have child nodes.
*     @topic: 6
*/
	dhtmlXTreeObject.prototype.getAllChildless =function(){
		return this._getAllScraggyItems(this.htmlNode);
	}
	dhtmlXTreeObject.prototype.getAllLeafs=dhtmlXTreeObject.prototype.getAllChildless;


/**
*     @desc: Returns the list of all children from all next levels of tree, separated by default delimiter.
*     @param: itemId - id of node
*     @edition: Professional
*     @type: private
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getAllScraggyItems =function(node)
   {
      var z="";
      for (var i=0; i<node.childsCount; i++)
        {
            if ((node.childNodes[i].unParsed)||(node.childNodes[i].childsCount>0))
            {
                    if (node.childNodes[i].unParsed)
                        var zb=this._getAllScraggyItemsXML(node.childNodes[i].unParsed,1);
                    else
                       var zb=this._getAllScraggyItems(node.childNodes[i])

                 if (zb)
                        if (z) z+=this.dlmtr+zb;
                        else z=zb;
         }
            else
               if (!z) z=""+node.childNodes[i].id;
             else z+=this.dlmtr+node.childNodes[i].id;
         }
          return z;
   };





/**
*     @desc: Returns the list of all children from all next levels of tree, separated by default delimiter.
*     @param: itemId - id of node
*     @type: private
*     @edition: Professional
*     @topic: 6
*/
   dhtmlXTreeObject.prototype._getAllFatItems =function(node)
   {
      var z="";
      for (var i=0; i<node.childsCount; i++)
        {
            if ((node.childNodes[i].unParsed)||(node.childNodes[i].childsCount>0))
            {
             if (!z) z=""+node.childNodes[i].id;
                else z+=this.dlmtr+node.childNodes[i].id;

                    if (node.childNodes[i].unParsed)
                        var zb=this._getAllFatItemsXML(node.childNodes[i].unParsed,1);
                    else
                       var zb=this._getAllFatItems(node.childNodes[i])

                 if (zb) z+=this.dlmtr+zb;
         }
         }
          return z;
   };

/**
*     @desc: Returns the list of all items which have child nodes, separated by default delimiter.
*     @type: public
*     @return: list of all items which has child nodes, separated by default delimiter.
*     @topic: 6
*/
	dhtmlXTreeObject.prototype.getAllItemsWithKids =function(){
		return this._getAllFatItems(this.htmlNode);
	}
	dhtmlXTreeObject.prototype.getAllFatItems=dhtmlXTreeObject.prototype.getAllItemsWithKids;



/**
*     @desc: return list of identificators of nodes with checked checkboxes, separated by default delimiter
*     @type: public
*     @return: list of ID of items with checked checkboxes, separated by default delimiter
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.getAllChecked=function(){
      return this._getAllChecked("","",1);
   }
/**
*     @desc: return list of identificators of nodes with unchecked checkboxes, separated by default delimiter
*     @type: public
*     @return: list of ID of items with unchecked checkboxes, separated by default delimiter
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.getAllUnchecked=function(itemId){
        if (itemId)
            itemId=this._globalIdStorageFind(itemId);
      return this._getAllChecked(itemId,"",0);
    }


/**
*     @desc: return list of identificators of nodes with third state checkboxes, separated by default delimiter
*     @type: public
*     @return: list of ID of items with third state checkboxes, separated by default delimiter
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.getAllPartiallyChecked=function(){
      return this._getAllChecked("","",2);
   }


/**
*     @desc: return list of identificators of nodes with checked and third state checkboxes, separated by default delimiter
*     @type: public
*     @return: list of ID of items with checked and third state checkboxes, separated by default delimiter
*     @topic: 5
*/
   dhtmlXTreeObject.prototype.getAllCheckedBranches=function(){
        var temp = [this._getAllChecked("","",1)];
        var second = this._getAllChecked("","",2);
        if (second) temp.push(second);
        return temp.join(this.dlmtr);
   }

/**
*     @desc: return list of identificators of nodes with checked checkboxes
*     @type: private
*     @param: node - node object (optional, used by private methods)
*     @param: list - initial identificators list (optional, used by private methods)
*     @topic: 5
*/
   dhtmlXTreeObject.prototype._getAllChecked=function(htmlNode,list,mode){
      if (!htmlNode) htmlNode=this.htmlNode;

      if (htmlNode.checkstate==mode)
         if (!htmlNode.nocheckbox)  { if (list) list+=this.dlmtr+htmlNode.id; else list=""+htmlNode.id;  }
      var j=htmlNode.childsCount;
      for (var i=0; i<j; i++)
      {
         list=this._getAllChecked(htmlNode.childNodes[i],list,mode);
      };
//#__pro_feature:01112006{
//#smart_parsing:01112006{
        if  (htmlNode.unParsed)
            list=this._getAllCheckedXML(htmlNode.unParsed,list,mode);
//#}
//#}

      if (list) return list; else return "";
   };

/**
*     @desc: set individual item style
*     @type: public
*     @param: itemId - node id
*     @param: styleString - valid CSS string
*     @param: resetCss - reset current style : 0/1
*     @topic: 2
*/
dhtmlXTreeObject.prototype.setItemStyle=function(itemId,style_string,resetCss){ 
	var resetCss= resetCss|| false; 
	var temp=this._globalIdStorageFind(itemId); 
	if (!temp) return 0; 
	if (!temp.span.style.cssText) 
		temp.span.setAttribute("style",temp.span.getAttribute("style")+"; "+style_string); 
	else 
		temp.span.style.cssText = resetCss? style_string : temp.span.style.cssText+";"+style_string; 
}

/**
*     @desc: enable draging item image with item text
*     @type: public
*     @param: mode - true/false
*     @topic: 1
*/
dhtmlXTreeObject.prototype.enableImageDrag=function(mode){
    this._itim_dg=dhx4.s2b(mode);
}

/**
*     @desc: set function called when tree item draged over another item
*     @param: func - event handling function
*     @type: depricated
*     @edition: Professional
*     @topic: 4
*     @event: onDragIn
*     @depricated: use grid.attachEvent("onDragIn",func); instead
*     @eventdesc: Event raised when item draged other other dropable target
*     @eventparam:  ID draged item
*     @eventparam:  ID potencial drop landing
*     @eventparam:  source object
*     @eventparam:  target object
*     @eventreturn: true - allow drop; false - deny drop;
*/
	dhtmlXTreeObject.prototype.setOnDragIn=function(func){
		this.attachEvent("onDragIn",func);
        };

/**
*     @desc: enable/disable auto scrolling while drag-and-drop
*     @type: public
*     @param: mode - enabled/disabled
*     @topic: 0
*/
   dhtmlXTreeObject.prototype.enableDragAndDropScrolling=function(mode){ this.autoScroll=dhx4.s2b(mode); };


dhtmlXTreeObject.prototype.setSkin=function(name){
	var tmp = this.parentObject.className.replace(/dhxtree_[^ ]*/gi,"");
  this.parentObject.className= tmp+" dhxtree_"+name;
  if (name == "dhx_terrace" || name == "dhx_web" || name == "material"){
    this.enableTreeLines(false);
  }
  if (name == "material")
    this.setIconSize("25", "25");
};

//tree
(function(){
	
	dhtmlx.extend_api("dhtmlXTreeObject",{
		_init:function(obj){
			return [obj.parent,(obj.width||"100%"),(obj.height||"100%"),(obj.root_id||0)];
		},
		auto_save_selection:"enableAutoSavingSelected",
		auto_tooltip:"enableAutoTooltips",
		checkbox:"enableCheckBoxes",
		checkbox_3_state:"enableThreeStateCheckboxes",
		checkbox_smart:"enableSmartCheckboxes",
		context_menu:"enableContextMenu",
		distributed_parsing:"enableDistributedParsing",
		drag:"enableDragAndDrop",
		drag_copy:"enableMercyDrag",
		drag_image:"enableImageDrag",
		drag_scroll:"enableDragAndDropScrolling",
		editor:"enableItemEditor",
		hover:"enableHighlighting",
		images:"enableTreeImages",
		image_fix:"enableIEImageFix",
		image_path:"setImagePath",
		lines:"enableTreeLines",
		loading_item:"enableLoadingItem",
		multiline:"enableMultiLineItems",
		multiselect:"enableMultiselection",
		navigation:"enableKeyboardNavigation",
		radio:"enableRadioButtons",
		radio_single:"enableSingleRadioMode",
		rtl:"enableRTL",
		search:"enableKeySearch",
		smart_parsing:"enableSmartXMLParsing",
		smart_rendering:"enableSmartRendering",
		text_icons:"enableTextSigns",
		xml:"loadXML",
		skin:"setSkin"
	},{});
	
})();

dhtmlXTreeObject.prototype._dp_init=function(dp){
	dp.attachEvent("insertCallback", function(upd, id, parent) {
		var data = dhx4.ajax.xpath(".//item",upd);
		var text = data[0].getAttribute('text');
		this.obj.insertNewItem(parent, id, text, 0, 0, 0, 0, "CHILD");
	});

	dp.attachEvent("updateCallback", function(upd, id, parent) {
		var data = dhx4.ajax.xpath(".//item",upd);
		var text = data[0].getAttribute('text');
		this.obj.setItemText(id, text);
		if (this.obj.getParentId(id) != parent) {
			this.obj.moveItem(id, 'item_child', parent);
		}
		this.setUpdated(id, true, 'updated');
	});

	dp.attachEvent("deleteCallback", function(upd, id, parent) {
		this.obj.setUserData(id, this.action_param, "true_deleted");
		this.obj.deleteItem(id, false);
	});
	
	dp._methods=["setItemStyle","","changeItemId","deleteItem"];
    this.attachEvent("onEdit",function(state,id){
        if (state==3)
            dp.setUpdated(id,true)
		return true;
	});
    this.attachEvent("onDrop",function(id,id_2,id_3,tree_1,tree_2){
    	if (tree_1==tree_2)
        	dp.setUpdated(id,true);
    });
    this._onrdlh=function(rowId){
		var z=dp.getState(rowId);
		if (z=="inserted") {  dp.set_invalid(rowId,false); dp.setUpdated(rowId,false);	return true; }
		if (z=="true_deleted")  { dp.setUpdated(rowId,false); return true; }

		dp.setUpdated(rowId,true,"deleted")
		return false;
	};
	this._onradh=function(rowId){
		dp.setUpdated(rowId,true,"inserted")
	};
	dp._getRowData=function(rowId){
		var data = {};
		var z=this.obj._globalIdStorageFind(rowId);
		var z2=z.parentObject;
			
		var i=0;
		for (i=0; i<z2.childsCount; i++)
			if (z2.childNodes[i]==z) break;
		
		data["tr_id"] = z.id;
		data["tr_pid"] = z2.id;
		data["tr_order"] = i;
		data["tr_text"] = z.span.innerHTML;
		
		z2=(z._userdatalist||"").split(",");
		for (i=0; i<z2.length; i++)
			data[z2[i]]=z.userData["t_"+z2[i]];
			
    	return data;
	};	
};

//(c)dhtmlx ltd. www.dhtmlx.com
if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype.attachTree = function(rootId) {
		
		this.callEvent("_onBeforeContentAttach",["tree"]);
		
		var obj = document.createElement("DIV");
		obj.style.width = "100%";
		obj.style.height = "100%";
		obj.style.position = "relative";
		obj.style.overflow = "hidden";
		
		this._attachObject(obj);
		
		this.dataType = "tree";
		this.dataObj = new dhtmlXTreeObject(obj, "100%", "100%", (rootId||0));
		this.dataObj.setSkin(this.conf.skin);
		
		// cosmetic fix
		this.dataObj.allTree.childNodes[0].style.marginTop = "2px";
		this.dataObj.allTree.childNodes[0].style.marginBottom = "2px";
		
		//obj.style.overflow = "auto";
		obj = null;
		
		this.callEvent("_onContentAttach",[]);
		
		return this.dataObj;
		
	};
	
}

