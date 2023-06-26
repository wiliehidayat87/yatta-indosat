<?php
class MY_Log extends CI_Log {

    protected $_threshold	= 4;
    public $trxid;
    public $counter;

    function __construct() {
        parent::__construct();
    }

    function error($message) {
        
        $this->write_log('error',$message);
    }

    function info($message) {
        $this->write_log('info',$message);
    }

    function debug($message) {
        $this->write_log('debug',$message);
    }

    function warning($message) {
        $this->write_log('warning',$message);
    }

    function start_counter() {
        if (empty($this->counter)) {
            $this->counter = $this->format_microtime(microtime(true));
        }
    }

    private function format_microtime($str) {
        return sprintf('%.5f',$str);
    }

    function write_log($level = 'error', $msg, $php_error = FALSE) {
        
        if (empty($this->trxid)) {
            $this->trxid = rand(10000000,99999999);
        }
        if (empty($this->counter)) {
            $this->counter = microtime(true);
        }

        require_once LOG_PACKAGE;
        require_once APPPATH . 'libraries/pear_log/Logger.php';

        $level = strtoupper($level);
        
        if ( ! isset($this->_levels[$level]) OR ($this->_levels[$level] > $this->_threshold)) {
            
            return FALSE;
        }

        $message  = '';
        $logger = &Log::singleton('kb', LOG_PATH . LOG_FILENAME, 'log', array('timeFormat' => LOG_TIME_FORMAT, 'lineFormat' => LOG_LINE_FORMAT), LOG_LEVEL);

        $priorities = array(
            'emergency' => PEAR_LOG_EMERG ,
            'alert'  	=> PEAR_LOG_ALERT ,
            'critical'  => PEAR_LOG_CRIT,
            'error'     => PEAR_LOG_ERR,
            'warning' 	=> PEAR_LOG_WARNING,
            'notice'    => PEAR_LOG_NOTICE,
            'info'  	=> PEAR_LOG_INFO ,
            'debug'   	=> PEAR_LOG_DEBUG
        );
        $logger->log($this->trxid." ".$msg." | ".$this->format_microtime(microtime(true)-$this->counter),$priorities[strtolower($level)]);
        return TRUE;
    }
}