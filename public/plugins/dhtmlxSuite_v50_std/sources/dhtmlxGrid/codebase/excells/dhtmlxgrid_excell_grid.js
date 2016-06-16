/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_grid(cell){
	if (cell){
		this.cell = cell;
    	this.grid = this.cell.parentNode.grid;
    
    	if (!this.grid._sub_grids) return;
    	this._sub=this.grid._sub_grids[cell._cellIndex];
    	if (!this._sub) return;
    	this._sindex=this._sub[1];
    	this._sub=this._sub[0];
    }
	
	this.getValue = function(){
		return this.cell.val;
	}
	this.setValue = function(val){
		this.cell.val=val;
		
		if (this._sub.getRowById(val)) {
			val=this._sub.cells(val,this._sindex);
		if (val) val=val.getValue();
		else val="";
	 } 
		
		this.setCValue((val||"&nbsp;"),val);
		
	}
	this.edit = function(){ 
		this.val = this.cell.val;
		
		this._sub.entBox.style.display='block';
		var arPos = this.grid.getPosition(this.cell);//,this.grid.objBox
		this._sub.entBox.style.top=arPos[1]+"px";
		this._sub.entBox.style.left=arPos[0]+"px";
		this._sub.entBox.style.position="absolute";
		this._sub.setSizes();
		
		var a=this.grid.editStop;
		this.grid.editStop=function(){};
		if (this._sub.getRowById(this.cell.val)) 
			this._sub.setSelectedRow(this.cell.val);
		this._sub.setActive(true)
		
		this.grid.editStop=a;
	}
	this.detach=function(){
		var old=this.cell.val;
		this._sub.entBox.style.display='none';
		if (this._sub.getSelectedId()===null) return false;
		this.setValue(this._sub.getSelectedId());
		this.grid.setActive(true)
		return this.cell.val!=old;
	}
}
eXcell_grid.prototype = new eXcell;


dhtmlXGridObject.prototype.setSubGrid=function(grid,s_index,t_index){
		if (!this._sub_grids) 
			this._sub_grids=[];
		this._sub_grids[s_index]=[grid,t_index];
		grid.entBox.style.display="none";
		var that=this;

		grid.entBox.onclick = function(event) { (event || window.event).cancelBubble = true;return false; }
		grid.attachEvent("onRowSelect",function(id){
			that.editStop();
			return true;
		});
		grid._chRRS=false;
};
//(c)dhtmlx ltd. www.dhtmlx.com
