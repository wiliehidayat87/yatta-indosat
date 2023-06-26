<?php

class model_begin extends model_base {

    private static $slot;
    
    public function transaction($slot) {
        $this->slot = $slot;
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $this->databaseObj->query("SET autocommit=0;");
        $this->databaseObj->query("START TRANSACTION;");
        return true;
    }

    public function commit() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $this->databaseObj->query("COMMIT;");
        return true;
    }

    public function rollback() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . mysql_error()));

        $this->databaseObj->query("ROLLBACK");
        $lock = new library_lockfile('mo_processor');
        $lock->delete($this->slot);
        exit;
        return false;
    }

}