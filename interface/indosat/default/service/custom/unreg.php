<?php

class default_service_custom_unreg implements service_listener {

    public function notify($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $mt_processor = new manager_mt_processor ();
        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';UNREG');
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

        $mt = loader_data::get('mt');
        $mt->inReply = $mo_data->id;
        $mt->msgId = $mo_data->msgId;
        $mt->adn = $mo_data->adn;
        $mt->operatorId = $mo_data->operatorId;
        $mt->operatorName = $mo_data->operatorName;
        $mt->service = $mo_data->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';UNREG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->channel = $mo_data->channel;
        $mt->mo = $mo_data;
        $ini_data->type = 'unreg.before_registered';
        $ini_data->section = 'REPLY';
        $mt->msgData = $ini_reader->get($ini_data);
        $ini_data->section = 'CHARGING';
        $mt->price = $ini_reader->get($ini_data);

        return array($mt);
    }

    private function getMsgAfterRegistered($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mo_data)));

        $service_config = loader_config::getInstance()->getConfig('service');
        $ini_reader = ini_reader::getInstance();
        $ini_data = loader_data::get('ini');
        $ini_data->file = $service_config->iniPath . "default.ini";
        $ini_data->type = 'unreg.after_registered';

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
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';UNREG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->mo = $mo_data;
        $mt->channel = $mo_data->channel;

        $user = $mo_data->userData;
        $user->channel_unsubscribe = $mo_data->channel;
        $user->transaction_id_unsubscribe = $mo_data->id;
        $user->active = '2';
        $user_manager = user_manager::getInstance();
        $user_manager->updateUserData($user);

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
        $mt->subject = strtoupper('MT;PULL;' . $mo_data->channel . ';UNREG');
        $mt->msisdn = $mo_data->msisdn;
        $mt->type = 'mtpull';
        $mt->mo = $mo_data;
        $mt->channel = $mo_data->channel;

        return array($mt);
    }

}