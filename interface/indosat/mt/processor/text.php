<?php

class indosat_mt_processor_text extends default_mt_processor_text {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function saveToQueue($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

       if ($mt_data->charging->messageType == 'mtpush' || (!isset($mt_data->mo->msgId) && empty($mt_data->mo->msgId)) )
        {
            if (empty($mt_data->msgId)) $mt_data->msgId =  date("YmdHis") . str_replace('.', '', microtime(true));
        }
        // Otherwise, take from MO
        else
            $mt_data->msgId = $mt_data->mo->msgId;
return $this->process($mt_data);
}

       public function process($mt) {

	$this->send_mt($mt);
}

    public function send_mt($mt) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start" . $slot));

        $loader_config = loader_config::getInstance();
        $configMT = $loader_config->getConfig('mt');
	$configSDM = $loader_config->getConfig('sdmcode');
        $profile = 'text';
        
        //print_r($queue);
        
            
            //echo('start-----');
            //print_r($mt);
            //http://202.152.162.221:55000/?uid=%UID%&pwd=%PWD%&serviceid=%SID%&msisdn=%MSISDN%&sms=%SMS%&transid=%TRXID%&smstype=0
            //?uid={UID}&pwd={PWD}&serviceid=%s&msisdn=%s&sms=%s&transid=%s&smstype=0
            
            //var_dump($queuePop, $mt);
            
            $param = 'uid=' . $mt->charging->username;
            $param .= '&pwd=' . $mt->charging->password;
            $param .= '&serviceid=' . $mt->charging->chargingId;
            $param .= '&msisdn=' . $mt->msisdn;
			
			$sms = $mt->msgData;
			
			if($mt->subject == "MT;PUSH;SMS;TEXT" || $mt->subject == "MT;PUSH;SMS;DAILYPUSH")
			{
				if($mt->service == "asik")
				{
					if($mt->subject == "MT;PUSH;SMS;TEXT"){

						http_request::get("https://yt.gamren.mobi/subscription/subscribe/?msisdn=".$mt->msisdn."&package=daily&event=reg", "", 10);

					} else if($mt->subject == "MT;PUSH;SMS;TEXT"){

						http_request::get("https://yt.gamren.mobi/subscription/renewal/?msisdn=".$mt->msisdn."&package=daily&event=renewal", "", 10);
					}

					
					$read = fopen("/app/xmp2012/logs/indosat/game_setting/setting_gameasik.txt", "r");
					$contents = fread($read, 4906);
					$contents = unserialize($contents);
					fclose($read);
					
					if($contents['REG'] == "ON")
					{
						//$hit = http_request::get("http://gameasik.mobi/indosat/membership/passwordgenerator/".$mt->msisdn."/1/0/", "", 10);
						//$hit = trim(strtoupper($hit));
						$hit = "0001";
						
						if($mt->subject == "MT;PUSH;SMS;TEXT")
						{
							//$sms = str_replace("#userpass#", "user:".$mt->msisdn.",pwd:".$hit, $mt->msgData);
							$sms = str_replace("#userpass#", "user:GUEST,pwd:".$hit, $mt->msgData);
						}
						else if($mt->subject == "MT;PUSH;SMS;DAILYPUSH")
						{
							$read = fopen("/app/xmp2012/logs/indosat/game_setting/setting_content_gameasik.txt", "r");
							$content_push = fread($read, 4906);
							$content_push = unserialize($content_push);
							fclose($read);
							
							//$sms = str_replace("#userpass#", " user:".$mt->msisdn.",pwd:".$hit, trim($content_push));
							$sms = str_replace("#userpass#", " user:GUEST,pwd:".$hit, trim($content_push));
						}
						
						$log->write(array('level' => 'debug', 'message' => 'Response hit password generator ['.$mt->msisdn.']: ' . $hit));
					}
				}
				
				if($mt->service == "game")
				{
					$read = fopen("/app/xmp2012/logs/indosat/game_setting/setting_eagame.txt", "r");
					$contents = fread($read, 4906);
					$contents = unserialize($contents);
					fclose($read);
					
					if($contents['REG'] == "ON")
					{
						$hit = http_request::get("http://m.eagame.mobi/eagame/membership/passwordgenerator/".$mt->msisdn."/1/0/", "", 10);
						$hit = trim(strtoupper($hit));
						$sms = "CS:021-52964211,Untuk dpt game keren Klik goo.gl/7QBGxh dan SignIn,utk stop *123*44# user:".$mt->msisdn.",pwd:".$hit;
					}
				}
				
				if($mt->msisdn == "6285775449978"  
					|| $mt->msisdn == "6285798614406" 
				)
				{
					if(strtolower($mt->service) == "asik" 
					|| strtolower($mt->service) == "game")
					{
						$conn = mysql_connect('172.16.0.62', 'xmp', '123456');
						mysql_select_db("xmp", $conn);

						if(strtolower($mt->service) == "asik") $service = 'Asik';
						else if(strtolower($mt->service) == "game") $service = 'Game';
							
						$SQL = "SELECT * FROM gamestored WHERE service='".$service."' AND operator = 'indosat' AND date(created_date) = CURRENT_DATE() LIMIT 1";
						$q = mysql_query($SQL);
						$r = mysql_fetch_array($q);
						
						$downloadlink = $r['downloadlink'];
						
						$sms = "CS:021-52964211,Untuk dpt game keren Klik ".$downloadlink.". utk stop *123*44#";
					}
				}
			}
			
			$sms = urlencode($sms);
			
			$param .= '&sms=' . $sms;
			
            $param .= '&transid=' . $mt->msgId;
            $param .= '&smstype=0';

	   
		/* if(isset($configSDM->sdm['SPMSERVICE'][$mt->service]))
	    {
			if($mt->charging->chargingId === '98790120001024' || $mt->charging->chargingId === '98790120001026'){
				$param .= '';
//				$param .= '&sdmcode='. $sdmcode;
			}else {
				$sdmcode=$configSDM->sdm['SPMSERVICE'][$mt->service];
				$param .= '&sdmcode='. $sdmcode;
			}
		} */
		
		$sdmcode=$configSDM->sdm['SPMSERVICE'][strtolower($mt->service)];
		$param .= '&sdmcode='. $sdmcode;

	    $url = $configMT->profile [$profile] ['sendUrl'] [0];
          
            $hit = http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']);
			$log->write ( array ('level' => 'debug', 'message' => "MT Url:" . $url . '?' . $param . ', Result:' . $hit ) );

 	    $log_hit_baru = "MT Url: " . $url . '?' . $param . ' Result:' . $hit;
 	    //error_log(date('Y-m-d H:i:s').' '.$log_hit_baru.PHP_EOL,3,"/data/log/indosat/broadcast/broadcast_hit_".date('Ymd'));		    


            $_hit = trim($hit);
            $smsc_response = simplexml_load_string($_hit);
			
			/*
			$retry = 1;
			while($retry<=3){
				if (($smsc_response->STATUS == 3)&&($smsc_response->MSG == "System Error")) {
					$hit_retry = http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']);
					$log->write ( array ('level' => 'debug', 'message' => "MT Url Retry".$retry.":" . $url . '?' . $param . ', Result Retry'.$retry.':' . $hit_retry ) );

					$_hit = trim($hit_retry);
					$smsc_response = simplexml_load_string($_hit);
					$retry++;
				}else{
					$retry = 4;
				}
			}
			*/
			
			/*
			if (($smsc_response->STATUS == 3)&&($smsc_response->MSG == "System Error")) {
				$hit_retry = http_request::get($url, $param, $configMT->profile [$profile] ['SendTimeOut']);
				$log->write ( array ('level' => 'debug', 'message' => "MT Url Retry:" . $url . '?' . $param . ', Result Retry:' . $hit_retry ) );

				$_hit = trim($hit_retry);
				$smsc_response = simplexml_load_string($_hit);
			}
			*/
            
            $configDr = loader_config::getInstance()->getConfig('dr');
            if ($configDr->synchrounous === TRUE) {
                if ($_hit == 1) {
                    $mt->msgStatus = 'DELIVERED';
                } else {
                    $mt->msgStatus = 'FAILED';
                }
                $mt->closeReason = $_hit;
            } else {
            	if ($smsc_response->STATUS == 0) {
			//change from $mt->msgStatus = 'DELIVERED'; because when we don't get DR for spesific message, message will be count as revenue, make like PROXL behaviour 
            		$mt->msgStatus = '';
            	} else {
            		$mt->msgStatus = 'FAILED';
            	}
            	$status = $smsc_response->STATUS;
            	$mt->closeReason = $status."|".$configDr->responseACK[(int)$status];
            	//var_dump($configDr->responseACK[(int)$status], $smsc_response->STATUS, $mt->closeReason);
            }
            
            //var_dump($url, $param, $_hit);		
			
            $mt->msgLastStatus = 'DELIVERED';
            $this->saveMTToTransact($mt);
        return true;
    }
}
