<?php

class model_schedule extends model_base {

    public function get(broadcast_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("SELECT * FROM dbpush.push_schedule WHERE push_time < NOW() AND operator = '%s' AND status = '%s';", mysql_real_escape_string($data->operator), mysql_real_escape_string($data->status));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    public function setStatus(broadcast_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("UPDATE dbpush.push_schedule SET `status` = '%s', `modified` = NOW()", mysql_real_escape_string($data->status));
        if (!empty($data->id)) {
            $sql .= sprintf(" WHERE id = '%s'", mysql_real_escape_string($data->id));
        }
        return $this->databaseObj->query($sql);
    }

    public function reset(broadcast_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        if ($data->recurringType == 1)
            $interval = 1;
        if ($data->recurringType == 2)
            $interval = 7;
        $sql = "UPDATE dbpush.push_schedule SET `status` = '0', `modified` = NOW()";
        if (!empty($interval))
            $sql .= sprintf(", push_time = DATE_ADD(push_time, INTERVAL %s DAY)", mysql_real_escape_string($interval));

        $sql .= sprintf(" WHERE id = '%s'", mysql_real_escape_string($data->id));

        return $this->databaseObj->query($sql);
    }

}
