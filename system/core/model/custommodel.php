<?php

class model_custommodel extends model_base {

	public function getPULLTransact($p) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($p)));
		$sql = sprintf("SELECT MSGDATA, SUBJECT, SERVICE, ADN FROM tbl_msgtransact WHERE msisdn = '%s' AND msgindex = '%s' ORDER BY id ASC LIMIT 1;", mysql_real_escape_string($p->msisdn), mysql_real_escape_string($p->msgId));
		
		$result = $this->databaseObj->fetch($sql);
		if (count($result) > 0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function getREGTransact($p) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($p)));
		
		$config_db = loader_config::getInstance()->getConfig('database');
		$dbname = $config_db->profile['connDatabase1']['database'];
		
		$sql = sprintf("SELECT MSGINDEX, MSGDATA, SUBJECT, SERVICE, ADN, MSGSTATUS, CLOSEREASON FROM {$dbname}.tbl_msgtransact WHERE msisdn = '%s' AND msgindex = '%s' ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($p->msisdn), mysql_real_escape_string($p->msgId));
		
		$result = $this->databaseObj->fetch($sql);
		if (count($result) > 0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function getDataTransact($p) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($p)));
		
		$config_db = loader_config::getInstance()->getConfig('database');
		$dbname = $config_db->profile['connDatabase1']['database'];
		
		$sql = sprintf("SELECT MSGINDEX, MSGDATA, SUBJECT, SERVICE, ADN, MSGSTATUS, CLOSEREASON FROM {$dbname}.tbl_msgtransact WHERE msisdn = '%s' AND msgindex = '%s' ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($p->msisdn), mysql_real_escape_string($p->msgId));
		
		$result = $this->databaseObj->fetch($sql);
		if (count($result) > 0) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function getSubject($data) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));
		
		$config_db = loader_config::getInstance()->getConfig('database');
		$dbname = $config_db->profile['connDatabase1']['database'];
		
		$sql = sprintf("SELECT * FROM {$dbname}.msisdn_subject WHERE msisdn = '%s' AND operator = '%s' AND service = '%s' LIMIT 1;", mysql_real_escape_string($data['msisdn']), mysql_real_escape_string($data['operator']), mysql_real_escape_string($data['service']));
		
		$result = $this->databaseObj->fetch($sql);
		if (count($result) > 0) {
			return $result;
		} else {
			return array();
		}
	}
}
