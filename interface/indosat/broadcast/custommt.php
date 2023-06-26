<?php
class indosat_broadcast_custommt extends default_broadcast_base {
	/**
	 * @param $broadcast_data
	 */
	public function push(broadcast_data $broadcast_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		if ($broadcast_data->contentSelect == "custom") {
			$custombase = new default_broadcast_custombase ( $broadcast_data );
			return $custombase->push ( $broadcast_data );
		}
		
		$content_manager = content_manager::getInstance ();
		$broadcast_content = $content_manager->getBroadcastContent ( $broadcast_data );
		
		if ($broadcast_content == false) {
			return false;
		}
		
		$model_operator = loader_model::getInstance ()->load ( 'operator', 'connDatabase1' );
		$operator_name = $broadcast_data->operator;
		$operator_id = $model_operator->getOperatorId ( $operator_name );
		
		$users = $this->populateUser ( $broadcast_data );
		
		if ($users !== false) {
			$pushproject_data = new model_data_pushproject ();
			$pushproject_data->sid = $broadcast_data->id;
			$pushproject_data->src = $broadcast_data->adn;
			$pushproject_data->oprid = $operator_id;
			$pushproject_data->service = $broadcast_data->service;
			$pushproject_data->subject = strtoupper ( "MT;PUSH;SMS;DAILYPUSH" );
			$pushproject_data->message = $broadcast_content->content;
			$pushproject_data->price = $broadcast_data->price;
			$pushproject_data->amount = 0;
			
			$mPushProject = loader_model::getInstance ()->load ( 'pushproject', 'connBroadcast' );
			$pid = $mPushProject->save ( $pushproject_data );
			
			$amount = 0;
			foreach ( $users as $users_data ) {
				$pushbuffer_data = new model_data_pushbuffer ();
				$pushbuffer_data->pid = $pid;
				$pushbuffer_data->src = $broadcast_data->adn;
				$pushbuffer_data->dest = $users_data ['msisdn'];
				$pushbuffer_data->oprid = $operator_id;
				$pushbuffer_data->service = $broadcast_data->service;
				$pushbuffer_data->subject = strtoupper ( "MT;PUSH;SMS;DAILYPUSH" );
				//$pushbuffer_data->message = $broadcast_content->content;
				$pushbuffer_data->message = $this->getCustomMessage($broadcast_content->content,$users_data ['msisdn'],$broadcast_data->service);
				
				$pushbuffer_data->price = $broadcast_data->price;
				$pushbuffer_data->stat = "ON_QUEUE";
				$pushbuffer_data->tid = date ( "YmdHis" ) . str_replace ( '.', '', microtime ( true ) );
				$pushbuffer_data->thread_id = substr ( $pushbuffer_data->dest, strlen ( $pushproject_data->dest ) - 1, 1 );
				
				$mt_data = $this->createMT ( $pushbuffer_data, $broadcast_data );
				$pushbuffer_data->obj = serialize ( $mt_data );
				
				$mPushBuffer = loader_model::getInstance ()->load ( 'pushbuffer', 'connBroadcast' );
				
				if ($mPushBuffer->save ( $pushbuffer_data )) {
					$amount ++;
				}
			}
			echo $amount;
			$pushproject_data = new model_data_pushproject ();
			$pushproject_data->pid = $pid;
			$pushproject_data->amount = $amount;
			$mPushProject->update ( $pushproject_data );
		}
		
		return true;
	}
	
	public function getCustomMessage($message,$msisdn,$service) {
		$result = $message;
		$funct = new funct_custommt();

		$custommt_config = loader_config::getInstance()->getConfig('custommt');
		//$filepath = $custommt_config->mt[$service]['file'];
		//$filepath = str_replace('{date}',date('Ymd',strtotime('-1 days')),$custommt_config->mt[$service]['file']);
		$file_dir = $custommt_config->mt[$service]['file'];
		$filepath = $funct->getLatestFile($file_dir);
		$filepath = $file_dir.$filepath;

		if(file_exists($filepath)) {
			$maxMt = $custommt_config->mt[$service]['max_mt'];

			$records = $funct->getDataFile($filepath);
			$totalMtPush = $funct->getCountPush($records,$msisdn);
			if($totalMtPush) {
				if($funct->isCount($totalMtPush,$maxMt)) {
					$result = $custommt_config->mt[$service]['message'];
				}
			}
		}

		return $result;
	}
	
	/**
	 * @param $broadcast_data
	 */
	public function populateUser(broadcast_data $broadcast_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$model_user = loader_model::getInstance ()->load ( 'user', 'connDatabase1' );
		return $model_user->execUser ( $broadcast_data );
	}
	
	/**
	 * @param $broadcast_data
	 * @param $user_data
	 */
	public function createMT(model_data_pushbuffer $pushbuffer_data, broadcast_data $broadcast_data) {
		$log = manager_logging::getInstance ();
		
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$mt_data = loader_data::get ( 'mt' );
		$mt_data->inReply = NULL;
		$mt_data->msgId = date ( "YmdHis" ) . str_replace ( '.', '', microtime ( true ) );
		$mt_data->adn = $pushbuffer_data->src;
		$mt_data->msgData = $pushbuffer_data->message;
		$mt_data->price = $pushbuffer_data->price;
		$mt_data->operatorId = $pushbuffer_data->oprid;
		$mt_data->channel = "sms";
		$mt_data->service = $pushbuffer_data->service;
		$mt_data->subject = $pushbuffer_data->subject;
		$mt_data->operatorName = $broadcast_data->operator;
		$mt_data->msisdn = $pushbuffer_data->dest;
		$mt_data->type = "dailypush";
		
		return $mt_data;
	}
	
	// this is called from broadcast manager
	public function sendtoQueue() {
		global $params;
		
		$service = $params['s'];
		$thread_id = $params['t'];
		if ($params['n']) $limit = $params['n']; else {
			$broadcast_config = loader_config::getInstance ()->getConfig ( 'broadcast' );
			$limit = $broadcast_config->limit;
		}

		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$operator_name = $main_config->operator;
		
		$model_operator = loader_model::getInstance ()->load ( 'operator', 'connDatabase1' );
		$operatorId = $model_operator->getOperatorId ( $operator_name );
		
		$mPushProject = loader_model::getInstance ()->load ( 'pushproject', 'connBroadcast' );
		$pushproject_data = new model_data_pushproject ();
		//$pushproject_data->status = '0';
		$pushproject_data->created = date ( 'Y-m-d' );
		$pushproject_data->oprid = $operatorId;
		$pushProjects = $mPushProject->get ( $pushproject_data );
		
		if ($pushProjects === false) {
			$log->write ( array ('level' => 'info', 'message' => "Data project not found" ) );
			return false;
		}
		
		// push projects
		$services = array();
		$pid_services = array();		
		foreach ( $pushProjects as $pushProject ) {
			array_push($services, $pushProject ['service']);
			$pid_services[$pushProject['service']] = $pushProject ['pid'];	
		}
		
		if (in_array($service, $services)){
			$log->write ( array ('level' => 'error', 'message' => "Worker #{$thread_id} is spawing..." ) );
			//$this->processSendToQueue ( $operator_name, $pushProject ['pid'], $service, true, $thread_id );
			$this->processSendToQueue ( $operator_name, $pid_services[$service], $service, true, $thread_id );								
		}		
		return true;
	}
	
	public function processSendToQueue($operator_name, $pushProject_pid, $service_name, $forking = false, $thread_id = 0, $limit=1000) {
		
		$log = manager_logging::getInstance ();
		
		if ($forking) {
			$lockPath = '/tmp/lock_default_broadcast_queue_' . $operator_name . '_' . $pushProject_pid . '_' . $service_name . '_' . $thread_id;
		} else {
			$lockPath = '/tmp/lock_default_broadcast_queue_' . $operator_name . '_' . $pushProject_pid . '_' . $service_name;
		}
		
		if (file_exists ( $lockPath )) {
			$log->write ( array ('level' => 'error', 'message' => "Lock File Exist on : " . $lockPath ) );
			echo "NOK - Lock File Exist on $lockPath \n";
			exit ( 0 );
		} else {
			touch ( $lockPath );
		}
		
		$model_operator = loader_model::getInstance ()->load ( 'operator', 'connDatabase1' );
		$operatorId = $model_operator->getOperatorId ( $operator_name );
		
		$mPushProject = loader_model::getInstance ()->load ( 'pushproject', 'connBroadcast' );
		
		$log->write ( array ('level' => 'debug', 'message' => " Slot [$operator_name - $operatorId] : [" . $pushProject_pid . "][" . $service_name . "][" . $thread_id . "]" ) );
		$model_user = loader_model::getInstance ()->load ( 'pushbuffer', 'connBroadcast' );
		$buffers = $model_user->execPushbufferWithThread ( $pushProject_pid, $service_name, $operatorId, $thread_id, $limit );
		
		if ($buffers !== false) {
			$processed = 0;
			foreach ( $buffers as $pushBuffer ) {
				$log->write ( array ('level' => 'debug', 'message' => " Update stat : ON_PROCESS" ) );
				$pushbuffer_data = new model_data_pushbuffer ();
				$pushbuffer_data->id = $pushBuffer ['id'];
				
				/*				
				$pushbuffer_data->stat = "ON_PROCESS";
				$model_user->update ( $pushbuffer_data );
				*/
				
				$mt_data = unserialize ( $pushBuffer ['obj'] );
				$mt_data->pushBufferId = $pushBuffer ['id'];
				
				$mt_processor = new manager_mt_processor ();
				$queue = $mt_processor->saveToQueue ( $mt_data );
				
				if ($queue === false) {
					$log->write ( array ('level' => 'debug', 'message' => " Update stat : PENDING" ) );
					$pushbuffer_data->stat = "PENDING";
					$model_user->update ( $pushbuffer_data );
				} else {
					$log->write ( array ('level' => 'debug', 'message' => " Update stat : PUSHED" ) );
					$pushbuffer_data->stat = "PUSHED";
					if ($model_user->update ( $pushbuffer_data )) {
						$processed ++;
					}
				}
			}
			
			$pushproject_data = new model_data_pushproject ();
			$pushproject_data->pid = $pushProject_pid;
			$pushproject_data->processed = $processed;
			$pushproject_data->status = '1';
			
			$mPushProject->update ( $pushproject_data );
		}
		unlink ( $lockPath );
		return true;
	}
	
	public function resetSchedule() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$broadcast = new manager_broadcast ();
		$schedules = $broadcast->populateSchedule ( '2' );
		
		if (count ( $schedules ) > 0) {
			foreach ( $schedules as $broadcast_data ) {
				$model_schedule = loader_model::getInstance ()->load ( 'schedule', 'connBroadcast' );
				if ($broadcast_data->recurringType > 0)
					$model_schedule->reset ( $broadcast_data );
			}
		}
		return true;
	}

}
