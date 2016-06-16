/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

// validation
if (typeof(window.dhtmlxValidation) != "undefined") {
	dhtmlxValidation.trackInput = function(el,rule,callback_error,callback_correct) {
		dhtmlxEvent(el, "keyup", function(e){
			if (dhtmlxValidation._timer) {
				window.clearTimeout(dhtmlxValidation._timer);
				dhtmlxValidation._timer = null;
			}
			dhtmlxValidation._timer = window.setTimeout(function(){
				if (!dhtmlxValidation.checkInput(el,rule)){
					if (!callback_error || callback_error(el,el.value,rule)) el.className += " dhtmlx_live_validation_error";
				} else {
					el.className=el.className.replace(/[ ]*dhtmlx_live_validation_error/g,"");
					if (callback_correct) callback_correct(el, el.value, rule);
				}
			},250);
		});
	};
	dhtmlxValidation.checkInput = function(input,rule) {
		return dhtmlxValidation.checkValue(input.value,rule);
	};
	dhtmlxValidation.checkValue = function(value,rule) {
		if (typeof rule=="string") rule = rule.split(",");
		var final_res = true;
		for (var i=0; i<rule.length; i++) {
			if (!this["is"+rule[i]]) {
				alert("Incorrect validation rule: "+rule[i]);
			} else {
				final_res = final_res && this["is"+rule[i]](value);
			}
		}
		return final_res;
	};
};
// extension for the grid
dhtmlXGridObject.prototype.enableValidation=function(mode,live){
	mode=dhx4.s2b(mode);
	if (mode) this._validators = {data:[]}; else this._validators = false;
	if (arguments.length>1) this._validators._live=live;
	if (!this._validators._event) this._validators._event=this.attachEvent("onEditCell",this.validationEvent);
};
dhtmlXGridObject.prototype.setColValidators=function(vals){
	if (!this._validators) this.enableValidation(true);
	if (typeof vals == "string") vals=vals.split(this.delim);
	this._validators.data=vals;
};
dhtmlXGridObject.prototype.validationEvent=function(stage,id,ind,newval,oldval){
	var v=this._validators;
	if (!v) return true; // validators disabled
	var rule=(v.data[ind]||this.cells(id,ind).getAttribute("validate"))||"";
	
	if (stage==1 && rule) {
		var ed = this.editor||(this._fake||{}).editor;
		if (!ed) return true; //event was trigered by checkbox
		ed.cell.className=ed.cell.className.replace(/[ ]*dhtmlx_validation_error/g,"");
		if (v._live){
			var grid=this;
			dhtmlxValidation.trackInput(ed.getInput(),rule,function(element,value,rule){
				return grid.callEvent("onLiveValidationError",[id,ind,value,element,rule]);
			}, function(element,value,rule){
				return grid.callEvent("onLiveValidationCorrect",[id,ind,value,element,rule]);
			});
		}
	}
	
	if (stage==2) this.validateCell(id,ind,rule,newval);
	
	return true;
};

dhtmlXGridObject.prototype.validateCell=function(id,ind,rule,value){
	rule=rule||(this._validators.data[ind]||this.cells(id,ind).getAttribute("validate"));
	value=value||this.cells(id,ind).getValue();
	if (!rule) return;
	var cell = this.cells(id,ind).cell;
	
	var result = true;
	if (typeof rule == "string")
		rule = rule.split(this.delim);
	
	for (var i=0; i < rule.length; i++) {
		if (!dhtmlxValidation.checkValue(value,rule[i])){
			if (this.callEvent("onValidationError",[id,ind,value,rule[i]]))
				cell.className+=" dhtmlx_validation_error";
			result = false;
		}
	}
	if (result){
		this.callEvent("onValidationCorrect",[id,ind,value,rule]);
		cell.className=cell.className.replace(/[ ]*dhtmlx_validation_error/g,"");		
	}
	return result;
};
