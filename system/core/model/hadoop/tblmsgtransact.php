<?php

class model_hadoop_tblmsgtransact {

    public function saveTransact($obj) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($obj)));

        $data = new model_data_tblmsgtransact ( );
        $data->messageid = $obj->msgId;
        $data->sumdate = date('Y-m-d');
        $data->adn = $obj->adn;
        $data->operatorid = $obj->operatorId;
        $data->serviceid = $obj->serviceId;
        $data->price = $obj->price;
        $data->partner = $obj->partner;
        $data->service = $obj->service;
        $data->subject = $obj->subject;
        $data->msgLastStatus = $obj->msgStatus;

        $config_hadoop = loader_config::getInstance()->getConfig('hadoop');

        $file_data = loader_data::get('file');
        $filename = "mt_transact_" . date($config_hadoop->formatFile) . ".log";
        $file_data->path = $config_hadoop->fileMTPath . "/" . $filename;
        $file_data->content = implode("\t", tools_convert::objectToArray($data));

        return logging_file::writeDBFile($file_data);
    }

}