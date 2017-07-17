/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/**
*	@desc: enables smart rendering mode (usefull for big trees with lots f items on each level)
*   @edition: professional
*	@type: public
*/
dhtmlXTreeObject.prototype.enableSmartRendering=function(){
	this.enableSmartXMLParsing(true);
	this._srnd=true;
	this.itemHeight=18;
	var that=this;
	this.allTree.onscroll=function(){
		if (that._srndT) return;
		that._srndT=window.setTimeout(function(){
			that._srndT=null;
			that._renderState();
			
		},300);
	};
	
	this.attachEvent("onXLE",function(){
		that._renderState();
	});
	this._singleTimeSRND();
}

dhtmlXTreeObject.prototype._renderState=function(){ 
		//var z=this.allTree.parentNode;
		//var t=z.removeChild(this.allTree);
		
		
		if (!this._idpull[this.rootId]._sready) 
			this.prepareSR(this.rootId,true);
		var top=this.allTree.scrollTop;
		var pos=Math.floor(top/this.itemHeight);
		var height=Math.ceil(this.allTree.offsetHeight/this.itemHeight);
		
		this._group_render=true;
		this._getItemByPos(top,this.itemHeight,height,null,false,this._renderItemSRND);
		this._group_render=false;
		
		//z.appendChild(this.allTree);
}

dhtmlXTreeObject.prototype._renderItemSRND=function(a,b){ 
			if (!a.span){
				//render row
				a.span=-1;
				var z=a.parentObject.htmlNode.childNodes[0].childNodes;
				var count=b*this.itemHeight; var x=null;
				for (var i=1; i<z.length; i++) {
					
					x=z[i]; 
					var y=x.nodem?this.itemHeight:(x.offsetHeight||parseInt(x.childNodes[1].firstChild.style.height));
					
					count-=y;
					if (count<0) {
						if (count==-1) { count++; continue; } //magic
						var h=x.childNodes[1].firstChild;
					//	console.log("top: "+this.allTree.scrollTop+",original: "+y+",stop at:"+count+", current height:"+(parseInt(h.style.height))+",new height:"+(parseInt(h.style.height)-(y-Math.abs(count)+this.itemHeight))+"px");
						h.style.height=(parseInt(h.style.height)-(y-Math.abs(count)+this.itemHeight))+"px";
						if (Math.abs(count)!=y){
						//	console.log("add new as "+(count+y));
							var fill=this._drawNewHolder(count+y,true);
							x.parentNode.insertBefore(fill,x)
							
						}							
					x.tr={nextSibling:x};
					break;
					}				
				}
				
				if (h && h.style.height!="0px" && !x.offsetHeight)
					{ var rh=this._hAdI; this._hAdI=true; }
				this._parseItem(a._sxml,a.parentObject,null,x);
				if (h && h.style.height!="0px" && !x.offsetHeight)
					{ this._hAdI=rh; }
				if (a.unParsed) this._correctPlus(a);
				if (h && h.style.height=="0px") x.parentNode.removeChild(x);
			}			
}

dhtmlXTreeObject.prototype._buildSRND=function(z,skipParsing){ 
	if (z.parentObject) 
		this._globalIdStorageFind(z.parentObject.id);
	if (!this._idpull[this.rootId]._sready) 
			this.prepareSR(this.rootId,true);
		
	this._renderItemSRND(z,this._getIndex(z));
	if ((z.unParsed)&&(!skipParsing))
        this.reParse(z,0);
                    
	if (!z.prepareSR)
		this.prepareSR(z.id);
}

dhtmlXTreeObject.prototype._getIndex=function(z){
       for (var a=0; a<z.parentObject.childsCount; a++)
       if (z.parentObject.childNodes[a]==z) return a;
};

dhtmlXTreeObject.prototype.prepareSR=function(it,mode){ 
	it=this._idpull[it];
	if (it._sready) return;
    var tr=this._drawNewHolder(this.itemHeight*it.childsCount,mode);
	it.htmlNode.childNodes[0].appendChild(tr);
	it._sready=true;
	//it.tr=tr; tr.nodem=it;
}



dhtmlXTreeObject.prototype._drawNewHolder=function(s,mode){ 
	var t=document.createElement("TR");
	var b=document.createElement("TD");
	var b2=document.createElement("TD");
	var z=document.createElement("DIV");
	z.innerHTML="&nbsp;";
	b.appendChild(z)
	t.appendChild(b2); t.appendChild(b);
	if (!mode){
		t.style.display="none";
	}
	
	z.style.height=s+'px';
	return t;
}

dhtmlXTreeObject.prototype._getNextNodeSR=function(item,mode){
   if ((!mode)&&(item.childsCount)) return item.childNodes[0];
   if (item==this.htmlNode)
      return -1;
   if ((item.tr)&&(item.tr.nextSibling)&&(item.tr.nextSibling.nodem))
   return item.tr.nextSibling.nodem;

   return this._getNextNode(item.parentObject,true);
};

dhtmlXTreeObject.prototype._getItemByPos=function(pos,h,l,i,m,f){
	/*
		current implementation can be slow in case of deep hierarchy, in future we can move 
		counter login in HideShow function, so each top level item will know it real position
	*/
	if (!i){
		this._pos_c=pos;
		i=this._idpull[this.rootId];
	}
	
	for (var j=0; j<i.childsCount; j++){
		this._pos_c-=h;
		if (this._pos_c<=0) m=true;
		if (m) {
			f.apply(this,[i.childNodes[j],j]);
			l--;}
		if (l<0) return l;
		if (i.childNodes[j]._open){
			l=this._getItemByPos(null,h,l,i.childNodes[j],m,f);
		if (l<0) return l;
		}
	}
	return l;
}


   dhtmlXTreeObject.prototype._addItemSRND=function(pid,id,p){
   		var parentObject=this._idpull[pid];
        var Count=parentObject.childsCount;
        var Nodes=parentObject.childNodes;



        Nodes[Count]=new dhtmlXTreeItemObject(id,"",parentObject,this,null,1);
		itemId = Nodes[Count].id;
		Nodes[Count]._sxml=p.clone();

        parentObject.childsCount++;
   	}
   	
   	
dhtmlXTreeObject.prototype._singleTimeSRND=function(){ 
    this._redrawFrom=function(){}
    var _originalTreeItem=dhtmlXTreeItemObject;
    this._singleTimeSRND=function(){};
	window.dhtmlXTreeItemObject=function(itemId,itemText,parentObject,treeObject,actionHandler,mode){
		if (!treeObject._srnd) {
			return _originalTreeItem.call(this,itemId,itemText,parentObject,treeObject,actionHandler,mode);
		}
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
   
	if (itemId==treeObject.rootId){
		if (this.treeNod.checkBoxOff ) this.htmlNode=this.treeNod._createItem(1,this,mode);
   		else  this.htmlNode=this.treeNod._createItem(0,this,mode);
   		this.htmlNode.objBelong=this;
	}
   
   return this;
};     

/*

Updates to existing code


*/
this.setCheckSR=this.setCheck;
this.setCheck=function(itemId,state){
	this._globalIdStorageFind(itemId);
	return this.setCheckSR(itemId,state);
};
this._get_srnd_p=function(id){
	var p=[];
	while(id!=this.rootId){
		var pid=this.getParentId(id);
		for (var i=0; i < this._idpull[pid].childsCount; i++) 
   			if (this._idpull[pid].childNodes[i].id==id){
   	 			p.push([pid,i])
   	 			break;
   	 		}
   	 	id=pid;
	}
	p.reverse();
	return p
};
this._get_srnd_p_last=function(id,p,mask){
	p=p||[];

	var pos=0;
	while (true){
		var i=this._idpull[id];
		if (i._sxml && this.findStrInXML(i._sxml.d,"text",mask))
			this._globalIdStorageFind(i.id);	
		var pos=i.childsCount;
		if (!pos) break;
		p.push([id,pos-1])
		id=i.childNodes[pos-1].id;
	}

	return p;
};
this._get_prev_srnd=function(p,mask){
    var last;
	if (!p.length){
        p.push.apply(p,this._get_srnd_p_last(this.rootId,null,mask));
        last=p[p.length-1];
        return this._idpull[last[0]].childNodes[last[1]];
    }
	last=p[p.length-1];
	if (last[1]) {
        last[1]--;
		var curr=this._idpull[last[0]].childNodes[last[1]];
		this._get_srnd_p_last(curr.id,p,mask);
		var last=p[p.length-1];
		return this._idpull[last[0]].childNodes[last[1]];
	} else {
		p.pop();
		if (!p.length) return this._get_prev_srnd(p,mask)
		var last=p[p.length-1];
		return this._idpull[last[0]].childNodes[last[1]];
	}
};

this._get_next_srnd=function(p,skip){
	if (!p.length){
		p.push([this.rootId,0]);
		return this._idpull[this.rootId].childNodes[0];
	}		
		
var last=p[p.length-1];
	var curr=this._idpull[last[0]].childNodes[last[1]];
	if (curr.childsCount && !skip){
		p.push([curr.id,0]);
		return curr.childNodes[0];
	}
	last[1]++;
	var curr=this._idpull[last[0]].childNodes[last[1]];
	if (curr)
		return curr;
	p.pop();
	if (!p.length)
		return this.htmlNode;
	
	return this._get_next_srnd(p,true);
};
this._findNodeByLabel=function(searchStr,direction,fromNode){
   //default next|prev locators is not reliable
   var searchStr=searchStr.replace(new RegExp("^( )+"),"").replace(new RegExp("( )+$"),"");
   searchStr =  new RegExp(searchStr.replace(/([\*\+\\\[\]\(\)]{1})/gi,"\\$1").replace(/ /gi,".*"),"gi");

   //get start node
   if (!fromNode)
      {
      fromNode=this._selected[0];
      if (!fromNode) fromNode=this.htmlNode;
      }
   var startNode=fromNode;
   var p=this._get_srnd_p(startNode.id);
   while (fromNode=(direction?this._get_prev_srnd(p,searchStr):this._get_next_srnd(p))){
   		if (fromNode.label){
   			if (fromNode.label.search(searchStr)!=-1) return fromNode
   		} else {
   			if (fromNode._sxml){
   				if (fromNode._sxml.get("text").search(searchStr)!=-1)
   					return fromNode;
   				if (this.findStrInXML(fromNode._sxml.d,"text",searchStr))
					this._globalIdStorageFind(fromNode.id);	
   			} 
   		}
   		
		if ((fromNode.unParsed)&&(this.findStrInXML(fromNode.unParsed.d,"text",searchStr)))
			this.reParse(fromNode);	
		
   		if (startNode.id==fromNode.id) break;
        if(direction&&p.length==1&&p[0][1]==0)
             break;

   }
   
   return null;
};
this.deleteChildItems=function(id){  
	if (this.rootId==id){
		this._selected=new Array();
		this._idpull={};
		this._p=this._pos_c=this._pullSize=null;
		this.allTree.removeChild(this.htmlNode.htmlNode);
		
		this.htmlNode=new dhtmlXTreeItemObject(this.rootId,"",0,this);
   		this.htmlNode.htmlNode.childNodes[0].childNodes[0].style.display="none";
   		this.htmlNode.htmlNode.childNodes[0].childNodes[0].childNodes[0].className="hiddenRow";
   		
   		this.allTree.insertBefore(this.htmlNode.htmlNode,this.selectionBar);
		if(_isFF){
			this.allTree.childNodes[0].width="100%";
			this.allTree.childNodes[0].style.overflow="hidden";
		}
	}
}
this._HideShow=function(itemObject,mode){
      if ((this.XMLsource)&&(!itemObject.XMLload)) {
            if (mode==1) return; //close for not loaded node - ignore it
            itemObject.XMLload=1;
            this._loadDynXML(itemObject.id);
            return; };
//#__pro_feature:01112006{
//#smart_parsing:01112006{
		if (!itemObject.span)
			this._buildSRND(itemObject);
		
        if (itemObject.unParsed){
        	this.reParse(itemObject);
			this.prepareSR(itemObject.id);
        }
        
        if (itemObject.childsCount==0) return;
//#}
//#}
      var Nodes=itemObject.htmlNode.childNodes[0].childNodes; var Count=Nodes.length;
      if (Count>1){
         if ( ( (Nodes[1].style.display!="none") || (mode==1) ) && (mode!=2) ) {
//nb:solves standard doctype prb in IE
          this.allTree.childNodes[0].border = "1";
          this.allTree.childNodes[0].border = "0";
         var nodestyle="none";
         itemObject._open=false;
         }
         else  {
	     	var nodestyle="";
         	itemObject._open=true;
     	}

      for (var i=1; i<Count; i++)
         Nodes[i].style.display=nodestyle;
         
      this._renderState();
      }
      this._correctPlus(itemObject);
   }
 }

//(c)dhtmlx ltd. www.dhtmlx.com



