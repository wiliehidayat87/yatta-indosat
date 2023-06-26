<?php

/**
 * 
 * @author	LinkIT dev Team
 * @since	2011-05-19
 * 
 * class to manage which mt broadcast is used by a particular operator
 *
 */
class manager_mt_broadcast {

    /**
     * 
     * public function to get MT from Queue and send it to operator
     * 
     * @param	char
     * @return	boolean
     * 
     */
    public function process($type) {
        $log_profile = 'broadcast';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $loaderConfig = loader_config::getInstance();

        $type = strtolower(trim($type));
        $type = (empty($type)) ? 'dailypush' : $type;

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

        $schedules = $this->populateSchedule();
        $total = count($schedules);
		
        if ($total > 0) {
            foreach ($schedules as $broadcast_data) {
                switch ($pid = pcntl_fork()) {
                    case - 1 :
                        $log->write(array('level' => 'error', 'message' => "Forking failed"));
                        die('Forking failed');
                        break;

                    case 0 :
                        $mtProcessor->process($broadcast_data->id);
						
                        exit();
                        break;

                    default :
                        //pcntl_waitpid ( $pid, $status );
                        
                        break;
                }
            }
        }
		
		
		
        //return TRUE;
    }

    private function populateSchedule() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $main_config = loader_config::getInstance()->getConfig('main');
        $operator_name = $main_config->operator;

        $model_schedule = loader_model::getInstance()->load('schedule', 'connBroadcast');
        $broadcast = new broadcast_data ( );
        $broadcast->operator = $operator_name;
        $broadcast->status = '2';
        $data = $model_schedule->get($broadcast);

        //create object broadcast data
        $broadcast_data = array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dt = new broadcast_data ( );

                $dt->id = $val ['id'];
                $dt->service = $val ['service'];
                $dt->operator = $val ['operator'];
                $dt->sdc = $val ['sdc'];
                $dt->recurringType = $val ['recurring_type'];
                $dt->handlerFile = $val ['handlerfile'];
                $dt->pushTime = $val ['push_time'];
                $dt->status = $val ['status'];
                $dt->contentLabel = $val ['content_label'];
                $dt->contentSelect = $val ['content_select'];
                $dt->lastContentId = $val ['last_content_id'];
                $dt->price = $val ['price'];
                $dt->notes = $val ['notes'];
                $dt->created = $val ['created'];
                $dt->modified = $val ['modified'];

                $broadcast_data [] = $dt;
            }
        }
        return $broadcast_data;
    }

}
