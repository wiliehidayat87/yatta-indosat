<?php

class model_quizsession extends model_base {

    public function checkSessionDate() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT * FROM quiz_session where date_start <= now() and date_end >= now()");
        return $this->databaseObj->fetch($sql);
    }

    public function getMaxQuestion($sessionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $sessionId));

        $sql = sprintf("SELECT max_allowed_question FROM quiz_session where id = " . $sessionId . " ");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getSessionActive($ownerId, $quizId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $ownerId . " " . $quizId));

        $sql = sprintf("SELECT * FROM quiz_session where owner_id = " . $ownerId . " AND quiz_id = " . $quizId . " AND date_start <= now() AND date_end >= now()");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

}