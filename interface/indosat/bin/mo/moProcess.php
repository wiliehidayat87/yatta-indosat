#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';


$lockPath = '/tmp/lock_indosat_mo_process';

if(file_exists($lockPath)) {
	echo "NOK - Lock File Exist on $lockPath \n";
	exit;
} else {
	touch($lockPath);
}

$mo = new manager_mo_processor ();
//$slot = loader_config::getInstance ()->getConfig ( 'mo' )->bufferSlot;
$result = $mo->process ();
if ($result) {
	echo "OK \n";
} else {
	echo "NOK \n";
}

unlink($lockPath);
