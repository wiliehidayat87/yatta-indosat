<?php
/*
 * http://ip:port://?time=[]&serviceid=[]&tid=[]&dest=[]&status=[]
 * 
 */
require_once '/app/xmp2012/interface/indosat/xmp.php';

if (isset($_GET) && count($_GET) != 0) {
    $_GET['adn'] = $_GET['sc'];	
    $drProcessor = new manager_dr_processor ( );
    $response = $drProcessor->saveToBuffer($_SERVER['QUERY_STRING']);
    
    if ($response == 'OK') {
    	printResponse($_GET['tid']);
    } else {
    	printResponse($_GET['tid'], 3);
    }
    
} else {
    printResponse($_GET['tid'], -1);
}

function printResponse($trx_id, $status=2) {
	
	$response_str = array(
	      2	=> 'Message processed successfully',
	   -1 => 'Parameter incomplete',
		3 => 'System error', 
		
	);
	
	
	$response = '<?xml version="1.0" ?><DR><STATUS>'.$status.'</STATUS><TRANSID>'.$trx_id.'</TRANSID><MSG>'.$response_str[$status].'</MSG></DR>'; 

	
	header('Content-type: text/xml');
	echo $response;
}
