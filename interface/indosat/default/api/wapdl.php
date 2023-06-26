<?php
class default_api_wapdl extends manager_api {
	public function process($GET) {
		$status = $this->checkMandatory ( $GET );
		if ($status === true) {
			$content_code = $GET ['code'];
			$type = $GET ['type'];
			$service = $GET ['service'];
			$operator = $GET ['operator'];
			$msisdn = $GET ['msisdn'];
			$shortcode = $GET ['sdc']; //adn
			$transID = $GET ['transID'];
			$id;
			$direct = 1;
			
			$api_config = loader_config::getInstance ()->getConfig ( 'api' );
			$url = $api_config->api_dl . "?code=$content_code&type=$type&service=$service&operator=$operator&msisdn=$msisdn&sdc=$shortcode&transID=$transID&direct=1";
			
			$response = file_get_contents ( $url );
			//$result = json_decode ( $result, TRUE )
			return $response;
		}
		return $status;
	}
	
	protected function checkMandatory($GET) {
		$api_config = loader_config::getInstance ()->getConfig ( 'api' );
		
		if ((empty ( $GET ['msisdn'] )) && (empty ( $GET ['operator'] )) && (empty ( $GET ['user'] )) && (empty ( $GET ['pwd'] ))) {
			return array ('status' => 'NOK', 'msg' => 'invalid parameter' );
		} else {
			if (($GET ['user'] == $api_config->user) && ($GET ['pwd'] == $api_config->password)) {
				return true;
			} else {
				return array ('status' => 'NOK', 'msg' => 'username and password invalid' );
			}
		}
	}
}