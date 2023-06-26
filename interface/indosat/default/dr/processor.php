<?php

class default_dr_processor implements dr_processor_interface {

    public function process($slot) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . $slot));

        $load_config = loader_config::getInstance();
        $buffer_file = buffer_file::getInstance();

        $config_dr = $load_config->getConfig('dr');
		print_r($config_dr);

        $path = $config_dr->bufferPath . '/' . $slot;
        $limit = $config_dr->bufferThrottle;
        $result = $buffer_file->read($path, $limit, 'dr');

        if ($result !== false) {
            foreach ($result as $val) {
                foreach ($val as $drDataPath => $content) {
                    $log->write(array('level' => 'debug', 'message' => "path : " . $drDataPath . " content : " . serialize($content)));
                    
                    $log->writeDefault("dr_process",$content);

                    if (is_object($content)) {
                        $drSave = $this->saveToDb($content);
                        if ($drSave) {
                            $buffer_file->delete($drDataPath);
                        }
                    } else {
                        $log->write(array('level' => 'error', 'message' => "buffer DR is not an object"));
                        $buffer_file->delete($drDataPath);
                    }
                }
            }
        }
    }

    public function saveToDb($str) {
    	print_r($str);
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        $drData = $this->getDRData($str);

        $load_config = loader_config::getInstance();
        $config_dr = $load_config->getConfig('dr');
        $type = 'text';
        if ($config_dr->responseMap [$type] [$str->msgStatus]) {
            $str->closeReason = $config_dr->responseMap [$type] [$str->msgStatus];
        } else {
            $str->closeReason = "UNKNOWN";
        }

        $drData->statusText = $str->msgData;
        $drData->statusCode = $str->msgStatus;
        $drData->statusInternal = $str->closeReason;
        $drData->cdrHour = date('G');

        $this->saveToFile($drData);
        $save = loader_model::getInstance()->load('cdr', 'connDr')->create($drData);

        $log->write(array('level' => 'debug', 'message' => 'Return Value for Save is ' . $save));

        return true;
    }

    /**
     * update from cdr table to transact
     *
     * @param 
     * 		array
     * 			-q : from hour
     * 			-w : to hour
     * 			-c : conn DB used to updating
     * 			-f : from database.table
     * 			-t : to database.table
     * 			
     */
    public function updateTransact($arr) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($arr)));

        if (empty($arr['c'])) {
            $log->write(array('level' => 'debug', 'message' => 'Parameter missing, exiting script'));
            return false;
        }

        $configDr = loader_config::getInstance()->getConfig('dr');
        $parameter = array(
            'q' => date('H'),
            'w' => date('H', strtotime('-' . $configDr->defaultHour . ' hour')),
            'f' => 'cdr.cdr_' . date('Ymd'),
            't' => 'tbl_msgtransact'
        );

        foreach ($parameter as $params => $value) {
            if (!isset($arr[$params])) {
                $arr[$params] = $value;
            }
        }
        return loader_model::getInstance()->load('cdr', $arr['c'])->updateTransact($arr);
    }

    public function saveToFile($str) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        $config_hadoop = loader_config::getInstance()->getConfig('hadoop');
        if ($config_hadoop->enableDR == true) {
            return loader_model::getInstance()->load('dr', 'connHadoop')->saveDR($str);
        } else {
            return false;
        }
    }

    public function saveToBuffer($str) {
    	print_r($str);
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        $config_dr = loader_config::getInstance()->getConfig('dr');

        $file_data = loader_data::get('file');
        $filename = date('Ymd') . str_replace('.', '', microtime(true)) . ".dr";
        $file_data->path = $config_dr->bufferPath . "/" . $filename;

        $file_data->content = serialize($str);

        if (logging_file::writeDBFile($file_data)) {
            return "OK";
        } else {
            return "NOK";
        }
    }

    protected function getDRData($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mt_data)));
		
        $model_transact = loader_model::getInstance()->load('tblmsgtransact', 'connDatabase1');
        $dataTansact = $model_transact->getDRTransact($mt_data);

        $chargingData = loader_data::get('charging');
        $chargingData->chargingId = $dataTansact[0]['chargingId'];
        $chargingData->senderType = $dataTansact[0]['sender_type'];

        $drData = loader_data::get('dr');
        $drData->msgId = $dataTansact[0]['MSGINDEX'];
        $drData->operatorId = $dataTansact[0]['OPERATORID'];
        $drData->subject = $dataTansact[0]['SUBJECT'];
        $drData->charging = $chargingData;

        return $drData;
    }

}
