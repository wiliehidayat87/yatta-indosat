#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$lockPath = '/tmp/lock_indosat_cmp_process';

if (file_exists($lockPath)) {
    echo "NOK - Lock File Exist on $lockPath \n";
    exit;
} else {
    touch($lockPath);
}

$cmp = new manager_cmp_processor ();

$result = $cmp->run();

if ($result) {
    echo "OK \n";
} else {
    echo "NOK \n";
}

unlink($lockPath);


