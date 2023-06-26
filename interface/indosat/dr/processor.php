<?php

class indosat_dr_processor extends default_dr_processor {

    private function _setStatus($drData) 
    {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($drData)));
       
	$config_main = loader_config::getInstance()->getConfig('main'); 
        //var_dump($drData);
        
        $mtData = loader_data::get('mt');
        $mtData->closeReason = $drData->statusCode;
        $mtData->msgId = $drData->msgId;
        
	//$mtData->adn = $config_main->adn;
	$mtData->adn = $drData->adn;
        //$mtData->adn = $drData->adn; get adn from config
        $mtData->msisdn = $drData->msisdn;
        $mtData->status = $drData->statusInternal;
        $mtData->serviceId = $drData->serviceId;

        return loader_model::getInstance()->load('tblmsgtransact', 'connDatabase1')->setStatus($mtData);
    }

    /**
     * update from cdr table to transact
     *
     * @param 
     * 		array
     * 			-q : from hour
     * 			-w : to hour
     * 			-c : conn DB used to updating
     * 			-f : from database.table
     * 			-t : to database.table
     * 			
     */
    public function updateTransact($arr) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($arr)));

        if (empty($arr['c'])) {
            $log->write(array('level' => 'debug', 'message' => 'Parameter missing, exiting script'));
            return false;
        }

        $configDr = loader_config::getInstance()->getConfig('dr');
        $parameter = array(
            'q' => date('H'),
            'w' => date('H', strtotime('-' . $configDr->defaultHour . ' hour')),
            'f' => 'cdr.cdr_' . date('Ymd'),
            't' => 'tbl_msgtransact'
        );

        foreach ($parameter as $params => $value) {
            if (!isset($arr[$params])) {
                $arr[$params] = $value;
            }
        }
        return loader_model::getInstance()->load('cdr', $arr['c'])->updateTransact($arr);
    }

    public function saveToDb($str) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        $drData = $this->getDRData($str);

        $load_config = loader_config::getInstance();
        $config_dr = $load_config->getConfig('dr');
        $type = 'text';

        $drData->closeReason = $str->closeReason;
        $drData->statusText = $str->statusText;
        $drData->statusCode = $str->statusCode;
        $drData->statusInternal = $str->msgStatus;
        $drData->serviceId = $str->serviceId;
        $drData->cdrHour = date('G');	
        $save = loader_model::getInstance()->load('cdr', 'connDr')->create($drData);
        $log->write(array('level' => 'debug', 'message' => 'Return Value for Save is ' . $save));

       	//var_dump($drData);
        
        $this->_setStatus($drData);

		/*
		if($drData->msisdn == "GUEST" 
			|| $drData->msisdn == "6285710291096" 
			|| $drData->msisdn == "6285798614406" 
			|| $drData->msisdn == "6285813812423" 
			)
		{
		*/
			$this->_setActiveGameSubscriber($drData);
		/*
		}
		*/

        return true;
    }

    public function _setActiveGameSubscriber($drData)
    {	
		$log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($drData)));
		
		$load_config = loader_config::getInstance();
        $config_main = $load_config->getConfig('main');
		
		$model_transact = loader_model::getInstance()->load('custommodel', 'connDatabase1');

		//PULL GAME
		
		if(strtoupper($dataTansact['SUBJECT']) == "MO;PULL;SMS;HANDLERCREATOR" && $drData->statusInternal == "DELIVERED")
		{			
			$dataTansact = $model_transact->getPULLTransact($drData);
			
			/*
			if(strtoupper($drData->service) == "DANGDUT")
			{				
				if(strtoupper($dataTansact['MSGDATA']) == "REG DANGDUT"){
					$url = "http://gameasik.mobi/membership/passwordgenerator/".$drData->msisdn."/1/1/";
				}else if(strtoupper($dataTansact['MSGDATA']) == "UNREG DANGDUT"){
					$url = "http://gameasik.mobi/membership/unsub/".$drData->msisdn."/";
				}
				
				$hit = http_request::get($url, "", 10);
				$hit = trim(strtoupper($hit));
				$log->write(array('level' => 'debug', 'message' => 'Response hit url ['.$url.']: ' . $hit));
			}
			else if(strtoupper($drData->service) == "MUSIK")
			{
				if(strtoupper($dataTansact['MSGDATA']) == "REG MUSIK"){
					$url = "http://m.eagame.mobi/eagame/membership/passwordgenerator/".$drData->msisdn."/1/1/";
				}else if(strtoupper($dataTansact['MSGDATA']) == "UNREG MUSIK"){
					$url = "http://m.eagame.mobi/eagame/membership/unsub/".$drData->msisdn."/";
				}
				
				$hit = http_request::get($url, "", 10);
				$hit = trim(strtoupper($hit));
				$log->write(array('level' => 'debug', 'message' => 'Response hit url ['.$url.']: ' . $hit));
			}
			
			if(strtoupper($drData->service) == "GAME_PULL1")
			{
				if(strtoupper($dataTansact['MSGDATA']) == "GAME"){
					$url = "http://m.eagame.mobi/eagame/membership/passwordgenerator/".$drData->msisdn."/1/1/";
				}
				
				$hit = http_request::get($url, "", 10);
				$hit = trim(strtoupper($hit));
				$log->write(array('level' => 'debug', 'message' => 'Response hit url ['.$url.']: ' . $hit));
			}
			*/
		}
		
		//PUSH GAME
		
		if(strtoupper($drData->service) == "ASIK" || strtoupper($drData->service) == "TOP")
		{
			/* $read = fopen("/app/xmp2012/logs/indosat/game_setting/setting_gameasik.txt", "r");
			$contents = fread($read, 4906);
			$contents = unserialize($contents);
			fclose($read); */
			
			//if($contents['REG'] == "ON")
			//{
				/* $dataTansact = $model_transact->getREGTransact($drData);
				
				if((strtoupper($dataTansact['SUBJECT']) == "MT;PULL;SMS;TEXT" || $drData->subject == "MT;PULL;SMS;TEXT") 
					&& $drData->statusInternal == "DELIVERED"){
					$premium = 1; $pull = 0;
				}else if((strtoupper($dataTansact['SUBJECT']) == "MT;PUSH;SMS;TEXT" || $drData->subject == "MT;PUSH;SMS;TEXT") 
					&& $drData->statusInternal == "DELIVERED"){
					$premium = 1; $pull = 0;
				}else if((strtoupper($dataTansact['SUBJECT']) == "MT;PUSH;SMS;TEXT" || $drData->subject == "MT;PUSH;SMS;TEXT") 
					&& $drData->statusInternal == "FAILED"){
					$premium = 0; $pull = 0;
				}else if(strtoupper($dataTansact['SUBJECT']) == "MO;PULL;SMS;HANDLERCREATOR" || $drData->subject == "MO;PULL;SMS;HANDLERCREATOR" 
					&& strtoupper($dataTansact['MSGDATA']) == "UNREG GAME"){
					$premium = 0; $pull = 0;
				} */
				
				//$hit = http_request::get("http://gameasik.mobi/indosat/membership/passwordgenerator/".$drData->msisdn."/".$premium."/".$pull."/", "", 10);
				//$hit = trim(strtoupper($hit));
				//$log->write(array('level' => 'debug', 'message' => 'Response hit url ['.$url.']: ' . $hit));
			//}
			
			$dataTansact = $model_transact->getDataTransact($drData);
			
			if(strpos(strtoupper($dataTansact['SUBJECT']), "MT;PUSH;") !== false)
			{				
				$customModel = loader_model::getInstance()->load('custommodel', 'connDatabase1');
				$dataSubject = $customModel->getSubject(
					array
					(
						'msisdn' => $drData->msisdn,
						'operator' => $config_main->operator,
						'service' => $drData->service
					)
				);
				
				if(strpos(strtoupper($dataTansact['SUBJECT']), "MT;PUSH;SMS;TEXT") !== false){
					
					if(count($dataSubject) > 0)
					{
						$ds = $dataSubject[0];
						
						$trxid = $dataTansact['MSGINDEX'];
						$msgstatus = $dataTansact['MSGSTATUS'];
						$closereason = $dataTansact['CLOSEREASON'];
						
						$keyword = "reg+".$drData->service."+".((!empty($ds['subject'])) ? $ds['subject'] : '')."+".((!empty($ds['subject2'])) ? $ds['subject2'] : '');
						
						$url = sprintf("http://149.129.252.221:8028/app/api/waki_dr.php?trx_id=%s&status=%d&statusdesc=%s&operator=2&msisdn=%d&sdc=99879&service=%s&type=firstpush", $trxid, $closereason, $msgstatus, $drData->msisdn, $keyword);
						
						http_request::get($url, "", 10);
					}
					
				}
				
				if(strpos(strtoupper($dataTansact['SUBJECT']), "MT;PUSH;SMS;DAILYPUSH") !== false){
					
					if(count($dataSubject) > 0)
					{
						$ds = $dataSubject[0];
						
						$trxid = $dataTansact['MSGINDEX'];
						$msgstatus = $dataTansact['MSGSTATUS'];
						$closereason = $dataTansact['CLOSEREASON'];
						
						$keyword = "reg+".$drData->service."+".((!empty($ds['subject'])) ? $ds['subject'] : '')."+".((!empty($ds['subject2'])) ? $ds['subject2'] : '');
						
						$url = sprintf("http://149.129.252.221:8028/app/api/waki_dr.php?trx_id=%s&status=%d&statusdesc=%s&operator=2&msisdn=%d&sdc=99879&service=%s&type=dailypush", $trxid, $closereason, $msgstatus, $drData->msisdn, $keyword);
						
						http_request::get($url, "", 10);
					}
					
				}
			}
			
		}
		
		if(strtoupper($drData->service) == "GAME")
		{
			$read = fopen("/app/xmp2012/logs/indosat/game_setting/setting_eagame.txt", "r");
			$contents = fread($read, 4906);
			$contents = unserialize($contents);
			fclose($read);
			
			if($contents['REG'] == "ON")
			{
				$dataTansact = $model_transact->getREGTransact($drData);
				
				if((strtoupper($dataTansact['SUBJECT']) == "MT;PULL;SMS;TEXT" || $drData->subject == "MT;PULL;SMS;TEXT") 
					&& $drData->statusInternal == "DELIVERED"){
					$premium = 1; $pull = 0;
				}else if((strtoupper($dataTansact['SUBJECT']) == "MT;PUSH;SMS;TEXT" || $drData->subject == "MT;PUSH;SMS;TEXT") 
					&& $drData->statusInternal == "DELIVERED"){
					$premium = 1; $pull = 0;
				}else if((strtoupper($dataTansact['SUBJECT']) == "MT;PUSH;SMS;TEXT" || $drData->subject == "MT;PUSH;SMS;TEXT") 
					&& $drData->statusInternal == "FAILED"){
					$premium = 0; $pull = 0;
				}else if(strtoupper($dataTansact['SUBJECT']) == "MO;PULL;SMS;HANDLERCREATOR" || $drData->subject == "MO;PULL;SMS;HANDLERCREATOR" 
					&& strtoupper($dataTansact['MSGDATA']) == "UNREG GAME"){
					$premium = 0; $pull = 0;
				}
				
				$hit = http_request::get("http://m.eagame.mobi/eagame/membership/passwordgenerator/".$drData->msisdn."/".$premium."/".$pull."/", "", 10);
				$hit = trim(strtoupper($hit));
				$log->write(array('level' => 'debug', 'message' => 'Response hit url ['.$url.']: ' . $hit));
			}
		}
    }

    public function saveToBuffer($str) 
    {
        /**
         *  http://ip:port://?time=[]&serviceid=[]&tid=[]&dest=[]&status=[]
         **/

        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($str)));

        $config_main = loader_config::getInstance()->getConfig('main');
        
        $config_dr = loader_config::getInstance()->getConfig('dr');
        $dr_data = loader_data::get('dr');
        $buffer_file = buffer_file::getInstance();
        
        $dr_data->msisdn = (isset($_GET['dest'])) ? $_GET['dest'] : '';
        $dr_data->msgId = (isset($_GET['tid'])) ? $_GET['tid'] : '';
        $dr_data->adn = (isset($_GET['adn'])) ? $_GET['adn'] : $config_main->adn;
		//$dr_data->adn = substr($_GET['serviceid'],0,5);
        $dr_data->dateCreated = (isset($_GET['time'])) ? $_GET['time'] : date('Y-m-d H:i:s');
        $dr_data->statusCode = (isset($_GET['status'])) ? $_GET['status'] : '';
        $dr_data->serviceId = (isset($_GET['serviceid'])) ? $_GET['serviceid'] : '';
        $dr_data->statusText = $config_dr->responseText[(int)$_GET['status']];
        		
        if (isset($_GET['status'])) {
			$dr_data->msgStatus = $config_dr->responseMap['text'][(int)$_GET['status']];
			$dr_data->closeReason = $config_dr->responseText[(int)$_GET['status']];
			$dr_data->msgLastStatus = $config_dr->responseMap['text'][(int)$_GET['status']]; 
        } else {
			$dr_data->msgStatus = 'FAILED';
			$dr_data->closeReason = 'FAILED';
			$dr_data->msgLastStatus = 'FAILED';
        }
		
        $path = $buffer_file->generate_file_name($dr_data, 'dr', 'dr');

        //var_dump($path, $dr_data);
        
        if ($buffer_file->save($path, $dr_data)) {
            return $config_dr->returnCode ['OK'];
        } else {
            return $config_dr->returnCode ['NOK'];
        }
    }

    protected function getDRData($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start : ' . serialize($mt_data)));

        $model_transact = loader_model::getInstance()->load('tblmsgtransact', 'connDatabase1');
        $dataTansact = $model_transact->getDRTransact($mt_data);

        $chargingData = loader_data::get('charging');
        $chargingData->chargingId = $dataTansact[0]['chargingId'];
        $chargingData->senderType = $dataTansact[0]['sender_type'];

        $drData = loader_data::get('dr');
        $drData->msgId = $dataTansact[0]['MSGINDEX'];
        $drData->operatorId = $dataTansact[0]['OPERATORID'];
        $drData->subject = $dataTansact[0]['SUBJECT'];
        $drData->charging = $chargingData;
        $drData->adn = $dataTansact[0]['ADN'];
        $drData->msisdn = $dataTansact[0]['MSISDN'];        
        $drData->service = $dataTansact[0]['SERVICE'];        
		$this->addPoint($dataTansact,$mt_data);

		return $drData;
    }

    protected function addPoint($dataTansact,$mt_data) {
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => 'Start Add Point: ' . serialize($mt_data)));
		$log->write(array('level' => 'debug', 'message' => 'Service '.$dataTansact[0]['SERVICE']));
		if(strtolower($dataTansact[0]['SERVICE'])=='jojoku_9790_pull' && $dataTansact[0]['SUBJECT']=='MT;PULL;SMS;JOJOKUTIPS') {
			$configDr = loader_config::getInstance()->getConfig('dr');
			$status = $configDr->responseMap['text'][$mt_data->msgStatus];
			if($status=='DELIVERED') {
				$userModel = loader_model::getInstance()->load('user', 'connDatabase1');
				$user_data = loader_data::get('user');
				$user_data->active = 0;
				$user_data->msisdn = $mt_data->msisdn;
				$user_data->adn = $mt_data->adn;
				$user_data->service = str_replace('pull','push',$dataTansact[0]['SERVICE']);
				$user = $userModel->getException($user_data);
				$log->write(array('level' => 'debug', 'message' => 'User Point'.serialize($user)));
				if($user) {
					$pointModel = loader_model::getInstance()->load('point', 'connDatabase1');
					if($pointDt = $pointModel->getAllPoint($user['id'])) {
						$point= $pointModel->updatePoint($pointDt['id']);
						if($point) {
							$log->write(array('level' => 'debug', 'message' => 'Point succesfully updated'));
						}
					} else {
						$point= $pointModel->insertPoint($user['id']);
						if($point) {
							$log->write(array('level' => 'debug', 'message' => 'Point succesfully added'));
						}
					}
				}
			}
		}
	}
}
