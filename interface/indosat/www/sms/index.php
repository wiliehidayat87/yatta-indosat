<?php
require_once '/app/xmp2012/interface/telkomsel/xmp.php';

if ($_GET) {
    $moProcessor = new manager_mo_processor ( );
    $response = $moProcessor->saveToFile($_GET);
    echo $response;
} else {
    echo 'NOK';
}
?>
