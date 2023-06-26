<?php
class manager_service_listener {
	private static $instance;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		if (! self::$instance) {
			self::$instance = new self ();
		}
		
		return self::$instance;
	}
	
	public function notify($mo_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$config_main = loader_config::getInstance ()->getConfig ( 'main' );
		$class_name = $config_main->operator . '_service_listener';
		$class_default = 'default_service_listener';
		
		if (class_exists ( $class_name )) {
			$service_listener = $class_name::getInstance ();
		} elseif (class_exists ( $class_default )) {
			$log->write ( array ('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name ) );
			$service_listener = $class_default::getInstance ();
		} else {
			$log->write ( array ('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . $class_default ) );
			return false;
		}
		
		return $service_listener->notify ( $mo_data );
	}
}