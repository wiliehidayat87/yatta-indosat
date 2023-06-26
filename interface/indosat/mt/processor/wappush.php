<?php

class indosat_mt_processor_wappush extends default_mt_processor_wappush {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function process($slot) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . $slot));

        $loader_config = loader_config::getInstance();
        $configMT = $loader_config->getConfig('mt');
        $profile = 'wappush';
        $queue = loader_queue::getInstance()->load($profile);
        $queue->subscribe($configMT->profile [$profile] ['prefix'] . $slot);
        for ($n = 1; $n <= $configMT->profile [$profile] ['throttle']; $n++) {
            $queuePop = $queue->pop();
            if ($queuePop === false) {
                $log->write(array('level' => 'debug', 'message' => 'Queue data not found'));
                return false;
            }
            $mt = unserialize($queuePop->body);

            //generate xml
            $xml_string = $this->generateXML($mt);

            // post parameter for send
            $param = 'cpid=' . $mt->charging->username;
            $param .= '&pwd=' . $mt->charging->password;
            $param .= '&msg=' . urlencode($xml_string);

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

            $hit = (int) trim(http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']));

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

    private function generateXML($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $signs = array(" ", "-", ":");
        $content_id = $mt_data->charging->username . '_' . $mt_data->content_data->type . '_' . $mt_data->charging->gross . '_WP';
        $xml_string = '<wap-push>' .
                '<url>' . $mt_data->content_data->url . '</url>' .
                '<msisdn_sender>' . $mt_data->msisdn . '</msisdn_sender>' .
                '<msisdn_receipient>' . $mt_data->msisdn . '</msisdn_receipient>' .
                '<sid>' . $mt_data->charging->chargingId . '</sid>' .
                '<text>' . $mt_data->msgData . '</text>' .
                '<trx_id>' . $mt_data->mo->msgId . '</trx_id>' .
                '<trx_date>' . str_replace($signs, "", $mt_data->mo->incomingDate) . '</trx_date>' .
                '<contentid>' . $content_id . '</contentid>' .
                '</wap-push>';

        $replace = array("\t", "\n", "\r");
        $xml = str_replace($replace, "", $xml_string);
        $log->write(array('level' => 'info', 'message' => "Info : " . print_r($xml, TRUE)));

        return htmlentities($xml_string, ENT_QUOTES);
    }

}

