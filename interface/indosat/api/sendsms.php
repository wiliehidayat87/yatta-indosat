<?php

class indosat_api_sendsms implements api_interface {

    public function process($GET) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if ($this->checkMandatory($GET) == true) {

            $GET['phone'] = $GET['msisdn'];
            $GET['trx_id'] = date('Ymdhis') . str_replace('.', '', microtime(true));
            $GET['sms'] = $GET['msgtext'];

            $mo_processor = new manager_mo_processor();
            $saveToFile = $mo_processor->saveToFile($GET);
            if ($saveToFile == true) {
                return array('status' => 'OK', 'description' => 'MO saved');
            } else {
                return array('status' => 'NOK', 'description' => 'failed to save');
            }
        } else {
            return false;
        }
    }

    protected function checkMandatory($GET) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));
        
        $api_config = loader_config::getInstance ()->getConfig('api');

        if ((empty($GET['controller'])) && (empty($GET['adn'])) && (empty($GET['msisdn'])) && (empty($GET['msgtext'])) && (empty($GET['operator'])) && (empty($GET['user'])) && (empty($GET['pwd']))) {
            $log->write(array('level' => 'error', 'message' => "invalid parameter"));
            return array('status' => 'NOK', 'description' => 'invalid parameter');
        } else {
            if (($GET['user'] == $api_config->user) && ($GET['pwd'] == $api_config->password)) {
                return true;
            } else {
                $log->write(array('level' => 'error', 'message' => "invalid user & password"));
                return false;
            }
        }
    }

}