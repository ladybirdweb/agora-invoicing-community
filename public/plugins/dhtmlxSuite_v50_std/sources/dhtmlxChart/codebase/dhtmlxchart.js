/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/


/* DHX DEPEND FROM FILE 'group.js'*/


/*DHX:Depend datastore.js*/
/*DHX:Depend dhtmlx.js*/

dhtmlx.Group = {
	_init:function(){
		dhtmlx.assert(this.data,"DataStore required for grouping");
		this.data.attachEvent("onStoreLoad",dhtmlx.bind(function(){
			if (this._settings.group)
				this.group(this._settings.group,false);
		},this));
		this.attachEvent("onBeforeRender",dhtmlx.bind(function(data){
			if (this._settings.sort){
				data.block();
				data.sort(this._settings.sort);
				data.unblock();
			}
		},this));
		this.data.attachEvent("onClearAll",dhtmlx.bind(function(){
			this.data._not_grouped_order = this.data._not_grouped_pull = null;
		},this));
		this.attachEvent("onBeforeSort",dhtmlx.bind(function(){
			this._settings.sort = null;
		},this));
	},
	_init_group_data_event:function(data,master){
		data.attachEvent("onClearAll",dhtmlx.bind(function(){
            this.ungroup(false);
            this.block();
            this.clearAll();
            this.unblock();
        },master));
	},
	sum:function(property, data){
		property = dhtmlx.Template.setter(property);
		
		data = data || this.data;
		var summ = 0; 
		data.each(function(obj){
			summ+=property(obj)*1;
		});
		return summ;
	},
	min:function(property, data){
		property = dhtmlx.Template.setter(property);
		
		data = data || this.data;
		var min = Infinity; 
		data.each(function(obj){
			if (property(obj)*1 < min) min = property(obj)*1;
		});
		return min*1;
	},
	max:function(property, data){
		property = dhtmlx.Template.setter(property);
		
		data = data || this.data;
		var max = -Infinity;
		data.each(function(obj){
			if (property(obj)*1 > max) max = property(obj)*1;
		});
		return max;
	},
	_split_data_by:function(stats){ 
		var any=function(property, data){
			property = dhtmlx.Template.setter(property);
			return property(data[0]);
		};
		var key = dhtmlx.Template.setter(stats.by);
		if (!stats.map[key])
			stats.map[key] = [key, any];
			
		var groups = {};
		var labels = [];
		this.data.each(function(data){
			var current = key(data);
			if (!groups[current]){
				labels.push({id:current});
				groups[current] = dhtmlx.toArray();
			}
			groups[current].push(data);
		});
		for (var prop in stats.map){
			var functor = (stats.map[prop][1]||any);
			if (typeof functor != "function")
				functor = this[functor];
				
			for (var i=0; i < labels.length; i++) {
				labels[i][prop]=functor.call(this, stats.map[prop][0], groups[labels[i].id]);
			}
		}
//		if (this._settings.sort)
//			labels.sortBy(stats.sort);

		/*this._not_grouped_data = this.data;
		this.data = new dhtmlx.DataStore();
		this.data.provideApi(this,true);
		this._init_group_data_event(this.data, this);
		this.parse(labels,"json");*/

		this.data._not_grouped_order = this.data.order;
		this.data._not_grouped_pull = this.data.pull;

		this.data.order = dhtmlx.toArray();
		this.data.pull = {};
		for (var i=0; i < labels.length; i++){
			var id = this.data.id(labels[i]);
			/*if(!labels[i].id)
				labels[i].id = dhtmlx.uid();
			var id = labels[i].id;*/
			this.data.pull[id] = labels[i];
			this.data.order.push(id);
		}

		this.callEvent("onStoreUpdated",[]);
	},
	group:function(config,mode){
		this.ungroup(false);
		this._split_data_by(config);
		if (mode!==false)
			this.data.callEvent("onStoreUpdated",[]);
	},
	ungroup:function(mode){
		/*if (this._not_grouped_data){
			this.data = this._not_grouped_data;
			this.data.provideApi(this, true);
		}*/
		if (this.data._not_grouped_order){
			this.data.order = this.data._not_grouped_order;
			this.data.pull = this.data._not_grouped_pull;
			this.data._not_grouped_pull = this.data._not_grouped_order = null;
		}
		if (mode!==false)
			this.data.callEvent("onStoreUpdated",[]);
	},
	group_setter:function(config){
		dhtmlx.assert(typeof config == "object", "Incorrect group value");
		dhtmlx.assert(config.by,"group.by is mandatory");
		dhtmlx.assert(config.map,"group.map is mandatory");
		return config;
	},
	//need to be moved to more appropriate object
	sort_setter:function(config){
		if (typeof config != "object")
			config = { by:config };
		
		this._mergeSettings(config,{
			as:"string",
			dir:"asc"
		});
		return config;
	}
};




/* DHX DEPEND FROM FILE 'date.js'*/


/*DHX:Depend dhtmlx.js*/

dhtmlx.Date={
	Locale: {
		month_full:["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
		month_short:["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
		day_full:["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    	day_short:["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
    },

	date_part:function(date){
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		date.setMilliseconds(0);	
		return date;
	},
	time_part:function(date){
		return (date.valueOf()/1000 - date.getTimezoneOffset()*60)%86400;
	},
	week_start:function(date){
			var shift=date.getDay();
			if (this.config.start_on_monday){
				if (shift===0) shift=6;
				else shift--;
			}
			return this.date_part(this.add(date,-1*shift,"day"));
	},
	month_start:function(date){
		date.setDate(1);
		return this.date_part(date);
	},
	year_start:function(date){
		date.setMonth(0);
		return this.month_start(date);
	},
	day_start:function(date){
			return this.date_part(date);
	},
	add:function(date,inc,mode){
		var ndate=new Date(date.valueOf());
		switch(mode){
			case "day": ndate.setDate(ndate.getDate()+inc); break;
			case "week": ndate.setDate(ndate.getDate()+7*inc); break;
			case "month": ndate.setMonth(ndate.getMonth()+inc); break;
			case "year": ndate.setYear(ndate.getFullYear()+inc); break;
			case "hour": ndate.setHours(ndate.getHours()+inc); break;
			case "minute": ndate.setMinutes(ndate.getMinutes()+inc); break;
			default:
				return dhtmlx.Date["add_"+mode](date,inc,mode);
		}
		return ndate;
	},
	to_fixed:function(num){
		if (num<10)	return "0"+num;
		return num;
	},
	copy:function(date){
		return new Date(date.valueOf());
	},
	date_to_str:function(format,utc){
		format=format.replace(/%[a-zA-Z]/g,function(a){
			switch(a){
				case "%d": return "\"+dhtmlx.Date.to_fixed(date.getDate())+\"";
				case "%m": return "\"+dhtmlx.Date.to_fixed((date.getMonth()+1))+\"";
				case "%j": return "\"+date.getDate()+\"";
				case "%n": return "\"+(date.getMonth()+1)+\"";
				case "%y": return "\"+dhtmlx.Date.to_fixed(date.getFullYear()%100)+\""; 
				case "%Y": return "\"+date.getFullYear()+\"";
				case "%D": return "\"+dhtmlx.Date.Locale.day_short[date.getDay()]+\"";
				case "%l": return "\"+dhtmlx.Date.Locale.day_full[date.getDay()]+\"";
				case "%M": return "\"+dhtmlx.Date.Locale.month_short[date.getMonth()]+\"";
				case "%F": return "\"+dhtmlx.Date.Locale.month_full[date.getMonth()]+\"";
				case "%h": return "\"+dhtmlx.Date.to_fixed((date.getHours()+11)%12+1)+\"";
				case "%g": return "\"+((date.getHours()+11)%12+1)+\"";
				case "%G": return "\"+date.getHours()+\"";
				case "%H": return "\"+dhtmlx.Date.to_fixed(date.getHours())+\"";
				case "%i": return "\"+dhtmlx.Date.to_fixed(date.getMinutes())+\"";
				case "%a": return "\"+(date.getHours()>11?\"pm\":\"am\")+\"";
				case "%A": return "\"+(date.getHours()>11?\"PM\":\"AM\")+\"";
				case "%s": return "\"+dhtmlx.Date.to_fixed(date.getSeconds())+\"";
				case "%W": return "\"+dhtmlx.Date.to_fixed(dhtmlx.Date.getISOWeek(date))+\"";
				default: return a;
			}
		});
		if (utc) format=format.replace(/date\.get/g,"date.getUTC");
		return new Function("date","return \""+format+"\";");
	},
	str_to_date:function(format,utc){
		var splt="var temp=date.split(/[^0-9a-zA-Z]+/g);";
		var mask=format.match(/%[a-zA-Z]/g);
		for (var i=0; i<mask.length; i++){
			switch(mask[i]){
				case "%j":
				case "%d": splt+="set[2]=temp["+i+"]||1;";
					break;
				case "%n":
				case "%m": splt+="set[1]=(temp["+i+"]||1)-1;";
					break;
				case "%y": splt+="set[0]=temp["+i+"]*1+(temp["+i+"]>50?1900:2000);";
					break;
				case "%g":
				case "%G":
				case "%h": 
				case "%H":
							splt+="set[3]=temp["+i+"]||0;";
					break;
				case "%i":
							splt+="set[4]=temp["+i+"]||0;";
					break;
				case "%Y":  splt+="set[0]=temp["+i+"]||0;";
					break;
				case "%a":					
				case "%A":  splt+="set[3]=set[3]%12+((temp["+i+"]||'').toLowerCase()=='am'?0:12);";
					break;					
				case "%s":  splt+="set[5]=temp["+i+"]||0;";
					break;
			}
		}
		var code ="set[0],set[1],set[2],set[3],set[4],set[5]";
		if (utc) code =" Date.UTC("+code+")";
		return new Function("date","var set=[0,0,1,0,0,0]; "+splt+" return new Date("+code+");");
	},
		
	getISOWeek: function(ndate) {
		if(!ndate) return false;
		var nday = ndate.getDay();
		if (nday === 0) {
			nday = 7;
		}
		var first_thursday = new Date(ndate.valueOf());
		first_thursday.setDate(ndate.getDate() + (4 - nday));
		var year_number = first_thursday.getFullYear(); // year of the first Thursday
		var ordinal_date = Math.floor( (first_thursday.getTime() - new Date(year_number, 0, 1).getTime()) / 86400000); //ordinal date of the first Thursday - 1 (so not really ordinal date)
		var week_number = 1 + Math.floor( ordinal_date / 7);	
		return week_number;
	},
	
	getUTCISOWeek: function(ndate){
   	return this.getISOWeek(ndate);
   }
};






/* DHX DEPEND FROM FILE 'math.js'*/


dhtmlx.math = {};
dhtmlx.math._toHex=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F"];
dhtmlx.math.toHex = function(number, length){
	number=parseInt(number,10);
	str = "";
		while (number>0){
			str=this._toHex[number%16]+str;
			number=Math.floor(number/16);
		}
		while (str.length <length)
			str = "0"+str;
	return str;
};
dhtmlx.math.hexToDec = function(hex){
   	return parseInt(hex, 16);
};
dhtmlx.math.toRgb = function(rgb){
   	var r,g,b,rgbArr;
   	if (typeof(rgb) != 'string') {
    	r = rgb[0];
       	g = rgb[1];
       	b = rgb[2];
   	} else if (rgb.indexOf('rgb')!=-1) {
		rgbArr = rgb.substr(rgb.indexOf("(")+1,rgb.lastIndexOf(")")-rgb.indexOf("(")-1).split(",");
	   	r = rgbArr[0];
	   	g = rgbArr[1];
	   	b = rgbArr[2];
   	} else {
       	if (rgb.substr(0, 1) == '#') {
        	rgb = rgb.substr(1);
       	}
       	r = this.hexToDec(rgb.substr(0, 2));
       	g = this.hexToDec(rgb.substr(2, 2));
       	b = this.hexToDec(rgb.substr(4, 2));
   	}
   	r = (parseInt(r,10)||0);
   	g = (parseInt(g,10)||0);
   	b = (parseInt(b,10)||0);
   	if (r < 0 || r > 255)
      	r = 0;
   	if (g < 0 || g > 255)
      	g = 0;
   	if (b < 0 || b > 255)
      	b = 0;
   	return [r,g,b];
}
dhtmlx.math.hsvToRgb = function(h, s, v){
	var hi,f,p,q,t,r,g,b;
   	hi = Math.floor((h/60))%6;
   	f = h/60-hi;
   	p = v*(1-s);
   	q = v*(1-f*s);
   	t = v*(1-(1-f)*s);
   	r = 0;
   	g = 0;
   	b = 0;
   	switch(hi) {
    	case 0:
        	r = v; g = t; b = p;
         	break;
      	case 1:
        	r = q; g = v; b = p;
         	break;
      	case 2:
        	r = p; g = v; b = t;
        	 break;
      	case 3:
        	r = p; g = q; b = v;
        	break;
      	case 4:
        	r = t; g = p; b = v;
        	break;
      	case 5:
        	r = v; g = p; b = q;
         	break;
   	}
    r = Math.floor(r*255);
    g = Math.floor(g*255);
    b = Math.floor(b*255);
    return [r, g, b];
};
dhtmlx.math.rgbToHsv = function(r, g, b){
   	var r0,g0,b0,min0,max0,s,h,v;
   	r0 = r/255;
   	g0 = g/255;
   	b0 = b/255;
   	var min0 = Math.min(r0, g0, b0);
   	var max0 = Math.max(r0, g0, b0);
   	h = 0;
   	s = max0==0?0:(1-min0/max0);
   	v = max0;
   	if (max0 == min0) {
   		h = 0;
   	} else if (max0 == r0 && g0>=b0) {
    	h = 60*(g0 - b0)/(max0 - min0)+0;
   	} else if (max0 == r0 && g0 < b0) {
    	h = 60*(g0 - b0)/(max0 - min0)+360;
   	} else if (max0 == g0) {
      	h = 60*(b0 - r0)/(max0-min0)+120;
   	} else if (max0 == b0) {
      	h = 60*(r0 - g0)/(max0 - min0)+240;
   	}
   	return [h, s, v];
}




/* DHX DEPEND FROM FILE 'ext/chart/presets.js'*/


/*chart presents*/
if(!dhtmlx.presets)
    dhtmlx.presets = {};
dhtmlx.presets.chart = {
    "simple":{
        item:{
            borderColor: "#ffffff",
            color: "#2b7100",
            shadow: false,
            borderWidth:2
        },
		line:{
			color:"#8ecf03",
            width:2
		}
    },
    "plot":{
        color:"#1293f8",
        item:{
            borderColor:"#636363",
            borderWidth:1,
            color: "#ffffff",
            type:"r",
            shadow: false
        },
	    line:{
			color:"#1293f8",
            width:2
	    }
    },
    "diamond":{
        color:"#b64040",
        item:{
			borderColor:"#b64040",
			color: "#b64040",
            type:"d",
            radius:3,
            shadow:true
        },
		line:{
			color:"#ff9000",
            width:2
		}
    },
    "point":{
        color:"#fe5916",
		disableLines:true,
        fill:false,
        disableItems:false,
        item:{
            color:"#feb916",
            borderColor:"#fe5916",
            radius:2,
            borderWidth:1,
            type:"r"
	    },
        alpha:1
    },
    "line":{
        line:{
            color:"#3399ff",
            width:2
        },
        item:{
            color:"#ffffff",
            borderColor:"#3399ff",
            radius:2,
            borderWidth:2,
            type:"d"
        },
        fill:false,
        disableItems:false,
        disableLines:false,
        alpha:1
    },
    "area":{
        fill:"#3399ff",
        line:{
            color:"#3399ff",
            width:1
        },
        disableItems:true,
        alpha: 0.2,
        disableLines:false
    },
    "round":{
        item:{
            radius:3,
            borderColor:"#3f83ff",
            borderWidth:1,
            color:"#3f83ff",
            type:"r",
            shadow:false,
            alpha:0.6
        }
    },
    "square":{
         item:{
            radius:3,
            borderColor:"#447900",
            borderWidth:2,
            color:"#69ba00",
            type:"s",
            shadow:false,
            alpha:1
        }
    },
    /*bar*/
    "column":{
        color:"RAINBOW",
        gradient:false,
        width:45,
        radius:0,
        alpha:1,
        border:true
    },
    "stick":{
        width:5,
        gradient:false,
		color:"#67b5c9",
        radius:2,
        alpha:1,
        border:false
    },
    "alpha":{
        color:"#b9a8f9",
        width:70,
        gradient:"falling",
        radius:0,
        alpha:0.5,
        border:true
    }
};



/* DHX DEPEND FROM FILE 'map.js'*/


/*DHX:Depend dhtmlx.js*/
	
dhtmlx.ui.Map = function(key){
	this.name = "Map";
	this._id = "map_"+dhtmlx.uid();
	this._key = key;
	this._map = [];
	this._areas = [];
};
dhtmlx.ui.Map.prototype = {
	addRect: function(id,points,userdata) {
		this._areas.push({ index: userdata, points: points });
		this._createMapArea(id,"RECT",points,userdata);
	},
	addPoly: function(id,points,userdata) {
		this._createMapArea(id,"POLY",points,userdata);
	},
	_createMapArea:function(id,shape,coords,userdata){
		var extra_data = "";
		if(arguments.length==4) 
			extra_data = "userdata='"+userdata+"'";
		this._map.push("<area "+this._key+"='"+id+"' shape='"+shape+"' coords='"+coords.join()+"' "+extra_data+"></area>");
	},
	addSector:function(id,alpha0,alpha1,x,y,R,ky,userdata){
		var points = [];
		points.push(x);
		points.push(Math.floor(y*ky)); 
		for(var i = alpha0; i < alpha1; i+=Math.PI/18){
			points.push(Math.floor(x+R*Math.cos(i)));
			points.push(Math.floor((y+R*Math.sin(i))*ky));
		}
		points.push(Math.floor(x+R*Math.cos(alpha1)));
		points.push(Math.floor((y+R*Math.sin(alpha1))*ky));
		points.push(x);
		points.push(Math.floor(y*ky)); 
		
		return this.addPoly(id,points,userdata);
	},
	render:function(obj){
		var d = dhtmlx.html.create("DIV");
		d.style.cssText="position:absolute; width:100%; height:100%; top:0px; left:0px;";
		obj.appendChild(d);
		var src = dhtmlx._isIE?"":"src='data:image/gif;base64,R0lGODlhEgASAIAAAP///////yH5BAUUAAEALAAAAAASABIAAAIPjI+py+0Po5y02ouz3pwXADs='";
		d.innerHTML="<map id='"+this._id+"' name='"+this._id+"'>"+this._map.join("\n")+"</map><img "+src+" class='dhx_map_img' usemap='#"+this._id+"' onmousedown='return false;'>";
		
		obj._htmlmap = d; //for clearing routine
		
		this._map = [];
	}
};


/* DHX DEPEND FROM FILE 'ext/chart/chart_base.js'*/


/*DHX:Depend map.js*/
dhtmlx.chart = {};


/* DHX DEPEND FROM FILE 'ext/chart/chart_scatter.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.scatter = {

	/**
	*   renders a graphic
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: point0  - top left point of a chart
	*   @param: point1  - right bottom point of a chart
	*   @param: sIndex - index of drawing chart
    *   @param: map - map object
	*/
	pvt_render_scatter:function(ctx, data, point0, point1, sIndex, map){
        if(!this._settings.xValue)
            return dhtmlx.log("warning","Undefined propery: xValue");
        /*max in min values*/
        var limitsY = this._getLimits();
        var limitsX = this._getLimits("h","xValue");
        /*render scale*/
        if(!sIndex){
	        if(!this.canvases["x"])
	            this.canvases["x"] = new dhtmlx.ui.Canvas(this._obj,"axis_x");
	        if(!this.canvases["y"])
	            this.canvases["y"] = new dhtmlx.ui.Canvas(this._obj,"axis_y");
            this._drawYAxis(this.canvases["y"].getCanvas(),data,point0,point1,limitsY.min,limitsY.max);
		    this._drawHXAxis(this.canvases["x"].getCanvas(),data,point0,point1,limitsX.min,limitsX.max);
        }
        limitsY = {min:this._settings.yAxis.start,max:this._settings.yAxis.end};
        limitsX = {min:this._settings.xAxis.start,max:this._settings.xAxis.end};
        var params = this._getScatterParams(ctx,data,point0,point1,limitsX,limitsY);
		this._mapStart = point0;
	    for(var i=0;i<data.length;i++){
            this._drawScatterItem(ctx,map,point0, point1, params,limitsX,limitsY,data[i],sIndex);
        }
    },
    _getScatterParams:function(ctx, data, point0, point1,limitsX,limitsY){
        var params = {};
		/*available space*/
		params.totalHeight = point1.y-point0.y;
        /*available width*/
        params.totalWidth = point1.x-point0.x;
		/*unit calculation (y_position = value*unit)*/
        this._calcScatterUnit(params,limitsX.min,limitsX.max,params.totalWidth,"X");
        this._calcScatterUnit(params,limitsY.min,limitsY.max,params.totalHeight,"Y");
		return params;
    },
    _drawScatterItem:function(ctx,map,point0, point1,params,limitsX,limitsY,obj,sIndex){
        var x0 = this._calculateScatterItemPosition(params, point1, point0, limitsX, obj, "X");
        var y0 = this._calculateScatterItemPosition(params, point0, point1, limitsY, obj, "Y");
        this. _drawItem(ctx,x0,y0,obj,this._settings.label.call(this,obj),sIndex,map);
    },
    _calculateScatterItemPosition:function(params, point0, point1, limits, obj, axis){
		/*the real value of an object*/
		var value = this._settings[axis=="X"?"xValue":"value"].call(this,obj);
		/*a relative value*/
        var valueFactor = params["valueFactor"+axis];
		var v = (parseFloat(value||0) - limits.min)*valueFactor;
		/*a vertical coordinate*/
        var unit = params["unit"+axis];
		var pos = point1[axis.toLowerCase()] - (axis=="X"?(-1):1)*Math.floor(unit*v);
		/*the limit of the minimum value is  the minimum visible value*/
		if(v<0)
			pos = point1[axis.toLowerCase()];
		/*the limit of the maximum value*/
		if(value > limits.max)
			pos = point0[axis.toLowerCase()];
		/*the limit of the minimum value*/
		if(value < limits.min)
			pos = point1[axis.toLowerCase()];
        return pos;
    },
    _calcScatterUnit:function(p,min,max,size,axis){
        var relativeValues = this._getRelativeValue(min,max);
        axis = (axis||"");
		p["relValue"+axis] = relativeValues[0];
		p["valueFactor"+axis] = relativeValues[1];
		p["unit"+axis] = (p["relValue"+axis]?size/p["relValue"+axis]:10);
    }
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_radar.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.radar = {
	pvt_render_radar:function(ctx,data,x,y,sIndex,map){
		this._renderRadarChart(ctx,data,x,y,sIndex,map);
		
	}, 
	/**
	*   renders a pie chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: x - the width of the container
	*   @param: y - the height of the container
	*   @param: ky - value from 0 to 1 that defines an angle of inclination (0<ky<1 - 3D chart)
	*/
	_renderRadarChart:function(ctx,data,point0,point1,sIndex,map){
		if(!data.length)
			return;
		var coord = this._getPieParameters(point0,point1);
		/*scale radius*/
		var radius = (this._settings.radius?this._settings.radius:coord.radius);
    	/*scale center*/
		var x0 = (this._settings.x?this._settings.x:coord.x);
		var y0 = (this._settings.y?this._settings.y:coord.y);
        /*angles for each unit*/
		var ratioUnits = [];
        for(var i=0;i<data.length;i++)
           ratioUnits.push(1)
		var ratios = this._getRatios(ratioUnits,data.length);
		this._mapStart = point0;
		if(!sIndex)
            this._drawRadarAxises(ratios,x0,y0,radius,data);
        this._drawRadarData(ctx,ratios,x0,y0,radius,data,sIndex,map);
	},
     _drawRadarData:function(ctx,ratios,x,y,radius,data,sIndex,map){
        var alpha0 ,alpha1, config, i, min, max, pos0, pos1, posArr,
	        r0, r1, relValue, startAlpha, value, value0, value1, valueFactor,
	        unit, unitArr;
        config = this._settings;
		/*unit calculation (item_radius_pos = value*unit)*/
        min = config.yAxis.start;
        max = config.yAxis.end;
		unitArr = this._getRelativeValue(min,max);
        relValue = unitArr[0];
		unit = (relValue?radius/relValue:radius/2);
        valueFactor = unitArr[1];

        startAlpha = -Math.PI/2;
        alpha0 =  alpha1 = startAlpha;
        posArr = [];
	    pos1 = 0;
        for(i=0;i<data.length;i++){
            if(!value1){
                value = config.value(data[i]);
                /*a relative value*/
                value0 = (parseFloat(value||0) - min)*valueFactor;
            }
            else
                value0 = value1;
            r0 = Math.floor(unit*value0);

            value = config.value((i!=(data.length-1))?data[i+1]:data[0]);
            value1 = (parseFloat(value||0) - min)*valueFactor;
            r1 = Math.floor(unit*value1);
            alpha0 = alpha1;
            alpha1 = ((i!=(data.length-1))?(startAlpha+ratios[i]-0.0001):startAlpha);
            pos0 = (pos1||this._getPositionByAngle(alpha0,x,y,r0));
            pos1 = this._getPositionByAngle(alpha1,x,y,r1);
            /*creates map area*/
			/*areaWidth  = (config.eventRadius||(parseInt(config.item.radius.call(this,data[i]),10)+config.item.borderWidth));
		    map.addRect(data[i].id,[pos0.x-areaWidth,pos0.y-areaWidth,pos0.x+areaWidth,pos0.y+areaWidth],sIndex);*/
            //this._drawLine(ctx,pos0.x,pos0.y,pos1.x,pos1.y,config.line.color.call(this,data[i]),config.line.width)
            posArr.push(pos0);
        }
         if(config.fill)
             this._fillRadarChart(ctx,posArr,data);
         if(!config.disableLines)
            this._strokeRadarChart(ctx,posArr,data);
         if(!config.disableItems)
             this._drawRadarItemMarkers(ctx,posArr,data,sIndex,map);
         posArr = null;
    },
    _drawRadarItemMarkers:function(ctx,points,data,sIndex,map){
        for(var i=0;i < points.length;i++){
            this._drawItem(ctx,points[i].x,points[i].y,data[i],this._settings.label.call(this,data),sIndex,map);
        }
    },
     _fillRadarChart:function(ctx,points,data){
        var pos0,pos1;
        ctx.globalAlpha= this._settings.alpha.call(this,{});

		ctx.beginPath();
        for(var i=0;i < points.length;i++){
            ctx.fillStyle = this._settings.fill.call(this,data[i]);
            pos0 = points[i];
            pos1 = (points[i+1]|| points[0]);
            if(!i){

                ctx.moveTo(pos0.x,pos0.y);
            }
            ctx.lineTo(pos1.x,pos1.y)
        }
         ctx.fill();
         ctx.globalAlpha=1;
    },
    _strokeRadarChart:function(ctx,points,data){
        var pos0,pos1;
        for(var i=0;i < points.length;i++){
            pos0 = points[i];
            pos1 = (points[i+1]|| points[0]);
            this._drawLine(ctx,pos0.x,pos0.y,pos1.x,pos1.y,this._settings.line.color.call(this,data[i]),this._settings.line.width)
        }
    },
    _drawRadarAxises:function(ratios,x,y,radius,data){
        var configY = this._settings.yAxis;
        var configX = this._settings.xAxis;
        var start = configY.start;
        var end = configY.end;
        var step = configY.step;
        var scaleParam= {};
        var config = this._configYAxis;
        if(typeof config.step =="undefined"||typeof config.start=="undefined"||typeof config.end =="undefined"){
            var limits = this._getLimits();
			scaleParam = this._calculateScale(limits.min,limits.max);
			start = scaleParam.start;
			end = scaleParam.end;
			step = scaleParam.step;
			configY.end = end;
			configY.start = start;
		}
        var units = [];
        var i,j,p;
        var c=0;
        var stepHeight = radius*step/(end-start);
        /*correction for small step*/
        var power,corr;
        if(step<1){
			power = Math.min(this._log10(step),(start<=0?0:this._log10(start)));
			corr = Math.pow(10,-power);
        }
        var angles = [];
	    if(!this.canvases["scale"])
	        this.canvases["scale"] =  new dhtmlx.ui.Canvas(this._obj,"radar_scale");
	    var ctx = this.canvases["scale"].getCanvas();
        for(i = end; i>=start; i -=step){
			if(scaleParam.fixNum)  i = parseFloat((new Number(i)).toFixed(scaleParam.fixNum));
            units.push(Math.floor(c*stepHeight)+ 0.5);
            if(corr){
				i = Math.round(i*corr)/corr;
			}
            var unitY = y-radius+units[units.length-1];

            this.canvases["scale"].renderTextAt("middle","left",x,unitY,
				configY.template(i.toString()),
				"dhx_axis_item_y dhx_radar"
			);
            if(ratios.length<2){
                this._drawScaleSector(ctx,"arc",x,y,radius-units[units.length-1],-Math.PI/2,3*Math.PI/2,i);
                return;
            }
            var startAlpha = -Math.PI/2;/*possibly need  to moved in config*/
            var alpha0 = startAlpha;
            var alpha1;
            for(j=0;j< ratios.length;j++){
                if(i==end)
                   angles.push(alpha0);
                alpha1 = startAlpha+ratios[j]-0.0001;
                this._drawScaleSector(ctx,(config.lineShape||"line"),x,y,radius-units[units.length-1],alpha0,alpha1,i,j,data[i]);
                alpha0 = alpha1;
            }
            c++;
        }
         /*renders radius lines and labels*/
        for(i=0;i< angles.length;i++){
            p = this._getPositionByAngle(angles[i],x,y,radius);
	        if(configX.lines.call(this,data[i],i))
                this._drawLine(ctx,x,y,p.x,p.y,(configX?configX.lineColor.call(this,data[i]):"#cfcfcf"),1);
            this._drawRadarScaleLabel(ctx,x,y,radius,angles[i],(configX?configX.template.call(this,data[i]):"&nbsp;"));
        }

    },
    _drawScaleSector:function(ctx,shape,x,y,radius,a1,a2,i,j){
         var pos1, pos2;
         if(radius<0)
            return false;
         pos1 = this._getPositionByAngle(a1,x,y,radius);
         pos2 = this._getPositionByAngle(a2,x,y,radius);
         var configY = this._settings.yAxis;
         if(configY.bg){
             ctx.beginPath();
             ctx.moveTo(x,y);
             if(shape=="arc")
                 ctx.arc(x,y,radius,a1,a2,false);
             else{
                 ctx.lineTo(pos1.x,pos1.y);
                 ctx.lineTo(pos2.x,pos2.y);
             }
             ctx.fillStyle =  configY.bg(i,j);
             ctx.moveTo(x,y);
             ctx.fill();
             ctx.closePath();
         }
         if(configY.lines.call(this,i)){
             ctx.lineWidth = 1;
             ctx.beginPath();
              if(shape=="arc")
                 ctx.arc(x,y,radius,a1,a2,false);
             else{
                 ctx.moveTo(pos1.x,pos1.y);
                 ctx.lineTo(pos2.x,pos2.y);
             }
             ctx.strokeStyle = configY.lineColor.call(this,i);
             ctx.stroke();
         }
    },
    _drawRadarScaleLabel:function(ctx,x,y,r,a,text){
         var t = this.canvases["scale"].renderText(0,0,text,"dhx_axis_radar_title",1);
         var width = t.scrollWidth;
         var height = t.offsetHeight;
         var delta = 0.001;
         var pos =  this._getPositionByAngle(a,x,y,r+5);
         var corr_x=0,corr_y=0;
         if(a<0||a>Math.PI){
             corr_y = -height;
         }
         if(a>Math.PI/2){
             corr_x = -width;
         }
         if(Math.abs(a+Math.PI/2)<delta||Math.abs(a-Math.PI/2)<delta){
            corr_x = -width/2;
         }
         else if(Math.abs(a)<delta||Math.abs(a-Math.PI)<delta){
            corr_y = -height/2;
         }
         t.style.top  = pos.y+corr_y+"px";
	     t.style.left = pos.x+corr_x+"px";
		 t.style.width = width+"px";
		 t.style.whiteSpace = "nowrap";
    }
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_area.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.area = {
	/**
	*   renders an area chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: width - the width of the container
	*   @param: height - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_area:function(ctx, data, point0, point1, sIndex, map){
		var align, config, i, mapRect, obj, params, path,
			res1, res2, x0, x1, y1, x2, y2, y0;

		params = this._calculateLineParams(ctx,data,point0,point1,sIndex);
		config = this._settings;

		//the size of map area
		mapRect = (config.eventRadius||Math.floor(params.cellWidth/2));

		if (data.length) {

			// area points
			path = [];

			//the x position of the first item
			x0 = (!config.offset?point0.x:point0.x+params.cellWidth*0.5);

			/*
				iterates over all data items:
			    calculates [x,y] for area path, adds rect to chart map and renders labels
			*/
			for(i=0; i < data.length;i ++){
				obj = data[i];

				res2 = this._getPointY(obj,point0,point1,params);
				x2 = x0 + params.cellWidth*i ;
				if(res2){
					y2 = (typeof res2 == "object"?res2.y0:res2);
					if(i && this._settings.fixOverflow){
						res1 = this._getPointY(data[i-1],point0,point1,params);
						if(res1.out && res1.out == res2.out){
							continue;
						}
						x1 = params.cellWidth*(i-1) - 0.5 + x0;
						y1 = (typeof res1 == "object"?res1.y0:res1);
						if(res1.out){
							y0 = (res1.out == "min"?point1.y:point0.y);
							path.push([this._calcOverflowX(x1,x2,y1,y2,y0),y0]);
						}
						if(res2.out){
							y0 = (res2.out == "min"?point1.y:point0.y);
							path.push([this._calcOverflowX(x1,x2,y1,y2,y0),y0]);
							if(i == (data.length-1) && y0 == point0.y)
								path.push([x2,point0.y]);
						}
					}
					if(!res2.out){
						path.push([x2,y2]);
						//map
						map.addRect(obj.id,[x2-mapRect-point0.x,y2-mapRect-point0.y,x2+mapRect-point0.x,y2+mapRect-point0.y],sIndex);
					}

					//labels
					if(!config.yAxis){
						align = (!config.offset&&(i == data.length-1)?"left":"center");
						this.canvases[sIndex].renderTextAt(false, align, x2, y2-config.labelOffset,config.label(obj));
					}
				}

			}
			if(path.length){
				path.push([x2,point1.y]);
				path.push([path[0][0],point1.y]);
			}



			//filling area
			ctx.globalAlpha = this._settings.alpha.call(this,data[0]);
			ctx.fillStyle = this._settings.color.call(this,data[0]);
			ctx.beginPath();
			this._path(ctx,path);
			ctx.fill();

			//border
			if(config.border){
				ctx.lineWidth = config.borderWidth||1;
				if(config.borderColor)
					ctx.strokeStyle =  config.borderColor.call(this,data[0]);
				else
					this._setBorderStyles(ctx,ctx.fillStyle);

				ctx.beginPath();
				this._path(ctx,path);
				ctx.stroke();

			}
			ctx.lineWidth = 1;
			ctx.globalAlpha =1;

		}
	}
};
dhtmlx.chart.stackedArea ={
	/**
	*   renders an area chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: width - the width of the container
	*   @param: height - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_stackedArea:function(ctx, data, point0, point1, sIndex, map){
		var a0, a1, align, config, i, j, lastItem, mapRect, obj, params, path, x, y, yPos;

		params = this._calculateLineParams(ctx,data,point0,point1,sIndex);

		config = this._settings;

		/*the value that defines the map area position*/
		mapRect = (config.eventRadius||Math.floor(params.cellWidth/2));

	
		/*drawing all items*/
		if (data.length) {

			// area points
			path = [];

			// y item positions
			yPos = [];

			//the x position of the first item
			x = (!config.offset?point0.x:point0.x+params.cellWidth*0.5);


			var setOffset = function(i,y){
				return sIndex?(data[i].$startY?y-point1.y+data[i].$startY:0):y;
			};

			var solveEquation  = function(x,p0,p1){
				var k = (p1.y - p0.y)/(p1.x - p0.x);
				return  k*x + p0.y - k*p0.x;
			};

			/*
			 iterates over all data items:
			 calculates [x,y] for area path, adds rect to chart map and renders labels
			 */

			for(i=0; i < data.length;i ++){
				obj = data[i];

				if(!i){
					y =  setOffset(i,point1.y);
					path.push([x,y]);
				}
				else{
					x += params.cellWidth ;
				}

				y = setOffset(i,this._getPointY(obj,point0,point1,params));

				yPos.push((isNaN(y)&&!i)?(data[i].$startY||point1.y):y);

				if(y){
					path.push([x,y]);

					//map
					map.addRect(obj.id,[x-mapRect-point0.x,y-mapRect-point0.y,x+mapRect-point0.x,y+mapRect-point0.y],sIndex);

					//labels
					if(!config.yAxis){
						align = (!config.offset&&lastItem?"left":"center");
						this.canvases[sIndex].renderTextAt(false, align, x, y-config.labelOffset,config.label(obj));
					}
				}
			}

			// bottom right point
			path.push([x,setOffset(i-1,point1.y)]);

			// lower border from the end to start
            if(sIndex){
				for(i=data.length-2; i > 0; i --){
				    x -= params.cellWidth ;
					y =  data[i].$startY;
					if(y)
						path.push([x,y]);
				}
			}

			// go to start point
			path.push([path[0][0],path[0][1]]);

			// filling path
			ctx.globalAlpha = this._settings.alpha.call(this,data[0]);
			ctx.fillStyle = this._settings.color.call(this,data[0]);
			ctx.beginPath();
			this._path(ctx,path);
			ctx.fill();

			// set y positions of the next series
			for(i=0; i < data.length;i ++){
				y =  yPos[i];

				if(!y){
					if(i == data.length-1){
						y = data[i].$startY;
					}
					for(j =i+1; j< data.length; j++){
						if(yPos[j]){
							a0 =  {x:point0.x,y:yPos[0]};
							a1 =  {x:(point0.x+params.cellWidth*j),y:yPos[j]};
							y = solveEquation(point0.x+params.cellWidth*i,a0,a1);
							break;
						}

					}
				}

				data[i].$startY = y;
			}


		}
	}
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_spline.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.spline = {
	/**
	*   renders a spline chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: width - the width of the container
	*   @param: height - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_spline:function(ctx, data, point0, point1, sIndex, map){

		var config,i,items,j,params,sparam,x,x0,x1,x2,y,y1,y2;
		params = this._calculateLineParams(ctx,data,point0,point1,sIndex);
		config = this._settings;
		this._mapStart = point0;

		/*array of all points*/
		items = [];

		/*drawing all items*/
		if (data.length) {

			/*getting all points*/
			x0 = (config.offset?point0.x+params.cellWidth*0.5:point0.x);
			for(i=0; i < data.length;i ++){
				y = this._getPointY(data[i],point0,point1,params);
				if(y){
					x = ((!i)?x0:params.cellWidth*i - 0.5 + x0);
					items.push({x:x,y:y,index:i});
				}
			}
			sparam = this._getSplineParameters(items);

			for(i =0; i< items.length; i++){
				x1 = items[i].x;
				y1 = items[i].y;
				if(i<items.length-1){
					x2 = items[i+1].x;
					y2 = items[i+1].y;
					for(j = x1; j < x2; j++){
						var sY1 = this._getSplineYPoint(j,x1,i,sparam.a,sparam.b,sparam.c,sparam.d);
						if(sY1<point0.y)
							sY1=point0.y;
						if(sY1>point1.y)
							sY1=point1.y;
						var sY2 = this._getSplineYPoint(j+1,x1,i,sparam.a,sparam.b,sparam.c,sparam.d);
						if(sY2<point0.y)
							sY2=point0.y;
						if(sY2>point1.y)
							sY2=point1.y;
						this._drawLine(ctx,j,sY1,j+1,sY2,config.line.color(data[i]),config.line.width);

					}
					this._drawLine(ctx,x2-1,this._getSplineYPoint(j,x1,i,sparam.a,sparam.b,sparam.c,sparam.d),x2,y2,config.line.color(data[i]),config.line.width);
				}
				this._drawItem(ctx,x1,y1,data[items[i].index],config.label(data[items[i].index]), sIndex, map);
				/*creates map area*/
				/*radius = (parseInt(config.item.radius.call(this,data[i-1]),10)||2);
			    areaPos = (config.eventRadius||radius+1);
				map.addRect(data[i].id,[x1-areaPos,y1-areaPos,x1+areaPos,y1+areaPos],sIndex); */

			}
			//this._drawItemOfLineChart(ctx,x2,y2,data[i],config.label(data[i]));

		}
	},
	/*gets spline parameter*/
	_getSplineParameters:function(points){
		var i,u,v,s,a,b,c,d,
		h = [],
	    m = [],
		n = points.length;
		
		for(i =0; i<n-1;i++){
			h[i] = points[i+1].x - points[i].x;
			m[i] = (points[i+1].y - points[i].y)/h[i];
		}
		u = [];	v = [];
		u[0] = 0;
		u[1] = 2*(h[0] + h[1]);
		v[0] = 0;
		v[1] = 6*(m[1] - m[0]);
		for(i =2; i < n-1; i++){
			u[i] = 2*(h[i-1]+h[i]) - h[i-1]*h[i-1]/u[i-1];
	    	v[i] = 6*(m[i]-m[i-1]) - h[i-1]*v[i-1]/u[i-1];
		}
		
		s = [];
		s[n-1] = s[0] = 0;
		for(i = n -2; i>=1; i--)
	   		s[i] = (v[i] - h[i]*s[i+1])/u[i];
	
        a = []; b = []; c = [];	d = []; 
		
		for(i =0; i<n-1;i++){
			a[i] = points[i].y;
			b[i] = - h[i]*s[i+1]/6 - h[i]*s[i]/3 + (points[i+1].y-points[i].y)/h[i];
			c[i] = s[i]/2;
			d[i] = (s[i+1] - s[i])/(6*h[i]);
		}
		return {a:a,b:b,c:c,d:d};
	},
	/*returns the y position of the spline point */
	_getSplineYPoint:function(x,xi,i,a,b,c,d){
		return a[i] + (x - xi)*(b[i] + (x-xi)*(c[i]+(x-xi)*d[i])); 
	}
};


/* DHX DEPEND FROM FILE 'ext/chart/chart_barh.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.barH = {
	/**
	*   renders a bar chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: x - the width of the container
	*   @param: y - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_barH:function(ctx, data, point0, point1, sIndex, map){
	     var barOffset, barWidth, cellWidth, color, gradient, i, limits, maxValue, minValue,
		     innerGradient, valueFactor, relValue, radius, relativeValues,
		     startValue, totalWidth,value,  unit, x0, y0, yax;

		/*an available width for one bar*/
		cellWidth = (point1.y-point0.y)/data.length;

		limits = this._getLimits("h");

		maxValue = limits.max;
		minValue = limits.min;

		totalWidth = point1.x-point0.x;
		
		yax = !!this._settings.yAxis;
		
		/*draws x and y scales*/
		if(!sIndex)
			this._drawHScales(ctx,data,point0, point1,minValue,maxValue,cellWidth);
		
		/*necessary for automatic scale*/
		if(yax){
		    maxValue = parseFloat(this._settings.xAxis.end);
			minValue = parseFloat(this._settings.xAxis.start);
		}
		
		/*unit calculation (bar_height = value*unit)*/
		relativeValues = this._getRelativeValue(minValue,maxValue);
		relValue = relativeValues[0];
		valueFactor = relativeValues[1];
		
		unit = (relValue?totalWidth/relValue:10);
		if(!yax){
			/*defines start value for better representation of small values*/
			startValue = 10;
			unit = (relValue?(totalWidth-startValue)/relValue:10);
		}
		
		
		/*a real bar width */
		barWidth = parseInt(this._settings.width,10);


		var seriesNumber = this._series.length;
		var seriesMargin = this._settings.seriesMargin;
		var seriesPadding = this._settings.seriesPadding;

		if(this._series&&(barWidth*seriesNumber+seriesPadding + (seriesNumber>2?seriesMargin*seriesNumber:0)>cellWidth) )
			barWidth = cellWidth/seriesNumber-seriesPadding-(seriesNumber>2?seriesMargin:0);

		/*the half of distance between bars*/
		barOffset = (cellWidth - barWidth*seriesNumber - seriesMargin*(seriesNumber-1))/2;

		if(this._settings.border){
			barWidth = parseInt(barWidth,10);
			barOffset = parseInt(barOffset,10);
		}

		/*the radius of rounding in the top part of each bar*/
		radius = (typeof this._settings.radius!="undefined"?parseInt(this._settings.radius,10):Math.round(barWidth/5));
		
		innerGradient = false;
		gradient = this._settings.gradient;
	
		if (gradient&&typeof(gradient) != "function"){
			innerGradient = gradient;
			gradient = false;
		} else if (gradient){
			gradient = ctx.createLinearGradient(point0.x,point0.y,point1.x,point0.y);
			this._settings.gradient(gradient);
		}
		/*draws a black line if the horizontal scale isn't defined*/
		if(!yax){
			this._drawLine(ctx,point0.x-0.5,point0.y,point0.x-0.5,point1.y,"#000000",1); //hardcoded color!
		}
		
		
		
		for(i=0; i < data.length;i ++){
			
			
			value =  parseFloat(this._settings.value(data[i]||0));
			if(value>maxValue) value = maxValue;
			value -= minValue;
			value *= valueFactor;
			
			/*start point (bottom left)*/
			x0 = point0.x;
			y0 = point0.y + barOffset+(seriesNumber>2?seriesMargin*sIndex:0) + parseInt(i*cellWidth,10)+barWidth*sIndex;

			if((value<0&&this._settings.origin=="auto")||(this._settings.xAxis&&value===0&&!(this._settings.origin!="auto"&&this._settings.origin>minValue))){
				this.canvases[sIndex].renderTextAt("middle", "right", x0+10,y0+barWidth/2+barOffset,this._settings.label(data[i]));
				continue;
			}
			if(value<0&&this._settings.origin!="auto"&&this._settings.origin>minValue){
				value = 0;
			}
			
			/*takes start value into consideration*/
			if(!yax) value += startValue/unit;
			color = gradient||this._settings.color.call(this,data[i]);
			
			/*drawing the gradient border of a bar*/
			if(this._settings.border){
				this._drawBarHBorder(ctx,x0,y0,barWidth,minValue,radius,unit,value,color);
			}

			/*drawing bar body*/
			ctx.globalAlpha = this._settings.alpha.call(this,data[i]);
			var points = this._drawBarH(ctx,point1,x0,y0,barWidth,minValue,radius,unit,value,color,gradient,innerGradient);
			if (innerGradient!=false){
				this._drawBarHGradient(ctx,x0,y0,barWidth,minValue,radius,unit,value,color,innerGradient);

			}
			ctx.globalAlpha = 1;
			
			
			/*sets a bar label and map area*/
	
			if(points[3]==y0){
				this.canvases[sIndex].renderTextAt("middle", "left", points[0]-5,points[3]+Math.floor(barWidth/2),this._settings.label(data[i]));
				map.addRect(data[i].id,[points[0]-point0.x,points[3]-point0.y,points[2]-point0.x,points[3]+barWidth-point0.y],sIndex);
			
			}else{
				this.canvases[sIndex].renderTextAt("middle", false, points[2]+5,points[1]+Math.floor(barWidth/2),this._settings.label(data[i]));
				map.addRect(data[i].id,[points[0]-point0.x,y0-point0.y,points[2]-point0.x,points[3]-point0.y],sIndex);
			}
			  
		}
	},
	/**
	*   sets points for bar and returns the position of the bottom right point
	*   @param: ctx - canvas object
	*   @param: x0 - the x position of start point
	*   @param: y0 - the y position of start point
	*   @param: barWidth - bar width 
	*   @param: radius - the rounding radius of the top
	*   @param: unit - the value defines the correspondence between item value and bar height
	*   @param: value - item value
	*   @param: offset - the offset from expected bar edge (necessary for drawing border)
	*/
	_setBarHPoints:function(ctx,x0,y0,barWidth,radius,unit,value,offset,skipLeft){
		/*correction for displaing small values (when rounding radius is bigger than bar height)*/
		var angle_corr = 0;
		if(radius>unit*value){
			var sinA = (radius-unit*value)/radius;
			angle_corr = -Math.asin(sinA)+Math.PI/2;
		}
		/*start*/
		ctx.moveTo(x0,y0+offset);
		/*start of left rounding*/
		var x1 = x0 + unit*value - radius - (radius?0:offset);
		if(radius<unit*value)
			ctx.lineTo(x1,y0+offset);
   		/*left rounding*/
		var y2 = y0 + radius;
		if (radius&&radius>0)
			ctx.arc(x1,y2,radius-offset,-Math.PI/2+angle_corr,0,false);
   		/*start of right rounding*/
		var y3 = y0 + barWidth - radius - (radius?0:offset);
		var x3 = x1 + radius - (radius?offset:0);
		ctx.lineTo(x3,y3);
		/*right rounding*/
		if (radius&&radius>0)
			ctx.arc(x1,y3,radius-offset,0,Math.PI/2-angle_corr,false);
   		/*bottom right point*/
		var y5 = y0 + barWidth-offset;
        ctx.lineTo(x0,y5);
		/*line to the start point*/
		if(!skipLeft){
   			ctx.lineTo(x0,y0+offset);
   		}
	//	ctx.lineTo(x0,0); //IE fix!
		return [x3,y5];
	},
	 _drawHScales:function(ctx,data,point0,point1,start,end,cellWidth){
		 var x = 0;
		 if(this._settings.xAxis){
			 if(!this.canvases["x"])
			    this.canvases["x"] =  new dhtmlx.ui.Canvas(this._obj);
			 x = this._drawHXAxis(this.canvases["x"].getCanvas(),data,point0,point1,start,end);
		 }
		 if (this._settings.yAxis){
			 if(!this.canvases["y"])
			    this.canvases["y"] =  new dhtmlx.ui.Canvas(this._obj);
		    this._drawHYAxis(this.canvases["y"].getCanvas(),data,point0,point1,cellWidth,x);
		 }
	},
	_drawHYAxis:function(ctx,data,point0,point1,cellWidth,yAxisX){
		if (!this._settings.yAxis) return;
		var unitPos;
		var x0 = parseInt((yAxisX?yAxisX:point0.x),10)-0.5;
		var y0 = point1.y+0.5;
		var y1 = point0.y;
		this._drawLine(ctx,x0,y0,x0,y1,this._settings.yAxis.color,1);



		for(var i=0; i < data.length;i ++){

			/*scale labels*/
			var right = ((this._settings.origin!="auto")&&(this._settings.view=="barH")&&(parseFloat(this._settings.value(data[i]))<this._settings.origin));
			unitPos = y1+cellWidth/2+i*cellWidth;
			this.canvases["y"].renderTextAt("middle",(right?false:"left"),(right?x0+5:x0-5),unitPos,
				this._settings.yAxis.template(data[i]),
				"dhx_axis_item_y",(right?0:x0-10)
			);
			if(this._settings.yAxis.lines.call(this,data[i]))
				this._drawLine(ctx,point0.x,unitPos,point1.x,unitPos,this._settings.yAxis.lineColor.call(this,data[i]),1);
		}
		this._drawLine(ctx,point0.x+0.5,y1+0.5,point1.x,y1+0.5,this._settings.yAxis.lineColor.call(this,{}),1);
		this._setYAxisTitle(point0,point1);
	},
	_drawHXAxis:function(ctx,data,point0,point1,start,end){
		var step;
		var scaleParam= {};
		var axis = this._settings.xAxis;
		if (!axis) return;
		
		var y0 = point1.y+0.5;
		var x0 = point0.x-0.5;
		var x1 = point1.x-0.5;
		var yAxisStart = point0.x;
		this._drawLine(ctx,x0,y0,x1,y0,axis.color,1);
		
		if(axis.step)
		     step = parseFloat(axis.step);
		
		if(typeof this._configXAxis.step =="undefined"||typeof this._configXAxis.start=="undefined"||typeof this._configXAxis.end =="undefined"){
			scaleParam = this._calculateScale(start,end);
			start = scaleParam.start;
			end = scaleParam.end;
			step = scaleParam.step;
			this._settings.xAxis.end = end;
			this._settings.xAxis.start = start;
			this._settings.xAxis.step = step;
		}
		
		if(step===0) return;
		var stepHeight = (x1-x0)*step/(end-start);
		var c = 0;
		for(var i = start; i<=end; i += step){
			if(scaleParam.fixNum)  i = parseFloat((new Number(i)).toFixed(scaleParam.fixNum));
			var xi = Math.floor(x0+c*stepHeight)+ 0.5;/*canvas line fix*/
			if(!(i==start&&this._settings.origin=="auto") &&axis.lines.call(this,i))
				this._drawLine(ctx,xi,y0,xi,point0.y,this._settings.xAxis.lineColor.call(this,i),1);
			if(i == this._settings.origin) yAxisStart = xi+1;
			var label = i;
			if(step<1){
				var power = Math.min(this._log10(step),(start<=0?0:this._log10(start)));
				var corr = Math.pow(10,-power);
				label = Math.round(i*corr)/corr;
			}
			this.canvases["x"].renderTextAt(false, true,xi,y0+2,axis.template(label.toString()),"dhx_axis_item_x");
			c++;
		}
		this.canvases["x"].renderTextAt(true, false, x0,point1.y+this._settings.padding.bottom-3,
			this._settings.xAxis.title,
			"dhx_axis_title_x",
			point1.x - point0.x
		);
		/*the right border in lines in scale are enabled*/
		if (!axis.lines.call(this,{})){
			this._drawLine(ctx,x0,point0.y-0.5,x1,point0.y-0.5,this._settings.xAxis.color,0.2);
		}
		return yAxisStart;
	},
	_correctBarHParams:function(ctx,x,y,value,unit,barWidth,minValue){
		var yax = this._settings.yAxis;
		var axisStart = x;
		if(!!yax&&this._settings.origin!="auto" && (this._settings.origin>minValue)){
			x += (this._settings.origin-minValue)*unit;
			axisStart = x;
			value = value-(this._settings.origin-minValue);
			if(value < 0){
				value *= (-1);
			 	ctx.translate(x,y+barWidth);
				ctx.rotate(Math.PI);
				x = 0.5;
				y = 0;
			}
			x += 0.5;
		}
		
		return {value:value,x0:x,y0:y,start:axisStart}
	},
	_drawBarH:function(ctx,point1,x0,y0,barWidth,minValue,radius,unit,value,color,gradient,inner_gradient){
		var points;
		ctx.save();
		var p = this._correctBarHParams(ctx,x0,y0,value,unit,barWidth,minValue);	
		ctx.fillStyle = color;
		ctx.beginPath();
		if(unit*p.value>0){
			points = this._setBarHPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,(this._settings.border?1:0));
			if (gradient&&!inner_gradient) ctx.lineTo(point1.x,p.y0+(this._settings.border?1:0)); //fix gradient sphreading
		}
		else
			points = [p.x0,p.y0+1];

   		ctx.fill();
		ctx.restore();
		var y1 = p.y0;
		var y2 = (p.y0!=y0?y0:points[1]);
		var x1 = (p.y0!=y0?(p.start-points[0]):p.start);
		var x2 = (p.y0!=y0?p.start:points[0]);
		
		return [x1,y1,x2,y2];
	},
	_drawBarHBorder:function(ctx,x0,y0,barWidth,minValue,radius,unit,value,color){
		ctx.save();
		var p = this._correctBarHParams(ctx,x0,y0,value,unit,barWidth,minValue);	
		
		ctx.beginPath();
		this._setBorderStyles(ctx,color);
		ctx.globalAlpha =0.9;
		if(unit*p.value>0)
			this._setBarHPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,ctx.lineWidth/2,1);
		
		ctx.stroke();	
	    ctx.restore();
	},
	_drawBarHGradient:function(ctx,x0,y0,barWidth,minValue,radius,unit,value,color,inner_gradient){
		ctx.save();
		//y0 -= (dhx.env.isIE?0:0.5);
		var p = this._correctBarHParams(ctx,x0,y0,value,unit,barWidth,minValue);	
		var gradParam = this._setBarGradient(ctx,p.x0,p.y0+barWidth,p.x0+unit*p.value,p.y0,inner_gradient,color,"x");
		ctx.fillStyle = gradParam.gradient;
		ctx.beginPath();
		if(unit*p.value>0)
			this._setBarHPoints(ctx,p.x0,p.y0+gradParam.offset,barWidth-gradParam.offset*2,radius,unit,p.value,gradParam.offset);
		ctx.fill();
		ctx.globalAlpha = 1;
	    ctx.restore();
	}
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_stackedbarh.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
/*DHX:Depend ext/chart/chart_barh.js*/

dhtmlx.assert(dhtmlx.chart.barH);
dhtmlx.chart.stackedBarH = {
/**
	*   renders a bar chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: x - the width of the container
	*   @param: y - the height of the container
	*   @param: sIndex - index of drawing chart
	*   @param: map - map object
	*/
	pvt_render_stackedBarH:function(ctx, data, point0, point1, sIndex, map){
	   var maxValue,minValue;
		/*necessary if maxValue - minValue < 0*/
		var valueFactor;
		/*maxValue - minValue*/
		var relValue;
		
		var total_width = point1.x-point0.x;
		
		var yax = !!this._settings.yAxis;
		
		var limits = this._getStackedLimits(data);
		maxValue = limits.max;
		minValue = limits.min;
		
		/*an available width for one bar*/
		var cellWidth = Math.floor((point1.y-point0.y)/data.length);
	
		/*draws x and y scales*/
		if(!sIndex)
			this._drawHScales(ctx,data,point0, point1,minValue,maxValue,cellWidth);
		
		/*necessary for automatic scale*/
		if(yax){
		    maxValue = parseFloat(this._settings.xAxis.end);
			minValue = parseFloat(this._settings.xAxis.start);      
		}
		
		/*unit calculation (bar_height = value*unit)*/
		var relativeValues = this._getRelativeValue(minValue,maxValue);
		relValue = relativeValues[0];
		valueFactor = relativeValues[1];
		
		var unit = (relValue?total_width/relValue:10);
		if(!yax){
			/*defines start value for better representation of small values*/
			var startValue = 10;
			unit = (relValue?(total_width-startValue)/relValue:10);
		}
		
		/*a real bar width */
		var barWidth = parseInt(this._settings.width,10);
		if((barWidth+4)>cellWidth) barWidth = cellWidth-4;
		/*the half of distance between bars*/
		var barOffset = (cellWidth - barWidth)/2;
		/*the radius of rounding in the top part of each bar*/
		var radius = 0;

		var inner_gradient = false;
		var gradient = this._settings.gradient;
		if (gradient){
			inner_gradient = true;
		}
		/*draws a black line if the horizontal scale isn't defined*/
		if(!yax){
			this._drawLine(ctx,point0.x-0.5,point0.y,point0.x-0.5,point1.y,"#000000",1); //hardcoded color!
		}

		var seriesNumber = 0;
		var seriesIndex = 0;
		for(i=0; i<this._series.length; i++ ){
			if(i == sIndex){
				seriesIndex  = seriesNumber;
			}
			if(this._series[i].view == "stackedBarH")
				seriesNumber++;
		}

		for(var i=0; i < data.length;i ++){
			
			if(!seriesIndex)
			   data[i].$startX = point0.x;
			
			var value =  parseFloat(this._settings.value(data[i]||0));
			if(value>maxValue) value = maxValue;
			value -= minValue;
			value *= valueFactor;
			
			/*start point (bottom left)*/
			var x0 = point0.x;
			var y0 = point0.y+ barOffset + i*cellWidth;
			
			if(!seriesIndex)
                data[i].$startX = x0;
			else
			    x0 = data[i].$startX;
			
			if(value<0||(this._settings.yAxis&&value===0)){
				this.canvases["y"].renderTextAt("middle", true, x0+10,y0+barWidth/2,this._settings.label(data[i]));
				continue;
			}
			
			/*takes start value into consideration*/
			if(!yax) value += startValue/unit;
			var color = this._settings.color.call(this,data[i]);
			
			
			/*drawing bar body*/
			ctx.globalAlpha = this._settings.alpha.call(this,data[i]);
			ctx.fillStyle = this._settings.color.call(this,data[i]);
			ctx.beginPath();
			var points = this._setBarHPoints(ctx,x0,y0,barWidth,radius,unit,value,(this._settings.border?1:0));
			if (gradient&&!inner_gradient) ctx.lineTo(point0.x+total_width,y0+(this._settings.border?1:0)); //fix gradient sphreading
   			ctx.fill();
			
			if (inner_gradient!=false){
				var gradParam = this._setBarGradient(ctx,x0,y0+barWidth,x0,y0,inner_gradient,color,"x");
				ctx.fillStyle = gradParam.gradient;
				ctx.beginPath();
				points = this._setBarHPoints(ctx,x0,y0, barWidth,radius,unit,value,0);
				ctx.fill();
			}
			/*drawing the gradient border of a bar*/
			if(this._settings.border){
				this._drawBarHBorder(ctx,x0,y0,barWidth,minValue,radius,unit,value,color);
			}
			
			ctx.globalAlpha = 1;
			
			/*sets a bar label*/
			this.canvases[sIndex].renderTextAt("middle",true,data[i].$startX+(points[0]-data[i].$startX)/2-1, y0+(points[1]-y0)/2, this._settings.label(data[i]));
			/*defines a map area for a bar*/
			map.addRect(data[i].id,[data[i].$startX-point0.x,y0-point0.y,points[0]-point0.x,points[1]-point0.y],sIndex);
			/*the start position for the next series*/
			data[i].$startX = points[0];
		}
	}
};


/* DHX DEPEND FROM FILE 'ext/chart/chart_stackedbar.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.stackedBar = {
	/**
	*   renders a bar chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: x - the width of the container
	*   @param: y - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_stackedBar:function(ctx, data, point0, point1, sIndex, map){
		var maxValue,minValue, xAxisY, x0, y0;
		/*necessary if maxValue - minValue < 0*/
		var valueFactor;
		/*maxValue - minValue*/
		var relValue;
		var config = this._settings;
		var total_height = point1.y-point0.y;

		var yax = !!config.yAxis;
		var xax = !!config.xAxis;

		var limits = this._getStackedLimits(data);

		var origin = (config.origin === 0);

		maxValue = limits.max;
		minValue = limits.min;
		if(!data.length)
			return;
		/*an available width for one bar*/
		var cellWidth = (point1.x-point0.x)/data.length;

		/*draws x and y scales*/
		if(!sIndex){
			xAxisY = this._drawScales(data,point0, point1,minValue,maxValue,cellWidth);
		}

		/*necessary for automatic scale*/
		if(yax){
			maxValue = parseFloat(config.yAxis.end);
			minValue = parseFloat(config.yAxis.start);
		}

		/*unit calculation (bar_height = value*unit)*/
		var relativeValues = this._getRelativeValue(minValue,maxValue);
		relValue = relativeValues[0];
		valueFactor = relativeValues[1];

		var unit = (relValue?total_height/relValue:10);

		/*a real bar width */
		var barWidth = parseInt(config.width,10);
		if(barWidth+4 > cellWidth) barWidth = cellWidth-4;
		/*the half of distance between bars*/
		var barOffset = Math.floor((cellWidth - barWidth)/2);


		var inner_gradient = (config.gradient?config.gradient:false);

		/*draws a black line if the horizontal scale isn't defined*/
		if(!xax){
			//scaleY = y-bottomPadding;
			this._drawLine(ctx,point0.x,point1.y+0.5,point1.x,point1.y+0.5,"#000000",1); //hardcoded color!
		}

		for(var i=0; i < data.length;i ++){
			var value =  parseFloat(config.value(data[i]||0));

			if(this._logScaleCalc)
				value = this._log10(value);

			/*start point (bottom left)*/
			x0 = point0.x + barOffset + i*cellWidth;
			
			var negValue = origin&&value<0;
			if(!sIndex){
				y0 = xAxisY-1;
				data[i].$startY = y0;
				if(origin){
					if(negValue)
						y0 = xAxisY+1;
					data[i].$startYN = xAxisY+1;
				}
			}
			else{
				y0 = negValue?data[i].$startYN:data[i].$startY;
			}

			if(!value)
				continue;

			/*adjusts the first tab to the scale*/
			if(!sIndex && !origin)
				value -= minValue;

			value *= valueFactor;

			/*the max height limit*/
			if(y0 < (point0.y+1)) continue;

			if(config.yAxis&&value===0){
				this.canvases["y"].renderTextAt(true, true, x0+Math.floor(barWidth/2),y0,this._settings.label(data[i]));
				continue;
			}

			var color = this._settings.color.call(this,data[i]);
			var firstSector =  Math.abs(y0-(origin?(point1.y+minValue*unit):point1.y))<3;

			/*drawing bar body*/
			ctx.globalAlpha = config.alpha.call(this,data[i]);
			ctx.fillStyle = ctx.strokeStyle = config.color.call(this,data[i]);
			ctx.beginPath();

			var y1 = y0 - unit*value + (firstSector?(negValue?-1:1):0);

			var points = this._setStakedBarPoints(ctx,x0-(config.border?0.5:0),y0,barWidth+(config.border?0.5:0),y1, 0,point0.y);
			ctx.fill();
			ctx.stroke();

			/*gradient*/
			if (inner_gradient){
				ctx.save();
				var gradParam = this._setBarGradient(ctx,x0,y0,x0+barWidth,points[1],inner_gradient,color,"y");
				ctx.fillStyle = gradParam.gradient;
				ctx.beginPath();
				points = this._setStakedBarPoints(ctx,x0+gradParam.offset,y0,barWidth-gradParam.offset*2,y1,(config.border?1:0),point0.y);
				ctx.fill();
				ctx.restore();
			}
			/*drawing the gradient border of a bar*/
			if(config.border){
				ctx.save();
				if(typeof config.border == "string")
					ctx.strokeStyle = config.border;
				else
					this._setBorderStyles(ctx,color);
				ctx.beginPath();

				this._setStakedBarPoints(ctx,x0-0.5,parseInt(y0,10)+0.5,barWidth+1,parseInt(y1,10)+0.5,0,point0.y, firstSector);
				ctx.stroke();
				ctx.restore();
			}
			ctx.globalAlpha = 1;

			/*sets a bar label*/
			this.canvases[sIndex].renderTextAt(false, true, x0+Math.floor(barWidth/2),(points[1]+(y0-points[1])/2)-7,this._settings.label(data[i]));
			/*defines a map area for a bar*/
			map.addRect(data[i].id,[x0-point0.x,points[1]-point0.y,points[0]-point0.x,data[i][negValue?"$startYN":"$startY"]-point0.y],sIndex);

			/*the start position for the next series*/

			data[i][negValue?"$startYN":"$startY"] = points[1];

		}
	},
	/**
	 *   sets points for bar and returns the position of the bottom right point
	 *   @param: ctx - canvas object
	 *   @param: x0 - the x position of start point
	 *   @param: y0 - the y position of start point
	 *   @param: barWidth - bar width
	 *   @param: radius - the rounding radius of the top
	 *   @param: unit - the value defines the correspondence between item value and bar height
	 *   @param: value - item value
	 *   @param: offset - the offset from expected bar edge (necessary for drawing border)
	 *   @param: minY - the minimum y position for the bars ()
	 */
	_setStakedBarPoints:function(ctx,x0,y0,barWidth,y1,offset,minY,skipBottom){
		/*start*/
		ctx.moveTo(x0,y0);

		/*maximum height limit*/
		if(y1<minY)
			y1 = minY;
		ctx.lineTo(x0,y1);
		var x3 = x0 + barWidth;
		var y3 = y1;
		ctx.lineTo(x3,y3);
		/*right rounding*/
		/*bottom right point*/
		var x5 = x0 + barWidth;
		ctx.lineTo(x5,y0);
		/*line to the start point*/
		if(!skipBottom){
			ctx.lineTo(x0,y0);
		}
		//	ctx.lineTo(x0,0); //IE fix!
		return [x5,y3];
	}
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_line.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.line = {

	/**
	*   renders a graphic
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: width - the width of the container
	*   @param: height - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_line:function(ctx, data, point0, point1, sIndex, map){

		var config,i,items,params,x0,x1,x2,y1,y2,y0,res1,res2;
	    params = this._calculateLineParams(ctx,data,point0,point1,sIndex);
		config = this._settings;
		if (data.length) {
			x0 = (config.offset?point0.x+params.cellWidth*0.5:point0.x);
			//finds items with data (excludes scale units)
			items= [];
			for(i=0; i < data.length;i++){
				res2 = this._getPointY(data[i],point0,point1,params);
				if(res2){
					x2 = ((!i)?x0:params.cellWidth*i - 0.5 + x0);
					y2 = (typeof res2 == "object"?res2.y0:res2);
					if(i && this._settings.fixOverflow){
						res1 = this._getPointY(data[i-1],point0,point1,params);
						if(res1.out && res1.out == res2.out){
							continue;
						}
						x1 = params.cellWidth*(i-1) - 0.5 + x0;
						y1 = (typeof res1 == "object"?res1.y0:res1);

						if(res1.out){
							y0 = (res1.out == "min"?point1.y:point0.y);
							items.push({x:this._calcOverflowX(x1,x2,y1,y2,y0),y:y0});
						}
						if(res2.out){
							y0 = (res2.out == "min"?point1.y:point0.y);
							items.push({x:this._calcOverflowX(x1,x2,y1,y2,y0),y:y0});
						}

					}
					if(!res2.out)
						items.push({x:x2, y: res2, index: i});
				}
			}


			this._mapStart = point0;
			for(i = 1; i <= items.length; i++){
				//line start position
				x1 = items[i-1].x;
				y1 = items[i-1].y;
				if(i<items.length){
					//line end position
					x2 = items[i].x;
					y2 = items[i].y;
					//line

					this._drawLine(ctx,x1,y1,x2,y2,config.line.color.call(this,data[i-1]),config.line.width);
					//line shadow
					if(config.line&&config.line.shadow){
						ctx.globalAlpha = 0.3;

						this._drawLine(ctx,x1+2,y1+config.line.width+8,x2+2,y2+config.line.width+8,"#eeeeee",config.line.width+3);
						ctx.globalAlpha = 1;
					}
				}
				//item
				if(typeof items[i-1].index != "undefined")
					this._drawItem(ctx,x1,y1,data[items[i-1].index],config.label(data[items[i-1].index]), sIndex, map, point0);
			}

		}
	},
	_calcOverflowX: function(x1,x2,y1,y2,y){
		return  x1 + ( y - y1 )*( x2 - x1 )/( y2 - y1 );
	},
	/**
	*   draws an item and its label
	*   @param: ctx - canvas object
	*   @param: x0 - the x position of a circle
	*   @param: y0 - the y position of a circle
	*   @param: obj - data object 
	*   @param: label - (boolean) defines wherether label needs being drawn 
	*/
	_drawItem:function(ctx,x0,y0,obj,label,sIndex,map){
		var config = this._settings.item;

		var R = parseInt(config.radius.call(this,obj),10)||0;
		var mapStart = this._mapStart;
		if(R){
			ctx.save();
			if(config.shadow){
				ctx.lineWidth = 1;
				ctx.strokeStyle = "#bdbdbd";
				ctx.fillStyle = "#bdbdbd";
				var alphas = [0.1,0.2,0.3];
				for(var i=(alphas.length-1);i>=0;i--){
					ctx.globalAlpha = alphas[i];
					ctx.strokeStyle = "#d0d0d0";
					ctx.beginPath();
					this._strokeChartItem(ctx,x0,y0+2*R/3,R+i+1,config.type);
					ctx.stroke();
				}
				ctx.beginPath();
				ctx.globalAlpha = 0.3;
				ctx.fillStyle = "#bdbdbd";
				this._strokeChartItem(ctx,x0,y0+2*R/3,R+1,config.type);
				ctx.fill();
			}
			ctx.restore();
			
			if(config.type == "image" && config.src){
				this._drawImage(ctx,x0-R,y0-R,config.src,R*2, R*2);
			}
			else{
				ctx.lineWidth = config.borderWidth;
				ctx.fillStyle = config.color.call(this,obj);
				ctx.strokeStyle = config.borderColor.call(this,obj);
				ctx.globalAlpha = config.alpha.call(this,obj);
				ctx.beginPath();
				this._strokeChartItem(ctx,x0,y0,R+1,config.type);
				ctx.fill();
				ctx.stroke();
				ctx.globalAlpha = 1;
			}
		}
		/*item label*/
		if(label)
			this.canvases[sIndex].renderTextAt(false, true, x0,y0-R-this._settings.labelOffset,this._settings.label.call(this,obj));

		var areaPos = (this._settings.eventRadius||R+1);
		map.addRect(obj.id,[x0-areaPos-mapStart.x,y0-areaPos-mapStart.y,x0+areaPos-mapStart.x,y0+areaPos-mapStart.y],sIndex);
	},
	_drawImage: function(ctx,x,y,src, width, height){
		var image = document.createElement("img");
		image.style.display = "none";
		image.style.width = width+"px";
		image.style.height = height+"px";
		document.body.appendChild(image);
		image.src = src;
		var callback = function() {
			ctx.drawImage(image, x,y);
		};
		if(image.complete) { //check if image was already loaded by the browser
			callback(image);
		}else {
			image.onload = callback;
		}
	},
    _strokeChartItem:function(ctx,x0,y0,R,type){
	    var p=[];
	    x0 = parseInt(x0,10);
	    y0 = parseInt(y0,10);
        if(type && (type=="square" || type=="s")){
		    R *= Math.sqrt(2)/2;
	        p = [
		        [x0-R-ctx.lineWidth/2,y0-R],
		        [x0+R,y0-R],
		        [x0+R,y0+R],
		        [x0-R,y0+R],
		        [x0-R,y0-R]
	        ];
		}
        else if(type && (type=="diamond" || type=="d")){
		    var corr = (ctx.lineWidth>1?ctx.lineWidth*Math.sqrt(2)/4:0);
	        p = [
		        [x0,y0-R],
		        [x0+R,y0],
		        [x0,y0+R],
		        [x0-R,y0],
		        [x0+corr,y0-R-corr]
	        ];
        }
        else if(type && (type=="triangle" || type=="t")){
	        p = [
		        [x0,y0-R],
		        [x0+Math.sqrt(3)*R/2,y0+R/2],
		        [x0-Math.sqrt(3)*R/2,y0+R/2],
		        [x0,y0-R]
	        ];
        }
		else
            p = [
	            [x0,y0,R,0,Math.PI*2,true]
            ];
	    this._path(ctx,p);
    },
	/**
	*   gets the vertical position of the item
	*   @param: data - data object
	*   @param: y0 - the y position of chart start
	*   @param: y1 - the y position of chart end
	*   @param: params - the object with elements: minValue, maxValue, unit, valueFactor (the value multiple of 10) 
	*/
	_getPointY: function(data,point0,point1,params){
		var minValue = params.minValue;
		var maxValue = params.maxValue;
		var unit = params.unit;
		var valueFactor = params.valueFactor;
		/*the real value of an object*/
		var value = this._settings.value(data);
		/*a relative value*/
		var v = (parseFloat(value||0) - minValue)*valueFactor;
		if(!this._settings.yAxis)
			v += params.startValue/unit;
		/*a vertical coordinate*/
		var y = point1.y - unit*v;

		/*the limit of the max and min values*/
		if(this._settings.fixOverflow && ( this._settings.view == "line" || this._settings.view == "area")){
			if(value > maxValue)
				y = {y: point0.y, y0:  y, out: "max"};
			else if(v<0 || value < minValue)
				y = {y: point1.y, y0:  y, out: "min"};
		}
		else{
			if(value > maxValue)
				y =  point0.y;
			if(v<0 || value < minValue)
				y =  point1.y;
		}
		return y;
	},
	_calculateLineParams: function(ctx,data,point0,point1,sIndex){
		var params = {};
		
		/*maxValue - minValue*/
		var relValue;
		
		/*available height*/
		params.totalHeight = point1.y-point0.y;
		
		/*a space available for a single item*/
		//params.cellWidth = Math.round((point1.x-point0.x)/((!this._settings.offset&&this._settings.yAxis)?(data.length-1):data.length)); 
		params.cellWidth = (point1.x-point0.x)/((!this._settings.offset)?(data.length-1):data.length);
		
		/*scales*/
		var yax = !!this._settings.yAxis;
		
		var limits = (this._settings.view.indexOf("stacked")!=-1?this._getStackedLimits(data):this._getLimits());
		params.maxValue = limits.max;
		params.minValue = limits.min;
		
		/*draws x and y scales*/
		if(!sIndex)
			this._drawScales(data, point0, point1,params.minValue,params.maxValue,params.cellWidth);
		
		/*necessary for automatic scale*/
		if(yax){
		    params.maxValue = parseFloat(this._settings.yAxis.end);
			params.minValue = parseFloat(this._settings.yAxis.start);      
		}
		
		/*unit calculation (y_position = value*unit)*/
		var relativeValues = this._getRelativeValue(params.minValue,params.maxValue);
		relValue = relativeValues[0];
		params.valueFactor = relativeValues[1];
		params.unit = (relValue?params.totalHeight/relValue:10);
		
		params.startValue = 0;
		if(!yax){
			/*defines start value for better representation of small values*/
			params.startValue = 10;
			if(params.unit!=params.totalHeight)
				params.unit = (relValue?(params.totalHeight - params.startValue)/relValue:10);
		}
		return params;
	}
};



/* DHX DEPEND FROM FILE 'ext/chart/chart_bar.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.bar = {
	/**
	*   renders a bar chart
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: x - the width of the container
	*   @param: y - the height of the container
	*   @param: sIndex - index of drawing chart
	*/
	pvt_render_bar:function(ctx, data, point0, point1, sIndex, map){
	    var barWidth, cellWidth,
		    i,
		    limits, maxValue, minValue,
		    relValue, valueFactor, relativeValues,
		    startValue, unit,
		    xax, yax,
		    totalHeight = point1.y-point0.y;


		

		
		yax = !!this._settings.yAxis;
		xax = !!this._settings.xAxis;
		
		limits = this._getLimits();
		maxValue = limits.max;
		minValue = limits.min;
		
		/*an available width for one bar*/
		cellWidth = (point1.x-point0.x)/data.length;
		
		/*draws x and y scales*/
		if(!sIndex&&!(this._settings.origin!="auto"&&!yax)){
			this._drawScales(data,point0, point1,minValue,maxValue,cellWidth);
		}
		
		/*necessary for automatic scale*/
		if(yax){
		    maxValue = parseFloat(this._settings.yAxis.end);
			minValue = parseFloat(this._settings.yAxis.start);      
		}
		
		/*unit calculation (bar_height = value*unit)*/
		relativeValues = this._getRelativeValue(minValue,maxValue);
		relValue = relativeValues[0];
		valueFactor = relativeValues[1];
		
		unit = (relValue?totalHeight/relValue:relValue);

		if(!yax&&!(this._settings.origin!="auto"&&xax)){
			/*defines start value for better representation of small values*/
			startValue = 10;
			unit = (relValue?(totalHeight-startValue)/relValue:startValue);
		}
		/*if yAxis isn't set, but with custom origin */
		if(!sIndex&&(this._settings.origin!="auto"&&!yax)&&this._settings.origin>minValue){
			this._drawXAxis(ctx,data,point0,point1,cellWidth,point1.y-unit*(this._settings.origin-minValue));
		}
		
		/*a real bar width */
		barWidth = parseInt(this._settings.width,10);
		var seriesNumber = 0;
		var seriesIndex = 0;
		for(i=0; i<this._series.length; i++ ){
			if(i == sIndex){
				seriesIndex  = seriesNumber;
			}
			if(this._series[i].view == "bar")
				seriesNumber++;
		}

		var seriesMargin = this._settings.seriesMargin;
		var seriesPadding = this._settings.seriesPadding;

		if(this._series&&(barWidth*seriesNumber+seriesPadding + (seriesNumber>2?seriesMargin*seriesNumber:0)>cellWidth) )
			barWidth = cellWidth/seriesNumber-seriesPadding-(seriesNumber>2?seriesMargin:0);

		/*the half of distance between bars*/
		var barOffset = (cellWidth - barWidth*seriesNumber - seriesMargin*(seriesNumber-1))/2 ;

		if(this._settings.border){
			barWidth = parseInt(barWidth,10);
			barOffset = parseInt(barOffset,10);
		}

		/*the radius of rounding in the top part of each bar*/
		var radius = (typeof this._settings.radius!="undefined"?parseInt(this._settings.radius,10):Math.round(barWidth/5));

		var inner_gradient = false;
		var gradient = this._settings.gradient;
		
		if(gradient && typeof(gradient) != "function"){
			inner_gradient = gradient;
			gradient = false;
		} else if (gradient){
			gradient = ctx.createLinearGradient(0,point1.y,0,point0.y);
			this._settings.gradient(gradient);
		}
		/*draws a black line if the horizontal scale isn't defined*/
		if(!xax){
			this._drawLine(ctx,point0.x,point1.y+0.5,point1.x,point1.y+0.5,"#000000",1); //hardcoded color!
		}
		
		for(i=0; i < data.length;i ++){

			var value =  parseFloat(this._settings.value(data[i])||0);
			if(isNaN(value))
				continue;
			if(value>maxValue) value = maxValue;
			value -= minValue;
			value *= valueFactor;
			
			/*start point (bottom left)*/
			var x0 = point0.x + barOffset+(seriesNumber>2?seriesMargin*seriesIndex:0) + i*cellWidth + barWidth*seriesIndex;
			var y0 = point1.y;
		
			if(value<0||(this._settings.yAxis&&value===0&&!(this._settings.origin!="auto"&&this._settings.origin>minValue))){
				this.canvases[sIndex].renderTextAt(true, true, x0+Math.floor(barWidth/2),y0,this._settings.label(data[i]));
				continue;
			}
			
			/*takes start value into consideration*/
			if(!yax&&!(this._settings.origin!="auto"&&xax)) value += startValue/unit;
			
			var color = gradient||this._settings.color.call(this,data[i]);
	
			
			/*drawing bar body*/
			ctx.globalAlpha = this._settings.alpha.call(this,data[i]);
			var points = this._drawBar(ctx,point0,x0,y0,barWidth,minValue,radius,unit,value,color,gradient,inner_gradient);
			if (inner_gradient){
				this._drawBarGradient(ctx,x0,y0,barWidth,minValue,radius,unit,value,color,inner_gradient);
			}
			/*drawing the gradient border of a bar*/
			if(this._settings.border)
				this._drawBarBorder(ctx,x0,y0,barWidth,minValue,radius,unit,value,color);
			
			ctx.globalAlpha = 1;
			
			/*sets a bar label*/
			if(points[0]!=x0)
				this.canvases[sIndex].renderTextAt(false, true, x0+Math.floor(barWidth/2),points[1],this._settings.label(data[i]));
			else
				this.canvases[sIndex].renderTextAt(true, true, x0+Math.floor(barWidth/2),points[3],this._settings.label(data[i]));
			/*defines a map area for a bar*/
			map.addRect(data[i].id,[x0-point0.x,points[3]-point0.y,points[2]-point0.x,points[1]-point0.y],sIndex);
			//this._addMapRect(map,data[i].id,[{x:x0,y:points[3]},{x:points[2],y:points[1]}],point0,sIndex);
		}
	},
	_correctBarParams:function(ctx,x,y,value,unit,barWidth,minValue){
		var xax = this._settings.xAxis;
		var axisStart = y;
		if(!!xax&&this._settings.origin!="auto" && (this._settings.origin>minValue)){
			y -= (this._settings.origin-minValue)*unit;
			axisStart = y;
			value = value-(this._settings.origin-minValue);
			if(value < 0){
				value *= (-1);
			 	ctx.translate(x+barWidth,y);
				ctx.rotate(Math.PI);
				x = 0;
				y = 0;
			}
			y -= 0.5;
		}
		
		return {value:value,x0:x,y0:y,start:axisStart}
	},
	_drawBar:function(ctx,point0,x0,y0,barWidth,minValue,radius,unit,value,color,gradient,inner_gradient){
		var points;
		ctx.save();
		ctx.fillStyle = color;
		var p = this._correctBarParams(ctx,x0,y0,value,unit,barWidth,minValue);
		if( unit*p.value > 0)
			points = this._setBarPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,(this._settings.border?1:0));
		else
			points = [p.x0,p.y0];
		if (gradient&&!inner_gradient) ctx.lineTo(p.x0+(this._settings.border?1:0),point0.y); //fix gradient sphreading

   		ctx.fill();

	    ctx.restore();
		var x1 = p.x0;
		var x2 = (p.x0!=x0?x0+points[0]:points[0]);
		var y1 = (p.x0!=x0?(p.start-points[1]-p.y0):p.y0);
		var y2 = (p.x0!=x0?p.start-p.y0:points[1]);

		return [x1,y1,x2,y2];
	},

	_drawBarBorder:function(ctx,x0,y0,barWidth,minValue,radius,unit,value,color){
	    var p;
		ctx.save();
		p = this._correctBarParams(ctx,x0,y0,value,unit,barWidth,minValue);
		this._setBorderStyles(ctx,color);
		if( unit*p.value > 0)
			this._setBarPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,ctx.lineWidth/2,1);
		ctx.stroke();
		/*ctx.fillStyle = color;
		this._setBarPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,0);
		ctx.lineTo(p.x0,0);
		ctx.fill()
	   
				
		ctx.fillStyle = "#000000";
		ctx.globalAlpha = 0.37;
		
		this._setBarPoints(ctx,p.x0,p.y0,barWidth,radius,unit,p.value,0);
		ctx.fill()
		*/
	    ctx.restore();
	},
	_drawBarGradient:function(ctx,x0,y0,barWidth,minValue,radius,unit,value,color,inner_gradient){
		ctx.save();
		//y0 -= (dhtmlx._isIE?0:0.5);
		var p = this._correctBarParams(ctx,x0,y0,value,unit,barWidth,minValue);
		var gradParam = this._setBarGradient(ctx,p.x0,p.y0,p.x0+barWidth,p.y0-unit*p.value+2,inner_gradient,color,"y");
		var borderOffset = this._settings.border?1:0;
		ctx.fillStyle = gradParam.gradient;
		if( unit*p.value > 0)
			this._setBarPoints(ctx,p.x0+gradParam.offset,p.y0,barWidth-gradParam.offset*2,radius,unit,p.value,gradParam.offset+borderOffset);
		ctx.fill();
	    ctx.restore();
	},
	/**
	*   sets points for bar and returns the position of the bottom right point
	*   @param: ctx - canvas object
	*   @param: x0 - the x position of start point
	*   @param: y0 - the y position of start point
	*   @param: barWidth - bar width 
	*   @param: radius - the rounding radius of the top
	*   @param: unit - the value defines the correspondence between item value and bar height
	*   @param: value - item value
	*   @param: offset - the offset from expected bar edge (necessary for drawing border)
	*/
	_setBarPoints:function(ctx,x0,y0,barWidth,radius,unit,value,offset,skipBottom){
		/*correction for displaing small values (when rounding radius is bigger than bar height)*/
		ctx.beginPath();
		//y0 = 0.5;
		var angle_corr = 0;
		if(radius>unit*value){
			var cosA = (radius-unit*value)/radius;
			if(cosA<=1&&cosA>=-1)
				angle_corr = -Math.acos(cosA)+Math.PI/2;
		}
		/*start*/
		ctx.moveTo(x0+offset,y0);
		/*start of left rounding*/
		var y1 = y0 - Math.floor(unit*value) + radius + (radius?0:offset);
		if(radius<unit*value)
			ctx.lineTo(x0+offset,y1);
   		/*left rounding*/
		var x2 = x0 + radius;
		if (radius&&radius>0)
			ctx.arc(x2,y1,radius-offset,-Math.PI+angle_corr,-Math.PI/2,false);
   		/*start of right rounding*/
		var x3 = x0 + barWidth - radius - offset;
		var y3 = y1 - radius + (radius?offset:0);
		ctx.lineTo(x3,y3);
		/*right rounding*/
		if (radius&&radius>0)
			ctx.arc(x3,y1,radius-offset,-Math.PI/2,0-angle_corr,false);
   		/*bottom right point*/
		var x5 = x0 + barWidth-offset;
        ctx.lineTo(x5,y0);
		/*line to the start point*/
		if(!skipBottom){
   			ctx.lineTo(x0+offset,y0);
		}
   	//	ctx.lineTo(x0,0); //IE fix!
		return [x5,y3];
	}
};


/* DHX DEPEND FROM FILE 'ext/chart/chart_pie.js'*/


/*DHX:Depend ext/chart/chart_base.js*/
/*DHX:Depend ext/chart/chart_base.js*/
dhtmlx.chart.pie = {
	pvt_render_pie:function(ctx,data,x,y,sIndex,map){
		this._renderPie(ctx,data,x,y,1,map,sIndex);

	},
	/**
	 *   renders a pie chart
	 *   @param: ctx - canvas object
	 *   @param: data - object those need to be displayed
	 *   @param: x - the width of the container
	 *   @param: y - the height of the container
	 *   @param: ky - value from 0 to 1 that defines an angle of inclination (0<ky<1 - 3D chart)
	 */
	_renderPie:function(ctx,data,point0,point1,ky,map,sIndex){
		if(!data.length)
			return;
		var coord = this._getPieParameters(point0,point1);
		/*pie radius*/
		var radius = (this._settings.radius?this._settings.radius:coord.radius);
		if(radius<0)
			return;

		/*real values*/
		var values = this._getValues(data);

		var totalValue = this._getTotalValue(values);

		/*weighed values (the ratio of object value to total value)*/
		var ratios = this._getRatios(values,totalValue);

		/*pie center*/
		var x0 = (this._settings.x?this._settings.x:coord.x);
		var y0 = (this._settings.y?this._settings.y:coord.y);
		/*adds shadow to the 2D pie*/
		if(ky==1&&this._settings.shadow)
			this._addShadow(ctx,x0,y0,radius);

		/*changes vertical position of the center according to 3Dpie cant*/
		y0 = y0/ky;
		/*the angle defines the 1st edge of the sector*/
		var alpha0 = -Math.PI/2;
		var alpha1;
		var angles = [];
		/*changes Canvas vertical scale*/
		ctx.scale(1,ky);
		/*adds radial gradient to a pie*/
		if (this._settings.gradient){
			var x1 = (ky!=1?x0+radius/3:x0);
			var y1 = (ky!=1?y0+radius/3:y0);
			this._showRadialGradient(ctx,x0,y0,radius,x1,y1);
		}

		if(this._settings.labelLines)
			this._labelMargins = this._getLabelMargins(ratios,radius);


		for(var i = 0; i < data.length;i++){
			if (!values[i]) continue;
			/*drawing sector*/
			//ctx.lineWidth = 2;
			ctx.strokeStyle = (data.length==1?this._settings.color.call(this,data[i]):this._settings.lineColor.call(this,data[i]));
			ctx.beginPath();
			ctx.moveTo(x0,y0);
			angles.push(alpha0);
			/*the angle defines the 2nd edge of the sector*/
			alpha1 = -Math.PI/2+ratios[i]-0.0001;
			ctx.arc(x0,y0,radius,alpha0,alpha1,false);
			ctx.lineTo(x0,y0);

			var color = this._settings.color.call(this,data[i]);
			ctx.fillStyle = color;
			ctx.fill();

			/*text that needs being displayed inside the sector*/
			if(this._settings.pieInnerText)
				this._drawSectorLabel(x0,y0,5*radius/6,alpha0,alpha1,ky,this._settings.pieInnerText(data[i],totalValue),true);
			/*label outside the sector*/

			if(this._settings.label){
				this._drawSectorLabel(x0,y0,radius,alpha0,alpha1,ky,this._settings.label(data[i]),0,(this._labelMargins?this._labelMargins[i]:{}),ctx);
			}

			/*drawing lower part for 3D pie*/
			if(ky!=1){
				this._createLowerSector(ctx,x0,y0,alpha0,alpha1,radius,true);
				ctx.fillStyle = "#000000";
				ctx.globalAlpha = 0.2;
				this._createLowerSector(ctx,x0,y0,alpha0,alpha1,radius,false);
				ctx.globalAlpha = 1;
				ctx.fillStyle = color;
			}
			/*creats map area (needed for events)*/
			map.addSector(data[i].id,alpha0,alpha1,x0-point0.x,y0-point0.y/ky,radius,ky,sIndex);

			alpha0 = alpha1;
		}
		/*renders radius lines and labels*/
		ctx.globalAlpha = 0.8;
		var p;
		if(angles.length>1)
			for(i=0;i< angles.length;i++){
				p = this._getPositionByAngle(angles[i],x0,y0,radius);
				this._drawLine(ctx,x0,y0,p.x,p.y,this._settings.lineColor.call(this,data[i]),2);
			}

		if(ky==1){
			ctx.lineWidth = 2;
			ctx.strokeStyle = "#ffffff";
			ctx.beginPath();
			ctx.arc(x0,y0,radius+1,0,2*Math.PI,false);
			ctx.stroke();
		}
		ctx.globalAlpha =1;

		ctx.scale(1,1/ky);
	},
	_getLabelMargins: function(ratios,R){
		var alpha1, alpha2, i, j,
			margins = [],
			r = [];
		var dists = {1:[0]};


		for(i = 1; i < ratios.length; i++ ){
			alpha1 = -Math.PI/2  + (i>1?(ratios[i-1]- (ratios[i-1]-ratios[i-2])/2):ratios[i-1]/2);
			alpha2 = -Math.PI/2 + ratios[i]- (ratios[i]-ratios[i-1])/2;
			var cos2 = Math.cos(alpha2);
			var sin2 = Math.sin(alpha2);
			var cos1 = Math.cos(alpha1);
			var sin1 = Math.sin(alpha1);
			var dist = Math.round((R+8)*Math.abs(Math.sin(alpha2)-Math.sin(alpha1)));

			var quarter2 = (cos2<0?(sin2<0?4:3) :(sin2<0?1:2));
			var quarter1 = (cos1<0?(sin1<0?4:3) :(sin1<0?1:2));
			if(!dists[quarter2])
				dists[quarter2] = [];
			dists[quarter2].push(quarter1==quarter2?dist:0);
		}
		var distIncrease = [];
		var c = 0;

		for(var q in dists){
			var start = 0;
			var end = dists[q].length;
			var inc = 0;
			var incX = 0;
			if(q == 1 || q == 3){
				j = q-1;
				var len = 0;
				while(j>0){
					if(dists[j])
						len += dists[j].length;
					j--;
				}
				distIncrease[len+ dists[q].length-1] = {y:0,x:0};

				var j = dists[q].length-2;

				while(j>=0){
					if((inc||j) && dists[q][j+1]-inc<18){
						inc += 18 -dists[q][j+1];
					}
					else{
						inc = 0;
					}

					distIncrease[len+j] = {y:inc*(q == 1?-1:1)};
					j --;
				}
				for(var k=distIncrease.length-dists[q].length; k < distIncrease.length; k++){
					if(distIncrease[k]["y"]!=0){
						incX += 6;
						distIncrease[k]["x"] = incX;

					}
					else{
						distIncrease[k]["x"] = 0;
						incX = 0;
					}
				}
			}
			else{
				var j = 1;
				distIncrease.push({y:0,x:0});
				while(j<dists[q].length){
					if(dists[q][j]-inc<18){
						inc += 18 - dists[q][j];
					}
					else{
						inc = 0;
					}
					distIncrease.push({y:inc*(q == 4?-1:1)});
					j ++;
				}
				for(var k=distIncrease.length-1; k >=  distIncrease.length-dists[q].length; k--){
					if(distIncrease[k]["y"]!=0){
						incX += 8;
						distIncrease[k]["x"] = incX;

					}
					else{
						incX = 0;
						distIncrease[k]["x"] = 0;
					}

				}
			}
		}

		return distIncrease;
	},
	/**
	 *   returns list of values
	 *   @param: data array
	 */
	_getValues:function(data){
		var v = [];
		for(var i = 0; i < data.length;i++)
			v.push(parseFloat(this._settings.value(data[i])||0));
		return v;
	},
	/**
	 *   returns total value
	 *   @param: the array of values
	 */
	_getTotalValue:function(values){
		var t=0;
		for(var i = 0; i < values.length;i++)
			t += values[i];
		return  t;
	},
	/**
	 *   gets angles for all values
	 *   @param: the array of values
	 *   @param: total value (optional)
	 */
	_getRatios:function(values,totalValue){
		var value;
		var ratios = [];
		var prevSum = 0;
		totalValue = totalValue||this._getTotalValue(values);
		for(var i = 0; i < values.length;i++){
			value = values[i];

			ratios[i] = Math.PI*2*(totalValue?((value+prevSum)/totalValue):(1/values.length));
			prevSum += value;
		}
		return ratios;
	},
	/**
	 *   returns calculated pie parameters: center position and radius
	 *   @param: x - the width of a container
	 *   @param: y - the height of a container
	 */
	_getPieParameters:function(point0,point1){
		/*var offsetX = 0;
		 var offsetY = 0;
		 if(this._settings.legend &&this._settings.legend.layout!="x")
		 offsetX = this._settings.legend.width*(this._settings.legend.align=="right"?-1:1);
		 var x0 = (x + offsetX)/2;
		 if(this._settings.legend &&this._settings.legend.layout=="x")
		 offsetY = this._settings.legend.height*(this._settings.legend.valign=="bottom"?-1:1);
		 var y0 = (y+offsetY)/2;*/
		var width = point1.x-point0.x;
		var height = point1.y-point0.y;
		var x0 = point0.x+width/2;
		var y0 = point0.y+height/2;
		var radius = Math.min(width/2,height/2);
		return {"x":x0,"y":y0,"radius":radius};
	},
	/**
	 *   creates lower part of sector in 3Dpie
	 *   @param: ctx - canvas object
	 *   @param: x0 - the horizontal position of the pie center
	 *   @param: y0 - the vertical position of the pie center
	 *   @param: a0 - the angle that defines the first edge of a sector
	 *   @param: a1 - the angle that defines the second edge of a sector
	 *   @param: R - pie radius
	 *   @param: line (boolean) - if the sector needs a border
	 */
	_createLowerSector:function(ctx,x0,y0,a1,a2,R,line){
		ctx.lineWidth = 1;
		/*checks if the lower sector needs being displayed*/
		if(!((a1<=0 && a2>=0)||(a1>=0 && a2<=Math.PI)||(Math.abs(a1-Math.PI)>0.003&&a1<=Math.PI && a2>=Math.PI))) return;

		if(a1<=0 && a2>=0){
			a1 = 0;
			line = false;
			this._drawSectorLine(ctx,x0,y0,R,a1,a2);
		}
		if(a1<=Math.PI && a2>=Math.PI){
			a2 = Math.PI;
			line = false;
			this._drawSectorLine(ctx,x0,y0,R,a1,a2);
		}
		/*the height of 3D pie*/
		var offset = (this._settings.height||Math.floor(R/4))/this._settings.cant;
		ctx.beginPath();
		ctx.arc(x0,y0,R,a1,a2,false);
		ctx.lineTo(x0+R*Math.cos(a2),y0+R*Math.sin(a2)+offset);
		ctx.arc(x0,y0+offset,R,a2,a1,true);
		ctx.lineTo(x0+R*Math.cos(a1),y0+R*Math.sin(a1));
		ctx.fill();
		if(line)
			ctx.stroke();
	},
	/**
	 *   draws a serctor arc
	 */
	_drawSectorLine:function(ctx,x0,y0,R,a1,a2){
		ctx.beginPath();
		ctx.arc(x0,y0,R,a1,a2,false);
		ctx.stroke();
	},
	/**
	 *   adds a shadow to pie
	 *   @param: ctx - canvas object
	 *   @param: x - the horizontal position of the pie center
	 *   @param: y - the vertical position of the pie center
	 *   @param: R - pie radius
	 */
	_addShadow:function(ctx,x,y,R){
		ctx.globalAlpha = 0.5;
		var shadows = ["#c4c4c4","#c6c6c6","#cacaca","#dcdcdc","#dddddd","#e0e0e0","#eeeeee","#f5f5f5","#f8f8f8"];
		for(var i = shadows.length-1;i>-1;i--){
			ctx.beginPath();
			ctx.fillStyle = shadows[i];
			ctx.arc(x+1,y+1,R+i,0,Math.PI*2,true);
			ctx.fill();
		}
		ctx.globalAlpha = 1
	},
	/**
	 *   returns a gray gradient
	 *   @param: gradient - gradient object
	 */
	_getGrayGradient:function(gradient){
		gradient.addColorStop(0.0,"#ffffff");
		gradient.addColorStop(0.7,"#7a7a7a");
		gradient.addColorStop(1.0,"#000000");
		return gradient;
	},
	/**
	 *   adds gray radial gradient
	 *   @param: ctx - canvas object
	 *   @param: x - the horizontal position of the pie center
	 *   @param: y - the vertical position of the pie center
	 *   @param: radius - pie radius
	 *   @param: x0 - the horizontal position of a gradient center
	 *   @param: y0 - the vertical position of a gradient center
	 */
	_showRadialGradient:function(ctx,x,y,radius,x0,y0){
		//ctx.globalAlpha = 0.3;
		ctx.beginPath();
		var gradient;
		if(typeof this._settings.gradient!= "function"){
			gradient = ctx.createRadialGradient(x0,y0,radius/4,x,y,radius);
			gradient = this._getGrayGradient(gradient);
		}
		else gradient = this._settings.gradient(gradient);
		ctx.fillStyle = gradient;
		ctx.arc(x,y,radius,0,Math.PI*2,true);
		ctx.fill();
		//ctx.globalAlpha = 1;
		ctx.globalAlpha = 0.7;
	},
	/**
	 *   returns the calculates pie parameters: center position and radius
	 *   @param: ctx - canvas object
	 *   @param: x0 - the horizontal position of the pie center
	 *   @param: y0 - the vertical position of the pie center
	 *   @param: R - pie radius
	 *   @param: alpha1 - the angle that defines the 1st edge of a sector
	 *   @param: alpha2 - the angle that defines the 2nd edge of a sector
	 *   @param: ky - the value that defines an angle of inclination
	 *   @param: text - label text
	 *   @param: in_width (boolean) - if label needs being displayed inside a pie
	 */
	_drawSectorLabel:function(x0,y0,R,alpha1,alpha2,ky,text,in_width, margin,ctx){
		var t = this.canvases[0].renderText(0,0,text,0,1);
		if (!t) return;
		//get existing width of text
		var labelWidth = t.scrollWidth;
		t.style.width = labelWidth+"px";	//adjust text label to fit all text
		if (labelWidth>x0) labelWidth = x0;	//the text can't be greater than half of view

		//calculate expected correction based on default font metrics
		var width = (alpha2-alpha1<0.2?4:8);
		if (in_width) width = labelWidth/1.8;
		var alpha = alpha1+(alpha2-alpha1)/2;

		//position and its correction
		var radius = R;
		R = (in_width?5*R/6:R+this._settings.labelOffset);
		R = R-(width-8)/2;
		var corr_x = - width;
		var corr_y = -8;
		var align = "right";

		//for items in left upper and lower sector
		if(alpha>=Math.PI/2 && alpha<Math.PI || alpha<=3*Math.PI/2 && alpha>=Math.PI){
			corr_x = -labelWidth-corr_x;/*correction for label width*/
			align = "left";
		}

		//calculate position of text
		//basically get point at center of pie sector
		var offset = 0;

		if(!in_width&&ky<1&&(alpha>0&&alpha<Math.PI))
			offset = (this._settings.height||Math.floor(R/4))/ky;


		var y = (y0+Math.floor(R+offset)*Math.sin(alpha))*ky+corr_y;
		var x = x0+Math.floor(R+width/2)*Math.cos(alpha)+corr_x;




		//if pie sector starts in left of right part pie, related text
		//must be placed to the left of to the right of pie as well
		var left_end = (alpha2 < Math.PI/2+0.01);
		var left_start = (alpha1 < Math.PI/2);

		if (left_start && left_end){
			x = Math.max(x,x0+3);	//right part of pie
			/*if(alpha2-alpha1<0.2)
			 x = x0;*/
		}

		else if (!left_start && !left_end){

			x = Math.min(x,x0-labelWidth);	//left part of pie
		}else if (!in_width&&!this._settings.labelLines&&(alpha>=Math.PI/2 && alpha<Math.PI || alpha<=3*Math.PI/2 && alpha>=Math.PI)){

			x += labelWidth/3;
		}


		if(this._settings.labelLines && !in_width){
			var r1 = Math.abs((Math.abs(margin||0) +Math.abs(radius*Math.sin(alpha)))/Math.sin(alpha));

			if(margin.y)
				y += margin.y;
			if(align=="left"){
				x -= margin.x;
			}
			else
				x +=margin.x;

			ctx.beginPath();
			ctx.strokeStyle = "#555";
			var x1 = x0+radius*Math.cos(alpha);
			var y1 = y0+radius*Math.sin(alpha);
			ctx.moveTo(x1,y1);

			var x2= x-(align=="left"?corr_x-8:2);
			var y2 = y;

			if(align=="left" && x2>x1){
				x2 = x1-Math.abs(y2-y1+16)/Math.tan(alpha-Math.PI);
				y2 = y2+16;
				if(alpha<Math.PI)
					y2 -= 8;
			}
			else
				y2 += 8;
			ctx.lineTo(x2,y2);

			ctx.lineTo(x2 + (align=="left"?-5:5),y2);
			ctx.stroke();

			y = y2 - 8;
			x = x2 + corr_x + (align=="left"?-15:15);
		}



		//we need to set position of text manually, based on above calculations
		t.style.top  = y+"px";
		t.style.left = x + "px";
		t.style.width = labelWidth+"px";
		t.style.textAlign = align;
		t.style.whiteSpace = "nowrap";
	}
};

dhtmlx.chart.pie3D = {
	pvt_render_pie3D:function(ctx,data,x,y,sIndex,map){
		this._renderPie(ctx,data,x,y,this._settings.cant,map);
	}
};
dhtmlx.chart.donut = {
	pvt_render_donut:function(ctx,data,point0,point1,sIndex,map){
		if(!data.length)
			return;
		this._renderPie(ctx,data,point0,point1,1,map);
		var config = this._settings;
		var coord = this._getPieParameters(point0,point1);
		var pieRadius = (config.radius?config.radius:coord.radius);
		var innerRadius = ((config.innerRadius&&(config.innerRadius<pieRadius))?config.innerRadius:pieRadius/3);
		var x0 = (config.x?config.x:coord.x);
		var y0 = (config.y?config.y:coord.y);
		ctx.fillStyle = "#ffffff";
		ctx.beginPath();
		ctx.arc(x0,y0,innerRadius,0,Math.PI*2,true);
		ctx.fill();
	}
};



/* DHX DEPEND FROM FILE 'compatibility_grid.js'*/


/*
	Compatibility hack for loading data from the grid.
	Provides new type of datasource - dhtmlxgrid
	
*/

/*DHX:Depend load.js*/

dhtmlx.DataDriver.dhtmlxgrid={
	_grid_getter:"_get_cell_value",
	toObject:function(data){
		this._grid = data;
		return data;
	},
	getRecords:function(data){
		return data.rowsBuffer;
	},
	getDetails:function(data){
		var result = {};
		for (var i=0; i < this._grid.getColumnsNum(); i++)
			result["data"+i]=this._grid[this._grid_getter](data,i);
      
		return result;
	},
	getInfo:function(data){
		return { 
			_size:0,
			_from:0
		};
	}
};


/* DHX DEPEND FROM FILE 'canvas.js'*/


/*DHX:Depend thirdparty/excanvas*/
/*DHX:Depend dhtmlx.js*/

dhtmlx.ui.Canvas = function(container,name,style) {
	this._canvas_labels = [];
	this._canvas_name =  name;
	this._obj = container;
	var width = container.offsetWidth*(window.devicePixelRatio||1);
	var height = container.offsetHeight*(window.devicePixelRatio||1);
	var style = style||"";
	style += ";width:"+container.offsetWidth+"px;height:"+container.offsetHeight+"px;";
	this._prepareCanvas(name, style ,width, height);
};
dhtmlx.ui.Canvas.prototype = {
	_prepareCanvas:function (name,style,width,height){
		//canvas has the same size as master object
		this._canvas = dhtmlx.html.create("canvas",{ width:width, height:height, canvas_id:name, style:(style||"") });
		this._obj.appendChild(this._canvas);
		//use excanvas in IE
		if (!this._canvas.getContext){
			if (dhtmlx._isIE){
				dhtmlx.require("thirdparty/excanvas/excanvas.js");	//sync loading
				G_vmlCanvasManager.init_(document);
				G_vmlCanvasManager.initElement(this._canvas);
			} else	//some other not supported browser
				dhtmlx.error("Canvas is not supported in the current browser");
		}
		return this._canvas;
	}, 
	getCanvas:function(context){
		var ctx = (this._canvas||this._prepareCanvas()).getContext(context||"2d");
		if(!this._dhtmlxDevicePixelRatio){
			this._dhtmlxDevicePixelRatio = true;
			ctx.scale(window.devicePixelRatio||1, window.devicePixelRatio||1);
		}
		return ctx;
	},
	_resizeCanvas:function(){
		if (this._canvas){
			var w = this._canvas.parentNode.offsetWidth;
			var h = this._canvas.parentNode.offsetHeight;
			this._canvas.setAttribute("width", w*(window.devicePixelRatio||1));
			this._canvas.setAttribute("height", h*(window.devicePixelRatio||1));
			this._canvas.style.width = w+"px";
			this._canvas.style.height = h+"px";
			this._dhtmlxDevicePixelRatio = false;
		}
	},
	renderText:function(x,y,text,css,w){
		if (!text) return; //ignore empty text
		
		var t = dhtmlx.html.create("DIV",{
			"class":"dhx_canvas_text"+(css?(" "+css):""),
			"style":"left:"+x+"px; top:"+y+"px;"
		},text);
		this._obj.appendChild(t);
		this._canvas_labels.push(t); //destructor?
		if (w)
			t.style.width = w+"px";
		return t;
	},
	renderTextAt:function(valign,align, x,y,t,c,w){
		var text=this.renderText.call(this,x,y,t,c,w);
		if (text){
			if (valign){
				if(valign == "middle")
					text.style.top = parseInt(y-text.offsetHeight/2,10) + "px";
				else
					text.style.top = y-text.offsetHeight + "px";
			}
			if (align){
			    if(align == "left")
					text.style.left = x-text.offsetWidth + "px";
				else
					text.style.left = parseInt(x-text.offsetWidth/2,10) + "px";
			}
		}
		return text;
	},
	clearCanvas:function(skipMap){
		var areas=[], i;

		for(i=0; i < this._canvas_labels.length;i++)
			this._obj.removeChild(this._canvas_labels[i]);
		this._canvas_labels = [];

		if (!skipMap&&this._obj._htmlmap){

			//areas that correspond this canvas layer
		    areas = this._getMapAreas();

			//removes areas of this canvas
			while(areas.length){
                areas[0].parentNode.removeChild(areas[0]);
				areas.splice(0,1);
			}
			areas = null;

			//removes _htmlmap object if all its child nodes are removed
			if(!this._obj._htmlmap.getElementsByTagName("AREA").length){
				this._obj._htmlmap.parentNode.removeChild(this._obj._htmlmap);
				this._obj._htmlmap = null;
			}

		}
		//FF breaks, when we are using clear canvas and call clearRect without parameters	
		this.getCanvas().clearRect(0,0,this._canvas.width, this._canvas.height);
	},
	toggleCanvas:function(){
		this._toggleCanvas(this._canvas.style.display=="none")

	},
	showCanvas:function(){
		this._toggleCanvas(true);
	},
	hideCanvas:function(){
		this._toggleCanvas(false);
	},
	_toggleCanvas:function(show){
		var areas, i;

		for(i=0; i < this._canvas_labels.length;i++)
			this._canvas_labels[i].style.display = (show?"":"none");

		if (this._obj._htmlmap){
			areas = this._getMapAreas();
			for( i = 0; i < areas.length; i++){
				if(show)
					areas[i].removeAttribute("disabled");
				else
					areas[i].setAttribute("disabled","true");
			}
		}
		//FF breaks, when we are using clear canvas and call clearRect without parameters
		this._canvas.style.display = (show?"":"none");
	},
	_getMapAreas:function(){
		var res = [], areas, i;

		areas = this._obj._htmlmap.getElementsByTagName("AREA");

		for(i = 0; i < areas.length; i++){
			if(areas[i].getAttribute("userdata") == this._canvas_name){
				res.push(areas[i]);
			}
		}

		return res;
	}
};

/* DHX INITIAL FILE 'C:\http\legacy/dhtmlxCore/sources//chart.js'*/


/*DHX:Depend chart.css*/
/*DHX:Depend canvas.js*/
/*DHX:Depend load.js*/

/*DHX:Depend compatibility_grid.js*/
/*DHX:Depend compatibility_layout.js*/

/*DHX:Depend config.js*/
/*DHX:Depend destructor.js*/
/*DHX:Depend mouse.js*/
/*DHX:Depend key.js*/
/*DHX:Depend group.js*/
/*DHX:Depend autotooltip.js*/

/*DHX:Depend ext/chart/chart_base.js*/
/*DHX:Depend ext/chart/chart_pie.js*/		//+pie3d
/*DHX:Depend ext/chart/chart_bar.js*/	
/*DHX:Depend ext/chart/chart_line.js*/
/*DHX:Depend ext/chart/chart_barh.js*/	
/*DHX:Depend ext/chart/chart_stackedbar.js*/	
/*DHX:Depend ext/chart/chart_stackedbarh.js*/
/*DHX:Depend ext/chart/chart_spline.js*/	
/*DHX:Depend ext/chart/chart_area.js*/	 	//+stackedArea
/*DHX:Depend ext/chart/chart_radar.js*/	 	
/*DHX:Depend ext/chart/chart_scatter.js*/
/*DHX:Depend ext/chart/presets.js*/
/*DHX:Depend math.js*/
/*DHX:Depend destructor.js*/
/*DHX:Depend dhtmlx.js*/
/*DHX:Depend date.js*/

dhtmlXChart = function(container){
	this.name = "Chart";	
	
	if (dhtmlx.assert_enabled()) this._assert();
	
	dhtmlx.extend(this, dhtmlx.Settings);
	
	this._parseContainer(container,"dhx_chart");
	
	dhtmlx.extend(this, dhtmlx.AtomDataLoader);
	dhtmlx.extend(this, dhtmlx.DataLoader);
	this.data.provideApi(this,true);
	
	dhtmlx.extend(this, dhtmlx.EventSystem);
	dhtmlx.extend(this, dhtmlx.MouseEvents);
	dhtmlx.destructors.push(this);
	//dhtmlx.extend(this, dhtmlx.Destruction);
	//dhtmlx.extend(this, dhtmlx.Canvas);
	dhtmlx.extend(this, dhtmlx.Group);
	dhtmlx.extend(this, dhtmlx.AutoTooltip);
	
	for (var key in dhtmlx.chart)
		dhtmlx.extend(this, dhtmlx.chart[key]);


    if(container.preset){
        this.definePreset(container);
    }
	this._parseSettings(container,this.defaults);
	this._series = [this._settings];
	this.data.attachEvent("onStoreUpdated",dhtmlx.bind(function(){
		this.render();  
	},this));
	this.attachEvent("onLocateData", this._switchSerie);
};
dhtmlXChart.prototype={
	_id:"dhx_area_id",
	on_click: {
		dhx_chart_legend_item: function(e,id,obj){
			var series = obj.getAttribute("series_id");
			if(this.callEvent("onLegendClick",[e,series,obj])){
				var config = this._settings;
				var values = config.legend.values;
				var toggle = (values&&(typeof values[series].toggle != "undefined"))?values[series].toggle:config.legend.toggle;
			    if((typeof series != "undefined")&&this._series.length>1){
				    // hide action
				    if(toggle){
					    if(obj.className.indexOf("hidden")!=-1){
						    this.showSeries(series);
					    }
					    else{
						    this.hideSeries(series);
					    }
				    }
			    }
			}
		}
	},
	on_dblclick:{
	},
	on_mouse_move:{
	},
	destructor: function(){
		dhtmlx.Destruction.destructor.apply(this, arguments);
		if(this.canvases){
			for(var i in this.canvases){
				this.canvases[i]._obj = null;
				this.canvases[i] = null;
			}
			this.canvases = null;
		}
		if(this.legendObj){
			this.legendObj.innerHTML = "";
			this.legendObj = null;
		}
		if (this.config.tooltip) {
			this.config.tooltip._obj = null;
			this.config.tooltip._dataobj = null;
		}
	},
	bind:function(){
		dhtmlx.BaseBind.legacyBind.apply(this, arguments);
	},
	sync:function(){
		dhtmlx.BaseBind.legacySync.apply(this, arguments);
	},
	resize:function(){
		for(var c in this.canvases){
			this.canvases[c]._resizeCanvas();
		}
		this.render();	
	},
	view_setter:function( val){
		if (!dhtmlx.chart[val])
			dhtmlx.error("Chart type extension is not loaded: "+val);
		//if you will need to add more such settings - move them ( and this one ) in a separate methods
		
		if (typeof this._settings.offset == "undefined"){
			this._settings.offset = !(val == "area" || val == "stackedArea");
		}
        if(val=="radar"&&!this._settings.yAxis)
		    this.define("yAxis",{});
        if(val=="scatter"){
            if(!this._settings.yAxis)
                this.define("yAxis",{});
            if(!this._settings.xAxis)
                this.define("xAxis",{});
        }

		return val;
	},
	clearCanvas:function(){
		if(this.canvases&&typeof this.canvases == "object")
			for(var c in this.canvases){
				this.canvases[c].clearCanvas();
			}
	},
	render:function(){
		var bounds, i, data, map, temp;

		if (!this.callEvent("onBeforeRender",[this.data]))
			return;
		
		if(this.canvases&&typeof this.canvases == "object"){
			for(i in this.canvases){
				this.canvases[i].clearCanvas();
			}
		}
		else
			this.canvases = {};

		if(this._settings.legend){
			if(!this.canvases["legend"])
				this.canvases["legend"] =  new dhtmlx.ui.Canvas(this._obj,"legend");
			this._drawLegend(
				this.data.getRange(),
				this._obj.offsetWidth
			);
		}
		bounds = this._getChartBounds(this._obj.offsetWidth,this._obj.offsetHeight);
		this._map = map = new dhtmlx.ui.Map(this._id);
		temp = this._settings;
		data = this._getChartData();
		
		for(i=0; i < this._series.length;i++){
		 	this._settings = this._series[i];
			if(!this.canvases[i]){
				this.canvases[i] = new dhtmlx.ui.Canvas(this._obj,i,"z-index:"+(2+i));
			}
			this["pvt_render_"+this._settings.view](
				this.canvases[i].getCanvas(),
				data,
				bounds.start,
				bounds.end,
				i,
				map
			);
		}

		map.render(this._obj);
		this._obj.lastChild.style.zIndex = 1000;
		this._applyBounds(this._obj.lastChild,bounds);
		this.callEvent("onAfterRender",[]);
		this._settings = temp;
	},
	_applyBounds: function(elem,bounds){
		var style = {};
		style.left = bounds.start.x;
		style.top = bounds.start.y;
		style.width = bounds.end.x-bounds.start.x;
		style.height = bounds.end.y - bounds.start.y;
		for(var prop in style){
			elem.style[prop] = style[prop]+"px";
		}
	},
	_getChartData: function(){
		var  axis, axisConfig ,config, data, i, newData, start, units, value, valuesHash;
		data = this.data.getRange();
		axis = (this._settings.view.toLowerCase().indexOf("barh")!=-1?"yAxis":"xAxis");
		axisConfig = this._settings[axis];
		if(axisConfig&&axisConfig.units&&(typeof axisConfig.units == "object")){
			config = axisConfig.units;
			units = [];
			if(typeof config.start != "undefined"&&typeof config.end != "undefined" && typeof config.next != "undefined"){
				start = config.start;
				while(start<=config.end){
					units.push(start);
					start = config.next.call(this,start);
				}
			}
			else if(Object.prototype.toString.call(config) === '[object Array]'){
				units = config;
			}
			newData = [];
			if(units.length){
				value = axisConfig.value;
				valuesHash = {};
				for(i=0;i < data.length;i++){
					valuesHash[value(data[i])] = i;
				}
				for(i=0;i< units.length;i++){
					if(typeof valuesHash[units[i]]!= "undefined"){
						data[valuesHash[units[i]]].$unit = units[i];
						newData.push(data[valuesHash[units[i]]]);
					}
					else{
						newData.push({$unit:units[i]});
					}
				}
			}
			return newData;
		}
		return data;
	},
	value_setter:dhtmlx.Template.obj_setter,
    xValue_setter:dhtmlx.Template.obj_setter,
    yValue_setter:function(config){
        this.define("value",config);
    },
	alpha_setter:dhtmlx.Template.obj_setter,	
	label_setter:dhtmlx.Template.obj_setter,
	lineColor_setter:dhtmlx.Template.obj_setter,
	borderColor_setter:dhtmlx.Template.obj_setter,
	pieInnerText_setter:dhtmlx.Template.obj_setter,
	gradient_setter:function(config){
		if((typeof(config)!="function")&&config&&(config === true))
			config = "light";
		return config;
	},
	colormap:{
		"RAINBOW":function(obj){
            var pos = Math.floor(this.indexById(obj.id)/this.dataCount()*1536);
			if (pos==1536) pos-=1;
			return this._rainbow[Math.floor(pos/256)](pos%256);
		}
	},
	color_setter:function(value){
		return this.colormap[value]||dhtmlx.Template.obj_setter( value);
	},
    fill_setter:function(value){
        return ((!value||value==0)?false:dhtmlx.Template.obj_setter( value));
    },
    definePreset:function(obj){
        this.define("preset",obj.preset);
        delete obj.preset;
    },
	preset_setter:function(value){
        var a, b, preset;
        this.defaults = dhtmlx.extend({},this.defaults);
        if(typeof dhtmlx.presets.chart[value]=="object"){

            preset =  dhtmlx.presets.chart[value];
            for(a in preset){

                if(typeof preset[a]=="object"){
                    if(!this.defaults[a]||typeof this.defaults[a]!="object"){
                         this.defaults[a] = dhtmlx.extend({},preset[a]);
                    }
                    else{
                        this.defaults[a] = dhtmlx.extend({},this.defaults[a]);
                        for(b in preset[a]){
                            this.defaults[a][b] = preset[a][b];
                        }
                    }
                }else{
                     this.defaults[a] = preset[a];
                }
            }
            return value;
        }
		return false;
	},
	legend_setter:function( config){
		if(!config){
			if(this.legendObj){
				this.legendObj.innerHTML = "";
				this.legendObj = null;
			}
			return false;
		}
		if(typeof(config)!="object")	//allow to use template string instead of object
			config={template:config};
			
		this._mergeSettings(config,{
			width:150,
			height:18,
			layout:"y",
			align:"left",
			valign:"bottom",
			template:"",
			toggle:(this._settings.view.toLowerCase().indexOf("stacked")!=-1?"":"hide"),
			marker:{
				type:"square",
				width:15,
				height:15,
                radius:3
			},
            margin: 4,
            padding: 3
		});
		
		config.template = dhtmlx.Template.setter(config.template);
		return config;
	},
    defaults:{
        color:"RAINBOW",
		alpha:"1",
		label:false,
		value:"{obj.value}",
		padding:{},
		view:"pie",
		lineColor:"#ffffff",
		cant:0.5,
		width: 30,
		labelWidth:100,
		line:{
            width:2,
			color:"#1293f8"
        },
	    seriesMargin: 1,
	    seriesPadding: 4,
		item:{
			radius:3,
			borderColor:"#636363",
            borderWidth:1,
            color: "#ffffff",
            alpha:1,
            type:"r",
            shadow:false
		},
		shadow:true,
		gradient:false,
		border:true,
		labelOffset: 20,
		origin:"auto"
    },
	item_setter:function( config){
		if(typeof(config)!="object")
			config={color:config, borderColor:config};
        this._mergeSettings(config,dhtmlx.extend({},this.defaults.item));
		var settings = ["alpha","borderColor","color","radius"];
		for(var i=0; i< settings.length; i++)
			config[settings[i]] = dhtmlx.Template.setter(config[settings[i]]);
		/*config.alpha = dhtmlx.Template.setter(config.alpha);
        config.borderColor = dhtmlx.Template.setter(config.borderColor);
		config.color = dhtmlx.Template.setter(config.color);
        config.radius = dhtmlx.Template.setter(config.radius);*/
		return config;
	},
	line_setter:function( config){
		if(typeof(config)!="object")
			config={color:config};
	    dhtmlx.extend(this.defaults.line,config);
        config = dhtmlx.extend({},this.defaults.line);
		config.color = dhtmlx.Template.setter(config.color);
		return config;
	},
	padding_setter:function( config){	
		if(typeof(config)!="object")
			config={left:config, right:config, top:config, bottom:config};
		this._mergeSettings(config,{
			left:50,
			right:20,
			top:35,
			bottom:40
		});
		return config;
	},
	xAxis_setter:function( config){
		if(!config) return false;
		if(typeof(config)!="object")
			config={ template:config };
		if(!config.value)
			config.value = config.template;
		this._mergeSettings(config,{
			title:"",
			color:"#000000",
			lineColor:"#cfcfcf",
			template:"{obj}",
			value:"{obj}",
			lines:true
		});
		var templates = ["lineColor","template","lines","value"];
        this._converToTemplate(templates,config);
		this._configXAxis = dhtmlx.extend({},config);
		return config;
	},
    yAxis_setter:function( config){
		if(!config) return false;
	    this._mergeSettings(config,{
			title:"",
			color:"#000000",
			lineColor:"#cfcfcf",
			template:"{obj}",
			lines:true,
            bg:"#ffffff"
		});
		var templates = ["lineColor","template","lines","bg"];
        this._converToTemplate(templates,config);
		this._configYAxis = dhtmlx.extend({},config);
		return config;
	},
    _converToTemplate:function(arr,config){
        for(var i=0;i< arr.length;i++){
            config[arr[i]] = dhtmlx.Template.setter(config[arr[i]]);
        }
    },
    _drawScales:function(data,point0,point1,start,end,cellWidth){
	    var y = 0;
	    if(this._settings.yAxis){
		    if(! this.canvases["y"])
		        this.canvases["y"] =  new dhtmlx.ui.Canvas(this._obj,"axis_y");
		    y = this._drawYAxis(this.canvases["y"].getCanvas(),data,point0,point1,start,end);
	    }
	    if (this._settings.xAxis){
		    if(! this.canvases["x"])
			    this.canvases["x"] =  new dhtmlx.ui.Canvas(this._obj,"axis_x");
		    this._drawXAxis(this.canvases["x"].getCanvas(),data,point0,point1,cellWidth,y);
	    }
		return y;
	},
	_drawXAxis:function(ctx,data,point0,point1,cellWidth,y){
		var x0 = point0.x-0.5;
		var y0 = parseInt((y?y:point1.y),10)+0.5;
		var x1 = point1.x;
		var unitPos;
		var center = true;
		var labelY = (this._settings.origin ===0 && this._settings.view=="stackedBar")?point1.y+0.5:y0;

		for(var i=0; i < data.length; i++){

			if(this._settings.offset === true)
				unitPos = x0+cellWidth/2+i*cellWidth;
			else{
				unitPos = (i==data.length-1)?point1.x:x0+i*cellWidth;
				center = !!i;
			}
			unitPos = Math.ceil(unitPos)-0.5;
			/*scale labels*/
			var top = ((this._settings.origin!="auto")&&(this._settings.view=="bar")&&(parseFloat(this._settings.value(data[i]))<this._settings.origin));
			this._drawXAxisLabel(unitPos,labelY,data[i],center,top);
			/*draws a vertical line for the horizontal scale*/

			if((this._settings.offset||i)&&this._settings.xAxis.lines.call(this,data[i]))
		    	this._drawXAxisLine(ctx,unitPos,point1.y,point0.y,data[i]);
		}
		
		this.canvases["x"].renderTextAt(true, false, x0,point1.y+this._settings.padding.bottom-3,
			this._settings.xAxis.title,
			"dhx_axis_title_x",
			point1.x - point0.x
		);
		this._drawLine(ctx,x0,y0,x1,y0,this._settings.xAxis.color,1);
		/*the right border in lines in scale are enabled*/
		if (!this._settings.xAxis.lines.call(this,{}) || !this._settings.offset) return;
		this._drawLine(ctx,x1+0.5,point1.y,x1+0.5,point0.y+0.5,this._settings.xAxis.color,0.2);
	},
	_drawYAxis:function(ctx,data,point0,point1,start,end){
		var step;
		var scaleParam= {};
		if (!this._settings.yAxis) return;
		
		var x0 = point0.x - 0.5;
		var y0 = point1.y;
		var y1 = point0.y;
		var lineX = point1.y;
		
		//this._drawLine(ctx,x0,y0,x0,y1,this._settings.yAxis.color,1);
		
		if(this._settings.yAxis.step)
		     step = parseFloat(this._settings.yAxis.step);

		if(typeof this._configYAxis.step =="undefined"||typeof this._configYAxis.start=="undefined"||typeof this._configYAxis.end =="undefined"){
			scaleParam = this._calculateScale(start,end);
			start = scaleParam.start;
			end = scaleParam.end;
			step = scaleParam.step;
			
			this._settings.yAxis.end = end;
			this._settings.yAxis.start = start;
		}
		this._setYAxisTitle(point0,point1);
		/*if(step===0) return;
		if(end==start){
			return y0;
		}
		var stepHeight = (y0-y1)*step/(end-start);*/
		if(step===0){
			end = start;
			step = 1;
		}
		var stepHeight = (end==start?y0-y1:(y0-y1)*step/(end-start));
		var c = 0;
		for(var i = start; i<=end; i += step){
			if(scaleParam.fixNum)  i = parseFloat((new Number(i)).toFixed(scaleParam.fixNum));
			var yi = Math.floor(y0-c*stepHeight)+ 0.5;/*canvas line fix*/
			if(!(i==start&&this._settings.origin=="auto") &&this._settings.yAxis.lines.call(this,i))
				this._drawLine(ctx,x0,yi,point1.x,yi,this._settings.yAxis.lineColor.call(this,i),1);
			if(i == this._settings.origin) lineX = yi;
			/*correction for JS float calculation*/
			var label = i;
			if(step<1){
				var power = Math.min(this._log10(step),(start<=0?0:this._log10(start)));
				var corr = Math.pow(10,-power);
				label = Math.round(i*corr)/corr;
				i = label;
			}
			this.canvases["y"].renderText(0,yi-5,
				this._settings.yAxis.template(label.toString()),
				"dhx_axis_item_y",
				point0.x-5
			);	
			c++;
		}
		this._drawLine(ctx,x0,y0+1,x0,y1,this._settings.yAxis.color,1);
		return lineX;
	},
	_setYAxisTitle:function(point0,point1){
        var className = "dhx_axis_title_y"+(dhtmlx._isIE&&dhtmlx._isIE !=9?" dhx_ie_filter":"");
		var text=this.canvases["y"].renderTextAt("middle",false,0,parseInt((point1.y-point0.y)/2+point0.y,10),this._settings.yAxis.title,className);
        if (text)
			text.style.left = (dhtmlx.env.transform?(text.offsetHeight-text.offsetWidth)/2:0)+"px";
		/*var ctx = this.canvases["y"].getCanvas();
		var metric = ctx.measureText(this._settings.yAxis.title);
		var tx = 5 + (metric.width/2);
		var ty = 5 +point0.y+ (point1.y-point0.y)/2+metric.width/2;
		ctx.font = "bold 12pt sans-serif";
		ctx.save();
		ctx.translate(tx,ty);
		ctx.rotate(Math.PI/2*3);
		//ctx.translate(-tx,-ty);

		ctx.fillText(this._settings.yAxis.title, 0,0)
		ctx.restore();*/

	},
	_calculateScale:function(nmin,nmax){
	    if(this._settings.origin!="auto"&&this._settings.origin<nmin)
			nmin = this._settings.origin;
		var step,start,end;
	   	step = ((nmax-nmin)/8)||1;
		var power = Math.floor(this._log10(step));
		var calculStep = Math.pow(10,power);
		var stepVal = step/calculStep;
		stepVal = (stepVal>5?10:5);
		step = parseInt(stepVal,10)*calculStep;
		
		if(step>Math.abs(nmin))
			start = (nmin<0?-step:0);
		else{
			var absNmin = Math.abs(nmin);
			var powerStart = Math.floor(this._log10(absNmin));
			var nminVal = absNmin/Math.pow(10,powerStart);
			start = Math.ceil(nminVal*10)/10*Math.pow(10,powerStart)-step;
			if(absNmin>1&&step>0.1){
				start = Math.ceil(start);
			}
			while(nmin<0?start<=nmin:start>=nmin)
				start -= step;
			if(nmin<0) start =-start-2*step;
			
		}
	     end = start;
		while(end<nmax){
			end += step;
			end = parseFloat((new Number(end)).toFixed(Math.abs(power)));
		}
		return { start:start,end:end,step:step,fixNum:Math.abs(power) };
	},
	_getLimits:function(orientation,value){
		var maxValue,minValue;
		var axis = ((arguments.length && orientation=="h")?this._configXAxis:this._configYAxis);
		value = value||"value";
		if(axis&&(typeof axis.end!="undefined")&&(typeof axis.start!="undefined")&&axis.step){
		    maxValue = parseFloat(axis.end);
			minValue = parseFloat(axis.start);      
		}
		else{
			maxValue = this.max(this._series[0][value]);
			minValue = (axis&&(typeof axis.start!="undefined"))?parseFloat(axis.start):this.min(this._series[0][value]);
			if(this._series.length>1)
			for(var i=1; i < this._series.length;i++){
				var maxI = this.max(this._series[i][value]);
				var minI = this.min(this._series[i][value]);
				if (maxI > maxValue) maxValue = maxI;
		    	if (minI < minValue) minValue = minI;
			}
		}
		return {max:maxValue,min:minValue};
	},
	_log10:function(n){
        var method_name="log";
        return Math.floor((Math[method_name](n)/Math.LN10));
    },
	_drawXAxisLabel:function(x,y,obj,center,top){
		if (!this._settings.xAxis) return;
		var elem = this.canvases["x"].renderTextAt(top, center, x,y-(top?2:0),this._settings.xAxis.template(obj));
		if (elem)
			elem.className += " dhx_axis_item_x";
	},
	_drawXAxisLine:function(ctx,x,y1,y2,obj){
		if (!this._settings.xAxis||!this._settings.xAxis.lines) return;
		this._drawLine(ctx,x,y1,x,y2,this._settings.xAxis.lineColor.call(this,obj),1);
	},
	_drawLine:function(ctx,x1,y1,x2,y2,color,width){
		ctx.strokeStyle = color;
		ctx.lineWidth = width;
		ctx.beginPath();
		ctx.moveTo(x1,y1);
		ctx.lineTo(x2,y2);
		ctx.stroke();
        ctx.lineWidth = 1;
	},
	_getRelativeValue:function(minValue,maxValue){
	    var relValue, origRelValue;
		var valueFactor = 1;
		if(maxValue != minValue){
			relValue = maxValue - minValue;
			/*if(Math.abs(relValue) < 1){
			    while(Math.abs(relValue)<1){
				    valueFactor *= 10;
				    relValue = origRelValue* valueFactor;
				}
			}
			*/
		}
		else relValue = minValue;
		return [relValue,valueFactor];
	},
	_rainbow : [
		function(pos){ return "#FF"+dhtmlx.math.toHex(pos/2,2)+"00";},
		function(pos){ return "#FF"+dhtmlx.math.toHex(pos/2+128,2)+"00";},
		function(pos){ return "#"+dhtmlx.math.toHex(255-pos,2)+"FF00";},
		function(pos){ return "#00FF"+dhtmlx.math.toHex(pos,2);},
		function(pos){ return "#00"+dhtmlx.math.toHex(255-pos,2)+"FF";},
		function(pos){ return "#"+dhtmlx.math.toHex(pos,2)+"00FF";}		
	],
	/**
	*   adds series to the chart (value and color properties)
	*   @param: obj - obj with configuration properties
	*/
	addSeries:function(obj){
		var temp = this._settings;
		this._settings = dhtmlx.extend({},temp);
		this._parseSettings(obj,{});
	    this._series.push(this._settings);
		this._settings = temp;
    },
    /*switch global settings to serit in question*/
    _switchSerie:function(id, tag, e){
        var tip;
    	this._active_serie = (this._series.length==1?tag.getAttribute("userdata"):this._getActiveSeries(e));

    	if (!this._series[this._active_serie]) return;
    	for (var i=0; i < this._series.length; i++) {
    		tip = this._series[i].tooltip;
    		if (tip)
    			tip.disable();
		}
	    if(!tag.getAttribute("disabled")){
		    tip = this._series[this._active_serie].tooltip;
		    if (tip)
			    tip.enable();
	    }
    },
	_getActiveSeries: function(e){
		var a, areas, i, offset, pos, selection,  x, y;

		areas = this._map._areas;
		offset = dhtmlx.html.offset(this._obj._htmlmap);
		pos = dhtmlx.html.pos(e);
		x = pos.x - offset.x;
		y = pos.y - offset.y;

		for( i = 0; i < areas.length; i++){
			a = areas[i].points;
			if(x <= a[2] && x >= a[0] && y <= a[3] && y >= a[1]){
				if(selection){
					if(areas[i].index > selection.index)
						selection = areas[i];
				}
				else
					selection = areas[i];
			}
		}

		return selection?selection.index:0;
	},
	hideSeries:function(series){
		this.canvases[series].hideCanvas();
		if(this._settings.legend.values&&this._settings.legend.values[series])
			this._settings.legend.values[series].$hidden = true;
		this._drawLegend();
	},
	showSeries:function(series){
		this.canvases[series].showCanvas();
		if(this._settings.legend.values&&this._settings.legend.values[series])
			delete this._settings.legend.values[series].$hidden;
		this._drawLegend();

	},
	_changeColorSV:function(color,s,v){
		var hsv,rgb;
		rgb = dhtmlx.math.toRgb(color);
		hsv = dhtmlx.math.rgbToHsv(rgb[0],rgb[1],rgb[2]);
		hsv[1] *= s;
		hsv[2] *= v;
		return "rgb("+dhtmlx.math.hsvToRgb(hsv[0],hsv[1],hsv[2])+")";

	},
	_setBorderStyles:function(ctx,color){
		var hsv,rgb;
		rgb = dhtmlx.math.toRgb(color);
		hsv = dhtmlx.math.rgbToHsv(rgb[0],rgb[1],rgb[2]);
		hsv[2] /= 2;
		color = "rgb("+dhtmlx.math.hsvToRgb(hsv[0],hsv[1],hsv[2])+")";
		ctx.strokeStyle = color;
		if(ctx.globalAlpha==1)
			ctx.globalAlpha = 0.9;
	},
	/**
	*   renders legend block
	*   @param: ctx - canvas object
	*   @param: data - object those need to be displayed
	*   @param: width - the width of the container
	*   @param: height - the height of the container
	*/
	_drawLegend:function(data,width){
        var i, legend, legendContainer, legendHeight, legendItems, legendWidth, style, x=0, y= 0, ctx, itemColor, disabled, item;

		data = data||[];
		width = width||this._obj.offsetWidth;
		ctx = this.canvases["legend"].getCanvas();
		/*legend config*/
		legend = this._settings.legend;
        
		style = (this._settings.legend.layout!="x"?"width:"+legend.width+"px":"");
		/*creation of legend container*/
		if(this.legendObj){
			this.legendObj.innerHTML = "";
			this.legendObj.parentNode.removeChild(this.legendObj);
		}

		this.canvases["legend"].clearCanvas(true);
		legendContainer = dhtmlx.html.create("DIV",{
			"class":"dhx_chart_legend",
			"style":"left:"+x+"px; top:"+y+"px;"+style
		},"");
        if(legend.padding){
            legendContainer.style.padding =  legend.padding+"px";
        }
		this.legendObj = legendContainer;
		this._obj.appendChild(legendContainer);
		/*rendering legend text items*/
		legendItems = [];
		if(!legend.values)
			for(i = 0; i < data.length; i++){
				legendItems.push(this._drawLegendText(legendContainer,legend.template(data[i])));
			}
		else
			for(i = 0; i < legend.values.length; i++){
				legendItems.push(this._drawLegendText(legendContainer,legend.values[i].text,(typeof legend.values[i].id!="undefined"?typeof legend.values[i].id:i),legend.values[i].$hidden));
			}
	   	legendWidth = legendContainer.offsetWidth;
	    legendHeight = legendContainer.offsetHeight;
		/*this._settings.legend.width = legendWidth;
		this._settings.legend.height = legendHeight;*/

		/*setting legend position*/
		if(legendWidth<this._obj.offsetWidth){
			if(legend.layout == "x"&&legend.align == "center"){
			    x = (this._obj.offsetWidth-legendWidth)/2;
            }
			if(legend.align == "right"){
				x = this._obj.offsetWidth-legendWidth;
			}
            if(legend.margin&&legend.align != "center"){
                x += (legend.align == "left"?1:-1)*legend.margin;
            }


        }
		if(legendHeight<this._obj.offsetHeight){
			if(legend.valign == "middle"&&legend.align != "center"&&legend.layout != "x")
				y = (this._obj.offsetHeight-legendHeight)/2;
			else if(legend.valign == "bottom")
				y = this._obj.offsetHeight-legendHeight;
            if(legend.margin&&legend.valign != "middle"){
                y += (legend.valign == "top"?1:-1)*legend.margin;
            }
		}
		legendContainer.style.left = x+"px";
		legendContainer.style.top = y+"px";

		/*drawing colorful markers*/
		ctx.save();

		for(i = 0; i < legendItems.length; i++){
			item = legendItems[i];
			if(legend.values&&legend.values[i].$hidden){
				disabled = true;
				itemColor = (legend.values[i].disableColor?legend.values[i].disableColor:"#d9d9d9");
			}
			else{
				disabled = false;
				itemColor = (legend.values?legend.values[i].color:this._settings.color.call(this,data[i]));
			}
			var offset = (legend.marker.position == "right"?item.offsetWidth - legend.marker.width:0);
			this._drawLegendMarker(ctx,item.offsetLeft + x + offset,item.offsetTop+y,itemColor,item.offsetHeight,disabled,i);
		}
		ctx.restore();
		legendItems = null;
	},
	/**
	*   appends legend item to legend block
	*   @param: ctx - canvas object
	*   @param: obj - data object that needs being represented
	*/
	_drawLegendText:function(cont,value,series,disabled){
		var style = "";
		var legend = this._settings.legend;
		if(legend.layout=="x")
			style = "float:left;";
		/*the text of the legend item*/
		var pos = legend.marker.position;
		var text = dhtmlx.html.create("DIV",{
			"style":style+"padding-"+(pos&&pos=="right"?"right":"left")+":"+(10+legend.marker.width)+"px",
			"class":"dhx_chart_legend_item"+(disabled?" hidden":"")
		},value);
		if(arguments.length>2)
			text.setAttribute("series_id",series);
		cont.appendChild(text);
		return text;
	},
	/**
	*   draw legend colorful marder
	*   @param: ctx - canvas object
	*   @param: x - the horizontal position of the marker
	*   @param: y - the vertical position of the marker
	*   @param: color - marker color
	*   @param: height - item height
	*   @param: disabled - disabled staet
	*   @param: i - index of legend item
	*/
	_drawLegendMarker:function(ctx,x,y,color,height,disabled,i){
		var p = [];
		var marker = this._settings.legend.marker;
		var values = this._settings.legend.values;
		var type = (values&&values[i].markerType?values[i].markerType:marker.type);

		if(color){
			ctx.fillStyle = color;
			ctx.strokeStyle = this._getDarkenColor(color,0.75);
		}
        ctx.beginPath();
		if(type=="round"||!marker.radius){
            ctx.lineWidth = marker.height;
		    ctx.lineCap = type;
		    /*start of marker*/
		    x += ctx.lineWidth/2+5;
		    y += height/2;
		    ctx.moveTo(x,y);
		    var x1 = x + marker.width-marker.height +1;
		    ctx.lineTo(x1,y);

        }else if(type=="item"){
			/*copy of line*/
			if(this._settings.line&&this._settings.view != "scatter" && !this._settings.disableLines){
				ctx.beginPath();
				ctx.lineWidth = this._series[i].line.width;
				ctx.strokeStyle = disabled?color:this._series[i].line.color.call(this,{});
				var x0 = x + 5;
				var y0 = y + height/2;
				ctx.moveTo(x0,y0);
				var x1 = x0 + marker.width;
				ctx.lineTo(x1,y0);
				ctx.stroke();
			}
			/*item copy*/


			var config = this._series[i].item;
			var radius = parseInt(config.radius.call(this,{}),10)||0;
			if(radius){
				if(config.type == "image" && config.src){
					this._drawImage(ctx,x+5,y+marker.height/2-5,config.src,radius*2, radius*2);
					return;
				}
				else{
					ctx.beginPath();
					if(disabled){
						ctx.lineWidth = config.borderWidth;
						ctx.strokeStyle = color;
						ctx.fillStyle = color;
					}
					else{
						ctx.lineWidth = config.borderWidth;
						ctx.fillStyle = config.color.call(this,{});
						ctx.strokeStyle = config.borderColor.call(this,{});
						ctx.globalAlpha = config.alpha.call(this,{});
					}
					ctx.beginPath();
					x += marker.width/2+5;
					y += height/2;
					this._strokeChartItem(ctx,x,y,radius+1,config.type);
					ctx.fill();
					ctx.stroke();
				}

			}
			ctx.globalAlpha = 1;
		}else{
            ctx.lineWidth = 1;
            x += 5;
            y += parseInt(height/2-marker.height/2,10);

			p = [
				[x+marker.radius,y+marker.radius,marker.radius,Math.PI,3*Math.PI/2,false],
				[x+marker.width-marker.radius,y],
				[x+marker.width-marker.radius,y+marker.radius,marker.radius,-Math.PI/2,0,false],
				[x+marker.width,y+marker.height-marker.radius],
				[x+marker.width-marker.radius,y+marker.height-marker.radius,marker.radius,0,Math.PI/2,false],
				[x+marker.radius,y+marker.height],
				[x+marker.radius,y+marker.height-marker.radius,marker.radius,Math.PI/2,Math.PI,false],
				[x,y+marker.radius]
			];
            this._path(ctx,p);
        }

		ctx.stroke();
        ctx.fill();

	},
	_getDarkenColor:function(color,darken){
		var hsv,rgb;
		rgb = dhtmlx.math.toRgb(color);
		hsv = dhtmlx.math.rgbToHsv(rgb[0],rgb[1],rgb[2]);
		hsv[2] = hsv[2]*darken;
		return "rgb("+dhtmlx.math.hsvToRgb(hsv[0],hsv[1],hsv[2])+")";
	},
	/**
	*   gets the points those represent chart left top and right bottom bounds
	*   @param: width - the width of the chart container
	*   @param: height - the height of the chart container
	*/
	_getChartBounds:function(width,height){
		var chartX0, chartY0, chartX1, chartY1;
		
		chartX0 = this._settings.padding.left;
		chartY0 = this._settings.padding.top;
		chartX1 = width - this._settings.padding.right;
		chartY1 = height - this._settings.padding.bottom;	
		
		if(this._settings.legend){
			var legend = this._settings.legend;
			/*legend size*/
			var legendWidth = this._settings.legend.width;
			var legendHeight = this._settings.legend.height;
		
			/*if legend is horizontal*/
			if(legend.layout == "x"){
				if(legend.valign == "center"){
					if(legend.align == "right")
						chartX1 -= legendWidth;
					else if(legend.align == "left")
				 		chartX0 += legendWidth;
			 	}
			 	else if(legend.valign == "bottom"){
			    	chartY1 -= legendHeight;
			 	}
			 	else{
			    	chartY0 += legendHeight;
			 	}
			}
			/*vertical scale*/
			else{
				if(legend.align == "right")
					chartX1 -= legendWidth;
			 	else if(legend.align == "left")
					chartX0 += legendWidth;
			}
		}
		return {start:{x:chartX0,y:chartY0},end:{x:chartX1,y:chartY1}};
	},
	/**
	*   gets the maximum and minimum values for the stacked chart
	*   @param: data - data set
	*/
	_getStackedLimits:function(data){
		var i, j, maxValue, minValue, value;
		if(this._settings.yAxis&&(typeof this._settings.yAxis.end!="undefined")&&(typeof this._settings.yAxis.start!="undefined")&&this._settings.yAxis.step){
		    maxValue = parseFloat(this._settings.yAxis.end);
			minValue = parseFloat(this._settings.yAxis.start);
		}
		else{
			for(i=0; i < data.length; i++){
				data[i].$sum = 0 ;
				data[i].$min = Infinity;
				for(j =0; j < this._series.length;j++){
					value = parseFloat(this._series[j].value(data[i])||0);
					if(isNaN(value)) continue;
					if(this._series[j].view.toLowerCase().indexOf("stacked")!=-1)
						data[i].$sum += value;
					if(value < data[i].$min) data[i].$min = value;
				}
			}
			maxValue = -Infinity;
			minValue = Infinity;
			for(i=0; i < data.length; i++){
				if (data[i].$sum > maxValue) maxValue = data[i].$sum ;
				if (data[i].$min < minValue) minValue = data[i].$min ;
			}
			if(minValue>0) minValue =0;
		}
		return {max: maxValue, min: minValue};
	},
	/*adds colors to the gradient object*/
	_setBarGradient:function(ctx,x1,y1,x2,y2,type,color,axis){
		var gradient, offset, rgb, hsv, color0, stops;
		if(type == "light"){
			if(axis == "x")
				gradient = ctx.createLinearGradient(x1,y1,x2,y1);
			else
				gradient = ctx.createLinearGradient(x1,y1,x1,y2);
			stops = [[0,"#FFFFFF"],[0.9,color],[1,color]];
			offset = 2;
		}
		else if(type == "falling"||type == "rising"){
			if(axis == "x")
				gradient = ctx.createLinearGradient(x1,y1,x2,y1);
			else
				gradient = ctx.createLinearGradient(x1,y1,x1,y2);
			rgb = dhtmlx.math.toRgb(color);
			hsv = dhtmlx.math.rgbToHsv(rgb[0],rgb[1],rgb[2]);
			hsv[1] *= 1/2;
			color0 = "rgb("+dhtmlx.math.hsvToRgb(hsv[0],hsv[1],hsv[2])+")";
			if(type == "falling"){
				stops = [[0,color0],[0.7,color],[1,color]];
			}
			else if(type == "rising"){
				stops = [[0,color],[0.3,color],[1,color0]];
			}
			offset = 0;
		}
		else{
			ctx.globalAlpha = 0.37;
			offset = 0;
			if(axis == "x")
				gradient = ctx.createLinearGradient(x1,y2,x1,y1);
			else
				gradient = ctx.createLinearGradient(x1,y1,x2,y1);
			stops = [[0,"#9d9d9d"],[0.3,"#e8e8e8"],[0.45,"#ffffff"],[0.55,"#ffffff"],[0.7,"#e8e8e8"],[1,"#9d9d9d"]];
		}
		this._gradient(gradient,stops);
		return {gradient:gradient,offset:offset};
	},
    /**
	*   returns the x and y position
    *   @param: a - angle
    *   @param: x - start x position
    *   @param: y - start y position
	*   @param: r - destination to the point
	*/
     _getPositionByAngle:function(a,x,y,r){
         a *= (-1);
         x = x+Math.cos(a)*r;
         y = y-Math.sin(a)*r;
         return {x:x,y:y};
    },
	_gradient:function(gradient,stops){
		for(var i=0; i< stops.length; i++){
			gradient.addColorStop(stops[i][0],stops[i][1]);
		}
	},
	_path: function(ctx,points){
		var i, method;
		for(i = 0; i< points.length; i++){
			method = (i?"lineTo":"moveTo");
			if(points[i].length>2)
				method = "arc";
			ctx[method].apply(ctx,points[i]);
		}
	},
	_circle: function(ctx,x,y,r){
		ctx.arc(x,y,r,Math.PI*2,true);
	},
	_addMapRect:function(map,id,points,bounds,sIndex){
		map.addRect(id,[points[0].x-bounds.x,points[0].y-bounds.y,points[1].x-bounds.x,points[1].y-bounds.y],sIndex);
	}
};

dhtmlx.compat("layout");

if (typeof(window.dhtmlXCellObject) != "undefined") {
	
	dhtmlXCellObject.prototype.attachChart = function(conf) {
		
		this.callEvent("_onBeforeContentAttach",["chart"]);
		
		var obj = document.createElement("DIV");
		obj.id = "dhxChartObj_"+window.dhx4.newId();
		obj.style.width = "100%";
		obj.style.height = "100%";
		document.body.appendChild(obj);
		this._attachObject(obj);
		
		conf.container = obj.id;
		
		this.dataType = "chart";
		this.dataObj = new dhtmlXChart(conf);
		
		if (!this.dataObj.setSizes) {
			this.dataObj.setSizes = function() {
				if (this.resize) this.resize(); else this.render();
			};
		}
		
		return this.dataObj;
	};
	
}

