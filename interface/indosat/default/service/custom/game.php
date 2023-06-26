<?php
class default_service_custom_game implements service_listener {
	private function onUnRegister($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		$ini_data->type = 'unreg';

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

		return array ($mt );
	}

	private function onRegister($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		$ini_data->type = 'welcome_message';

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

		return array ($mt );
	}

        private function alreadyRegister($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		$ini_data->type = 'already_register';

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

		return array ($mt );
	}

        private function alreadyUnRegister($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		$ini_data->type = 'already_unregister';

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

		return array ($mt );
	}

        private function notRegister($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		$ini_data->type = 'not_reg';

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

		return array ($mt );
	}

        private function getGame($mo_data) {
		$service_config = loader_config::getInstance ()->getConfig ( 'service' );
		$ini_reader = ini_reader::getInstance ();
		$ini_data = loader_data::get ( 'ini' );
		$ini_data->file = $service_config->iniPath .'game.ini';
		//$ini_data->type = 'game';
                
                $user = $mo_data->userData;
                
                $arrayMt = array();

		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $mo_data->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $mo_data->adn;
                $ini_data->type = 'game';
		$ini_data->section = 'REPLY';
		$mt->msgData = $ini_reader->get ( $ini_data );
		$ini_data->section = 'CHARGING';
		$mt->price = $ini_reader->get ( $ini_data );
		$mt->operatorId = $mo_data->operatorId;
		$mt->operatorName = $mo_data->operatorName;
		$mt->service = $mo_data->service;
		$mt->subject = strtoupper ( 'MT-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mt->msisdn = $mo_data->msisdn;
		$mt->channel = $mo_data->channel;
		$mt->type = 'mtpull';
		$mt->mo = $mo_data;

                $arrayMt [] = $mt;



                $content = loader_data::get('content');

                $content->handler = 'indirect';
                $content->price = 0;
                $content->limit = 4;
                $content->userObj = $user_data;
                $content->generatorType = 'spring';
                $content->initialChargeType = 'not charged';

                $ini_data->type = 'push';
                $ini_data->section = 'REPLY';
                $content->wapName = $ini_reader->get($ini_data);
                $contentNew = content_manager::getInstance ()->getContent($content);
                
                $mt2 = loader_data::get ( 'mt' );
		$mt2->inReply = $mo_data->id;
		$mt2->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt2->adn = $mo_data->adn;
                $mt2->operatorId = $mo_data->operatorId;
		$mt2->operatorName = $mo_data->operatorName;
		$mt2->service = $mo_data->service;
                $mt2->subject = strtoupper('MT;WAPPUSH;' . $mo_data->channel . ';SIM');
                $mt->msisdn = $mo_data->msisdn;
		//$mt->channel = $mo_data->channel;
		$mt2->type = 'wappush';
                $ini_data->type = 'push';
                $ini_data->section = 'REPLY';
                $mt2->msgData = $ini_reader->get($ini_data);
                $ini_data->section = 'CHARGING';
                $mt2->price = $ini_reader->get($ini_data);
                $mt2->content_data = $contentNew;
                $mt2->channel = $mo_data->channel;

                $arrayMt [] = $mt2;

		//return array ($mt );
                return $arrayMt;
	}

        private function onQuery($keyword, $mo_data) {

                if($this->subscriber === false){

			$mt = $this->notRegister ( $mo_data );

		}else{
			if($keyword == 'GAME'){

                                $mt = $this->getGame ( $mo_data );
				//$msg 	= $this->ini_reader->getMsg('wow');
				//$price 	= $this->ini_reader->getChargingPrice('wow');
			}/*elseif($keyword == 'MORE'){
				$msg 	= $this->ini_reader->getMsg('more');
				$price 	= $this->ini_reader->getChargingPrice('more');
			}elseif($keyword == 'NOKIA'){
				$msg 	= $this->ini_reader->getMsg('nokia');
				$price 	= $this->ini_reader->getChargingPrice('nokia');
			}elseif($keyword == 'INFO DIS'){
				$msg 	= $this->ini_reader->getMsg('info_dis');
				$price 	= $this->ini_reader->getChargingPrice('info_dis');
			}*/
		}

		//return $this->sendMT($msg, $price);
                //print_r($mt);
                return $mt;
	}



	public function notify($mo_data) {
                //print_r($mo_data);
		$mt_processor = new manager_mt_processor ();
		$mo_data->subject = strtoupper ( 'MO-' . $mo_data->operatorName . ';CONTENT;PULL;' . $mo_data->channel . ';' . $mo_data->service );
		$mo_data->id = $mt_processor->saveMOToTransact ( $mo_data );
		$user_manager = user_manager::getInstance ();

		$key 		= explode(" ", trim($mo_data->msgData));
		$keyword 	= strtoupper(trim($key[0]));
		$parameter 	= strtoupper(trim($key[1]));

		$user = loader_data::get ( 'user' );
		$user->msisdn = $mo_data->msisdn;
		$user->adn = $mo_data->adn;
		$user->service = $mo_data->service;
		$user->operator = $mo_data->operatorName;
		//$user->active = '2';

		$subscribe = $user_manager->getUserData($user);
                $this->subscriber = $subscribe;
              
                if($keyword == 'SUB' || $keyword == 'REG'){
                    if($subscribe === false){
                       
                        $user->active = '1';
			$user_manager->addUserData ( $user );
			$mt = $this->onRegister ( $mo_data );
                    }
                    else{
			if ($subscribe->active == '1') {
                                
				$mt = $this->alreadyRegister ( $mo_data );
			} else if ($subscribe->active == '0') {
                                
                                $user->active = '1';
				$user_manager->updateUserData($user);
                                $mt = $this->onRegister ( $mo_data );
			}
                    }
                }
                else if($keyword == 'UNSUB' || $keyword == 'UNREG'){
                    if($subscribe->active == '1'){
                        $user->active = '0';
                        $user_manager->updateUserData($user);
                        $mt = $this->onUnRegister ( $mo_data );
                    }
                    else{
                        $mt = $this->alreadyUnRegister ( $mo_data );
                    }
                }
                else{
                    $mt = $this->onQuery ( trim($keyword), $mo_data );
                }

                foreach ( $mt as $getMt ) {

			$mt_processor->saveToQueue ( $getMt );
		}

		return $mo_data;

		/*if ($subscribe === false) {
			$user->active = '1';
			$user_manager->addUserData ( $user );
			$mt = $this->getMsgBeforeRegistered ( $mo_data );
		} else {
			if ($subscribe->active == '1') {
				$mt = $this->getMsgAfterRegistered ( $mo_data );
			} else if ($subscribe->active == '0') {
				$mt = $this->getMsgBeforeRegistered ( $mo_data );
			} else {
				$user->active = '0';
				$user_manager->addUserData ( $user );
				$mt = $this->getMsgBeforeRegistered ( $mo_data );
			}
		}*/

	}
}