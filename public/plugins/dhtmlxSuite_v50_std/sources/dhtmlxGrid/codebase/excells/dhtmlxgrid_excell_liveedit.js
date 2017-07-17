/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_liveedit(cell)
{
	if (cell) {
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
	}

	this.edit = function() 
	{
		this.cell.inputObj.focus();
		this.cell.inputObj.focus();
	}

	this.detach = function()
	{
		this.setValue(this.cell.inputObj.value); }

	this.getValue = function()
	{
		return this.cell.inputObj ? this.cell.inputObj.value : '';
	}
	this.destructor = function() {}

	this.onFocus = function()
	{
		var res = this.grid.callEvent('onEditCell', [0, this.cell.parentNode.idd, this.cell._cellIndex]);
		if (res === false)
			this.cell.inputObj.blur();
	}

	this.onBlur = function()
	{
		var res = this.grid.callEvent('onEditCell', [2, this.cell.parentNode.idd, this.cell._cellIndex]);
		this.detach();
	}

	this.onChange = function()
	{
		var res = this.grid.callEvent( "onCellChanged", [this.cell.parentNode.idd, this.cell._cellIndex, this.cell.inputObj.value] );
		this.detach();
	}
}



eXcell_liveedit.prototype = new eXcell_ed;
eXcell_liveedit.prototype.setValue = function(val)
	{
		var self = this;
		this.cell.innerHTML = '<input type="text" value="" style="width:100%;" />';
		
		this.cell.inputObj = this.cell.firstChild;
		this.cell.inputObj = this.cell.firstChild;
//		this.inputObj.style.border = '1px solid ';
		this.cell.inputObj.value = val;
		this.cell.inputObj.onfocus = function() {self.onFocus()}
		
		
		this.cell.inputObj.onblur = function() {self.onFocus()}
		this.cell.inputObj.onchange = function() {self.onChange()}
	}
if (window.eXcell_math){ 
	eXcell_liveedit.prototype.setValueA=eXcell_liveedit.prototype.setValue;
	eXcell_liveedit.prototype.setValue=eXcell_math.prototype._NsetValue;
}
//(c)dhtmlx ltd. www.dhtmlx.com
