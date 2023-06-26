#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$lockPath = '/tmp/lock_indosat_mt_delay_process';

if(file_exists($lockPath)) {
	echo "NOK - Lock File Exist on $lockPath \n";
	exit;
} else {
	touch($lockPath);
}

$mt = new indosat_mt_forwarder ();
$result = $mt->process ();
if ($result) {
	echo "OK \n";
} else {
	echo "NOK \n";
}

unlink($lockPath);
