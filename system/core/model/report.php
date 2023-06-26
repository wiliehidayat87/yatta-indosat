<?php

class model_report extends model_base {

    public function saveSummary($array, summarizer_data $summarizer) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        try {
            $this->databaseObj->query("SET autocommit=0");
            $this->databaseObj->query("START TRANSACTION");
	    if(count($array) > 0){
            foreach ($array as $data) {
                $sql = sprintf("
                            INSERT INTO %1\$s
                                    (sumdate, adn, operator, partner, service, subject, charging_id, gross, msgstatus, total, lastupdate)
                            VALUES
                                    ('%2\$s', '%3\$s', '%4\$s', '%5\$s', '%6\$s', '%7\$s', '%8\$s', '%9\$s', '%10\$s', '%11\$s', NOW())
                            ON DUPLICATE KEY
                                UPDATE
                                    total = '%11\$s'
                                ", mysql_real_escape_string($summarizer->tableTo), mysql_real_escape_string($data['sumdate']), mysql_real_escape_string($data['adn']), mysql_real_escape_string($data['operatorid']), mysql_real_escape_string($data['partner']), mysql_real_escape_string($data['service']), mysql_real_escape_string($data['subject']), mysql_real_escape_string($data['serviceid']), mysql_real_escape_string($data['price']), mysql_real_escape_string($data['msgstatus']), mysql_real_escape_string($data['total'])
                );
                $this->databaseObj->query($sql);
            }
            $this->databaseObj->query("COMMIT");
	    }
        } catch (Exception $e) {
            //echo $e->getMessage();
            $this->databaseObj->query("ROLLBACK");
        }
        return true;
    }

    public function saveSummaryUser($array, summarizer_data $summarizer) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        try {
            $this->databaseObj->query("SET autocommit=0");
            $this->databaseObj->query("START TRANSACTION");
            foreach ($array as $data) {
                $sql = sprintf("
                            INSERT INTO %s
                                    (date_subscribed, adn, service, channel, operator, total, date_created)
                            VALUES
                                    ('%s', '%s', '%s', '%s', '%s', '%s', NOW())", mysql_real_escape_string($summarizer->tableTo), mysql_real_escape_string($data['date_subscribed']), mysql_real_escape_string($data['adn']), mysql_real_escape_string($data['service']), mysql_real_escape_string($data['channel']), mysql_real_escape_string($data['operator']), mysql_real_escape_string($data['total'])
                );
                $this->databaseObj->query($sql);
            }
            $this->databaseObj->query("COMMIT");
        } catch (Exception $e) {
            //echo $e->getMessage();
            $this->databaseObj->query("ROLLBACK");
        }
        return true;
    }

	//2014-06-25
    public function saveLelangSummary($array, summarizer_data $summarizer) {
    	    	
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        try {
            $this->databaseObj->query("SET autocommit=0");
            $this->databaseObj->query("START TRANSACTION");
	    if(count($array) > 0){
            foreach ($array as $data) {
                $sql = sprintf("
                            INSERT INTO %1\$s
                                    (operatorid, msisdn, service, adn, msg_date, price,total,msgstatus)
                            VALUES
                                    ('%2\$s', '%3\$s', '%4\$s', '%5\$s', '%6\$s', '%7\$s', '%8\$s', '%9\$s')
                            ON DUPLICATE KEY
                                UPDATE
                                    total = '%10\$s'
                                ", mysql_real_escape_string($summarizer->tableTo), mysql_real_escape_string($data['operatorid']), 
								mysql_real_escape_string($data['msisdn']), mysql_real_escape_string($data['service']), 
								mysql_real_escape_string($data['adn']), mysql_real_escape_string($data['msg_date']), 
								mysql_real_escape_string($data['price']), mysql_real_escape_string($data['total']), 
								mysql_real_escape_string($data['msgstatus']), mysql_real_escape_string($data['total'])
                );
				echo($sql);
                $this->databaseObj->query($sql);
            }
            $this->databaseObj->query("COMMIT");
	    }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->databaseObj->query("ROLLBACK");
        }
        return true;
    }
}
