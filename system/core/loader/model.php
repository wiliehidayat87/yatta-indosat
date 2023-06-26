<?php

class loader_model {

    private static $instance = NULL;
    private $model = array();
    private $connection = array();

    private function __construct() {
        
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self ();
        }
        return self::$instance;
    }

    public function load($modelName, $connProfile) {
        if (empty($modelName) || empty($connProfile)) {
            return FALSE;
        }
        $modelName = strtolower($modelName);
        $driver = loader_config::getInstance()->getConfig('database')->profile [$connProfile] ['driver'];
        $className = ($driver == 'mysql') ? 'model_' . $modelName : 'model_' . strtolower($driver) . '_' . $modelName;
		
        //echo $className;
        
        $model = $this->initModel($className);
        $conn = $this->initConn($connProfile);
        if (($driver == 'mysql') && ($conn === false)) {
            return false;
        }
        
        if (strtolower($driver) != 'hadoop') {
            $model->setConnection($conn);
        }
        return $model;
    }

    private function initModel($className) {
        if (!isset($this->model[$className]) || !is_object($this->model[$className])) {
            if (!class_exists($className)) {
                return FALSE;
            }
            $this->model[$className] = new $className();
        }
        return $this->model[$className];
    }

    private function initConn($connProfile) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Check connection : " . @print_r($this->connection[$connProfile], 1)));

        $db = database_base::getInstance(); // Change This to the right class
        if (!isset($this->connection[$connProfile]) && @!is_resource($this->connection[$connProfile]->resource)) {
            $conn = $db->load($connProfile, true);
            if ($conn) {
                $this->connection[$connProfile] = $conn;
            } else {
                return false;
            }
        }

        return $this->connection[$connProfile];
    }

}