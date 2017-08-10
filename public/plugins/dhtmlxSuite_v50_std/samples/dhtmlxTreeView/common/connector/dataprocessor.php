<?php
/*
    @author dhtmlx.com
    @license GPL, see license.txt
*/
/*! Base DataProcessor handling
**/

require_once 'xss_filter.php';

class DataProcessor
{
    protected $connector; //!< Connector instance
    protected $config; //!< DataConfig instance
    protected $request; //!< DataRequestConfig instance
    public static $action_param = '!nativeeditor_status';

    /*! constructor
<<<<<<< HEAD

        @param connector
=======
        
        @param connector 
>>>>>>> refs/remotes/origin/master
            Connector object
        @param config
            DataConfig object
        @param request
            DataRequestConfig object
    */
    public function __construct($connector, $config, $request)
    {
        $this->connector = $connector;
        $this->config = $config;
        $this->request = $request;
    }

    /*! convert incoming data name to valid db name
        redirect to Connector->name_data by default
<<<<<<< HEAD
        @param data
            data name from incoming request
        @return
=======
        @param data 
            data name from incoming request
        @return 
>>>>>>> refs/remotes/origin/master
            related db_name
    */
    public function name_data($data)
    {
        return $data;
    }

    /*! retrieve data from incoming request and normalize it
<<<<<<< HEAD

        @param ids
            array of extected IDs
        @return
=======
        
        @param ids 
            array of extected IDs
        @return 
>>>>>>> refs/remotes/origin/master
            hash of data
    */
    protected function get_post_values($ids)
    {
        $data = [];
        for ($i = 0; $i < count($ids); $i++) {
            $data[$ids[$i]] = [];
        }

        foreach ($_POST as $key => $value) {
            $details = explode('_', $key, 2);
            if (count($details) == 1) {
                continue;
            }

            $name = $this->name_data($details[1]);
            $data[$details[0]][$name] = ConnectorSecurity::filter($value);
        }

        return $data;
    }

    protected function get_ids()
    {
        if (!isset($_POST['ids'])) {
            throw new Exception('Incorrect incoming data, ID of incoming records not recognized');
        }

        return explode(',', $_POST['ids']);
    }

    protected function get_operation($rid)
    {
        if (!isset($_POST[$rid.'_'.self::$action_param])) {
            throw new Exception("Status of record [{$rid}] not found in incoming request");
        }

        return $_POST[$rid.'_'.self::$action_param];
    }

    /*! process incoming request ( save|update|delete )
    */
    public function process()
    {
        LogMaster::log('DataProcessor object initialized', $_POST);

        $results = [];

        $ids = $this->get_ids();
        $rows_data = $this->get_post_values($ids);
        $failed = false;

        try {
            if ($this->connector->sql->is_global_transaction()) {
                $this->connector->sql->begin_transaction();
            }

            for ($i = 0; $i < count($ids); $i++) {
                $rid = $ids[$i];
                LogMaster::log("Row data [{$rid}]", $rows_data[$rid]);
                $status = $this->get_operation($rid);

                $action = new DataAction($status, $rid, $rows_data[$rid]);
                $results[] = $action;
                $this->inner_process($action);
            }
        } catch (Exception $e) {
            LogMaster::log($e);
            $failed = true;
        }

        if ($this->connector->sql->is_global_transaction()) {
            if (!$failed) {
                for ($i = 0; $i < count($results); $i++) {
                    if ($results[$i]->get_status() == 'error' || $results[$i]->get_status() == 'invalid') {
                        $failed = true;
                        break;
                    }
                }
            }
            if ($failed) {
                for ($i = 0; $i < count($results); $i++) {
                    $results[$i]->error();
                }
                $this->connector->sql->rollback_transaction();
            } else {
                $this->connector->sql->commit_transaction();
            }
        }

        $this->output_as_xml($results);
    }

    /*! converts status string to the inner mode name
<<<<<<< HEAD

        @param status
            external status string
        @return
=======
        
        @param status 
            external status string
        @return 
>>>>>>> refs/remotes/origin/master
            inner mode name
    */
    protected function status_to_mode($status)
    {
        switch ($status) {
            case 'updated':
                return 'update';
                break;
            case 'inserted':
                return 'insert';
                break;
            case 'deleted':
                return 'delete';
                break;
            default:
                return $status;
                break;
        }
    }

    /*! process data updated request received
<<<<<<< HEAD

        @param action
            DataAction object
        @return
=======
        
        @param action 
            DataAction object
        @return 
>>>>>>> refs/remotes/origin/master
            DataAction object with details of processing
    */
    protected function inner_process($action)
    {
        if ($this->connector->sql->is_record_transaction()) {
            $this->connector->sql->begin_transaction();
        }

        try {
            $mode = $this->status_to_mode($action->get_status());
            if (!$this->connector->access->check($mode)) {
                LogMaster::log("Access control: {$mode} operation blocked");
                $action->error();
            } else {
                $check = $this->connector->event->trigger('beforeProcessing', $action);
                if (!$action->is_ready()) {
                    $this->check_exts($action, $mode);
                }
                if ($mode == 'insert' && $action->get_status() != 'error' && $action->get_status() != 'invalid') {
                    $this->connector->sql->new_record_order($action, $this->request);
                }

                $check = $this->connector->event->trigger('afterProcessing', $action);
            }
        } catch (Exception $e) {
            LogMaster::log($e);
            $action->set_status('error');
            if ($action) {
                $this->connector->event->trigger('onDBError', $action, $e);
            }
        }

        if ($this->connector->sql->is_record_transaction()) {
            if ($action->get_status() == 'error' || $action->get_status() == 'invalid') {
                $this->connector->sql->rollback_transaction();
            } else {
                $this->connector->sql->commit_transaction();
            }
        }

        return $action;
    }

    /*! check if some event intercepts processing, send data to DataWrapper in other case

<<<<<<< HEAD
        @param action
=======
        @param action 
>>>>>>> refs/remotes/origin/master
            DataAction object
        @param mode
            name of inner mode ( will be used to generate event names )
    */
    public function check_exts($action, $mode)
    {
        $old_config = new DataConfig($this->config);

        $this->connector->event->trigger('before'.$mode, $action);
        if ($action->is_ready()) {
            LogMaster::log('Event code for '.$mode.' processed');
        } else {
            //check if custom sql defined
            $sql = $this->connector->sql->get_sql($mode, $action);
            if ($sql) {
                $this->connector->sql->query($sql);
            } else {
                $action->sync_config($this->config);
                if ($this->connector->model && method_exists($this->connector->model, $mode)) {
                    call_user_func([$this->connector->model, $mode], $action);
                    LogMaster::log('Model object process action: '.$mode);
                }
                if (!$action->is_ready()) {
                    $method = [$this->connector->sql, $mode];
                    if (!is_callable($method)) {
                        throw new Exception('Unknown dataprocessing action: '.$mode);
                    }
                    call_user_func($method, $action, $this->request);
                }
            }
        }
        $this->connector->event->trigger('after'.$mode, $action);

        $this->config->copy($old_config);
    }

    /*! output xml response for dataprocessor

        @param  results
            array of DataAction objects
    */
    public function output_as_xml($results)
    {
        LogMaster::log('Edit operation finished', $results);
        ob_clean();
        header('Content-type:text/xml');
        echo "<?xml version='1.0' ?>";
        echo '<data>';
        for ($i = 0; $i < count($results); $i++) {
            echo $results[$i]->to_xml();
        }
        echo '</data>';
    }
}

/*! contain all info related to action and controls customizaton
**/
class DataAction
{
    private $status; //!< cuurent status of record
    private $id; //!< id of record
    private $data; //!< data hash of record
    private $userdata; //!< hash of extra data , attached to record
    private $nid; //!< new id value , after operation executed
    private $output; //!< custom output to client side code
    private $attrs; //!< hash of custtom attributes
    private $ready; //!< flag of operation's execution
    private $addf; //!< array of added fields
    private $delf; //!< array of deleted fields

    /*! constructor
<<<<<<< HEAD

        @param status
=======
        
        @param status 
>>>>>>> refs/remotes/origin/master
            current operation status
        @param id
            record id
        @param data
            hash of data
    */
    public function __construct($status, $id, $data)
    {
        $this->status = $status;
        $this->id = $id;
        $this->data = $data;
        $this->nid = $id;

        $this->output = '';
        $this->attrs = [];
        $this->ready = false;

        $this->addf = [];
        $this->delf = [];
    }

    /*! add custom field and value to DB operation
<<<<<<< HEAD

        @param name
=======
        
        @param name 
>>>>>>> refs/remotes/origin/master
            name of field which will be added to DB operation
        @param value
            value which will be used for related field in DB operation
    */
    public function add_field($name, $value)
    {
        LogMaster::log('adding field: '.$name.', with value: '.$value);
        $this->data[$name] = $value;
        $this->addf[] = $name;
    }

    /*! remove field from DB operation
<<<<<<< HEAD

        @param name
=======
        
        @param name 
>>>>>>> refs/remotes/origin/master
            name of field which will be removed from DB operation
    */
    public function remove_field($name)
    {
        LogMaster::log('removing field: '.$name);
        $this->delf[] = $name;
    }

    /*! sync field configuration with external object
<<<<<<< HEAD

        @param slave
            SQLMaster object
        @todo
=======
        
        @param slave 
            SQLMaster object
        @todo 
>>>>>>> refs/remotes/origin/master
            check , if all fields removed then cancel action
    */
    public function sync_config($slave)
    {
        foreach ($this->addf as $k => $v) {
            $slave->add_field($v);
        }
        foreach ($this->delf as $k => $v) {
            $slave->remove_field($v);
        }
    }

    /*! get value of some record's propery
<<<<<<< HEAD

        @param name
            name of record's property ( name of db field or alias )
        @return
=======
        
        @param name 
            name of record's property ( name of db field or alias )
        @return 
>>>>>>> refs/remotes/origin/master
            value of related property
    */
    public function get_value($name)
    {
        if (!array_key_exists($name, $this->data)) {
            LogMaster::log('Incorrect field name used: '.$name);
            LogMaster::log('data', $this->data);

            return '';
        }

        return $this->data[$name];
    }

    /*! set value of some record's propery
<<<<<<< HEAD

        @param name
=======
        
        @param name 
>>>>>>> refs/remotes/origin/master
            name of record's property ( name of db field or alias )
        @param value
            value of related property
    */
    public function set_value($name, $value)
    {
        LogMaster::log('change value of: '.$name.' as: '.$value);
        $this->data[$name] = $value;
    }

    /*! get hash of data properties
<<<<<<< HEAD

        @return
=======
        
        @return 
>>>>>>> refs/remotes/origin/master
            hash of data properties
    */
    public function get_data()
    {
        return $this->data;
    }

    /*! get some extra info attached to record
        deprecated, exists just for backward compatibility, you can use set_value instead of it
<<<<<<< HEAD
        @param name
            name of userdata property
        @return
=======
        @param name 
            name of userdata property
        @return 
>>>>>>> refs/remotes/origin/master
            value of related userdata property
    */
    public function get_userdata_value($name)
    {
        return $this->get_value($name);
    }

    /*! set some extra info attached to record
        deprecated, exists just for backward compatibility, you can use get_value instead of it
<<<<<<< HEAD
        @param name
=======
        @param name 
>>>>>>> refs/remotes/origin/master
            name of userdata property
        @param value
            value of userdata property
    */
    public function set_userdata_value($name, $value)
    {
        return $this->set_value($name, $value);
    }

    /*! get current status of record
<<<<<<< HEAD

        @return
=======
        
        @return 
>>>>>>> refs/remotes/origin/master
            string with status value
    */
    public function get_status()
    {
        return $this->status;
    }

    /*! assign new status to the record
<<<<<<< HEAD

        @param status
=======
        
        @param status 
>>>>>>> refs/remotes/origin/master
            new status value
    */
    public function set_status($status)
    {
        $this->status = $status;
    }

   /*! set id
    @param  id
        id value
    */
    public function set_id($id)
    {
        $this->id = $id;
        LogMaster::log('Change id: '.$id);
    }

   /*! set id
    @param  id
        id value
    */
    public function set_new_id($id)
    {
        $this->nid = $id;
        LogMaster::log('Change new id: '.$id);
    }

    /*! get id of current record
<<<<<<< HEAD

        @return
=======
        
        @return 
>>>>>>> refs/remotes/origin/master
            id of record
    */
    public function get_id()
    {
        return $this->id;
    }

    /*! sets custom response text
<<<<<<< HEAD

        can be accessed through defineAction on client side. Text wrapped in CDATA, so no extra escaping necessary
        @param text
=======
        
        can be accessed through defineAction on client side. Text wrapped in CDATA, so no extra escaping necessary
        @param text 
>>>>>>> refs/remotes/origin/master
            custom response text
    */
    public function set_response_text($text)
    {
        $this->set_response_xml('<![CDATA['.$text.']]>');
    }

    /*! sets custom response xml
<<<<<<< HEAD

=======
        
>>>>>>> refs/remotes/origin/master
        can be accessed through defineAction on client side
        @param text
            string with XML data
    */
    public function set_response_xml($text)
    {
        $this->output = $text;
    }

    /*! sets custom response attributes
<<<<<<< HEAD

=======
        
>>>>>>> refs/remotes/origin/master
        can be accessed through defineAction on client side
        @param name
            name of custom attribute
        @param value
            value of custom attribute
    */
    public function set_response_attribute($name, $value)
    {
        $this->attrs[$name] = $value;
    }

<<<<<<< HEAD
    /*! check if action finished

        @return
=======
    /*! check if action finished 
        
        @return 
>>>>>>> refs/remotes/origin/master
            true if action finished, false otherwise
    */
    public function is_ready()
    {
        return $this->ready;
    }

    /*! return new id value
<<<<<<< HEAD

        equal to original ID normally, after insert operation - value assigned for new DB record
        @return
=======
    
        equal to original ID normally, after insert operation - value assigned for new DB record	
        @return 
>>>>>>> refs/remotes/origin/master
            new id value
    */
    public function get_new_id()
    {
        return $this->nid;
    }

    /*! set result of operation as error
    */
    public function error()
    {
        $this->status = 'error';
        $this->ready = true;
    }

    /*! set result of operation as invalid
    */
    public function invalid()
    {
        $this->status = 'invalid';
        $this->ready = true;
    }

    /*! confirm successful opeation execution
        @param  id
            new id value, optional
    */
    public function success($id = false)
    {
        if ($id !== false) {
            $this->nid = $id;
        }
        $this->ready = true;
    }

    /*! convert DataAction to xml format compatible with client side dataProcessor
<<<<<<< HEAD
        @return
=======
        @return 
>>>>>>> refs/remotes/origin/master
            DataAction operation report as XML string
    */
    public function to_xml()
    {
        $str = "<action type='{$this->status}' sid='{$this->id}' tid='{$this->nid}' ";
        foreach ($this->attrs as $k => $v) {
            $str .= $k."='".$this->xmlentities($v)."' ";
        }
        $str .= ">{$this->output}</action>";

        return $str;
    }

    /*! replace xml unsafe characters
<<<<<<< HEAD

        @param string
            string to be escaped
        @return
=======
        
        @param string 
            string to be escaped
        @return 
>>>>>>> refs/remotes/origin/master
            escaped string
    */
    public function xmlentities($string)
    {
        return str_replace(['&', '"', "'", '<', '>', '’'], ['&amp;', '&quot;', '&apos;', '&lt;', '&gt;', '&apos;'], $string);
    }

    /*! convert self to string ( for logs )
<<<<<<< HEAD

        @return
            DataAction operation report as plain string
=======
        
        @return 
            DataAction operation report as plain string 
>>>>>>> refs/remotes/origin/master
    */
    public function __toString()
    {
        return "action:{$this->status}; sid:{$this->id}; tid:{$this->nid};";
    }
}
