<?php
class user_manager {
	private static $instance;
	private function __construct() {
	}
	public static function getInstance() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		if (! self::$instance)
			self::$instance = new self ();
		
		return self::$instance;
	}
	public function getUserData($user_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$operator_name = $main_config->operator;
		
		$class_name = $operator_name . "_user_manager";
		$class_default = "default_user_manager";
		
		if (class_exists ( $class_name )) {
			$user = $class_name::getInstance ();
		} else if (class_exists ( $class_default )) {
			$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
			$user = $class_default::getInstance ();
		} else {
			$log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . $class_default));
			return false;
		}
		
		return $user->getUserData ( $user_data );
	}
	public function getUserException($user_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$operator_name = $main_config->operator;
		
		$class_name = $operator_name . "_user_manager";
		$class_default = "default_user_manager";
		
		if (class_exists ( $class_name )) {
			$user = $class_name::getInstance ();
		} else if (class_exists ( $class_default )) {
			$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
			$user = $class_default::getInstance ();
		} else {
			$log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . $class_default));
			return false;
		}
		
		return $user->getUserException ( $user_data );
	}
	public function addUserData($user_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$operator_name = $main_config->operator;
		
		$class_name = $operator_name . "_user_manager";
		$class_default = "default_user_manager";
		
		if (class_exists ( $class_name )) {
			$user = $class_name::getInstance ();
		} else if (class_exists ( $class_default )) {
			$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
			$user = $class_default::getInstance ();
		} else {
			$log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . $class_default));
			return false;
		}
		
		return $user->addUserData ( $user_data );
	}
	public function updateUserData($user_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$operator_name = $main_config->operator;
		
		$class_name = $operator_name . "_user_manager";
		$class_default = "default_user_manager";
		
		if (class_exists ( $class_name )) {
			$user = $class_name::getInstance ();
		} else if (class_exists ( $class_default )) {
			$log->write(array('level' => 'info', 'message' => "Class Doesn't Exist : " . $class_name));
			$user = $class_default::getInstance ();
		} else {
			$log->write(array('level' => 'error', 'message' => "Class Doesn't Exist : " . $class_name . " & " . $class_default));
			return false;
		}
		
		$user->updateUserData ( $user_data );
		return true;
	}
}