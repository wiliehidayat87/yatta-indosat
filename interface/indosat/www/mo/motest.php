<?php

require_once('/app/xmp2012/interface/indosat/xmp.php');
if(count($arrmbid = explode(' ',$GET['sms']))==3) {
	if(strpos(strtolower($GET['sms']),'reg dg')!==FALSE) {
		$data['partner']='mobusi';
        	$data['id']=$arrmbid[2];
	        $data['instid']=$GET['msisdn'];
        	$config_cmp = loader_config::getInstance()->getConfig('cmp');
	        if(isset($config_cmp->partner[$data['partner']]) && $config_cmp->partner[$data['partner']]==1) {
        		$cmp_manager = new manager_cmp_processor();
	                $data['service']='dg';
        	        $data['adn']=$GET['sc'];
                	$cmp_manager->saveToBuffer($data);
	        }
	}
}
?>
