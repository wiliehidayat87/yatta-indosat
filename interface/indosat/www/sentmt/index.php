<?php
//uid=001520&pwd=u0Qtuwtz&serviceid=97900287008013&msisdn=6285731337871&sms=Jarak+yang+jauh+%26+waktu+yang+sempit+bisa+jadi+perusak+hubungan.+http%3A%2F%2Fwap.singiku.com%2Fdw%2F1099.3gp+Tarif+GPRS+normal+CS%3A085695779414+Stop%3A+UNREG+JOJOKU+ke+99790&transid=2020050407000615885504060551&smstype=0&sdmcode=jojoku_99790

$start = time();

require_once '/app/xmp2012/interface/indosat/xmp.php';

$log_profile = 'mt_forwarder';
$log = manager_logging::getInstance ();
$log->setProfile($log_profile);

$params = implode("&", array(
         "uid=" . $_REQUEST['uid']
        ,"pwd=" . $_REQUEST['pwd']
        ,"serviceid=" . $_REQUEST['serviceid']
        ,"msisdn=" . $_REQUEST['msisdn']
        ,"sms=" . urlencode($_REQUEST['sms'])
        ,"transid=" . $_REQUEST['transid']
        ,"smstype=" . $_REQUEST['smstype']
        ,"sdmcode=" . $_REQUEST['sdmcode']
));

$url = "http://202.152.162.163:8329/?" . $params;

$response = file_get_contents($url);

$elapse = (int)time() - (int)$start;

$log->write(array('level' => 'info', 'message' => "Hit : " . $url . ", Response : " . $response . ", Elapse : " . $elapse));
exit();
