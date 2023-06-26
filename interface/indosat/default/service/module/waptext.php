<?php

class default_service_module_waptext implements service_module_interface {
	public function run(mo_data $mo, service_reply_data $reply)
	{
		/* @var $mt mt_data */
		$mt = loader_data::get('mt');
		$mt_manager = new manager_mt_processor();
		
		/* @var $replyAttribute model_replyattribute */
		$replyAttribute = loader_model::get('replyattribute', 'connDatabase1');
		$replyAttribute->get($reply->id);
		$replyData = array();
		
		foreach($replyAttribute as $data)
		{
			$replyData[$data['name']] = $data['value'];
		}
		
		/* @var $user user_data */
		$user = loader_data::get('user');
		$user->adn = $mo->adn;
		$user->msisdn = $mo->msisdn;
		$user->operator = $mo->operatorName;
		$user->active = '2';
		
		$userManager = user_manager::getInstance();
		
		
		$contentData = NULL;
		
		$mt->inReply      = $mo->id;
		$mt->msgId        = date('YmdHis') . str_replace('.', '', microtime(true));
		$mt->adn          = $mo->adn;
		$mt->channel      = $mo->channel;
		$mt->operatorId   = $mo->operatorId;
		$mt->operatorName = $mo->operatorName;
		$mt->service      = $mo->service;
		$mt->serviceId    = $mo->serviceId;
		$mt->subject      = strtoupper('MT;WAPPUSH;'.$mo->channel.';'.$mo->operatorName);
		$mt->msisdn       = $mo->msisdn;
		$mt->type         = 'wappush';
		$mt->price        = $reply->price;
		$mt->msgData      = $reply->message;
		$mt->content_data = $contentData;
		
		//process mo
		$mt_manager->saveMOToTransact($mo);
		$mt_manager->saveToQueue($mt);
		
		return $mo;
	}
	
	private function getMsgBeforeRegistered($mo, $reply, $replyAttributes) 
	{
		
	}
	
	private function getMsgAfterRegistered($mo, $reply, $replyAttributes) 
	{
		
	}
}