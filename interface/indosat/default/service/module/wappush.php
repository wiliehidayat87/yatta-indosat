<?php

class default_service_module_wappush implements service_module_interface {

    public function run(mo_data $mo, service_reply_data $reply) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $mt_processor = new manager_mt_processor ( );
        $loader_model = loader_model::getInstance();

        /* @var $replyAttribute model_replyattribute */
        # load reply attributes value
        $replyAttributes = array();
        $log->write(array('level' => 'debug', 'message' => "GET Atribute"));
        $replyAttributeModel = $loader_model->load('replyattribute', 'connDatabase1');
        $attributes = $replyAttributeModel->get($reply->id);
        foreach ($attributes as $attribute) {
            $replyAttributes [$attribute ['name']] = $attribute ['value'];
        }

        $log->write(array('level' => 'debug', 'message' => "GET Reply Attributes : " . serialize($replyAttributes)));

        $user_manager = user_manager::getInstance();
        $mt_processor = new manager_mt_processor ( );

        $content = loader_data::get('content');
        $user_data = loader_data::get('user');

        $user_data->service = $mo->service;
        $user_data->msisdn = $mo->msisdn;
        $user_data->adn = $mo->adn;
        $user_data->operator = $mo->operatorName;

        $content->handler = 'indirect';
        $content->price = $replyAttributes ['wapdownload_price'];
        $content->limit = $replyAttributes ['wapdownload_limit'];
        $content->userObj = $user_data;
        //$content->generatorType = 'default';
        $content->initialChargeType = 'not charged';

        $content->wapName = $replyAttributes ['wapdownload_name'];

        #save New Token | return Obj Content_data
        $objContentData = content_manager::getInstance()->getContent($content);

        #format message reply
        $reply->message = str_replace('@URL@', $saveNewToken->url, $reply->message);

        # save MO
        if ($reply->sequence < 1) // IF empty $mo->inReply (assume Not Yet Save MO ) THEN SAVEMO // empty ( $mo->inReply )
            $mo->inReply = $mt_processor->saveMOToTransact($mo);

        if ($reply->sequence > 0) :
            $log->write(array('level' => 'debug', 'message' => "MTPUSH"));
            #IF rereg_push = 1 THEN SENDMT ELSE DO NOTHING
            if ((int) $replyAttributes ['rereg_content'] = 1) :
                $log->write(array('level' => 'debug', 'message' => "rereg_content = 1"));

                #Send MTPUSH
                $this->getMsg($mo, $reply, $replyAttributes, $objContentData, $content);
                $log->write(array('level' => 'debug', 'message' => "SEND MTPUSH text message : " . $reply->message));

            endif;


        else :
            $log->write(array('level' => 'debug', 'message' => "MTPULL"));
            #check if pull_member or not;
            if ((int) $replyAttributes ['pull_member'] == 1) {
                $log->write(array('level' => 'debug', 'message' => "PULL MEMBER = 1"));

                # init user object for used in getUserException func
                $user = loader_data::get('user');
                $user->msisdn = $mo->msisdn;
                $user->adn = $mo->adn;
                $user->service = $mo->service;
                $user->operator = $mo->operatorName;
                $user->active = '1';

                # get user subscribe data
                $log->write(array('level' => 'debug', 'message' => "SEND MTPULL msg_pull_notregistered : " . $reply->message));
                $user_manager = user_manager::getInstance();
                $subscribe = $user_manager->getUserData($user);

                $mo->userData = $subscribe;

                if ($subscribe === FALSE or (int) $subscribe->active != 1) { // Not Yet Registered or Not Active
                    #set reply message 
                    $reply->message = $replyAttributes ['msg_pull_notregistered'];

                    #Send MT 
                    $this->getMsg($mo, $reply, $replyAttributes, $objContentData, $content);
                    $log->write(array('level' => 'debug', 'message' => "SEND MTPULL msg_pull_notregistered : " . $reply->message));
                } else { // User is active and Registered
                    #Send MT 
                    $this->getMsg($mo, $reply, $replyAttributes, $objContentData, $content);
                    $log->write(array('level' => 'debug', 'message' => "SEND MT text message : " . $reply->message));
                }
            } else { // NOT PULL_MEMBER
                $log->write(array('level' => 'debug', 'message' => "NOT PULL MEMBER != 1"));
                #Send MT 
                $this->getMsg($mo, $reply, $replyAttributes, $content);
                $log->write(array('level' => 'debug', 'message' => "SEND MT text message : " . $reply->message));
            }


        endif;

        return $mo;
    }

    private function getMsg(mo_data $mo, service_reply_data $reply, array $replyAttributes, $content) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        #get message_type from charging
        $model_charging = loader_model::getInstance()->load('charging', 'connDatabase1');
        $charging_data = $model_charging->getChargingById($reply->chargingId);

        $mt_manager = new manager_mt_processor ( );

        $mt = loader_data::get('mt');

        $mt->msgData = $reply->message;
        $mt->msgId = $mo->msgId;
        $mt->charging = $reply->chargingId;
        $mt->adn = $mo->adn;
        $mt->channel = $mo->channel;
        $mt->msisdn = $mo->msisdn;
        $mt->operatorId = $mo->operatorId;
        $mt->price = $reply->price;
        $mt->operatorName = $mo->operatorName;
        $mt->service = $mo->service;
        $mt->type = $charging_data ['message_type'];
        $mt->content_data = $content;

        if ($reply->sequence < 1) {
            $mt->subject = strtoupper('MT;PULL;' . $mo->channel . ';WAPPUSH');
            //$mt->type = 'mtpush';
            $mt->inReply = $mo->inReply;
        } else {
            $mt->subject = strtoupper('MT;PUSH;' . $mo->channel . ';WAPPUSH');
            //$mt->type = 'mtpush';
            $mt->inReply = null;
        }
        error_log("WAPPUSH " . serialize($mt), 3, '/tmp/ardywap');
        $mt->mo = $mo;

        $mt_manager->saveToQueue($mt);

        return $mo;
    }

}