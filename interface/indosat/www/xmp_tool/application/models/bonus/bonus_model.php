<?php

class Bonus_model extends CI_Model {

    public $db,
           $db_xmp,
           $db_reports,
           $db_table    = "bonus_token",
           $db_table2   = "rpt_mo",
           $db_table3   = "adn",           
           $db_table4   = "service";           

    function __construct() {
        parent::__construct();        
    }

    function getTodayBONUSTotal(){
        $this->db = $this->load->database('default', TRUE);
        
        $query  = $this->db->query('SELECT COUNT(id) AS todayBONUSTotal FROM '.$this->db_table.' WHERE DATE(timestamp) = CURDATE() ');        
        
        $row = $query->row();  
        return $row->todayBONUSTotal;        
    }
    
    function getTodayBONUSYesterday(){
        $this->db = $this->load->database('default', TRUE);
        
        $query  = $this->db->query('SELECT COUNT(id) AS todayBONUSYesterday FROM '.$this->db_table.' WHERE DATE(timestamp) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) ');        
        
        $row = $query->row();  
        return $row->todayBONUSYesterday;
    }
    
    function getTodayBONUSLastSevenDays(){
        $this->db = $this->load->database('default', TRUE);

        
        $query  = $this->db->query('SELECT COUNT(id) AS todayBONUSLastSevenDays FROM '.$this->db_table.' WHERE DATE(timestamp) >= ( CURDATE() - INTERVAL 7 DAY ) ');        
        
        $row = $query->row();  
        return $row->todayBONUSLastSevenDays;
    }
    
    function getTotalBONUSThisMonth(){
        $this->db = $this->load->database('default', TRUE);

        $query  = $this->db->query('SELECT COUNT(id) AS totalBONUSThisMonth FROM '.$this->db_table.' WHERE MONTH(timestamp) = MONTH(CURDATE()) ');        
        
        $row = $query->row();  
        return $row->totalBONUSThisMonth;        
    }
    
    function getTotalBONUSLastMonths(){
        $this->db = $this->load->database('default', TRUE);
        
        $query  = $this->db->query('SELECT COUNT(id) AS totalBONUSLastMonths FROM '.$this->db_table.' WHERE MONTH(timestamp) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) ');        
        
        $row = $query->row();  
        return $row->totalBONUSLastMonths;
    }
    
    function getTotalBONUSLastSixMonths(){
        $this->db = $this->load->database('default', TRUE);

        $query  = $this->db->query('SELECT COUNT(id) AS todayBONUSLastSixMonths FROM '.$this->db_table.' WHERE timestamp >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ');        
        
        $row = $query->row();  
        return $row->todayBONUSLastSixMonths;
    }
    
    public function getBONUSWinner(){
    	$this->db = $this->load->database('default', TRUE);
    	
    	$sql_query = "SELECT * FROM (";
    	$sql_query .= "SELECT id, COUNT(msisdn) totalnya, time_downloaded, msisdn, content_url ";
    	$sql_query .= "FROM $this->db_table ";
    	$sql_query .= "WHERE is_downloaded = '1' ";
    	$sql_query .= "GROUP BY msisdn ";
    	$sql_query .= "ORDER BY totalnya DESC, time_downloaded desc ";
    	$sql_query .= ") tmp WHERE tmp.totalnya >=3";

    	write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
    	try {
    		$query = $this->db->query($sql_query);
    		$total = $query->num_rows();
    		$queryData = $this->db->query($sql_query . $sql_limit);
    		$totalData = $queryData->num_rows();
    	
    		$result = array(
    				'query' => $sql_query . $sql_limit,
    				'total' => $total,
    				'result'=> array(
    						'data'  => $queryData->result_array(),
    						'total' => $totalData
    				)
    		);
    		write_log("info", __METHOD__ . ", Query Success ");
    	} catch (Exception $e) {
    		write_log("info", __METHOD__ . ", Query Failed ");
    		$result = array();
    	}
    	
    	return $result;
    }
    
    public function getBONUSTrafficByMSISDN($msisdnNumber) {
        $this->db = $this->load->database('default', TRUE);
        
        $sql_query = "SELECT id, time_downloaded, msisdn, content_url ";
        $sql_query .= "FROM $this->db_table ";
        $sql_query .= "WHERE is_downloaded='1' ";
        $sql_query .= sprintf(" AND msisdn = '%s' ", mysql_real_escape_string($msisdnNumber));                        
        $sql_query .= "ORDER BY timestamp desc ";

    	write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
    	try {
    		$query = $this->db->query($sql_query);
    		$total = $query->num_rows();
    		$queryData = $this->db->query($sql_query . $sql_limit);
    		$totalData = $queryData->num_rows();
    	
    		$result = $queryData->result_array();
    		write_log("info", __METHOD__ . ", Query Success ");
    	} catch (Exception $e) {
    		write_log("info", __METHOD__ . ", Query Failed ");
    		$result = array();
    	}
    	
    	return $result;
    }
    
    public function getBONUSTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search="") {
        $this->db = $this->load->database('default', TRUE);

        $sql_query = "SELECT id, timestamp, time_downloaded, msisdn, content_url ";
        $sql_query .= "FROM $this->db_table ";
        $sql_query .= sprintf("WHERE timestamp >= '%s' AND timestamp <= '%s' ", mysql_real_escape_string($from), mysql_real_escape_string($until) );
        
        if($msisdnNumber != FALSE) $sql_query   .= sprintf(" AND msisdn = '%s' ", mysql_real_escape_string($msisdnNumber));                        
        
        $sql_query .= ($msisdnCheckbox == 1) ? "GROUP BY msisdn ": " ";
        $sql_query .= "ORDER BY timestamp desc ";
        $sql_limit = (!empty($limit)) ? "LIMIT $offset, $limit " : "";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array(
                    'query' => $sql_query . $sql_limit,
                    'total' => $total,
                    'result'=> array(
                            'data'  => $queryData->result_array(),
                            'total' => $totalData
                )
            );
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    


    public function getBONUSSummary($startDate, $interval = 1)
    {
        if($interval > 0)
        {
            $months = array('1' => 'Jan', 'Feb', 'Mar', 'Apr',
                       'May', 'Jun', 'Jul', 'Aug', 'Sep',
                       'Oct', 'Nov', 'Dec');
            $this->db_xmp = $this->load->database('xmp', TRUE);
            $sqlSelect  = '';
            $month = date('n', $startDate);
            $year  = date('Y', $startDate);
            $result = array();

            for($i = 1; $i <= $interval; $i++)
            {
                $columnDate = "$month-$year";
                $columnDates[] = $columnDate;
                $sqlSelect[] = "IFNULL(SUM(IF(date_group = '$columnDate', total_mo, 0)), 0) AS `$columnDate`";
                $month--;

                if($month == 0) {
                    $month = 12;
                    $year--;
                }
            }

            $sqlSelect = array_reverse($sqlSelect);

            $sql = "SELECT %s FROM
                (SELECT CONCAT(MONTH(msgtimestamp),'-',YEAR(msgtimestamp)) AS date_group, COUNT(*) AS total_mo FROM
                rpt_mo WHERE msgtimestamp >= DATE_SUB('%s', INTERVAL %d MONTH)
                GROUP BY date_group) AS tmp_table";

            $sql = sprintf($sql, implode(',', $sqlSelect), date('Y-n-d', $startDate), $interval);

            $query = $this->db_xmp->query($sql);
            $row = $query->row_array();

            foreach($row as $key => $value)
            {
                $buffer = explode('-', $key);
                $buffer[0] = $months[$buffer[0]];
                $new_label = implode('-', $buffer);
                $result[$new_label] = $value;
            }

            return $result;
        }
        else
        {
            return FALSE;
        }
    }
    
    public function dataSummary($from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest){
        $this->db = $this->load->database('default', TRUE);
        
        $sql_query = "SELECT id, timestamp, msisdn, content_url ";
        $sql_query .= "FROM $this->db_table ";
        $sql_query .= sprintf("WHERE timestamp >= '%s' AND timestamp <= '%s' ", mysql_real_escape_string($from), mysql_real_escape_string($until) );
        
        if($msisdnNumber != FALSE) $sql_query   .= sprintf(" AND msisdn = '%s' ", mysql_real_escape_string($msisdnNumber));                        

        $sql_query .= ($msisdnCheckbox == 1) ? "GROUP BY msisdn ": " ";
        $sql_query .= "ORDER BY timestamp desc ";
        
        $data = $this->db_xmp->query($sql_query);
        return $data;
    }
}
