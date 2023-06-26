#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$arr = getopt('c:q:w:f:t:');

if (!isset($arr['c'])) {
    echo 'Incomplete parameter. Usage' . "\n";
    Echo 'drUpdater.php -c connDBProfile' . "\n";
    exit;
}

$lockFile = '/tmp/lock_indosat_dr_updater_' . $arr['c'];

if (file_exists($lockFile)) {
    echo "NOK - Lock File Exist on $lockFile \n";
    exit;
} else {
    touch($lockFile);
}

$dr = new manager_dr_processor();
$result = $dr->updateTransact($arr);

if ($result) {
    echo "OK \n";
} else {
    echo "NOK \n";
}

unlink($lockFile);


