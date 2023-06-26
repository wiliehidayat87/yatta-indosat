#!/usr/bin/php
<?php
require_once '/app/xmp2012/interface/indosat/xmp.php';

$broadcast = new indosat_broadcast_base ();
$result = $broadcast->retryDP();
if ($result) {
    echo "OK \n";
} else {
    echo "NOK \n";
}
