<?php

class model_quizprize extends model_base {

    public function getPrize() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT * FROM quiz_prize WHERE active = 1");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

}