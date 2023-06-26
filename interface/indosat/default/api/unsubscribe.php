<?php
class default_api_unsubscribe implements api_interface {
	public function process($GET) {
		$status = $this->checkMandatory ( $GET );
		if ($status === true) {
			$user_data = new user_data ();
			$user_data->active = 1;
			$user_data->msisdn = $GET ['msisdn'];
			$user_data->adn = $GET ['adn'];
			$user_data->service = strtolower ( $GET ['service'] );
			$user_data->operator = strtolower ( $GET ['operator'] );
			$user_data->channel_unsubscribe = (isset ( $GET ['channel'] ) && ! empty ( $GET ['channel'] )) ? strtolower ( $GET ['operator'] ) : 'sms';
			
			// to check object is empty or not  we must casting the object into an array then check
			$isregistered = user_manager::getInstance ()->getUserData ( $user_data );
			
			if ($isregistered == FALSE) {
				return array ('status' => 'NOK', 'description' => "$user_data->msisdn is not registered" );
			} else {
				/*$user_data->active = 0;
				$unsubscribe = user_manager::getInstance ()->updateUserData ( $user_data );
				
				if ($unsubscribe == FALSE) {
					return array ('status' => 'NOK', 'description' => "System error" );
				}*/
				
				// sms text push, message = 'UNREG <SERVICE> WEB'
				$mo = new mo_data ();
				$mo->userData = $user_data;
				$mo->rawSMS = 'unreg ' . $user_data->service . ' web';
				$mo->msisdn = $user_data->msisdn;
				$mo->adn = $user_data->adn;
				$mo->operatorName = $user_data->operator;
				$mo->service = $user_data->service;
				$mo->channel = $GET ['channel'];
				
				$service_listener = manager_service_listener::getInstance ();
				$traffic_model = loader_model::getInstance ()->load ( 'traffic', 'reports' );
				
				$obj = $service_listener->notify ( $mo );
				if (is_object ( $obj )) {
					$save_result = $traffic_model->saveMoToRptMO ( $obj );
				}
				return $obj;
			}
		}
		return $status;
	}
	
	protected function checkMandatory($GET) {
		$api_config = loader_config::getInstance ()->getConfig ( 'api' );
		
		if ((empty ( $GET ['msisdn'] )) && (empty ( $GET ['operator'] )) && (empty ( $GET ['user'] )) && (empty ( $GET ['pwd'] )) && (empty ( $GET ['adn'] )) && (empty ( $GET ['service'] ))) {
			return array ('status' => 'NOK', 'description' => 'invalid parameter' );
		} else {
			if (($GET ['user'] == $api_config->user) && ($GET ['pwd'] == $api_config->password)) {
				return true;
			} else {
				return array ('status' => 'NOK', 'description' => 'username and password invalid' );
			}
		}
	}
}