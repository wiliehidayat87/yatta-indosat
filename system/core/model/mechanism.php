<?php

class model_mechanism extends model_base {

    public function readAll($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_data)));

        $sql = sprintf("
            SELECT  
                m.id, 
                m.pattern,  
                m.handler,
                s.name 
            FROM  
                mechanism m 
            INNER JOIN  
                service s ON  m.service_id = s.id 
            WHERE 
                m.status <> '0' 
            AND 
                s.adn = '%s' 
            AND 
            	m.operator_id = '%s'
            ORDER BY m.pattern DESC;", mysql_real_escape_string($mo_data->adn), mysql_real_escape_string($mo_data->operatorId));
        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

    public function readAllModule($patternId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $patternId));

        $sql = sprintf("
            SELECT 
                r.*, 
                m.handler, 
                m.name AS moduleName, 
                c.message_type,
				c.gross,
				c.netto,
		c.charging_id as charging_code
            FROM 
                reply r
            INNER JOIN 
                module m ON r.module_id = m.id
            INNER JOIN 
                charging c ON r.charging_id = c.id
            WHERE 
                m.status = '1' 
            AND 
                r.mechanism_id = '%s'", mysql_real_escape_string($patternId));

        $data = $this->databaseObj->fetch($sql);
        if (count($data) > 0) {
            return $data;
        } else {
            return false;
        }
    }

}
