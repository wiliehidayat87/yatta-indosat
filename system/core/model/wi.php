<?php

class model_wi extends model_base {
	
	public function save_wi_info($data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_info_save : " . serialize ( $data ) ) );

		$sql = sprintf(
		"INSERT INTO wap_installment (
			`reqid`,
			`first_installment`, 
			`next_installment`,
			`first_installment_date`, 
			`last_installment_date`,
			`next_installment_date`,
			`installment_count`, 
			`service_code`, 
			`content_code`, 
			`content_price`, 
			`query_string`, 
			`msisdn`, 
			`operator_id`, 
			`service_id`, 
			`date_created`, 
			`date_modified`		
		 ) VALUES (
			'%s',
			'%s',
			'%s',
			'%s', 
			'%s',
			'%s',
			'%d', 
			'%s', 
			'%s', 
			'%d', 
			'%s', 
			'%s', 
			'%d', 
			'%s', 
			'%s', 
			'%s'		
		 )",
			 mysql_real_escape_string($data->reqid),
			 mysql_real_escape_string($data->first_installment),
			 mysql_real_escape_string($data->next_installment),
			 mysql_real_escape_string($data->first_installment_date),
			 mysql_real_escape_string($data->next_installment_date),
			 mysql_real_escape_string($data->last_installment_date),
			 mysql_real_escape_string($data->installment_count),
			 mysql_real_escape_string($data->service_code),
			 mysql_real_escape_string($data->content_code),
			 mysql_real_escape_string($data->content_price),
			 mysql_real_escape_string($data->query_string),
			 mysql_real_escape_string($data->msisdn),
			 mysql_real_escape_string($data->operator_id),
			 mysql_real_escape_string($data->service_id),
			 mysql_real_escape_string($data->date_created),
			 mysql_real_escape_string($data->date_modified)
		);

		//$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
		 
		$query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
		 
	}
	
	public function read_wi_info($data) {
		
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_info_read : " . serialize ( $data ) ) );

		$sql = sprintf("
			SELECT * FROM wap_installment
			WHERE 
			reqid = '%s' and 
			msisdn = '%s' and 
			content_code = '%s'   
		",
			mysql_real_escape_string($data->reqid),
			mysql_real_escape_string($data->msisdn),
			mysql_real_escape_string($data->content_code)
		);
		
		//$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;
	}

	public function save_wi_tracker($data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_tracker_save : " . serialize ( $data ) ) );

		$sql = sprintf("
		INSERT INTO wap_installment_tracker (
			`reqid`,
			`msisdn`,
			`status`,
			`trxid`,
			`instid`,
			`created`,
			`operator_id`,			
			`atype`,
			`scode`,
			`ccode`,
			`inst_no`
		) VALUES (
			'%s',
			'%s',
			'%d',
			'%s',
			'%s',
			now(),
			%d,
			'%s',
			'%s',
			'%s',
			%d
		)
		",
			mysql_real_escape_string($data->reqid),
			mysql_real_escape_string($data->msisdn),
			mysql_real_escape_string($data->status),
			mysql_real_escape_string($data->trxid),
			mysql_real_escape_string($data->instid),
			mysql_real_escape_string($data->operator_id),
			mysql_real_escape_string($data->atype),
			mysql_real_escape_string($data->scode),
			mysql_real_escape_string($data->ccode),
			mysql_real_escape_string($data->inst_no)					
		);
		//$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
		 
		$query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
	}
	
	public function update_wi_info($data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_info_update : " . serialize ( $data ) ) );

		$sql = sprintf("UPDATE wap_installment SET instid = '%s' WHERE reqid = '%s'",
			 mysql_real_escape_string($data->instid),
			 mysql_real_escape_string($data->reqid)
		);
		
        $this->databaseObj->query($sql);
        return true;
	}
	
	public function save_wi_trans($data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_save_trans : " . serialize ( $data ) ) );
		
		$sql = sprintf("INSERT INTO xmp.tbl_msgtransact (
                IN_REPLY_TO, 
                MSGINDEX, 
                MSGTIMESTAMP, 
                ADN, 
                MSISDN, 
                OPERATORID, 
                MSGDATA, 
                MSGLASTSTATUS, 
                MSGSTATUS, 
                CLOSEREASON, 
                SERVICEID, 
                MEDIA, 
                CHANNEL, 
                SERVICE, 
                PARTNER, 
                SUBJECT, 
                PRICE 
            ) VALUES (
                '%s', 
                '%s', 
                '%s', 
                '%s', 
                '%s', 
                '%d', 
                '%s', 
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
            mysql_real_escape_string($data->in_reply_to),
            mysql_real_escape_string($data->msgindex), 
            mysql_real_escape_string($data->msgtimestamp), 
            mysql_real_escape_string($data->adn), 
            mysql_real_escape_string($data->msisdn), 
            mysql_real_escape_string($data->operatorid), 
            mysql_real_escape_string($data->msgdata), 
            mysql_real_escape_string($data->msglaststatus), 
            mysql_real_escape_string($data->msgstatus), 
            mysql_real_escape_string($data->closereason), 
            mysql_real_escape_string($data->serviceid), 
            mysql_real_escape_string($data->media), 
            mysql_real_escape_string($data->channel),
            mysql_real_escape_string($data->service),  
            mysql_real_escape_string($data->partner), 
            mysql_real_escape_string($data->subject), 
            mysql_real_escape_string($data->price) 
		);

		$query = $this->databaseObj->query($sql);
		return $this->databaseObj->last_insert_id();
	}

	public function read_wi_instid($data) {
		
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "wi_instid_read : " . serialize ( $data ) ) );

		$sql = sprintf("
			SELECT * FROM wap_installment
			WHERE 
			instid = '%s' and 
			service_code = '%s'   
		",
			mysql_real_escape_string($data->instid),
			mysql_real_escape_string($data->service_code)
		);
		
		//$log->write ( array ('level' => 'debug', 'message' => "SQL : " . $sql));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) return $data[0]; else return false;
	}	
}

