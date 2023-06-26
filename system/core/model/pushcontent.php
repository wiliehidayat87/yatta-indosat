<?php

class model_pushcontent extends model_base {

    public function getContentDefault($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = "
            SELECT * FROM dbpush.push_content
            WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
            AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
            LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }
    
    public function getContent($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = "
            SELECT * FROM dbpush.push_content 
            WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "' 
            AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "' 
            AND DATE(datepublish) = CURDATE()
            LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result [0];
        } else {
            return false;
        }
    }

    public function getContentRandom($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = "
           SELECT * FROM dbpush.push_content
           WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
           AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
           ORDER BY RAND()
           LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getContentSequential($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = "
           SELECT * FROM dbpush.push_content
           WHERE id > (SELECT last_content_id
           FROM push_schedule
           WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
           AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "')
           AND service = '" . mysql_real_escape_string($broadcast_data->service) . "'
           AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
           LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getContentCustom($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $$sql = "
            SELECT * FROM dbpush.push_content
            WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
            AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
            LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getContentSequentialRepeat($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = "
            SELECT * FROM dbpush.push_content
            WHERE id > (SELECT last_content_id
            FROM push_schedule
'           WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
            AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "')
            AND service = '" . mysql_real_escape_string($broadcast_data->service) . "'
            AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
            LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function updateSchedule($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = sprintf("UPDATE push_schedule SET last_content_id = '" . mysql_real_escape_string($broadcast_data->lastContentId) . "'
                     WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
                     AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
                    ");
        $this->databaseObj->query($sql);
        return true;
    }

    public function updateScheduleRepeat($broadcast_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($broadcast_data)));

        $sql = sprintf("UPDATE push_schedule SET last_content_id = '0'
                     WHERE service = '" . mysql_real_escape_string($broadcast_data->service) . "'
                     AND content_label = '" . mysql_real_escape_string($broadcast_data->contentLabel) . "'
                    ");
        $this->databaseObj->query($sql);
        return true;
    }

}
