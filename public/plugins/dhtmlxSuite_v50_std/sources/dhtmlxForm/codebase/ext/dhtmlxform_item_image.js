/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

dhtmlXForm.prototype.items.image = {
	
	_dimFix: true,
	
	render: function(item, data) {
		
		item._type = "image";
		item._enabled = true;
		
		item._fr_name = "dhxform_image_"+window.dhx4.newId();
		item._url = (typeof(data.url)=="undefined"||data.url==null?"":data.url);
		
		if (data.inputWidth == "auto") data.inputWidth = 120;
		if (data.inputHeight == "auto") data.inputHeight = data.inputWidth;
		
		this.doAddLabel(item, data);
		
		this.doAddInput(item, data, "DIV", null, true, true, "dhxform_image");
		
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		t.style.height = parseInt(t.style.height)-dhtmlXForm.prototype.items[this.t]._dim+"px";
		
		var w = (typeof(data.imageWidth)!="undefined"?parseInt(data.imageWidth):data.inputWidth);
		var h = (typeof(data.imageHeight)!="undefined"?parseInt(data.imageHeight):data.inputHeight);
		if (h == "auto") h = w;
		
		item._dim = {mw: data.inputWidth-this._dim, mh: data.inputHeight-this._dim, w: w, h: h};
		
		t.innerHTML = "<img class='dhxform_image_img' border='0' style='visibility:hidden;'>"+
				"<iframe name='"+item._fr_name+"' style='position: absolute; width:0px; height:0px; top:-10px; left:-10px;' frameBorder='0' border='0'></iframe>"+
				"<div class='dhxform_image_wrap'>"+
					"<form action='"+item._url+"' method='POST' enctype='multipart/form-data' target='"+item._fr_name+"' class='dhxform_image_form'>"+
						"<input type='hidden' name='action' value='uploadImage'>"+
						"<input type='hidden' name='itemId' value='"+item._idd+"'>"+
						"<input type='file' name='file' class='dhxform_image_input'>"+
					"</form>";
				"</div>";
		
		this.adjustImage(item);
		
		// file selection
		t.childNodes[2].firstChild.lastChild.onchange = function() {
			item._is_uploading = true;
			this.parentNode.submit();
			this.parentNode.parentNode.className = "dhxform_image_wrap dhxform_image_in_progress";
			this.value = ""; // prevent update on cancel click in chrome
		}
		
		// iframe updates
		var that = this;
		if (window.navigator.userAgent.indexOf("MSIE") >= 0) {
			t.childNodes[1].onreadystatechange = function() {if (this.readyState == "complete") that.doOnUpload(item);}
		} else {
			t.childNodes[1].onload = function(){that.doOnUpload(item);}
		}
		this._moreClear = function() {
			that = null;
		}
		
		// initial value
		this.setValue(item, data.value||"");
		
		t = null;
		
		return this;
		
	},
	
	destruct: function(item) {
		// custom functionality
		var t = item.childNodes[item._ll?1:0].childNodes[0];
		t.childNodes[2].firstChild.lastChild.onchange = null;
		t.childNodes[1].onreadystatechange = null;
		t.childNodes[1].onload = null;
		this._moreClear();
		
		// common form's unload
		this.d2(item);
		item = null;
	},
	
	doAttachEvents: function() {
		
	},
	
	setValue: function(item, value) {
		item._value = (value==null?"":value);
		
		var u = item._url+
			(item._url.indexOf("?")>=0?"&":"?")+"action=loadImage"+
			"&itemId="+encodeURIComponent(item._idd)+
			"&itemValue="+encodeURIComponent(item._value)+
			window.dhx4.ajax._dhxr("&")
		
		var currentImg = item.childNodes[item._ll?1:0].childNodes[0].firstChild;
		
		if (currentImg.nextSibling.tagName.toLowerCase() == "img") {
			currentImg.nextSibling.src = u; // new img created and still loaded from prev setValue() call
		} else {
			var img = document.createElement("IMG");
			img.className = "dhxform_image_img";
			img.style.visibility = "hidden";
			img.onload = function() {
				this.style.visibility = "visible";
				this.parentNode.removeChild(this.nextSibling);
				this.onload = this.onerror = null;
			}
			img.onerror = function() {
				this.onload.apply(this, arguments);
				this.style.visibility = "hidden";
			}
			currentImg.parentNode.insertBefore(img, currentImg);
			img.src = u;
			img = null;
			this.adjustImage(item);
		}
		
		currentImg = null;
	},
	
	getValue: function(item) {
		return item._value;
	},
	
	doOnUpload: function(item) {
		if (item._is_uploading == true) {
			
			var fr = item.childNodes[item._ll?1:0].childNodes[0].lastChild.previousSibling; // iframe
			var r = dhx4.s2j(fr.contentWindow.document.body.innerHTML);
			
			if (typeof(r) == "object" && r != null && r.state == true && r.itemId == item._idd) {
				this.setValue(item, r.itemValue, true);
				item.getForm().callEvent("onImageUploadSuccess", [r.itemId, r.itemVaule, r.extra])
			} else {
				// show empty field, r can be null
				item.getForm().callEvent("onImageUploadFail", [item._idd, (r?r.extra:null)]);
			}
			
			r = fr = null;
			
			window.setTimeout(function(){
				item.childNodes[item._ll?1:0].childNodes[0].lastChild.className = "dhxform_image_wrap"; // div
				item._is_uploading = false; // ready to new upload
			},50);
			
		}
	},
	
	adjustImage: function(item) {
		var i = item.childNodes[item._ll?1:0].childNodes[0].firstChild; // image
		var w = Math.min(item._dim.mw, item._dim.w);
		var h = Math.min(item._dim.mh, item._dim.h);
		i.style.width = w+"px";
		i.style.height = h+"px";
		i.style.marginLeft = Math.max(0, Math.round(item._dim.mw/2-w/2))+"px";
		i.style.marginTop = Math.max(0, Math.round(item._dim.mh/2-h/2))+"px";
		i = item = null;
	}
	
};

(function(){
	for (var a in dhtmlXForm.prototype.items.input) {
		if (!dhtmlXForm.prototype.items.image[a]) dhtmlXForm.prototype.items.image[a] = dhtmlXForm.prototype.items.input[a];
	}
})();
dhtmlXForm.prototype.items.image.d2 = dhtmlXForm.prototype.items.input.destruct;

dhtmlXForm.prototype.setFormData_image = function(name, value) {
	this.setItemValue(name, value);
};
dhtmlXForm.prototype.getFormData_image = function(name) {
	return this.getItemValue(name);
};

