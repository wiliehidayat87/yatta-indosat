<?php

class model_hset extends model_base {
/*
        public $counter;
        public $active;
        public $keyword;
        public $adn;
        public $operator_name;
        public $handler;
*/
	public function getHset($mt_data) {
	        $log = manager_logging::getInstance();
        	$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        	$sql = sprintf("SELECT * FROM hset WHERE keyword='%s' and service= '%s' AND adn = '%s' AND operator_name = '%s' LIMIT 1", 
			mysql_real_escape_string($mt_data->keyword), mysql_real_escape_string($mt_data->service), mysql_real_escape_string($mt_data->adn), mysql_real_escape_string($mt_data->operator_name));
//exit($sql);
        	$result = $this->databaseObj->fetch($sql);
	        if (count($result) > 0) {
        	    return $result[0];
	        } else {
        	    return false;
	        }	
	}
	
	public function update($data) {
		$log = manager_logging::getInstance();
                $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));
		$sql = sprintf("update hset set inc='%s' WHERE id='%s' LIMIT 1",
                        mysql_real_escape_string($data->inc), mysql_real_escape_string($data->id));
		$this->databaseObj->query($sql);
        	return $this->databaseObj->numRows;
	}
}

?>
