/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/


//readonly
function eXcell_mro(cell){
	this.cell = cell;
    this.grid = this.cell.parentNode.grid;
	this.edit = function(){}
}
eXcell_mro.prototype = new eXcell;
eXcell_mro.prototype.getValue = function(){
						return this.cell.childNodes[0].innerHTML._dhx_trim();//innerText;
					}
eXcell_mro.prototype.setValue = function(val){
                        if (!this.cell.childNodes.length){
                            this.cell.style.whiteSpace='normal';
                            this.cell.innerHTML="<div style='height:100%; white-space:nowrap; overflow:hidden;'></div>";
                            }

						if(!val || val.toString()._dhx_trim()=="")
							val="&nbsp;"
						this.cell.childNodes[0].innerHTML = val;
				}
//(c)dhtmlx ltd. www.dhtmlx.com



