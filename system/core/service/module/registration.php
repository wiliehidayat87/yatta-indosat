<?php

final class service_module_registration implements service_module_interface {

    public function run ($mo, $reply){
        $log = manager_logging::getInstance ();
        $log->write ( array ('level' => 'debug', 'message' => "Start" ) );
        $user_manager = user_manager::getInstance();
        $mt_processor = new manager_mt_processor();
        
        $user = loader_data::get( 'user' );
        $user->msisdn = $mo->msisdn;
        $user->adn = $mo->adn;
        $user->service = $mo->service;
        $user->operator = $mo->operatorName;
        $user->active = '2';

        $content = loader_data::get('content');
        $content->handler = 'indirect';
        $content->price = 0;
        $content->limit = 4;
        $content->userObj = $user;
        $content->generatorType = 'spring';
        $content->initialChargeType = 'not charged';

        /* @var $mt mt_data */
        /* @var $mo mo_data */
        $mt = loader_data::get('mt');
        $mt->inReply = $mo->id;
        $mt->msgId = $mo->msgId;
        $mt->adn = $mo->adn;

        $subcribe = $user_manager->getUserException( $user );
        if ( $subcribe === false ) {
            $user->active = '0';
            $user_manager->addUserData($user);
            $mt->msgData = $reply->message;
        } else {
            if ( $subcribe->active == '1' ){
                $msgData .= 'Anda sudah terdaftar';
                $mt->msgData = $msgData;
            } else if ( $subcribe->active == '0' ){
                $mt->msgData = $reply->message;
            } else {
                $user->active = '0';
                $user_manager->addUserData($user);
                $mt->msgData = $reply->message;
            }
        }

        $mt->operatorId = $mo->operatorId;
        $mt->operatorName = $mo->operatorName;
        $mt->service = $mo->service;
        $mt->subject = strtoupper( 'MT;WAPPUSH;' . $mo->channel . ';REG' );
        $mt->msisdn = $mo->msisdn;
        $mt->channel = $mo->channel;
        $mt->type = 'wappush';

        $mt_processor->saveMOToTransact($mo);
        
        $mt_processor->saveToQueue($mt);
        
        return $mt;
    }
}
