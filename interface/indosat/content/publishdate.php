<?php
class indosat_content_publishdate implements content_interface {
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
	
	public function get($broadcast_data) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => "Start" ) );
		
		$model_pushcontent = loader_model::getInstance ()->load ( 'pushcontent', 'connBroadcast' );
		$data = $model_pushcontent->getContent ( $broadcast_data );
		if ($data !== false) {
			$push_content = new broadcast_pushdata ();
			$push_content->id = $data ['id'];
			$push_content->service = $data ['service'];
			$push_content->contentLabel = $data ['content_label'];
			$push_content->content = $data ['content'];
			$push_content->author = $data ['author'];
			$push_content->notes = $data ['notes'];
			$push_content->datePublish = $data ['datepublish'];
			$push_content->lastUsed = $data ['lastused'];
			$push_content->created = $data ['created'];
			$push_content->modified = $data ['modified'];
			
			return $push_content;
		}
	}
}