<?php

class model_traffic extends model_base {

    public function saveMoToRptMO($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_data)));

        $sql = sprintf("
			INSERT INTO 
				reports.rpt_mo(
					msisdn, 
					operator, 
					adn, 
					sms, 
					rawsms, 
					service, 
					req_type, 
					channel, 
					mo_date,
					partner
					) 
			VALUES (
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s',
				'%s'
				)", 
        		mysql_real_escape_string($mo_data->msisdn), 
        		mysql_real_escape_string($mo_data->operatorId), 
        		mysql_real_escape_string($mo_data->adn), 
        		mysql_real_escape_string($mo_data->msgData), 
        		mysql_real_escape_string($mo_data->rawSMS), 
        		mysql_real_escape_string($mo_data->service), 
        		mysql_real_escape_string($mo_data->requestType), 
        		mysql_real_escape_string($mo_data->channel), 
        		mysql_real_escape_string($mo_data->incomingDate), 
        		mysql_real_escape_string($mo_data->partner)
        );

        $query = $this->databaseObj->query($sql);
        if ($query) {
            return (bool) $this->databaseObj->numRows;
        } else {
            $config_retry = loader_config::getInstance()->getConfig('retry');
            
            $filename = uniqid() . ".sql";
            $path = $config_retry->bufferPathMysql . "/" . $filename;

            $retry = loader_data::get('retry');
            $retry->profile = $this->databaseObj->connProfile['database'];
            $retry->query = $sql;

            $buffer = buffer_file::getInstance();

            return $buffer->save($path, $retry);
        }
    }

}

