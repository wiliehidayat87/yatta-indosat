<?php

class model_partner extends model_base {

    public function get($partner_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($partner_data)));

        $sql = sprintf("select * from spring_partner where service = '%s' and type = '%s' limit 1", mysql_real_escape_string($partner_data->service), mysql_real_escape_string($partner_data->type));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result [0];
        } else {
            return false;
        }
    }

}