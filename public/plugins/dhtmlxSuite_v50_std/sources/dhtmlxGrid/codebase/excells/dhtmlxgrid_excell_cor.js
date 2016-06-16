/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

//Combobox
function eXcell_cor(cell){
        if (cell){
      this.cell = cell;
      this.grid = this.cell.parentNode.grid;
      this.combo = this.grid.getCombo(this.cell._cellIndex);
      this.editable = true
   }
    this.shiftNext=function(){

        var z=this.list.options[this.list.selectedIndex+1];
        if (z) z.selected=true;
        this.obj.value=this.list.value;

        return true;
    }
    this.shiftPrev=function(){

        var z=this.list.options[this.list.selectedIndex-1];
        if (z) z.selected=true;

        this.obj.value=this.list.value;

       return true;
    }

   this.edit = function(){
                  this.val = this.getValue();
                  this.text = this.cell.innerHTML._dhx_trim();
                  var arPos = this.grid.getPosition(this.cell)//,this.grid.objBox)

                  this.obj = document.createElement("TEXTAREA");
                        this.obj.className="dhx_combo_edit";
                  this.obj.style.height=(this.cell.offsetHeight-4)+"px";

                     this.obj.wrap = "soft";
                     this.obj.style.textAlign = this.cell.align;
                     this.obj.onclick = function(e){(e||event).cancelBubble = true}
                     this.obj.value = this.text

                            this.list =  document.createElement("SELECT");
                           this.list.editor_obj = this;
                            this.list.className='dhx_combo_select';
                            this.list.style.width=this.cell.offsetWidth+"px";
                     this.list.style.left = arPos[0]+"px";//arPos[0]
                     this.list.style.top = arPos[1]+this.cell.offsetHeight+"px";//arPos[1]+this.cell.offsetHeight;
                     this.list.onclick = function(e){
                                          var ev = e||window.event;
                                          var cell = ev.target||ev.srcElement
                                                        //tbl.editor_obj.val=cell.combo_val;
                                                        if (cell.tagName=="OPTION") cell=cell.parentNode;
										  if (cell.value!=-1){
											  cell.editor_obj._byClick=true;
	                                        //  cell.editor_obj.setValue(cell.value);
                                              cell.editor_obj.editable=false;
                                              cell.editor_obj.grid.editStop();
											  }
                                          else {
										  	ev.cancelBubble=true;
											cell.editor_obj.obj.value="";											
											cell.editor_obj.obj.focus();
											}
                                       }
                     var comboKeys = this.combo.getKeys();

                     var selOptId=0;

					 this.list.options[0]=new Option(this.combo.get(comboKeys[0]),comboKeys[0]);
					 this.list.options[0].selected=true;

                     for(var i=1;i<comboKeys.length;i++){
                           var val = this.combo.get(comboKeys[i])
                                    this.list.options[this.list.options.length]=new Option(val,comboKeys[i]);
			                        if(comboKeys[i]==this.val)
                                        selOptId=this.list.options.length-1;
                                    }

                  document.body.appendChild(this.list)//nb:this.grid.objBox.appendChild(this.listBox);
                        this.list.size="6";
                        this.cstate=1;
                  if(this.editable){
                     this.cell.innerHTML = "";
                  }
                        else {
                            this.obj.style.width="1px";
                            this.obj.style.height="1px";
                            }
                     this.cell.appendChild(this.obj);
                            this.list.options[selOptId].selected=true;
                     //fix for coro - FF scrolls grid in incorrect position
                     if (this.editable){
                           this.obj.focus();
                              this.obj.focus();
                     }
                            if (!this.editable)
                                this.obj.style.visibility="hidden";
               }

   this.getValue = function(){
                  return ((this.cell.combo_value==window.undefined)?"":this.cell.combo_value);
               }
   this.getText = function(){
                  return this.cell.innerHTML;
               }
	this.getState=function(){
		return {prev:this.cell.__prev,now:this.cell.__now};
    }
   this.detach = function(){
                  if(this.val!=this.getValue()){
                     this.cell.wasChanged = true;
            }

                  if(this.list.parentNode!=null){
                     if ((this.obj.value._dhx_trim()!=this.text)||(this._byClick)){
					 	//cell data was changed
						var cval=this.list.value;
						if (!this._byClick)
							this.combo.values[this.combo.keys._dhx_find(cval)]=this.obj.value;
                        this.setValue(cval);
                     }else{
                        this.setValue(this.val);
                     }
                  }
                     if(this.list.parentNode)
                        this.list.parentNode.removeChild(this.list);
                     if(this.obj.parentNode)
                        this.obj.parentNode.removeChild(this.obj);

                  return this.val!=this.getValue();
               }
}
eXcell_cor.prototype = new eXcell;
eXcell_cor.prototype.setValue = function(val){
                  if ((val||"").toString()._dhx_trim()=="")
                     val=null

                       	var viVal=this.grid.getCombo(this.cell._cellIndex).get(val);
						if ((val==-1)&&(viVal=="")){
							this.combo.values[this.combo.keys._dhx_find(-1)]="Create new value";
							val=null;
						}


                        if (val!==null)
                        	this.setCValue( viVal,val);
                        else
                            this.setCValue("&nbsp;",val);

						this.cell.__prev=this.cell.__now;
						this.cell.__now={key:val,value:viVal};

                  this.cell.combo_value = val;
               }

//(c)dhtmlx ltd. www.dhtmlx.com
