/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_passw(cell){
	
	if (cell){
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
	}
	
	this.edit = function(){
					this.cell.innerHTML = "";
					this.cell.atag="INPUT";
					this.val = this.getValue();
					this.obj = document.createElement(this.cell.atag);
					this.obj.style.height = (this.cell.offsetHeight-(_isIE?6:4))+"px";
                    this.obj.className="dhx_combo_edit";
					this.obj.type = "password";
				   	this.obj.wrap = "soft";
					this.obj.style.textAlign = this.cell.align;
					this.obj.onclick = function(e){(e||event).cancelBubble = true}
					this.obj.onmousedown = function(e){(e||event).cancelBubble = true}
					this.obj.value = this.cell._rval||"";
					this.cell.appendChild(this.obj);
				  	if (_isFF) {
						this.obj.style.overflow="visible";
						if ((this.grid.multiLine)&&(this.obj.offsetHeight>=18)&&(this.obj.offsetHeight<40)){
							this.obj.style.height="36px";
							this.obj.style.overflow="scroll";
						}
					}
                    this.obj.onselectstart=function(e){  if (!e) e=event; e.cancelBubble=true; return true;  };
					this.obj.focus()
  					this.obj.focus()
					
					
					
	}
	this.getValue = function(){
        return this.cell._rval;
	}
	this.setValue = function(val){
			var str = "*****";
			this.cell.innerHTML  = str;
			this.cell._rval=val;
					
						
	}

	this.detach = function(){
					this.setValue(this.obj.value);
					return this.val!=this.getValue();
	}

}
eXcell_passw.prototype = new eXcell;
//(c)dhtmlx ltd. www.dhtmlx.com
