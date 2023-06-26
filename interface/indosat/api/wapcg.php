<?php

class indosat_api_wapcg implements api_interface {

    public function process($GET) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));
        
        if ($this->checkMandatory($GET) == true) {
            $charging = charging_manager::getInstance();
            $getOperatorId = loader_model::getInstance ()->load('operator', 'connDatabase1');
            $loader_config = loader_config::getInstance ();
            $configMT = $loader_config->getConfig('mt');
            $profile = 'text';

            $mt = new mt_data();
            $mt->adn = $GET['adn'];
            $mt->msisdn = $GET['msisdn'];
            $mt->service = $GET['service'];
            $mt->price = $GET['price'];
            $mt->operatorName = $GET['operator'];
            $mt->msgId = date('YmdHis') . str_replace('.', '', microtime(true));
            $mt->msgData = $GET['service'] . ' WAPCG ' . $GET['code'];
            $mt->subject = strtoupper('MT;PULL;API;WAPCG-' . $GET['code']);
            $mt->incomingDate = date('Y-m-d H:i:s');
            $mt->operatorId = $getOperatorId->getOperatorId($GET['operator']);
            $mt->type = 'wapcg/wapcharging';

            $mt->charging = $charging->getCharging($mt);

            $param = 'cpid=' . $mt->charging->username;
            $param .= '&pwd=' . $mt->charging->password;
            $param .= '&sid=' . $mt->charging->chargingId;
            $param .= '&msisdn=' . $mt->msisdn;

            $url = $configMT->profile[$profile]['sendUrl'][0];
            $hit = http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']);
            $log->write ( array ('level' => 'debug', 'message' => 'Hit url wapcg : ' . $url . '?' . $param, 'response' => $hit ) );

            if ($hit == '1')
                $mt->msgStatus = 'DELIVERED';
            else
                $mt->msgStatus = 'FAILED';

            $mt->closeReason = $hit;
            $mt->msgLastStatus = 'DELIVERED';

            $default_mt = default_mt_processor_text::getInstance();
            return $default_mt->saveMTToTransact($mt);
        }else {
            return false;
        }
    }

    protected function checkMandatory($GET) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));
        
        $api_config = loader_config::getInstance ()->getConfig('api');

        if ((empty($GET['controller'])) && (empty($GET['adn'])) && (empty($GET['msisdn'])) && (empty($GET['service'])) && (empty($GET['code'])) && (empty($GET['price'])) && (empty($GET['operator'])) && (empty($GET['user'])) && (empty($GET['pwd']))) {
            $log->write(array('level' => 'error', 'message' => "'invalid parameter"));
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