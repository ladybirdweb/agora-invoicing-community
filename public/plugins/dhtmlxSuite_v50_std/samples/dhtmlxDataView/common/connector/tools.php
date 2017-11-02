<?php
/*
    @author dhtmlx.com
    @license GPL, see license.txt
*/

/*! Class which allows to assign|fire events.
*/
class EventMaster
{
    private $events; //!< hash of event handlers
    private $master;
    private static $eventsStatic = [];

    /*! constructor
    */
    public function __construct()
    {
        $this->events = [];
        $this->master = false;
    }

    /*! Method check if event with such name already exists.
        @param name
            name of event, case non-sensitive
        @return
            true if event with such name registered, false otherwise
    */
    public function exist($name)
    {
        $name = strtolower($name);

        return isset($this->events[$name]) && count($this->events[$name]);
    }

    /*! Attach custom code to event.

        Only on event handler can be attached in the same time. If new event handler attached - old will be detached.

        @param name
            name of event, case non-sensitive
        @param method
            function which will be attached. You can use array(class, method) if you want to attach the method of the class.
    */
    public function attach($name, $method = false)
    {
        //use class for event handling
        if ($method === false) {
            $this->master = $name;

            return;
        }
        //use separate functions
        $name = strtolower($name);
        if (!array_key_exists($name, $this->events)) {
            $this->events[$name] = [];
        }
        $this->events[$name][] = $method;
    }

    public static function attach_static($name, $method)
    {
        $name = strtolower($name);
        if (!array_key_exists($name, self::$eventsStatic)) {
            self::$eventsStatic[$name] = [];
        }
        self::$eventsStatic[$name][] = $method;
    }

    public static function trigger_static($name, $method)
    {
        $arg_list = func_get_args();
        $name = strtolower(array_shift($arg_list));

        if (isset(self::$eventsStatic[$name])) {
            foreach (self::$eventsStatic[$name] as $method) {
                if (is_array($method) && !method_exists($method[0], $method[1])) {
                    throw new Exception('Incorrect method assigned to event: '.$method[0].':'.$method[1]);
                }
                if (!is_array($method) && !function_exists($method)) {
                    throw new Exception('Incorrect function assigned to event: '.$method);
                }
                call_user_func_array($method, $arg_list);
            }
        }

        return true;
    }

    /*! Detach code from event
        @param	name
            name of event, case non-sensitive
    */
    public function detach($name)
    {
        $name = strtolower($name);
        unset($this->events[$name]);
    }

    /*! Trigger event.
        @param	name
            name of event, case non-sensitive
        @param data
            value which will be provided as argument for event function,
            you can provide multiple data arguments, method accepts variable number of parameters
        @return
            true if event handler was not assigned , result of event hangler otherwise
    */
    public function trigger($name, $data)
    {
        $arg_list = func_get_args();
        $name = strtolower(array_shift($arg_list));

        if (isset($this->events[$name])) {
            foreach ($this->events[$name] as $method) {
                if (is_array($method) && !method_exists($method[0], $method[1])) {
                    throw new Exception('Incorrect method assigned to event: '.$method[0].':'.$method[1]);
                }
                if (!is_array($method) && !function_exists($method)) {
                    throw new Exception('Incorrect function assigned to event: '.$method);
                }
                call_user_func_array($method, $arg_list);
            }
        }

        if ($this->master !== false) {
            if (method_exists($this->master, $name)) {
                call_user_func_array([$this->master, $name], $arg_list);
            }
        }

        return true;
    }
}

/*! Class which handles access rules.
**/
class AccessMaster
{
    private $rules;
    private $local;

    /*! constructor

        Set next access right to "allowed" by default : read, insert, update, delete
        Basically - all common data operations allowed by default
    */
    public function __construct()
    {
        $this->rules = ['read' => true, 'insert' => true, 'update' => true, 'delete' => true];
        $this->local = true;
    }

    /*! change access rule to "allow"
        @param name
            name of access right
    */
    public function allow($name)
    {
        $this->rules[$name] = true;
    }

    /*! change access rule to "deny"

        @param name
            name of access right
    */
    public function deny($name)
    {
        $this->rules[$name] = false;
    }

    /*! change all access rules to "deny"
    */
    public function deny_all()
    {
        $this->rules = [];
    }

    /*! check access rule

        @param name
            name of access right
        @return
            true if access rule allowed, false otherwise
    */
    public function check($name)
    {
        if ($this->local) {
            /*!
            todo
                add referrer check, to prevent access from remote points
            */
        }
        if (!isset($this->rules[$name]) || !$this->rules[$name]) {
            return false;
        }

        return true;
    }
}

/*! Controls error and debug logging.
    Class designed to be used as static object.
**/
class LogMaster
{
    private static $_log = false; //!< logging mode flag
    private static $_output = false; //!< output error infor to client flag
    private static $session = ''; //!< all messages generated for current request

    /*! convert array to string representation ( it is a bit more readable than var_dump )

        @param data
            data object
        @param pref
            prefix string, used for formating, optional
        @return
            string with array description
    */
    private static function log_details($data, $pref = '')
    {
        if (is_array($data)) {
            $str = [''];
            foreach ($data as $k => $v) {
                array_push($str, $pref.$k.' => '.self::log_details($v, $pref."\t"));
            }

            return implode("\n", $str);
        }

        return $data;
    }

    /*! put record in log

        @param str
            string with log info, optional
        @param data
            data object, which will be added to log, optional
    */
    public static function log($str = '', $data = '')
    {
        if (self::$_log) {
            $message = $str.self::log_details($data)."\n\n";
            self::$session .= $message;
            error_log($message, 3, self::$_log);
        }
    }

    /*! get logs for current request
        @return
            string, which contains all log messages generated for current request
    */
    public static function get_session_log()
    {
        return self::$session;
    }

    /*! error handler, put normal php errors in log file

        @param errn
            error number
        @param errstr
            error description
        @param file
            error file
        @param line
            error line
        @param context
            error cntext
    */
    public static function error_log($errn, $errstr, $file, $line, $context)
    {
        self::log($errstr.' at '.$file.' line '.$line);
    }

    /*! exception handler, used as default reaction on any error - show execution log and stop processing

        @param exception
            instance of Exception
    */
    public static function exception_log($exception)
    {
        self::log("!!!Uncaught Exception\nCode: ".$exception->getCode()."\nMessage: ".$exception->getMessage());
        if (self::$_output) {
            echo "<pre><xmp>\n";
            echo self::get_session_log();
            echo "\n</xmp></pre>";
        }
        die();
    }

    /*! enable logging

        @param name
            path to the log file, if boolean false provided as value - logging will be disabled
        @param output
            flag of client side output, if enabled - session log will be sent to client side in case of an error.
    */
    public static function enable_log($name, $output = false)
    {
        self::$_log = $name;
        self::$_output = $output;
        if ($name) {
            set_error_handler(['LogMaster', 'error_log'], E_ALL);
            set_exception_handler(['LogMaster', 'exception_log']);
            self::log("\n\n====================================\nLog started, ".date('d/m/Y h:i:s')."\n====================================");
        }
    }
}
