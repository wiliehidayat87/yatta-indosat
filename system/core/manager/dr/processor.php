<?php

/**
 * 
 * Manager DR Processor
 * 
 *
 */
class manager_dr_processor {

    public function process() {
    	//echo 'here';
        $log = manager_logging::getInstance();

        $log->setProfile('dr_processor');
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        $config_main = loader_config::getInstance()->getConfig('main');
        $slot = loader_config::getInstance()->getConfig('dr')->bufferSlot;

        $class_name = $config_main->operator . '_dr_processor';
        
        if (class_exists($class_name)) {
            $drProcessor = new $class_name();
            //echo $class_name;
        } else if (class_exists('default_dr_processor')) {
            $log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
            $drProcessor = new default_dr_processor ();
            //echo default_dr_processor;
        } else {
            $log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . "default_mo_processor"));
            return false;
        }

        for ($i = 0; $i < $slot; $i++) {
            if ($config_main->use_forking) {
                switch ($pid = pcntl_fork()) {
                    case - 1 :
                        $log->write(array('level' => 'error', 'message' => "Forking failed"));
                        die('Forking failed');
                        break;

                    case 0 :
                        $drProcessor->process($i);
                        exit();
                        break;

                    default :
                        //pcntl_waitpid ( $pid, $status );
                        break;
                }
            } else {
                $drProcessor->process($i);
            }
        }

        return true;
    }

    public function updateTransact($arr) {
        $log = manager_logging::getInstance();

        $log->setProfile('dr_updater');
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($arr)));

        $configMain = loader_config::getInstance()->getConfig('main');

        $className = $configMain->operator . '_dr_processor';

        $objUsed = (class_exists($className)) ? $className : 'default_dr_processor';

        $log->write(array('level' => 'debug', 'message' => 'Call Class ' . $objUsed));

        $obj = new $objUsed;
        $result = $obj->updateTransact($arr);

        $log->write(array('level' => 'info', 'message' => 'Return Value ' . str_replace(array("\r\n", "\n", "\t", "\r"), ' ', $result)));

        return $result;
    }

    public function saveToBuffer($str) {
        $log = manager_logging::getInstance();

        $log->setProfile('dr_receiver');
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        $configMain = loader_config::getInstance()->getConfig('main');

        $className = $configMain->operator . '_dr_processor';

        $objUsed = (class_exists($className)) ? $className : 'default_dr_processor';

        $log->write(array('level' => 'debug', 'message' => 'Call Class ' . $objUsed));

        $obj = new $objUsed;
        $result = $obj->saveToBuffer($str);

        $log->write(array('level' => 'info', 'message' => 'Return Value ' . str_replace(array("\r\n", "\n", "\t", "\r"), ' ', $result)));

        return $result;
    }

}