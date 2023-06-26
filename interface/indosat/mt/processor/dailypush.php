<?php

class indosat_mt_processor_dailypush extends default_mt_processor_dailypush {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function process($slot) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . $slot));

        $loader_config = loader_config::getInstance();
        $configMT = $loader_config->getConfig('mt');
        $profile = 'dailypush';
        $queue = loader_queue::getInstance()->load($profile);
        $queue->subscribe($configMT->profile [$profile] ['prefix'] . $slot);
        for ($n = 1; $n <= $configMT->profile [$profile] ['throttle']; $n++) {
            $queuePop = $queue->pop();
            if ($queuePop === false) {
                $log->write(array('level' => 'debug', 'message' => 'Queue data not found'));
                return false;
            }
            $body = $queuePop->body;
            $log->write(array('level' => 'debug', 'message' => $body));
            $mt = unserialize($body);
            //?uid={UID}&pwd={PWD}&serviceid=%s&msisdn=%s&sms=%s&transid=%s&smstype=0
            $get = '&uid=' . $mt->charging->username;
            $get .= '&pwd=' . $mt->charging->password;
            $get .= '&serviceid=' . $mt->charging->chargingId;
            $get .= 'msisdn=' . $mt->msisdn;
            $get .= '&sms=' . urlencode($mt->msgData);
            $get .= '&transid=' . $mt->msgId;
            $get .= '&smstype=0';
            //$get .= '&url=' . $mt->content_data->url;			

            $url = $configMT->profile [$profile] ['sendUrl'] [0];

            $hit = http_request::get($url, $get, $configMT->profile [$profile] ['SendTimeOut']);

            $mt->msgLastStatus = 'DELIVERED';
            $configDr = loader_config::getInstance()->getConfig('dr');
            if ($configDr->synchrounous === TRUE) {
                if ($hit == 1) {
                    $mt->msgStatus = 'DELIVERED';
                } else {
                    $mt->msgStatus = 'FAILED';
                }
                $mt->closeReason = $hit;
            } else {
                if ($hit != 1) {
                    $mt->closeReason = $hit;
                }
            }

            $this->saveMTToTransact($mt);
        }

        return true;
    }

}
