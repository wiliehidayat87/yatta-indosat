<?php

class model_claropin extends model_base {

    public function create(pin_claro_data $pin_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($pin_data)));

        $spring_config = loader_config::getInstance()->getConfig('spring');
        $sql = sprintf("INSERT INTO claro_account 
                    (msisdn, pin, date_created, date_expired) 
                    VALUES ('%s', '%s', NOW(), DATE_ADD(NOW(),INTERVAL %d SECOND));", mysql_real_escape_string($pin_data->msisdn), mysql_real_escape_string($pin_data->pin), mysql_real_escape_string($spring_config->claro_pin_expired));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

}