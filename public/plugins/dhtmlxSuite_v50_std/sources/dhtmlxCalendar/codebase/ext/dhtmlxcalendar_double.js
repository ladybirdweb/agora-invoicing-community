/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

window.dhtmlxDblCalendarObject = window.dhtmlXDoubleCalendarObject = window.dhtmlXDoubleCalendar = function(parentId) {
	
	var that = this;
	
	this.leftCalendar = new dhtmlXCalendarObject(parentId);
	this.leftCalendar.hideTime();
	this.rightCalendar = new dhtmlXCalendarObject(parentId);
	this.rightCalendar.hideTime();
	
	this.leftCalendar.attachEvent("onClick", function(d){
		that._updateRange("rightCalendar", d, null);
		that._evOnClick(["left", d]);
	});
	
	this.rightCalendar.attachEvent("onClick", function(d){
		that._updateRange("leftCalendar", null, d);
		that._evOnClick(["right", d]);
	});
	
	this.leftCalendar.attachEvent("onBeforeChange", function(d){
		return that._evOnBeforeChange(["left",d]);
	});
	
	this.rightCalendar.attachEvent("onBeforeChange", function(d){
		return that._evOnBeforeChange(["right",d]);
	});
	
	this.show = function() {
		this.leftCalendar.show();
		this.rightCalendar.base.style.marginLeft=this.leftCalendar.base.offsetWidth-1+"px";
		this.rightCalendar.show();
	}
	
	this.hide = function() {
		this.leftCalendar.hide();
		this.rightCalendar.hide();
	}
	
	this.setDateFormat = function(t) {
		this.leftCalendar.setDateFormat(t);
		this.rightCalendar.setDateFormat(t);
	}
	
	this.setDates = function(d0, d1) {
		if (d0 != null) this.leftCalendar.setDate(d0);
		if (d1 != null) this.rightCalendar.setDate(d1);
		this._updateRange();
	}
	
	this._updateRange = function(obj, from, to) {
		if (arguments.length == 3) {
			(obj=="leftCalendar"?this.leftCalendar:this.rightCalendar).setSensitiveRange(from, to);
		} else {
			this.leftCalendar.setSensitiveRange(null, this.rightCalendar.getDate());
			this.rightCalendar.setSensitiveRange(this.leftCalendar.getDate(), null);
		}
	}
	
	this.getFormatedDate = function() {
		return this.leftCalendar.getFormatedDate.apply(this.leftCalendar, arguments);
	}
	
	this.unload = function() {
		
		window.dhx4._eventable(this, "clear");
		
		this.leftCalendar.unload();
		this.rightCalendar.unload();
		this.leftCalendar = this.rightCalendar = null;
		
		this._updateRange = null;
		this._evOnClick = null;
		this._evOnBeforeChange = null;
		this.show = null;
		this.hide = null;
		this.setDateFormat = null;
		this.setDates = null;
		this.getFormatedDate = null;
		this.unload = null;
		
		that = null;
	}
	
	this._evOnClick = function(args) {
		return this.callEvent("onClick", args);
	}
	this._evOnBeforeChange = function(args) {
		return this.callEvent("onBeforeChange", args);
	}
	
	window.dhx4._eventable(this);
	
	return this;
}

