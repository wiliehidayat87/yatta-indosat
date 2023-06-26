<?php

class default_service_custom_reg implements service_listener {

    public function notify($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $mt_processor = new manager_mt_processor ();
        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';REG');
        $mo_data->id = $mt_processor->saveMOToTransact($mo_data);

        $user = loader_data::get('user');
        $user->msisdn = $mo_data->msisdn;
        $user->adn = $mo_data->adn;
        $user->service = $mo_data->service;
        $user->operator = $mo_data->operatorName;

        $mo_data->userData = $user;

        $user->active = '2';

        $user_manager = user_manager::getInstance();
        $subscribe = $user_manager->getUserException($user);

        if ($subscribe === false) {
            $mt = $this->getMsgBeforeRegistered($mo_data);
        } else {
            switch ($subscribe->active) {
                case "1":
                    $mt = $this->getMsgAfterRegistered($mo_data);
                    break;
                case "2":
                    $mt = $this->getMsgBeforeRegistered($mo_data);
                    break;
                default:
                    $mt = $this->getErrorSubscription($mo_data);
                    break;
            }
        }
        foreach ($mt as $getMt) {
            $mt_processor->saveToQueue($getMt);
        }
        return $mo_data;
    }

    private function getMsgBeforeRegistered($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath . "default.ini";

        $arrayMt = array();

        $mt = loader_data::get('mt');
        $mt->inReply = $mo_data->id;
        $mt->msgId = $mo_data->msgId;
        $mt->adn = $mo_data->adn;
        $mt->operatorId = $mo_data->operatorId;
        $mt->operatorName = $mo_data->operatorName;
        $mt->service = $mo_data->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';REG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->channel = $mo_data->channel;
        $mt->mo = $mo_data;
        $ini_data->type = 'reg.before_registered';
        $ini_data->section = 'REPLY';
        $mt->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt->price = $ini_reader->get($ini_data);

        $arrayMt [] = $mt;

        $mt2 = loader_data::get('mt');
        $mt2->inReply = $mo_data->id;
        $mt2->msgId = $mo_data->msgId;
        $mt2->adn = $mo_data->adn;
        $mt2->operatorId = $mo_data->operatorId;
        $mt2->operatorName = $mo_data->operatorName;
        $mt2->service = $mo_data->service;
        $mt2->subject = strtoupper('MT;PUSH;' . $mo_data->channel . ';REG');
        $mt2->msisdn = $mo_data->msisdn;
        $mt2->type = 'mtpush';
        $mt2->channel = $mo_data->channel;
        $mt2->mo = $mo_data;
        $ini_data->type = 'reg.push';
        $ini_data->section = 'REPLY';
        $mt2->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt2->price = $ini_reader->get($ini_data);

        $arrayMt [] = $mt2;
        
        $content = loader_data::get('content');

        $user_data = loader_data::get('user');
        $user_data->service = $mo_data->service;
        $user_data->msisdn = $mo_data->msisdn;
        $user_data->adn = $mo_data->adn;
        $user_data->operator = $mo_data->operatorName;

        $content->handler = 'indirect';
        $content->price = 0;
        $content->limit = 4;
        $content->userObj = $user_data;
        $content->initialChargeType = 'not charged';

        $ini_data->type = 'reg.wap_free';
        $ini_data->section = 'REPLY';
        $content->wapName = $ini_reader->get($ini_data);

        $contentNew = content_manager::getInstance()->getContent($content);

        $mt3 = loader_data::get('mt');
        $mt3->inReply = $mo_data->id;
        $mt3->msgId = $mo_data->msgId;
        $mt3->adn = $mo_data->adn;
        $mt3->operatorId = $mo_data->operatorId;
        $mt3->operatorName = $mo_data->operatorName;
        $mt3->service = $mo_data->service;
        $mt3->subject = strtoupper('MT;WAPPUSH;' . $mo_data->channel . ';REG');
        $mt3->msisdn = $mo_data->msisdn;
        $mt3->type = 'wappush';
        $ini_data->type = 'reg.free_content';
        $ini_data->section = 'REPLY';
        $mt3->msgData = $ini_reader->get($ini_data);
        $mt3->msgData = str_replace("@urlDownload@", $contentNew->url, $mt3->msgData);
        $ini_data->section = 'CHARGING';
        $mt3->price = $ini_reader->get($ini_data);
        $mt3->content_data = $contentNew;
        $mt3->channel = $mo_data->channel;
        $mt3->mo = $mo_data;

        $arrayMt [] = $mt3;

        $user = $mo_data->userData;
        $user->channel_subscribe = $mo_data->channel;
        $user->transaction_id_subscribe = $mo_data->id;
        $user->active = '1';
        $user_manager = user_manager::getInstance();
        $user_manager->addUserData($user);

        return $arrayMt;
    }

    private function getMsgAfterRegistered($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath . "default.ini";
        $ini_data->type = 'reg.after_registered';

        $mt = loader_data::get('mt');
        $mt->inReply = $mo_data->id;
        $mt->msgId = $mo_data->msgId;
        $mt->adn = $mo_data->adn;
        $ini_data->section = 'REPLY';
        $mt->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt->price = $ini_reader->get($ini_data);
        $mt->operatorId = $mo_data->operatorId;
        $mt->operatorName = $mo_data->operatorName;
        $mt->service = $mo_data->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';REG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->mo = $mo_data;
        $mt->channel = $mo_data->channel;

        return array($mt);
    }

    private function getErrorSubscription($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath . "clubfun.ini";
        $ini_data->type = 'error.wrong_keyword';

        $mt = loader_data::get('mt');
        $mt->inReply = $mo_data->id;
        $mt->msgId = $mo_data->msgId;
        $mt->adn = $mo_data->adn;
        $ini_data->section = 'REPLY';
        $mt->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt->price = $ini_reader->get($ini_data);
        $mt->operatorId = $mo_data->operatorId;
        $mt->operatorName = $mo_data->operatorName;
        $mt->service = $mo_data->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';REG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->mo = $mo_data;
        $mt->channel = $mo_data->channel;

        return array($mt);
    }

}