<?php
    require_once "/app/operator/mcp/0.1/mcp/xmp.php";
    
//   $url = 'http://10.1.2.66:11001/mo_client_url.php';
$url = 'http://103.3.221.154:10000/mt/mvas/send';
$param='<message  type="mtpush">	
  	
  <msisdn>DFJIFUN</msisdn>	
  <sms><![CDATA[terimakasih anda telah masuk layanan content]]></sms>	
  	
  <ccode>9790SMSPUSH2000</ccode>	
  	
  <scode>DFJIFUN</scode>	
  	
  <cpid>passindonesia</cpid>	
  	
  <cppwd>passindonesia</cppwd>	
	
  </message>';	
  	


/*    $param = '<message type="mo">
      <adn>9877</adn>
      <msisdn>12345</msisdn>
      <tid>' . mt_rand(1,9999) . '</tid>
      <sms>reg apa</sms>
      <tdate>' . date('Y-m-d H:i:s') . '</tdate>
      </message>';
*/
    echo http_request::post($url, $param);
