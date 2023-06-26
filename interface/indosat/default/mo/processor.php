<?php

class default_mo_processor implements mo_processor {

    public function saveToFile($arrData) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($arrData)));

        $load_config = loader_config::getInstance();
        $config_main = $load_config->getConfig('main');
        $config_mo = $load_config->getConfig('mo');

        $mo_data = loader_data::get('mo');
        $mo_data->msisdn = $arrData ['msisdn'];
        $mo_data->msgId = $arrData ['trx_id'];
        $mo_data->rawSMS = $arrData ['sms'];
        $mo_data->adn = $config_main->adn;
        $mo_data->operatorName = $config_main->operator;
        $mo_data->incomingDate = date("Y-m-d H:i:s");
        $mo_data->incomingIP = http_request::getRealIpAddr();
        $mo_data->type = 'mo';

        $buffer_file = buffer_file::getInstance();

        $path = $buffer_file->generate_file_name($mo_data);

        if ($buffer_file->save($path, $mo_data)) {
            $log->write(array('level' => 'info', 'message' => 'Object MO write at: ' . $path . ' response : ' . $config_mo->returnCode['OK']));
            return $config_mo->returnCode ['OK'];
        } else {
            $log->write(array('level' => 'error', 'message' => 'Write Object MO failed at : ' . $path . ' response : ' . $config_mo->returnCode['NOK']));
            return $config_mo->returnCode ['NOK'];
        }
    }

    public function process($slot) {
        $lock = new library_lockfile('mo_processor');
        $lock->create($slot);

        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => " Start : " . $slot));
        $loader_model = loader_model::getInstance();
        $traffic_model = $loader_model->load('traffic', 'reports');
        if ($traffic_model === false) {
            $lock->delete($slot);
            return false;
        }

        $buffer_file = buffer_file::getInstance();
        $load_config = loader_config::getInstance();

        $config_main = $load_config->getConfig('main');
        $config_mo = $load_config->getConfig('mo');

        $path = $config_mo->bufferPath . '/' . $slot;
        $limit = $config_mo->bufferThrottle;
        $result = $buffer_file->read($path, $limit);

        if ($result !== false) {
            $service_listener = manager_service_listener::getInstance();
            foreach ($result as $val) {
                foreach ($val as $moDataPath => $content) {
                    $log->write(array('level' => 'debug', 'message' => serialize($content)));
                    
                    $log->writeDefault("mo_process",$content);

                    if (is_object($content)) {
                        $obj = $service_listener->notify($content);
                        if (is_object($obj)) {
                            $buffer_file->delete($moDataPath);
                            $traffic_model->saveMoToRptMO($obj);
                        } else {
                            $log->write(array('level' => 'error', 'message' => "This is not an object"));
                            $lock->delete($slot);
                            return false;
                        }
                    } else {
                        $log->write(array('level' => 'error', 'message' => "buffer MO is not an object"));
                        $buffer_file->delete($moDataPath);
                        $lock->delete($slot);
                        return false;
                    }
                }
            }
            $lock->delete($slot);
            return true;
        } else {
            $lock->delete($slot);
            return false;
        }
    }

}
