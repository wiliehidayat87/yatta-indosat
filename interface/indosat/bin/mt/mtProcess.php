#!/usr/bin/php
<?php

/**
 * 
 * aka. MT Flusher, CLI mode only
 * 
 * usage	: mtProcess.php -t <message_type>
 * example	: mtProcess.php -t text
 * 
 */


require_once '/app/xmp2012/interface/indosat/xmp.php'; 


$params = getopt('t:');

if(!isset($params['t'])) {
	echo 'Incomplete parameter. Usage' . "\n";
	Echo 'mtProcess.php -t text' . "\n";
	exit;
}
/*
 * 'q' => date('H'),
            'w' => date('H', strtotime('-' . $configDr->defaultHour . ' minute')),
            'f' => 'cdr_' . date('Ymd'),
            't' => 'tbl_msgtransact'
 */

$lockFile = '/tmp/lock_indosat_mt_process' . $params['t'];

if(file_exists($lockFile)) {
	echo "NOK - Lock File Exist on $lockFile \n";
	exit;
} else {
	touch($lockFile);
}

$mtManager	= new manager_mt_processor();
$result		= $mtManager->process($params['t']);

if($result){
	echo "OK \n";
}else{
	echo "NOK \n";
}

unlink($lockFile);
