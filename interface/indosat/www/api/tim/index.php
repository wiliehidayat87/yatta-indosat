<?php
require_once '/app/operator/tim/0.1/tim/xmp.php';

$xml = (isset($HTTP_RAW_POST_DATA)) ? $HTTP_RAW_POST_DATA : file_get_contents('php://input');

$api = new tim_manager_subscription_api();

$response = $api->process($xml);
echo $response;

