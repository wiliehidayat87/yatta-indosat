<?php

class service_reply_creator {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance)
            self::$instance = new self ( );

        return self::$instance;
    }

    public function generate($patternId) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $loader_model = loader_model::getInstance();

        $mechanism = $loader_model->load('mechanism', 'connDatabase1');
        $arrHandler = $mechanism->readAllModule($patternId);
        $replyAttribute = $loader_model->load('replyattribute', 'connDatabase1');
        $reply_data = array();
        $replyIndex = 0;

        foreach ($arrHandler as $data) {
            $reply = new service_reply_data();
            $reply->id = $data['id'];
            $reply->moduleName = $data['moduleName'];
            $reply->moduleHandler = $data['handler'];
            $reply->subject = $data['subject'];
            $reply->message = $data['message'];
            $reply->chargingId = $data['charging_id'];
	    $reply->chargingCode = $data['charging_code'];
            $reply->type = $data['message_type'];
            $reply->price = $data['gross'];
            $reply->sequence = $replyIndex;
            $replyIndex++;
            $reply_data[] = $reply;
        }
        return $reply_data;
    }

}
