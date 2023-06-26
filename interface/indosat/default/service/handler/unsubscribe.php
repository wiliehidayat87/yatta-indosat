<?php

class default_service_handler_unsubscribe {

    public function notify(mo_data $mo_data) {
        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath;
        $ini_data->type = 'info';

        $manager_mt_processor = new manager_mt_processor ();
        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';UNSUBSCRIBE');
        $row_id = $manager_mt_processor->saveMOToTransact($mo_data);

        $arrayMT = array();

        $mt_data = loader_data::get('mt');
        $mt_data->inReply = $row_id;
        $ini_data->section = 'REPLY';
        $mt_data->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt_data->price = $ini_reader->get($ini_data);
        $mt_data->msgId = date('YmdHis') . str_replace('.', '', microtime(true));
        $mt_data->adn = $mo_data->adn;
        $mt_data->operatorId = $mo_data->operatorId;
        $mt_data->operatorName = $mo_data->operatorName;
        $mt_data->service = $mo_data->service;
        $mt_data->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';UNSUBSCRIBE');
        $mt_data->msisdn = $mo_data->msisdn;
        $mt_data->channel = $mo_data->channel;
        $mt_data->type = 'mtpull';
        $mt_data->mo = $mo_data;

        $arrayMT [] = $mt_data;

        foreach ($arrayMT as $mt) {
            $manager_mt_processor->saveToQueue($mt);
        }

        return $mo_data;
    }

}