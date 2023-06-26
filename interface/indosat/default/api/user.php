<?php
class default_api_user implements api_interface {
	public function process($GET) {
		$status = $this->checkMandatory ( $GET );
		if ($status === true) {
			$user_data = new user_data ( );
			$user_data->active = 1;
			$user_data->msisdn = $GET ['msisdn'];
			$user_data->adn = $GET ['adn'];
			$user_data->service = strtolower ( $GET ['service'] );
			$user_data->operator = strtolower ( $GET ['operator'] );
			// $user_data->operator_id = loader_model::getInstance ()->load ( 'operator', 'connDatabase1' )->getOperatorId ( $GET ['operator'] );
			
			// to check object is empty or not  we must casting the object into an array then check
			$isregistered = user_manager::getInstance ()->getUserData ( $user_data );
			
			if ($isregistered)
				return array ('status' => '1', 'description' => "$user_data->msisdn is registered" );
			else
				return array ('status' => '0', 'description' => "$user_data->msisdn is not registered" );
		}
		return $status;
	}
	
	protected function checkMandatory($GET) {
		$api_config = loader_config::getInstance ()->getConfig ( 'api' );
		
		if ((empty ( $GET ['msisdn'] )) && (empty ( $GET ['operator'] )) && (empty ( $GET ['user'] )) && (empty ( $GET ['pwd'] ))) {
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