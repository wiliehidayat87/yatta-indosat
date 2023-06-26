<?php

class model_pixel extends model_base {

    public function setPixel($pixel,$operator,$service = 'none', $campurl = 'none', $reffurl = 'none') {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Set Pixel to DB"));
        
	//$pixel = str_replace('?msisdn=0','',$pixel);
	$find_str = strpos($pixel,'?');
	if($find_str !== false){
	   $pixel = substr($pixel,0,strpos($pixel,'?'));
	}
	if(substr($pixel, 0, 3) == "ADT") {
	   $data['partner']='adsterdam';
	   //$pixel = str_replace('ADT','',$pixel);
	}
	else if(substr($pixel, 0, 3) == "102") {
	    $partner='pocketmedia';
	   //$pixel = str_replace('ADT','',$pixel);
	}
	else if(substr($pixel, 0, 11) == "TEST_OFFER_") {
            $partner='bmb';
        }
	else if(substr($pixel, 0, 7) == "bmconv_") {
            $partner='bmb';
        }
	else{ 
	if(strlen($pixel) == 30) {
	   //$partner='kissads';
	   $partner='isj';
	}else if(strlen($pixel) == 31) {
           $partner='isj';
	}else if(strlen($pixel) == 55) {
	   $partner='kimia';
	}else if(strlen($pixel) == 40 || strlen($pixel) == 41) {
	   $partner='tfc';
	}else if(strlen($pixel) == 23) {
	   $partner='mobusi';
	}else if(strlen($pixel) == 24) {
	   $partner='adm';
	//}else if(strlen($pixel) == 24) {
	   //$partner='okinet';
	}else if(strlen($pixel) == 12) {
           $partner='elymob';
	}else if(strlen($pixel) == 32) {
           $partner='pin';
	//}else if(strlen($pixel) == 32) {
           //$partner='adacts';
	}else if(strlen($pixel) == 45) {
           $partner='lev';
	//}else if(strlen($pixel) == 32) {
           //$partner='adacts';
	}else if(strpos(strtolower($pixel),'573360aa5d9d0')) {
              $partner='mobipium';
	}else {
	   if(preg_match('/^cd/',strtolower($pixel))) {
	      $partner='cd';
	      $pixel = str_replace('cd','',$pixel);
	   } else {
	      $partner='blank';
	   }
	}
	}
	
	//$partner='muji'; // loss partner
	
	if($partner!=='blank'){
        $sql = sprintf("INSERT INTO pixel_storage (pixel, operator, partner, service, campurl, reffurl) values('%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($pixel),mysql_real_escape_string($operator),mysql_real_escape_string($partner),mysql_real_escape_string($service),mysql_real_escape_string($campurl),mysql_real_escape_string($reffurl));
		
	$query = $this->databaseObj->query($sql);
	//$query = false;
	}
        if ($query) {
            return $this->databaseObj->last_insert_id();
        } else {
	    return false;
	}
    }

    public function getPixel($operator,$partner) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start get Pixel in DB"));
	
        $sql = sprintf("SELECT pixel FROM pixel_storage WHERE operator = '%s' AND partner = '%s' AND date_time > NOW() - INTERVAL 3 HOUR  AND is_used = 0 ORDER BY RAND() LIMIT 1",mysql_real_escape_string($operator),mysql_real_escape_string($partner));
	
	$query = $this->databaseObj->fetch($sql);
	
        if (count($query)>0) {
            //$this->updatePixel($query[0]['pixel']);
			$sql = sprintf("UPDATE pixel_storage set is_used = 1 where id = '%s'",mysql_real_escape_string($id));
			$this->databaseObj->query($sql);
            return $query[0]['pixel'];
        } else {
  	   return false;
	}
    }

    public function showPixel($id){
	$log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start show Pixel in DB"));

        $sql = "SELECT pixel FROM pixel_storage WHERE is_used = 0 and id = ".$id;

        $query = $this->databaseObj->fetch($sql);

        if (count($query)>0) {
            //$this->updatePixel($query[0]['pixel']);
			$sql = sprintf("UPDATE pixel_storage set is_used = 1 where id = '%s'",mysql_real_escape_string($id));
			$this->databaseObj->query($sql);
            return $query[0]['pixel'];
        } else {
           return false;
        }
    }
	
    public function updatePixel($data) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Update Pixel to DB"));

        $sql = sprintf("UPDATE pixel_storage set is_used = 1 where pixel = '%s'",mysql_real_escape_string($data));
	$query = $this->databaseObj->query($sql);
        //$query = false;
	if ($query) {
            return true;
        } else {
 	    return false;
	}
	}
	
	public function checkMO($msisdn,$msgdata) {
        $log = manager_logging::getInstance();
        $log->write(array('level' => 'debug', 'message' => "Start Check MO to DB"));

        $sql = sprintf("SELECT * FROM xmp.tbl_msgtransact WHERE msisdn = '%s' AND msgdata = '%s' AND DATE(msgtimestamp) = CURRENT_DATE",mysql_real_escape_string($msisdn),mysql_real_escape_string($msgdata));
		$query = $this->databaseObj->fetch($sql);
        if (count($query)>1) {
            return false;
        } else {
			return true;
		}
    }
}

?>

