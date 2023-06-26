<?php

class default_retry_processor implements retry_processor {

    public function queue() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $buffer_file = buffer_file::getInstance();
        $load_config = loader_config::getInstance();

        $config_retry = $load_config->getConfig('retry');

        $path = $config_retry->bufferPathQueue;
        $limit = $config_retry->bufferThrottleQueue;
        $result = $buffer_file->readString($path, $limit);

        if ($result !== false) {
            foreach ($result as $val) {
                foreach ($val as $retryDataPath => $content) {
                    $log->write(array('level' => 'debug', 'message' => "Path : " . $retryDataPath . ". Content : " . serialize($content)));
                    
                    $log->writeDefault("retry_process",$content);

                    if (!empty($content)) {
                        $queue_data = unserialize($content);
                        $mt_data = unserialize($queue_data->value);

                        $queue = loader_queue::getInstance()->load($queue_data->profile);
                        if ($queue) {
                            if ($queue->put($queue_data)) {
                                $buffer_file->delete($retryDataPath);
                                if ($queue_data->profile == "dailypush" && !empty($mt_data->pushBufferId)) {
                                    $model_user = loader_model::getInstance()->load('pushbuffer', 'connBroadcast');
                                    $pushbuffer_data = new model_data_pushbuffer ();
                                    $pushbuffer_data->stat = "PUSHED";
                                    $pushbuffer_data->id = $mt_data->pushBufferId;
                                    $model_user->update($pushbuffer_data);
                                }
                            }
                        }
                    } else {
                        $buffer_file->delete($retryDataPath);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function db() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $buffer_file = buffer_file::getInstance();
        $load_config = loader_config::getInstance();

        $config_retry = $load_config->getConfig('retry');

        $path = $config_retry->bufferPathMysql;
        $limit = $config_retry->bufferThrottleMysql;
        $result = $buffer_file->readString($path, $limit);

        if ($result !== false) {
            foreach ($result as $val) {
                foreach ($val as $retryDataPath => $content) {
                    $log->write(array('level' => 'debug', 'message' => "Path : " . $retryDataPath . ". Content : " . $content));

                    if (!empty($content)) {
                        $retry_data = unserialize($content);
                        $db = loader_model::getInstance()->load('retry', $retry_data->profile);
                        if ($db) {
                            if ($db->save($retry_data->query)) {
                                $buffer_file->delete($retryDataPath);
                            }
                        }
                    } else {
                        $buffer_file->delete($retryDataPath);
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
