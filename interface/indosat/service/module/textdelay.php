<?php

class indosat_service_module_textdelay implements service_module_interface {

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

        $user_manager = user_manager::getInstance();
        $mt_processor = new manager_mt_processor ( );

	 /* Check Blacklist */
        $model_user = loader_model::getInstance()->load('user', 'connDatabase1');
        $isblacklist = $model_user->checkBlacklist($mo->msisdn);

        # save MO
        if ($reply->sequence < 1) // IF empty $mo->inReply (assume Not Yet Save MO ) THEN SAVEMO // empty ( $mo->inReply )
            $mo->inReply = $mt_processor->saveMOToTransact($mo);

        if ($reply->sequence > 0) :
            $log->write(array('level' => 'debug', 'message' => "MTPUSH"));
            if ((int) $replyAttributes ['rereg_content'] = 1) :
                $log->write(array('level' => 'debug', 'message' => "rereg_content = 1"));
		
		if($isblacklist === FALSE){
                #Send MTPUSH
                $this->getMsg($mo, $reply, $replyAttributes);
                $log->write(array('level' => 'debug', 'message' => "SEND MTPUSH text message : " . $reply->message));
		}else{
		$log->write(array('level' => 'debug', 'message' => "Blacklist Detected"));
		}

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
			
		    if($isblacklist === FALSE){
                    #Send MT 
                    $this->getMsg($mo, $reply, $replyAttributes);
                    $log->write(array('level' => 'debug', 'message' => "SEND MTPULL msg_pull_notregistered : " . $reply->message));
		    }else{
		    $log->write(array('level' => 'debug', 'message' => "Blacklist Detected"));
		    }
                } else { // User is active and Registered
		    if($isblacklist === FALSE){
                    #Send MT 
                    $this->getMsg($mo, $reply, $replyAttributes);
                    $log->write(array('level' => 'debug', 'message' => "SEND MT text message : " . $reply->message));
		    }else{
		    $log->write(array('level' => 'debug', 'message' => "Blacklist Detected"));
		    }
                }
            } else { // NOT PULL_MEMBER
                $log->write(array('level' => 'debug', 'message' => "NOT PULL MEMBER != 1"));
		if($isblacklist === FALSE){
                #Send MT 
                $this->getMsg($mo, $reply, $replyAttributes);
                $log->write(array('level' => 'debug', 'message' => "SEND MT text message : " . $reply->message));
		}else{
                $log->write(array('level' => 'debug', 'message' => "Blacklist Detected"));
                }
            }


        endif;

        return $mo;
    }

    private function getMsg(mo_data $mo, service_reply_data $reply, array $replyAttributes) {

        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

		$mUser = loader_model::getInstance()->load('user', 'connDatabase1');
		
        $mt = loader_data::get('mt');

        // Set DELAY
        $log->write(array('level' => 'debug', 'message' => "SET DELAY TRUE"));
        $mt->isDelay = TRUE;

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

        if ($reply->sequence < 1) {
            $mt->subject = strtoupper('MT;PULL;' . $mo->channel . ';TEXT');
            $mt->type = 'mtpull';
            $mt->inReply = $mo->inReply;
        } else {
            $mt->subject = strtoupper('MT;PUSH;' . $mo->channel . ';TEXT');
			
			$channel_subject = "NORMAL";
			$campaignKeyword = "NORMAL";
			
			if(!empty($mo->customService))
			{
				$idPStorage = substr($mo->customService, 1, strlen($mo->customService));
				$idPStorage = (int)$idPStorage;
				
				if(is_numeric($idPStorage) && $idPStorage > 0){
					$mt->subject = strtoupper('MT;PUSH;' . $mo->channel . ';TEXT');
				}else{
					$mt->subject = strtoupper('MT;PUSH;' . $mo->channel . ';TEXT;' . $mo->customService);
					$channel_subject = strtoupper($mo->customService);
				}
				
				if(!empty($mo->campaignKeyword))
				{
					$mt->subject = $mt->subject . strtoupper(';' . $mo->campaignKeyword);
					$campaignKeyword = $mo->campaignKeyword;
				}
				
			}else
				$mt->subject = strtoupper('MT;PUSH;' . $mo->channel . ';TEXT');
			
			$checkMsisdnSubject = $mUser->checkMsisdnSubject(
				array(
					'msisdn' => $mo->msisdn,
					'operator' => $mt->operatorName,
					'service' => $mt->service,
				)
			);
			
			if(count($checkMsisdnSubject) > 0)
			{
				$mUser->updateMsisdnSubject(array(
					'msisdn' => $mo->msisdn,
					'operator' => $mt->operatorName,
					'service' => $mt->service,
					'subject' => $channel_subject,
					'subject2' => $campaignKeyword,
				));
			}
			else
			{
				$mUser->insertMsisdnSubject(array(
					'msisdn' => $mo->msisdn,
					'operator' => $mt->operatorName,
					'service' => $mt->service,
					'subject' => $channel_subject,
					'subject2' => $campaignKeyword,
				));
			}
			
            $mt->type = 'mtpush';
            $mt->inReply = null;
        }
        $mt->mo = $mo;

		if(in_array(strtoupper($mt->service), array("TOP", "ASIK")))
		{
			/* $keyword = "REG+".$mt->service."+".$mo->customService;
			$keyword .= (!empty($mo->campaignKeyword) ? "+".$mo->campaignKeyword : ""); */
			$keyword = urlencode($mo->rawSMS);
			
			//$url = sprintf("https://mcb.mediapera.com/api/v2/mobile-originated/id/linkit?msisdn=%s&id_service=%s&operator=2&sms=%s&trx_id=%s&service_type=2&sdc=99789&trx_date=%d", $mt->msisdn, $mo->serviceId, $keyword, $mt->msgId, date("YmdHis"));
			$url = sprintf("http://149.129.252.221:8028/app/api/waki_in.php?msisdn=%d&operator=2&sdc=99879&sms=%s&trx_id=%d&service_type=2&trx_date=%d", $mt->msisdn, $keyword, $mt->msgId, date("YmdHis"));
			
			http_request::get($url, "", 10);
		}
		
        // ini kok textdelay insert via mt manager si .. padahal di textdelay module ude insert ke mtdelay .. iqbal, 2013/06/12
        $mt_manager = new manager_mt_processor ( );
        $mt_manager->saveToQueue($mt);

        return $mo;

    }

}
