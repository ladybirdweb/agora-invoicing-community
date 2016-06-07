/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/


/*
Textfield with Button eXcell v.1.0  for dhtmlxGrid
(c)DHTMLX LTD. 2005


The corresponding  cell value in XML should be

Samples:

<cell>IN637237-23</cell>
<cell>158</cell>
*/


function eXcell_wbut(cell){
	this.cell = cell;
    this.grid = this.cell.parentNode.grid;
	this.edit = function(){
		var val = this.getValue().toString();
		this.obj = document.createElement("INPUT");
			this.obj.readOnly = true;
			this.obj.style.width = "60px";
			this.obj.style.height = (this.cell.offsetHeight-(this.grid.multiLine?5:4))+"px";
			this.obj.style.border = "0px";
			this.obj.style.margin = "0px";
			this.obj.style.padding = "0px";
			this.obj.style.overflow = "hidden";
			this.obj.style.fontSize = _isKHTML?"10px":"12px";
			this.obj.style.fontFamily = "Arial";
			this.obj.wrap = "soft";
			this.obj.style.textAlign = this.cell.align;
			this.obj.onclick = function(e){(e||event).cancelBubble = true}
			this.cell.innerHTML = "";
			this.cell.appendChild(this.obj);
			this.obj.onselectstart=function(e){  if (!e) e=event; e.cancelBubble=true; return true;  };
			this.obj.style.textAlign = this.cell.align;
			this.obj.value=val;
			this.obj.focus()
			this.obj.focus()
		this.cell.appendChild(document.createTextNode(" ")); // Create space between text box and button
		var	butElem = document.createElement('input');        // This is the button DOM code
			if(_isIE){
				butElem.style.height = (this.cell.offsetHeight-(this.grid.multiLine?5:4))+"px";
				butElem.style.lineHeight = "5px";
			}else{
				butElem.style.fontSize = "8px";
				butElem.style.width = "10px";
				butElem.style.marginTop = "-5px"
			}

			butElem.type='button'
			butElem.name='Lookup'
			butElem.value='...'
			var inObj = this.obj;
			var inCellIndex = this.cell.cellIndex
			var inRowId = this.cell.parentNode.idd
			var inGrid = this.grid
			var inCell = this;
			this.dhx_m_func=this.grid.getWButFunction(this.cell._cellIndex);
            butElem.onclick = function (e){inCell.dhx_m_func(inCell,inCell.cell.parentNode.idd,inCell.cell._cellIndex,val)};
		this.cell.appendChild(butElem);
	}
	this.detach = function(){
					this.setValue(this.obj.value);
					return this.val!=this.getValue();
  }
}
eXcell_wbut.prototype = new eXcell;

dhtmlXGridObject.prototype.getWButFunction=function(index){
	if (this._wbtfna) return this._wbtfna[index];
	else return (function(){});
}
dhtmlXGridObject.prototype.setWButFunction=function(index,func){
	if (!this._wbtfna) this._wbtfna=new Array();
	this._wbtfna[index]=func;
}
//(c)dhtmlx ltd. www.dhtmlx.com
