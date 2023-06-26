<?php

class model_pushproject extends model_base {

    public function get(model_data_pushproject $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $param = array();
        $sql = sprintf("select * from dbpush.push_projects");
        if (isset($data->pid)) {
	    $param[] = sprintf("pid = '%s' ", mysql_real_escape_string($data->pid));
        }
        if (isset($data->sid)) {
            $param[] = sprintf("sid = '%s' ", mysql_real_escape_string($data->sid));
        }
        if (isset($data->src)) {
            $param[] = sprintf("src = '%s' ", mysql_real_escape_string($data->src));
        }
        if (isset($data->dest)) {
            $param[] = sprintf("dest = '%s' ", mysql_real_escape_string($data->dest));
        }
        if (isset($data->oprid)) {
            $param[] = sprintf("oprid = '%s' ", mysql_real_escape_string($data->oprid));
        }
        if (isset($data->service)) {
            $param[] = sprintf("service = '%s' ", mysql_real_escape_string($data->service));
        }
        if (isset($data->subject)) {
            $param[] = sprintf("subject = '%s' ", mysql_real_escape_string($data->subject));
        }
        if (isset($data->message)) {
            $param[] = sprintf("message = '%s' ", mysql_real_escape_string($data->message));
        }
        if (isset($data->price)) {
            $param[] = sprintf("price = '%s' ", mysql_real_escape_string($data->price));
        }
        if (isset($data->amount)) {
            $param[] = sprintf("amount = '%s' ", mysql_real_escape_string($data->amount));
        }
        if (isset($data->processed)) {
            $param[] = sprintf("processed = '%s' ", mysql_real_escape_string($data->processed));
        }
        if (isset($data->status)) {
            $param[] = sprintf("status = '%s' ", mysql_real_escape_string($data->status));
        }
        if (isset($data->created)) {
            //$param[] = sprintf("created LIKE '%%%s%%' ", mysql_real_escape_string($data->created));
	    if(($data->oprid=='2')||($data->oprid=='5')){
		$param[] = sprintf("date(created) >= '%s' - INTERVAL 1 DAY ", mysql_real_escape_string($data->created));
	    }else{
	        $param[] = sprintf("date(created) = '%s' ", mysql_real_escape_string($data->created));
	    }
        }

        if (count($param) > 0) {
            $data = implode($param, " AND ");
            $sql .= sprintf(" WHERE %s", $data);
        }

        $result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function save(model_data_pushproject $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("
                INSERT INTO
                    dbpush.push_projects(
                        sid,
                        src,
                        oprid,
                        service,
                        subject,
                        message,
                        price,
                        amount,
                        created
                    )
                VALUES(
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        '%s',
                        NOW()
                )", mysql_real_escape_string($data->sid), mysql_real_escape_string($data->src), mysql_real_escape_string($data->oprid), mysql_real_escape_string($data->service), mysql_real_escape_string($data->subject), mysql_real_escape_string($data->message), mysql_real_escape_string($data->price), mysql_real_escape_string($data->amount));

        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(model_data_pushproject $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = "UPDATE dbpush.push_projects SET ";
        if (isset($data->processed)) {
            $param[] = sprintf("processed = '%s' ", mysql_real_escape_string($data->processed));
        }
        if (isset($data->amount)) {
            $param[] = sprintf("amount = '%s' ", mysql_real_escape_string($data->amount));
        }
        if (isset($data->status)) {
            $param[] = sprintf("status = '%s' ", mysql_real_escape_string($data->status));
        }
        $sql .= implode($param, ",");
        $sql .= sprintf(" WHERE pid = %d", mysql_real_escape_string($data->pid));

        return $this->databaseObj->query($sql);
    }

    public function isactivepush($service){
	$log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("SELECT * FROM `dbpush.push_projects` WHERE service = '%s' AND DATE(created) = CURRENT_DATE",$service);
		
	$result = $this->databaseObj->fetch($sql);
        if (count($result) > 0) {
            return true;
        } else {
            return false;
        }
   }

}
