<?php
class manager_controller {
	public function process($GET) {
                $log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
                
		$xmp_controller = $GET ['xmp_controller'];
		$class_name = 'controller_' . $xmp_controller;
		//echo $class_name;
		if (class_exists ( $class_name )) {
			$controller = new $class_name ( );
			return $controller->process ( $GET );
		} else {
			//return 'INVALID PARAMETERS';
			return 'NOK';
		}
	}
}