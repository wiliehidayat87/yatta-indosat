<?php

class manager_service_generator {

    private static $instance;

    private function __construct() {

    }

    public static function getInstance() {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance)
            self::$instance = new self ();

        return self::$instance;
    }

    public function runHandler($mo_data) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $main_config = loader_config::getInstance ()->getConfig('main');
        $loader_model = loader_model::getInstance ();

        $model_operator = $loader_model->load('operator', 'connDatabase1');

        $mo_data->operatorName = $main_config->operator;
        $mo_data->operatorId = $model_operator->getOperatorId($mo_data->operatorName);

        $mechanism = $loader_model->load('mechanism', 'connDatabase1');
        $arrHandler = $mechanism->readAll($mo_data);
        $service_handler = $this->findhandler($mo_data, $arrHandler);

        if (class_exists($service_handler)) {
            $service = new $service_handler ();
        } else if (class_exists('default_service_handler_error')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $service_handler));
            $service = new default_service_handler_error ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $service_handler . " & " . "default_service_handler_error"));
            return false;
        }

        $mo_data = $service->notify($mo_data);

        return $mo_data;
    }

    private function findhandler($mo_data, $arrHandler) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $msgCount = count(explode(' ', $mo_data->msgData));
        $loop = $msgCount + ($msgCount - 1);
        $msgData = strtolower($mo_data->msgData);
	$log->write(array('level' => 'debug', 'message' => "Check Value : ".$msgData." - ".$loop." - ".$msgCount));
        for ($x = 0; $x < $loop; $loop--) {
            if ($arrHandler !== false) {
                foreach ($arrHandler as $data) {
					
					if(strlen($msgData) > strlen($data ['pattern'])) {
						
						$keywordChecker = strpos($data ['pattern'], $msgData);
						//echo "1 - " . $data ['pattern']."-".$msgData ."--". $keywordChecker . PHP_EOL;
						
					} else if(strlen($data ['pattern']) == strlen($msgData)) {
						
						$keywordChecker = strpos($msgData, $data ['pattern']);
						
						//echo "2 - " . $data ['pattern']."-".$msgData ."--". $keywordChecker . PHP_EOL;
						
					} else {
						
						$keywordChecker = strpos($msgData, $data ['pattern']);
						
						//echo "3 - " . $data ['pattern']."-".$msgData ."--". $keywordChecker . PHP_EOL;
						
					}
					
					if(isset($mo_data->flagKeyword))
					{
						if($mo_data->flagKeyword == 'REG')
						{
							if ($keywordChecker !== false || (strpos($msgData, ' ') === false && $data ['pattern'] == "*")) {
								$mo_data->service = $data ['name'];
								$mo_data->patternId = $data ['id'];
								
								//echo "4 - " . $data ['pattern']."-".$msgData ."--". $data ['handler'] . PHP_EOL;
								
								return $data ['handler'];
							}
						}
						else if($mo_data->flagKeyword == 'UNREG') 
						{
							if ($data ['pattern'] == $msgData || (strpos($msgData, ' ') === false && $data ['pattern'] == "*")) {
								$mo_data->service = $data ['name'];
								$mo_data->patternId = $data ['id'];
								
								//echo "5 - " . $data ['pattern']."-".$msgData ."--". $data ['handler'] . PHP_EOL;
								return $data ['handler'];
							}
						}
					} else {
						
						if ($data ['pattern'] == $msgData || (strpos($msgData, ' ') === false && $data ['pattern'] == "*")) {
							$mo_data->service = $data ['name'];
							$mo_data->patternId = $data ['id'];
							
							//echo "6 - " . $data ['pattern']."-".$msgData ."--". $data ['handler'] . PHP_EOL;
							return $data ['handler'];
						}
					
						//echo "7 - " . $data ['pattern']."-".$msgData ."--". $data ['handler'] . PHP_EOL;
					}
					
					//echo "8 - " . $data ['pattern']."-".$msgData ."--". $data ['handler'] . PHP_EOL;
                    //if ($data ['pattern'] == $msgData || (strpos($msgData, ' ') === false && $data ['pattern'] == "*")) {
                    /* if ($keywordChecker !== false || (strpos($msgData, ' ') === false && $data ['pattern'] == "*")) {
                        $mo_data->service = $data ['name'];
                        $mo_data->patternId = $data ['id'];
                        return $data ['handler'];
                    } */
                }
            }
            if (strpos($msgData, '*') === false)
                $msgData = preg_replace('~\s+\S+$~', ' *', $msgData);
            else
                $msgData = preg_replace('~\s+\S+$~', '', $msgData);
        }
        $log->write(array('level' => 'debug', 'message' => "Handler not found : " . $msgData));
        return false;
    }

}
