<?php

class model_wapregsession extends model_base {

    public function add(wap_reg_session_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("INSERT INTO wapreg_session 
						(token, service, operator, adn, msisdn, status, date_created, date_modified ) 
						VALUES ('%s', '%s', '%s', '%s', '%s', '0', NOW(), NOW());", mysql_real_escape_string($data->token), mysql_real_escape_string($data->service), mysql_real_escape_string($data->operator), mysql_real_escape_string($data->adn), mysql_real_escape_string($data->msisdn));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(wap_reg_session_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("UPDATE wapreg_session SET `status` = '%s', `date_modified` = NOW() WHERE `id` = '%s';", mysql_real_escape_string($data->status), mysql_real_escape_string($data->id));
        return $this->databaseObj->query($sql);
    }

    public function read(wap_reg_session_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("select * from wapreg_session 
					where status = 0 and token = '%s' and date_created < DATE_ADD(date_created, INTERVAL '%s' MINUTE) limit 1;", mysql_real_escape_string($data->token), '10');
        $result = $this->databaseObj->fetch($sql);

        if (count($result) > 0)
            return $result[0];
        else
            return false;
    }

}