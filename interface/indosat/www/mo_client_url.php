<?php

/**
 * example http request
 * <message type="mo">
      <adn>9877</adn>
      <msisdn>9800002222</msisdn>
      <tid>1234567890</tid>
      <sms>REG BAND</sms>
      <tdate>2012-02-21 12:31:20</tdate>
      </message>
 */
$xml = (file_get_contents('php://input'));

require_once '/app/xmp2012/interface/indosat/xmp.php';

if (@simplexml_load_string($xml) !== false) {
    $moProcessor = new manager_mo_processor ( );
    $response = $moProcessor->saveToFile($xml);
    echo $response;
} else {
    echo 'NOK';
}

