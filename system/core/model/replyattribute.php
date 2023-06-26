<?php

class model_replyattribute extends model_base {

    public function get($replyId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $replyId));

        $sql = "select ra.*,  a.name 
                            from reply_attribute AS ra
                            INNER JOIN attribute AS a ON ra.attribute_id =  a.id
                            WHERE reply_id = '" . mysql_real_escape_string($replyId) . "'";
        $data = $this->databaseObj->fetch($sql);
        return $data;
    }

}