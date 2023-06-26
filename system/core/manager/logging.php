<?php

class manager_logging {

    protected static $instance = NULL;
    protected static $object = array();
    protected $currentProfile = NULL;

    private function __construct() {
        
    }

    public static function getInstance() {

        if (self::$instance == NULL) {
            self::$instance = new self ();
        }

        return self::$instance;
    }

    public function setProfile($profile = 'default') {
        if (array_key_exists($profile, self::$object) == FALSE) {
            $config = loader_config::getInstance()->getConfig('logging');
            $profileArray = $config->profile [$profile];

            $className = 'logging_' . $profileArray ['type'];
            $classDefault = 'logging_default';

            self::$object [$profile] = (class_exists($className)) ? new $className($profileArray) : new $classDefault($profileArray);
        }

        $this->currentProfile = $profile;

        //return self::$object[$profile];
    }

    public function getProfile() {

        return $this->currentProfile;
    }

    public function write($loggingArr) {
        if (!$this->currentProfile)
            $this->setProfile();
        $loggingArr['pid'] = posix_getpid();
        $objLogger = self::$object [$this->currentProfile];
        return $objLogger->write($loggingArr);
    }

    public function writeDefault($processType=null,$loggingArr) {
        if (!$this->currentProfile)
            $this->setProfile();
        $objLogger = self::$object [$this->currentProfile];
        return $objLogger->writeDefault($processType, $loggingArr);
    }

}
