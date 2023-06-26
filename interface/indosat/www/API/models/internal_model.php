<?php

class Internal_model extends CI_Model {

    public $db_xmp;

    public function __construct() {
        parent::__construct();
        include(APPPATH . 'config/database' . EXT);
        $this->db_xmp = $db['traffic']['database'];
    }

    private function logError($source, $message) {
        error_log("\r\n$message\r\n", 3, '/tmp/logs/reporting_api_sql.log');
    }

    /**
     * @throws Exception
     * @param $searchPattern
     * @param $orderField
     * @param $order
     * @param $startFrom
     * @param $limit
     * @return array
     */
    public function getShortcode($searchPattern, $orderField, $order, $startFrom, $limit) {
        $CI = $this->load->database('traffic', TRUE);
        $params = array();

        $sqlSearch = "";
        if (isset($searchPattern)) {
            $sqlSearch = " AND adn LIKE '%$searchPattern%'";
        }

        $sqlOrder = "";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = " ORDER BY $orderField $order";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } elseif (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "SELECT
					distinct(adn)
				FROM
					service
				WHERE
					1 = 1
					$sqlSearch
					$sqlOrder
				";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $CI->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $CI->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
            $data = array();
            foreach ($query->result_array() as $row) {
                $data[] = $row['adn'];
            }
            return array(
                0 => $totalRecord,
                1 => $data
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    /**
     * @throws Exception
     * @param $adn
     * @param $searchPattern
     * @param $orderField
     * @param $order
     * @param $startFrom
     * @param $limit
     * @return array
     */
    public function getOperator($adn, $searchPattern, $orderField, $order, $startFrom, $limit) {
        $this->db = $this->load->database('traffic', TRUE);

        $params = array();

        $sqlSearch = "";
        $sqlOrder = "";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = " ORDER BY $orderField $order";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "SELECT
					id operator_code,
					name operator
				FROM
					operator
				WHERE
					1 = 1
					$sqlSearch
				";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        
        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
            return array(
                0 => $totalRecord,
                1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    /**
     * @throws Exception
     * @param $adn
     * @param $searchPattern
     * @param $orderField
     * @param $order
     * @param $startFrom
     * @param $limit
     * @return array
     */
    public function getService($adn, $searchPattern, $orderField, $order, $startFrom, $limit) {
        $params = array();
        $params[] = $adn;

        $sqlSearch = "";
        if (isset($searchPattern)) {
            $sqlSearch = " AND service LIKE '%$searchPattern%'";
        }

        $sqlOrder = "";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = " ORDER BY $orderField $order";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "SELECT
					*
				FROM
					service
				";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
            return array(
                0 => $totalRecord,
                1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    /**
     * @throws Exception
     * @param $year
     * @param $month
     * @param $adn
     * @param $operatorId
     * @return array|bool
     */
    public function getOperatorReport($year, $month, $adn, $operatorId) {
        $params = array();
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $activeDays = (int) date("d") - 1;
        } else {
            $activeDays = $maxDate;
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($adn)) {
            if ($adn === 'all') {
                $adn = '';
            }
            $sqlShortcode = " AND r.adn like '%$adn%'";
        }

        $sql = "SELECT
					o.id as operatorId,
					o.name as operatorName,
					SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MO', 1, 0)) AS moTotal,
					SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT', 1, 0)) AS mtTotal,
					SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS deliveredTotal,
					SUM(ABS(r.total * r.gross)* IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS grossTotal,";

        for ($i = 1; $i <= $activeDays; $i++) {
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MO' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) AS mo$i,";
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) AS mt$i,";
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS delivered$i,";
            $sql .= "SUM(ABS(r.total * r.gross)*IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS gross$i";
            if ($i != $activeDays)
                $sql .= ',';
        }

        $sql .= " FROM
					rpt_service2 r
				LEFT JOIN
					$this->db_xmp.operator o
				ON
					r.operator = o.id
				WHERE
					1=1";

        if (is_array($operatorId)) {
            $tmp = '';
            foreach ($operatorId as $i => $s) {
                if ($i != 0)
                    $tmp .= ',';
                $tmp .= $s;
            }
            $sql .= " AND r.operator in ($tmp)";
        }
        else if (isset($operatorId)) {
            $sql .= " AND r.operator = '$operatorId'";
        }

        $sql .= " $sqlShortcode";
        $sql .= " AND r.subject <> 'MT'";
        $sql .= " AND r.sumdate BETWEEN '$year-$month-01' AND '$year-$month-$activeDays'";
        $sql .= " GROUP BY r.operator WITH ROLLUP";

        write_log('debug', "SQL Executed (GetOperatorReportForTable): $sql - " . print_r($operatorId, true));
        $query = $this->db->query($sql, $params);
        //error_log($sql, 3, "/tmp/l7.log");
        if ($query != false) {
            $newFormat = array();
            $mo = array();
            $mt = array();
            $delivered = array();
            $gross = array();

            foreach ($query->result_array() as $row) { //error_log(print_r($row,true),3, "/tmp/l7.log");
                for ($i = 1; $i <= $activeDays; $i++) {
                    if (isset($row['mo' . $i])) {
                        $mo[$i]['total'] = $row['mo' . $i];

                        if (isset($mo[$i - 1]['total']) && $mo[$i]['total'] > $mo[$i - 1]['total']) {
                            $mo[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($mo[$i - 1]['total']) && $mo[$i]['total'] < $mo[$i - 1]['total']) {
                            $mo[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $mo[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($mo);
                    }

                    if (isset($row['mt' . $i])) {
                        $mt[$i]['total'] = $row['mt' . $i];

                        if (isset($mt[$i - 1]['total']) && $mt[$i]['total'] > $mt[$i - 1]['total']) {
                            $mt[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($mt[$i - 1]['total']) && $mt[$i]['total'] < $mt[$i - 1]['total']) {
                            $mt[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $mt[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($mt);
                    }

                    if (isset($row['delivered' . $i])) {
                        $delivered[$i]['total'] = $row['delivered' . $i];

                        if (isset($delivered[$i - 1]['total']) && $delivered[$i]['total'] > $delivered[$i - 1]['total']) {
                            $delivered[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($delivered[$i - 1]['total']) && $delivered[$i]['total'] < $delivered[$i - 1]['total']) {
                            $delivered[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $delivered[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($delivered);
                    }

                    if (isset($row['gross' . $i])) {
                        $gross[$i]['total'] = $row['gross' . $i];

                        if (isset($gross[$i - 1]['total']) && $gross[$i]['total'] > $gross[$i - 1]['total']) {
                            $gross[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($gross[$i - 1]['total']) && $gross[$i]['total'] < $gross[$i - 1]['total']) {
                            $gross[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $gross[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($gross);
                    }
                }

                $newFormat[] = array(
                    'operatorId' => $row['operatorId'],
                    'operatorName' => $row['operatorName'],
                    'moTotal' => $row['moTotal'],
                    'moAverage' => round($row['moTotal'] / $activeDays),
                    'moMonthEnd' => round(($row['moTotal'] / $activeDays) * date("t")),
                    'moDaily' => $mo,
                    'mtTotal' => $row['mtTotal'],
                    'mtAverage' => round($row['mtTotal'] / $activeDays),
                    'mtMonthEnd' => round(($row['mtTotal'] / $activeDays) * date("t")),
                    'mtDaily' => $mt,
                    'deliveredTotal' => $row['deliveredTotal'],
                    'deliveredAverage' => round($row['deliveredTotal'] / $activeDays),
                    'deliveredMonthEnd' => round(($row['deliveredTotal'] / $activeDays) * date("t")),
                    'deliveredDaily' => $delivered,
                    'grossTotal' => $row['grossTotal'],
                    'grossAverage' => round($row['grossTotal'] / $activeDays),
                    'grossMonthEnd' => round(($row['grossTotal'] / $activeDays) * date("t")),
                    'grossDaily' => $gross
                );
            }

            //total record modification
            $newFormat[count($newFormat) - 1]['operatorName'] = "total";

            return array(
                0 => count($newFormat) - 1,
                1 => $newFormat
            );
        } else {
            throw new Exception(mysql_error());
            return false;
        }
    }

    public function getOperatorReportL7($year, $month, $adn, $operatorId) {
        $params = array();
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $activeDays = (int) date("d") - 1;
        } else {
            $activeDays = $maxDate;
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($adn)) {
            if ($adn === 'all') {
                $adn = '';
            }
            $sqlShortcode = " AND o.adn like '%$adn%'";
        }

//        $type = 'NEW,UNREG';
//        error_log(print_r($type,1),3,"/tmp/l7.log");
//        die();

        $sql = "SELECT * FROM (";
        $sql .= "SELECT operatorId as operatorId,operatorName as operatorName,SUM(ABS(subsTotal)) AS subscribedTotal,SUM(ABS(unsubsTotal)) AS unsubscribedTotal,( SUM(ABS(unsubsTotal)) / SUM(ABS(subsTotal)) ) * 100 AS unregrateTotal, ";

        for ($i = 1; $i <= $activeDays; $i++) {
            $sql .= "SUM(ABS(subsTotal) * IF (DAYOFMONTH(subsDate) = '$i' and active = '1', 1, 0)) AS subs$i,";
            $sql .= "SUM(ABS(unsubsTotal) * IF (DAYOFMONTH(unsubsDate) = '$i' and active = '2', 1, 0)) AS unsubs$i,";
            $sql .= "IFNULL(SUM(ABS(unsubsTotal) * IF (DAYOFMONTH(unsubsDate) = '$i' and active = '2', 1, 0)) / SUM(ABS(subsTotal) * IF (DAYOFMONTH(subsDate) = '$i' and active = '1', 1, 0)),0) AS unregrate$i";
//            $sql .= "( SUM(ABS(unsubsTotal) * IF (DAYOFMONTH(unsubsDate) = '$i' and active = '2', 1, 0)) / SUM(ABS(subsTotal) * IF (DAYOFMONTH(subsDate) = '$i' and active = '1', 1, 0))) AS unregrate$i";
            if ($i != $activeDays)
                $sql .= ',';
        }

        $sql .= " FROM (SELECT so.id as operatorId, su.operator as operatorName, count(IF(active = '1',1,NULL)) as subsTotal,count(IF(active = '2',1,NULL)) as unsubsTotal,date(su.subscribed_from) subsDate , date(su.subscribed_until) unsubsDate, su.active from $this->db_xmp.subscription su LEFT JOIN $this->db_xmp.operator so ON su.operator = so.name WHERE su.adn like '%$adn%' AND partner = 'L7' AND subscribed_from BETWEEN '$year-$month-01' AND '$year-$month-$activeDays'";

//        $sql .= " and su.active =1";

        if (is_array($operatorId)) {
            $tmp = '';
            foreach ($operatorId as $i => $s) {
                if ($i != 0)
                    $tmp .= ',';
                $tmp .= $s;
            }
            $sql .= " AND so.id in ($tmp)";
        }
        else if (isset($operatorId)) {
            $sql .= " AND so.id = '$operatorId'";
        }

        $sql .= " group by operator, active, date(subscribed_from), date(subscribed_until)) as sapi group by operatorName";
        $sql .= ") aaa inner join (";

        $sql .= "SELECT r.operator as operatorId,o.name as operatorName,SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MO', 1, 0)) AS moTotal,SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT', 1, 0)) AS mtTotal,SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND (UPPER(msgstatus) = 'OK'), 1, 0)) AS mtBillTotal,SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS deliveredTotal,SUM(ABS(r.total * r.gross)* IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND (UPPER(msgstatus) = 'OK'), 1, 0)) AS grossTotal,( SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND (UPPER(msgstatus) = 'OK'), 1, 0)) / SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT', 1, 0))) * 100 AS grossRateTotal,";

        for ($i = 1; $i <= $activeDays; $i++) {
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MO' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) AS mo$i,";
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) AS mt$i,";
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) AS mtBill$i,";
            $sql .= "SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i' AND (UPPER(msgstatus) = 'DELIVERED' OR UPPER(msgstatus) = 'RECEIVED'), 1, 0)) AS delivered$i,";
            $sql .= "SUM(ABS(r.total * r.gross)*IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND (UPPER(msgstatus) = 'OK'), 1, 0)) AS gross$i,";
            $sql .= "IFNULL(((SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND SUBSTRING(UPPER(r.subject), 13, 5) = 'WAPCG' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0)) / SUM(ABS(r.total) * IF(SUBSTRING(UPPER(r.subject), 1, 2) = 'MT' AND DAYOFMONTH(r.sumdate) = '$i', 1, 0))) * 100),0) AS grossRate$i";
            if ($i != $activeDays)
                $sql .= ',';
        }

        $sql .= " FROM rpt_service2 r LEFT JOIN $this->db_xmp.operator o ON r.operator = o.id WHERE 1=1 AND partner = 'L7'";

        if (is_array($operatorId)) {
            $tmp = '';
            foreach ($operatorId as $i => $s) {
                if ($i != 0)
                    $tmp .= ',';
                $tmp .= $s;
            }
            $sql .= " AND r.operator in ($tmp)";
        }
        else if (isset($operatorId)) {
            $sql .= " AND r.operator = '$operatorId'";
        }

//         error_log(print_r($operatorId,true),3, "/tmp/l7.log");

        $sql .= " $sqlShortcode";
        $sql .= " AND r.subject <> 'MT'";
        $sql .= " AND r.sumdate BETWEEN '$year-$month-01' AND '$year-$month-$activeDays'";
        $sql .= " GROUP BY r.operator";

        $sql .= ") bbb on aaa.operatorName = bbb.operatorName group by aaa.operatorId WITH ROLLUP";

        write_log('debug', "SQL Executed (GetOperatorReportForTable): $sql - " . print_r($operatorId, true));
        $query = $this->db->query($sql, $params);
        //error_log($sql, 3, "/tmp/l7.log");
//        error_log(print_r($query,true),3, "/tmp/l7.log");
        if ($query != false) {
            $newFormat = array();
            $mo = array();
            $mt = array();
            $delivered = array();
            $gross = array();
            $subs = array();
            $unsubs = array();
            $unregrate = array();
            $grossRate = array();
//            error_log(print_r($query,true),3, "/tmp/l7.log");
            foreach ($query->result_array() as $row) {  //error_log(print_r($row,true),3, "/tmp/l7.log");
                for ($i = 1; $i <= $activeDays; $i++) {
                    if (isset($row['mo' . $i])) {
                        $mo[$i]['total'] = $row['mo' . $i];

                        if (isset($mo[$i - 1]['total']) && $mo[$i]['total'] > $mo[$i - 1]['total']) {
                            $mo[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($mo[$i - 1]['total']) && $mo[$i]['total'] < $mo[$i - 1]['total']) {
                            $mo[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $mo[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($mo);
                    }

                    if (isset($row['mt' . $i])) {
                        $mt[$i]['total'] = $row['mt' . $i];

                        if (isset($mt[$i - 1]['total']) && $mt[$i]['total'] > $mt[$i - 1]['total']) {
                            $mt[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($mt[$i - 1]['total']) && $mt[$i]['total'] < $mt[$i - 1]['total']) {
                            $mt[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $mt[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($mt);
                    }

                    if (isset($row['mtBill' . $i])) {
                        $mtBill[$i]['total'] = $row['mtBill' . $i];

                        if (isset($mtBill[$i - 1]['total']) && $mtBill[$i]['total'] > $mtBill[$i - 1]['total']) {
                            $mtBill[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($mtBill[$i - 1]['total']) && $mtBill[$i]['total'] < $mtBill[$i - 1]['total']) {
                            $mtBill[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $mtBill[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($mtBill);
                    }

                    if (isset($row['delivered' . $i])) {
                        $delivered[$i]['total'] = $row['delivered' . $i];

                        if (isset($delivered[$i - 1]['total']) && $delivered[$i]['total'] > $delivered[$i - 1]['total']) {
                            $delivered[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($delivered[$i - 1]['total']) && $delivered[$i]['total'] < $delivered[$i - 1]['total']) {
                            $delivered[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $delivered[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($delivered);
                    }

                    if (isset($row['gross' . $i])) {
                        $gross[$i]['total'] = $row['gross' . $i];

                        if (isset($gross[$i - 1]['total']) && $gross[$i]['total'] > $gross[$i - 1]['total']) {
                            $gross[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($gross[$i - 1]['total']) && $gross[$i]['total'] < $gross[$i - 1]['total']) {
                            $gross[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $gross[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($gross);
                    }

                    if (isset($row['grossRate' . $i])) {
                        $grossRate[$i]['total'] = $row['grossRate' . $i];

                        if (isset($grossRate[$i - 1]['total']) && $grossRate[$i]['total'] > $grossRate[$i - 1]['total']) {
                            $grossRate[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($grossRate[$i - 1]['total']) && $grossRate[$i]['total'] < $grossRate[$i - 1]['total']) {
                            $grossRate[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $grossRate[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($grossRate);
                    }


                    if (isset($row['subs' . $i])) {
                        $subs[$i]['total'] = $row['subs' . $i];

                        if (isset($subs[$i - 1]['total']) && $subs[$i]['total'] > $subs[$i - 1]['total']) {
                            $subs[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($subs[$i - 1]['total']) && $subs[$i]['total'] < $subs[$i - 1]['total']) {
                            $subs[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $subs[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($subs);
                    }

                    if (isset($row['unsubs' . $i])) {
                        $unsubs[$i]['total'] = $row['unsubs' . $i]; //error_log(print_r($row['unsubs' . $i],true),3, "/tmp/l7.log");

                        if (isset($unsubs[$i - 1]['total']) && $unsubs[$i]['total'] > $unsubs[$i - 1]['total']) {
                            $unsubs[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($unsubs[$i - 1]['total']) && $unsubs[$i]['total'] < $unsubs[$i - 1]['total']) {
                            $unsubs[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $unsubs[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($unsubs);
                    }

                    if (isset($row['unregrate' . $i])) {
                        $unregrate[$i]['total'] = $row['unregrate' . $i]; //error_log(print_r($row['unsubs' . $i],true),3, "/tmp/l7.log");

                        if (isset($unregrate[$i - 1]['total']) && $unregrate[$i]['total'] > $unregrate[$i - 1]['total']) {
                            $unregrate[$i]['color'] = 'background:' . GREEN . ';';
                        } elseif (isset($unregrate[$i - 1]['total']) && $unregrate[$i]['total'] < $unregrate[$i - 1]['total']) {
                            $unregrate[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $unregrate[$i]['color'] = 'background:' . GREY . ';';
                        }
                        krsort($unregrate);
                    }
                }

                $newFormat[] = array(
                    'operatorId' => $row['operatorId'],
                    'operatorName' => $row['operatorName'],
                    'subscribedTotal' => $row['subscribedTotal'],
                    'subscribedAverage' => round($row['subscribedTotal'] / $activeDays),
                    'subscribedMonthEnd' => round(($row['subscribedTotal'] / $activeDays) * date("t")),
                    'subscribedDaily' => $subs,
                    'unsubscribedTotal' => $row['unsubscribedTotal'],
                    'unsubscribedAverage' => round($row['unsubscribedTotal'] / $activeDays),
                    'unsubscribedMonthEnd' => round(($row['unsubscribedTotal'] / $activeDays) * date("t")),
                    'unsubscribedDaily' => $unsubs,
                    'unregrateTotal' => $row['unregrateTotal'],
                    'unregrateAverage' => round($row['unregrateTotal'] / $activeDays),
                    'unregrateMonthEnd' => round(($row['unregrateTotal'] / $activeDays) * date("t")),
                    'unregrateDaily' => $unregrate,
                    'moTotal' => $row['moTotal'],
                    'moAverage' => round($row['moTotal'] / $activeDays),
                    'moMonthEnd' => round(($row['moTotal'] / $activeDays) * date("t")),
                    'moDaily' => $mo,
                    'mtTotal' => $row['mtTotal'],
                    'mtAverage' => round($row['mtTotal'] / $activeDays),
                    'mtMonthEnd' => round(($row['mtTotal'] / $activeDays) * date("t")),
                    'mtDaily' => $mt,
                    'mtBillTotal' => $row['mtBillTotal'],
                    'mtBillAverage' => round($row['mtBillTotal'] / $activeDays),
                    'mtBillMonthEnd' => round(($row['mtBillTotal'] / $activeDays) * date("t")),
                    'mtBillDaily' => $mtBill,
                    'deliveredTotal' => $row['deliveredTotal'],
                    'deliveredAverage' => round($row['deliveredTotal'] / $activeDays),
                    'deliveredMonthEnd' => round(($row['deliveredTotal'] / $activeDays) * date("t")),
                    'deliveredDaily' => $delivered,
                    'grossTotal' => $row['grossTotal'],
                    'grossAverage' => round($row['grossTotal'] / $activeDays),
                    'grossMonthEnd' => round(($row['grossTotal'] / $activeDays) * date("t")),
                    'grossDaily' => $gross,
                    'grossRateTotal' => $row['grossRateTotal'],
                    'grossRateAverage' => round($row['grossRateTotal'] / $activeDays),
                    'grossRateMonthEnd' => round(($row['grossRateTotal'] / $activeDays) * date("t")),
                    'grossRateDaily' => $grossRate
                );
            }

            //total record modification
            $newFormat[count($newFormat) - 1]['operatorName'] = "total";
//            error_log(print_r($newFormat, true), 3, "/tmp/l7.log");

            return array(
                0 => count($newFormat) - 1,
                1 => $newFormat
            );
        } else {
            throw new Exception(mysql_error());
            return false;
        }
    }

    public function getOperatorChargingReport($year, $month, $operatorId, $type, $shortCode) {
        $params = array();
//        error_log($type, 3, "/tmp/l7.log");
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $activeDays = (int) date("d") - 1;
        } else {
            $activeDays = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$activeDays";

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND operator = $operatorId";
        }

        //generate type filter
        $sqlType = "";
        if (strtoupper($type) == 'DELIVERED') {
            $sqlType = "
				AND (UPPER(r.msgstatus) = 'DELIVERED' OR UPPER(r.msgstatus) = 'RECEIVED')
				AND r.subject <> 'MT' AND SUBSTRING(UPPER(r.subject),1,2)='MT'
			";
        } else if (strtoupper($type) == 'MO') {
            $sqlType = "
				AND r.subject <> 'MO' AND SUBSTRING(UPPER(r.subject),1,2)='MO'
			";
        } else if (strtoupper($type) == 'NEW') {
            $sqlType = "
                                AND r.subject <> 'MO' AND SUBSTRING(UPPER(r.subject),1,2)='NEW'
			";
        } else if (strtoupper($type) == 'MT') {
            $sqlType = "
				AND r.subject <> 'MT' AND SUBSTRING(UPPER(r.subject),1,2)='MT'
			";
        }

        $sqlShortCode = "";
        if (isset($shortCode)) {
            if ($shortCode === 'all') {
                $shortCode = '';
            }
            $sqlShortCode = " AND r.adn LIKE '%$shortCode%'";
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(r.sumdate = '$year-$month-@num@', ABS(r.total), 0)) AS r@num@";
        $sqlDynamic = "";
        for ($i = 1; $i <= $activeDays; $i++) {
            $sqlDynamic .= str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $sql = "
			SELECT";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject as id";
        } else {
            $sql .= " r.charging_id as id, r.gross";
        }
        $sql .= ", SUM(ABS(r.total)) AS total
				$sqlDynamic
			FROM rpt_service2 r
			WHERE
				r.sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
				$sqlOperator
				$sqlType
				$sqlShortCode
			GROUP BY";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject";
        } else {
            $sql .= " r.charging_id, r.gross";
        }
        $sql .= " ORDER BY";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject";
        } else {
            $sql .= " r.charging_id";
        }

        write_log('debug', "SQL Execute: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != false) {
            $newFormat = array();
            $daily = array();
            foreach ($query->result_array() as $row) {
                //for($i = $activeDays; $i >= 1; $i--){
                for ($i = 1; $i <= $activeDays; $i++) {
                    $before = (1 == $i) ? 1 : $i - 1;

                    if (isset($row['r' . $i])) {
                        $daily[$i]['total'] = $row['r' . $i];

                        if ($daily[$before]['total'] < $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($daily[$before]['total'] > $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $daily[$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                }

                ksort($daily);
                $dailyReverse = array_reverse($daily);

                $id = $row['id'];
                if (isset($row['gross']))
                    $id .= ' (' . $row['gross'] . ')';
                $newFormat[] = array(
                    'id' => $id,
                    'total' => $row['total'],
                    'average' => round($row['total'] / $activeDays),
                    'monthEnd' => round(($row['total'] / $activeDays) * date("t")),
                    'daily' => $dailyReverse
                );
            }

            return array(
                0 => count($newFormat),
                1 => array($type => $newFormat)
            );
        } else {
            throw new Exception(mysql_error());
            return false;
        }
    }

    public function getOperatorChargingReportL7($year, $month, $operatorId, $type, $shortCode) {
        $params = array();
//        error_log($type, 3, "/tmp/l7.log");
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $activeDays = (int) date("d") - 1;
        } else {
            $activeDays = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$activeDays";

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND operator = $operatorId";
        }

        //generate type filter
        $sqlType = "";
        if (strtoupper($type) == 'DELIVERED') {
            $sqlType = "
				AND (UPPER(r.msgstatus) = 'DELIVERED' OR UPPER(r.msgstatus) = 'RECEIVED')
				AND r.subject <> 'MT' AND SUBSTRING(UPPER(r.subject),1,2)='MT'
			";
        } else if (strtoupper($type) == 'MO') {
            $sqlType = "
				AND r.subject <> 'MO' AND SUBSTRING(UPPER(r.subject),1,2)='MO'
			";
        } else if (strtoupper($type) == 'NEW') {
            $sqlType = "
                                AND r.subject <> 'MO' AND SUBSTRING(UPPER(r.subject),1,2)='NEW'
			";
        } else if (strtoupper($type) == 'MT') {
            $sqlType = "
				AND r.subject <> 'MT' AND SUBSTRING(UPPER(r.subject),1,2)='MT'
			";
        }

        $sqlShortCode = "";
        if (isset($shortCode)) {
            if ($shortCode === 'all') {
                $shortCode = '';
            }
            $sqlShortCode = " AND r.adn LIKE '%$shortCode%'";
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(r.sumdate = '$year-$month-@num@', ABS(r.total), 0)) AS r@num@";
        $sqlDynamic = "";
        for ($i = 1; $i <= $activeDays; $i++) {
            $sqlDynamic .= str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $sql = "
			SELECT";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject as id";
        } else {
            $sql .= " r.charging_id as id, r.gross";
        }
        $sql .= ", SUM(ABS(r.total)) AS total
				$sqlDynamic
			FROM rpt_service2 r
			WHERE
				r.sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
				$sqlOperator
				$sqlType
				$sqlShortCode
			GROUP BY";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject";
        } else {
            $sql .= " r.charging_id, r.gross";
        }
        $sql .= " ORDER BY";
        if (strtoupper($type) == 'MO' || strtoupper($type) == 'MT' || strtoupper($type) == 'NEW') {
            $sql .= " r.subject";
        } else {
            $sql .= " r.charging_id";
        }

        write_log('debug', "SQL Execute: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != false) {
            $newFormat = array();
            $daily = array();
            foreach ($query->result_array() as $row) {
                //for($i = $activeDays; $i >= 1; $i--){
                for ($i = 1; $i <= $activeDays; $i++) {
                    $before = (1 == $i) ? 1 : $i - 1;

                    if (isset($row['r' . $i])) {
                        $daily[$i]['total'] = $row['r' . $i];

                        if ($daily[$before]['total'] < $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($daily[$before]['total'] > $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $daily[$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                }

                ksort($daily);
                $dailyReverse = array_reverse($daily);

                $id = $row['id'];
                if (isset($row['gross']))
                    $id .= ' (' . $row['gross'] . ')';
                $newFormat[] = array(
                    'id' => $id,
                    'total' => $row['total'],
                    'average' => round($row['total'] / $activeDays),
                    'monthEnd' => round(($row['total'] / $activeDays) * date("t")),
                    'daily' => $dailyReverse
                );
            }

            return array(
                0 => count($newFormat),
                1 => array($type => $newFormat)
            );
        } else {
            throw new Exception(mysql_error());
            return false;
        }
    }

    public function getSubjectReport($shortCode, $operatorId, $year, $month, $searchPattern, $startFrom, $limit) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate) = @num@, abs(total), 0)) totalSent@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'REJECTED', ABS(total), 0), 0)) totalFailed@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'DELIVERED', ABS(total), 0), 0)) totalDelivered@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF((msgstatus != 'DELIVERED' AND msgstatus! = 'REJECTED') OR msgstatus IS NULL, ABS(total), 0), 0)) totalUnknown@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'DELIVERED', ABS(total)*gross, 0), 0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        //generate searchPattern
        $sqlSearchPattern = "";
        if (isset($searchPattern)) {
            $sqlSearchPattern = " AND subject like '%$searchPattern%'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			temp2.*,
			(averageSent*$maxDate) monthEndSent, (averageFailed*$maxDate) monthEndFailed,
			(averageDelivered*$maxDate) monthEndDelivered, (averageUnknown*$maxDate) monthEndUnknown,
			(averageRevenue*$maxDate) monthEndRevenue
		FROM
			(
			SELECT
				temp.*,
				FLOOR(totalSent/$totalDate) averageSent, FLOOR(totalFailed/$totalDate) averageFailed,
				FLOOR(totalDelivered/$totalDate) averageDelivered, FLOOR(totalUnknown/$totalDate) averageUnknown,
				FLOOR(totalRevenue/$totalDate) averageRevenue
			FROM
				(
				SELECT
					subject, p_notes info,
					SUM(ABS(total)) totalSent,
					SUM(IF(msgstatus = 'REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus = 'DELIVERED', abs(total), 0)) totalDelivered,
					SUM(IF((msgstatus != 'DELIVERED' AND msgstatus != 'REJECTED') OR msgstatus IS NULL, ABS(total), 0)) totalUnknown,
					SUM(IF(msgstatus = 'DELIVERED', abs(total)*gross, 0)) totalRevenue
					$sqlDynamic
				FROM
					rpt_service2 rpt LEFT JOIN tbl_subjects sbj ON (substring_index(substring_index(subject, ';', 4), ';', -3) = sbj.p_subject)
				WHERE
					sumdate >= '$dateRangeStart' AND sumdate < '$dateRangeEnd'
					$sqlShortcode
					$sqlOperator
					$sqlSearchPattern
				GROUP BY
					subject
				ORDER BY
					totalSent DESC
				$sqlLimit
				) temp
			) temp2
		";

        if (!empty($sqlLimit)) {
            $sqlTotal = "
				SELECT
					COUNT(*) total
				FROM
					(
					SELECT
						subject
					FROM
						rpt_service2 rpt
					WHERE
						sumdate >= '$dateRangeStart' AND sumdate < '$dateRangeEnd'
						$sqlShortcode
						$sqlOperator
						$sqlSearchPattern
					GROUP BY
						subject
					) temp
			";
            write_log('debug', "SQL Executed: $sqlTotal");
            $query = $this->db->query($sqlTotal);
            if ($query != FALSE) {
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $totalRecord = $row->total;
                } else {
                    write_log('warning', "Failed to get total record of this filter");
                }
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                $grandTotal = array(
                    'name' => 'total',
                    'info' => 'total',
                    'totalSent' => 0,
                    'totalDelivered' => 0,
                    'totalFailed' => 0,
                    'totalUnknown' => 0,
                    'totalRevenue' => 0,
                    'averageSent' => 0,
                    'averageDelivered' => 0,
                    'averageFailed' => 0,
                    'averageUnknown' => 0,
                    'averageRevenue' => 0,
                    'monthEndSent' => 0,
                    'monthEndDelivered' => 0,
                    'monthEndFailed' => 0,
                    'monthEndUnknown' => 0,
                    'monthEndRevenue' => 0
                );
                for ($i = 1; $i <= $totalDate; $i++) {
                    $grandTotal['daily'][$i] = array(
                        'sent' => 0,
                        'delivered' => 0,
                        'failed' => 0,
                        'unknown' => 0,
                        'revenue' => 0
                    );
                }
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['name'] = $subject['subject'];
                    $subjectResponse['info'] = $subject['info'];
                    $subjectResponse['totalSent'] = $subject['totalSent'];
                    $subjectResponse['totalUnknown'] = $subject['totalUnknown'];
                    $subjectResponse['totalFailed'] = $subject['totalFailed'];
                    $subjectResponse['totalDelivered'] = $subject['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $subject['totalRevenue'];
                    $subjectResponse['averageSent'] = $subject['averageSent'];
                    $subjectResponse['averageUnknown'] = $subject['averageUnknown'];
                    $subjectResponse['averageFailed'] = $subject['averageFailed'];
                    $subjectResponse['averageDelivered'] = $subject['averageDelivered'];
                    $subjectResponse['averageRevenue'] = $subject['averageRevenue'];
                    $subjectResponse['monthEndSent'] = $subject['monthEndSent'];
                    $subjectResponse['monthEndUnknown'] = $subject['monthEndUnknown'];
                    $subjectResponse['monthEndFailed'] = $subject['monthEndFailed'];
                    $subjectResponse['monthEndDelivered'] = $subject['monthEndDelivered'];
                    $subjectResponse['monthEndRevenue'] = $subject['monthEndRevenue'];
                    //grandtotal
                    $grandTotal['totalSent'] += $subject['totalSent'];
                    $grandTotal['totalDelivered'] += $subject['totalDelivered'];
                    $grandTotal['totalFailed'] += $subject['totalFailed'];
                    $grandTotal['totalUnknown'] += $subject['totalUnknown'];
                    $grandTotal['totalRevenue'] += $subject['totalRevenue'];
                    $grandTotal['averageSent'] += $subject['averageSent'];
                    $grandTotal['averageDelivered'] += $subject['averageDelivered'];
                    $grandTotal['averageFailed'] += $subject['averageFailed'];
                    $grandTotal['averageUnknown'] += $subject['averageUnknown'];
                    $grandTotal['averageRevenue'] += $subject['averageRevenue'];
                    $grandTotal['monthEndSent'] += $subject['monthEndSent'];
                    $grandTotal['monthEndDelivered']+= $subject['monthEndDelivered'];
                    $grandTotal['monthEndFailed'] += $subject['monthEndFailed'];
                    $grandTotal['monthEndUnknown'] += $subject['monthEndUnknown'];
                    $grandTotal['monthEndRevenue'] += $subject['monthEndRevenue'];

                    $subjectResponse['daily'] = array(
                        1 => array(
                            'sent' => 0,
                            'unknown' => 0,
                            'failed' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'color' => ''
                        )
                    );

                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = array();
                        $daily['sent'] = $subject["totalSent$i"];
                        $daily['unknown'] = $subject["totalUnknown$i"];
                        $daily['failed'] = $subject["totalFailed$i"];
                        $daily['delivered'] = $subject["totalDelivered$i"];
                        $daily['revenue'] = $subject["totalRevenue$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                            $daily['color'] = 'background:' . GREEN . ';';
                        } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                            $daily['color'] = 'background:' . RED . ';';
                        } else {
                            $daily['color'] = 'background:' . GREY . ';';
                        }

                        $subjectResponse['daily']["$i"] = $daily;
                        // grandtotal
                        $grandTotal['daily'][$i]['sent'] += $subject["totalSent$i"];
                        $grandTotal['daily'][$i]['delivered'] += $subject["totalDelivered$i"];
                        $grandTotal['daily'][$i]['failed'] += $subject["totalFailed$i"];
                        $grandTotal['daily'][$i]['unknown'] += $subject["totalUnknown$i"];
                        $grandTotal['daily'][$i]['revenue'] += $subject["totalRevenue$i"];
                    }
                    $response[] = $subjectResponse;
                }
                $response[] = $grandTotal;

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getSubjectOperatorReport($subject, $shortCode, $operatorId, $year, $month) {

        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate) = @num@, ABS(total), 0)) totalSent@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'REJECTED', ABS(total), 0), 0)) totalFailed@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'DELIVERED', ABS(total), 0), 0)) totalDelivered@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF((msgstatus! = 'DELIVERED' AND msgstatus != 'REJECTED') OR msgstatus IS NULL, abs(total), 0), 0)) totalUnknown@num@,
					SUM(IF(DAYOFMONTH(sumdate) = @num@, IF(msgstatus = 'DELIVERED', ABS(total)*gross, 0), 0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();
        $params[] = $subject;

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sql = "
		SELECT
			temp2.*,
			(averageSent*$maxDate) monthEndSent, (averageFailed*$maxDate) monthEndFailed,
			(averageDelivered*$maxDate) monthEndDelivered, (averageUnknown*$maxDate) monthEndUnknown,
			(averageRevenue*$maxDate) monthEndRevenue
		FROM
			(
			SELECT
				temp.*,
				FLOOR(totalSent/$totalDate) averageSent, FLOOR(totalFailed/$totalDate) averageFailed,
				FLOOR(totalDelivered/$totalDate) averageDelivered, FLOOR(totalUnknown/$totalDate) averageUnknown,
				FLOOR(totalRevenue/$totalDate) averageRevenue
			FROM
				(
				SELECT
					opr.operator,
					SUM(abs(total)) totalSent,
					SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
					SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
					SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
					$sqlDynamic
				FROM
					rpt_service2 rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
				WHERE
					sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
					AND subject=?
					$sqlShortcode
					$sqlOperator
				GROUP BY
					rpt.operator
				ORDER BY
					totalSent
				) temp
			) temp2
		";

        write_log('info', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['operator'] = $subject['operator'];
                    $subjectResponse['totalSent'] = $subject['totalSent'];
                    $subjectResponse['totalUnknown'] = $subject['totalUnknown'];
                    $subjectResponse['totalFailed'] = $subject['totalFailed'];
                    $subjectResponse['totalDelivered'] = $subject['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $subject['totalRevenue'];
                    $subjectResponse['averageSent'] = $subject['averageSent'];
                    $subjectResponse['averageUnknown'] = $subject['averageUnknown'];
                    $subjectResponse['averageFailed'] = $subject['averageFailed'];
                    $subjectResponse['averageDelivered'] = $subject['averageDelivered'];
                    $subjectResponse['averageRevenue'] = $subject['averageRevenue'];
                    $subjectResponse['monthEndSent'] = $subject['monthEndSent'];
                    $subjectResponse['monthEndUnknown'] = $subject['monthEndUnknown'];
                    $subjectResponse['monthEndFailed'] = $subject['monthEndFailed'];
                    $subjectResponse['monthEndDelivered'] = $subject['monthEndDelivered'];
                    $subjectResponse['monthEndRevenue'] = $subject['monthEndRevenue'];

                    $subjectResponse['daily'] = array(
                        1 => array(
                            'sent' => 0,
                            'unknown' => 0,
                            'failed' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'color' => ''
                        )
                    );

                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = array();
                        $daily['sent'] = $subject["totalSent$i"];
                        $daily['unknown'] = $subject["totalUnknown$i"];
                        $daily['failed'] = $subject["totalFailed$i"];
                        $daily['delivered'] = $subject["totalDelivered$i"];
                        $daily['revenue'] = $subject["totalRevenue$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                            $daily['color'] = 'background:' . GREEN . ';';
                        } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                            $daily['color'] = 'background:' . RED . ';';
                        } else {
                            $daily['color'] = 'background:' . GREY . ';';
                        }

                        $subjectResponse['daily']["$i"] = $daily;
                    }
                    $response[] = $subjectResponse;
                }
                return array(
                    0 => count($response),
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getServiceReport($shortCode, $year, $month, $operatorId, $searchPattern, $orderField
    , $order, $startFrom, $limit) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,abs(total),0)) totalSent@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='REJECTED', abs(total), 0),0)) totalFailed@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total), 0),0)) totalDelivered@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0),0)) totalUnknown@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        //generate searchPattern
        $sqlSearchPattern = "";
        if (isset($searchPattern)) {
            $sqlSearchPattern = " AND subject like '%$searchPattern%'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			temp2.*,
			(averageSent*$maxDate) monthEndSent, (averageFailed*$maxDate) monthEndFailed,
			(averageDelivered*$maxDate) monthEndDelivered, (averageUnknown*$maxDate) monthEndUnknown,
			(averageRevenue*$maxDate) monthEndRevenue
		FROM
			(
			SELECT
				temp.*,
				FLOOR(totalSent/$totalDate) averageSent, FLOOR(totalFailed/$totalDate) averageFailed,
				FLOOR(totalDelivered/$totalDate) averageDelivered, FLOOR(totalUnknown/$totalDate) averageUnknown,
				FLOOR(totalRevenue/$totalDate) averageRevenue
			FROM
				(
				SELECT
					service,
					SUM(abs(total)) totalSent,
					SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
					SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
					SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
					$sqlDynamic
				FROM
					rpt_service2 rpt
				WHERE
					sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
					AND subject like 'MT%'
					$sqlShortcode
					$sqlOperator
					$sqlSearchPattern
				GROUP BY
					service
				ORDER BY totalSent DESC
				$sqlLimit
				) temp
			) temp2
		";

        if (!empty($sqlLimit)) {
            $sqlTotal = "
				SELECT
					count(*) total
				FROM
					(
					SELECT
						subject
					FROM
						rpt_service2 rpt
					WHERE
						sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
						AND subject like 'MT%'
						$sqlShortcode
						$sqlOperator
						$sqlSearchPattern
					GROUP BY
						service
					) temp
			";
            write_log('debug', "SQL Executed: $sqlTotal");
            $query = $this->db->query($sqlTotal);
            if ($query != FALSE) {
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $totalRecord = $row->total;
                } else {
                    write_log('warning', "Failed to get total record of this filter");
                }
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                $grandTotal = array(
                    'service' => 'total',
                    'totalSent' => 0,
                    'totalDelivered' => 0,
                    'totalFailed' => 0,
                    'totalUnknown' => 0,
                    'totalRevenue' => 0,
                    'averageSent' => 0,
                    'averageDelivered' => 0,
                    'averageFailed' => 0,
                    'averageUnknown' => 0,
                    'averageRevenue' => 0,
                    'monthEndSent' => 0,
                    'monthEndDelivered' => 0,
                    'monthEndFailed' => 0,
                    'monthEndUnknown' => 0,
                    'monthEndRevenue' => 0
                );
                for ($i = 1; $i <= $totalDate; $i++) {
                    $grandTotal['daily'][$i] = array(
                        'sent' => 0,
                        'delivered' => 0,
                        'failed' => 0,
                        'unknown' => 0,
                        'revenue' => 0
                    );
                }
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['service'] = $subject['service'];
                    $subjectResponse['totalSent'] = $subject['totalSent'];
                    $subjectResponse['totalUnknown'] = $subject['totalUnknown'];
                    $subjectResponse['totalFailed'] = $subject['totalFailed'];
                    $subjectResponse['totalDelivered'] = $subject['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $subject['totalRevenue'];
                    $subjectResponse['averageSent'] = $subject['averageSent'];
                    $subjectResponse['averageUnknown'] = $subject['averageUnknown'];
                    $subjectResponse['averageFailed'] = $subject['averageFailed'];
                    $subjectResponse['averageDelivered'] = $subject['averageDelivered'];
                    $subjectResponse['averageRevenue'] = $subject['averageRevenue'];
                    $subjectResponse['monthEndSent'] = $subject['monthEndSent'];
                    $subjectResponse['monthEndUnknown'] = $subject['monthEndUnknown'];
                    $subjectResponse['monthEndFailed'] = $subject['monthEndFailed'];
                    $subjectResponse['monthEndDelivered'] = $subject['monthEndDelivered'];
                    $subjectResponse['monthEndRevenue'] = $subject['monthEndRevenue'];
                    //grandtotal
                    $grandTotal['totalSent'] += $subject['totalSent'];
                    $grandTotal['totalDelivered'] += $subject['totalDelivered'];
                    $grandTotal['totalFailed'] += $subject['totalFailed'];
                    $grandTotal['totalUnknown'] += $subject['totalUnknown'];
                    $grandTotal['totalRevenue'] += $subject['totalRevenue'];
                    $grandTotal['averageSent'] += $subject['averageSent'];
                    $grandTotal['averageDelivered'] += $subject['averageDelivered'];
                    $grandTotal['averageFailed'] += $subject['averageFailed'];
                    $grandTotal['averageUnknown'] += $subject['averageUnknown'];
                    $grandTotal['averageRevenue'] += $subject['averageRevenue'];
                    $grandTotal['monthEndSent'] += $subject['monthEndSent'];
                    $grandTotal['monthEndDelivered']+= $subject['monthEndDelivered'];
                    $grandTotal['monthEndFailed'] += $subject['monthEndFailed'];
                    $grandTotal['monthEndUnknown'] += $subject['monthEndUnknown'];
                    $grandTotal['monthEndRevenue'] += $subject['monthEndRevenue'];

                    $subjectResponse['daily'] = array(
                        1 => array(
                            'sent' => 0,
                            'unknown' => 0,
                            'failed' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'color' => ''
                        )
                    );

                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = array();
                        $daily['sent'] = $subject["totalSent$i"];
                        $daily['unknown'] = $subject["totalUnknown$i"];
                        $daily['failed'] = $subject["totalFailed$i"];
                        $daily['delivered'] = $subject["totalDelivered$i"];
                        $daily['revenue'] = $subject["totalRevenue$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                            $daily['color'] = 'background:' . GREEN . ';';
                        } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                            $daily['color'] = 'background:' . RED . ';';
                        } else {
                            $daily['color'] = 'background:' . GREY . ';';
                        }

                        $subjectResponse['daily'][$i] = $daily;
                        // grandtotal
                        $grandTotal['daily'][$i]['sent'] += $subject["totalSent$i"];
                        $grandTotal['daily'][$i]['delivered'] += $subject["totalDelivered$i"];
                        $grandTotal['daily'][$i]['failed'] += $subject["totalFailed$i"];
                        $grandTotal['daily'][$i]['unknown'] += $subject["totalUnknown$i"];
                        $grandTotal['daily'][$i]['revenue'] += $subject["totalRevenue$i"];
                    }
                    $response[] = $subjectResponse;
                }
                $response[] = $grandTotal;

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getServiceOperatorReport($shortCode, $year, $month, $service, $operatorId, $searchPattern, $orderField
    , $order, $startFrom, $limit) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,abs(total),0)) totalSent@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='REJECTED', abs(total), 0),0)) totalFailed@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total), 0),0)) totalDelivered@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0),0)) totalUnknown@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate searchPattern
        $sqlSearchPattern = "";
        if (isset($searchPattern)) {
            $sqlSearchPattern = " AND rpt.subject like '%$searchPattern%'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			temp2.*,
			(averageSent*$maxDate) monthEndSent, (averageFailed*$maxDate) monthEndFailed,
			(averageDelivered*$maxDate) monthEndDelivered, (averageUnknown*$maxDate) monthEndUnknown,
			(averageRevenue*$maxDate) monthEndRevenue
		FROM
			(
			SELECT
				temp.*,
				FLOOR(totalSent/$totalDate) averageSent, FLOOR(totalFailed/$totalDate) averageFailed,
				FLOOR(totalDelivered/$totalDate) averageDelivered, FLOOR(totalUnknown/$totalDate) averageUnknown,
				FLOOR(totalRevenue/$totalDate) averageRevenue
			FROM
				(
				SELECT
					opr.id operatorId, opr.name operator,
					SUM(abs(total)) totalSent,
					SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
					SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
					SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
					$sqlDynamic
				FROM
					rpt_service2 rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
				WHERE
					sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
					AND subject like 'MT%'
					$sqlShortcode
					$sqlOperator
					$sqlSearchPattern
					$sqlService
				GROUP BY
					rpt.operator
				ORDER BY
					totalSent
				$sqlLimit
				) temp
			) temp2
		";

        if (!empty($sqlLimit)) {
            $sqlTotal = "
				SELECT
					count(*) total
				FROM
					(
					SELECT
						subject
					FROM
						rpt_service2 rpt
					WHERE
						sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
						AND subject like 'MT%'
						$sqlShortcode
						$sqlOperator
						$sqlSearchPattern
						$sqlService
					GROUP BY
						operator
					) temp
			";
            write_log('debug', "SQL Executed: $sqlTotal");
            $query = $this->db->query($sqlTotal);
            if ($query != FALSE) {
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $totalRecord = $row->total;
                } else {
                    write_log('warning', "Failed to get total record of this filter");
                }
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['service'] = $service;
                    $subjectResponse['operator'] = $subject['operator'];
                    $subjectResponse['operatorId'] = $subject['operatorId'];
                    $subjectResponse['totalSent'] = $subject['totalSent'];
                    $subjectResponse['totalUnknown'] = $subject['totalUnknown'];
                    $subjectResponse['totalFailed'] = $subject['totalFailed'];
                    $subjectResponse['totalDelivered'] = $subject['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $subject['totalRevenue'];
                    $subjectResponse['averageSent'] = $subject['averageSent'];
                    $subjectResponse['averageUnknown'] = $subject['averageUnknown'];
                    $subjectResponse['averageFailed'] = $subject['averageFailed'];
                    $subjectResponse['averageDelivered'] = $subject['averageDelivered'];
                    $subjectResponse['averageRevenue'] = $subject['averageRevenue'];
                    $subjectResponse['monthEndSent'] = $subject['monthEndSent'];
                    $subjectResponse['monthEndUnknown'] = $subject['monthEndUnknown'];
                    $subjectResponse['monthEndFailed'] = $subject['monthEndFailed'];
                    $subjectResponse['monthEndDelivered'] = $subject['monthEndDelivered'];
                    $subjectResponse['monthEndRevenue'] = $subject['monthEndRevenue'];

                    $subjectResponse['daily'] = array(
                        1 => array(
                            'sent' => 0,
                            'unknown' => 0,
                            'failed' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'color' => ''
                        )
                    );

                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = array();
                        $daily['sent'] = $subject["totalSent$i"];
                        $daily['unknown'] = $subject["totalUnknown$i"];
                        $daily['failed'] = $subject["totalFailed$i"];
                        $daily['delivered'] = $subject["totalDelivered$i"];
                        $daily['revenue'] = $subject["totalRevenue$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                            $daily['color'] = 'background:' . GREEN . ';';
                        } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                            $daily['color'] = 'background:' . RED . ';';
                        } else {
                            $daily['color'] = 'background:' . GREY . ';';
                        }

                        $subjectResponse['daily'][$i] = $daily;
                    }
                    $response[] = $subjectResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response);
                }

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getServiceOperatorSubjectReport($shortCode, $year, $month, $service, $operatorId, $searchPattern, $orderField
    , $order, $startFrom, $limit) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,abs(total),0)) totalSent@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='REJECTED', abs(total), 0),0)) totalFailed@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total), 0),0)) totalDelivered@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0),0)) totalUnknown@num@,
					SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator = '$operatorId'";
        }

        //generate searchPattern
        $sqlSearchPattern = "";
        if (isset($searchPattern)) {
            $sqlSearchPattern = " AND rpt.subject like '%$searchPattern%'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			temp2.*,
			(averageSent*$maxDate) monthEndSent, (averageFailed*$maxDate) monthEndFailed,
			(averageDelivered*$maxDate) monthEndDelivered, (averageUnknown*$maxDate) monthEndUnknown,
			(averageRevenue*$maxDate) monthEndRevenue
		FROM
			(
			SELECT
				temp.*,
				FLOOR(totalSent/$totalDate) averageSent, FLOOR(totalFailed/$totalDate) averageFailed,
				FLOOR(totalDelivered/$totalDate) averageDelivered, FLOOR(totalUnknown/$totalDate) averageUnknown,
				FLOOR(totalRevenue/$totalDate) averageRevenue
			FROM
				(
				SELECT
					subject,
					SUM(abs(total)) totalSent,
					SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
					SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
					SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
					$sqlDynamic
				FROM
					rpt_service2 rpt
				WHERE
					sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
					AND subject like 'MT%'
					$sqlShortcode
					$sqlOperator
					$sqlSearchPattern
					$sqlService
				GROUP BY
					subject
				ORDER BY
					totalSent
				$sqlLimit
				) temp
			) temp2
		";

        if (!empty($sqlLimit)) {
            $sqlTotal = "
				SELECT
					count(*) total
				FROM
					(
					SELECT
						subject
					FROM
						rpt_service2 rpt
					WHERE
						sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
						AND subject like 'MT%'
						$sqlShortcode
						$sqlOperator
						$sqlSearchPattern
						$sqlService
					GROUP BY
						subject
					) temp
			";
            write_log('debug', "SQL Executed: $sqlTotal");
            $query = $this->db->query($sqlTotal);
            if ($query != FALSE) {
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $totalRecord = $row->total;
                } else {
                    write_log('warning', "Failed to get total record of this filter");
                }
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['subject'] = $subject['subject'];
                    $subjectResponse['totalSent'] = $subject['totalSent'];
                    $subjectResponse['totalUnknown'] = $subject['totalUnknown'];
                    $subjectResponse['totalFailed'] = $subject['totalFailed'];
                    $subjectResponse['totalDelivered'] = $subject['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $subject['totalRevenue'];
                    $subjectResponse['averageSent'] = $subject['averageSent'];
                    $subjectResponse['averageUnknown'] = $subject['averageUnknown'];
                    $subjectResponse['averageFailed'] = $subject['averageFailed'];
                    $subjectResponse['averageDelivered'] = $subject['averageDelivered'];
                    $subjectResponse['averageRevenue'] = $subject['averageRevenue'];
                    $subjectResponse['monthEndSent'] = $subject['monthEndSent'];
                    $subjectResponse['monthEndUnknown'] = $subject['monthEndUnknown'];
                    $subjectResponse['monthEndFailed'] = $subject['monthEndFailed'];
                    $subjectResponse['monthEndDelivered'] = $subject['monthEndDelivered'];
                    $subjectResponse['monthEndRevenue'] = $subject['monthEndRevenue'];

                    $subjectResponse['daily'] = array(
                        1 => array(
                            'sent' => 0,
                            'unknown' => 0,
                            'failed' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'color' => ''
                        )
                    );

                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = array();
                        $daily['sent'] = $subject["totalSent$i"];
                        $daily['unknown'] = $subject["totalUnknown$i"];
                        $daily['failed'] = $subject["totalFailed$i"];
                        $daily['delivered'] = $subject["totalDelivered$i"];
                        $daily['revenue'] = $subject["totalRevenue$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                            $daily['color'] = 'background:' . GREEN . ';';
                        } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                            $daily['color'] = 'background:' . RED . ';';
                        } else {
                            $daily['color'] = 'background:' . GREY . ';';
                        }

                        $subjectResponse['daily']["$i"] = $daily;
                    }
                    $response[] = $subjectResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response);
                }

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getClosereasonReport($shortCode, $dateRangeStart, $dateRangeEnd, $service, $operatorId, $sorting, $limit) {

        $timestampStart = strtotime($dateRangeStart);
        $timestampEnd = strtotime($dateRangeEnd);

        $dates = array();
        $i = -1;
        while ($timestampEnd >= $timestampStart) {
            $i++;
            $dates[$i]['month'] = date('m', $timestampEnd);
            $dates[$i]['date'] = date('d', $timestampEnd);
            $timestampEnd-=86400;
        }
        $totalDate = count($dates);

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ AND MONTH(sumdate)=@month@,abs(total),0)) total@month@_@date@";

        $sqlDynamic = "";
        for ($i = 0; $i < $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $dates[$i]['date'], str_replace("@month@", $dates[$i]['month'], $sqlDynamicTemplate));
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn = '$shortCode'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service in ($service)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlSorting = "";
        if (isset($sorting)) {
            if ($sorting == "total") {
                $sqlSorting = "ORDER BY total DESC";
            } else if ($sorting == "yesterday") {
                $sqlSorting = "ORDER BY total" . $dates[0]['month'] . "_" . $dates[0]['date'] . " DESC";
            }
        }

        //generate limit
        $sqlLimit = "";
        if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			rpt.operator operatorId, opr.operator operator, rpt.closereason, resp.desc description,
			SUM(abs(total)) total
			$sqlDynamic
		FROM
			rpt_creason rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
			LEFT JOIN tbl_response resp ON (opr.name=resp.opr AND rpt.closereason=resp.code)
		WHERE
			rpt.sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
			$sqlShortcode
			$sqlService
			$sqlOperator
		GROUP BY
			rpt.operator,
			rpt.closereason
		$sqlSorting
		$sqlLimit
		";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['operatorId'] = $subject['operatorId'];
                    $subjectResponse['operator'] = $subject['operator'];
                    $subjectResponse['closereason'] = $subject['closereason'];
                    $subjectResponse['description'] = $subject['description'];
                    $subjectResponse['total'] = $subject['total'];
                    $daily = array();
                    for ($i = 0; $i < $totalDate; $i++) {
                        $daily[$dates[$i]['month'] . "-" . $dates[$i]['date']]['total'] = $subject["total" . $dates[$i]['month'] . "_" . $dates[$i]['date']];
                    }

                    $dailyReverse = array_reverse($daily);

                    $before = null;
                    foreach ($dailyReverse as $key => $row) {
                        if ($before != null && $row['total'] > $before) {
                            $daily[$key]['color'] = 'background:' . GREEN . ';';
                        } elseif ($before != null && $row['total'] < $before) {
                            $daily[$key]['color'] = 'background:' . RED . ';';
                        } else {
                            $daily[$key]['color'] = 'background:' . GREY . ';';
                        }

                        $before = $row['total'];
                    }
                    $subjectResponse['daily'] = $daily;
                    $response[] = $subjectResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response);
                }

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getClosereasonServiceReport($shortCode, $dateRangeStart, $dateRangeEnd, $closereason, $service, $operatorId, $sorting, $limit) {
        $timestampStart = strtotime($dateRangeStart);
        $timestampEnd = strtotime($dateRangeEnd);

        $dates = array();
        $i = -1;
        while ($timestampEnd >= $timestampStart) {
            $i++;
            $dates[$i]['month'] = date('m', $timestampEnd);
            $dates[$i]['date'] = date('d', $timestampEnd);
            $timestampEnd-=86400;
        }
        $totalDate = count($dates);

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ AND MONTH(sumdate)=@month@,abs(total),0)) total@month@_@date@";

        $sqlDynamic = "";
        for ($i = 0; $i < $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $dates[$i]['date'], str_replace("@month@", $dates[$i]['month'], $sqlDynamicTemplate));
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn = '$shortCode'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service in ($service)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator = '$operatorId'";
        }

        //generate closereason
        $sqlClosereason = "";
        if (isset($closereason)) {
            $sqlClosereason = " AND rpt.closereason = '$closereason'";
        }

        $sqlSorting = "";
        if (isset($sorting)) {
            if ($sorting == "total") {
                $sqlSorting = "ORDER BY total DESC";
            } else if ($sorting == "yesterday") {
                $sqlSorting = "ORDER BY total" . $dates[0]['month'] . "_" . $dates[0]['date'] . " DESC";
            }
        }

        //generate limit
        $sqlLimit = "";
        if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }


        $sql = "
		SELECT
			rpt.service,
			SUM(abs(total)) total
			$sqlDynamic
		FROM
			rpt_creason rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
			LEFT JOIN tbl_response resp ON (opr.name=resp.opr AND rpt.closereason=resp.code)
		WHERE
			rpt.sumdate>='$dateRangeStart' AND rpt.sumdate<'$dateRangeEnd'
			$sqlShortcode
			$sqlService
			$sqlOperator
			$sqlClosereason
		GROUP BY
			rpt.service
		$sqlSorting
		$sqlLimit
		";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $subject) {
                    $subjectResponse = array();
                    $subjectResponse['service'] = $subject['service'];
                    $subjectResponse['total'] = $subject['total'];
                    $daily = array();
                    for ($i = 0; $i < $totalDate; $i++) {
                        $daily[$dates[$i]['month'] . "-" . $dates[$i]['date']]['total'] = $subject["total" . $dates[$i]['month'] . "_" . $dates[$i]['date']];
                    }

                    $dailyReverse = array_reverse($daily);

                    $before = null;
                    foreach ($dailyReverse as $key => $row) {
                        if ($before != null && $row['total'] > $before) {
                            $daily[$key]['color'] = 'background:' . GREEN . ';';
                        } elseif ($before != null && $row['total'] < $before) {
                            $daily[$key]['color'] = 'background:' . RED . ';';
                        } else {
                            $daily[$key]['color'] = 'background:' . GREY . ';';
                        }

                        $before = $row['total'];
                    }
                    $subjectResponse['daily'] = $daily;
                    $response[] = $subjectResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response);
                }

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getMedia($searchPattern, $orderField, $order, $startFrom, $limit) {
        $params = array();

        $sqlSearch = "";
        if (isset($searchPattern)) {
            $sqlSearch = " AND fullname LIKE '%$searchPattern%'";
        }

        $sqlOrder = "";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = " ORDER BY $orderField $order";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "
		SELECT
			id,fullname as name
		FROM
			tbl_partners
		WHERE
			1 = 1
			$sqlSearch
			$sqlOrder
		";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
//			$data = array();
//			foreach ($query->result_array() as $row) {
//				$data[] = $row['shortCode'];
//			}
            return array(
                0 => $totalRecord,
                1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getSubject($searchPattern, $orderField, $order, $startFrom, $limit) {
        $params = array();

        $sqlSearch = "";
        if (isset($searchPattern)) {
            $sqlSearch = "";
        }

        $sqlOrder = "";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = "";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "
		SELECT
			substring_index(substring_index(subject,';',-3),';',1) as subjectX
		FROM
			rpt_service2
		WHERE
			1 = 1
			$sqlSearch
			$sqlOrder
		GROUP BY
			subjectX
		";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
            $data = array();
            foreach ($query->result_array() as $row) {
                $data[] = $row['subjectX'];
            }
            return array(
                0 => $totalRecord,
                1 => $data
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getSubscriberReport($shortCode, $year, $month, $operatorId
    , $service, $startFrom, $limit) {

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(type='REG' AND DAYOFMONTH(sumdate)=@date@, abs(total), 0)) totalReg@date@, SUM(IF(type='UNREG' AND DAYOFMONTH(sumdate)=@date@, abs(total), 0)) totalUnreg@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn = '$shortCode'";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        if (!empty($sqlLimit)) {
            $sql = "
				SELECT
					service
				FROM
					rpt_service2 rpt
				WHERE
					subject like 'MT%'
					AND substring_index(substring_index(subject,';',-3),';',1) in ('REG', 'UNREG')
					AND sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
					$sqlShortcode
					$sqlOperator
					$sqlService
				GROUP BY
					service
			";
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }


        $sql = "
		SELECT
			service
			, SUM(IF(type='REG', abs(total), 0)) totalReg, SUM(IF(type='UNREG', abs(total), 0)) totalUnreg
			$sqlDynamic
		FROM
			(
			SELECT
				service, sumdate, substring_index(substring_index(subject,';',-3),';',1) as type, total
			FROM
				rpt_service2 rpt
			WHERE
				subject like 'MT%'
				AND sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
				$sqlShortcode
				$sqlOperator
				$sqlService
			) temp
		WHERE
			(type='REG' OR type='UNREG')
		GROUP BY
			service WITH ROLLUP
		$sqlLimit
		";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $record) {
                    $recordResponse = array();
                    $recordResponse['service'] = $record['service'];

                    $reg = array();
                    $reg['name'] = 'reg';
                    $reg['total'] = $record['totalReg'];
                    $reg['daily'] = array();
                    //for ($i=$totalDate;$i>0;$i--) {
                    for ($i = 1; $i <= $totalDate; $i++) {
                        //$before = ($totalDate == $i) ? $totalDate : $i + 1;
                        $before = (1 == $i) ? 1 : $i - 1;

                        if (isset($record['totalReg' . $i])) {
                            $reg['daily'][$i]['total'] = $record['totalReg' . $i];
                        } else {
                            $reg['daily'][$i]['total'] = 0;
                        }

                        if ($reg['daily'][$before]['total'] < $reg['daily'][$i]['total']) {
                            $reg['daily'][$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($reg['daily'][$before]['total'] > $reg['daily'][$i]['total']) {
                            $reg['daily'][$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $reg['daily'][$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                    $reg['daily'] = array_reverse($reg['daily'], true);
                    $recordResponse['subject'][] = $reg;

                    $unreg = array();
                    $unreg['name'] = 'unreg';
                    $unreg['total'] = $record['totalUnreg'];
                    $unreg['daily'] = array();
                    //for ($i=$totalDate;$i>0;$i--) {
                    for ($i = 1; $i <= $totalDate; $i++) {
                        //$before = ($totalDate == $i) ? $totalDate : $i + 1;
                        $before = (1 == $i) ? 1 : $i - 1;

                        if (isset($record['totalUnreg' . $i])) {
                            $unreg['daily'][$i]['total'] = $record['totalUnreg' . $i];
                        } else {
                            $unreg['daily'][$i]['total'] = 0;
                        }

                        if ($unreg['daily'][$before]['total'] < $unreg['daily'][$i]['total']) {
                            $unreg['daily'][$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($unreg['daily'][$before]['total'] > $unreg['daily'][$i]['total']) {
                            $unreg['daily'][$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $unreg['daily'][$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                    $unreg['daily'] = array_reverse($unreg['daily'], true);
                    $recordResponse['subject'][] = $unreg;

                    $subtotal = array();
                    $subtotal['name'] = 'subtotal';
                    $subtotal['total'] = $reg['total'] - $unreg['total'];
                    $subtotal['daily'] = array();
                    //for ($i=$totalDate;$i>0;$i--) {
                    for ($i = 1; $i <= $totalDate; $i++) {
                        //$before = ($totalDate == $i) ? $totalDate : $i + 1;
                        $before = (1 == $i) ? 1 : $i - 1;

                        $subtotal['daily'][$i] = array('total' => ($reg['daily'][$i]['total'] - $unreg['daily'][$i]['total']), 'color' => '');

                        if ($subtotal['daily'][$before]['total'] < $subtotal['daily'][$i]['total']) {
                            $subtotal['daily'][$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($subtotal['daily'][$before]['total'] > $subtotal['daily'][$i]['total']) {
                            $subtotal['daily'][$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $subtotal['daily'][$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                    $subtotal['daily'] = array_reverse($subtotal['daily'], true);
                    $recordResponse['subject'][] = $subtotal;

                    $response[] = $recordResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response) - 1;
                }

                //total record modification
                $response[count($response) - 1]['service'] = "total";
                $response[count($response) - 1]['subject'][2]['name'] = "grandtotal";

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getUserReportxxx($shortCode, $year, $month, $operatorId
    , $service, $startFrom, $limit) {

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(tgl)=@date@, ABS(amount), 0)) active@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.sdc = '$shortCode'";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        //generate limit
        $sqlLimit = "";
        if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        if (!empty($sqlLimit)) {
            $sql = "
				SELECT
					service
				FROM
					rpt_users rpt INNER JOIN operator opr ON (rpt.operator = opr.operator_name)
				WHERE
					status = 1
					AND tgl>='$dateRangeStart' AND tgl<'$dateRangeEnd'
					$sqlShortcode
					$sqlOperator
					$sqlService
				GROUP BY
					service
			";
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        $sql = "
			SELECT
				service, SUM(ABS(amount)) total
				$sqlDynamic
			FROM
				rpt_users rpt INNER JOIN operator opr ON (rpt.operator = opr.operator_name)
			WHERE
				status = 1
				AND tgl >= '$dateRangeStart' AND tgl < '$dateRangeEnd'
				$sqlShortcode
				$sqlOperator
				$sqlService
			GROUP BY
				service WITH ROLLUP
			$sqlLimit
		";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $record) {
                    $recordResponse['service'] = $record['service'];
                    $daily = array();

                    //for ($i=$totalDate;$i>=1;$i--) {
                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily[$i]['total'] = $record["active$i"];

                        $before = (1 == $i) ? 1 : $i - 1;

                        if ($daily[$before]['total'] < $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . GREEN . ';';
                        } else if ($daily[$before]['total'] > $daily[$i]['total']) {
                            $daily[$i]['color'] = 'background:' . RED . ';';
                        } else {
                            $daily[$i]['color'] = 'background:' . GREY . ';';
                        }
                    }
                    $recordResponse['daily'] = array_reverse($daily, true);

                    $response[] = $recordResponse;
                }

                if (!isset($totalRecord)) {
                    $totalRecord = count($response) - 1;
                }

                //total record modification
                $response[count($response) - 1]['service'] = "total";

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getUserReport($username, $password, $adn, $operatorId, $service, $date, $channel, $startFrom, $limit) {

        $sqlComplete = 'select * from rpt_subscription WHERE total >= 0';

        $params = array();

        if ($operatorId != '') {
            $sqlComplete = $sqlComplete . " AND operator like '%" . $operatorId . "%' ";
        }
        if ($adn != '') {
            $sqlComplete = $sqlComplete . " AND adn = " . $adn . "";
        }
        if ($service != '') {
            $sqlComplete = $sqlComplete . " AND service like '%" . $service . "%'";
        }
        if ($date != '') {
            $sqlComplete = $sqlComplete . " AND date_created like '%" . $date . "%'";
        }
        if ($channel != '') {
            $sqlComplete = $sqlComplete . " AND channel like '%" . $channel . "%'";
        }

        $queryTotal = $this->db->query($sqlComplete);
        $totalRecord = count($queryTotal->result());

        $sqlComplete = $sqlComplete . " ORDER BY date_created DESC LIMIT " . $startFrom . ", " . $limit . "";

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));

        $query = $this->db->query($sqlComplete);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                //create final object response
                foreach ($query->result_array() as $record) {
                    $response[] = $record;
                }

                return array(
                    0 => $totalRecord,
                    1 => $response
                );
            } else {
                return $response;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getContentOwner($searchPattern, $startFrom, $limit) {
        $params = array();

        $sqlSearch = "";
        if (isset($searchPattern)) {
            $sqlSearch = " AND o_name LIKE '%$searchPattern%'";
        }

        $sqlLimit = "";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " LIMIT $startFrom, $limit";
        } else if (isset($limit)) {
            $sqlLimit = " LIMIT $limit";
        }

        $sql = "
		SELECT
			id, o_name as name
		FROM
			tbl_content_owner
		WHERE
			1 = 1
			$sqlSearch
		ORDER BY
			id
		";

        $sqlComplete = "
			$sql
			$sqlLimit
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }
//			$data = array();
//			foreach ($query->result_array() as $row) {
//				$data[] = $row['shortCode'];
//			}
            return array(
                0 => $totalRecord,
                1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDownloadReportDaily($year, $month, $operatorId, $contentOwner, $contentType) {
        $params = array();

        $sqlLimit = "";

        $sqlContentOwner = "";
        if ($contentOwner) {
            $sqlContentOwner = " AND partner=$contentOwner";
        }

        $sqlContentType = "";
        if ($contentType) {
            if (is_array($contentType)) {
                $sqlContentType = sprintf(" ctype in (%s)", implode(',', $contentType));
            } else {
                $sqlContentType = " AND ctype='$contentType'";
            }
        }

        $sqlOperator = "";
        if ($operatorId) {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total), 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total), 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        // generate sql for total
        $sqlTotal = ", SUM(ABS(total)) totalSent, SUM(IF(status='DELIVERED', ABS(total), 0)) totalDelivered, SUM(IF(status='DELIVERED', ABS(total)*price, 0)) totalRevenue ";

        $sql = "
		SELECT
			ctype as type,
			code,
			content_title as title
			$sqlDynamic
			$sqlTotal
		FROM
			rpt_content a
		LEFT JOIN
			tbl_content_dl b
		ON
			a.code=b.content_code
		WHERE
			sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
			$sqlOperator
			$sqlContentOwner
			$sqlContentType
		GROUP BY
			ctype, code
		ORDER BY
			totalSent DESC
		";

        $sqlComplete = "
			$sql
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }

            $grandTotal = array(
                'type' => 'total',
                'code' => 'total',
                'title' => 'total',
                'totalSent' => 0,
                'totalDelivered' => 0,
                'totalRevenue' => 0
            );
            for ($i = 1; $i <= $totalDate; $i++) {
                $grandTotal['daily'][$i] = array(
                    'sent' => 0,
                    'delivered' => 0,
                    'revenue' => 0
                );
            }

            foreach ($query->result_array() as $dl) {
                $subjectResponse = array();
                $subjectResponse['type'] = $dl['type'];
                $subjectResponse['code'] = $dl['code'];
                $subjectResponse['title'] = $dl['title'];
                $subjectResponse['totalSent'] = $dl['totalSent'];
                $subjectResponse['totalDelivered'] = $dl['totalDelivered'];
                $subjectResponse['totalRevenue'] = $dl['totalRevenue'];
                // grandtotal
                $grandTotal['totalSent'] += $dl['totalSent'];
                $grandTotal['totalDelivered'] += $dl['totalDelivered'];
                $grandTotal['totalRevenue'] += $dl['totalRevenue'];

                $subjectResponse['daily'] = array(
                    1 => array(
                        'sent' => 0,
                        'delivered' => 0,
                        'revenue' => 0,
                        'color' => ''
                    )
                );

                for ($i = 1; $i <= $totalDate; $i++) {
                    $before = (1 == $i) ? 1 : $i - 1;

                    $daily = array();
                    $daily['sent'] = $dl["sent$i"];
                    $daily['delivered'] = $dl["delivered$i"];
                    $daily['revenue'] = $dl["revenue$i"];

                    if ($subjectResponse['daily'][$before]['revenue'] < $daily['revenue']) {
                        $daily['color'] = 'background:' . GREEN . ';';
                    } else if ($subjectResponse['daily'][$before]['revenue'] > $daily['revenue']) {
                        $daily['color'] = 'background:' . RED . ';';
                    } else {
                        $daily['color'] = 'background:' . GREY . ';';
                    }

                    $subjectResponse['daily'][$i] = $daily;
                    // grand total
                    $grandTotal['daily'][$i]['sent'] += $dl["sent$i"];
                    $grandTotal['daily'][$i]['delivered'] += $dl["delivered$i"];
                    $grandTotal['daily'][$i]['revenue'] += $dl["revenue$i"];
                }
                $response[] = $subjectResponse;
            }
            $response[] = $grandTotal;

            return array(
                0 => $totalRecord,
                1 => $response
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDownloadReportMonthly($year, $operatorId, $contentOwner, $contentType) {
        $params = array();

        $sqlLimit = "";

        $sqlContentOwner = "";
        if ($contentOwner) {
            $sqlContentOwner = " AND partner=$contentOwner";
        }

        $sqlContentType = "";
        if ($contentType) {
            if (is_array($contentType)) {
                $sqlContentType = sprintf(" ctype in (%s)", implode(',', $contentType));
            } else {
                $sqlContentType = " AND ctype='$contentType'";
            }
        }

        $sqlOperator = "";
        if ($operatorId) {
            $sqlOperator = " AND operator='$operatorId'";
        }

        if (date("Y") == $year) {
            $totalMonth = (int) date("m");
        } else {
            $totalMonth = 12;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(MONTH(sumdate) = @date@, ABS(total), 0)) sent@date@,
							SUM(IF(MONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total), 0)) delivered@date@,
							SUM(IF(MONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalMonth; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        // generate sql for total
        $sqlTotal = ", SUM(ABS(total)) totalSent, SUM(IF(status='DELIVERED', ABS(total), 0)) totalDelivered,
					SUM(IF(status='DELIVERED', ABS(total)*price, 0)) totalRevenue ";

        $sql = "
		SELECT
			ctype as type,
			code,
			content_title as title
			$sqlDynamic
			$sqlTotal
		FROM
			rpt_content a
		LEFT JOIN
			tbl_content_dl b
		ON
			a.code=b.content_code
		WHERE
			year(sumdate) = '$year'
			AND ctype <> ''
			$sqlOperator
			$sqlContentOwner
			$sqlContentType
		GROUP BY
			ctype, code
		ORDER BY
			totalSent DESC
		";

        $sqlComplete = "
			$sql
		";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
            $query = $this->db->query($sql, $params);
            if ($query != FALSE) {
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            if (empty($sqlLimit)) {
                $totalRecord = $query->num_rows();
            }

            $grandTotal = array(
                'type' => 'total',
                'code' => 'total',
                'title' => 'total',
                'totalSent' => 0,
                'totalDelivered' => 0,
                'totalRevenue' => 0
            );
            for ($i = 1; $i <= $totalMonth; $i++) {
                $grandTotal['monthly'][$i] = array(
                    'sent' => 0,
                    'delivered' => 0,
                    'revenue' => 0
                );
            }
            foreach ($query->result_array() as $dl) {
                $subjectResponse = array();
                $subjectResponse['type'] = $dl['type'];
                $subjectResponse['code'] = (int) $dl['code'];
                $subjectResponse['title'] = $dl['title'];
                $subjectResponse['totalSent'] = $dl['totalSent'];
                $subjectResponse['totalDelivered'] = $dl['totalDelivered'];
                $subjectResponse['totalRevenue'] = $dl['totalRevenue'];
                // grandtotal
                $grandTotal['totalSent'] += $dl['totalSent'];
                $grandTotal['totalDelivered'] += $dl['totalDelivered'];
                $grandTotal['totalRevenue'] += $dl['totalRevenue'];

                $subjectResponse['monthly'] = array(
                    1 => array(
                        'sent' => 0,
                        'delivered' => 0,
                        'revenue' => 0,
                        'color' => ''
                    )
                );

                for ($i = 1; $i <= $totalMonth; $i++) {
                    $before = (1 == $i) ? 1 : $i - 1;

                    $daily = array();
                    $daily['sent'] = $dl["sent$i"];
                    $daily['delivered'] = $dl["delivered$i"];
                    $daily['revenue'] = $dl["revenue$i"];

                    if ($subjectResponse['monthly'][$before]['revenue'] < $daily['revenue']) {
                        $daily['color'] = 'background:' . GREEN . ';';
                    } else if ($subjectResponse['monthly'][$before]['revenue'] > $daily['revenue']) {
                        $daily['color'] = 'background:' . RED . ';';
                    } else {
                        $daily['color'] = 'background:' . GREY . ';';
                    }

                    $subjectResponse['monthly'][$i] = $daily;
                    // grand total
                    $grandTotal['monthly'][$i]['sent'] += $dl["sent$i"];
                    $grandTotal['monthly'][$i]['delivered'] += $dl["delivered$i"];
                    $grandTotal['monthly'][$i]['revenue'] += $dl["revenue$i"];
                }
                $response[] = $subjectResponse;
            }
            $response[] = $grandTotal;

            return array(
                0 => $totalRecord,
                1 => $response
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getTrafficReportChart($year, $month, $shortCode, $service, $operatorId) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,abs(total),0)) totalSent@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='REJECTED', abs(total), 0),0)) totalFailed@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total), 0),0)) totalDelivered@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0),0)) totalUnknown@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    opr.id operatorId, opr.name operator,
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
                    SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
                    $sqlDynamic
                FROM
                    rpt_service2 rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
                WHERE
                    sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
                    $sqlShortcode
                    $sqlOperator
                    $sqlService
                GROUP BY
                    rpt.operator
                ORDER BY
                    totalSent
                ) temp
            ) temp2
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            $label = array('TOTAL', 'DELIVERED', 'FAILED', 'UNKNOWN');

            if ($query->num_rows() > 0) {
                foreach ($label as $index => $key) {
                    $response[$index]['label'] = $key;
                    $response[$index]['xAxis'] = array();
                    $response[$index]['value'] = array();

                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = 0;
                        foreach ($query->result_array() as $subject) {
                            if ($key == 'TOTAL') {
                                $daily += (int) $subject["totalSent$i"];
                            } elseif ($key == 'DELIVERED') {
                                $daily += (int) $subject["totalDelivered$i"];
                            } elseif ($key == 'FAILED') {
                                $daily += (int) $subject["totalFailed$i"];
                            } elseif ($key == 'UNKNOWN') {
                                $daily += (int) $subject["totalUnknown$i"];
                            }
                        }
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], $daily);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getTrafficChart($startDate, $endDate, $top, $grouping) {
        $dateRangeStart = $startDate;
        $dateRangeEnd = $endDate;

        $params = array();

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
                    SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalSent
                DESC
                ) temp
            ) temp2
            $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                $label = array('DELIVERED', 'FAILED', 'UNKNOWN');
                foreach ($label as $i => $key) {
                    $response[$i]['label'] = $key;
                    $response[$i]['xAxis'] = array();
                    $response[$i]['value'] = array();
                    foreach ($query->result_array() as $index => $subject) {
                        $response[$i]['xAxis'][$index] = $subject['name'];
                        if ($key == 'DELIVERED') {
                            $response[$i]['value'][$index] = $subject['totalDelivered'];
                        } elseif ($key == 'FAILED') {
                            $response[$i]['value'][$index] = $subject['totalFailed'];
                        } elseif ($key == 'UNKNOWN') {
                            $response[$i]['value'][$index] = $subject['totalUnknown'];
                        }
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getRevenueReportChart($year, $month, $shortCode, $service, $operatorId) {
        // get total maximum date on requested month to calculate this month prediction
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-" . ($month + 1) . "-01";

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d");
        } else {
            $totalDate = $maxDate;
        }

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn in ($shortCode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    opr.id operatorId, opr.name operator,
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
                    SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
                    $sqlDynamic
                FROM
                    rpt_service2 rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
                WHERE
                    sumdate>='$dateRangeStart' AND sumdate<'$dateRangeEnd'
                    AND subject like 'MT%'
                    $sqlShortcode
                    $sqlOperator
                    $sqlService
                GROUP BY
                    rpt.operator
                ORDER BY
                    totalSent
                ) temp
            ) temp2
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            $label = array('REVENUE');

            if ($query->num_rows() > 0) {
                foreach ($label as $index => $key) {
                    $response[$index]['label'] = $key;
                    $response[$index]['xAxis'] = array();
                    $response[$index]['value'] = array();

                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        $daily = 0;
                        foreach ($query->result_array() as $subject) {
                            $daily += (int) $subject["totalRevenue$i"];
                        }
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], $daily);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getRevenueChart($startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $dateRangeStart = $startDate;
        $dateRangeEnd = $endDate;

        $params = array();

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            if ($shortcode === 'all') {
                $shortcode = "";
            }
            $sqlShortcode .= " AND rpt.adn like '%$shortcode%'";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = "LIMIT 0,$top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
                    SUM(abs(total)*gross) totalRevenue
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    AND subject like 'MT;%'
         
                    $sqlOperator
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalRevenue
                DESC
                $sqlTop
                ) temp
            ) temp2
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject['name'];
                    $response[$index]['xAxis'] = $index;
                    $response[$index]['value'] = (int) $subject['totalRevenue'];
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getRevenueChartl7($startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $dateRangeStart = $startDate;
        $dateRangeEnd = $endDate;

        $params = array();

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            if ($shortcode === 'all') {
                $shortcode = "";
            }
            $sqlShortcode .= " AND rpt.adn like '%$shortcode%'";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = "LIMIT 0,$top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown,
                    SUM(abs(total)*gross) totalRevenue
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    AND subject like 'MT;%'
                    AND partner = 'l7'
                    $sqlOperator
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalRevenue
                DESC
                $sqlTop
                ) temp
            ) temp2
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject['name'];
                    $response[$index]['xAxis'] = $index;
                    $response[$index]['value'] = (int) $subject['totalRevenue'];
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getUserReportChart($dateRangeStart, $dateRangeEnd, $shortCode, $operatorId, $top, $grouping, $isPercentage=false) {
        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        $params = array();

        //generate sql for percentage difference
        $sqlRange = ", (active$last2Day+(active$last2Day*$percentage)) maxRange,
                       (active$last2Day-(active$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(tgl)=@date@, ABS(amount), 0)) active@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.sdc = '$shortCode'";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " INNER JOIN operator opr ON (rpt.operator = opr.operator_name)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlJoint = " INNER JOIN $this->db_xmp.operator opr ON (rpt.operator = opr.id)";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.sdc) name, ";
                $sqlGroup = " rpt.sdc";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (active$last1Day>maxRange OR active$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
            SELECT
                tmp2.*
            FROM
                (
                SELECT
                    tmp.*
                    $sqlRange
                FROM
                    (
                    SELECT
                        $sqlSelect 
                        SUM(ABS(amount)) total
                        $sqlDynamic
                    FROM
                        rpt_users rpt 
                        $sqlJoint
                    WHERE
                        status = 1
                        AND tgl>='$dateRangeStart' AND tgl<'$dateRangeEnd'
                        $sqlShortcode
                        $sqlOperator
                    GROUP BY
                        name
                    )tmp
                    WHERE
                        1=1
                    ORDER BY
                        total
                    DESC
                )tmp2
            WHERE
                1=1
            $sqlDiff
            $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = (empty($subject["name"])) ? 'noname' : $subject["name"];
                    $response[$index]['value'] = (int) $subject["total"];
                    $response[$index]['xAxis'] = $index;
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDownloadContentReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $contentType, $top, $isPercentage=false) {
        $params = array();

        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        //generate sql for percentage difference
        $sqlRange = ", (sent$last2Day+(sent$last2Day*$percentage)) maxRange,
                       (sent$last2Day-(sent$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total), 0)) sent@date@,
								SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total), 0)) delivered@date@,
								SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        // generate sql for total
        $sqlTotal = ", SUM(ABS(total)) totalSent, SUM(IF(status='DELIVERED', ABS(total), 0)) totalDelivered,
        			SUM(IF(status='DELIVERED', ABS(total)*price, 0)) totalRevenue ";

        $sqlContentType = "";
        if ($contentType) {
            if (is_array($contentType)) {
                $sqlContentType = sprintf(" ctype in (%s)", implode(',', $contentType));
            } else {
                $sqlContentType = " AND ctype = '$contentType'";
            }
        }

        $sqlOperator = "";
        if ($operatorId) {
            $sqlOperator = " AND operator = '$operatorId'";
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (sent$last1Day>maxRange OR sent$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*
                $sqlRange
            FROM
                (
                SELECT
                    ctype as type,
                    code,
                    partner,
                    o_name as owner,
                    content_title as title
                    $sqlDynamic
                    $sqlTotal
                FROM
                    rpt_content a
                LEFT JOIN
                    tbl_content_dl b
                ON
                    a.code=b.content_code
                RIGHT JOIN
                    tbl_content_owner o
                ON
                    a.partner=o.id
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    $sqlOperator
                    $sqlContentType
                GROUP BY
                    partner
                ORDER BY
                    totalSent DESC
                )tmp
            )tmp2
        WHERE
            1=1
            $sqlDiff
            $sqlTop
        ";

        $sqlComplete = "
            $sql
        ";

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = (empty($subject["code"])) ? 'noname' : $subject["owner"];
                    $response[$index]['value'] = (int) $subject["totalRevenue"];
                    $response[$index]['xAxis'] = $subject["partner"];
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getClosereasonReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortCode, $service, $sorting, $top, $isPercentage=false) {

        $timestampStart = strtotime($dateRangeStart);
        $timestampEnd = strtotime($dateRangeEnd);

        $dates = array();
        $i = -1;
        while ($timestampEnd >= $timestampStart) {
            $i++;
            $dates[$i]['month'] = date('m', $timestampEnd);
            $dates[$i]['date'] = date('d', $timestampEnd);
            $timestampEnd-=86400;
        }
        sort($dates);
        $totalDate = count($dates);

        $percentage = DIFF_PERCENTAGE / 100;

        if (3 > (int) date('d')) {
            $last2Day = '01';
            $last1Day = date('d');
        } else {
            $last2Day = date("d", strtotime("-2 day", strtotime(date('Y-m-d'))));
            $last1Day = date("d", strtotime("-1 day", strtotime(date('Y-m-d'))));
        }

        //generate sql for percentage difference
        $sqlRange = ", (total" . $dates[$totalDate - 1]['month'] . "_$last2Day +(total" . $dates[$totalDate - 1]['month'] . "_$last2Day*$percentage)) maxRange,
                       (total" . $dates[$totalDate - 1]['month'] . "_$last2Day-(total" . $dates[$totalDate - 1]['month'] . "_$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ AND MONTH(sumdate)=@month@,abs(total),0)) total@month@_@date@";

        $sqlDynamic = "";
        for ($i = 0; $i < $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $dates[$i]['date'], str_replace("@month@", $dates[$i]['month'], $sqlDynamicTemplate));
        }

        $params = array();

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn = '$shortCode'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service in ($service)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlSorting = "";
        if (isset($sorting)) {
            if ($sorting == "total") {
                $sqlSorting = "ORDER BY total DESC";
            } else if ($sorting == "yesterday") {
                $sqlSorting = "ORDER BY total" . $dates[$totalDate - 1]['month'] . "_" . date('d', $last1Day) . " DESC";
            }
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (total" . $dates[$totalDate - 1]['month'] . "_$last1Day>maxRange OR total" . $dates[$totalDate - 1]['month'] . "_$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*
                $sqlRange
            FROM
                (
                SELECT
                    opr.id operatorId, opr.name operator, rpt.closereason, resp.desc description,
                    SUM(abs(total)) total
                    $sqlDynamic
                FROM
                    rpt_creason rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
                    LEFT JOIN tbl_response resp ON (opr.name=resp.opr AND rpt.closereason=resp.code)
                WHERE
                    rpt.sumdate>='$dateRangeStart' AND rpt.sumdate<='$dateRangeEnd'
                    $sqlShortcode
                    $sqlService
                    $sqlOperator
                GROUP BY
                    rpt.closereason,
                    rpt.operator
                ORDER BY
                	total DESC
                )tmp
            )tmp2
        WHERE
            1=1
        $sqlDiff
        $sqlSorting
        $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject['operator'] . '_' . $subject["closereason"];
                    $response[$index]['value'] = (int) $subject['total'];
                    $response[$index]['xAxis'] = $subject['operator'] . '_' . $subject["closereason"];
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyRevenueReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortcode, $top, $grouping) {
        // select timeperiod

        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;

        $params = array();
        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@, abs(total)*gross,0)) revenue@num@";

//        $sqlDynamicTemplate = ", total as revenue@num@";
//echo "sempaknyet";
        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }
//      echo "| $grouping|";exit;
        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            if ($shortcode === 'all') {
                $shortcode = "";
            }
            $sqlShortcode .= " AND rpt.adn like '%$shortcode%'";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)*gross) totalRevenue
                    $sqlDynamic
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    AND subject like 'MT;%'
                    $sqlOperator
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalRevenue
                DESC
                $sqlTop
                ) temp
            ) temp2
        ";
//echo $sql;exit;
//error_log($sql,3,'/tmp/ardysql');        
        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject["name"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["revenue$i"]);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyRevenueReportChartl7($dateRangeStart, $dateRangeEnd, $operatorId, $shortcode, $top, $grouping) {
        // select timeperiod

        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;

        $params = array();
        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@, abs(total)*gross,0)) revenue@num@";

//        $sqlDynamicTemplate = ", total as revenue@num@";
//echo "sempaknyet";
        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }
//      echo "| $grouping|";exit;
        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            if ($shortcode === 'all') {
                $shortcode = "";
            }
            $sqlShortcode .= " AND rpt.adn like '%$shortcode%'";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)*gross) totalRevenue
                    $sqlDynamic
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    AND subject like 'MT;%'
                    AND partner = 'l7'
                    $sqlOperator
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalRevenue
                DESC
                $sqlTop
                ) temp
            ) temp2
        ";
//echo $sql;exit;
//error_log($sql,3,'/tmp/ardysql');        
        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject["name"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["revenue$i"]);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyTrafficServicePercentageChart($month, $year, $shortCode, $operatorId='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sql = "
    		SELECT
    			service,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			service,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total)*gross, 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total)*gross, 0)) revenue2
	    		FROM 
	    			rpt_service2
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND adn = '$shortCode' 
	    			$sqlOperator
	    		GROUP BY 
	    			service
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

//		log_error(__CLASS__.' '.__METHOD__.' '.__LINE__, $sql);
        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $service = array();
            foreach ($query->result_array() as $row) {
                $service[] = "'{$row['service']}'";
            }
            $serviceList = implode(',', $service);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ , ABS(total), 0)) delivered@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		service
	        		$sqlDynamic
	        	FROM
	        		rpt_service2
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        		AND service in ($serviceList)
	        		$sqlOperator
	    		GROUP BY
	    			service
	        ";

//			log_error(__CLASS__.' '.__METHOD__.' '.__LINE__, $sql);
            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["service"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["delivered$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailyTrafficServicePercentageChartl7($month, $year, $shortCode, $operatorId='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sql = "
    		SELECT
    			service,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			service,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total)*gross, 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total)*gross, 0)) revenue2
	    		FROM 
	    			rpt_service2
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
                                AND adn = '$shortCode' 
                                AND partner = 'l7'
	    			$sqlOperator
	    		GROUP BY 
	    			service
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

//		log_error(__CLASS__.' '.__METHOD__.' '.__LINE__, $sql);
        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $service = array();
            foreach ($query->result_array() as $row) {
                $service[] = "'{$row['service']}'";
            }
            $serviceList = implode(',', $service);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ , ABS(total), 0)) delivered@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		service
	        		$sqlDynamic
	        	FROM
	        		rpt_service2
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        		AND service in ($serviceList)
                                AND partner = 'l7'
	        		$sqlOperator
	    		GROUP BY
	    			service
	        ";

//			log_error(__CLASS__.' '.__METHOD__.' '.__LINE__, $sql);
            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["service"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["delivered$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailyRevenuePercentageChart($month, $year, $shortCode, $operatorId='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sql = "
    		SELECT
    			service,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			service,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total)*gross, 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total)*gross, 0)) revenue2
	    		FROM 
	    			rpt_service2
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND adn = '$shortCode'
	    			$sqlOperator
	    		GROUP BY 
	    			service
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $service = array();
            foreach ($query->result_array() as $row) {
                $service[] = "'{$row['service']}'";
            }
            $serviceList = implode(',', $service);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ , ABS(total)*gross, 0)) revenue@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		service
	        		$sqlDynamic
	        	FROM
	        		rpt_service2
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        		AND service in ($serviceList)
	        		$sqlOperator
	    		GROUP BY
	    			service
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["service"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["revenue$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailyUserPercentageChart($month, $year, $shortCode, $operatorId='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sql = "
    		SELECT
    			service,
    			FLOOR(((amount1-amount2)/amount2)*100) percentage,
    			ABS(amount1-amount2) amount
    		FROM
    		(
	    		SELECT
	    			service,
	    			SUM(IF(DAYOFMONTH(tgl)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(amount), 0)) amount1,
	    			SUM(IF(DAYOFMONTH(tgl)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(amount), 0)) amount2
	    		FROM 
	    			rpt_users
	    		WHERE
	    			tgl BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND sdc='$shortCode'
	    		AND	status='1'
	    			$sqlOperator
	    		GROUP BY 
	    			service
	    	) as tbl
	    	WHERE
	    		IF( amount2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((amount1-amount2)/amount2)*100) > $diff
	    		OR
	    			FLOOR(((amount1-amount2)/amount2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		amount DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed XXXXXX: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $service = array();
            foreach ($query->result_array() as $row) {
                $service[] = "'{$row['service']}'";
            }
            $serviceList = implode(',', $service);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(tgl)=@date@ AND status='1', ABS(amount), 0)) traffic@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		service
	        		$sqlDynamic
	        	FROM
	        		rpt_users
	        	WHERE
	        		tgl BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        		AND service in ($serviceList)
	        		$sqlOperator
	    		GROUP BY
	    			service
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["service"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["traffic$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailySubscriberPercentageChart($month, $year, $shortCode, $operatorId='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sql = "
    		SELECT
    			service,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			service,
	    			(SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)) AND substring_index(substring_index(subject,';',-3),';',1)='REG', ABS(total), 0)) + SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)) AND substring_index(substring_index(subject,';',-3),';',1)='UNREG', total, 0))) revenue1,
	    			(SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)) AND substring_index(substring_index(subject,';',-3),';',1)='REG', ABS(total), 0)) + SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)) AND substring_index(substring_index(subject,';',-3),';',1)='UNREG', ABS(total), 0))) revenue2
	    		FROM 
	    			rpt_service2
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND adn = '$shortCode'
	    		AND subject like 'MT%'
				AND substring_index(substring_index(subject,';',-3),';',1) in ('REG', 'UNREG')
	    			$sqlOperator
	    		GROUP BY 
	    			service
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $service = array();
            foreach ($query->result_array() as $row) {
                $service[] = "'{$row['service']}'";
            }
            $serviceList = implode(',', $service);

            $sqlDynamicTemplate = ", (SUM(IF(DAYOFMONTH(sumdate)=@date@
									AND substring_index(substring_index(subject,';',-3),';',1)='REG', ABS(total), 0)) + SUM(IF(DAYOFMONTH(sumdate)=@date@
									AND substring_index(substring_index(subject,';',-3),';',1)='UNREG', total, 0))) subtotal@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		service
	        		$sqlDynamic
	        	FROM
	        		rpt_service2
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        	AND service in ($serviceList)
	        	AND subject like 'MT%'
				AND substring_index(substring_index(subject,';',-3),';',1) in ('REG', 'UNREG')
	        		$sqlOperator
	    		GROUP BY
	    			service
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["service"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["subtotal$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailyCloseReasonPercentageChart($period, $shortCode, $operatorId='', $service='') {
        $diff = DIFF_PERCENTAGE;

        write_log('debug', $period);

        if (strpos($period, '-') !== false) {
            list($year, $month) = explode('-', $period);
            $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            if (date("Y") == $year && (int) date("m") == (int) $month) {
                $totalDate = (int) date("d") - 1;
            } else {
                $totalDate = $maxDate;
            }

            $dateRangeStart = "$year-$month-01";
            $dateRangeEnd = "$year-$month-$totalDate";
        } else {
            $dateRangeStart = date("Y-m-d", mktime(0, 0, 0, date("m"), (int) date("d") - $period, date("Y")));
            $dateRangeEnd = date("Y-m-d");
            $totalDate = $period;
        }

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator in ($operatorId)";
        }

        $sqlService = "";
        if ($service != '') {
            $sqlService = " AND service in ($service)";
        }

        $sql = "
    		SELECT
    			closereason,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			closereason,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total), 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total), 0)) revenue2
	    		FROM 
	    			rpt_creason
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND adn = '$shortCode'
	    			$sqlOperator
	    			$sqlService
	    		GROUP BY 
	    			closereason,
	    			operator
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $closereason = array();
            foreach ($query->result_array() as $row) {
                $closereason[] = "'{$row['closereason']}'";
            }
            $closereasonList = implode(',', $closereason);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total), 0)) traffic@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		concat(operator,'_',closereason) cr
	        		$sqlDynamic
	        	FROM
	        		rpt_creason
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        	AND closereason in ($closereasonList)
	        		$sqlOperator
	        		$sqlService
	    		GROUP BY
	    			closereason,
	    			operator
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["cr"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["traffic$i"]);
                }
            }
            return $response;
        }
    }

    public function getDailyTrafficReportChart($dateRangeStart, $dateRangeEnd, $shortcode, $top, $grouping) {
        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        $params = array();

        //generate sql for percentage difference
        $sqlRange = ", (totalSent$last2Day+(totalSent$last2Day*$percentage)) maxRange,
                       (totalSent$last2Day-(totalSent$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,abs(total),0)) totalSent@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='REJECTED', abs(total), 0),0)) totalFailed@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total), 0),0)) totalDelivered@num@,
                    SUM(IF(DAYOFMONTH(sumdate)=@num@,IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0),0)) totalUnknown@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        $sqlDiff = "";
        if ($totalDate > 1) {
            $sqlDiff .= "AND (totalSent$last1Day>maxRange OR totalSent$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            $sqlShortcode .= " AND rpt.adn ='$shortcode'";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
                $sqlRange
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(abs(total)) totalSent,
                    SUM(IF(msgstatus='REJECTED', abs(total), 0)) totalFailed, SUM(IF(msgstatus='DELIVERED', abs(total), 0)) totalDelivered,
                    SUM(IF((msgstatus!='DELIVERED' AND msgstatus!='REJECTED') OR msgstatus is NULL, abs(total), 0)) totalUnknown
                    $sqlDynamic
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalSent
                DESC
                ) temp
            ) temp2
            WHERE
                1=1
                $sqlDiff
            $sqlTop
        ";

        $this->logError('test', $sql);
        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject["name"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["totalSent$i"]);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyRevenuePercentageReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortcode, $top, $grouping) {
        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        $params = array();

        //generate sql for percentage difference
        $sqlRange = ", (totalRevenue$last2Day+(totalRevenue$last2Day*$percentage)) maxRange,
                       (totalRevenue$last2Day-(totalRevenue$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@num@,IF(msgstatus='DELIVERED', abs(total)*gross, 0),0)) totalRevenue@num@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        $sqlDiff = "";
        if ($totalDate > 1) {
            $sqlDiff .= "AND (totalRevenue$last1Day>maxRange OR totalRevenue$last1Day<minRange)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlShortcode = "";
        if (isset($shortcode)) {
            $sqlShortcode .= " AND rpt.adn='$shortcode'";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            temp2.*
        FROM
            (
            SELECT
                temp.*
                $sqlRange
            FROM
                (
                SELECT
                    $sqlSelect
                    SUM(IF(msgstatus='DELIVERED', abs(total)*gross, 0)) totalRevenue
                    $sqlDynamic
                FROM
                    rpt_service2 rpt 
                    $sqlJoint
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    AND subject like 'MT%'
                    $sqlOperator
                    $sqlShortcode
                GROUP BY
                    $sqlGroup
                ORDER BY
                    totalRevenue
                DESC
                ) temp
            ) temp2
            WHERE
                1=1
                $sqlDiff
            $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject["name"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["totalRevenue$i"]);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailySubcriberSubtotalReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage=false) {
        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        $params = array();

        //generate sql for percentage difference
        $sqlRange = ", ((totalReg$last2Day+totalUnreg$last2Day)+((totalReg$last2Day+totalUnreg$last2Day)*$percentage)) maxRange,
                       ((totalReg$last2Day+totalUnreg$last2Day)-((totalReg$last2Day+totalUnreg$last2Day)*$percentage)) minRange";

        // generate sql for daily subtotal summary
        $sqlSubtotalTemplate = ", (totalReg@num@-totalUnreg@num@) totalSubtotal@num@";


        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(type='REG' AND DAYOFMONTH(sumdate)=@num@, abs(total), 0)) totalReg@num@,
								SUM(IF(type='UNREG' AND DAYOFMONTH(sumdate)=@num@, abs(total), 0)) totalUnreg@num@";

        $sqlSubtotal = "";
        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlSubtotal.=str_replace("@num@", $i, $sqlSubtotalTemplate);
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortcode)) {
            $sqlShortcode = " AND rpt.adn in ($shortcode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND ((totalReg$last1Day+totalUnreg$last1Day)>maxRange OR (totalReg$last1Day+totalUnreg$last1Day)<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*,
                (totalReg-totalUnreg) totalSubtotal
                $sqlSubtotal
                $sqlRange
            FROM
                (
                SELECT
                    name
                    , SUM(IF(type='REG', abs(total), 0)) totalReg, SUM(IF(type='UNREG', abs(total), 0)) totalUnreg
                    $sqlDynamic
                FROM
                    (
                    SELECT
                        $sqlSelect sumdate, substring_index(substring_index(subject,';',-3),';',1) as type, total
                    FROM
                        rpt_service2 rpt
                    WHERE
                        subject like 'MT%'
                        AND sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                        $sqlShortcode
                        $sqlOperator
                        $sqlService
                    ) temp
                WHERE
                    (type='REG' OR type='UNREG')
                GROUP BY
                    name
                ) tmp
            )tmp2
        WHERE
            1=1
            $sqlDiff
        ORDER BY
            totalSubtotal
        DESC
        $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = (empty($subject["name"])) ? 'noname' : $subject["name"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["totalSubtotal$i"]);
                    }
                }
                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailySubcriberRegUnregReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage=false) {
        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        $params = array();

        //generate sql for percentage difference
        $sqlRange = ", ((totalReg$last2Day+totalUnreg$last2Day)+((totalReg$last2Day+totalUnreg$last2Day)*$percentage)) maxRange,
                       ((totalReg$last2Day+totalUnreg$last2Day)-((totalReg$last2Day+totalUnreg$last2Day)*$percentage)) minRange";

        // generate sql for daily subtotal summary
        $sqlSubtotalTemplate = ", (totalReg@num@+totalUnreg@num@) totalSubtotal@num@";


        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(type='REG' AND DAYOFMONTH(sumdate)=@num@, abs(total), 0)) totalReg@num@, SUM(IF(type='UNREG' AND DAYOFMONTH(sumdate)=@num@, abs(total), 0)) totalUnreg@num@";

        $sqlSubtotal = "";
        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlSubtotal.=str_replace("@num@", $i, $sqlSubtotalTemplate);
            $sqlDynamic.=str_replace("@num@", $i, $sqlDynamicTemplate);
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " LEFT JOIN 
                                $this->db_xmp.operator opr ON (rpt.operator=opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " (rpt.adn) name, ";
                $sqlGroup = " rpt.adn";
            } elseif ($grouping == 'service') {
                $sqlSelect = " (rpt.service) name, ";
                $sqlGroup = " rpt.service";
            }
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortcode)) {
            $sqlShortcode = " AND rpt.adn in ($shortcode)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service = '$service'";
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND ((totalReg$last1Day+totalUnreg$last1Day)>maxRange OR (totalReg$last1Day+totalUnreg$last1Day)<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*,
                (totalReg+totalUnreg) totalSubtotal
                $sqlSubtotal
                $sqlRange
            FROM
                (
                SELECT
                    name
                    , SUM(IF(type='REG', abs(total), 0)) totalReg, SUM(IF(type='UNREG', abs(total), 0)) totalUnreg
                    $sqlDynamic
                FROM
                    (
                    SELECT
                        $sqlSelect sumdate, substring_index(substring_index(subject,';',-3),';',1) as type, total
                    FROM
                        rpt_service2 rpt
                    WHERE
                        subject like 'MT%'
						AND substring_index(substring_index(subject,';',-3),';',1) in ('REG', 'UNREG')
                        AND sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                        $sqlShortcode
                        $sqlOperator
                        $sqlService
                    ) temp
                GROUP BY
                    name
                ) tmp
            )tmp2
        WHERE
            1=1
            $sqlDiff
        ORDER BY
            totalSubtotal
        DESC
        $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                $label = array('REG', 'UNREG');
                foreach ($label as $i => $key) {
                    $i = $i + 1;
                    $response[$i]['label'] = $key;
                    $response[$i]['value'] = array();
                    $response[$i]['xAxis'] = array();
                    //create final object response
                }

                $reg = 0;
                $unreg = 0;

                foreach ($query->result_array() as $index => $subject) {
                    $reg += (int) $subject["totalReg"];
                    $unreg += (int) $subject["totalUnreg"];
                }

                $response[1]['value'] = $reg;
                $response[2]['value'] = $unreg;

                return $response;
            }
            else
                return false;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyUserReportChart($dateRangeStart, $dateRangeEnd, $shortCode, $operatorId, $top, $grouping, $isPercentage=false) {
        $params = array();

        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;
        list($year, $month, $day) = explode('-', $dateRangeEnd);

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        //generate sql for percentage difference
        $sqlRange = ", (active$last2Day+(active$last2Day*$percentage)) maxRange,
                       (active$last2Day-(active$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(tgl)=@date@, ABS(amount), 0)) active@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.sdc = '$shortCode'";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        //group by
        $sqlGroup = "";
        $sqlJoint = " INNER JOIN $this->db_xmp.operator opr ON (rpt.operator = opr.id)";
        if (isset($grouping)) {
            if ($grouping == 'operator') {
                $sqlSelect = " opr.id operatorId, opr.name, ";
                $sqlGroup = " rpt.operator";
            } elseif ($grouping == 'sdc') {
                $sqlSelect = " sdc as unit, ";
                $sqlGroup = " rpt.sdc";
            } elseif ($grouping == 'service') {
                $sqlSelect = " service as unit, ";
                $sqlGroup = " rpt.service";
            }
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (active$last1Day>maxRange OR active$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
            SELECT
                tmp2.*
            FROM
                (
                SELECT
                    tmp.*
                    $sqlRange
                FROM
                    (
                    SELECT
                        $sqlSelect 
                        SUM(ABS(amount)) total
                        $sqlDynamic
                    FROM
                        rpt_users rpt 
                        $sqlJoint
                    WHERE
                        status = 1
                        AND tgl BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                        $sqlShortcode
                        $sqlOperator
                    GROUP BY
                        unit
                    )tmp
                    WHERE
                        1=1
                    ORDER BY
                        total
                    DESC
                )tmp2
            WHERE
                1=1
            $sqlDiff
            $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = (empty($subject["unit"])) ? 'noname' : $subject["unit"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["active$i"]);
                    }
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyDownloadContentPercentageChart($month, $year, $operatorId='', $contentOwner='', $contentType='') {
        $diff = DIFF_PERCENTAGE;

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd = "$year-$month-$totalDate";

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $sqlContentOwner = "";
        if ($contentOwner != '') {
            $sqlContentOwner = " AND partner='$contentOwner'";
        }

        $sqlContentType = "";
        if ($contentType != '') {
            $sqlContentType = " AND ctype='$contentType'";
        }

        $sql = "
    		SELECT
    			code,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			code,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total)*price, 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total)*price, 0)) revenue2
	    		FROM 
	    			rpt_content
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND status='DELIVERED'
	    			$sqlOperator
	    			$sqlContentOwner
	    			$sqlContentType
	    		GROUP BY 
	    			code
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $code = array();
            foreach ($query->result_array() as $row) {
                $code[] = $row['code'];
            }
            $codeList = implode(',', $code);

            $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalDate; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		code
	        		$sqlDynamic
	        	FROM
	        		rpt_content
	        	WHERE
	        		sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
	        		AND code in ($codeList)
	        		$sqlOperator
	    			$sqlContentOwner
	    			$sqlContentType
	    		GROUP BY
	    			code
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["code"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalDate; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["revenue$i"]);
                }
            }
            return $response;
        }
    }

    public function getMonthlyDownloadContentPercentageChart($year, $operatorId='', $contentOwner='', $contentType='') {
        $diff = DIFF_PERCENTAGE;

        $totalMonth = (int) date("m");

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $sqlContentOwner = "";
        if ($contentOwner != '') {
            $sqlContentOwner = " AND partner='$contentOwner'";
        }

        $sqlContentType = "";
        if ($contentType != '') {
            $sqlContentType = " AND ctype='$contentType'";
        }

        $sql = "
    		SELECT
    			code,
    			FLOOR(((revenue1-revenue2)/revenue2)*100) percentage,
    			ABS(revenue1-revenue2) revenue
    		FROM
    		(
	    		SELECT
	    			code,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-1)), ABS(total)*price, 0)) revenue1,
	    			SUM(IF(DAYOFMONTH(sumdate)=DAYOFMONTH(DATE(CURDATE()-2)), ABS(total)*price, 0)) revenue2
	    		FROM 
	    			rpt_content
	    		WHERE
	    			sumdate BETWEEN DATE(CURDATE()-2) AND DATE(CURDATE()-1)
	    		AND status='DELIVERED'
	    			$sqlOperator
	    			$sqlContentOwner
	    			$sqlContentType
	    		GROUP BY 
	    			code
	    	) as tbl
	    	WHERE
	    		IF( revenue2 = 0,
	    		1=1,
	    		(
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) > $diff
	    		OR
	    			FLOOR(((revenue1-revenue2)/revenue2)*100) < ($diff*-1)
	    		))
	    	ORDER BY
	    		revenue DESC
	    	LIMIT 5
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $code = array();
            foreach ($query->result_array() as $row) {
                $code[] = $row['code'];
            }
            $codeList = implode(',', $code);

            $sqlDynamicTemplate = ", SUM(IF(MONTH(sumdate)='@date@' AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

            $sqlDynamic = "";
            for ($i = 1; $i <= $totalMonth; $i++) {
                $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sql = "
	        	SELECT
	        		code
	        		$sqlDynamic
	        	FROM
	        		rpt_content
	        	WHERE
	        		YEAR(sumdate)='$year'
	        		AND code in ($codeList)
	        		$sqlOperator
	    			$sqlContentOwner
	    			$sqlContentType
	    		GROUP BY
	    			code
	        ";

            write_log('debug', "SQL Executed: $sql");
            $query = $this->db->query($sql);

            if (!is_object($query)) {
                throw new Exception('error on query.');
            }

            $response = array();
            foreach ($query->result_array() as $index => $row) {
                $response[$index]['label'] = $row["code"];
                $response[$index]['value'] = array();
                $response[$index]['xAxis'] = array();
                //create final object response
                for ($i = 1; $i <= $totalMonth; $i++) {
                    array_push($response[$index]['xAxis'], $i);
                    array_push($response[$index]['value'], (int) $row["revenue$i"]);
                }
            }
            return $response;
        }
    }

    public function getMonthlyDownloadContentReportChart($year, $operatorId='', $contentOwner='', $contentType='') {
        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $sqlContentOwner = "";
        if ($contentOwner != '') {
            $sqlContentOwner = " AND partner='$contentOwner'";
        }

        $sqlContentType = "";
        if ($contentType != '') {
            $sqlContentType = " AND ctype='$contentType'";
        }

        $totalMonth = (int) date("m");

        $sqlDynamicTemplate = ", SUM(IF(MONTH(sumdate)=@month@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@month@";
        $sqlDynamic = "";
        for ($i = 1; $i <= $totalMonth; $i++) {
            $sqlDynamic.=str_replace("@month@", $i, $sqlDynamicTemplate);
        }

        $sql = "
        	SELECT
        		code,
        		SUM(IF(status='DELIVERED', ABS(total), 0)) totalSent
        		$sqlDynamic
        	FROM
        		rpt_content
        	WHERE
        		YEAR(sumdate)='$year'  
        		$sqlOperator
    			$sqlContentOwner
    			$sqlContentType
    		GROUP BY
    			code
    		ORDER BY 
    			totalSent DESC
    		LIMIT 5
        ";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() > 0) {
            $response = array();
            foreach ($query->result_array() as $row) {
                $xAxis = array();
                $value = array();
                for ($i = 1; $i <= $totalMonth; $i++) {
                    array_push($xAxis, $i);
                    array_push($value, (int) $row["revenue$i"]);
                }
                $response[] = array(
                    'label' => $row["code"],
                    'xAxis' => $xAxis,
                    'value' => $value
                );
            }
            return $response;
        } else {
            return false;
        }
    }

    public function getMonthlyContentOwnerReportChart($year, $operatorId='', $contentType='') {
        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $sqlContentType = "";
        if ($contentType != '') {
            $sqlContentType = " AND ctype='$contentType'";
        }

        $totalMonth = (int) date("m");

//    	$sqlDynamicTemplate = ", SUM(IF(MONTH(sumdate)=@month@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@month@";
//	    $sqlDynamic="";
//	    for ($i=1;$i<=$totalMonth;$i++) {
//	    	$sqlDynamic.=str_replace("@month@", $i, $sqlDynamicTemplate);
//	    }

        $sql = "
        	SELECT
        		partner,
        		o_name as owner,
        		SUM(IF(status='DELIVERED', ABS(total)*price, 0)) totalRevenue
        	FROM
        		rpt_content a
        	RIGHT JOIN
                tbl_content_owner o
            ON
                a.partner=o.id
        	WHERE
        		YEAR(sumdate)='$year'  
        		$sqlOperator
    			$sqlContentType
    		GROUP BY
    			partner
    		ORDER BY 
    			totalRevenue DESC
    		LIMIT 10
        ";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() > 0) {
            $response = array();
            foreach ($query->result_array() as $row) {
                $response[] = array(
                    'label' => $row["owner"],
                    'xAxis' => $row["partner"],
                    'value' => ($row['totalRevenue'] / 1000000)
                );
            }
            return $response;
        } else {
            return false;
        }
    }

    public function getDailyDownloadContentReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $contentOwner, $contentType, $top, $isPercentage=false) {
        $params = array();

        // select timeperiod
        $dateDiff = strtotime($dateRangeEnd) - strtotime($dateRangeStart);
        $totalDate = floor($dateDiff / (60 * 60 * 24)) + 1;
        $percentage = DIFF_PERCENTAGE / 100;

        if (3 > (int) date('d')) {
            $last2Day = 1;
            $last1Day = (int) date('d');
        } else {
            $last2Day = ((int) date('d')) - 2;
            $last1Day = (int) date('d') - 1;
        }

        //generate sql for percentage difference
        $sqlRange = ", (sent$last2Day+(sent$last2Day*$percentage)) maxRange,
                       (sent$last2Day-(sent$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total), 0)) sent@date@,
							  SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total), 0)) delivered@date@,
							  SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*price, 0)) revenue@date@";

        $sqlDynamic = "";
        for ($i = 1; $i <= $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $i, $sqlDynamicTemplate);
        }

        // generate sql for total
        $sqlTotal = ", SUM(ABS(total)) totalSent, SUM(IF(status='DELIVERED', ABS(total), 0)) totalDelivered,
        			SUM(IF(status='DELIVERED', ABS(total)*price, 0)) totalRevenue ";

        $sqlContentOwner = "";
        if ($contentOwner) {
            $sqlContentOwner = " AND partner=$contentOwner";
        }

        $sqlContentType = "";
        if ($contentType) {
            if (is_array($contentType)) {
                $sqlContentType = sprintf(" ctype in (%s)", implode(',', $contentType));
            } else {
                $sqlContentType = " AND ctype='$contentType'";
            }
        }

        $sqlOperator = "";
        if ($operatorId) {
            $sqlOperator = " AND operator='$operatorId'";
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (sent$last1Day>maxRange OR sent$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*
                $sqlRange
            FROM
                (
                SELECT
                    ctype as type,
                    code,
                    content_title as title
                    $sqlDynamic
                    $sqlTotal
                FROM
                    rpt_content a
                LEFT JOIN
                    tbl_content_dl b
                ON
                    a.code=b.content_code
                WHERE
                    sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    $sqlOperator
                    $sqlContentOwner
                    $sqlContentType
                GROUP BY
                    ctype, code
                ORDER BY
                    totalSent DESC
                )tmp
            )tmp2
        WHERE
            1=1
            $sqlDiff
            $sqlTop
        ";

        $sqlComplete = "
            $sql
        ";

        write_log('debug', "SQL Executed: $sqlComplete - " . print_r($params, true));
        $query = $this->db->query($sqlComplete, $params);
        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = (empty($subject["code"])) ? 'noname' : $subject["code"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 1; $i <= $totalDate; $i++) {
                        array_push($response[$index]['xAxis'], $i);
                        array_push($response[$index]['value'], (int) $subject["revenue$i"]);
                    }
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDailyCloseReasonReportChart($dateRangeStart, $dateRangeEnd, $operatorId, $shortCode, $service, $sorting, $top, $isPercentage=false) {

        $params = array();
        $timestampStart = strtotime($dateRangeStart);
        $timestampEnd = strtotime($dateRangeEnd);

        $dates = array();
        $i = -1;
        while ($timestampEnd >= $timestampStart) {
            $i++;
            $dates[$i]['month'] = date('m', $timestampEnd);
            $dates[$i]['date'] = date('d', $timestampEnd);
            $timestampEnd-=86400;
        }
        sort($dates);
        $totalDate = count($dates);

        $percentage = DIFF_PERCENTAGE / 100;

        if (3 > (int) date('d')) {
            $last2Day = '01';
            $last1Day = date('d');
        } else {
            $last2Day = date("d", strtotime("-2 day", strtotime(date('Y-m-d'))));
            $last1Day = date("d", strtotime("-1 day", strtotime(date('Y-m-d'))));
        }

        //generate sql for percentage difference
        $sqlRange = ", (total" . $dates[$totalDate - 1]['month'] . "_$last2Day+(total" . $dates[$totalDate - 1]['month'] . "_$last2Day*$percentage)) maxRange,
                       (total" . $dates[$totalDate - 1]['month'] . "_$last2Day-(total" . $dates[$totalDate - 1]['month'] . "_$last2Day*$percentage)) minRange";

        // generate sql for daily summary
        $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@ AND MONTH(sumdate)=@month@,abs(total),0)) total@month@_@date@";

        $sqlDynamic = "";
        for ($i = 0; $i < $totalDate; $i++) {
            $sqlDynamic.=str_replace("@date@", $dates[$i]['date'], str_replace("@month@", $dates[$i]['month'], $sqlDynamicTemplate));
        }

        //generate shortcode
        $sqlShortcode = "";
        if (isset($shortCode)) {
            $sqlShortcode = " AND rpt.adn = '$shortCode'";
        }

        //generate serviceName
        $sqlService = "";
        if (isset($service)) {
            $sqlService = " AND rpt.service in ($service)";
        }

        //generate operator
        $sqlOperator = "";
        if (isset($operatorId)) {
            $sqlOperator = " AND rpt.operator in ($operatorId)";
        }

        $sqlSorting = "";
        if (isset($sorting)) {
            if ($sorting == "total") {
                $sqlSorting = "ORDER BY total DESC";
            } else if ($sorting == "yesterday") {
                $sqlSorting = "ORDER BY total" . $dates[$totalDate - 1]['month'] . "_" . date('d', $last1Day) . " DESC";
            }
        }

        $sqlDiff = "";
        if (( $totalDate > 1) && ($isPercentage != false)) {
            $sqlDiff .= "AND (total" . $dates[$totalDate - 1]['month'] . "_$last1Day>maxRange OR total" . $dates[$totalDate - 1]['month'] . "_$last1Day<minRange)";
        }

        //limit
        $sqlTop = "";
        if (isset($top)) {
            $sqlTop = " LIMIT 0, $top";
        }

        $sql = "
        SELECT
            tmp2.*
        FROM
            (
            SELECT
                tmp.*
                $sqlRange
            FROM
                (
                SELECT
                    rpt.operator operatorId, opr.name operator, rpt.closereason, resp.desc description,
                    SUM(abs(total)) total
                    $sqlDynamic
                FROM
                    rpt_creason rpt LEFT JOIN $this->db_xmp.operator opr ON (rpt.operator=opr.id)
                    LEFT JOIN tbl_response resp ON (opr.name=resp.opr AND rpt.closereason=resp.code)
                WHERE
                    rpt.sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                    $sqlShortcode
                    $sqlService
                    $sqlOperator
                GROUP BY
                	rpt.closereason,
                    rpt.operator 
                ORDER BY
                	total DESC                   
                    )tmp
                )tmp2
        WHERE
            1=1
        $sqlDiff
        $sqlSorting
        $sqlTop
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);

        if ($query != FALSE) {
            $response = array();
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $index => $subject) {
                    $response[$index]['label'] = $subject['operator'] . '_' . $subject["closereason"];
                    $response[$index]['value'] = array();
                    $response[$index]['xAxis'] = array();
                    //create final object response
                    for ($i = 0; $i < $totalDate; $i++) {
                        $counter = $i;
                        array_push($response[$index]['xAxis'], $dates[$i]['date']);
                        array_push($response[$index]['value'], (int) $subject["total" . $dates[$i]['month'] . "_" . $dates[$i]['date']]);
                    }
                }
                return $response;
            } else {
                return false;
            }
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getAllDashboard($userId) {
        $params = array();

        $sql = "
        SELECT
            *
        FROM
            tbl_admin_dashboard
        WHERE
            userId = $userId
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);
        if ($query != FALSE) {
            return $query->result_array();
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDashboard($userId, $priority) {
        $params = array();

        $sql = "
        SELECT
            *
        FROM
            tbl_admin_dashboard
        WHERE
            userId = $userId
        AND 
            priority = $priority
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);
        if ($query != FALSE) {
            $result = $query->result_array();
            return $result[0];
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDashboardIdByIndex($userId, $priority) {
        $params = array();

        $sql = "
        SELECT
            id
        FROM
            tbl_admin_dashboard
        WHERE
            userId = $userId
        AND 
            priority = $priority
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);
        if ($query != FALSE) {
            $result = $query->result_array();
            return $result[0]['id'];
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function swapDashboard($id, $index) {
        $params = array();

        $sql = "
        UPDATE
            tbl_admin_dashboard
        SET
            priority = $index
        WHERE
            id = $id
        ";

        write_log('debug', "SQL Executed: $sql - " . print_r($params, true));
        $query = $this->db->query($sql, $params);
        if ($query != FALSE) {
            return true;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function addDashboard($userId, $priority, $param) {
        $sql = "
        INSERT INTO
            tbl_admin_dashboard
            (userId, priority, params)
        VALUES 
            ($userId, $priority, '$param')
        ";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);
        if ($query != FALSE) {
            return $this->db->insert_id();
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function modifyDashboard($param, $id) {
        $sql = "
        UPDATE
            tbl_admin_dashboard
        SET
            params='$param'
        WHERE
            id=$id
        ";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);
        if ($query != FALSE) {
            return true;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function deleteDashboard($id) {
        $sql = "
        DELETE FROM
            tbl_admin_dashboard
        WHERE
            id=$id
        ";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);
        if ($query != FALSE) {
            return true;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getTopServiceHighSubscriber($period, $shortCode='', $operatorId='') {
        $sqlShortCode = "";
        if ($shortCode != '') {
            $sqlShortCode = " AND a.sdc='$shortCode'";
        }

        $sqlOperator = "";
        if ($operatorId != '') {
            $sqlOperator = " AND b.id='$operatorId'";
        }

        list($year, $month) = explode('-', $period);
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $dateRangeEnd = "$year-$month-$totalDate";

        $sql = "
    		SELECT
    			a.service,
    			SUM(a.amount) total 
    		FROM
    			rpt_users a
    		LEFT JOIN
    			$this->db_xmp.operator b
    		ON
    			a.operator = b.id
    		WHERE
    			a.tgl = '$dateRangeEnd'
    		AND 
    			a.status=1
    			$sqlShortCode
    			$sqlOperator
    		GROUP BY 
    			a.service
    		ORDER BY 
    			total DESC
    		LIMIT 10
    	";

        write_log('debug', "SQL Executed: $sql");
        $query = $this->db->query($sql);

        if (!is_object($query)) {
            throw new Exception('error on query.');
        }

        if ($query->num_rows() == 0) {
            return false;
        } else {
            $response = array();
            foreach ($query->result_array() as $row) {
                $response[] = array(
                    'label' => $row["service"],
                    'xAxis' => $row["service"],
                    'value' => (int) $row["total"]
                );
            }
            return $response;
        }
    }

}


