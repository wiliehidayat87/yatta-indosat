<?php
$pwd=$_GET['pwd'];
$sid=$_GET['sid'];
$msisdn=$_GET['msisdn'];
$sms=$_GET['sms'];
$trx_id=$_GET['trx_id'];


$arr = array(
    '2' => 'SEND',
    '3' => 'FAILED',
    '4' => 'FAILED',
    '5' => 'FAILED',
    '6' => 'FAILED',
    '7' => 'FAILED',
    '8' => 'FAILED',
    '9' => 'FAILED',
    '97' => 'FAILED',
    '99' => 'FAILED'
);

/*$a = file_get_contents("http://localhost/exec.php?pwd=$pwd&sid=$sid&msisdn=$msisdn&sms=$sms&trx_id=$trx_id");
$x = $http_response_header;
foreach ($x as $header => $value) {
    echo "$value\n";
}
echo "\n\n";*/
//echo $a."\n";
/*$t='<?xml version="1.0"?>
<MO>
    <STATUS>'.array_rand($arr).'</STATUS>
    <TRANSID>'.$trx_id.'</TRANSID>
    <MSG>'.$arr[array_rand($arr)].'</MSG>
</MO>
';*/

$t = array_rand($arr).":".$trx_id.":".$arr[array_rand($arr)];
echo $t;
?>