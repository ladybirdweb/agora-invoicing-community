/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

function eXcell_dhxCalendar(cell) {
	if (cell) {
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
		
		if (!this.grid._grid_calendarA) {
			
			var cal = this.grid._grid_calendarA = new dhtmlxCalendarObject();
			this.grid.callEvent("onDhxCalendarCreated", [cal]);
			
			var sgrid = this.grid;
			cal.attachEvent("onClick",function(){
                    		this._last_operation_calendar=true;
                    		window.setTimeout(function(){sgrid.editStop()},1);
                    		return true;
                    	});
                    	
                    	var zFunc = function(e){ (e||event).cancelBubble=true; }
                    	dhtmlxEvent(cal.base, "click", zFunc);
                    	cal = null;
                }
	}
}
eXcell_dhxCalendar.prototype = new eXcell;

eXcell_dhxCalendar.prototype.edit = function() {
	
	var arPos = this.grid.getPosition(this.cell);
	this.grid._grid_calendarA._show(false, false);
	this.grid._grid_calendarA.setPosition(arPos[0],arPos[1]+this.cell.offsetHeight);
	this.grid._grid_calendarA._last_operation_calendar = false;
	
	
	this.grid.callEvent("onCalendarShow", [this.grid._grid_calendarA, this.cell.parentNode.idd, this.cell._cellIndex]);
	//var arPos = this.grid.getPosition(this.cell);
	//var pval=this._date2str2(this.cell.val||new Date());
	//window._grid_calendar.render(arPos[0],arPos[1]+this.cell.offsetHeight,this,pval);
	this.cell._cediton = true;
	this.val = this.cell.val;
	this._val = this.cell.innerHTML;
	// alert(this.cell.val);
	var t = this.grid._grid_calendarA.draw;
	this.grid._grid_calendarA.draw = function(){};
	this.grid._grid_calendarA.setDateFormat((this.grid._dtmask||"%d/%m/%Y"));
	this.grid._grid_calendarA.setDate(this.val||(new Date()));
	this.grid._grid_calendarA.draw = t;
	
	//this.grid._grid_calendarA.draw();
}
eXcell_dhxCalendar.prototype.getDate = function() {
	if (this.cell.val) return this.cell.val;
	return null;
}

eXcell_dhxCalendar.prototype.getValue = function() {
	if (this.cell._clearCell) return "";
	if (this.grid._dtmask_inc && this.cell.val) return this.grid._grid_calendarA.getFormatedDate(this.grid._dtmask_inc, this.cell.val).toString();
	return this.cell.innerHTML.toString()._dhx_trim()
}

eXcell_dhxCalendar.prototype.detach = function() {
	if (!this.grid._grid_calendarA) return;
	this.grid._grid_calendarA.hide();
	if (this.cell._cediton) this.cell._cediton = false; else return;
	
	if (this.grid._grid_calendarA._last_operation_calendar) {
		var z1=this.grid._grid_calendarA.getFormatedDate((this.grid._dtmask||"%d/%m/%Y"));
		var z2=this.grid._grid_calendarA.getDate();
		this.cell.val=new Date(z2);
		this.setCValue(z1,z2);
		this.cell._clearCell = !z1;
		var t = this.val;
		this.val = this._val;
		return (this.cell.val.valueOf()!=(t||"").valueOf());
	}
	return false;
}


eXcell_dhxCalendar.prototype.setValue = function(val) {
	
	if (val && typeof val == "object") {
		this.cell.val=val;
		this.cell._clearCell=false;
		this.setCValue(this.grid._grid_calendarA.getFormatedDate((this.grid._dtmask||"%d/%m/%Y"),val).toString(),this.cell.val);
		return;
	}
	
	
	if (!val || val.toString()._dhx_trim()=="") {
		val="&nbsp";
		this.cell._clearCell=true;
		this.cell.val="";
	} else{
		this.cell._clearCell=false;
		this.cell.val=new Date(this.grid._grid_calendarA.setFormatedDate((this.grid._dtmask_inc||this.grid._dtmask||"%d/%m/%Y"),val.toString(),null,true));
		if (this.grid._dtmask_inc)
			val = this.grid._grid_calendarA.getFormatedDate((this.grid._dtmask||"%d/%m/%Y"),this.cell.val);
	}
	
	if ((this.cell.val=="NaN")||(this.cell.val=="Invalid Date")) {
		this.cell._clearCell=true;
		this.cell.val=new Date();
		this.setCValue("&nbsp;",0);
	} else {
		this.setCValue((val||"").toString(),this.cell.val);
	}
}


function eXcell_dhxCalendarA(cell) {
	if (cell) {
		this.cell = cell;
		this.grid = this.cell.parentNode.grid;
		
		if (!this.grid._grid_calendarA) {
			
			var cal = this.grid._grid_calendarA = new dhtmlxCalendarObject();
			this.grid.callEvent("onDhxCalendarCreated",[cal]);
			
			var sgrid=this.grid;
			cal.attachEvent("onClick",function() {
				this._last_operation_calendar=true;
				window.setTimeout(function() {sgrid.editStop()},1);
				return true;
                    	});
                    	
                    	var zFunc=function(e) { (e||event).cancelBubble=true;  }
                    	dhtmlxEvent(cal.base,"click",zFunc);
                }      
	}
}
eXcell_dhxCalendarA.prototype = new eXcell;

eXcell_dhxCalendarA.prototype.edit = function() {
	var arPos = this.grid.getPosition(this.cell);
	
	this.grid._grid_calendarA._show(false, false);
	this.grid._grid_calendarA.setPosition(arPos[0]*1+this.cell.offsetWidth,arPos[1]*1);
	this.grid.callEvent("onCalendarShow",[this.grid._grid_calendarA,this.cell.parentNode.idd,this.cell._cellIndex]);
	this.grid._grid_calendarA._last_operation_calendar=false;
	
	this.cell._cediton=true;
	this.val=this.cell.val;
	this._val=this.cell.innerHTML;
	
	var t=this.grid._grid_calendarA.draw; this.grid._grid_calendarA.draw=function() {};
	this.grid._grid_calendarA.setDateFormat((this.grid._dtmask||"%d/%m/%Y"));
	this.grid._grid_calendarA.setDate(this.val);
	this.grid._grid_calendarA.draw=t;
	
	this.cell.atag=((!this.grid.multiLine)&&(_isKHTML||_isMacOS||_isFF))?"INPUT":"TEXTAREA";
	
	this.obj = document.createElement(this.cell.atag);
	this.obj.style.height = (this.cell.offsetHeight-4)+"px";
	this.obj.className="dhx_combo_edit";
	this.obj.wrap = "soft";
	this.obj.style.textAlign = this.cell.align;
	this.obj.onclick = function(e) {(e||event).cancelBubble = true}
	this.obj.onmousedown = function(e) {(e||event).cancelBubble = true}
	this.obj.value = this.getValue();
	this.cell.innerHTML = "";
	this.cell.appendChild(this.obj);
	if (window.dhx4.isIE) {
		this.obj.style.overflow = "visible";
		if ((this.grid.multiLine)&&(this.obj.offsetHeight>=18)&&(this.obj.offsetHeight<40)) {
			this.obj.style.height = "36px";
			this.obj.style.overflow = "scroll";
		}
	}
	this.obj.onselectstart=function(e) {
		if (!e) e=event;
		e.cancelBubble = true;
		return true;
	};
	this.obj.focus()
	this.obj.focus()
	
}

eXcell_dhxCalendarA.prototype.getDate = function() {
	if (this.cell.val) return this.cell.val;
	return null;
}

eXcell_dhxCalendarA.prototype.getValue = function() {
	if (this.cell._clearCell) return "";
	if (this.grid._dtmask_inc && this.cell.val)
		return this.grid._grid_calendarA.getFormatedDate(this.grid._dtmask_inc, this.cell.val).toString();
	return this.cell.innerHTML.toString()._dhx_trim()
}

eXcell_dhxCalendarA.prototype.detach = function() {
	if (!this.grid._grid_calendarA) return;
	this.grid._grid_calendarA.hide();
	if (this.cell._cediton) this.cell._cediton=false; else return;
	if (this.grid._grid_calendarA._last_operation_calendar) {
		this.grid._grid_calendarA._last_operation_calendar=false;
		var z1=this.grid._grid_calendarA.getFormatedDate(this.grid._dtmask||"%d/%m/%Y");
		var z2=this.grid._grid_calendarA.getDate();
		this.cell.val=new Date(z2);
		this.setCValue(z1,z2);
		this.cell._clearCell = !z1;
		var t = this.val;
		this.val=this._val;
		return (this.cell.val.valueOf()!=(t||"").valueOf());
	}
	this.setValue(this.obj.value);
	var t = this.val;
	this.val = this._val;
	return (this.cell.val.valueOf()!=(t||"").valueOf());
}

eXcell_dhxCalendarA.prototype.setValue = function(val) {
	if (val && typeof val == "object") {
		this.cell.val=val;
		this.cell._clearCell=false;
		this.setCValue(this.grid._grid_calendarA.getFormatedDate((this.grid._dtmask||"%d/%m/%Y"),val).toString(),this.cell.val);
		return;
	}
	
	if (!val || val.toString()._dhx_trim()=="") {
		val="&nbsp";
		this.cell._clearCell=true;
		this.cell.val="";
	} else {
		this.cell._clearCell = false;
		this.cell.val = new Date(this.grid._grid_calendarA.setFormatedDate((this.grid._dtmask_inc||this.grid._dtmask||"%d/%m/%Y"),val.toString(),null,true));
		if (this.grid._dtmask_inc)
			val = this.grid._grid_calendarA.getFormatedDate((this.grid._dtmask||"%d/%m/%Y"),this.cell.val);
	}
	
	if ((this.cell.val=="NaN")||(this.cell.val=="Invalid Date")) {
		this.cell.val=new Date();
		this.cell._clearCell=true;
		this.setCValue("&nbsp;",0);
	} else {
		this.setCValue((val||"").toString(),this.cell.val);
	}
}

