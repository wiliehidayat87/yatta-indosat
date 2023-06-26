<?php

class default_service_handler_error extends service_handler_error {

    /**
     * @param $mo_data
     */
    public function notify($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $main_config = loader_config::getInstance()->getConfig('main');
        $manager_mt_processor = new manager_mt_processor ( );

        $mo_data->service = "ERROR";
        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';ERROR');
	    $mo_data->msgStatus = "DELIVERED";
        $row_id = $manager_mt_processor->saveMOToTransact($mo_data);

        $arrayMT = array();
        // create mt data object
        $mt_data = loader_data::get('mt');
        $mt_data->inReply = $row_id;
        $mt_data->msgId = $mo_data->msgId;
        $mt_data->adn = $mo_data->adn;
        $mt_data->msgData = "Error";
        //$mt_data->msgLastStatus = $mo_data->msgLastStatus;
        $mt_data->retry = $mo_data->retry;
        //$mt_data->msgStatus = $mo_data->msgStatus;
        //$mt_data->closeReason;
        //$mt_data->price = $mo_data->price;
        $mt_data->operatorId = $mo_data->operatorId;
        $mt_data->media = $mo_data->media;
        $mt_data->channel = $mo_data->channel;
        $mt_data->service = $mo_data->service;
        $mt_data->partner = $mo_data->partner;
        $mt_data->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';ERROR');
        $mt_data->keyword = $mo_data->keyword;
        $mt_data->operatorName = $main_config->operator;
        $mt_data->rawSMS = $mo_data->rawSMS;
        $mt_data->requestType = $mo_data->requestType;
        //$mt_data->incomingDate = $mo_data->incomingDate;
        //$mt_data->parameter = $mo_data->parameter;
        //$mt_data->incomingIP = $mo_data->incomingIP;
        $mt_data->price = '0';
        $mt_data->msisdn = $mo_data->msisdn;
        $mt_data->type = 'mtpull';
        $mt_data->mo = $mo_data;

        $manager_mt_processor->saveToQueue($mt_data);

        return $mo_data;
    }

}
