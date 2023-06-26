<?php
    class m_wapreglog extends CI_Model
    {
        var $limit = 1;

       // function m_wapreglog() {
         //   parent::Model();
  function __construct() {
                parent::__construct();

            $this->load->database("newwap");
        }

        function get_data_banner($type = 0, $search = NULL, $page = 0, $row = 0)
        { //-type: 0->query data; 1->query count
            $sql    = "SELECT id, token, msisdn, operator,Ip, prevPos, status,updateTime
                        WHERE  date(updateTime)>= $datestart AND date(updateTime) <= $dateend
                        FROM waptrack_log ";
           // $sql   .= ($search == NU/LL) ? "" : "WHERE (file LIKE ? OR link LIKE ?) ";
            $sql   .= "ORDER BY updateTime ";
           // $sql   .= ($type == 0) ? "LIMIT ?, ?" : "";

            if ($search == NULL) {
                $query = ($type == 0) ? $this->db->query($sql, array ($page, $row)) : $this->db->query($sql);
            }
            else {
                $var_search = mysql_real_escape_string("%".$search."%");
                $query      = ($type == 0) ? $this->db->query($sql, array ($var_search, $var_search, $page, $row)) : $this->db->query($sql, array ($var_search, $var_search));
            }

            if ($type == 0) {
                if ($query->num_rows() > 0)
                    return $query->result_array();
                else
                    return FALSE;
            }
            else
            {
                return $query->num_rows();
            }
        }

    public function ajaxGetJDDetails()
    {
        $sql    = "SELECT id, token, msisdn, operator,Ip, prevPos, status,updateTime ";
        $sql   .= " FROM waptrack_log";
        $sql   .= " WHERE status = 2 AND operator = 47";

        $sql48    = "SELECT id, token, msisdn, operator,Ip, prevPos, status,updateTime ";
        $sql48   .= " FROM waptrack_log";
        $sql48   .= " WHERE status = 2 AND operator = 48";

        $sql49    = "SELECT id, token, msisdn, operator,Ip, prevPos, status,updateTime ";
        $sql49   .= " FROM waptrack_log";
        $sql49   .= " WHERE status = 2 AND operator = 49";

		//echo $sql; exit;

        try {
            $query     = $this->db->query($sql);
            $ttsel     = $query->num_rows();
            $query48   = $this->db->query($sql48);
            $tisat     = $query48->num_rows();
            $query49   = $this->db->query($sql49);
            $txl     = $query49->num_rows();

            $result = array (
                    'ttsel' => $ttsel,
                    'tisat' => $tisat,
                    'txl' => $txl
            );
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }

    public function getWapregLog($offset = 0, $limit = 0, $dstart=0, $dend=0,$findserv=''){
        $data     = array();
        $dataSend = array();
        $start  = strtotime($dstart) ;
        $end = strtotime($dend);
        $date = $start;
        while($date <= $end){
            $printDate =  date("Y-m-d",$date);
            $sql    = "SELECT id, token, service, msisdn, operator,Ip, currPos, status,updateTime 
			FROM waptrack_log 
			WHERE  date(updateTime) = '$printDate' and service!=''";
			$sql2    = "SELECT id, token, service, msisdn, operator,Ip, currPos, status,updateTime 
			FROM waptrack_log 
			WHERE  date(updateTime) = '$printDate' and service!='' 
			GROUP by service ";
            $queryData = $this->db->query($sql);
            $queryData2 = $this->db->query($sql2);
            if($queryData->num_rows() > 0){
                $result_array  = $queryData->result_array();
                $result_array2 = $queryData2->result_array();
                foreach ($result_array2 as $re){
                    $lp 		= 0; //total landing page
                    $msi   		= 0; // ada msisdn = 2
                    $jd    		= 0; //java download where status = 3
                    $etc   		= 0; //selain jd 4
                    $ak_msisdn  = 0; // 5 => Autoclik with MSISDN
                    $ak_java    = 0; // 6 => Autoclik with Java Download
                    $tsel  		= 0;
                    $isat  		= 0;
                    $xl    		= 0;
		    		$axis  		= 0;
					//pakistan :
					$mobilink 	= 0;
					$ufone    	= 0;
					$zong     	= 0;
					$warid    	= 0;
					$telenor  	= 0;
                    $conf  		= 0; // confirmed  status = 7
                    $thank 		= 0; // thank you pag status = 10
                    $mo_send   	= 0; // 8 => MO send
                    $mo_failed 	= 0; // 9 => MO send failed
                    $java 		= 0; // 11 => Java Downloaded
                    $jad 		= 0; //12 => JAD file downloaded
                    $jar 		= 0; // 13 => JAR file downloaded
					$etc 		= 0;
                    $mo_del 	= 0; // 14 => mo delivered
					
					// New Step 
					$start_jad	= 0; // 16 => start download JAD
					$start_jar	= 0; // 17 => start download JAR
					$def_jad= 0; // 18 => got default handset
					$miss_jad	= 0; // 19 => not found JAD
					$miss_jar	= 0; // 20 => not found JAR
					$miss_jad_val= 0; // 21 => wrong JAD value
					
					
                    $service  	= $re['service'];
                    foreach ($result_array as $re2){
                        $service2  = $re2['service'];
                        if($service == $service2){
                            if($re2['status']==1) $lp++;
                            elseif($re2['status']==2){
                                $msi++;
                                if($re2['operator']==47) $tsel++;
                                elseif($re2['operator']==48) $isat++;
                                elseif($re2['operator']==49) $xl++;
                                elseif($re2['operator']==50) $axis++;
                            }
                            elseif($re2['status']==3) $jd++;
                            elseif($re2['status']==4)$etc++;
                            elseif($re2['status']==5)  $ak_msisdn++;
                            elseif($re2['status']==6) $ak_java++;
                            elseif($re2['status']==7) $conf++;
                            elseif($re2['status']==8) $mo_send++;
                            elseif($re2['status']==9) $mo_failed++;
                            elseif($re2['status']==10) $thank++;
                            elseif($re2['status']==11) $java++;
                            elseif($re2['status']==12) $jad++;
                            elseif($re2['status']==13) { $jar++; $start_jar++; }
                            elseif($re2['status']==14) $etc++;
							elseif($re2['status']==15) $mo_del++;
							// NEW STEP
							elseif($re2['status']==16) $start_jad++;
							//elseif($re2['status']==17) $start_jar++;
							elseif($re2['status']==18) $def_jad++;
							elseif($re2['status']==19) $miss_jad++;
							elseif($re2['status']==20) $miss_jar++;
							elseif($re2['status']==21) $miss_jad_val++;
                            if($re2['status']==12){
                                if($re2['operator']==47) $tsel++;
                                elseif($re2['operator']==48) $isat++;
                                elseif($re2['operator']==49) $xl++;
                                elseif($re2['operator']==50) $axis++;
                             }
                        }
                    }
					// get total landing page 
					$lp = $msi + $jd + $etc;
                    $result = array('date'=>$printDate,'service'=>$service,'lp'=>$lp,'jd'=>$jd,'msi'=>$msi,'etc'=>$etc,
					'ak_msisdn'=>$ak_msisdn,'ak_java'=>$ak_java,'tsel'=>$tsel,'isat'=>$isat,'xl'=>$xl,'axis'=>$axis,
					'conf'=>$conf,'thank'=>$thank,'mo_send'=>$mo_send,'mo_failed'=>$mo_failed,
					'java'=>$java,'jad'=>$jad,'jar'=>$jar,'mo_del'=>$mo_del,
					'start_jad'=>$start_jad,'start_jar'=>$start_jar,'def_jad'=>$def_jad,'miss_jad'=>$miss_jad,'miss_jar'=>$miss_jar,'miss_jad_val'=>$miss_jad_val);
                    if(!empty($findserv) || $findserv != ''){
                        if($findserv == $service){
                            $push = TRUE;
                            foreach ($data as $rr){
                                if ($rr['service']==$service && $rr['date']==$printDate)
                                    $push = FALSE;
                            }
                            if($push == TRUE)
                                array_push($data,  $result);
                        	}
                    	}else{
                        	$push = TRUE;
                        	foreach ($data as $rr){
	                            if ($rr['service']==$service && $rr['date']==$printDate)
                                	$push = FALSE;
                        		}
                        		if($push == TRUE)
                            		array_push($data,  $result);
                    			}
                			}
            			}
            			$date = strtotime("+1 day", $date);
        			}
      				if($offset == 0) $offset = 1;
       				for($x=$limit*($offset-1);$x<$limit*$offset;$x++){
           				if(!empty($data[$x]['date']))
                			array_push($dataSend,$data[$x]);
      				}
            		$total     = count($data);
            		$queryData = $dataSend;
            		$totalData = count($dataSend);
            		$result = array (
                		'total'  => $total,
                		'result' => array (
                    	'data'  => $queryData,
                    	'total' => $totalData
                	)
            	);
        		return $result;
    		}
		}
		?>
