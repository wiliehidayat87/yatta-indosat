#!/usr/bin/php
<?php

/**
 * 
 * use getopt function to get the parameter. Ex:
 *
 * summarizerProcess.php -p default -d 2011-06-08 -f connDatabase1 -t reports -ft tbl_msgtransact -tt report
 * 
 * -p : controller used to processing summarized transaction
 * -d : date which data is summarized
 * -f : conn DB used to collect data
 * -t : conn DB destination
 * -x : from table
 * -r : to table
 * 
 */

require_once '/app/xmp2012/interface/indosat/xmp.php';

$params = getopt('p:d:f:t:x:r:');

if(!isset($params['f']) && !isset($params['t'])) {
	echo 'Incomplete parameter. Usage' . "\n";
	echo 'summarizerProcess.php -p default -d 2011-06-08 -f connDatabase1 -t reports -x tbl_msgtransact -r rpt_service2' . "\n";
	exit;
}

$lockFile = '/tmp/lock_indosat_summarizer_process';

if(file_exists($lockFile)) {
	echo "NOK - Lock File Exist on $lockFile \n";
	exit;
} else {
	touch($lockFile);
}

$mtManager	= new manager_summarizer();
$result		= $mtManager->process($params);

if($result){
	echo "OK \n";
}else{
	echo "NOK \n";
}

unlink($lockFile);

