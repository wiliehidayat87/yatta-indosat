<?php

class model_hadoop_dr {

    public function saveDR($obj) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($obj)));

        $data = new model_data_dr ( );
        $data->msgIndex = $obj->msgId;
        if ($obj->msgStatus)
            $data->statusInternal = $obj->msgStatus;
        else
            $data->statusInternal = $obj->statusInternal;
        $data->operatorId = $obj->operatorId;
        $data->subject = $obj->subject;

        $config_hadoop = loader_config::getInstance()->getConfig('hadoop');

        $file_data = loader_data::get('file');
        $filename = "cdr_" . date($config_hadoop->formatFile) . ".log";
        $file_data->path = $config_hadoop->fileDRPath . "/" . $filename;
        $file_data->content = implode("\t", tools_convert::objectToArray($data));

        return logging_file::writeDBFile($file_data);
    }

}