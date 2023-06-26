<?php

class model_quizowner extends model_base {

    public function getOwnerById($owner_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $owner_id));

        $sql = sprintf("SELECT * FROM quiz_owner WHERE id = " . $owner_id . "");
        $data = $this->databaseObj->fetch($sql);
        return $data[0];
    }

    public function getOwnerByName($owner_name) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $owner_name));

        $sql = sprintf("SELECT * FROM quiz_owner WHERE name = '" . $owner_name . "' ");
        $data = $this->databaseObj->fetch($sql);
        return $data[0];
    }

}