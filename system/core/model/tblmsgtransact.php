<?php

class model_tblmsgtransact extends model_base {

    public function saveMO($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_data)));

        $sql = sprintf("
            INSERT INTO xmp.tbl_msgtransact (
                IN_REPLY_TO, MSGINDEX, MSGTIMESTAMP, ADN, MSISDN, OPERATORID, MSGDATA, MSGLASTSTATUS, 
                MSGSTATUS, CLOSEREASON, SERVICEID, MEDIA, CHANNEL, SERVICE, PARTNER, SUBJECT, PRICE, ISR 
            ) VALUES (
                %d , '%s', '%s', '%s', '%s', '%d', '%s', NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, 0
            )", mysql_real_escape_string($mo_data->inReply), mysql_real_escape_string($mo_data->msgId), mysql_real_escape_string($mo_data->incomingDate), mysql_real_escape_string($mo_data->adn), mysql_real_escape_string($mo_data->msisdn), mysql_real_escape_string($mo_data->operatorId), mysql_real_escape_string($mo_data->msgData), mysql_real_escape_string($mo_data->msgStatus), mysql_real_escape_string($mo_data->closeReason), mysql_real_escape_string($mo_data->serviceId), mysql_real_escape_string($mo_data->media), mysql_real_escape_string($mo_data->channel), mysql_real_escape_string($mo_data->service), mysql_real_escape_string($mo_data->partner), mysql_real_escape_string($mo_data->subject), mysql_real_escape_string($mo_data->price));
        $this->databaseObj->set_charset();

        $query = $this->databaseObj->query($sql);
        if ($query) {
            return $this->databaseObj->last_insert_id();
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

    public function saveMT($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $sql = sprintf("
            INSERT INTO xmp.tbl_msgtransact (
                IN_REPLY_TO, MSGINDEX, MSGTIMESTAMP, ADN, MSISDN, OPERATORID, MSGDATA, MSGLASTSTATUS, 
                MSGSTATUS, CLOSEREASON, SERVICEID, MEDIA, CHANNEL, SERVICE, PARTNER, SUBJECT, PRICE, ISR 
            ) VALUES (
                %d, '%s', NOW(), '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, '0'
            )", mysql_real_escape_string($mt_data->inReply), mysql_real_escape_string($mt_data->msgId), mysql_real_escape_string($mt_data->adn), mysql_real_escape_string($mt_data->msisdn), mysql_real_escape_string($mt_data->operatorId), mysql_real_escape_string($mt_data->msgData), mysql_real_escape_string($mt_data->msgLastStatus), mysql_real_escape_string($mt_data->msgStatus), mysql_real_escape_string($mt_data->closeReason), mysql_real_escape_string($mt_data->serviceId), mysql_real_escape_string($mt_data->media), mysql_real_escape_string($mt_data->channel), mysql_real_escape_string($mt_data->service), mysql_real_escape_string($mt_data->partner), mysql_real_escape_string($mt_data->subject), mysql_real_escape_string($mt_data->price));
        $this->databaseObj->set_charset();
        $query = $this->databaseObj->query($sql);
        if ($query) {
            return $this->databaseObj->last_insert_id();
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

    public function setStatus($mt_data) {
	//error_log(date('Ymd').print_r($mt_data,1)."\n",3,'/tmp/proxl_dr_delivered.log');
        $log = manager_logging::getInstance();
        //$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));
	$serviceid = (!empty($mt_data->serviceId)) ? "AND serviceid ='". $mt_data->serviceId ."'" : '';
	//error_log(date('Ymd His')." ".print_r($mt_data,1)." ",3,"/tmp/mt");
        $sql = sprintf("UPDATE 
		xmp.tbl_msgtransact 
		SET msgstatus = '%s', 
		closereason = '%s' 
		WHERE msgindex = '%s' 
		AND adn = '%s' 
		AND msisdn = '%s' 
		%s ORDER BY id desc 
		LIMIT 1", mysql_real_escape_string($mt_data->status), mysql_real_escape_string($mt_data->closeReason), mysql_real_escape_string($mt_data->msgId), mysql_real_escape_string($mt_data->adn), mysql_real_escape_string($mt_data->msisdn),$serviceid);
        $this->databaseObj->set_charset();
        $this->databaseObj->query($sql);
        return $this->databaseObj->numRows;
    }

    public function get($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $sql = sprintf("SELECT * 
		FROM tbl_msgtransact 
		WHERE MSGINDEX = '%s' 
		AND adn = '%s'
		AND msisdn = '%s' 
		LIMIT 1", mysql_real_escape_string($mt_data->msgId), mysql_real_escape_string($mt_data->adn), mysql_real_escape_string($mt_data->msisdn));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getDRTransact($mt_data) {
        $log = manager_logging::getInstance();
        //$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $sql = sprintf("SELECT a.*, b.id chargingId, b.sender_type 
		FROM xmp.tbl_msgtransact a
                INNER JOIN xmp.charging b ON a.serviceid = b.charging_id
		WHERE a.msgindex = '%s' 
		AND a.adn = '%s'
		AND a.msisdn = '%s' 
		ORDER BY a.id DESC LIMIT 1", mysql_real_escape_string($mt_data->msgId), mysql_real_escape_string($mt_data->adn), mysql_real_escape_string($mt_data->msisdn));
	//	echo $sql;
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getSummary(summarizer_data $summarizer) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($summarizer)));

        $config_main = loader_config::getInstance()->getConfig('main');
        $operator_name = $config_main->operator;
        $model_operator = loader_model::getInstance()->load('operator', 'connDatabase1');
        $operator = $model_operator->getOperatorId($operator_name);

        $sql = sprintf("
                SELECT 
                        DATE(msgtimestamp) AS sumdate,
                        adn,
                        operatorid,
                        serviceid,
                        price,
                        partner,
                        service,
                        subject,
                        msgstatus,
                        count(*) as total
                FROM
                        %s
                WHERE
                        msgtimestamp between '%s 00:00:00' AND '%s 23:59:59' AND operatorid='%s'
                GROUP BY
                        DATE(msgtimestamp), subject, operatorid, partner, service, price, msgstatus
                ORDER BY
                        DATE(msgtimestamp), subject, operatorid, partner, service, price, msgstatus", mysql_real_escape_string($summarizer->tableFrom), mysql_real_escape_string($summarizer->date), mysql_real_escape_string($summarizer->date), mysql_real_escape_string($operator));
	//exit($sql);	
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function getByMsgId($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $sql = sprintf("SELECT * 
		FROM tbl_msgtransact 
		WHERE MSGINDEX = '%s' 
		LIMIT 1", mysql_real_escape_string($mt_data->msgId));
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

    public function getFirstDateRecord() {
        //$log = manager_logging::getInstance();
        //$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $sql = "SELECT date(msgtimestamp) as tgl FROM tbl_msgtransact order by id asc LIMIT 1";
        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result[0];
        } else {
            return false;
        }
    }

	//2014-06-25	
	public function getLelangSummary(summarizer_data $summarizer) {
	        $log = manager_logging::getInstance();
        	$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($summarizer)));

	        $config_main = loader_config::getInstance()->getConfig('main');
        	$operator_name = $config_main->operator;
	        $model_operator = loader_model::getInstance()->load('operator', 'connDatabase1');
        	$operator = $model_operator->getOperatorId($operator_name);

	        $sql = sprintf("
                SELECT 
                        b.msgdata,b.msisdn,b.service,b.adn,
						b.operatorid,
						DATE(a.msgtimestamp) as msg_date,a.msgstatus,
						count(1) as total,substring_index(b.msgdata,' ',-1) as price
                from %s a
				join %s b ON a.in_reply_to=b.id
                WHERE
					b.service='%s' and b.adn='%s' and b.msgtimestamp between '%s 00:00:00' and '%s 23:59:59' and 
					b.operatorid='%s' and a.msgstatus='DELIVERED' and b.msgStatus='DELIVERED'					
                GROUP BY
                        DATE(a.msgtimestamp),b.msisdn,b.msgdata,a.msgstatus,b.operatorid
                ORDER BY
                        DATE(a.msgtimestamp),b.msisdn,b.msgdata,a.msgstatus,b.operatorid", 
						mysql_real_escape_string($summarizer->tableFrom), 
						mysql_real_escape_string($summarizer->tableFrom),
						mysql_real_escape_string($summarizer->service),
						mysql_real_escape_string($summarizer->adn),
						mysql_real_escape_string($summarizer->date), 
						mysql_real_escape_string($summarizer->date), 
						mysql_real_escape_string($operator));
			echo $sql;
	        $result = $this->databaseObj->fetch($sql);
		
        	if (count($result) > 0) {
	            return $result;
        	} else {
	            return false;
        	}
	    }
	
	//2014-06-25
	public function getCountLelang($mt) {
		$log = manager_logging::getInstance();
	        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt)));
	        $sql = sprintf("
        	        SELECT count(1) as total
					from tbl_msgtransact
                	WHERE service='%s' and adn='%s' and msgtimestamp between '%s 00:00:00' and '%s 23:59:59' and operatorid='%s' and msisdn='%s' and msgstatus='DELIVERED' and substring_index(msgdata,' ',-1) regexp '^[0-9]+$'", 
						mysql_real_escape_string($mt->service),
						mysql_real_escape_string($mt->adn),
						date('Y-m-d'),date('Y-m-d'),
						mysql_real_escape_string($mt->operatorId),
						mysql_real_escape_string($mt->msisdn)
				);
				
		echo($sql);
		
        	$result = $this->databaseObj->fetch($sql);
		
	        if (count($result) > 0) {
        	    return $result;
	        } else {
        	    return false;
	        }
	}

	//2014-07-07
	public function getMaxPriceLelang($mt) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt)));
		$sql = sprintf("select substring_index(msgdata,' ',-1) as maxbitprice from tbl_msgtransact 
						where service='%s' and msgtimestamp >= '%s 00:00:00' and 
						left(subject,2)='MO' and adn='%s' and 
						substring_index(msgdata,' ',-1) regexp '^[0-9]+$' and
						operatorid='%s' and msisdn='%s'
						order by msgdata desc limit 1",
						mysql_real_escape_string($mt->service),
						date('y-m-d'),
						mysql_real_escape_string($mt->adn),
						mysql_real_escape_string($mt->operatorId),
						mysql_real_escape_string($mt->msisdn));
		
		$result = $this->databaseObj->fetch($sql);
		if (count($result) > 0) {
        	    return $result;
	        } else {
        	    return false;
	        }
	}

	public function getMtByPos($mt,$price) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt)));
		$sql = sprintf("select substring_index(msgdata,' ',-1) as bitprice from tbl_msgtransact 
						where service='%s' and msgtimestamp >= '%s 00:00:00' and 
						left(subject,2)='MO' and adn='%s' and 
						substring_index(msgdata,' ',-1) regexp '^[0-9]+$' and
						operatorid='%s'
						order by msgdata desc ",
						mysql_real_escape_string($mt->service),
						date('y-m-d'),
						mysql_real_escape_string($mt->adn),
						mysql_real_escape_string($mt->operatorId),
						mysql_real_escape_string($mt->msisdn));
		
		$result = $this->databaseObj->fetch($sql);
		
		$phigh = $psame = $plower = 0;
		if (count($result) > 0) {
			foreach($result as $idx => $row) {
				if($row['bitprice']==$price) {
					$psame++;
				}
				if($row['bitprice']>$price) {
					$phigh++;
				}
				if($row['bitprice']<$price) {
					$plower++;
				}
			}
			
			$log->write(array('level' => 'debug', 'message' => "Message Position : " .$psame."|".$phigh."|".$plower));
			
			if($psame==0 && $phigh==0 && $plower>=0) {
				$result = 1;
			} else {
				if($psame==1 && $phigh==0 && $plower>=0) {
					$result = 2;
				} else {
					if($psame>1 && $phigh==0 && $plower>=0) {
						$result = 3;
					} else {
						if($psame==0 && $phigh>=0 && $plower>=0) {
							$result = 5;
						} else {
							$result = 4;
						}
					}
				}
			}
			
            return $result;
        } else {
            return 1;
        }
	}

	public function allowSOASubs($mt) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt)));
		$sql = sprintf("SELECT COUNT(msisdn) m FROM subscription WHERE msisdn = '%s' AND service = '%s' AND active in (0,1,2) GROUP BY msisdn ORDER BY id DESC LIMIT 1;", mysql_real_escape_string($mt->msisdn), mysql_real_escape_string($mt->service));
		
		$result = $this->databaseObj->fetch($sql);
		if ($result[0]['m'] < 2) {
			return true;
		} else {
			return false;
		}
	}
}
