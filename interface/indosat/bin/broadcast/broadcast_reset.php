#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$lockPath = '/tmp/lock_indosat_broadcast_reset';

if (file_exists($lockPath)) {
    echo "NOK - Lock File Exist on $lockPath \n";
    exit;
} else {
    touch($lockPath);
}

$broadcast = new manager_broadcast ();
$result = $broadcast->resetSchedule();
if ($result) {
    echo "OK \n";
} else {
    echo "NOK \n";
}

unlink($lockPath);

