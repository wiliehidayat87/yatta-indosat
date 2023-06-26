<?php

class model_quizwinner extends model_base {

    public function getWinner($ownerId, $quizId, $year, $month, $week, $periodType) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("");

        $sql = "SELECT * FROM quiz_winner WHERE owner_id = " . $ownerId . " AND quiz_id = " . $quizId . " ";
        if (!empty($week))
            $sql .= "AND week = " . $week . " ";

        if (!empty($month))
            $sql .= "AND month = " . $month . " ";

        if (!empty($year))
            $sql .= "AND year = " . $year . " ";

        if (!empty($periodType))
            $sql .= "AND period_type = " . $periodType . " ";

        $sql .= "AND canceled = 0 ORDER BY total_point DESC";

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0)
            return $result;
        else
            return false;
    }

    public function getMonthlyWinner($ownerId, $quizId, $year, $month, $periodType) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("");

        $sql = "SELECT * FROM quiz_winner WHERE owner_id = " . $ownerId . " AND quiz_id = " . $quizId . " ";

        if (!empty($month))
            $sql .= "AND month = " . $month . " ";

        if (!empty($year))
            $sql .= "AND year = " . $year . " ";

        if (!empty($periodType))
            $sql .= "AND period_type = " . $periodType . " ";

        $sql .= "AND canceled = 0 ORDER BY total_point DESC";

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0)
            return $result;
        else
            return false;
    }

}