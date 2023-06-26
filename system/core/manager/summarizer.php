<?php

class manager_summarizer {

    public function process($params) {
    	
        $log_profile = 'default';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (empty($params['f']) || empty($params['t'])) {
            return false;
        }
        $summarizer = loader_data::get('summarizer');
        $summarizer->type = ($params['p'] ? $params['p'] : "default");
        $summarizer->from = $params['f'];
        $summarizer->to = $params['t'];
        $summarizer->date = ($params['d'] ? $params['d'] : date("Y-m-d", strtotime("yesterday")));
        $summarizer->tableFrom = ($params['x'] ? $params['x'] : "tbl_msgtransact");
        $summarizer->tableTo = ($params['r'] ? $params['r'] : "rpt_service2");
        $summarizer_profile = 'summarizer_' . $params['p'];
        $summarizer_default = new $summarizer_profile();
        $summarizer_default->execute($summarizer);
			

        return true;
    }

    public function process_lelang($params) {
    	
        $log_profile = 'default';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (empty($params['f']) || empty($params['t'])) {
            return false;
        }
        $summarizer = loader_data::get('summarizer');
        $summarizer->type = ($params['p'] ? $params['p'] : "default");
        $summarizer->from = $params['f'];
        $summarizer->to = $params['t'];
		$summarizer->adn = $params['a'];
		$summarizer->service = $params['s'];
        $summarizer->date = ($params['d'] ? $params['d'] : date("Y-m-d", strtotime("yesterday")));
        $summarizer->tableFrom = ($params['x'] ? $params['x'] : "tbl_msgtransact");
        $summarizer->tableTo = ($params['r'] ? $params['r'] : "rpt_lelang");
		
        $summarizer_profile = 'summarizer_' . $params['p'];
        $summarizer_default = new $summarizer_profile();
        $summarizer_default->execute($summarizer);

        return true;
    }
}
