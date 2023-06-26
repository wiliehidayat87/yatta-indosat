<?php

class model_point extends model_base {

    public function getPoint($subscription_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $subscription_id));

        $this->databaseObj->query("SET @rank = 0;");
        $sql = sprintf(" SELECT rank,point,subscription_id
                    FROM (SELECT @rank := @rank + 1 AS rank, pp.point, pp.subscription_id
                    FROM service_point pp WHERE DATE(pp.time_created) = DATE(NOW())
                    ORDER BY pp.point DESC) as result
                    WHERE subscription_id = '%s'", mysql_real_escape_string($subscription_id));

        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data[0]['rank'];
        } else {
            return false;
        }
    }

    public function getAllPoint($subscription_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $subscription_id));

        $sql = sprintf(" SELECT id,point,subscription_id
                    FROM service_point
                    WHERE subscription_id = '%s'", mysql_real_escape_string($subscription_id));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data[0];
        } else {
            return false;
        }
    }
        public function getSumPoint($msisdn,$service) {
                $sql = sprintf(" select sum(a.point) as point 
                        from service_point a 
                        inner join subscription b on a.subscription_id=b.id 
                        where msisdn='%s' and b.service='%s' group by msisdn", mysql_real_escape_string($msisdn),mysql_real_escape_string($service));
                $data = $this->databaseObj->fetch($sql);
                if (count($data) > 0) {
                    return $data[0];
                } else {
                    return false;
                }
        }

    public function insertPoint($subscription_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $subscription_id));

        $sql = sprintf("INSERT INTO service_point (subscription_id, point, time_created, time_updated)VALUES('%s', '1', NOW(), NOW())", mysql_real_escape_string($subscription_id));
        $this->databaseObj->query($sql);
        return true;
    }

    public function updatePoint($id,$msgindex=0) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $id));
	
	if(!($this->isDouble($msgindex))) {
	        $sql = sprintf("UPDATE service_point SET point = point + 1, time_updated = NOW() WHERE id = '%s'", mysql_real_escape_string($id));
        	$this->databaseObj->query($sql);
	        return true;
	} else {
		return false;
	}
    }

    public function isDouble($msgindex) {
	$result = true;
	if($msgindex!=0){
		$sql = sprintf("SELECT count(*) as total from service_point_history WHERE msgindex = '%s'", mysql_real_escape_string($msgindex));
		error_log($sql."\n",3,"/tmp/double.log");
		$data = $this->databaseObj->fetch($sql);
		if (isset($data[0]['total']) and $data[0]['total'] == 0) {
            		$result=false;
        	}
	}
	return $result;
    }

    public function checkPoint($subscription_id) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $subscription_id));

        $sql = sprintf("SELECT * FROM service_point pp WHERE pp.subscription_id = '%s' AND DATE(pp.time_created) = DATE(NOW()) LIMIT 1", mysql_real_escape_string($subscription_id));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data[0]['id'];
        } else {
            return false;
        }
    }

}

?>
