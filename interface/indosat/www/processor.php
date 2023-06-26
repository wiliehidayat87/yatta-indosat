<?php

class mcp_dr_processor extends default_dr_processor {

    /**
     * 
     * @param $str mt_data
     * 
     */
    public function saveToDb($str) {

        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . $str));

        $xmlData = $this->extract($str);
        foreach ($xmlData as $data) {
            $this->saveToFile($data);
            $save = loader_model::getInstance()->load('cdr', 'connDr')->create($data);

            $log->write(array('level' => 'debug', 'message' => 'Return Value for Save is ' . $save));
        }

        return true;
    }

    /**
     * 
     * @param $array array('status' => close reason, 'type' => sender type)
     *
     */
    private function setStatus($array) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Parse for parameter : ' . print_r($array, TRUE)));

        $configDr = loader_config::getInstance()->getConfig('dr');
        $status = 'UNKNOWN';

        /* parse the response */

        if ($array['status'] != '' && isset($configDr->responseMap[$array['type']][$array['status']])) {
            $status = $configDr->responseMap[$array['type']][$array['status']];
        }

        $log->write(array('level' => 'debug', 'message' => 'return value is : ' . $status));

        return $status;
    }

    private function extract($str) {
        $response = new DOMDocument('1.0', 'utf-8');
        $response->formatOutput = true;
        $response->preserveWhiteSpace = false;
        $response->loadXML($str);
        $drArray = array();
        foreach ($response->getElementsByTagName('message') as $value) {

            $mt_data = loader_data::get('mt');
            $mt_data->msisdn = $value->getAttribute('msisdn');
            $mt_data->msgId = $value->getAttribute('tid');
            $mt_data->adn = $value->getAttribute('adn');

            $drData = $this->getDRData($mt_data);

            $drData->statusText = $value->getAttribute('status');
            $drData->statusCode = $value->getAttribute('status');
            $drData->closeReason = $value->getAttribute('status');
            $drData->statusInternal = $this->setStatus(array('status' => $value->getAttribute('status'), 'type' => $drData->charging->senderType));
            $drData->cdrHour = date('G');
            $drArray[] = $drData;
        }

        return $drArray;
    }

    public function saveToBuffer($str) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        if (@!simplexml_load_string($str)) {
            return "NOK";
        }

        $config_dr = loader_config::getInstance()->getConfig('dr');

        $file_data = loader_data::get('file');
        $filename = date('Ymd') . str_replace('.', '', microtime(true)) . ".dr";
        $file_data->path = $config_dr->bufferPath . "/" . $filename;

        $file_data->content = trim($str);

        if (logging_file::writeDBFile($file_data)) {
            return "OK";
        } else {
            return "NOK";
        }
    }

}