<?php

class model_mtdelay extends model_base {

    public function add(mt_delay_data $mt_delay) {

        $delay_config = loader_config::getInstance()->getConfig('delay');
        $sql = sprintf("
                    INSERT INTO mt_delay (
                            obj, operator, service, adn, msisdn, date_created, date_expired, status
                    ) VALUES (
                            '%s', '%s', '%s', '%s', '%s', NOW(), DATE_ADD(NOW(),INTERVAL %d SECOND), 0 
                    )", mysql_real_escape_string($mt_delay->obj), mysql_real_escape_string($mt_delay->operator), mysql_real_escape_string($mt_delay->service), mysql_real_escape_string($mt_delay->adn), mysql_real_escape_string($mt_delay->msisdn), mysql_real_escape_string($delay_config->delayTime));

        $log = manager_logging::getInstance();
        //$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_delay)));
        $log->write(array('level' => 'debug', 'message' => "Add textdelay data : " . $sql));

        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(mt_delay_data $mt_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_delay)));

        $sql = sprintf("UPDATE mt_delay SET status = '%s' WHERE id = '%s'", mysql_real_escape_string($mt_delay->status), mysql_real_escape_string($mt_delay->id));
        return $this->databaseObj->query($sql);
    }

    public function get($user) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $user));

        $sql = sprintf("SELECT * FROM mt_delay WHERE msisdn = '%s' AND status = '0' ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($user));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result [0];
        } else {
            return false;
        }
    }

    public function getExpired() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $delay_config = loader_config::getInstance()->getConfig('delay');
        $sql = sprintf("SELECT * FROM mt_delay WHERE date_expired < NOW() AND status = '0' LIMIT %d;", mysql_real_escape_string($delay_config->mtDelay));

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getExpiredByOperator($opr) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $delay_config = loader_config::getInstance()->getConfig('delay');
        $sql = sprintf("SELECT * FROM mt_delay WHERE date_expired < NOW() AND status = '0' and operator='%s' LIMIT %d;",mysql_real_escape_string($opr), mysql_real_escape_string($delay_config->mtDelay));

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }


}

