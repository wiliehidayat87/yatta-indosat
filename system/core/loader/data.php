<?php
class loader_data {

	public static function get($charObj){
		$config_main = loader_config::getInstance()->getConfig( 'main' );
		$class_name  = $config_main->operator . '_'. $charObj . '_data';
		$class_name_default = $charObj . '_data';
		if (class_exists ( $class_name )) {
			return new $class_name ( );
		} else {
			return new $class_name_default ( );
		}
	}
}