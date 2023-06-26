<?php

class manager_broadcast {

    public function execute() {
        $log_profile = 'broadcast';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $schedules = $this->populateSchedule();
        $total = count($schedules);

        $main_config = loader_config::getInstance()->getConfig('main');
        $operator_name = $main_config->operator;

        if ($total > 0) {
            foreach ($schedules as $broadcast_data) {
                $model_schedule = loader_model::getInstance()->load('schedule', 'connBroadcast');
                $broadcast_data->status = 1;
                $model_schedule->setStatus($broadcast_data); //set status to 1

                $class_name = $operator_name . "_broadcast_" . $broadcast_data->handlerFile;
                $class_default = "default_broadcast_" . $broadcast_data->handlerFile;

                if (class_exists($class_name)) {
                    $broadcast = new $class_name ();
                } elseif (class_exists($class_default)) {
                    $log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $class_name));
                    $broadcast = new $class_default ();
                } elseif (class_exists('default_broadcast_base')) {
                    $log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $class_name . " & " . $class_default));
                    $broadcast = new default_broadcast_base ();
                } else {
                    $log->write(array('level' => 'error', 'message' => " Class Doesn't Exist : " . $class_name . " & " . $class_default . " & " . 'default_broadcast_base'));
                    return false;
                }

                //if ($broadcast->push($broadcast_data) == true)
                $broadcast->push($broadcast_data);
                $broadcast_data->status = 2;

                $model_schedule->setStatus($broadcast_data); // set status to 2
            }
            /* foreach ( $schedules as $broadcast_data ) {
              switch ($pid = pcntl_fork ()) {
              case - 1 :
              die ( 'Forking failed' );
              break;

              case 0 :
              $broadcast_data->status = 1;
              $model_schedule->setStatus ( $broadcast_data ); //set status to 1


              $class_name = $operator_name . "_broadcast_" . $broadcast_data->handlerFile;
              $class_default = "default_broadcast_" . $broadcast_data->handlerFile;

              if (class_exists ( $class_name )) {
              $broadcast = new $class_name ( );
              } else {
              $broadcast = new $class_default ( );
              }

              $broadcast->push ( $broadcast_data );
              //$broadcast_base = new broadcast_base ( );
              //$broadcast_base->push ( $broadcast_data );

              $broadcast_data->status = 2;
              $model_schedule->setStatus ( $broadcast_data ); // set status to 2
              exit ();
              break;

              default :
              pcntl_waitpid ( $pid, $status );
              break;
              }
              } */
        }
        return true;
    }

    public function populateSchedule($status = '0') {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $main_config = loader_config::getInstance()->getConfig('main');
        $operator_name = $main_config->operator;

        $model_schedule = loader_model::getInstance()->load('schedule', 'connBroadcast');
        $broadcast = new broadcast_data ();
        $broadcast->operator = $operator_name;
        $broadcast->status = $status;
        $data = $model_schedule->get($broadcast);

        //create object broadcast data
        $broadcast_data = array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $dt = new broadcast_data ();

                $dt->id = $val ['id'];
                $dt->service = $val ['service'];
                $dt->operator = $val ['operator'];
                $dt->adn = $val ['adn'];
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

    public function process() {
        $log_profile = 'broadcast';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        // check handler        
        $main_config = loader_config::getInstance()->getConfig('main');
        $operator_name = $main_config->operator;

        $class_name = $operator_name . "_broadcast_base";
        $class_default = "default_broadcast_base";

        if (class_exists($class_name)) {
        	$broadcast = new $class_name ();
		} elseif (class_exists($class_default)) {
        	$log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $class_name));
            $broadcast = new $class_default ();
		} elseif (class_exists('default_broadcast_base')) {
        	$log->write(array('level' => 'info', 'message' => " Class Doesn't Exist : " . $class_name . " & " . $class_default));
        	$broadcast = new default_broadcast_base ();
        } else {
			$log->write(array('level' => 'error', 'message' => " Class Doesn't Exist : " . $class_name . " & " . $class_default . " & " . 'default_broadcast_base'));
        	return false;
 		}        
        $broadcast->sendtoQueue();

        return true;
    }
    
    public function resetSchedule() {
        $log_profile = 'broadcast';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $broadcast = new default_broadcast_base ();
        $broadcast->resetSchedule();

        return true;
    }

}

