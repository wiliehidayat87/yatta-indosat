<?php

class Mo_traffic_model extends CI_Model {

    public $db_xmp,
           $db_reports,
           $db_table    = "operator",
           $db_table2   = "rpt_mo",
           $db_table3   = "adn",           
           $db_table4   = "service";           

    function __construct() {
        parent::__construct();        
    }

    function getTodayMOTotal(){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $query  = $this->db_reports->query('SELECT COUNT(id) AS todayMOTotal FROM '.$this->db_table2.' WHERE DATE(mo_date) = CURDATE() ');        
        
        $row = $query->row();  
        return $row->todayMOTotal;        
    }
    
    function getTodayMOYesterday(){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $query  = $this->db_reports->query('SELECT COUNT(id) AS todayMOYesterday FROM '.$this->db_table2.' WHERE DATE(mo_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) ');        
        
        $row = $query->row();  
        return $row->todayMOYesterday;
    }
    
    function getTodayMOLastSevenDays(){
        $this->db_reports = $this->load->database('reports', TRUE);

        
        $query  = $this->db_reports->query('SELECT COUNT(id) AS todayMOLastSevenDays FROM '.$this->db_table2.' WHERE DATE(mo_date) >= ( CURDATE() - INTERVAL 7 DAY ) ');        
        
        $row = $query->row();  
        return $row->todayMOLastSevenDays;
    }
    
    function getTotalMOThisMonth(){
        $this->db_reports = $this->load->database('reports', TRUE);

        $query  = $this->db_reports->query('SELECT COUNT(id) AS totalMOThisMonth FROM '.$this->db_table2.' WHERE MONTH(mo_date) = MONTH(CURDATE()) ');        
        
        $row = $query->row();  
        return $row->totalMOThisMonth;        
    }
    
    function getTotalMOLastMonths(){
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $query  = $this->db_reports->query('SELECT COUNT(id) AS totalMOLastMonths FROM '.$this->db_table2.' WHERE MONTH(mo_date) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) ');        
        
        $row = $query->row();  
        return $row->totalMOLastMonths;
    }
    
    function getTotalMOLastSixMonths(){
        $this->db_reports = $this->load->database('reports', TRUE);

        $query  = $this->db_reports->query('SELECT COUNT(id) AS todayMOLastSixMonths FROM '.$this->db_table2.' WHERE mo_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ');        
        
        $row = $query->row();  
        return $row->todayMOLastSixMonths;
    }
    
    public function getMOTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search="") {
        $this->db_reports = $this->load->database('reports', TRUE);

        $sql_query = "SELECT id, mo_date, operator, adn, msisdn, service, req_type, sms ";
        $sql_query .= "FROM $this->db_table2 ";
        $sql_query .= sprintf("WHERE mo_date >= '%s' AND mo_date <= '%s' ", mysql_real_escape_string($from), mysql_real_escape_string($until) );
        
        if($adnNumber != FALSE) $sql_query      .= sprintf(" AND adn = '%s' ", mysql_real_escape_string($adnNumber));
        if($operatorName != FALSE) $sql_query   .= sprintf(" AND operator = '%s' ", mysql_real_escape_string($operatorName));
        if($reqType != FALSE) $sql_query        .= sprintf(" AND req_type = '%s' ", mysql_real_escape_string($reqType));
        if($serviceName != FALSE) $sql_query    .= sprintf(" AND service = '%s' ", mysql_real_escape_string($serviceName));
        if($msisdnNumber != FALSE) $sql_query   .= sprintf(" AND msisdn = '%s' ", mysql_real_escape_string($msisdnNumber));                        
        if($smsRequest != FALSE){
            if(substr($smsRequest,0,1) == '^'){
                $sql_query .= sprintf(" AND sms REGEXP '%s' ", mysql_real_escape_string($smsRequest));
            }else{
                $sql_query .= sprintf(" AND sms LIKE '%%%s%%' ", mysql_real_escape_string($smsRequest));
            }
        }
        
        $sql_query .= ($msisdnCheckbox == 1) ? "GROUP BY msisdn ": " ";
        $sql_query .= "ORDER BY mo_date desc ";
        $sql_limit = (!empty($limit)) ? "LIMIT $offset, $limit " : "";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_reports->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db_reports->query($sql_query . $sql_limit);
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
    
    public function getOperatorList($operator_id) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query = (!empty ($operator_id)) ? "SELECT a.id, a.name as operator_name " : "SELECT DISTINCT (a.name) as operator_name, a.id ";
        $sql_query .= "FROM " . $this->db_table . " a ";
        $sql_query .= (!empty ($operator_id)) ? "WHERE a.id = '$operator_id' " : "ORDER BY a.id ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmp->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function getAdnList() {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql_query  = "SELECT DISTINCT (a.name) as adn_name, a.id ";
        $sql_query .= "FROM " . $this->db_table3 . " a ";
        $sql_query .= "ORDER BY a.name ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_xmp->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function getTypeList() {
        $this->db_reports = $this->load->database('reports', TRUE);

        $sql_query  = "SELECT DISTINCT (a.req_type) as type ";
        $sql_query .= "FROM " . $this->db_table2 . " a ";
        $sql_query .= "WHERE a.req_type != '' ORDER BY a.req_type ";        
        
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db_reports->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }
    
    public function getServiceList($params) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $this->db_xmp->select('DISTINCT(name)');
        $this->db_xmp->like('name', $params, 'both');
        $query = $this->db_xmp->get('service');
        return $query->result();
    }
    
    public function checkServiceName($serviceName) {
        $this->db_xmp = $this->load->database('xmp', TRUE);

        $sql   = "SELECT * FROM ".$this->db_table4." WHERE name=? "; 
  	$query = $this->db_xmp->query($sql, $serviceName);
  	
        if($query->num_rows() != 0)
        {
            return true;
        }
      return false;
    }

    public function getMOSummary($startDate, $interval = 1)
    {
        if($interval > 0)
        {
            $months = array('1' => 'Jan', 'Feb', 'Mar', 'Apr',
                       'May', 'Jun', 'Jul', 'Aug', 'Sep',
                       'Oct', 'Nov', 'Dec');
            $this->db_reports = $this->load->database('reports', TRUE);
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
                (SELECT CONCAT(MONTH(mo_date),'-',YEAR(mo_date)) AS date_group, COUNT(*) AS total_mo FROM
                rpt_mo WHERE mo_date >= DATE_SUB('%s', INTERVAL %d MONTH)
                GROUP BY date_group) AS tmp_table";

            $sql = sprintf($sql, implode(',', $sqlSelect), date('Y-n-d', $startDate), $interval);

            $query = $this->db_reports->query($sql);
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
        $this->db_reports = $this->load->database('reports', TRUE);
        
        $sql_query = "SELECT id, mo_date, operator, adn, msisdn, service, req_type, sms ";
        $sql_query .= "FROM $this->db_table2 ";
        $sql_query .= sprintf("WHERE mo_date >= '%s' AND mo_date <= '%s' ", mysql_real_escape_string($from), mysql_real_escape_string($until) );
        
        if($adnNumber != FALSE) $sql_query      .= sprintf(" AND adn = '%s' ", mysql_real_escape_string($adnNumber));
        if($operatorName != FALSE) $sql_query   .= sprintf(" AND operator = '%s' ", mysql_real_escape_string($operatorName));
        if($reqType != FALSE) $sql_query        .= sprintf(" AND req_type = '%s' ", mysql_real_escape_string($reqType));
        if($serviceName != FALSE) $sql_query    .= sprintf(" AND service = '%s' ", mysql_real_escape_string($serviceName));
        if($msisdnNumber != FALSE) $sql_query   .= sprintf(" AND msisdn = '%s' ", mysql_real_escape_string($msisdnNumber));                        
        if($smsRequest != FALSE){
            if(substr($smsRequest,0,1) == '^'){
                $sql_query .= sprintf(" AND sms REGEXP '%s' ", mysql_real_escape_string($smsRequest));
            }else{
                $sql_query .= sprintf(" AND sms LIKE '%%%s%%' ", mysql_real_escape_string($smsRequest));
            }
        }
        
        $sql_query .= ($msisdnCheckbox == 1) ? "GROUP BY msisdn ": " ";
        $sql_query .= "ORDER BY mo_date desc ";
        
        $data = $this->db_reports->query($sql_query);
        return $data;
    }
}
