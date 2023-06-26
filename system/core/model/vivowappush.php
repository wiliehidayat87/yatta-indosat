<?php

class model_vivowappush extends model_base {

    public function add(mt_delay_wappush_data $mt_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_delay)));

        $spring_config = loader_config::getInstance()->getConfig('spring');
        $sql = sprintf("
						INSERT INTO vivo_wappush (
							adn, msisdn, wapsite_id, token, code, price, `date`, status
						) VALUES (
							'%s', '%s', '%s', '%s', '%s', '%s', now(), '%s'
						)", mysql_real_escape_string($mt_delay->adn), mysql_real_escape_string($mt_delay->msisdn), mysql_real_escape_string($mt_delay->wapsiteId), mysql_real_escape_string($mt_delay->token), mysql_real_escape_string($mt_delay->code), mysql_real_escape_string($mt_delay->price), mysql_real_escape_string($mt_delay->status)
        );
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(mt_delay_wappush_data $mt_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_delay)));

        $sql = sprintf("UPDATE vivo_wappush SET status = '%s' WHERE id = '%s'", mysql_real_escape_string($mt_delay->status), mysql_real_escape_string($mt_delay->id));
        return $this->databaseObj->query($sql);
    }

    public function get(mt_delay_wappush_data $mt_delay) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_delay)));

        $sql = sprintf("SELECT * FROM vivo_wappush WHERE msisdn = '%s' AND status = '0' ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($mt_delay->msisdn));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0)
            return $result[0];
        else
            return false;
    }

}