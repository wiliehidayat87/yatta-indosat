<?php

final class default_service_module_registration implements service_module_interface {

    public function run(mo_data $mo, service_reply_data $reply) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $loader_model = loader_model::getInstance();

        /* @var $replyAttribute model_replyattribute */
        $log->write(array('level' => 'debug', 'message' => "GET Atribute"));

        # load reply attributes value
        $replyAttributes = array();
        $replyAttributeModel = $loader_model->load('replyattribute', 'connDatabase1');
        $attributes = $replyAttributeModel->get($reply->id);
        foreach ($attributes as $attribute) {
            $replyAttributes [$attribute ['name']] = $attribute ['value'];
        }

	/* Check Blacklist */
	$log->write(array('level' => 'debug', 'message' => "Check MSISDN in blacklist"));
	$model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $isblacklist = $model_user->checkBlacklist($mo->msisdn);

        /* @var $user_manager user_manager */
        $user_manager = user_manager::getInstance();
        $mt_processor = new manager_mt_processor ( );

        # save MO
        $mo->inReply = $mt_processor->saveMOToTransact($mo);

        /* @var $user user_data */
        $user = loader_data::get('user');
        $user->msisdn = $mo->msisdn;
        $user->adn = $mo->adn;
        $user->service = $mo->service;
        $user->operator = $mo->operatorName;
        $user->active = '0';
        $user->operator_id = $mo->operatorId;

        $log->write(array('level' => 'debug', 'message' => "GET USER EXCEPTION"));
        $subcribe = $user_manager->getUserException($user);

        if ($subcribe === FALSE) {
            $log->write(array('level' => 'debug', 'message' => "Not Yet Registered, THEN ADD User"));

            $user->active = '1';
            $user->transaction_id_subscribe = $mo->inReply;
            $user_manager->addUserData($user);
            $mt = $this->getMsgBeforeRegistered($mo, $reply, $replyAttributes);
            //$user_manager->addUserData ( $user );
        } else {
            if ((int) $subcribe->active == 1) { # user is registered & active
                $log->write(array('level' => 'debug', 'message' => "Subscribe  Active = 1"));

                #set reply message from replyattributes
                $reply->message = $replyAttributes ['msg_isregistered'];

                #check rereg_welcome value 
                if ((int) $replyAttributes ['rereg_welcome'] == 1) // send MT
                    $mt = $this->getMsgAfterRegistered($mo, $reply, $replyAttributes);
                else // no MT
                    return $mo;
            } else if ($subcribe->active == '0') { # user is registered & not active
                $log->write(array('level' => 'debug', 'message' => "Subscribe  Active = 0"));

                $mt = $this->getMsgBeforeRegistered($mo, $reply, $replyAttributes);
            } else {
                $log->write(array('level' => 'debug', 'message' => "ADD UserDatata GetMSGBefore"));

                $user->active = '1';
                $user_manager->addUserData($user);
                $mt = $this->getMsgBeforeRegistered($mo, $reply, $replyAttributes);
            }
        }

        return $mo;
    }

    private function getMsgBeforeRegistered($mo, $reply, $replyAttributes) {

        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $mt_manager = new manager_mt_processor ( );
        //$reply_id = $mt_manager->saveMOToTransact ( $mo );

        $mt = loader_data::get('mt');

        $mt->msgData = $reply->message;
        $mt->msgId = $mo->msgId;
        $mt->charging = $reply->chargingId;
        $mt->adn = $mo->adn;
        $mt->channel = $mo->channel;
        $mt->msisdn = $mo->msisdn;
        $mt->operatorId = $mo->operatorId;
        $mt->inReply = $mo->inReply;
        $mt->price = $reply->price;
        $mt->operatorName = $mo->operatorName;
        $mt->service = $mo->service;
        $mt->subject = strtoupper('MT;PULL;' . $mo->channel . ';TEXT');
        $mt->type = 'mtpull';
        $mt->mo = $mo;

        $mt_manager->saveToQueue($mt);

        return $mo;
    }

    private function getMsgAfterRegistered($mo, $reply, $replyAttributes) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $mt_manager = new manager_mt_processor ( );

        $mt = loader_data::get('mt');

        $mt->msgData = $reply->message;
        $mt->msgId = $mo->msgId;
        $mt->charging = $reply->chargingId;
        $mt->adn = $mo->adn;
        $mt->channel = $mo->channel;
        $mt->msisdn = $mo->msisdn;
        $mt->operatorId = $mo->operatorId;
        $mt->inReply = $mo->inReply;
        $mt->price = $reply->price;
        $mt->operatorName = $mo->operatorName;
        $mt->service = $mo->service;

        $mt->subject = strtoupper('MT;PULL;' . $mo->channel . ';TEXT');
        $mt->type = 'mtpull';
        $mt->mo = $mo;

        $mt_manager->saveToQueue($mt);

        return $mo;
    }

}
