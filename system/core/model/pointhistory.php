<?php

class model_pointhistory extends model_base {

        public function insertPointHistory($msisdn,$service,$msgindex) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $msisdn));

        $sql = sprintf("INSERT INTO service_point_history(point, point_date, point_time,msisdn,service,msgindex) VALUES('1', NOW(), NOW(),'%s','%s','%s')", mysql_real_escape_string($msisdn),mysql_real_escape_string($service),mysql_real_escape_string($msgindex));
        $this->databaseObj->query($sql);
        return true;
    }
}

?>
