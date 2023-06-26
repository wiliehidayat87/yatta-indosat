<?php

class model_cdr extends model_base {

    /**
     * 
     * @param
     * $drData->msgIndex
      $drData->operatorId
      $drData->chargingId
      $drData->statusText
      $drData->statusCode
      $drData->statusInternal
      $drData->cdrHour
     * 
     *
     */
    public function create(dr_data $obj) {
    	
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($obj)));
        $date = date("Ymd");
	$minutes = date("i");

	if ( $minutes == '00' ) {
          $this->generateTable($date);
        };

        $sql = sprintf("
            INSERT INTO cdr_%1\$s
                (msg_index, operator_id, charging_id, status_text, status_code, status_internal, cdr_hour, date_created)
                VALUES
                ('%2\$s', '%3\$d', '%4\$s', '%5\$s', '%6\$s', '%7\$s', '%8\$d', NOW())
            ON DUPLICATE KEY
                UPDATE 
                    charging_id = '%4\$s', 
                    status_text = '%5\$s', 
                    status_code = '%6\$s', 
                    status_internal = '%7\$s', 
                    cdr_hour = '%8\$d'
            ", date('Ymd'), mysql_real_escape_string($obj->msgId), mysql_real_escape_string($obj->operatorId), mysql_real_escape_string($obj->charging->chargingId), mysql_real_escape_string($obj->statusText), mysql_real_escape_string($obj->statusCode), mysql_real_escape_string($obj->statusInternal), mysql_real_escape_string($obj->cdrHour));
            //print_r($obj);
		
        return $this->databaseObj->query($sql);
    }

    /**
     * @param
     * $date Ymd ex, 20110304
     *
     */
    public function generateTable($date) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $date));
        $sql = sprintf("
                CREATE TABLE IF NOT EXISTS `cdr_%s` (
                `msg_index` varchar(50) NOT NULL,
                 `operator_id` int(11) unsigned NOT NULL,
                 `charging_id` int(11) unsigned NOT NULL,
                 `status_text` varchar(30) NOT NULL,
                 `status_code` int(10) NOT NULL,
                 `status_internal` varchar(15) NOT NULL,
                 `cdr_hour` tinyint(2) NOT NULL,
                 `date_created` datetime NOT NULL,
                 UNIQUE KEY `msg_index` (`msg_index`,`operator_id`),
        		 KEY `cdr_hour` (`cdr_hour`)
                ) ENGINE=InnoDB row_format=compressed key_block_size=4;", mysql_real_escape_string($date));
        $log->write(array('level' => 'debug', 'message' => "No tables yet, creating cdr_" . $date . ' with Query : ' . str_replace(array("\r\t", "\t", "\r"), "", $sql)));
        return $this->databaseObj->query($sql);
    }

    /**
     * @param
     * array
     * -q : from hour
     * -w : to hour
     * -c : conn DB used to updating
     * -f : from database.table
     * -t : to database.table
     *
     */
    public function updateTransact($arr) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($arr)));

        $sql = sprintf("
                    UPDATE 
                        %s dest, %s src 
                    SET 
                        dest.msgstatus = src.status_internal,
                        dest.closereason = src.status_code
                    WHERE 
                        dest.msgindex = src.msg_index AND
                        dest.operatorid = src.operator_id AND
                        src.cdr_hour >= '%d' AND 
                        src.cdr_hour <= '%d'", mysql_real_escape_string($arr ['t']), mysql_real_escape_string($arr ['f']), mysql_real_escape_string($arr ['w']), mysql_real_escape_string($arr ['q']));

        echo $sql;
        return $this->databaseObj->query($sql);
        //print_r($arr);
		
    }

}
