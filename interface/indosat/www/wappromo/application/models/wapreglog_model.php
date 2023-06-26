<?php
class Wapreglog_model extends CI_Model {
   
    function __construct() {
		parent::__construct();

     //   $this->load->database();
    }
    public function getWapTrack($service, $begindate, $enddate, $ua = '', $sts = 12) {
        $this->db_newwap = $this->load->database('newwap', TRUE);
        
	if(strlen(trim($ua)) == 0){
		$ua = '%%';
	} 
	
//        $sql_query  = "SELECT REPLACE(SUBSTRING(SUBSTRING_INDEX(ua, ';', 3), LENGTH(SUBSTRING_INDEX(ua, ';', 3 -1)) + 1), ';', '') as ua, count( ua ) AS jml FROM waptrack_log WHERE length( ua ) >0 AND service=? AND DATE( updateTime ) >= ? AND DATE( updateTime ) <= ? AND REPLACE(SUBSTRING(SUBSTRING_INDEX(ua, ';', 3), LENGTH(SUBSTRING_INDEX(ua, ';', 3 -1)) + 1), ';', '') LIKE ? GROUP BY ua";

	$sql_query  = "SELECT ua, count( ua ) AS jml FROM waptrack_log WHERE service=? AND DATE( updateTime ) = ?  AND ua LIKE ?  AND status = ?  GROUP BY service, status, ua ";
	//$sql_query  = "SELECT ua, count( ua ) AS jml FROM waptrack_log WHERE service=? AND DATE( updateTime ) = ?  AND status = ?  GROUP BY service, status, ua ";
	//echo "SELECT ua, count( ua ) AS jml FROM waptrack_log WHERE service='$service' AND DATE( updateTime ) = '$begindate'  AND ua LIKE '%$ua%'  AND status = '$sts'  GROUP BY service, status, ua ";
        try {
            $query     = $this->db_newwap->query($sql_query, array($service, $begindate, $ua, $sts));
            $total     = $query->num_rows();
            $result = array (
                'query'  => $sql_query,
                'result'  => $query->result_array(),
                'total'  => $total
            );
        }
        catch (Exception $e) {
            $result = array ();
        }

        return $result;
    }
    
 }

