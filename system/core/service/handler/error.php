<?php
class service_handler_error implements service_handler {
	
	public function saveMOtoTblMsgtransact($mo_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
	}
	
	/**
	 * @param $mo_data
	 */
	public function notify($mo_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$manager_mt_processor = new manager_mt_processor ();
		$manager_mt_processor->saveMOToTransact ( $mo_data );
	}

}