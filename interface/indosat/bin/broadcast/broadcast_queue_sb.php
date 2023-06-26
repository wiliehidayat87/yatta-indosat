#!/usr/bin/php
<?php

$params = getopt('s:t:n:');

if(!isset($params['s']) && !isset($params['t'])) {
	echo 'Incomplete parameter. Usage' . "\n";
	echo 'broadcast_queue.php -s dg -t 0' . "\n";
	exit(0);
}

require_once '/app/xmp2012/interface/indosat/xmp.php';
//$project = loader_model::getInstance()->load('pushproject', 'xmp');
//if($project->isactivepush($params['s'])){
$broadcast = new manager_broadcast ();
$result = $broadcast->process ();
if ($result) {
	echo "OK \n";
} else {
	echo "NOK \n";
}
//}else{
//echo "NOK \n";
//}
exit(0);
