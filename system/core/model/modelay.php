<?php

class model_modelay extends model_base {

    public function add(mo_delay_data $mo_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_delay)));

        $spring_config = loader_config::getInstance()->getConfig('spring');
        $sql = sprintf("
                    INSERT INTO mo_delay (
                            obj, service, adn, msisdn, date_created, date_expired, status
                    ) VALUES (
                            '%s', '%s', '%s', '%s', NOW(), DATE_ADD(NOW(),INTERVAL %d SECOND), '%s'
                    )", mysql_real_escape_string($mo_delay->obj), mysql_real_escape_string($mo_delay->service), mysql_real_escape_string($mo_delay->adn), mysql_real_escape_string($mo_delay->msisdn), mysql_real_escape_string($spring_config->tim_mo_delay), mysql_real_escape_string($mo_delay->status));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(mo_delay_data $mo_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_delay)));

        $sql = sprintf("UPDATE mo_delay SET status = '%s' WHERE id = '%s'", mysql_real_escape_string($mo_delay->status), mysql_real_escape_string($mo_delay->id));
        return $this->databaseObj->query($sql);
    }

    public function get($user) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $user));

        $sql = sprintf("SELECT * FROM mo_delay WHERE msisdn = '%s' AND status = '0' ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($user));
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

        $spring_config = loader_config::getInstance()->getConfig('spring');
        $sql = sprintf("SELECT * FROM mo_delay WHERE date_expired < NOW() AND status = '0' LIMIT %d;", mysql_real_escape_string($spring_config->timForwarderThrottle));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

}