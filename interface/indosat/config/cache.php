<?php

class config_cache {

    public $profile = array(
        'cache_server1' =>
        array(
            'host' => '127.0.0.1'
            , 'port' => '11211'
        ),
        'cache_server2' =>
        array(
            'host' => '127.0.0.1'
            , 'port' => '11211'
        )
    );
    public $host;
    public $port;
    public $timeout;
    // when live expire is not set (recomended)
    public $expire = 10;
    public $key_service_prefix = 'service'; // ex : service_$keyword_$operatorId
    public $key_operatorId_prefix = 'operatorId'; // ex : operatorId_$name 
    public $key_charging_prefix = 'charging';  // ex : operatorId_$name 

}