<?php

class indosat_mo_processor extends default_mo_processor {

    /**
     * @param $arrData
     */
    public function saveToFile($arrData) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $log->write(array('level' => 'info', 'message' => "Info : " . print_r($arrData, TRUE)));

        $load_config = loader_config::getInstance();
        $config_main = $load_config->getConfig('main');
        $ip = $_SERVER ['REMOTE_ADDR'];

        $config_mo = $load_config->getConfig('mo');
        $mo_data = loader_data::get('mo');
		$mo_data->msisdn = $arrData ['msisdn'];
        $mo_data->msgId = $arrData ['transid'];
        $mo_data->rawSMS = $arrData ['sms'];
        $mo_data->adn = $arrData['sc'];
        $mo_data->operatorName = $config_main->operator;
		
		// ADD postback for campaign
		
		list($trigger,$servicename,$px) = explode(" ", $mo_data->rawSMS);
		
		if(!empty($px) && in_array(strtoupper($servicename), array('DANGDUT','GAME','MUSIK','ASIK','GOSIK')))
		{	
			if(strpos($px,"BB") !== FALSE) // NEW ADNET
			{
				$px = str_replace("BB","-",$px);
			}
			
			if(strpos($px,"A") !== FALSE) // NEW MBV
			{
				$px = str_replace("A","_",$px);
				
				if(strpos($px,"BB") !== FALSE)
				{
					$px = str_replace("BB","-",$px);
				}
			}
			
			$params = array(
				 "trxid=".$mo_data->msgId
				,"serv_id=".strtolower($servicename)
				,"partner=yattaisat"
				,"msisdn=".$mo_data->msisdn
				,"px=" . $px
			);
			
			$hit = http_request::get ( "http://kbtools.net/yatpost.php", implode("&", $params), 10 );
		}
		
        if (!empty($arrData ['trx_time']))
            $trx_date = $arrData ['trx_time'];
        else
            $trx_date = '';
		
		if(strpos($mo_data->rawSMS, "+") !== FALSE)
			$split_raw_sms = explode("+", $mo_data->rawSMS);
		else
			$split_raw_sms = explode(" ", $mo_data->rawSMS);
		
		if(!empty($split_raw_sms[0]))
		{
			if(strtoupper($split_raw_sms[0]) == 'REG')
				$mo_data->flagKeyword = "REG";
			else if(strtoupper($split_raw_sms[0]) == 'UNREG')
				$mo_data->flagKeyword = "UNREG";
			else
				unset($mo_data->flagKeyword);
		}
		
		if(count($split_raw_sms) > 2){
			$trigger = $split_raw_sms[0]; // REG
			$service = $split_raw_sms[1]; // SERVICENAME
			
			$mo_data->rawSMS = $trigger." ".$service;
			
			if(!empty($split_raw_sms[2]))
			{
				$cs = $split_raw_sms[2]; // LAST KEYWORD / SUBKEYWORD INIT
				$mo_data->customService = $cs;
				
				$mo_data->rawSMS = $trigger." ".$service." ".$mo_data->customService;
				
				if(!empty($split_raw_sms[3]))
				{
					$ck = $split_raw_sms[3]; // CAMPAIGN KEYWORD
					$mo_data->campaignKeyword = $ck;
					
					$mo_data->rawSMS = $trigger." ".$service." ".$mo_data->customService." ".$mo_data->campaignKeyword;
				}
			}
		}
		
        $mo_data->incomingDate = $this->setDate($trx_time);
        $mo_data->incomingIP = http_request::getRealIpAddr();
        $mo_data->substype = $arrData['substype'];
        $mo_data->type = 'mtpull';

        //var_dump($mo_data);
		
        $buffer_file = buffer_file::getInstance();

        $path = $buffer_file->generate_file_name($mo_data);
        
        $save_file = $buffer_file->save($path, $mo_data);

		if(!empty($split_raw_sms[0])){

			if($mo_data->flagKeyword = "UNREG" && strtoupper($servicename) == 'ASIK'){

            http_request::get("https://asikgame.club/notify_unsubscribe?msisdn=".$mo_data->msisdn, "", 5);
            
        	//http_request::get("https://yt.gamren.mobi/subscription/unsubscribe/?msisdn=".$mo_data->msisdn."&event=unreg", "", 10);
        }
		}

        if ($save_file) {
            $log->write(array('level' => 'info', 'message' => 'Object MO write at: ' . $path . ' response : ' . $config_mo->returnCode['OK']));
            return $config_mo->returnCode ['OK'];
        } else {
            $log->write(array('level' => 'error', 'message' => 'Write Object MO failed at : ' . $path . ' response : ' . $config_mo->returnCode['OK']));
            return $config_mo->returnCode ['NOK'];
        }
    }

    private function setDate($char) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $log->write(array('level' => 'info', 'message' => "Info : " . $char));

        if (empty($char)) {
            return date("Y-m-d H:i:s");
        } else {
            $y = substr($char, 0, 4);
            $m = substr($char, 4, 2);
            $d = substr($char, 6, 2);
            $h = substr($char, 8, 2);
            $i = substr($char, 10, 2);
            $s = substr($char, 12, 2);
            $datetime = $y . '-' . $m . '-' . $d . ' ' . $h . ':' . $i . ':' . $s;
            return $datetime;
        }
    }
}
