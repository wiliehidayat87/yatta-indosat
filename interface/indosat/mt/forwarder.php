<?php

class indosat_mt_forwarder {

    public function process() {
        $log_profile = 'mt_processor';
        $log = manager_logging::getInstance();
        $log->setProfile($log_profile);
        $log->write(array('level' => 'debug', 'message' => "Start"));

        $model_mtdelay = loader_model::getInstance()->load('mtdelay', 'connDatabase1');

        $get_expired = $model_mtdelay->getExpiredByOperator('indosat');
        if ($get_expired) {
            foreach ($get_expired as $data) {


                $delay_data = new mt_delay_data ();
                $delay_data->id = $data ['id'];
                $delay_data->status = '1';
                    
                //$mt_data = unserialize(preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $data['obj']));
                $mt_data = unserialize($data['obj']);

                // Must be set to FALSE.
                $mt_data->isDelay = FALSE;
                
                $mt_processor = new manager_mt_processor ();
                $mt_processor->saveToQueue($mt_data);

                $model_mtdelay->update($delay_data);
                
            }
        }
        return true;
    }

}
