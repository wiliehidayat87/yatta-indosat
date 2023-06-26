<?php

/**
 * 
 * Manager Cmp Processor
 * 
 *
 */
class manager_cmp_processor {

    public function process($arrdata) {
		$log = manager_logging::getInstance();

        $log->setProfile('cmp_processor');
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        $config_main = loader_config::getInstance()->getConfig('main');
		
		$hset = loader_model::getInstance()->load('hset', 'cmp');
		$hset_data = new model_data_hset();
		$hset_data->service = str_replace("\n", '',strtolower($arrdata['service']));
		$hset_data->adn = '99879';//$arrdata['adn'];
		$hset_data->operator_name = $config_main->operator;
		/* 
		if(strpos($arrdata['partner'],"pocket") !== FALSE)
			error_log(serialize($arrdata),3,'/tmp/checkpartner.log'); */
		
		if(strpos($arrdata['partner'],"pocket") !== FALSE){
			$hset_data->keyword = "pocketmedia";
			$arrdata['partner'] = "pocketmedia";
		}else{
			$hset_data->keyword = $arrdata['partner'];
		}
		
		$hset_records = $hset->getHset($hset_data);		
		//var_dump($hset_records);exit;
		if($hset_records) {
			if($hset_records['handler']!='' && class_exists($hset_records['handler'])) {
				$cmpProcessor = new $hset_records['handler']();
			} else {
				$class_name = $config_main->operator . '_cmp_processor_' . $arrdata['partner'];
				
				if (class_exists($class_name)) {
					$cmpProcessor = new $class_name();
					//echo $class_name;
				} else if (class_exists('default_cmp_processor')) {
					$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
					$cmpProcessor = new default_cmp_processor ();
					//echo default_dr_processor;
				} else {
					$log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_mo_processor"));
					return false;
				}
				
				$cmpProcessor->process($arrdata,$hset_records);
			}
		}
    	
		return true;		
	}

	public function build($slot) {
	        $log = manager_logging::getInstance();
        	$log->write(array('level' => 'debug', 'message' => 'Start : ' . $slot));

	        $load_config = loader_config::getInstance();
        	$buffer_file = buffer_file::getInstance();

	        $config_dr = $load_config->getConfig('cmp');

        	$path = $config_dr->bufferPath . '/' . $slot;
	        $limit = $config_dr->bufferThrottle;
        	$result = $buffer_file->read($path, $limit, 'cmp');

	        if ($result !== false) {
        	    foreach ($result as $val) {
	                foreach ($val as $drDataPath => $data) {
        	            $log->write(array('level' => 'debug', 'message' => "path : " . $drDataPath . " content : " . serialize($data)));

	                    if (is_object($data)) {
                		$cmp_data['msisdn'] = (isset($data->msisdn)) ? $data->msisdn : '';
		                $cmp_data['msgId'] = (isset($data->txid)) ? $data->txid : '';
                		$cmp_data['service'] = (isset($data->service)) ? $data->service : '';
		                $cmp_data['adn'] = (isset($data->adn)) ? $data->adn : '';
		                $cmp_data['partner'] = (isset($data->partner)) ? $data->partner : '';
                		$cmp_data['id'] = (isset($data->id)) ? $data->id : '';
                		$cmp_data['instid'] = (isset($data->instid)) ? $data->instid : '';
		                $cmp_data['status'] = (isset($data->status)) ? $data->status : '';

        	                $drSave = $this->process($cmp_data);
	                        if ($drSave) {
                	            $buffer_file->delete($drDataPath);
        	                }
	                    } else {
                	        $log->write(array('level' => 'error', 'message' => "buffer DR is not an object"));
        	                $buffer_file->delete($drDataPath);
	                    }
                	}
        	    }
	        }
		
	}

	public function run() {
	        $log = manager_logging::getInstance();

	        $log->setProfile('cmp_processor');
        	$log->write(array('level' => 'debug', 'message' => 'Start'));

	        $config_cmp = loader_config::getInstance()->getConfig('cmp');
        	$slot = loader_config::getInstance()->getConfig('cmp')->bufferSlot;

	        /*$class_name = $config_main->operator . '_cmp_processor';
        	if (class_exists($class_name)) {
	            $cmpProcessor = new $class_name ();
        	} else if (class_exists('default_cmp_processor')) {
	            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
        	    $cmpProcessor = new default_cmp_processor ();
	        } else {
        	    $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_cmp_processor"));
	            return false;
        	}*/

	        for ($i = 0; $i < $slot; $i++) {
			if ($config_cmp->use_forking) {
		            switch ($pid = pcntl_fork()) {
	        	        case - 1 :
        	        	    $log->write(array('level' => 'error', 'message' => "Forking failed"));
	                	    die('Forking failed');
	                	    break;

        		        case 0 :
	        	            $this->build($i);
                		    exit();
	        	            break;
	
		                default :
                		    //pcntl_waitpid ( $pid, $status );
        	        	    break;
		            }
			} else {
				 $this->build($i);
			}
        	}

	        return true;
	}
	public function saveToBuffer($data) {
		$log = manager_logging::getInstance();
		$log->setProfile('cmp_processor');
	        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($data)));

        	$config_cmp = loader_config::getInstance()->getConfig('cmp');
	        $cmp_data = loader_data::get('cmp');
        	$buffer_file = buffer_file::getInstance();
        
	        $cmp_data->msisdn = (isset($data['msisdn'])) ? $data['msisdn'] : '';
        	$cmp_data->msgId = (isset($data['txid'])) ? $data['txid'] : '';
		$cmp_data->service = (isset($data['service'])) ? $data['service'] : '';
		$cmp_data->adn = (isset($data['adn'])) ? $data['adn'] : '';
		$cmp_data->partner = (isset($data['partner'])) ? $data['partner'] : '';
		$cmp_data->id = (isset($data['id'])) ? $data['id'] : '';
		$cmp_data->instid = (isset($data['instid'])) ? $data['instid'] : $data['msisdn'];
		$cmp_data->status = (isset($data['status'])) ? $data['status'] : '';

        	$path = $buffer_file->generate_file_name($cmp_data, 'cmp', 'cmp');


	        if ($buffer_file->save($path, $cmp_data)) {
        	    return $config_cmp->returnCode ['OK'];
	        } else {
        	    return $config_cmp->returnCode ['NOK'];
	        }
	}
}
