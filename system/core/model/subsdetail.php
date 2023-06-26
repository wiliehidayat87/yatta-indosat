<?php

class model_subsdetail extends model_base {
	
	public function gen_subs($date,$service) {
		$sql = "insert into subscription_detail(id,id_subscription,msisdn,active,`desc`) 
select '',id,msisdn,0,service from subscription where service='$service' and date(time_created)='$date')";
		//$this->databaseObj->query($sql);
		//$this->count($date,$service);
	}
	
	public function add($data) {
		$data->id_subscription = $this->getHebohSubsId($data);
		if($data->id_subscription > 0) {
		$sql = "insert into subscription_detail(id_subscription,msisdn,active,`desc`,counter) 
			values('".$data->id_subscription."','".$data->msisdn."','".$data->active."','".$data->desc."',1) ";
			$this->databaseObj->query($sql);
		
		return $this->databaseObj->last_insert_id();
		}		
	}

	public function update($data) {
                $sql = "update subscription_detail set active='".$data->active."', time_updated=now(),closereason='".$data->closereason."' where id='".$data->id."'";
                return $this->databaseObj->query($sql);
        }

	public function inc_counter($data) {
                $sql = "update subscription_detail set counter=(counter+1) where msisdn='".$data->msisdn."' AND active=0 limit 1";
                return $this->databaseObj->query($sql);
        }

	public function count($data) {
		$sql = "select count(*) as total from subscription_detail where msisdn='".$data->msisdn."' AND active=0";
		$data = $this->databaseObj->fetch($sql);
		return $data[0]['total'];
	}

	public function isNew($data) {
		$result = FALSE;
		$count = $this->count($data);
		if($count==0){
			$result = TRUE;
		}
		return $result;
	}
	
	public function findActive($data,$limit=5) {
		$sql = "select id,id_subscription,active,msisdn from subscription_detail where counter >= '".$data->counter."' AND active='".$data->active."' AND date(time_updated) < date(now()) limit $limit";
		$records = $this->databaseObj->fetch($sql);
                return $records;
	}
	
	public function getHebohSubsId($data) {
		$sql = "select id from subscription where msisdn='".$data->msisdn."' and service='".$data->desc."' and active='1' limit 1";
		$records = $this->databaseObj->fetch($sql);
		$records[0]['id'] = isset($records[0]['id']) ? $records[0]['id'] : 0;
		return $records[0]['id'];
	}

	public function getHebohTransact($data) {
		//$id = '2271622';
		$fileid = '/home/script/lastidheboh.log';
		$incr = 0;
		$id = file_get_contents($fileid);

		if(!empty($id)) {
			$where = 'AND id > '.$id;

			$sql = "select id,msisdn from tbl_msgtransact where service='".$data->desc."' and subject='MT;PUSH;SMS;DAILYPUSH' and msgstatus='DELIVERED' ".$where." order by id asc limit 100";
			//echo "$sql\n";

			$records = $this->databaseObj->fetch($sql);
			$incr = 10;
			if($records) {
				$incr = 0;
		                foreach($records as $row) {
					$data->msisdn = $row['msisdn'];
					if($this->isNew($data)) {
        					$this->add($data);
					} else {
        					$this->inc_counter($data);
					}
					file_put_contents($fileid,$row['id']);
					$incr = $incr + 1;
        	        	}
			}
		}
		return $incr;
	}
}

?>
