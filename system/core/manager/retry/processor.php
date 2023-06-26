<?php

class manager_retry_processor {

    public function process() {
        $log_profile = 'retry_processor';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $config_main = loader_config::getInstance()->getConfig('main');

        $class_name = $config_main->operator . '_retry_processor';
        if (class_exists($class_name)) {
            $retryProcessor = new $class_name ();
        } else if (class_exists('default_retry_processor')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $retryProcessor = new default_retry_processor ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_retry_processor"));
            return false;
        }

        $retryProcessor->queue();
        $retryProcessor->db();
        return true;
    }

}
