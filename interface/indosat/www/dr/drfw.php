<?php
//202.152.162.163 - - [04/May/2020:00:13:03 +0700] "GET /adapter/vendor/indosat/dr/index.php?time=20200504001303&serviceid=99876301001001&dest=62855123&tid=100003200301200503171224322461&status=2 HTTP/1.1" 200 152 "-" "-"

$start = time();

require_once '/app/xmp2012/interface/indosat/xmp.php';

$log_profile = 'dr_forwarder';
$log = manager_logging::getInstance ();
$log->setProfile($log_profile);

$params = implode("&", array(
         "time=" . $_REQUEST['time']
        ,"serviceid=" . $_REQUEST['serviceid']
        ,"dest=" . $_REQUEST['dest']
        ,"tid=" . $_REQUEST['tid']
        ,"status=" . $_REQUEST['status']
));
$url = "http://103.77.79.19:8087/drindosat/index.php?" . $params;

$response = file_get_contents($url);

$elapse = (int)time() - (int)$start;

$log->write(array('level' => 'info', 'message' => "Hit : " . $url . ", Response : " . $response . ", Elapse : " . $elapse));
exit();

