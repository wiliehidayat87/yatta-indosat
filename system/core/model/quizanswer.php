<?php

class model_quizanswer extends model_base {

    public function getAnswerByQuestion($question_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $question_id));

        $sql = sprintf("SELECT * FROM quiz_answer where question_id = " . $question_id . " ");
        return $this->databaseObj->fetch($sql);
    }

    public function getAnswerByAnswer($answer) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $answer));

        $sql = sprintf("SELECT question_id FROM quiz_answer where answer = " . strtoupper($answer) . " ");
        return $this->databaseObj->fetch($sql);
    }

    public function getPoint($questionId, $answerState) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $questionId . " " . $answerState));

        $sql = sprintf("SELECT point FROM quiz_answer where question_id = " . $questionId . " AND answer_state = " . $answerState . " ");
        $data = $this->databaseObj->fetch($sql);
        return $data[0];
    }

}