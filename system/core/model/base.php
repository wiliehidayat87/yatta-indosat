<?php

class model_base {

    public $databaseObj = NULL;

    public function setConnection($conn) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $this->databaseObj = $conn;

        return TRUE;
    }

}