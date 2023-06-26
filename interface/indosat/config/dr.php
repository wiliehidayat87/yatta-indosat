<?php

/**
 * 
 * based on Telkomsel configuration
 *
 */
class config_dr {

    public $synchrounous = FALSE; // false; // if TRUE, after MT sent, the DR will be saved, otherwise nothing happens
    
    public $responseACK = array (
       -1 => 'Parameter Incomplete',
    	0 => 'Message processed successfully',
    	1 => 'Error in user id/password',
    	2 => 'Invalid Service ID',
    	3 => 'System Error',
    	4 => 'Invalid MSISDN',
    	5 => 'Connection Problem',
       99 => 'ERROR : Throttling Detected',
       96 => 'Subscribers is not registered in the service SPM',
                'Message processed successfully' => 'Message processed successfully',

    );
        
    public $responseText = array(
            2 => 'Success',
            3 => 'Carrier Internal Failure',
            4 => 'Insufficient Balance/Number In Grace Period',
            5 => 'Charging Failed',
            6 => 'MSISDN Not Subscribed',
            7 => 'Invalid Service ID',
            8 => 'Invalid Transaction ID',
            97 => 'Subscription Hit Limit',
       		99 => 'ERROR : Throttling Detected',    	
		96 => 'Subscribers is not registered in the service SPM',
                0 => 'Message processed successfully',
                'Message processed successfully' => 'Message processed successfully',
    );
    
    
    public $responseMap = array(
        'text' => array(
            2 => 'DELIVERED',
            3 => 'FAILED',
            4 => 'FAILED',
            5 => 'FAILED',
            6 => 'FAILED',
            7 => 'FAILED',
            8 => 'FAILED',
            97 => 'FAILED',
       		99 => 'FAILED',
		96 => 'FAILED',
		'0|Message processed successfully' => '',
		'|Message processed successfully' => '',    	
        ),
        'push' => array(
            2 => 'DELIVERED',
            3 => 'FAILED',
            4 => 'FAILED',
            5 => 'FAILED',
            6 => 'FAILED',
            7 => 'FAILED',
            8 => 'FAILED',
            97 => 'FAILED',
       		99 => 'FAILED',
		96 => 'FAILED',
        ),
        'wappush' => array(
            2 => 'DELIVERED',
            3 => 'FAILED',
            4 => 'FAILED',
            5 => 'FAILED',
            6 => 'FAILED',
            7 => 'FAILED',
            8 => 'FAILED',
            97 => 'FAILED',
       		99 => 'FAILED',
		96 => 'FAILED',    	        
        )
    );
    public $defaultHour = '1';
    public $bufferPath = '/app/xmp2012/buffers/indosat/drBuffer';
    public $bufferThrottle = '999';
    public $bufferSlot = 10;
    public $returnCode = array('OK' => 'OK', 'NOK' => 'NOK');

}
