<?php

class model_hmo extends model_base {

    public function save($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("insert into hmo values(NULL,'%s','%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($data->hset_id),mysql_real_escape_string($data->msisdn),mysql_real_escape_string($data->hash),mysql_real_escape_string($data->date_send),mysql_real_escape_string($data->time_send),mysql_real_escape_string($data->status),mysql_real_escape_string($data->closereason));
	$query = $this->databaseObj->query($sql);
        if ($query) {
            return $this->databaseObj->last_insert_id();
        } else {
		return false;
	}
    }

    public function isUnique($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT id FROM hmo where hset_id='%s' and hash='%s' limit 1",mysql_real_escape_string($data->hset_id),mysql_real_escape_string($data->hash));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return false;
        } else {
            return true;
        }
    }
    
    public function isUniqueChecker($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT ID FROM hmo 
        			WHERE hset_id='%s' 
					AND hash='%s' 
					AND date(date_send) = '%s' 
					AND msisdn = '%s' 
					LIMIT 1",
        	mysql_real_escape_string($data->hset_id),
        	mysql_real_escape_string($data->hash),
        	mysql_real_escape_string($data->date_send),
        	mysql_real_escape_string($data->msisdn));
        	
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function getLastSend() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT hmo.id FROM hset join hmo where hset.keyword='%s' and hmo.status=1 order by hmo.id desc");
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    public function countLastRow($id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $sql = sprintf("SELECT id from hmo where hset_id = '%s' order by id desc",mysql_real_escape_string($id));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return count($data);
        } else {
            return false;
        }
    }
}

?>
