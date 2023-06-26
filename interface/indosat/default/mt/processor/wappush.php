<?php

class default_mt_processor_wappush implements mt_processor {

    private static $instance;

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function saveToQueue($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => 'Start'));

        $loader_queue = loader_queue::getInstance();
        $loader_config = loader_config::getInstance();
        $main_config = $loader_config->getConfig('main');
        $mt_config = $loader_config->getConfig('mt');

        $profile = 'wappush';
        $slot = $mt_config->profile [$profile] ['throttle'];
        $last_num = substr($mt_data->msisdn, - 1, 1);
        $num_channel = $last_num % $slot;

        $queue = $loader_queue->load($profile);

        $data = loader_data::get('queue');
        $data->channel = $mt_config->profile [$profile] ['prefix'] . $num_channel;
        $data->value = serialize($mt_data);
        $data->profile = $profile;
        
        $log->writeDefault("mt_queue",$data);

        if ($queue) {
            return $queue->put($data);
        } else {
            $config_retry = loader_config::getInstance()->getConfig('retry');

            $filename = uniqid() . ".queue";
            $path = $config_retry->bufferPathQueue . "/" . $filename;
            $content = $data;

            $buffer = buffer_file::getInstance();
            $buffer->save($path, $content);
            return false;
        }
    }

    public function process($slot) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));
    }

    public function saveMTToTransact($mt_data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start : " . serialize($mt_data)));

        $configDr = loader_config::getInstance()->getConfig('dr');

        if ($configDr->synchrounous === TRUE) {
            $previousProfile = $log->getProfile();

            $drObj = new manager_dr_processor();
            $drSave = $drObj->saveToBuffer($mt_data);

            $log->setProfile($previousProfile);
        }

        $config_hadoop = loader_config::getInstance()->getConfig('hadoop');
        if ($config_hadoop->enableMT == true) {
            loader_model::getInstance()->load('tblmsgtransact', 'connHadoop')->saveTransact($mt_data);
        }

        return loader_model::getInstance()->load('tblmsgtransact', 'connDatabase1')->saveMT($mt_data);
    }

}
