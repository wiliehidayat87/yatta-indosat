<?php
require_once APPPATH . 'libraries/pear_log/Log/file.php';

class Log_kb extends Log_file {

    public $backtrace_custom ;

    function _format($format, $timestamp, $priority, $message) {

        if (preg_match('/%[5678]/', $format)) {
            list($file, $line, $func, $class) = $this->_getBacktraceVars(4);
        }

        $file = str_replace(FCPATH,'',$file);
        //echo $priority . '|';
        return sprintf($format, // %1$s [%3$s] %{file} %{class}:%{function}:%6$s, %4$s
        $timestamp,
        $this->_ident,
        $this->priorityToString($priority),
        $message,
        isset($file) ? $file : '',
        isset($line) ? $line : '',
        isset($func) ? $func : '',
        isset($class) ? $class : '');
    }
}