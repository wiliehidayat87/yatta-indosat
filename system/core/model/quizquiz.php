<?php

class model_quizquiz extends model_base {

    public function getQuizData($ownerId, $quizName) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $ownerId . " " . $quizName));

        $sql = sprintf("SELECT * FROM quiz_quiz where owner_id = " . $ownerId . " AND name = '" . $quizName . "' ORDER BY id ASC");
        $data = $this->databaseObj->fetch($sql);
        return $data[0];
    }

    public function getQuizIdByName($quizName) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $quizName));

        $sql = sprintf("SELECT id FROM quiz_quiz where name = '" . $quizName . "' ");
        $data = $this->databaseObj->fetch($sql);
        return $data[0];
    }

}