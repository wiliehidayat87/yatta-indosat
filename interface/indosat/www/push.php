<?php

class mcp_mt_processor_push extends default_mt_processor_push {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function process($slot) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Forking Start : ' . $slot));

        $loader_config = loader_config::getInstance();
        $configMT = $loader_config->getConfig('mt');
        $profile = 'push';
        $queue = loader_queue::getInstance()->load($profile);
        if ($queue) {
            $queue->subscribe($configMT->profile [$profile] ['prefix'] . $slot);
            for ($n = 1; $n <= $configMT->profile [$profile] ['throttle']; $n++) {
                $queuePop = $queue->pop();
                if ($queuePop === false) {
                    return false;
                }
                $body = $queuePop->body;
                $mt = unserialize($body);

                $url = $configMT->profile [$profile] ['sendUrl'] [0];

                $xmlWriter = new library_xml_writer ();
                $xmlWriter->push('message', array('type' => 'mtpush'));
                $xmlWriter->element('msisdn', $mt->msisdn);
                $xmlWriter->element('sms', '<![CDATA[' . $mt->msgData . ']]>', true);
                $xmlWriter->element('ccode', $mt->charging->chargingId);
                $xmlWriter->element('scode', $mt->service);
                $xmlWriter->element('cpid', $mt->charging->username);
                $xmlWriter->element('cppwd', $mt->charging->password);
                $xmlWriter->pop();
                $xml = $xmlWriter->getXml();

                $hit = http_request::post($url, $xml, $configMT->profile [$profile] ['SendTimeOut']);

                if (@simplexml_load_string($hit) === false) {
                    $mt->msgLastStatus = 'FAILED';
                    $mt->msgStatus = 'FAILED';
                    $mt->closeReason = '';
                    $this->saveMTToTransact($mt);
                } else {
                    $response = new DOMDocument('1.0', 'utf-8');
                    $response->formatOutput = true;
                    $response->preserveWhiteSpace = false;
                    $response->loadXML($hit);

                    $status = $response->getElementsByTagName('status')->item(0)->nodeValue;
                    $msgid = $response->getElementsByTagName('tid')->item(0)->nodeValue;

                    $mt->msgLastStatus = 'DELIVERED';
                    $configDr = loader_config::getInstance()->getConfig('dr');
                    $drStatus = $configDr->responseMap[$profile][$status];
                    if ($configDr->synchrounous === TRUE) {
                        if ($hit == '0') {
                            $mt->msgStatus = $drStatus;
                            if (!empty($mt->mo->partner)) {
                                $partner = default_partner_processor::getInstance();
                                $partner->process($mt);
                            }
                        } else {
                            $mt->msgStatus = $drStatus;
                        }
                        $mt->closeReason = $hit;
                    } else {
                        if ($hit != '0') {
                            $mt->closeReason = $hit;
                        }
                    }
                    $this->saveMTToTransact($mt);
                }
            }
        }
        return true;
    }

}