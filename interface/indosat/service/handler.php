<?php
class indosat_service_handler implements service_listener{
    public function notify($mo_data){
        $log = manager_logging::getInstance ();
        $log->write(array('level' => 'debug', 'message' => "Start"));
        
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
        $mt_data->mo             = $mo_data;
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
        $mt_data2->mo             = $mo_data;
        $mtArr[] = $mt_data2;
        
        //mt data 3 (WAPPUSH)
        $mt_data3 = loader_data::get ( 'mt' );
        $mt_data3->inReply       = $row_id;
        $mt_data3->msgId         = $mo_data->msgId;
        $mt_data3->operatorName  = $main_config->operator;
        $mt_data3->adn           = $mo_data->adn;
        $mt_data3->operatorId    = $mo_data->operatorId;
        $mt_data3->type          = "wappush";
        $mt_data3->price         = "10000.00";
        $mt_data3->service       = $mo_data->service;
        $mt_data3->channel       = $mo_data->channel;
        $mt_data3->subject       = $mo_data->subject;
        $mt_data3->msisdn        = $mo_data->msisdn;
        $mt_data3->msgData       = $mo_data->msgData;
        $mt_data3->mo             = $mo_data;
        $mtArr[] = $mt_data3;
        
        //mt data 4 (DelayWapPush)
        $mt_data4 = loader_data::get ( 'mt' );
        $mt_data4->inReply       = $row_id;
        $mt_data4->msgId         = $mo_data->msgId;
        $mt_data4->operatorName  = $main_config->operator;
        $mt_data4->adn           = $mo_data->adn;
        $mt_data4->operatorId    = $mo_data->operatorId;
        $mt_data4->type          = "delaywappush";
        $mt_data4->price         = "10000.00";
        $mt_data4->service       = $mo_data->service;
        $mt_data4->channel       = $mo_data->channel;
        $mt_data4->subject       = $mo_data->subject;
        $mt_data4->msisdn        = $mo_data->msisdn;
        $mt_data4->msgData       = $mo_data->msgData;
        $mt_data4->mo             = $mo_data;
        $mtArr[] = $mt_data4;
        
        foreach($mtArr as $mtData){
            $manager_mt_processor->saveToQueue ( $mtData );
        }

        return $mo_data;
    }
}