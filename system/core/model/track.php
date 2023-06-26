<?php

class model_track extends model_base {

    public function setTrack($operator, $msisdn, $service, $trackcode, $reg) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Set Track Code to DB"));
        $sql = sprintf("INSERT INTO track_storage (operator, msisdn, service, trackcode, reg, lastupdate) VALUES('%s','%s','%s','%s','%s',now()) 
ON DUPLICATE KEY UPDATE operator = '%s', msisdn = '%s', service = '%s', trackcode = '%s', reg = '%s', lastupdate = now()",mysql_real_escape_string($operator),mysql_real_escape_string($msisdn),mysql_real_escape_string($service),mysql_real_escape_string($trackcode),mysql_real_escape_string($reg),mysql_real_escape_string($operator),mysql_real_escape_string($msisdn),mysql_real_escape_string($service),mysql_real_escape_string($trackcode),mysql_real_escape_string($reg));
	$query = $this->databaseObj->query($sql);
        
	if ($query) {
            return $this->databaseObj->last_insert_id();
        } else {
	    return false;
	}
    }
/*
    public function getPixel($operator,$partner) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start get Pixel in DB"));
	
        $sql = sprintf("SELECT pixel FROM pixel_storage WHERE operator = '%s' AND partner = '%s' AND date_time > NOW() - INTERVAL 3 HOUR  AND is_used = 0 ORDER BY RAND() LIMIT 1",mysql_real_escape_string($operator),mysql_real_escape_string($partner));
	
	$query = $this->databaseObj->fetch($sql);
	
        if (count($query)>0) {
            //$this->updatePixel($query[0]['pixel']);
			$sql = sprintf("UPDATE pixel_storage set is_used = 1 where id = '%s'",mysql_real_escape_string($id));
			$this->databaseObj->query($sql);
            return $query[0]['pixel'];
        } else {
  	   return false;
	}
    }

    public function showPixel($id){
	$log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start show Pixel in DB"));

        $sql = "SELECT pixel FROM pixel_storage WHERE is_used = 0 and id = ".$id;

        $query = $this->databaseObj->fetch($sql);

        if (count($query)>0) {
            //$this->updatePixel($query[0]['pixel']);
			$sql = sprintf("UPDATE pixel_storage set is_used = 1 where id = '%s'",mysql_real_escape_string($id));
			$this->databaseObj->query($sql);
            return $query[0]['pixel'];
        } else {
           return false;
        }
    }
	
    public function updatePixel($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Update Pixel to DB"));

        $sql = sprintf("UPDATE pixel_storage set is_used = 1 where pixel = '%s'",mysql_real_escape_string($data));
	$query = $this->databaseObj->query($sql);
        //$query = false;
	if ($query) {
            return true;
        } else {
 	    return false;
	}
	}
	
	public function checkMO($msisdn,$msgdata) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Check MO to DB"));

        $sql = sprintf("SELECT * FROM xmp.tbl_msgtransact WHERE msisdn = '%s' AND msgdata = '%s' AND DATE(msgtimestamp) = CURRENT_DATE",mysql_real_escape_string($msisdn),mysql_real_escape_string($msgdata));
		$query = $this->databaseObj->fetch($sql);
        if (count($query)>1) {
            return false;
        } else {
			return true;
		}
    }
*/
}

?>

