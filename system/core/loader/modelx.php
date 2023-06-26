<?php

class loader_modelx {
	private static $instance	= NULL;
	private $model				= array();
	private $connection			= array();
	
	private function __construct() {}
	
	public function getInstance() {
		
		if (! self::$instance) {
			self::$instance = new self ();
		}
		
		return self::$instance;
		
	}
	
	public function load($modelName, $connProfile) {
		
		if(empty($modelName) || empty($connProfile)) {
			return FALSE;	
		}
		
		$modelName	= strtolower($modelName);
		$driver		= loader_config::getInstance ()->getConfig ( 'database' )->profile [$connProfile] ['driver'];
		$className	= ($driver == 'mysql') ? 'model_' . $modelName : 'model_' . strtolower($driver) . '_' . $modelName;
		
		$model = $this->initModel($className);
		$conn = $this->initConn($connProfile);
		
		$model->setConnection($conn);
		return $model; 
	}
	
	private function initModel($className) {
		
		if(!isset($this->model[$className]) || !is_object($this->model[$className])) {
			
			if(!class_exists($className)) {
				
				return FALSE;
			}
			
			$this->model[$className] = new $className();
			
		}
		
		return $this->model[$className];
	}
	
	private function initConn($connProfile) {
		
		$db	= database_basex::getInstance (); // Change This to the right class
		
		if(!isset($this->connection[$connProfile]) || !is_resource($this->connection[$connProfile])) {
			
			$reload = FALSE;
			
		} else {
			
			$reload = TRUE;	
		}
		
		$conn = $db->load($connProfile, $reload);
		$this->connection[$connProfile] = $conn;
		
		return $this->connection[$connProfile];
	}
}