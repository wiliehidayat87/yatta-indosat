<?php
class default_service_abstract_handler {
	protected $config;
	protected $ini_reader;
	protected $mo_data;
	protected $mt_processor;
	protected $subscriber;
	protected $ini_data;
	
	public function __construct() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$this->config = loader_config::getInstance ()->getConfig ( 'main' );
		$this->ini_reader = ini_reader::getInstance ( $this->service );
		$cfgService = loader_config::getInstance ()->getConfig ( 'service' );
		$this->ini_data = new ini_data ();
		$this->ini_data->file = $cfgService->iniPath . $this->service . '.ini';
		$this->mt_processor = new manager_mt_processor ();
	}
	
	protected function setMT() {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$mt = loader_data::get ( 'mt' );
		$mt->inReply = $this->mo->id;
		$mt->msgId = date ( 'YmdHis' ) . str_replace ( '.', '', microtime ( true ) );
		$mt->adn = $this->mo->adn;
		$mt->operatorId = $this->mo->operatorId;
		$mt->service = $this->mo->service;
		$mt->subject = strtoupper ( 'MT;PULL;' . $this->mo->channel . ";" . $this->config->operator . ';CONTENT;OPT1;' . $this->mo->service );
		$mt->msisdn = $this->mo->msisdn;
		$mt->type = 'mtpull';
		$mt->mo = $this->mo;
		
		return $mt;
	}
	
	protected function sendMT($msg, $price) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$mt = $this->setMT ();
		$mt->msgData = $msg;
		$mt->price = $price;
		$mt->subject = strtoupper ( 'MT;PULL;' . $this->mo->channel . ";" . $this->config->operator . ';CONTENT;OPT1;' . $this->mo->service );
		
		return $this->mt_processor->saveToQueue ( $mt );
	
	}
	
	protected function sendPushMT($msg, $price) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$mt = $this->setMT ();
		$mt->msgData = $msg;
		$mt->price = $price;
		$mt->subject = strtoupper ( 'MT;PUSH;' . $this->mo->channel . ";" . $this->config->operator . ';CONTENT;OPT1;' . $this->mo->service );
		//$mt->type 	 = 'push';
		

		return $this->mt_processor->saveToQueue ( $mt );
	}
	
	protected function getMsg($type) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$this->ini_data->section = 'REPLY';
		$this->ini_data->type = $type;
		
		return $this->ini_reader->get ( $this->ini_data );
	}
	
	protected function getChargingPrice($type) {
		$log = manager_logging::getInstance ();
		$log->write ( array ('level' => 'debug', 'message' => 'Start' ) );
		
		$this->ini_data->section = 'CHARGING';
		$this->ini_data->type = $type;
		
		return $this->ini_reader->get ( $this->ini_data );
	}
}