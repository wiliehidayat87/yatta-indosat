<?php 
/*
 * http://localhost:20002/mo/?msisdn=628558047226&sms=kt+test2&trx_time=20140317180545&transid=12345678&substype=30
 *
 */ 
require_once '/app/xmp2012/interface/indosat/xmp.php'; 
//var_dump($_REQUEST); 
//if ($_GET) {
if(isset($_GET['transid'])){
    $_GET['adn'] = $_GET['sc'];
    $_GET['sms'] = str_replace('++','',$_GET['sms']);
    $_GET['sms'] = str_replace('  ','',$_GET['sms']);
    $arrmbid = explode(' ',$_GET['sms']);
    if(strtolower($arrmbid[2]) == 'umb'){
	//$_GET['channel']='umb';	
	//setTrack($operator, $msisdn, $service, $trackcode, $reg)
	$track = loader_model::getInstance()->load('track', 'cmp');
 	$track->setTrack('indosat',$_GET['msisdn'],$arrmbid[1],$arrmbid[2],'1');	
    }
    	$pixel = loader_model::getInstance()->load('pixel', 'cmp');
	$isvalid = $pixel->checkMO($_GET['msisdn'],$_GET['sms']);
	
	$whitelists = array("6285811647215","6285811647217");
	
	if($isvalid || in_array($_GET['msisdn'], $whitelists)){
		$moProcessor = new manager_mo_processor ( );
		$response = $moProcessor->saveToFile($_GET);
		printResponse($_GET['transid'],0);
		userCredential($_GET);	
		sendToCmp($_GET);
	}else{
		printResponse($_GET['transid'],4);
	}
} else {
    printResponse($_GET['transid'],3);
}

function printResponse($trx_id, $status=0) {
	
	$response_str = array(
	    0 => 'Message processed successfully',
	   -1 => 'Parameter incomplete',
	    3 => 'System error',	
	    4 => 'Duplicate Value'	
	);
	
	
	$response = '<?xml version="1.0" ?><MO><STATUS>'.$status.'</STATUS><TRANSID>'.$trx_id.'</TRANSID><MSG>'.$response_str[$status].'</MSG></MO>';
	error_log($response.PHP_EOL, 3, "/tmp/tesrespon.txt");	
	header('Content-type: text/xml');
	echo $response;
}

function sendToCmp($GET) {
	$log = manager_logging::getInstance();
	$pixel = loader_model::getInstance()->load('pixel', 'cmp');
	$operator = "INDOSAT";
	$arrmbid = explode(' ',$GET['sms']);
	$sms = $GET['sms']; 
	$notallow = array("menu","vs","mx","sv","xm");
	if((count($arrmbid)==3)&&(!in_array(strtolower($arrmbid[2]),$notallow))){
	//if((count($arrmbid)==3)&&(strtolower($arrmbid[2])!=='menu')){
	if(substr(strtolower($sms), 0, 3) == "reg" && (strpos(strtolower($GET['sms']),'reg dangdut')!==FALSE ||
		strpos(strtolower($GET['sms']),'reg dg')!==FALSE ||strpos(strtolower($GET['sms']),'reg marijoged')!==FALSE || strpos(strtolower($GET['sms']),'reg asik')!==FALSE ||
		strpos(strtolower($GET['sms']),'reg puasa')!==FALSE ||
		strpos(strtolower($GET['sms']),'reg musik')!==FALSE ||
		strpos(strtolower($GET['sms']),'reg game')!==FALSE ||
		strpos(strtolower($GET['sms']),'reg gosik')!==FALSE)){
		/*
		$publisher = array(1=>'mobusi',2=>'kimia',3=>'kissads',4=>'adsterdam',5=>'cd',6=>'mobipium');
		$partner = isset($arrmbid[2])?$publisher[$arrmbid[2]]:'none';
		
		$val_pixel = $pixel->getPixel($operator,$partner);
		*/ 

		$partid = array("M", "K", "C", "P", "S", "L", "B","E","I","T","A");
		if(in_array(substr($arrmbid[2], 0, 1),$partid)){
			$val_pixel = $pixel->showPixel(substr($arrmbid[2],1));
		}else{
			$publisher = array(1=>'mobusi',2=>'kimia',3=>'kissads',4=>'adsterdam',5=>'cd',6=>'mobipium',7=>'bmb',8=>'elymob',9=>'isj');
	                $partner = isset($arrmbid[2])?$publisher[$arrmbid[2]]:'none';
			$val_pixel = $pixel->getPixel($operator,$partner);
		}

		
		if(substr($val_pixel, 0, 3) == "ADT") {
			$data['partner']='adsterdam';
			$val_pixel = str_replace('ADT','',$val_pixel);
		}else if(substr($val_pixel, 0, 11) == "TEST_OFFER_") {
			$data['partner']='bmb';
		}else if(substr($val_pixel, 0, 7) == "bmconv_") {
			$data['partner']='bmb';
		}else{
			if(strlen($val_pixel) == 30) {
				//$data['partner']='kissads';
				$data['partner']='isj';
			}else if(strlen($val_pixel) == 31) {
                                $data['partner']='isj';
			}else if(strlen($val_pixel) == 55) {
				$data['partner']='kimia';
            //}else if(strlen($val_pixel) == 41){
				//$data['partner']='mobipium';
			}else if(strlen($val_pixel) == 40 || strlen($val_pixel) == 41){
				$data['partner']='tfc';
			}else if(strlen($val_pixel) == 24){
				$data['partner']='adm';
			//}else if(strlen($val_pixel) == 24){
				//$data['partner']='okinet';
			}else if(strlen($val_pixel) == 23){
				$data['partner']='mobusi';
			}else if(strlen($val_pixel) == 32){
                $data['partner']='pin';
			//}else if(strlen($val_pixel) == 32){
                //$data['partner']='adacts';
			}else if(strlen($val_pixel) == 45){
                $data['partner']='lev';
			//}else if(strlen($val_pixel) == 32){
                //$data['partner']='adacts';
			}else if(strlen($val_pixel) == 12){
                $data['partner']='elymob';
			}else {
				if(preg_match('/^cd/',strtolower($val_pixel))) {
					$data['partner']='cd';
					$val_pixel = str_replace('cd','',$val_pixel);
				} else {
					$data['partner']='none';
				}
			}
		}
		
		$subkeyword = strtolower($arrmbid[2]);
		
		if($subkeyword == "umb")
		{
			$trxid = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
			$url = "http://kbtools.net/yatpost.php";
			$prm = "trxid={$trxid}&serv_id=game&partner=yattaisat&msisdn=".$GET['msisdn']."&px=bmb";
			
			$hit = http_request::get($url, $prm, 30);
			$hit = trim(strtoupper($hit));
		}
		else
		{
			$data['id']=$val_pixel;
			$data['msisdn'] = $GET['msisdn'];
			$data['instid']=$GET['msisdn'];
			$config_cmp = loader_config::getInstance()->getConfig('cmp');
			if(isset($config_cmp->partner[$data['partner']]) && $config_cmp->partner[$data['partner']]==1 && strlen($val_pixel) >= 10) {
					$cmp_manager = new manager_cmp_processor();
					$data['service']=$arrmbid[1];
					$data['adn']=$GET['to'];
					$cmp_manager->saveToBuffer($data);
			}
		}
	}}
}

function userCredential($GET){
	$log = manager_logging::getInstance();
	$operator = "INDOSAT";
	$msisdn = $GET['msisdn'];
	
	$arrmbid = explode(' ',$GET['sms']);
	$sms = $GET['sms'];
	if((count($arrmbid)==3)&&(strtolower($arrmbid[2])!=='menu'))
	{
		if(substr(strtolower($sms), 0, 3) == "reg" && strpos(strtolower($GET['sms']),'reg game')!==FALSE)
		{
			if($msisdn == "6285710291096" || $msisdn == "6285798614406" || $msisdn == "6285813812423")
			{
				$urleagame = "http://m.eagame.mobi/eagame-test/user/passwordgenerator/store_credential/" . $msisdn . "/";
				//$password = http_request::get($urleagame, "", 10);
				// $sms = "CS:021-52964211, Untuk dapat game keren Klik http://m.eagame.mobi/eagame/user/login/ dan Sign In, utk stop *123*44# username:".$mt->msisdn.", and password:".$password;
				// $sms = urlencode($sms);
			}
		}
	}
}
