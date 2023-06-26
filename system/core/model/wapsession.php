<?php

class model_wapsession extends model_base {

    public function read(model_data_wapsession $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $config_wap = loader_config::getInstance()->getConfig('wap');
        $sql = sprintf("
            SELECT 
                *
            FROM 
                wap_session ws 
            WHERE 
                ws.Token = '%s' 
            AND 
            	ws.DateCreated > DATE_ADD(NOW(), INTERVAL -%d DAY) LIMIT 1
            ", mysql_real_escape_string($data->token), $config_wap->sessionExpired);
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data [0];
        } else {
            return false;
        }
    }

    public function readSiteSession(model_data_wapsession $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $config_wap = loader_config::getInstance()->getConfig('wap');
        $sql = sprintf("
            SELECT 
                a.*,b.Name wap_site
            FROM 
                wap_session a
                INNER JOIN wap_site b ON a.SiteId = b.ID
            WHERE 
                a.Token = '%s' 
            AND 
            	a.DateCreated > DATE_ADD(NOW(), INTERVAL -%d DAY) LIMIT 1
            ", mysql_real_escape_string($data->token), $config_wap->sessionExpired);
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data [0];
        } else {
            return false;
        }
    }

}