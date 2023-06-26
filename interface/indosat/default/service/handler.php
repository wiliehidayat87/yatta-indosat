<?php
class default_service_handler implements service_handler {
	
	public function notify($mo_data) {
		$main_config = loader_config::getInstance ()->getConfig ( 'main' );
		$manager_mt_processor = new manager_mt_processor();
		$row_id = $manager_mt_processor->saveMOToTransact ( $mo_data );
		
                $mtArr = array();
                
		$mt_data = loader_data::get ( 'mt' );
		$mt_data->inReply       = $row_id;
		$mt_data->msgId         = $mo_data->msgId;
		$mt_data->operatorName  = $main_config->operator;
		$mt_data->adn           = $mo_data->adn;
                $mt_data->operatorId    = $mo_data->operatorId;
                $mt_data->type          = "mtpull";
                $mt_data->price         = "10000.00";
                $mt_data->service       = $mo_data->service;
                $mt_data->channel       = $mo_data->channel;
                $mt_data->subject       = $mo_data->subject;
                $mt_data->msisdn        = $mo_data->msisdn;
                $mt_data->msgData       = $mo_data->msgData;
                $mtArr[] = $mt_data;
                
                //mt data 2 (delay push)
                $mt_data2 = loader_data::get ( 'mt' );
		$mt_data2->inReply       = $row_id;
		$mt_data2->msgId         = $mo_data->msgId;
		$mt_data2->operatorName  = $main_config->operator;
		$mt_data2->adn           = $mo_data->adn;
                $mt_data2->operatorId    = $mo_data->operatorId;
                $mt_data2->type          = "mtpush";
                $mt_data2->price         = "10000.00";
                $mt_data2->service       = $mo_data->service;
                $mt_data2->channel       = $mo_data->channel;
                $mt_data2->subject       = $mo_data->subject;
                $mt_data2->msisdn        = $mo_data->msisdn;
                $mt_data2->msgData       = $mo_data->msgData;
                $mtArr[] = $mt_data2;
                
                foreach($mtArr as $mtData){
                    $manager_mt_processor->saveToQueue ( $mtData );
                }
                
                return $mo_data;
	}
}