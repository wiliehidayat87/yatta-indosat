#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$lockPath = '/tmp/lock_indosat_dr_process';

if (file_exists($lockPath)) {
    echo "NOK - Lock File Exist on $lockPath \n";
    exit;
} else {
    touch($lockPath);
}

$dr = new manager_dr_processor ();

$result = $dr->process();

if ($result) {
    echo "OK \n";
} else {
    echo "NOK \n";
}

unlink($lockPath);




