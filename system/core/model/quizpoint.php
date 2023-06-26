<?php

class model_quizpoint extends model_base {

    public function getPointByCode($code) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $code));

        $sql = sprintf("SELECT point FROM quiz_point WHERE code = " . $code . " ");
        return $this->databaseObj->fetch($sql);
    }

    public function getPointById($id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $id));

        $sql = sprintf("SELECT point FROM quiz_point WHERE id = " . $id . " ");
        return $this->databaseObj->fetch($sql);
    }

}