<?php
/*
 * http://localhost:20002/mo/?msisdn=628558047226&sms=kt+test2&trx_time=20140317180545&transid=12345678&substype=30
 * 
 */

require_once '/app/xmp2012/interface/indosat/xmp.php';

//var_dump($_REQUEST);

if ($_REQUEST) {
    $moProcessor = new manager_mo_processor ( );
    $response = $moProcessor->saveToFile($_REQUEST);        
    printResponse($_GET['transid']);
    sendToCmp($_GET);
        
} else {
    printResponse(3, $_GET['transid']);
}

function printResponse($trx_id, $status=0) {
	
	$response_str = array(
		0 => 'Message processed successfully',
	   -1 => 'Parameter incomplete',
		3 => 'System error', 
		
	);
	
	
	$response = '<?xml version="1.0" ?><MO><STATUS>'.$status.'</STATUS><TRANSID>'.$trx_id.'</TRANSID><MSG>'.$response_str[$status].'</MSG></MO>';
	
	header('Content-type: text/xml');
	echo $response;
}


// trx_time=20150519144014&
// msisdn=6285711683714&
// sc=99879&
// transid=25388599515&
// sms=reg+DG+hIDAD150519000150000102828003467b030MAN2e3000939PZ05163&
// substype=20&
// sdmsubsid=178135734

// trx_time=20150519000441&
// msisdn=6285624329637&
// sc=99879&
// transid=14687093927&
// sms=reg+DG+hIDAD150518000150000103234003467b030TPCf730019040802032&
// substype=20&
// sdmsubsid=178053467

function sendToCmp($GET) {
	if(count($arrmbid = explode(' ',$GET['sms']))==3) {
		if(strpos(strtolower($GET['sms']),'reg dg')!==FALSE) {
			/* script ini untuk validasi kimia */
			// if(preg_match('/^g/',strtolower($arrmbid[2]))){
			if(strlen($arrmbid[2]) == 55) {
				$data['partner']='kimia';
			}else {
				if(preg_match('/^cd/',strtolower($arrmbid[2]))) {
					$data['partner']='cd';
					$arrmbid[2] = str_replace('cd','',$arrmbid[2]);
				} else {
					$data['partner']='mobusi';
				}
			}
				$data['id']=$arrmbid[2];
				$data['msisdn'] = $GET['msisdn'];
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

}
