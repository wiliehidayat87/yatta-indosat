<?php

class default_partner_processor {

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

    public function process($obj) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($obj)));

        $config_partner = loader_config::getInstance()->getConfig('partner');
        $url = $config_partner->partnerUrl;
        $hit = http_request::get($url, "");

        $data = new partner_log_data ( );
        $data->msisdn = $obj->msisdn;
        $data->msgId = $obj->msgId;
        $data->service = $obj->service;
        $data->dateTime = date("Y-m-d H:i:s");

        $file_data = loader_data::get('file');
        $filename = $obj->mo->partner . "_" . date($config_partner->formatFile) . ".log";
        $file_data->path = $config_partner->fileMTPath . "/" . $filename;
        $file_data->content = implode("\t", tools_convert::objectToArray($data));

        return logging_file::writeDBFile($file_data);
    }

}