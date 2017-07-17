/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/


function eXcell_stree(cell){
	if (cell){
		this.cell = cell;
    	this.grid = this.cell.parentNode.grid;
    
    	if (!this.grid._sub_trees) return;
    	this._sub=this.grid._sub_trees[cell._cellIndex];
    	if (!this._sub) return;
    	this._sub=this._sub[0];
    }
	
	this.getValue = function(){
		return this.cell._val;
	}
	this.setValue = function(val){
		this.cell._val=val;
		val = this._sub.getItemText(this.cell._val);
		this.setCValue((val||"&nbsp;"),val);
	}
	this.edit = function(){
		this._sub.parentObject.style.display='block';
		var arPos = this.grid.getPosition(this.cell);//,this.grid.objBox
		this._sub.parentObject.style.top=arPos[1]+"px";
		this._sub.parentObject.style.left=arPos[0]+"px";
		this._sub.parentObject.style.position="absolute";
		
		var a=this.grid.editStop;
		this.grid.editStop=function(){};
		
		this.grid.editStop=a;
	}
	this.detach=function(){
		this._sub.parentObject.style.display='none';
		if (this.grid._sub_id != null) {
			var old=this.cell._val;
			this.setValue(this._sub.getSelectedItemId());
			this.grid._sub_id = null;
			return this.cell._val!=old;
		}
	}
}
eXcell_stree.prototype = new eXcell;


dhtmlXGridObject.prototype.setSubTree=function(tree,s_index){
		if (!this._sub_trees) 
			this._sub_trees=[];
		this._sub_trees[s_index]=[tree];
		tree.parentObject.style.display="none";
		var that=this;
		tree.parentObject.onclick = function(event) {(event || window.event).cancelBubble = true;return false;}
		tree.ev_onDblClick=null;
		tree.attachEvent("onDblClick",function(id){
			that._sub_id = id;
			that.editStop();
			return true;
		});
		tree._chRRS=true;
};
//(c)dhtmlx ltd. www.dhtmlx.com

