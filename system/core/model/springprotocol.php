<?php

class model_springprotocol extends model_base {

    public function add($protocol_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start " . serialize($protocol_data)));

        $main_config = loader_config::getInstance()->getConfig('main');
        $sql = sprintf("
            INSERT INTO spring_protocol (
                subscription_id ,
                protocol ,
                mo_time ,
                mt_time ,
                msg_data ,
                protocol_type
            ) VALUES (
                '%s', '%s', '%s', NOW(), '%s', '%s'
            );", mysql_real_escape_string($protocol_data->subscriptionId), mysql_real_escape_string($protocol_data->protocol), mysql_real_escape_string($protocol_data->moTime), mysql_real_escape_string($protocol_data->msgData), mysql_real_escape_string($protocol_data->protocolType)
        );
        $query = $this->databaseObj->query($sql);
        return $this->databaseObj->last_insert_id();
    }

}