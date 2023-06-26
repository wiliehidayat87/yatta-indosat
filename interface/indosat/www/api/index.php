<?php

require_once('/app/xmp/0.4/default/config/bootstrap.php');
require_once('/app/xmp/0.4/core/manager/api.php');

$manager = new manager_api();
$process = $manager->process($_GET);

echo json_encode ( $process, true );
