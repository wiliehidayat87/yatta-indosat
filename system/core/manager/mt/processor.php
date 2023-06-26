<?php

/**
 * 
 * @author	LinkIT dev Team
 * @since	2011-05-19
 * 
 * class to manage which mt processor is used by a particular operator
 *
 */
class manager_mt_processor {

    /**
     *
     * public function to get MT from Queue and send it to operator
     *
     * @param	char
     * @return	boolean
     *
     */
    public function process($type) {
        $log_profile = 'mt_processor';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start : " . $type));

        $loaderConfig = loader_config::getInstance();

        $type = strtolower(trim($type));
        $type = (empty($type)) ? 'text' : $type;

        $configMain = $loaderConfig->getConfig('main');
        $configMT = $loaderConfig->getConfig('mt');

        $className = $configMain->operator . '_mt_processor_' . $type;
        $classNameDefault = 'default_mt_processor_' . $type;

        if (class_exists($className)) {
            $mtProcessor = $className::getInstance();
            $profile = $type;
        } else if (class_exists($classNameDefault)) {
            $log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $className));
            $mtProcessor = $classNameDefault::getInstance();
            $profile = 'default';
        } else {
            $log->write(array('level' => 'error', 'message' => " Class Doesn't Exist : " . $className . " & " . $classNameDefault));
            return false;
        }

        $slotMax = $configMT->profile [$profile] ['slot'] - 1;

        for ($x = 0; $x <= $slotMax; $x++) {
            if ($configMain->use_forking) {
                switch ($pid = pcntl_fork()) {
                    case - 1 :
                        $log->write(array('level' => 'error', 'message' => "Forking failed"));
                        die('Forking failed');
                        break;

                    case 0 :
                        $mtProcessor->process($x);
                        exit();
                        break;

                    default :
                        //pcntl_waitpid ( $pid, $status );
                        break;
                }
            } else {
                $mtProcessor->process($x);
            }
        }

        return TRUE;
    }

    public function saveToQueue($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $charging_manager = charging_manager::getInstance();
        $charging_data = $charging_manager->getCharging($mt_data);
        $main_config = loader_config::getInstance()->getConfig('main');

        if ($charging_data !== false) {
            $class_name = $main_config->operator . "_mt_processor_" . $charging_data->senderType;
            $class_name_default = "default_mt_processor_" . $charging_data->senderType;

            if ($mt_data->isDelay === TRUE)
            {
                $class_name .= 'delay';
                $class_name_default .= 'delay';
            }


            if (class_exists($class_name)) {
                $processor = $class_name::getInstance();
            } else if (class_exists($class_name_default)) {
                $log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $class_name));
                $processor = $class_name_default::getInstance();
            } else {
                $log->write(array('level' => 'error', 'message' => " Class Doesn't Exist : " . $class_name . " & " . $class_name_default));
                return false;
            }

            $mt_data->serviceId = $charging_data->chargingId;
            $mt_data->price = $charging_data->gross;
            $mt_data->charging = $charging_data;

            return $processor->saveToQueue($mt_data);
        } else {
            return false;
        }
    }

    public function saveMOToTransact($mo_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mo_data)));

        $charging_manager = charging_manager::getInstance();
        $charging_data = $charging_manager->getCharging($mo_data);

        if ($charging_data !== false) {
            $mo_data->serviceId = $charging_data->chargingId;
            $mo_data->price = $charging_data->gross;
        }

        $config_hadoop = loader_config::getInstance()->getConfig('hadoop');
        if ($config_hadoop->enableMO == true && $config_hadoop->enableMT == true) {
            loader_model::getInstance()->load('tblmsgtransact', 'connHadoop')->saveTransact($mo_data);
        }
        if ($config_hadoop->enableMO == true && $config_hadoop->enableDR == true) {
            loader_model::getInstance()->load('dr', 'connHadoop')->saveDR($mo_data);
        }

        return loader_model::getInstance()->load('tblmsgtransact', 'connDatabase1')->saveMO($mo_data);
    }

}
