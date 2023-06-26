<?php

class model_pin extends model_base {

    public function create(pin_data $pin_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($pin_data)));

        $sql = sprintf("INSERT INTO spring_pin 
                (`id`, `adn`, `service`, `msisdn`, `pin`, `date_created`, `date_modified`, `status`) 
                VALUES (NULL, '%s', '%s', '%s', '%s', NOW(), NOW(), '%s');", mysql_real_escape_string($pin_data->adn), mysql_real_escape_string($pin_data->service), mysql_real_escape_string($pin_data->msisdn), mysql_real_escape_string($pin_data->pin), mysql_real_escape_string($pin_data->status));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(pin_data $pin_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($pin_data)));

        $sql = sprintf("UPDATE spring_pin SET `status` = '%s', `date_modified` = NOW() WHERE `id` = '%s';", mysql_real_escape_string($pin_data->status), mysql_real_escape_string($pin_data->id));
        return $this->databaseObj->query($sql);
    }

    public function read(pin_data $pin_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($pin_data)));

        $sql = sprintf("select * from spring_pin 
                where adn = '%s' and service = '%s' and msisdn = '%s' and status = 0 and pin = '%s' and date_created < DATE_ADD(date_created, INTERVAL '%s' MINUTE) limit 1;", mysql_real_escape_string($pin_data->adn), mysql_real_escape_string($pin_data->service), mysql_real_escape_string($pin_data->msisdn), mysql_real_escape_string($pin_data->pin), '10');
        $result = $this->databaseObj->fetch($sql);

        if (count($result) > 0)
            return $result[0];
        else
            return false;
    }

}