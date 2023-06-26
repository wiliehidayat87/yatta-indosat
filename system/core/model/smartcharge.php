<?php

class model_smartcharge extends model_base {
	
	public function get_next_smartcharging($data) {
		$sql = sprintf("
			SELECT * FROM charging
			WHERE
			adn = '%s' and
			charging_id = '%s' and
			message_type = '%s' 
		",
			mysql_real_escape_string($data->adn),
			mysql_real_escape_string($data->serviceId),
			mysql_real_escape_string($data->type)
		);
		
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "get_next_smartcharging : " . $sql));
				
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;
        		
	}
	
	public function read_smartcharge_mt($data) {
			
		$sql = sprintf("
			SELECT * FROM smartcharge_transact
			WHERE
			msisdn = '%s' and
			tid = '%s' and
			sid = '%s' and
			next_sid = '%s' and
			status = %d 
		",
			mysql_real_escape_string($data->msisdn),
			mysql_real_escape_string($data->tid),
			mysql_real_escape_string($data->sid),
			mysql_real_escape_string($data->next_sid),
			mysql_real_escape_string($data->status)
		);
		
		$log = manager_logging::getInstance ();
		//$log->write ( array ('level' => 'debug', 'message' => "wi_info_read : " . serialize ( $data ) ) );
		$log->write ( array ('level' => 'debug', 'message' => "smartcharge_read : " . $sql));
				
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;
        		
	}
	
	public function update_smartcharging_mt($id, $numberofattempt) {
		
		$sql = sprintf("
			UPDATE smartcharge_transact
			SET attempt = %d, modified = now()
			WHERE id = %d
		",
			mysql_real_escape_string($numberofattempt),
			mysql_real_escape_string($id)
		);
		
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "smartcharge_save : " . $sql ) );		
		
		$query = $this->databaseObj->query($sql);
		return true;
		
	}
	
	public function save_smartcharge_mt($data) {
				
		$sql = sprintf("
			INSERT INTO smartcharge_transact (
				sid,
				next_sid,
				msisdn,
				tid,
				data,
				service,
				operator_id,
				status,
				subject,
				created,
				modified
			) VALUES (
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
				'%s',
				now(),
				now()
			)
			",
			mysql_real_escape_string ( $data->sid ),
			mysql_real_escape_string ( $data->next_sid ),
			mysql_real_escape_string ( $data->msisdn),
			mysql_real_escape_string ( $data->tid ),
			mysql_real_escape_string ( $data->data ),
			mysql_real_escape_string ( $data->service),
			mysql_real_escape_string ( $data->operator_id ),
			mysql_real_escape_string ( $data->status ),
			mysql_real_escape_string ( $data->subject )
		);
		
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "smartcharge_save : " . $sql ) );		
		
		$query = $this->databaseObj->query($sql);
		return $this->databaseObj->last_insert_id();
	}
	
	public function isSmartCharge($mt) {
		
		$operator_id = $mt->operatorId;
		$sid = $mt->serviceId;
		$service = $mt->service;
		
		$sql = sprintf ( "
			SELECT sc . * , s.name
			FROM xmp.smartcharge_sid sc
			LEFT JOIN xmp.service s ON sc.service = s.id
			WHERE s.name = '%s' AND
			sc.sid = '%s' AND
			sc.operator_id = '%d'
			", 
			mysql_real_escape_string ( $service ), 
			mysql_real_escape_string ( $sid ), 
			mysql_real_escape_string ( $operator_id ) 
		);
		
		$log = manager_logging::getInstance ();
		//$log->write ( array ('level' => 'debug', 'message' => "smart : " . serialize ( $data ) ) );
		$log->write ( array ('level' => 'debug', 'message' => "smartcharge_check : " . $sql));
		
		$data = $this->databaseObj->fetch ( $sql );
		if (count ( $data ) > 0)
			return $data [0];
		else
			return false;
	
	}

}

?>
