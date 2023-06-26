<?php

class model_service extends model_base {

    public function get() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT * FROM service GROUP BY name");
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

}