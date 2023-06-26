<?php

final class default_service_module_unregistration implements service_module_interface {

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
        $subscribe = $user_manager->getUserException($user);

        if ($subscribe === FALSE) {
            // ga bisa unreg
            $log->write(array('level' => 'debug', 'message' => "Not Yet Registered, Cannot UNREG"));

            #set reply message from replyattributes
            $reply->message = $replyAttributes ['msg_unreg_notregistered'];

            $mt = $this->SendMT($mo, $reply, $replyAttributes);
            //$user_manager->addUserData ( $user );
        } else {
            $log->write(array('level' => 'debug', 'message' => " Is Register and active = 1 THEN UNREG"));

            $subscribe->channel_unsubscribe = $mo_data->channel;
            $subscribe->transaction_id_unsubscribe = $mo->inReply;
            $subscribe->active = '0';

            $user_update = user_manager::getInstance();
            $user_update->updateUserData($subscribe);

            $mt = $this->SendMT($mo, $reply, $replyAttributes);
        }

        return $mo;
    }

    private function SendMT($mo, $reply, $replyAttributes) {
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
