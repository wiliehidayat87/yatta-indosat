<?php

class indosat_mt_processor_push extends default_mt_processor_push {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function process($slot) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $slot));

        $loader_config = loader_config::getInstance ();
        $configMT = $loader_config->getConfig('mt');
        $profile = 'push';
        $queue = loader_queue::getInstance ()->load($profile);
        $queue->subscribe($configMT->profile [$profile] ['prefix'] . $slot);
        
        for ($n = 1; $n <= $configMT->profile [$profile] ['throttle']; $n++) {
            $queuePop = $queue->pop();
            if ($queuePop === false) {
                return false;
            }
            $mt = unserialize($queuePop->body);

            $param = 'cp_name=' . $mt->charging->username;
            $param .= '&pwd=' . $mt->charging->password;
            $param .= '&sid=' . $mt->charging->chargingId;
            $param .= '&msisdn=' . $mt->msisdn;
            $param .= '&sms=' . urlencode($mt->msgData);

            $url = $configMT->profile [$profile] ['sendUrl'] [0];
            foreach ($configMT->profile [$profile] ['sendUrl'] as $urlConfig) {
                preg_match("/(http:\/\/)([A-Za-z0-9-.]*)(:[0-9]{1,5})*/i", $urlConfig, $matches);
                if (!empty($matches [2]))
                    $hostname = $matches [2];
                if (!empty($matches [3]))
                    $hostname .= $matches [3];
                if ($mt->incomingIP == $hostname)
                    $url = $urlConfig;
            }

            $hit = http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']);
            $log->write ( array ('level' => 'debug', 'message' => 'Hit url push : ' . $url . '?' . $param, 'response' => $hit ) );

            $_hit = trim($hit);
            if ($_hit == '1') {
                $mt->msgLastStatus = 'DELIVERED';
                $mt->msgStatus = 'DELIVERED';
            } else {
                $mt->msgLastStatus = 'FAILED';
                $mt->msgStatus = 'FAILED';
            }
            $mt->closeReason = $_hit;

            $this->saveMTToTransact($mt);
        }
        return true;
    }

}

