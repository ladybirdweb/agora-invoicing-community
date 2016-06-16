/*
Product Name: dhtmlxSuite 
Version: 5.0 
Edition: Standard 
License: content of this file is covered by GPL. Usage outside GPL terms is prohibited. To obtain Commercial or Enterprise license contact sales@dhtmlx.com
Copyright UAB Dinamenta http://www.dhtmlx.com
*/

/*
Decimal value (10,000.00) eXcell for dhtmlxGrid
(c)DHTMLX LTD. 2005


The corresponding  cell value in XML should be valid number

Samples:
<cell>123.01</cell>
<cell>1234.09356</cell>
<cell>12345</cell>
<cell>0</cell>
<cell>-100</cell>
*/
function eXcell_dec(cell){
	if (cell){
		this.cell = cell;
	    this.grid = this.cell.parentNode.grid;
	}
	this.getValue = function(){
		return parseFloat(this.cell.innerHTML.replace(/,/g,""));
	}

	this.setValue = function(val){
		var format = "0,000.00";
		if(val=="0"){
			this.setCValue(format.replace(/.*(0\.[0]+)/,"$1"),val);
			return;
		}
		var z = format.substr(format.indexOf(".")+1).length
		val = Math.round(val*Math.pow(10,z)).toString();
		var out = "";
		var cnt=0;
		var fl = false;
		for(var i=val.length-1;i>=0;i--){
			cnt++;
			out = val.charAt(i)+out;
			if(!fl && cnt==z){
				out = "."+out;
				cnt=0;
				fl = true;
			}
			if(fl && cnt==3 && i!=0 && val.charAt(i-1)!='-'){
				out = ","+out;
				cnt=0;
			}
		}
		this.setCValue(out,val);
	}
}
eXcell_dec.prototype = new eXcell_ed;
//(c)dhtmlx ltd. www.dhtmlx.com
