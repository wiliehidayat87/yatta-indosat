<?php

class service_creator_handler implements service_listener {

    public function notify($mo_data) {
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $loaderConfig = loader_config::getInstance();
        $configMain = $loaderConfig->getConfig('main');
        $generate = service_reply_creator::getInstance();

        $mo_data->subject = strtoupper('MO;PULL;' . $mo_data->channel . ';HANDLERCREATOR');
        $reply_creator = $generate->generate($mo_data->patternId);

        foreach ($reply_creator as $reply) {
        	$log->write(array('level' => 'debug', 'message' => "Start Check ServiceHandler "));
            $className = $configMain->operator . '_' . $reply->moduleHandler;
            if (!class_exists($className)) {
                $className = 'default_' . $reply->moduleHandler;
            }
            $serviceHandler = new $className();
            $log->write(array('level' => 'debug', 'message' => "USE ServiceHandler $className "));
            $reply_creator = $serviceHandler->run($mo_data, $reply);
        }
        return $mo_data;
    }

}
