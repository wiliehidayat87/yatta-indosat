<?php

class model_quizquestion extends model_base {

    public function getFirstQuestion($ownerId, $sessionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $ownerId . " " . $sessionId));

        $sql = sprintf("SELECT * FROM quiz_question WHERE session_id = " . $sessionId . " AND owner_id = " . $ownerId . " ORDER BY id ASC");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getQuestionById($questionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $questionId));

        $sql = sprintf("SELECT * FROM quiz_question WHERE id = " . $questionId . " ");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getNextQuestion($ownerId, $sessionId, $questionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $ownerId . " " . $sessionId . " " . $questionId));

        $sql = sprintf("SELECT * FROM quiz_question WHERE session_id = " . $sessionId . " AND owner_id = " . $ownerId . " AND id > " . $questionId . " ORDER BY id ASC");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getQuestionSent($msisdn, $sessionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $msisdn . " " . $sessionId));

        $sql = sprintf("SELECT COUNT(*) total_question_send FROM quiz_transaction qt INNER JOIN quiz_question qq ON qt.transaction_ref = qq.id WHERE qt.msisdn = " . $msisdn . " AND qt.transaction_type = 3 AND qq.session_id = " . $sessionId . " AND question_sent_counter = 1");
        $data = $this->databaseObj->fetch($sql);

        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

}