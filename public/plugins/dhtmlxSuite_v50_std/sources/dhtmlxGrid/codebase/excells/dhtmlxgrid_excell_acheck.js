/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/**
*	@desc: skined checkbox editor 
*	@returns: dhtmlxGrid cell editor object
*	@type: public
*/
function eXcell_acheck(cell){
	try{
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
		this.cell.obj = this;
	}catch(er){}
	this.changeState = function(){
					//nb:
					    if ((!this.grid.isEditable)||(this.cell.parentNode._locked)||(this.isDisabled())) return;
						if(this.grid.callEvent("onEditCell",[0,this.cell.parentNode.idd,this.cell._cellIndex])!=false){
							this.val = this.getValue()
							if(this.val=="1")
								this.setValue("<checkbox state='false'>")
							else
								this.setValue("<checkbox state='true'>")
								
							this.cell.wasChanged=true;								
							//nb:
							this.grid.callEvent("onEditCell",[1,this.cell.parentNode.idd,this.cell._cellIndex]);
							this.grid.callEvent("onCheck",[this.cell.parentNode.idd,this.cell._cellIndex,(this.val!='1')]);
                            this.grid.callEvent("onCheckbox",[this.cell.parentNode.idd,this.cell._cellIndex,(this.val!='1')]);

						}else{//preserve editing (not tested thoroughly for this editor)
							this.editor=null;
						}
				}
	this.getValue = function(){
						try{
							return this.cell.chstate.toString();
						}catch(er){
							return null;
						}
					}

	this.isCheckbox = function(){
						return true;
					}
	this.isChecked = function(){
						if(this.getValue()=="1")
							return true;
						else
							return false;
					}
	this.setChecked = function(fl){
	this.setValue(fl.toString())
	}
	this.detach = function(){
						return this.val!=this.getValue();
					}
    this.drawCurrentState=function(){
        if (this.cell.chstate==1)
            return "<div  onclick='(new eXcell_acheck(this.parentNode)).changeState(); (arguments[0]||event).cancelBubble=true;'  style='cursor:pointer; font-weight:bold; text-align:center; '><span style='height:8px; width:8px; background:green; display:inline-block;'></span>&nbsp;Yes</div>";
        else
            return "<div  onclick='(new eXcell_acheck(this.parentNode)).changeState(); (arguments[0]||event).cancelBubble=true;' style='cursor:pointer;  text-align:center; '><span style='height:8px; width:8px; background:red; display:inline-block;'></span>&nbsp;No</div>";
    }
}
eXcell_acheck.prototype = new eXcell;
eXcell_acheck.prototype.setValue = function(val){
                        //val can be int
                        val=(val||"").toString();
						if(val.indexOf("1")!=-1 || val.indexOf("true")!=-1){
							val = "1";
							this.cell.chstate = "1";
						}else{
							val = "0";
							this.cell.chstate = "0"
						}
						var obj = this;
						this.setCValue(this.drawCurrentState(),this.cell.chstate);
					}

//(c)dhtmlx ltd. www.dhtmlx.com
