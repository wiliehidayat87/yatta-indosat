<?php 
/*
 * http://localhost:20002/mo/?msisdn=628558047226&sms=kt+test2&trx_time=20140317180545&transid=12345678&substype=30
 *
 */ 

$POST = file_get_contents('php://input');

if(isset($_GET)){
    error_log(date('Y-m-d H:i:s').' '.print_r($_GET,1).PHP_EOL,3,"/tmp/momoco_".date('Ymd').".log");
}


require_once '/app/xmp2012/interface/indosat/xmp.php'; 

if ($_GET) {
    printResponse($_GET['TRX_ID']);
    sendToCmp($_GET);
} else {
    printResponse(3, $_GET['TRX_ID']);
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

function sendToCmp($GET) {
	$log = manager_logging::getInstance();
	$pixel = loader_model::getInstance()->load('pixel', 'cmp');	
	
	$val_pixel = $GET['PIXEL'];
	
	if(substr($val_pixel, 0, 3) == "ADT") {
		$data['partner']='adsterdam';
		$val_pixel = str_replace('ADT','',$val_pixel);
	}else{
		if(strlen($val_pixel) == 30) {
			$data['partner']='kissads';
		}else if(strlen($val_pixel) == 55) {
			$data['partner']='kimia';
		}else if(strlen($val_pixel) == 41){
			$data['partner']='mobipium';
		}else {
			if(preg_match('/^cd/',strtolower($val_pixel))) {
				$data['partner']='cd';
				$val_pixel = str_replace('cd','',$val_pixel);
			} else {
				$data['partner']='mobusi';
			}
		}
	}
	$data['id']=$val_pixel;
	$data['msisdn'] = $GET['MSISDN'];
	$data['instid']=$GET['MSISDN'];
	$config_cmp = loader_config::getInstance()->getConfig('cmp');
	if(isset($config_cmp->partner[$data['partner']]) && $config_cmp->partner[$data['partner']]==1) {
			$cmp_manager = new manager_cmp_processor();
			$data['service']=$GET['SERVICE'];
			$data['adn']=$GET['99095'];
			$cmp_manager->saveToBuffer($data);
	}
}
