/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_context(cell){
	if (cell){
		this.cell = cell;
    	this.grid = this.cell.parentNode.grid;
    
    	if (!this.grid._sub_context) return;
    	this._sub=this.grid._sub_context[cell._cellIndex];
    	if (!this._sub) return;
    	this._sindex=this._sub[1];
    	this._sub=this._sub[0];
    }
	
	this.getValue = function(){
		return _isIE?this.cell.innerText:this.cell.textContent;
	}
	this.setValue = function(val){
		this.cell._val=val;
		var item  = this._sub.itemPull[this._sub.idPrefix+this.cell._val];
		val = item?item.title:val;
		this.setCValue((val||"&nbsp;"),val);
	}
	this.edit = function(){
		var arPos = this.grid.getPosition(this.cell);//,this.grid.objBox
		
		this._sub.showContextMenu(arPos[0]+this.cell.offsetWidth,arPos[1]);
		var a=this.grid.editStop;
		this.grid.editStop=function(){};
		this.grid.editStop=a;
	}
	this.detach=function(){
		if (this.grid._sub_id != null) {
			var old=this.cell._val;
			this.setValue(this.grid._sub_id);
			this.grid._sub_id = null;
			return this.cell._val!=old;
		}
		this._sub.hideContextMenu();
	}
}
eXcell_context.prototype = new eXcell;


dhtmlXGridObject.prototype.setSubContext=function(ctx,s_index,t_index){
	var that=this;
	ctx.attachEvent("onClick",function(id,value){
		that._sub_id = id;
		that.editStop();
		ctx.hideContextMenu();
		return true;
	});
	if (!this._sub_context) 
		this._sub_context=[];
	this._sub_context[s_index]=[ctx,t_index];
	ctx.hideContextMenu();
};
//(c)dhtmlx ltd. www.dhtmlx.com
