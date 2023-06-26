#!/usr/bin/php
<?php
require_once '/app/interface/indosat/xmp.php';

$broadcast = new manager_broadcast ();
$result = $broadcast->process ();
if ($result) {
	echo "OK \n";
} else {
	echo "NOK \n";
}
