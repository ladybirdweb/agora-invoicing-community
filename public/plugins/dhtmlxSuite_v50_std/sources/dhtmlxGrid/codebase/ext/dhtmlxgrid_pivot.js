/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/**
*   @desc: hide pivot table related to grid, if any exists, switch grid back to normal mode
*   @type: public
*   @topic: 0
*/ 

dhtmlXGridObject.prototype.hidePivot=function(cont){ 
	if (this._pgridCont){
		if (this._pgrid) this._pgrid.destructor();
		var c=this._pgridCont.parentNode;
		c.innerHTML="";
		if (c.parentNode==this.entBox) 
			this.entBox.removeChild(c);
		this._pgrid=this._pgridSelect=this._pUNI=this._pgridCont=null;
		
	}
}
/**
*   @desc: show pivot table based on grid
*   @type: public
*	@param: cont - html container in which pivot rendered, but default pivot will be rendered over existing grid
*	details: collection of settings; details.column_list - list of columns used in pivot selects; details.readonly - created pivot with fixed configuration, details.action, details.value, action.x, action.y - default values for 4 pivot's selects 
*   @topic: 0
*/ 
dhtmlXGridObject.prototype.makePivot=function(cont,details){
	details=details||{};
	this.hidePivot();
	
	if (!cont){
			var cont=document.createElement("DIV");
			cont.style.cssText="position:absolute; top:0px; left:0px;background-color:white;";
			cont.style.height=this.entBox.offsetHeight+"px";
			cont.style.width=this.entBox.offsetWidth+"px";
			if (this.entBox.style.position!="absolute")
				this.entBox.style.position="relative";
			this.entBox.appendChild(cont);
	}
	
	if (typeof(cont)!="object") cont=document.getElementById(cont)
   
    if (details.column_list)
    	this._column_list=details.column_list;
    else{
		this._column_list=[];
		for (var i=0; i<this.hdr.rows[1].cells.length; i++)
   			this._column_list.push(this.hdr.rows[1].cells[i][_isIE?"innerText":"textContent"])
   	}
   		
   	var that = this;
	cont.innerHTML="<table cellspacing='0' cellpadding='0'><tr><td style='width:160px' align='center'></td><td>&nbsp;&nbsp;&nbsp;</td><td></td></tr></table><div></div>";
	var z1=this.makePivotSelect(this._column_list);
		z1.style.width="80px";
		z1.onchange=function(){
			if (this.value!=-1)
				that._pivotS.value=this.value;
			else that._pivotS.value="";
			
			that._reFillPivotLists();
			that._renderPivot2();
		}		
	var z2=this.makePivotSelect(this._column_list);
		z2.onchange=function(){
			if (this.value!=-1)
				that._pivotS.x=this.value;
			else that._pivotS.x="";
			that._reFillPivotLists();
			that._renderPivot()
		}		
	var z3=this.makePivotSelect(this._column_list);
		z3.onchange=function(){
			if (this.value!=-1)
				that._pivotS.y=this.value;
			else that._pivotS.y="";
			that._reFillPivotLists();
			that._renderPivot()
		}
	var z4=this.makePivotSelect(["Sum","Min","Max","Average","Count"],-1);
		z4.style.width="70px";
		z4.onchange=function(){
			if (this.value!=-1)
				that._pivotS.action=this.value;
			else that._pivotS.action=null;
			
			that._renderPivot2();
		}
		
	if (details.readonly)
		z1.disabled=z2.disabled=z3.disabled=z4.disabled=true;
	
	cont.firstChild.rows[0].cells[0].appendChild(z4);
	cont.firstChild.rows[0].cells[0].appendChild(z1);
	cont.firstChild.rows[0].cells[2].appendChild(z2);
	
	var gr=cont.childNodes[1];
	gr.style.width=cont.offsetWidth+"px";
	gr.style.height=cont.offsetHeight-20+"px";
	gr.style.overflow="hidden";
	this._pgridCont=gr;
	this._pgridSelect=[z1,z2,z3,z4];
	
	this._pData=this._fetchPivotData();
	this._pUNI=[];
	this._pivotS={ action:(details.action||"0"), value:(typeof details.value != "undefined" ? (details.value||"0") : null), x:(typeof details.x != "undefined" ? (details.x||"0") : null), y:(typeof details.y != "undefined" ? (details.y||"0") : null) };
	
	z1.value=this._pivotS.value;
	z2.value=this._pivotS.x;
	z3.value=this._pivotS.y;
	z4.value=this._pivotS.action;
	
	that._reFillPivotLists();
	this._renderPivot();
}

dhtmlXGridObject.prototype._fetchPivotData=function(){ 
	var z=[];
	for (var i=0; i<this._cCount; i++) {
		var d=[];
		for (var j=0; j<this.rowsCol.length; j++) {
			if (this.rowsCol[j]._cntr) continue;
			d.push(this.cells2(j,i).getValue());	//TODO : excell caching 
		}
		z.push(d)
	}
	return z;
}

dhtmlXGridObject.prototype._renderPivot=function(){ 
	if (_isIE) this._pgridSelect[2].removeNode(true)
	if (this._pgrid)  
		this._pgrid.destructor();
	
	this._pgrid=new dhtmlXGridObject(this._pgridCont);
	this._pgrid.setImagePath(this.imgURL);
	this._pgrid.attachEvent("onBeforeSelect",function(){return false;});
	if (this._pivotS.x){
		var l=this._getUniList(this._pivotS.x);
		var s=[160];
		for (var i=0; i < l.length; i++) 
			s.push(100);
		l=[""].concat(l)
		this._pgrid.setHeader(l);
		this._pgrid.setInitWidths(s.join(","));
	} else {
		this._pgrid.setHeader("");
		this._pgrid.setInitWidths("160");
	}
		
	this._pgrid.init();
	this._pgrid.setEditable(false);
	this._pgrid.setSkin(this.entBox.className.replace("gridbox gridbox_",""));

	var t=this._pgrid.hdr.rows[1].cells[0];
	if (t.firstChild && t.firstChild.tagName=="DIV") t=t.firstChild;
	t.appendChild(this._pgridSelect[2]);
	this._pgrid.setSizes();
	
	if (this._pivotS.y){
		var l=this._getUniList(this._pivotS.y);
		for (var i=0; i < l.length; i++) {
			this._pgrid.addRow(this._pgrid.uid(),[l[i]],-1);
		};
	} else {
		this._pgrid.addRow(1,"not ready",1);
	}	
	this._renderPivot2();
}
dhtmlXGridObject.prototype._pivot_action_0=function(a,b,c,av,bv,data){ 
	var ret=0;
	var resA=data[a];
	var resB=data[b];
	var resC=data[c];
	for (var i = resA.length - 1; i >= 0; i--)
		if (resA[i]==av && resB[i]==bv) 
			ret+=this.parseFloat(resC[i]);
	return ret;
}
dhtmlXGridObject.prototype._pivot_action_1=function(a,b,c,av,bv,data){ 
	ret=9999999999;
	var resA=data[a];
	var resB=data[b];
	var resC=data[c];
	
	for (var i = resA.length - 1; i >= 0; i--)
		if (resA[i]==av && resB[i]==bv) 
			ret=Math.min(this.parseFloat(resC[i]),ret);
	if (ret==9999999999) ret="";
	return ret;
}
dhtmlXGridObject.prototype._pivot_action_2=function(a,b,c,av,bv,data){ 
	
	ret=-9999999999;
	var resA=data[a];
	var resB=data[b];
	var resC=data[c];
	for (var i = resA.length - 1; i >= 0; i--)
		if (resA[i]==av && resB[i]==bv) 
			ret=Math.max(this.parseFloat(resC[i]),ret);
	if (ret==-9999999999) ret="";
	return ret;
}
dhtmlXGridObject.prototype._pivot_action_3=function(a,b,c,av,bv,data){ 
	var ret=0;
	var count=0;
	var resA=data[a];
	var resB=data[b];
	var resC=data[c];
	for (var i = resA.length - 1; i >= 0; i--)
		if (resA[i]==av && resB[i]==bv) {
			ret+=this.parseFloat(resC[i]);
			count++;
		}
	return count?ret/count:"";
}
dhtmlXGridObject.prototype._pivot_action_4=function(a,b,c,av,bv,data){ 
	var ret=0;
	var count=0;
	var resA=data[a];
	var resB=data[b];
	var resC=data[c];
	for (var i = resA.length - 1; i >= 0; i--)
		if (resA[i]==av && resB[i]==bv) {
			ret++;
		}
	return ret;
}
dhtmlXGridObject.prototype.parseFloat = function(val){
	val = parseFloat(val);
	if (isNaN(val)) return 0;
	return val;
}
	
dhtmlXGridObject.prototype._renderPivot2=function(){ 
	if (!(this._pivotS.x && this._pivotS.y && this._pivotS.value && this._pivotS.action)) return;

	var action=this["_pivot_action_"+this._pivotS.action];
	var x=this._getUniList(this._pivotS.x);
	var y=this._getUniList(this._pivotS.y);
	
	for (var i=0; i < x.length; i++) {
		for (var j=0; j < y.length; j++) {
			this._pgrid.cells2(j,i+1).setValue(Math.round(action(this._pivotS.x,this._pivotS.y,this._pivotS.value,x[i],y[j],this._pData)*100)/100);
		};
		
	};
}


dhtmlXGridObject.prototype._getUniList=function(col){ 
    if (!this._pUNI[col]){
    	var t={};
    	var a=[];
    	for (var i = this._pData[col].length - 1; i >= 0; i--){
    		t[this._pData[col][i]]=true;
    	}
    	for (var n in t) 
      		if (t[n]===true) a.push(n);
      	this._pUNI[col]=a.sort();
   	}
   	
   	return this._pUNI[col];
}

dhtmlXGridObject.prototype._fillPivotList=function(z,list,miss,v){ 
	if (!miss){
		miss={};
		v=-1;
	}
	z.innerHTML="";
	z.options[z.options.length]=new Option("-select-",-1);
	for (var i=0; i<list.length; i++){
		if (miss[i] || list[i]===null) continue;
		z.options[z.options.length]=new Option(list[i],i);
	}	
	z.value=parseInt(v);
}

dhtmlXGridObject.prototype._reFillPivotLists=function(){ 
	var s=[]; 	var v=[];
	for (var i=0; i<3; i++){
		s.push(this._pgridSelect[i]);
		v.push(s[i].value);
	}
	
	
	var t=this._reFfillPivotLists;
	var m={}; m[v[1]]=m[v[2]]=true;
	this._fillPivotList(s[0],this._column_list,m,v[0]);
	m={}; m[v[0]]=m[v[2]]=true;
	this._fillPivotList(s[1],this._column_list,m,v[1]);
	m={}; m[v[1]]=m[v[0]]=true;
	this._fillPivotList(s[2],this._column_list,m,v[2]);
	
	this._reFfillPivotLists=t;
	
}


dhtmlXGridObject.prototype.makePivotSelect=function(list,miss){ 
	var z=document.createElement("SELECT");
	this._fillPivotList(z,list,miss);
	z.style.cssText="width:150px; height:20px; font-family:Tahoma; font-size:8pt; font-weight:normal;";
	
	
	return z;
}
//(c)dhtmlx ltd. www.dhtmlx.com

