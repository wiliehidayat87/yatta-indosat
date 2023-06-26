<?php

class loader_queue {

    private static $instance = NULL;
    private $queueObj = array();

    private function __construct() {
        
    }

    public static function getInstance() {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if (self::$instance == NULL) {
            self::$instance = new self ();
        }

        return self::$instance;
    }

    /**
     * 
     * @param char $profile
     * 
     * load queue object based on profile name in config_mt
     * 
     */
    public function load($profile) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start"));

        if ($profile == '' || empty($profile)) {
            $profile = 'default';
        }

        if (in_array($profile, $this->queueObj)) {
            return $this->queueObj [$profile];
        } else {
            $configMT = loader_config::getInstance()->getConfig('mt');
            $aProfile = $configMT->profile [$profile];
            $className = 'queue_' . $aProfile ['type'];
			
            $objQueue = new $className ();
            if ($objQueue->connect($aProfile)) {
                $this->queueObj [$profile] = $objQueue;
                return $objQueue;
            } else {
                return false;
            }
        }
    }

}