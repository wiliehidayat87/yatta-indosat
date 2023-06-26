<?php

class indosat_mt_processor_textdelay extends indosat_mt_processor_text {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function saveToQueue($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Save MT to Buffer Delay Indosat: " . serialize($mt_data)));

        // If it's a Push Message or no TrxID found in MO , then the TRXID must be generated
        if ($mt_data->charging->messageType == 'mtpush' || (!isset($mt_data->mo->msgId) && empty($mt_data->mo->msgId)) )
            $mt_data->msgId =  date("YmdHis") . str_replace('.', '', microtime(true));

        // Otherwise, take from MO          
        else
            $mt_data->msgId = $mt_data->mo->msgId;

        
        $mt_delay = new mt_delay_data ();
	$mt_delay->operator = $mt_data->operatorName;
        $mt_delay->service = $mt_data->service;
        $mt_delay->adn = $mt_data->adn;
        $mt_delay->msisdn = $mt_data->msisdn;
        $mt_delay->obj = serialize($mt_data);

        $model_mtdelay = loader_model::getInstance()->load('mtdelay', 'connDatabase1');
        $model_mtdelay->add($mt_delay);
		
		//----CPA Start----
		/*
		$log->write(array('level' => 'debug', 'message' => "Start CPA Hit"));
		$scrcomand = 'cat /app/xmp2012/logs/indosat/cpa/CPAhist'.@date('Ymd').' | grep "'.$mt_data->msisdn.'" | grep -i "reg DG" | grep -v "blank" | tail -1';
		$command=shell_exec('cat /app/xmp2012/logs/indosat/cpa/CPAhist'.@date('Ymd').' | grep "'.$mt_data->msisdn.'" | grep -i "reg DG" | grep -v "blank" | tail -1');
		$log->write(array('level' => 'debug', 'message' => "CPA History : ".$scrcomand));
		$grepvar = explode(";",$command);
		$log->write(array('level' => 'debug', 'message' => "Send to CMP : ".count($grepvar).";reg DG;".$grepvar[2].";".$mt_data->msisdn.";".$mt_data->adn));
		
		if(count($grepvar)>1){
			$this->sendToCmp('reg DG',$grepvar[2],$mt_data->msisdn,$mt_data->adn);
		}
		$log->write(array('level' => 'debug', 'message' => "End CPA Hit"));
		*/
		//----CPA End----	
		
        return true;
    }
	
	public function sendToCmp($keyword,$pixel,$msisdn,$sdc) {	
		$log = manager_logging::getInstance();
		$log->write(array('level' => 'debug', 'message' => "Start CPA HMO Hit(".$keyword.";".$pixel.";".$msisdn.";".$sdc.";)"));
		if(strpos(strtolower($keyword),'reg dg')!==FALSE) {
			if(strlen($pixel) == 30){
                                $data['partner']='kissads';
                        }else if(strlen($pixel) == 55) {
				$data['partner']='kimia';
			}else {
				if(preg_match('/^cd/',strtolower($pixel))) {
					$data['partner']='cd';
					$pixel = str_replace('cd','',$pixel);
				} else {
					$data['partner']='mobusi';
				}
			}
			
			$data['id']=$pixel;
			$data['msisdn'] = $msisdn;
			$data['instid']=$msisdn;
			$config_cmp = loader_config::getInstance()->getConfig('cmp');
			$log->write(array('level' => 'debug', 'message' => "Create Buffer CPA"));
			if(isset($config_cmp->partner[$data['partner']]) && $config_cmp->partner[$data['partner']]==1) {
					$cmp_manager = new manager_cmp_processor();
					$data['service']='dg';
					$data['adn']=$sdc;
					$log->write(array('level' => 'debug', 'message' => "Save to CPA buffer"));
					$cmp_manager->saveToBuffer($data);
			}
		}
		return true;
	}
}
