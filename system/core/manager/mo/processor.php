<?php

class manager_mo_processor {

    /**
     * @param $arrData
     */
    public function saveToFile($arrData) {
    	//print_r($arrData);
        $log_profile = 'mo_receiver';
        $log = manager_logging::getInstance ();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $log->write(array('level' => 'info', 'message' => "Info : ". print_r($arrData, 1)));

        // config data from config_loader object
        $config_main = loader_config::getInstance ()->getConfig('main'); // load main config data

        $class_name = $config_main->operator . '_mo_processor';

        if (class_exists($class_name)) {
        	//echo($class_name);
            $moProcessor = new $class_name ();
        } else if (class_exists('default_mo_processor')) {
        	//echo('default');
        	$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $moProcessor = new default_mo_processor ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_mo_processor"));
            return false;
        }
        $encodedData = (is_array($arrData)) ? json_encode($arrData) : json_encode(simplexml_load_string($arrData));
        $log->writeDefault("mo_save", $encodedData);
		//echo $encodedData."<br/>";
        return $moProcessor->saveToFile($arrData);
    }

    public function process(){
    	//test
        $log_profile = 'mo_processor';
        $log = manager_logging::getInstance ();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $config_main = loader_config::getInstance ()->getConfig('main');
        $slot = loader_config::getInstance ()->getConfig('mo')->bufferSlot;

        $class_name = $config_main->operator . '_mo_processor';
//echo $class_name;
        if (class_exists($class_name)) {
            $moProcessor = new $class_name ();
            
        } else if (class_exists('default_mo_processor')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $moProcessor = new default_mo_processor ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_mo_processor"));
            return false;
        }

        //forking process slot

        for ($i = 0; $i < $slot; $i++) {
            if ($config_main->use_forking) {
                switch ($pid = pcntl_fork ()) {
                    case - 1 :
                        $log->write(array('level' => 'error', 'message' => "Forking failed"));
                        die('Forking failed');
                        break;
                    case 0 :
                        $moProcessor->process($i);
                        exit ();
                        break;
                    default :
                        //pcntl_waitpid ( $pid, $status );
                        break;
                }
            } else {
                $moProcessor->process($i);
            }
        }
        return true;
    }

    public function process_trial(){
    	//test
        $log_profile = 'mo_processor';
        $log = manager_logging::getInstance ();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $config_main = loader_config::getInstance ()->getConfig('main');
        $slot = loader_config::getInstance ()->getConfig('mo')->bufferSlot;

        $class_name = 'default_mo_processor';
//echo $class_name;
        if (class_exists($class_name)) {
            $moProcessor = new $class_name ();
            
        } else if (class_exists('default_mo_processor')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $moProcessor = new default_mo_processor ();
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_mo_processor"));
            return false;
        }

	$moProcessor->process_trial();

        return true;
    }
}
