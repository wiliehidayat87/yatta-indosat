<?php

class model_quiztransaction extends model_base {

    public function getTransaction($type) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $type));

        $sql = sprintf("SELECT * FROM quiz_transaction where transaction_type = " . $type . "");
        return $this->databaseObj->fetch($sql);
    }

    public function getLastQuestionTransaction($msisdn) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $msisdn));

        $sql = sprintf("SELECT * FROM quiz_transaction where msisdn = " . $msisdn . " ORDER BY id DESC LIMIT 1");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getSubscriberScore($quizId, $msisdn) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $quizId . " " . $msisdn));

        $sql = sprintf("SELECT SUM(point) AS subscriberscore FROM quiz_transaction where quiz_id = " . $quizId . " AND msisdn = '" . $msisdn . "' ");
        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function insertTransaction($transactionData) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($transactionData)));

        $sql = sprintf("INSERT INTO quiz_transaction 
						(owner_id,quiz_id,msisdn,transaction_type,transaction_ref,message,point,date_send,date_respond,question_sent_counter) 
						VALUES (%d , %d , '%s' , %d , %d , '%s' , %d , NOW(), NOW(), %d );", mysql_real_escape_string($transactionData->ownerId), mysql_real_escape_string($transactionData->quizId), mysql_real_escape_string($transactionData->msisdn), mysql_real_escape_string($transactionData->transactionType), mysql_real_escape_string($transactionData->transactionRef), mysql_real_escape_string($transactionData->message), mysql_real_escape_string($transactionData->point), mysql_real_escape_string($transactionData->questionSentCounter));

        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function getMonthlyPoint($date) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $date));

        $sql = "SELECT SUM(point) AS monthly_total FROM quiz_transaction WHERE date_send like '%" . $date . "%' GROUP BY msisdn ORDER BY monthly_total desc LIMIT 1";

        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function getWeeklyPoint($weekDay, $msisdn) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $weekDay . " " . $msisdn));

        $sql = "SELECT SUM(point) AS weekly_total FROM quiz_transaction WHERE msisdn = '" . $msisdn . "' AND date_send >= ( CURDATE() - INTERVAL " . $weekDay . " DAY )";

        $data = $this->databaseObj->fetch($sql);
        if ($data) {
            return $data[0];
        } else {
            return false;
        }
    }

    public function resetPoint($msisdn) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $msisdn));

        $sql = "UPDATE quiz_transaction SET point = 0 , question_sent_counter = 0";

        $sql .= sprintf(" WHERE msisdn = '%s';", mysql_real_escape_string($msisdn));

        $this->databaseObj->query($sql);
        return true;
    }

    public function updateSentCounter($transactId, $point, $sentCounter) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $transactId . " " . $point . " " . $sentCounter));

        $sql = "UPDATE quiz_transaction SET question_sent_counter = " . $sentCounter . " , point = " . $point;

        $sql .= sprintf(" WHERE id = '%s';", mysql_real_escape_string($transactId));

        $this->databaseObj->query($sql);
        return true;
    }

}