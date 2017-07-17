<?php
require_once("base_connector.php");

/*! DataItem class for Scheduler component
**/
class SchedulerDataItem extends DataItem{
	/*! return self as XML string
	*/
	function to_xml(){
		if ($this->skip) return "";
		
		$str="<event id='".$this->get_id()."' >";
		$str.="<start_date><![CDATA[".$this->data[$this->config->text[0]["name"]]."]]></start_date>";
		$str.="<end_date><![CDATA[".$this->data[$this->config->text[1]["name"]]."]]></end_date>";
		$str.="<text><![CDATA[".$this->data[$this->config->text[2]["name"]]."]]></text>";
		for ($i=3; $i<sizeof($this->config->text); $i++){
			$extra = $this->config->text[$i]["name"];
			$str.="<".$extra."><![CDATA[".$this->data[$extra]."]]></".$extra.">";
		}
		return $str."</event>";
	}
}


/*! Connector class for dhtmlxScheduler
**/
class SchedulerConnector extends Connector{
	
	/*! constructor
		
		Here initilization of all Masters occurs, execution timer initialized
		@param res 
			db connection resource
		@param type
			string , which hold type of database ( MySQL or Postgre ), optional, instead of short DB name, full name of DataWrapper-based class can be provided
		@param item_type
			name of class, which will be used for item rendering, optional, DataItem will be used by default
		@param data_type
			name of class which will be used for dataprocessor calls handling, optional, DataProcessor class will be used by default. 
	*/	
	public function __construct($res,$type=false,$item_type=false,$data_type=false){
		if (!$item_type) $item_type="SchedulerDataItem";
		if (!$data_type) $data_type="SchedulerDataProcessor";
		parent::__construct($res,$type,$item_type,$data_type);
	}

	//parse GET scoope, all operations with incoming request must be done here
	function parse_request(){
		parent::parse_request();

		if (isset($_GET["to"]))
			$this->request->set_filter($this->config->text[0]["name"],$_GET["to"],"<");
		if (isset($_GET["from"]))
			$this->request->set_filter($this->config->text[1]["name"],$_GET["from"],">");
	}
}

/*! DataProcessor class for Scheduler component
**/
class SchedulerDataProcessor extends DataProcessor{
	function name_data($data){
		if ($data=="start_date")
			return $this->config->text[0]["db_name"];
		if ($data=="end_date")
			return $this->config->text[1]["db_name"];
		if ($data=="text")
			return $this->config->text[2]["db_name"];
			
		return $data;
	}
}
?>