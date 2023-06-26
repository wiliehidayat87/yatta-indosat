<?php

class indosat_cmp_processor_adm {

    public function process($arrdata,$hset_records) {
		$log = manager_logging::getInstance();
		$hmo = loader_model::getInstance()->load('hmo', 'cmp');
		$hset = loader_model::getInstance()->load('hset', 'cmp');
		$mdata['msgid'] = $arrdata['id'];		
		$mdata['msisdn'] = $arrdata['instid'];
		
		$url = $hset_records['api_url'];
		$params = explode('|',$hset_records['params']);
		foreach($params as $idx => $row) {
			if(isset($mdata[$row])) {
				if($row=='msgData') {
					$url = str_replace('@'.$row.'@',urlencode($mdata[$row]),$url);
				} else {
					$url = str_replace('@'.$row.'@',$mdata[$row],$url);
				}
			} else {
				return true;
			}
		}

		$hmo_data = new model_data_hmo();
		$hset_data = new model_data_hset();
		$hmo_data->msisdn = $mdata['msisdn'];
		$hmo_data->date_send = date('Y-m-d');
		$hmo_data->time_send = date('H:i:s');
		$hmo_data->hash = $arrdata['id'];
		$hmo_data->hset_id = $hset_records['id'];
		$hmo_data->status = 0;
		//if($hmo->isUnique($hmo_data)) {
			if(($hset_records['inc']+1) >= $hset_records['counter']) {
				$arrUrl = explode('?',$url);
				$url = $arrUrl[0];
				$prm = isset($arrUrl[1]) ? $arrUrl[1] : '';		
				$hit = http_request::get($url, $prm, $hset_records['send_timeout']);
				$hit = trim(strtoupper($hit));
				$hmo_data->closereason=$hit;
				//if($hit=='SUCCESS=TRUE;') {
					$hmo_data->status = 1;				
					$hmo_data->closereason='OK';
				//}				
				$hset_data->inc = 0;
			} else {
				$hset_data->inc = $hset_records['inc']+1;
			}	
		//} else {
		//	$hmo_data->closereason = 'not unique';
		//}
		if($hmo->save($hmo_data)) {
			$hset_data->id = $hset_records['id'];
			$hset->update($hset_data);
		}

		return true;
    }
}

?>

