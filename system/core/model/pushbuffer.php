<?php

class model_pushbuffer extends model_base {

    public function save(model_data_pushbuffer $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("
                INSERT INTO
                    dbpush.push_buffer(
                        pid,
                        src,
                        dest,
                        oprid,
                        service,
                        subject,
                        message,
                        price,
                        stat,
                        created,
                        tid,
                        obj,
                        thread_id
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
                        '%s',
                        NOW(),
                        '%s',
                        '%s',
                         %d
                )", mysql_real_escape_string($data->pid), mysql_real_escape_string($data->src), mysql_real_escape_string($data->dest), mysql_real_escape_string($data->oprid), mysql_real_escape_string($data->service), mysql_real_escape_string($data->subject), mysql_real_escape_string($data->message), mysql_real_escape_string($data->price), mysql_real_escape_string($data->stat), mysql_real_escape_string($data->tid), mysql_real_escape_string(trim($data->obj)), mysql_real_escape_string($data->thread_id));

        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(model_data_pushbuffer $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("UPDATE dbpush.push_buffer SET stat = '%s' where id = '%s'", $data->stat, $data->id);
        return $this->databaseObj->query($sql);
    }

    public function execPushbuffer($pid, $service, $operatorId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : [" . $pid . "][" . $service . "]"));

        $sql = sprintf("SELECT * FROM dbpush.push_buffer WHERE pid = '%s' AND service = '%s' AND oprid = '%s' AND stat = 'ON_QUEUE'", mysql_real_escape_string($pid), mysql_real_escape_string($service), mysql_real_escape_string($operatorId));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    public function execPushbufferWithThread($pid, $service, $operatorId, $thread_id, $limit=1000) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : [" . $pid . "][" . $service . "]"));

        $sql = sprintf(
	        "SELECT * FROM dbpush.push_buffer 
	        WHERE 
	        pid = '%s' AND 
	        service = '%s' AND 
	        oprid = '%s' AND 
	        stat = 'ON_QUEUE' AND
	        thread_id = %d
	        LIMIT %d  
	        ", 
	        mysql_real_escape_string($pid), 
	        mysql_real_escape_string($service), 
	        mysql_real_escape_string($operatorId),
	        mysql_real_escape_string($thread_id),
	        mysql_real_escape_string($limit)	        
        );
	$log->write(array('level' => 'debug', 'message' => "Query : ".$sql));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }
        
    public function clearBuffer($deleteList) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($deleteList)));

        $deleteList = implode(",", $deleteList);
        $sql = sprintf("DELETE FROM dbpush.push_buffer where id in(%s)", $deleteList);
        return $this->databaseObj->query($sql);
    }

}

