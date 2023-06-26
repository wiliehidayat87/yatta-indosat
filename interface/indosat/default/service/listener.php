<?php
class default_service_listener implements service_listener {
	
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
		
		$mo_parser = new mo_parser ();
		$mo_data = $mo_parser->parseMessage ( $mo_data );
		
		return manager_service_generator::getInstance ()->runHandler ( $mo_data );
	}
}