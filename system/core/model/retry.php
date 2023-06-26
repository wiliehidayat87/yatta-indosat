<?php

class model_retry extends model_base {

    public function save($query) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $query));

        return $this->databaseObj->query($query);
    }

}