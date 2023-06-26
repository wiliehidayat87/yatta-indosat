<?php

class default_service_handler_download {

    public function notify($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath . "download.ini";

        $mt_processor = new manager_mt_processor ();
        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';DOWNLOAD');
        $mo_data->id = $mt_processor->saveMOToTransact($mo_data);

        $content = loader_data::get('content');
        $user_data = loader_data::get('user');
        $user_data->service = $mo_data->service;
        $user_data->msisdn = $mo_data->msisdn;
        $user_data->adn = $mo_data->adn;
        $user_data->operator = $mo_data->operatorName;

        $content->handler = 'indirect';
        $content->price = 0;
        $content->limit = 1;
        $content->userObj = $user_data;
        $content->generatorType = 'pakistan';
        $content->initialChargeType = 'not charged';
        $content->code = $mo_data->keyword;

        $ini_data->type = 'url_name';
        $ini_data->section = 'REPLY';
        $content->wapName = $ini_reader->get($ini_data);

        $contentNew = content_manager::getInstance()->getContent($content);

        /* @var $mt mt_data *//* @var $mo mo_data */
        $mt = loader_data::get('mt');
        $mt->inReply = $mo_data->id;
        $mt->msgId = $mo_data->msgId;
        $mt->adn = $mo_data->adn;
        $mt->operatorId = $mo_data->operatorId;
        $mt->operatorName = $mo_data->operatorName;
        $mt->service = $mo_data->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';DOWNLOAD');
        $ini_data->type = 'content';
        $ini_data->section = 'REPLY';
        $mt->msgData = $ini_reader->get($ini_data);
        $mt->msgData = str_replace("@LINK@", $contentNew->url, $mt->msgData);
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->content_data = $contentNew;
        $mt->channel = $mo_data->channel;
        $mt->mo = $mo_data;
        $user_manager = user_manager::getInstance();

        $user = loader_data::get('user');
        $user->msisdn = $mo_data->msisdn;
        $user->adn = $mo_data->adn;
        $user->service = $mo_data->service;
        $user->operator_id = $mo_data->operatorId;

        $subcriber = $user_manager->getUserData($user);

        if ($subcriber === false) {
            $user->active = 1;
            $user->transaction_id_subscribe = $mo_data->id;
            $user->channel_subscribe = $mo_data->channel;
            $user_manager->addUserData($user);
        }

        $mt_processor->saveToQueue($mt);

        return $mo_data;
    }

}