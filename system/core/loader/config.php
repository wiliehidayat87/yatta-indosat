<?php
/**
 * 
 * @author Dev Team
 *
 */

class loader_config {
	
	private static $instance;
	private $configContainer = array (); // store config as array of object
	private $config_path = CONFIG_PATH;
	
	private function __construct() {
		$this->populateContainer ();
	}
	
	public static function getInstance() {
		
		if (! self::$instance) {
			self::$instance = new self ( );
		}
		
		return self::$instance;
	}
	
	/**
	 * read all file inside config directory and insantiate all class then store it in var $configContainer as array of object
	 * @return void
	 */
	private function populateContainer() {
		
		# get php filename
		$dh = opendir ( $this->config_path );
		if ($dh != false) {
			while ( false !== ($filename = readdir ( $dh )) ) {
				if (($filename != '.' or $filename != '..') and preg_match ( '/\.php$/i', $filename )) {
					require_once $this->config_path . '/' . $filename;
					$cfg_name [] = substr ( $filename, 0, - 4 );
				}
			}
			closedir ( $dh );
		} else {
			echo 'ERROR , Directory is not found or readable : ' . $this->config_path;
		}
		
		#instantiate each class
		foreach ( $cfg_name as $val ) {
			$class_name = 'config_' . $val;
			$this->configContainer [$val] = new $class_name ( );
		}
	
	}
	
	/*
	 * @param config name
	 * @return [object]
	 */
	public function getConfig($config_name) {
		// check $config name is array or string
		if(is_array($config_name)){
			$cfg = new stdClass();
			foreach($config_name as $name){
				$cfg->{$name} = $this->configContainer[$name];
			}
			return $cfg;
		}
		return $this->configContainer[$config_name];
	}

}