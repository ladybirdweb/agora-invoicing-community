/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_num(cell){
	try{
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
	}catch(er){}
	this.edit = function(){
					this.val = this.getValue();
					this.obj = document.createElement(_isKHTML?"INPUT":"TEXTAREA");
                    this.obj.className="dhx_combo_edit";
					this.obj.style.height = (this.cell.offsetHeight-4)+"px";
					this.obj.wrap = "soft";
					this.obj.style.textAlign = this.cell.align;
					this.obj.onclick = function(e){(e||event).cancelBubble = true}
					this.obj.value = this.val;
					this.cell.innerHTML = "";
					this.cell.appendChild(this.obj);
                    this.obj.onselectstart=function(e){  if (!e) e=event; e.cancelBubble=true; return true;  };
					this.obj.focus()
					this.obj.focus()
				}
	this.getValue = function(){
		
        if ((this.cell.firstChild)&&(this.cell.firstChild.tagName=="TEXTAREA"))
            return this.cell.firstChild.value;
        else
		return this.grid._aplNFb(this.cell.innerHTML.toString()._dhx_trim(),this.cell._cellIndex);
	}
	 this.setValue = function(val){
		var re = new RegExp("[a-z]|[A-Z]","i")
		if(val.match(re)) val = "&nbsp;";
				
		this.cell.innerHTML = val;
		
	}

	this.detach = function(){
                    var tv=this.obj.value;
					this.setValue(tv);
					return this.val!=this.getValue();
				}
}
eXcell_num.prototype = new eXcell;
//(c)dhtmlx ltd. www.dhtmlx.com
