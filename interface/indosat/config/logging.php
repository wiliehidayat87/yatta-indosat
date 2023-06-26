<?php

class config_logging {

    public $timeDigit = 8;
    
    public $lineFormat = "{uniqueId} {level} {datetime} {exectime} {class} {function} {message} {response}";
	public $loglevel = 1; //1; //set to 9 to disable debug level logging
    //public $loglevel = 1;
    
	public $profile = array(
        'default' => array(
            'path' => '/data/log/indosat/default',
            'type' => 'file',
            'filename' => 'default'
        ),
        'mo_receiver' => array(
            'path' => '/data/log/indosat/mo_receiver',
            'type' => 'file',
            'filename' => 'mo_receiver'
        ),
        'mo_processor' => array(
            'path' => '/data/log/indosat/mo_processor',
            'type' => 'file',
            'filename' => 'mo_processor'
        ),
        'mt_processor' => array(
            'path' => '/data/log/indosat/mt_processor',
            'type' => 'file',
            'filename' => 'mt_processor'
        ),
        'mo_subscriber' => array(
            'path' => '/data/log/indosat/mo_subscriber',
            'type' => 'file',
            'filename' => 'mo_subscriber'
        ),
        'broadcast' => array(
            'path' => '/data/log/indosat/broadcast',
            'type' => 'file',
            'filename' => 'broadcast'
        ),
        'call_center' => array(
            'path' => '/data/log/indosat/callcenter',
            'type' => 'file',
            'filename' => 'callcenter'
        ),
        'dr_receiver' => array(
            'path' => '/data/log/indosat/dr_receiver',
            'type' => 'file',
            'filename' => 'dr_receiver'
        ),
        'dr_processor' => array(
            'path' => '/data/log/indosat/dr_processor',
            'type' => 'file',
            'filename' => 'dr_processor'
        ),
        'dr_updater' => array(
            'path' => '/data/log/indosat/dr_updater',
            'type' => 'file',
            'filename' => 'dr_updater'
        ),
	'retry_processor' => array(
            'path' => '/data/log/indosat/retry_processor',
            'type' => 'file',
            'filename' => 'retry_processor'
        ),
        'cmp_processor' => array(
            'path' => '/data/log/indosat/cmp_processor',
            'type' => 'file',
            'filename' => 'cmp_processor'
        )
    );

}
