<?php

class model_allowedfree extends model_base {

    public function add(broadcast_allowedfree_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("
		INSERT INTO allowed_free
			(subscription_id, status)
		VALUES
			('%s', '%s')
		", mysql_real_escape_string($data->subscriptionId), mysql_real_escape_string($data->status));
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

    public function update(broadcast_allowedfree_data $data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($data)));

        $sql = sprintf("
		UPDATE allowed_free
                SET status = '%s'
                WHERE subscription_id = '%d'
		", mysql_real_escape_string($data->status), mysql_real_escape_string($data->subscriptionId));
        return $this->databaseObj->query($sql);
    }

    public function getCount($subscriptionId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $subscriptionId));

        $sql = sprintf("SELECT count(*) total FROM allowed_free WHERE status = '0' AND subscription_id = '%s'", mysql_real_escape_string($subscriptionId));

        $result = $this->databaseObj->fetch($sql);

        if ($result[0]['total'] > 0) {
            return true;
        } else {
            return false;
        }
    }

}