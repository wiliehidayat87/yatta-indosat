<?php

class default_service_custom_discount extends default_service_abstract_handler implements service_listener {
	
	public function __construct() {
		parent::__construct ();
	}
	
	protected $service = 'discount';
	
	public function notify($mo_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$this->mo = $mo_data;
		
		$this->mo->subject = strtoupper ( 'MO;PULL;' . $this->mo->channel . ';' . $this->config->operator . ';CONTENT;' . $this->mo->service );
		$this->mo->id = $this->mt_processor->saveMOToTransact ( $this->mo );
		
		$this->user_manager = user_manager::getInstance ();
		
		$user = loader_data::get ( 'user' );
		$user->msisdn = $this->mo->msisdn;
		$user->adn = $this->mo->adn;
		$user->service = $this->mo->service;
		$user->operator_id = $this->mo->operatorId;
		
		$key = explode ( " ", trim ( $this->mo->keyword ) );
		$keyword = strtoupper ( trim ( $key [0] ) );
		$parameter = strtoupper ( trim ( $key [1] ) );
		
		$this->subscriber = $this->user_manager->getUserData ( $user );
		
		if ($keyword == 'SUB' || $keyword == 'REG') {
			$status = $this->onRegister ();
		} elseif ($keyword == 'UNSUB' || $keyword == 'UNREG') {
			$status = $this->onUnRegister ();
		} else {
			$status = $this->onQuery ( trim ( $this->mo->keyword ) );
		}
		
		return $this->mo;
	}
	
	private function onUnRegister() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$user = loader_data::get ( 'user' );
		$user->msisdn = $this->mo->msisdn;
		$user->adn = $this->mo->adn;
		$user->service = $this->mo->service;
		$user->operator_id = $this->mo->operatorId;
		
		if ($this->subscriber === false) {
			
			$msg = $this->getMsg ( 'not_reg' );
			$price = $this->getChargingPrice ( 'not_reg' );
		} else {
			if ($this->subscriber->active == '1') {
				$user->active = 0;
				$user->id = $this->subscriber->id;
				$user->transaction_id_unsubscribe = $this->mo->id;
				$user->channel_unsubscribe = $this->mo->channel;
				$this->user_manager->updateUserData ( $user );
				
				$msg = $this->getMsg ( 'unreg' );
				$price = $this->getChargingPrice ( 'unreg' );
			} elseif ($this->subscriber->active == '0') {
				
				$msg = $this->getMsg ( 'already_unreg' );
				$price = $this->getChargingPrice ( 'already_unreg' );
			}
		}
		
		return $this->sendMT ( $msg, $price );
	}
	
	private function onRegister() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$user = loader_data::get ( 'user' );
		$user->msisdn = $this->mo->msisdn;
		$user->adn = $this->mo->adn;
		$user->service = $this->mo->service;
		$user->operator_id = $this->mo->operatorId;
		$user->operator = $this->mo->operatorName;
		
		if ($this->subscriber === false) {
			
			$user->active = 1;
			$user->transaction_id_subscribe = $this->mo->id;
			$user->channel_subscribe = $this->mo->channel;
			$this->user_manager->addUserData ( $user );
			
			$msg = $this->getMsg ( 'welcome_message' );
			$price = $this->getChargingPrice ( 'welcome_message' );
			
			return $this->sendMT ( $msg, $price );
			
		//$this->sendPushMT($this->getMsg('push_after_reg1'), $this->getChargingPrice('push_after_reg1'));
		

		//$this->sendPushMT($this->getMsg('push_after_reg2'), $this->getChargingPrice('push_after_reg2'));
		} else {
			
			if ($this->subscriber->active == '1') {
				
				$msg = $this->getMsg ( 'already_reg' );
				$price = $this->getChargingPrice ( 'already_reg' );
			} elseif ($this->subscriber->active == '0') {
				$user->active = 1;
				$user->id = $this->subscriber->id;
				$this->user_manager->updateUserData ( $user );
				
				$msg = $this->getMsg ( 'welcome_message' );
				$price = $this->getChargingPrice ( 'welcome_message' );
				
			//$this->sendPushMT($this->getMsg('push_after_reg1'), $this->getChargingPrice('push_after_reg1'));
			}
			
			return $this->sendMT ( $msg, $price );
		}
	}
	
	private function onQuery($keyword) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		if ($this->subscriber === false || $this->subscriber->active == '0') {
			return $this->onRegister ();
			//$msg = $this->getMsg('not_reg');
		//$price = $this->getChargingPrice('not_reg');
		} else {
			$point = true;
			if ($keyword == 'WOW') {
				$msg = $this->getMsg ( 'wow' );
				$price = $this->getChargingPrice ( 'wow' );
			} elseif ($keyword == 'MORE') {
				$msg = $this->getMsg ( 'more' );
				$price = $this->getChargingPrice ( 'more' );
			} elseif ($keyword == 'NOKIA') {
				$msg = $this->getMsg ( 'nokia' );
				$price = $this->getChargingPrice ( 'nokia' );
			} elseif ($keyword == 'INFO DIS') {
				$msg = $this->getMsg ( 'info_dis' );
				$price = $this->getChargingPrice ( 'info_dis' );
			} elseif ($keyword == 'SAM') {
				$msg = $this->getMsg ( 'sam' );
				$price = $this->getChargingPrice ( 'sam' );
			} elseif ($keyword == 'SAMSUNG') {
				$msg = $this->getMsg ( 'samsung' );
				$price = $this->getChargingPrice ( 'samsung' );
			} elseif ($keyword == 'BLACKBERRY') {
				$msg = $this->getMsg ( 'blackberry' );
				$price = $this->getChargingPrice ( 'blackberry' );
			} elseif ($keyword == 'BB') {
				$msg = $this->getMsg ( 'bb' );
				$price = $this->getChargingPrice ( 'bb' );
			} elseif ($keyword == 'INFO') {
				$msg = $this->getMsg ( 'info' );
				$price = $this->getChargingPrice ( 'info' );
			} else {
				$msg = $this->getMsg ( 'wrong_keyword' );
				$price = $this->getChargingPrice ( 'wrong_keyword' );
				$point = false;
			}
			
			if ($point == true) {
				$model_point = loader_model::getInstance ()->load ( 'point', 'connDatabase1' );
				$ispoint = $model_point->checkPoint ( $this->subscriber->id );
				if ($ispoint === false)
					$model_point->insertPoint ( $this->subscriber->id );
				else
					$model_point->updatePoint ( $ispoint );
				$getpoint = $model_point->getPoint ( $this->subscriber->id );
				$msg = str_replace ( '@point@', $getpoint, $msg );
			}
		}
		
		return $this->sendMT ( $msg, $price );
	}

}