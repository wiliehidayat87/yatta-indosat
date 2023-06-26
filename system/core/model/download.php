<?php

class model_download extends model_base {

    public function getSite(content_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("SELECT ID FROM wap_site 
                    WHERE 
                    Service = '%s' AND
                    Adn = '%s' AND
                    Name = '%s' AND
                    Status = '1' LIMIT 1", mysql_real_escape_string($data->userObj->service), mysql_real_escape_string($data->userObj->adn), mysql_real_escape_string($data->wapName));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result [0] ['ID'];
        } else {
            return false;
        }
    }

    public function set(content_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("
		INSERT INTO wap_session
			(Token, InitialChargeType, SiteID, Service, Operator, Msisdn, `Status`, `Limit`, DateCreated, DateModified)
		VALUES
			('%s', '%s', '%s', '%s', '%s', '%s', '1', '%s', now(), now())
		", mysql_real_escape_string($data->token), mysql_real_escape_string($data->initialChargeType), mysql_real_escape_string($data->wapId), mysql_real_escape_string($data->userObj->service), mysql_real_escape_string($data->userObj->operator), mysql_real_escape_string($data->userObj->msisdn), mysql_real_escape_string($data->limit));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function get($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = "select * from download_session WHERE ";

        if (!empty($data->token))
            $sql .= " token = '" . mysql_real_escape_string($data->token) . "' ";

        if (!empty($data->service))
            $sql .= " AND service = '" . mysql_real_escape_string($data->service) . "' ";

        if (!empty($data->id))
            $sql .= " AND id = '" . mysql_real_escape_string($data->id) . "' ";

        $sql = ltrim($sql, "AND");

        $sql .= " LIMIT 1";

        $download_data = $this->databaseObj->fetch($sql);

        if (count($download_data) > 0) {
            return $download_data [0];
        } else {
            return false;
        }
    }

    public function getLog($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("SELECT count( * ) as total FROM `wap_download_log` WHERE `SessionID` ='%s'", mysql_real_escape_string($data->id));

        $total = $this->databaseObj->fetch($sql);
        if (count($total) > 0) {
            return count($total);
        } else {
            return false;
        }
    }

    public function setlog(wap_download_log_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("INSERT INTO wap_download_log
                    (`ID`, `ProtocolID`, `SessionID`, `Price`, `ContentCode`, `ChargeType`, `status_charging`, `DateCreated`) 
                    VALUES (NULL,'%s','%s','%s','%s','%s','%s',NOW())", mysql_real_escape_string($data->protocolId), mysql_real_escape_string($data->sessionId), mysql_real_escape_string($data->price), mysql_real_escape_string($data->contentCode), mysql_real_escape_string($data->chargeType), mysql_real_escape_string($data->statusCharging));

        $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

}