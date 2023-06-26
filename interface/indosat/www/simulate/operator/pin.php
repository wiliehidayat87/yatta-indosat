<?php
if($_POST){
echo "---";
print_r($_POST);
echo "---";
}else{
$xml = '<alerts_request operation="authenticate_confirmation"> <partner_id>kb</partner_id> <partner_key>kb43430</partner_key> <channel>CLUBFUN</channel> <authentication_key>7713</authentication_key> <target_list> <target>1181589940</target> </target_list> </alerts_request>';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://webapps.okto.com.br:60231/');
//curl_setopt($ch, CURLOPT_URL, 'http://202.146.224.76:11001/simulate/operator/pin.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
$output = curl_exec($ch);
curl_close($ch);
echo "\n" . $output . "\n";
}
