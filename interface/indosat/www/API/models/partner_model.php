<?php
class partner_model extends CI_Model
{
    public function signIn($signInUsername, $signInPassword) {
        $password = md5($signInPassword);

        $adminSql = "
            SELECT
                *
            FROM
                data_admin
            WHERE
                username = '$signInUsername'
            AND
                password = '$password'";

        write_log('debug', 'signIn SQL :: ' . toString($adminSql));
        $adminQuery = $this->db->query($adminSql);

        if (false != $adminQuery) {
            // If not in admin table
            if (1 > $adminQuery->num_rows()) {
                $partnerSql = "
                    SELECT
                        *
                    FROM
                        data_partner
                    WHERE
                        username = '$signInUsername'
                    AND
                        password = '$password'
                    AND
                        has_access = '1'";

                write_log('debug', 'signIn SQL :: ' . toString($partnerSql));
                $partnerQuery = $this->db->query($partnerSql);

                if (false != $partnerQuery) {
                    // not in partner is as well
                    if (1 > $partnerQuery->num_rows()) {
                        return array(
                            0 => 0,
                            1 => null
                        );
                    }
                    else {
                        $partner = $partnerQuery->result_array();

                        $privilegeList = $this->getPartnerPrivilegeList($partner[0]['id']);

                        $data = array(
                            'id' => $partner[0]['id'],
                            'username' => $signInUsername,
                            'type' => 'partner',
                            'privilege' => $privilegeList
                        );

                        $lastSignInSql = "
                            UPDATE
                                data_partner
                            SET
                                last_sign_in = NOW()
                            WHERE
                                id = " . $data['id'];
                        write_log('debug', 'updateLastsignIn :: ' . toString($lastSignInSql));
                        $this->db->query($lastSignInSql);

                        // IN PARTNER TABLE
                        return array(
                            0 => 1,
                            1 => $data
                        );
                    }
                }
                else {
                    // FAIL QUERYING PARTNER
                    return false;
                }
            }
            else {
                $admin = $adminQuery->result_array();
                $data = array(
                    'id' => $admin[0]['id'],
                    'username' => $signInUsername,
                    'type' => 'admin'
                );

                $lastSignInSql = "
                    UPDATE
                        data_admin
                    SET
                        last_sign_in = NOW()
                    WHERE
                        id = " . $data['id'];
                write_log('debug', 'updateLastsignIn :: ' . toString($lastSignInSql));
                $this->db->query($lastSignInSql);

                // IN ADMIN TABLE
                return array(
                    0 => 1,
                    1 => $data
                );
            }
        }
        else {
            // FAIL QUERYING ADMIN
            return false;
        }
    }

    public function partnerAdd($partnerUsername, $partnerPassword, $sharing, $hasAccess, $privilegeList) {
        $success = true;
        $username = $partnerUsername;
        $password = md5($partnerPassword);

        $sql = sprintf("
            INSERT INTO
                data_partner
            VALUES
                (0, '%s', '%s', %d, '%s', '', 'admin', NOW(), '', '')",
                $this->db->escape_str($username),
                $this->db->escape_str($password),
                $this->db->escape_str($sharing),
                $this->db->escape_str($hasAccess)
        );

        write_log('debug', 'addPartner :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            $result = $this->getPartnerIdByUsername($username);
            $partnerId = $result[1];

            $privilegeList = explode(',', $privilegeList);
            foreach ($privilegeList AS $key => $value) {
                if (false === $this->addPrivilege($partnerId, $value)) {
                    $success = false;
                }
            }
        }

        return array(
            0 => 0,
            1 => $success
        );
    }

    public function addPrivilege($partnerId, $privilege) {
        $sql = sprintf("
            INSERT INTO
                data_partner_access
            VALUES
                (%d, '%s')",
            $partnerId,
            $this->db->escape_str($privilege)
        );

        write_log('debug', 'addPrivilege :: ' . toString($sql));
        return $this->db->query($sql);
    }

    private function getContentFilter($partnerId,$pricingType){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " AND partner_id=$partnerId";
        }

        $sqlPricing = "";
        if($pricingType){
            $sqlPricing = " AND pricing_type='$pricingType'";
        }

        // make mapping_type owner in first position
        $sqlOrder = " ORDER BY mapping_type DESC";

        $sql = "
            SELECT
                a.id,
                a.content_id,
                a.mapping_type,
                b.key,
                b.type
            FROM
                data_partner_content a
            LEFT JOIN
                data_partner_content_filter b
            ON
                a.id = b.partner_content_id
            WHERE
                1=1
            $sqlPartner
            $sqlPricing
            $sqlOrder
        ";

        write_log('debug','getContentFilter :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    $map 		= $row['mapping_type'];
                    $id	 		= $row['id'];
                    $contentId	= $row['content_id'];
                    if($row['key'] != null && $row['type'] != null){
                        $tmp[]= array('key' => $row['key'], 'type' => $row['type']);
                    }
                    else{
                        $tmp = false;
                    }
                }

                $param[$map][] = array(
                    'id' 		=> $id,
                    'contentId' => $contentId,
                    'filter'	=> $tmp
                );
            }
            return $param;
        }
        else{
            return false;
        }
    }

    private function getContentRatio($partnerId,$pricingType){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " AND partner_id=$partnerId";
        }

        $sqlPricing = "";
        if($pricingType){
            $sqlPricing = " AND pricing_type='$pricingType'";
        }
        // make mapping_type owner in first position
        $sqlOrder = " ORDER BY mapping_type DESC";

        $sql = "
            SELECT
                a.id,
                a.content_id,
                a.mapping_type,
                c.start_time,
                c.end_time,
                c.ratio
            FROM
                data_partner_content a
            LEFT JOIN
                data_ratio_content c
            ON
                a.id = c.partner_content_id
            WHERE
                1=1
            $sqlPartner
            $sqlPricing
            $sqlOrder
        ";

        write_log('debug','getContentRatio :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    if($row['start_time'] != null && $row['end_time'] != null && $row['ratio'] != null){
                        $tmp[]= array(
                            'startTime' => $row['start_time'],
                            'endTime' 	=> $row['end_time'],
                            'ratio'		=> ($row['ratio']!=0)?((int)$row['ratio']/100):0
                        );
                    }
                    else{
                        $tmp = false;
                    }
                }

                $param[$row['id']] = $tmp;
            }
            return $param;
        }
        else{
            return false;
        }
    }

    private function getContentPublisherFilter($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " AND partner_id=$partnerId";
        }

        // make mapping_type owner in first position
        $sqlOrder = " ORDER BY mapping_type DESC";

        $sql = "
            SELECT
                a.id,
                a.content_id,
                a.mapping_type,
                b.key,
                b.type
            FROM
                data_partner_publisher a
            LEFT JOIN
                data_partner_publisher_filter b
            ON
                a.id = b.partner_publisher_id
            WHERE
                1=1
            $sqlPartner
            $sqlOrder
        ";

        write_log('debug','getContentPublisherFilter :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    $map 		= $row['mapping_type'];
                    $id	 		= $row['id'];
                    $contentId	= $row['content_id'];
                    if($row['key'] != null && $row['type'] != null){
                        $tmp[]= array('key' => $row['key'], 'type' => $row['type']);
                    }
                    else{
                        $tmp = false;
                    }
                }

                $param[$map][] = array(
                    'id' 		=> $id,
                    'contentId' => $contentId,
                    'filter'	=> $tmp
                );
            }
            return $param;
        }
        else{
            return false;
        }
    }

    private function getContentPublisherRatio($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " AND partner_id=$partnerId";
        }

        // make mapping_type owner in first position
        $sqlOrder = " ORDER BY mapping_type DESC";

        $sql = "
            SELECT
                a.id,
                a.content_id,
                a.mapping_type,
                c.start_time,
                c.end_time,
                c.ratio
            FROM
                data_partner_publisher a
            LEFT JOIN
                data_ratio_publisher c
            ON
                a.id = c.partner_publisher_id
            WHERE
                1=1
            $sqlPartner
            $sqlOrder
        ";

        write_log('debug','getContentPublisherRatio :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    if($row['start_time'] != null && $row['end_time'] != null && $row['ratio'] != null){
                        $tmp[]= array(
                            'startTime' => $row['start_time'],
                            'endTime' 	=> $row['end_time'],
                            'ratio'		=> ($row['ratio']!=0)?((int)$row['ratio']/100):0
                        );
                    }
                    else{
                        $tmp = false;
                    }
                }

                $param[$row['id']] = $tmp;
            }
            return $param;
        }
        else{
            return false;
        }
    }

    public function getPremiumDownloadReport($partnerId, $year, $month, $contentType, $searchPattern, $startFrom, $limit) {
        $params = array();

        $sqlSearch="";
        if (isset($searchPattern)) {
            $sqlSearch = " AND title LIKE '%$searchPattern%'";
        }

        $sqlLimit="";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " limit $startFrom, $limit";
        } else  if (isset($limit)) {
            $sqlLimit = " limit $limit";
        }

        $sqlContentType = "";
        if($contentType){
            if(is_array($contentType)){
                $sqlContentType = sprintf(" type in (%s)",implode(',',$contentType));
            }
            else{
                $sqlContentType = " AND type='$contentType'";
            }
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        $contentOwnerTemplate = "
            SELECT
                b.content_type as type,
                content_code as code,
                content_title as title
                @sqlDynamic@
            FROM
                tbl_content_dl a
            LEFT JOIN
                data_content_code_mapping b
            ON
                SUBSTRING(a.content_code,1,1) = b.prefix
            WHERE
                @sqlFilter@
        ";

        $contentReportTemplate = "
            SELECT
                ctype as type,
                code,
                content_title as title
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
            AND serviceid != 'FREE'
                @sqlFilter@
            GROUP BY code
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentFilter($partnerId,'premium');
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getContentRatio($partnerId,'premium');
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        write_log('info', 'getting contentlist.');
        if($filters){
            $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_content id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@,0 as revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " $filterFieldContent='$contentId'";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', (ABS(total)*@ratio@)*price, 0)) revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ",$sqlContent[0]),implode(" UNION ",$sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ",$sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ",$sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlFlooring		= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            $sqlFlooringTemplate= ",floor(sent@date@),floor(delivered@date@),floor(revenue@date@)";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlFlooring .= str_replace('@date@',$i,$sqlFlooringTemplate);
                $sqlSentTotal[] 	 = "sent$i";
                $sqlDeliveredTotal[] = "delivered$i";
                $sqlRevenueTotal[] 	 = "revenue$i";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    *
                    $sqlFlooring
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as tbl
                WHERE
                    1=1
                    $sqlContentType
                    $sqlSearch
                ORDER BY
                    totalSent DESC
            ";

            $sqlComplete = "
                $sql
                $sqlLimit
            ";

            $totalRecord = 0;

            if (!empty($sqlLimit)) {
                //write_log('debug',"SQL Executed: $sql - ".print_r($params, true));
                write_log('debug', 'getPremiumDownloadReport :: ' . toString($sql));
                $query = $this->db->query($sql, $params);

                if($query != FALSE){
                    $totalRecord = $query->num_rows();
                } else {
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                }
            }

            //write_log('debug',"SQL Executed: $sqlComplete - ".print_r($params, true));
            write_log('debug', 'getPremiumDownloadReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete, $params);
            if($query != FALSE){
                if (empty($sqlLimit)) {
                    $totalRecord = $query->num_rows();
                }

                $grandTotal = array(
                    'type' 				=> 'total',
                    'code' 				=> 'total',
                    'title'				=> 'total',
                    'totalSent' 		=> 0,
                    'totalDelivered' 	=> 0,
                    'totalRevenue'		=> 0
                );
                for ($i=1;$i<=$totalDate;$i++) {
                    $grandTotal['daily'][$i] = array(
                        'sent' 		=> 0,
                        'delivered' => 0,
                        'revenue'	=> 0
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

                    $grandTotal['totalSent'] += $dl['totalSent'];
                    $grandTotal['totalDelivered'] += $dl['totalDelivered'];
                    $grandTotal['totalRevenue'] += $dl['totalRevenue'];

                    $subjectResponse['daily'] = array();
                    for ($i=1;$i<=$totalDate;$i++) {
                        $daily = array();
                        $daily['sent'] 		= $dl["sent$i"];
                        $daily['delivered'] = $dl["delivered$i"];
                        $daily['revenue'] 	= $dl["revenue$i"];

                        $before = (1 < $i) ? $i - 1 : 1;

                        if ($dl["revenue$i"] < $dl["revenue$before"]) {
                            $daily['color'] = 'background:#f00;';
                        }
                        else if ($dl["revenue$i"] > $dl["revenue$before"]) {
                            $daily['color'] = 'background:#0f0;';
                        }
                        else {
                            $daily['color'] = 'background:#ccc;';
                        }

                        $subjectResponse['daily'][$i] = $daily;
                        $grandTotal['daily'][$i]['sent'] += $dl["sent$i"];
                        $grandTotal['daily'][$i]['delivered'] += $dl["delivered$i"];
                        $grandTotal['daily'][$i]['revenue'] += $dl["revenue$i"];

                        if ($grandTotal['daily'][$before]['revenue'] < $grandTotal['daily'][$i]['revenue']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#0f0;';
                        }
                        else if ($grandTotal['daily'][$before]['revenue'] > $grandTotal['daily'][$i]['revenue']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#f00;';
                        }
                        else {
                            $grandTotal['daily'][$i]['color'] = 'background:#ccc;';
                        }
                    }
                    $response[]=$subjectResponse;
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
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function getFreeDownloadReport($partnerId, $year, $month, $contentType, $searchPattern, $startFrom, $limit){
        $params = array();

        $sqlSearch="";
        if (isset($searchPattern)) {
            $sqlSearch = " AND title LIKE '%$searchPattern%'";
        }

        $sqlLimit="";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " limit $startFrom, $limit";
        } else  if (isset($limit)) {
            $sqlLimit = " limit $limit";
        }

        $sqlContentType = "";
        if($contentType){
            if(is_array($contentType)){
                $sqlContentType = sprintf(" type in (%s)",implode(',',$contentType));
            }
            else{
                $sqlContentType = " AND type='$contentType'";
            }
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        $contentOwnerTemplate = "
            SELECT
                b.content_type as type,
                content_code as code,
                content_title as title
                @sqlDynamic@
            FROM
                tbl_content_dl a
            LEFT JOIN
                data_content_code_mapping b
            ON SUBSTRING(a.content_code,1,1) = b.prefix
            WHERE
                @sqlFilter@
        ";

        $contentReportTemplate = "
            SELECT
                ctype as type,
                code,
                content_title as title
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
                AND serviceid = 'FREE'
                @sqlFilter@
            GROUP BY code
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentFilter($partnerId,'free');
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getContentRatio($partnerId,'free');
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        write_log('info', 'getting contentlist.');
        if($filters){
        $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_content id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@,0 as revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " $filterFieldContent='$contentId'";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', (ABS(total)*@ratio@)*price, 0)) revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ", $sqlContent[0]),implode(" UNION ", $sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ", $sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ", $sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlFlooring		= "";
            $sqlDeliveredTotal 	= array();
            $sqlFlooringTemplate= ",floor(sent@date@),floor(delivered@date@),floor(revenue@date@)";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlFlooring .= str_replace('@date@',$i,$sqlFlooringTemplate);
                $sqlDeliveredTotal[] = "delivered$i";
            }
            $sqlTotalDynamic = sprintf(", %s as totalDelivered",
                implode("+",$sqlDeliveredTotal)
            );

            $sql = "
                SELECT
                    *
                    $sqlFlooring
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as tbl
                WHERE
                    1=1
                    $sqlContentType
                    $sqlSearch
                ORDER BY
                    totalDelivered DESC
            ";

            $sqlComplete = "
                $sql
                $sqlLimit
            ";

            $totalRecord = 0;

            if (!empty($sqlLimit)) {
                //write_log('debug',"SQL Executed: $sql - ".print_r($params, true));
                write_log('debug', 'getFreeDownloadReport :: ' . toString($sql));
                $query = $this->db->query($sql, $params);
                if($query != FALSE){
                    $totalRecord = $query->num_rows();
                } else {
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                }
            }

            //write_log('debug',"SQL Executed: $sqlComplete - ".print_r($params, true));
            write_log('debug', 'getFreeDownloadReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete, $params);
            if($query != FALSE){
                if (empty($sqlLimit)) {
                    $totalRecord = $query->num_rows();
                }

                $grandTotal = array(
                    'type' 				=> 'total',
                    'code' 				=> 'total',
                    'title'				=> 'total',
                    'totalDelivered' 	=> 0
                );
                for ($i=1;$i<=$totalDate;$i++) {
                    $grandTotal['daily'][$i] = array('delivered' => 0, 'color' => '');
                }
                foreach ($query->result_array() as $dl) {
                    $subjectResponse = array();
                    $subjectResponse['type'] = $dl['type'];
                    $subjectResponse['code'] = $dl['code'];
                    $subjectResponse['title'] = $dl['title'];
                    $subjectResponse['totalDelivered'] = $dl['totalDelivered'];

                    $grandTotal['totalDelivered'] += $dl['totalDelivered'];

                    $subjectResponse['daily'] = array();
                    for ($i=1;$i<=$totalDate;$i++) {
                        $before = (1 < $i) ? $i - 1 : 1;
                        $daily = array();

                        $subjectResponse['daily'][$i]['delivered'] = $dl["delivered$i"];

                        if ($dl["delivered$i"] > $dl["delivered$before"]) {
                            $subjectResponse['daily'][$i]['color'] = 'background:#0f0;';
                        }
                        else if ($dl["delivered$i"] < $dl["delivered$before"]) {
                            $subjectResponse['daily'][$i]['color'] = 'background:#f00;';
                        }
                        else {
                            $subjectResponse['daily'][$i]['color'] = 'background:#ccc;';
                        }

                        $grandTotal['daily'][$i]['delivered'] += $dl["delivered$i"];

                        if ($grandTotal['daily'][$i]['delivered'] > $grandTotal['daily'][$before]['delivered']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#0f0;';
                        }
                        else if ($grandTotal['daily'][$i]['delivered'] > $grandTotal['daily'][$before]['delivered']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#f00;';
                        }
                        else {
                            $grandTotal['daily'][$i]['color'] = 'background:#ccc;';
                        }
                    }
                    $response[]=$subjectResponse;
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
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function getPublisherDownloadReport($partnerId, $year, $month, $contentType, $searchPattern, $startFrom, $limit, $orderField, $order){
        $params = array();

        $sqlSearch="";
        if (isset($searchPattern)) {
            $sqlSearch = " AND title LIKE '%$searchPattern%'";
        }

        $sqlOrder="";
        if (isset($orderField) && isset($order)) {
            $sqlOrder = " ORDER BY $orderField $order";
        }
        else{
            $sqlOrder = " ORDER BY totalSent DESC";
        }

        $sqlLimit="";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " limit $startFrom, $limit";
        } else  if (isset($limit)) {
            $sqlLimit = " limit $limit";
        }

        $sqlContentType = "";
        if($contentType){
            if(is_array($contentType)){
                $sqlContentType = sprintf(" type in (%s)",implode(',',$contentType));
            }
            else{
                $sqlContentType = " AND type='$contentType'";
            }
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        $contentOwnerTemplate = "
            SELECT
                b.content_type as type,
                content_code as code,
                content_title as title
                @sqlDynamic@
            FROM
                tbl_content_dl a
            LEFT JOIN
                data_content_code_mapping b
            ON
                SUBSTRING(a.content_code,1,1) = b.prefix
            WHERE
                @sqlFilter@
        ";

        $contentReportTemplate = "
            SELECT
                ctype as type,
                code,
                content_title as title
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
                @sqlFilter@
            GROUP BY code
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentPublisherFilter($partnerId);
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getContentPublisherRatio($partnerId);
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        write_log('info', 'getting contentlist.');
        if($filters){
            $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_content id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " $filterFieldContent='$contentId'";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ",$sqlContent[0]),implode(" UNION ",$sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ",$sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ",$sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code
            ";

            // calculate revenue
            $sqlRevenueDynamic = "";
            $sqlDynamicTemplate = ",floor(sent@date@),floor(delivered@date@),floor(delivered@date@*price) as revenue@date@";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlRevenueDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
            }

            $sqlRevenue = "
                SELECT
                    *
                FROM
                (
                    SELECT
                        tbl.*,
                        if(b.price IS NULL,0,b.price) as price
                        $sqlRevenueDynamic
                    FROM
                        ($sqlMerge) as tbl
                    LEFT JOIN
                        data_content_price b
                    ON
                        if(tbl.code = b.content_code,1,tbl.code like REPLACE(b.content_code,'*','%'))
                    ORDER BY
                        b.content_code DESC
                ) as tbl
                GROUP BY code
            ";


            //get total
            $sqlTotalDynamic	= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlSentTotal[] 	 = "sent$i";
                $sqlDeliveredTotal[] = "delivered$i";
                $sqlRevenueTotal[] 	 = "revenue$i";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    *
                    $sqlTotalDynamic
                FROM
                    ($sqlRevenue) as tbl
                WHERE
                    1=1
                    $sqlContentType
                    $sqlSearch
                    $sqlOrder
            ";

            $sqlComplete = "
                $sql
                $sqlLimit
            ";

            $totalRecord = 0;

            if (!empty($sqlLimit)) {
                //write_log('debug',"SQL Executed: $sql - ".print_r($params, true));
                write_log('debug','getPublisherDownloadReport :: ' . toString($sql));
                $query = $this->db->query($sql, $params);
                if($query != FALSE){
                    $totalRecord = $query->num_rows();
                } else {
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                }
            }

            //write_log('debug',"SQL Executed: $sqlComplete - ".print_r($params, true));
            write_log('debug','getPublisherDownloadReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete, $params);
            if($query != FALSE){
                if (empty($sqlLimit)) {
                    $totalRecord = $query->num_rows();
                }

                $grandTotal = array(
                    'type' 				=> 'total',
                    'code' 				=> 'total',
                    'title'				=> 'total',
                    'price'				=> '0',
                    'totalSent' 		=> 0,
                    'totalDelivered' 	=> 0,
                    'totalRevenue'		=> 0
                );
                for ($i=1;$i<=$totalDate;$i++) {
                    $grandTotal['daily'][$i] = array(
                        'sent' 		=> 0,
                        'delivered' => 0,
                        'revenue'	=> 0
                    );
                }
                foreach ($query->result_array() as $dl) {
                    $subjectResponse = array();
                    $subjectResponse['type'] = $dl['type'];
                    $subjectResponse['code'] = $dl['code'];
                    $subjectResponse['title'] = $dl['title'];
                    $subjectResponse['price'] = $dl['price'];
                    $subjectResponse['totalSent'] = $dl['totalSent'];
                    $subjectResponse['totalDelivered'] = $dl['totalDelivered'];
                    $subjectResponse['totalRevenue'] = $dl['totalRevenue'];

                    $grandTotal['totalSent'] += $dl['totalSent'];
                    $grandTotal['totalDelivered'] += $dl['totalDelivered'];
                    $grandTotal['totalRevenue'] += $dl['totalRevenue'];

                    $subjectResponse['daily'] = array();
                    for ($i=1;$i<=$totalDate;$i++) {
                        $daily = array();
                        $daily['sent'] 		= $dl["sent$i"];
                        $daily['delivered'] = $dl["delivered$i"];
                        $daily['revenue'] 	= $dl["revenue$i"];

                        $before = (1 < $i) ? $i - 1 : 1;

                        if ($dl["revenue$i"] < $dl["revenue$before"]) {
                            $daily['color'] = 'background:#f00;';
                        }
                        else if ($dl["revenue$i"] > $dl["revenue$before"]) {
                            $daily['color'] = 'background:#0f0;';
                        }
                        else {
                            $daily['color'] = 'background:#ccc;';
                        }

                        $subjectResponse['daily'][$i] = $daily;

                        $grandTotal['daily'][$i]['sent'] 		+= $dl["sent$i"];
                        $grandTotal['daily'][$i]['delivered'] 	+= $dl["delivered$i"];
                        $grandTotal['daily'][$i]['revenue'] 	+= $dl["revenue$i"];

                        if ($grandTotal['daily'][$before]['revenue'] < $grandTotal['daily'][$i]['revenue']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#0f0;';
                        }
                        else if ($grandTotal['daily'][$before]['revenue'] > $grandTotal['daily'][$i]['revenue']) {
                            $grandTotal['daily'][$i]['color'] = 'background:#f00;';
                        }
                        else {
                            $grandTotal['daily'][$i]['color'] = 'background:#ccc;';
                        }
                    }
                    $response[]=$subjectResponse;
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
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function partnerGetList($searchPattern, $startFrom, $limit) {
        $searchUsername = ' WHERE username LIKE \'%' . $searchPattern . '%\' ';

        $sql = "
            SELECT
                *,
                (
                    SELECT
                        COUNT(*)
                    FROM
                        data_partner_service
                    WHERE
                        data_partner_service.partner_id = data_partner.id
                ) totalService,
                (
                    SELECT
                        COUNT(*)
                    FROM
                        data_partner_content
                    WHERE
                        data_partner_content.partner_id = data_partner.id
                ) totalContent,
                (
                    SELECT
                        COUNT(*)
                    FROM
                        data_partner_publisher
                    WHERE
                        data_partner_publisher.partner_id = data_partner.id
                ) totalPublisher
            FROM
                data_partner
                $searchUsername
            ORDER BY
                id DESC
            LIMIT
                $startFrom, $limit";

        write_log('debug', 'partnerGetList :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $partnerList = $query->result_array();

                $sqlTotal = "
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner
                        $searchUsername";

                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $partnerList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for partner list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function addServicePermission($partnerId, $shortCode, $serviceId) {
        $sql = sprintf("
            INSERT INTO
                data_partner_service
            VALUES
                (0, %d, '%s', '%s', 'admin', NOW(), '', '')",
            $partnerId,
            $this->db->escape_str($shortCode),
            $this->db->escape_str($serviceId)
        );

        write_log('debug', 'addServicePermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            $getIdSql = sprintf("
                SELECT
                    id
                FROM
                    data_partner_service
                WHERE
                    partner_id = %d
                    AND service_shortcode = '%s'
                    AND service_name = '%s'
                ORDER BY
                    id DESC
                LIMIT 1",
                $partnerId,
                $this->db->escape_str($shortCode),
                $this->db->escape_str($serviceId)
            );

            write_log('debug', 'addServicePermission :: ' . toString($getIdSql));
            $getIdQuery = $this->db->query($getIdSql);

            if (false !== $getIdQuery) {
                if (0 < $getIdQuery->num_rows()) {
                    $service = $getIdQuery->result_array();
                    return $service[0]['id'];
                }
                else {
                    return null;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    public function addServiceFilter($partnerServiceId, $key, $type) {
        $sql = sprintf("
            INSERT INTO
                data_partner_service_filter
            VALUES
                (0, %d, '%s', '%s', 'admin', NOW(), '', '')",
            $partnerServiceId,
            $this->db->escape_str($key),
            $this->db->escape_str($type)
        );

        write_log('debug', 'addServiceFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function addServiceRatio($partnerServiceId, $startDate, $endDate, $ratio) {
        $sql = sprintf("
            INSERT INTO
                data_ratio_service
            VALUES
                (0, %d, '%s', '%s', %d)",
            $partnerServiceId,
            $this->db->escape_str($startDate),
            $this->db->escape_str($endDate),
            $this->db->escape_str($ratio)
        );

        write_log('debug', 'addServiceRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getServicePermission($partnerId, $searchPattern, $startFrom, $limit) {
        $sql = sprintf("
            SELECT
                svc.*, op.operator
            FROM
                (
                    SELECT
                        ps.id, ps.partner_id, ps.service_shortcode, ps.service_name,
                        psf.id AS filter_id, psf.key, psf.type,
                        rs.id AS ratio_id, rs.start_time, rs.end_time, rs.ratio
                    FROM
                        data_partner_service ps
                    LEFT JOIN
                        data_partner_service_filter psf
                    ON
                        ps.id = psf.partner_service_id
                    LEFT JOIN
                        data_ratio_service rs
                    ON
                        ps.id = rs.partner_service_id
                    WHERE
                        ps.partner_id = %d
                        AND
                        ps.id IN
                        (
                            SELECT *
                            FROM
                            (
                                SELECT id
                                FROM data_partner_service
                                WHERE partner_id = %d
                                LIMIT %d, %d
                            ) partner_service_list
                        )
                    ORDER BY
                    ps.id DESC
                ) svc
            LEFT JOIN
                tbl_operator op
            ON
                (
                    svc.type = 'operator'
                    AND svc.key = op.operator_code
                )
            ",
            $partnerId,
            $partnerId,
            $startFrom,
            $limit
        );

        write_log('debug', 'getServicePermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $serviceList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_service
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getServicePermission :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $serviceList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for service list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getServicePermissionById($partnerId, $partnerServiceId) {
        $sql = sprintf("
            SELECT
                ps.id, ps.partner_id, ps.service_shortcode, ps.service_name,
                psf.id AS filter_id, psf.partner_service_id, psf.key, psf.type,
                op.operator,
                rs.id AS ratio_id, rs.partner_service_id, rs.start_time, rs.end_time, rs.ratio
            FROM
                data_partner_service ps
            LEFT JOIN
                data_partner_service_filter psf
            ON
                ps.id = psf.partner_service_id
            LEFT JOIN
                tbl_operator op
            ON
                (
                    psf.type = 'operator'
                    AND psf.key = op.OPERATOR_CODE
                )
            LEFT JOIN
                data_ratio_service rs
            ON
                ps.id = rs.partner_service_id
            WHERE
                ps.id = %d
                AND ps.partner_id = %d
            ORDER BY
                rs.start_time, rs.end_time",
            $partnerServiceId,
            $partnerId
        );

        write_log('debug', 'getServicePermissionById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $contentList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_content
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getServicePermissionById :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $contentList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function removeAllServiceFilter($partnerServiceId) {
        $sql = sprintf("
            DELETE FROM
                data_partner_service_filter
            WHERE
                partner_service_id = %d",
            $partnerServiceId
        );

        write_log('debug', 'removeAllServiceFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeServiceFilter($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_service_filter
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'removeServiceFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function deleteService($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_service
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'deleteService :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function contentCodeExists($contentCode) {
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM
                tbl_content_dl
            WHERE
                SUBSTRING(content_code, 1, 4) = '%s'",
            $contentCode
        );

        write_log('debug', 'contentCodeExists :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $content = $query->result_array();

                return array(
                    0 => count($content),
                    1 => $content[0]['total']
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content code existence');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function addContentPermission($partnerId, $mappingType, $pricingType, $contentId) {
        $sql = sprintf("
            INSERT INTO
                data_partner_content
            VALUES
                (0, %d, '%s', '%s', '%s', 'admin', NOW(), '', '')",
            $partnerId,
            $this->db->escape_str($contentId),
            $this->db->escape_str($mappingType),
            $this->db->escape_str($pricingType)
        );

        write_log('debug', 'addContentPermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            $getIdSql = sprintf("
                SELECT
                    id
                FROM
                    data_partner_content
                WHERE
                    partner_id = %d
                    AND content_id = '%s'
                    AND mapping_type = '%s'
                    AND pricing_type = '%s'
                ORDER BY
                    id DESC
                LIMIT 1",
                $partnerId,
                $this->db->escape_str($contentId),
                $this->db->escape_str($mappingType),
                $this->db->escape_str($pricingType)
            );

            write_log('debug', 'addContentPermission :: ' . toString($getIdSql));
            $getIdQuery = $this->db->query($getIdSql);

            if (false !== $getIdQuery) {
                if (0 < $getIdQuery->num_rows()) {
                    $content = $getIdQuery->result_array();
                    return $content[0]['id'];
                }
                else {
                    // WE GET NOTHING
                    return null;
                }
            }
            else {
                // FAIL GETTING ID OF NEWLY INSERTED SERVICE
                return false;
            }
        }
        else {
            // FAIL ADDING SERVICE
            return false;
        }
    }

    public function editContentPermission($partnerId, $partnerContentId, $mappingType, $pricingType, $contentId) {
        $sql = sprintf("
            UPDATE
                data_partner_content
            SET
                mapping_type = '%s',
                pricing_type = '%s',
                content_id = '%s'
            WHERE
                id = %d
                AND partner_id = %d",
            $this->db->escape_str($mappingType),
            $this->db->escape_str($pricingType),
            $this->db->escape_str($contentId),
            $partnerContentId,
            $partnerId
        );

        write_log('debug', 'editContentPermission :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function addContentFilter($partnerContentId, $key, $type) {
        $sql = sprintf("
            INSERT INTO
                data_partner_content_filter
            VALUES
                (0, %d, '%s', '%s', 'admin', NOW(), '', '')",
            $partnerContentId,
            $this->db->escape_str($key),
            $this->db->escape_str($type)
        );

        write_log('debug', 'addContentFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeAllContentFilter($partnerContentId) {
        $sql = sprintf("
            DELETE FROM
                data_partner_content_filter
            WHERE
                partner_content_id = %d",
            $partnerContentId
        );

        write_log('debug', 'removeAllContentFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getContentPermission($partnerId, $searchPattern, $startFrom, $limit) {
        $sql = sprintf("
            SELECT
                pc.id, pc.partner_id, pc.content_id, pc.mapping_type, pc.pricing_type,
                pcf.id AS filter_id, pcf.partner_content_id, pcf.key, pcf.type,
                co.o_name AS owner,
                rc.id AS ratio_id, rc.partner_content_id, rc.start_time, rc.end_time, rc.ratio
            FROM
                data_partner_content pc
            LEFT JOIN
                data_partner_content_filter pcf
            ON
                pc.id = pcf.partner_content_id
            LEFT JOIN
                tbl_content_owner co
            ON
                (
                    pc.mapping_type = 'owner'
                    AND pc.content_id = co.id
                )
            LEFT JOIN
                data_ratio_content rc
            ON
                pc.id = rc.partner_content_id
            WHERE
                pc.partner_id = %d
                AND
                pc.id IN
                (
                    SELECT *
                    FROM
                    (
                        SELECT id
                        FROM data_partner_content
                        WHERE partner_id = %d
                        LIMIT %d, %d
                    ) partner_content_list
                )
            ORDER BY
                pc.id DESC",
            $partnerId,
            $partnerId,
            $startFrom,
            $limit
        );

        write_log('debug', 'getContentPermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $contentList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_content
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getContentPermission :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $contentList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getContentPermissionById($partnerId, $partnerContentId) {
        $sql = sprintf("
            SELECT
                pc.id, pc.partner_id, pc.content_id, pc.mapping_type, pc.pricing_type,
                pcf.id AS filter_id, pcf.partner_content_id, pcf.key, pcf.type,
                rc.id AS ratio_id, rc.partner_content_id, rc.start_time, rc.end_time, rc.ratio
            FROM
                data_partner_content pc
            LEFT JOIN
                data_partner_content_filter pcf
            ON
                pc.id = pcf.partner_content_id
            LEFT JOIN
                data_ratio_content rc
            ON
                pc.id = rc.partner_content_id
            WHERE
                pc.id = %d
                AND pc.partner_id = %d
            ORDER BY
                rc.start_time, rc.end_time",
            $partnerContentId,
            $partnerId
        );

        write_log('debug', 'getContentPermissionById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $contentList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_content
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getContentPermissionById :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $contentList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function removeContentFilter($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_content_filter
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'removeContentFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function deleteContent($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_content
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'deleteContent :: ' . toString($sql));
        return $this->db->query($sql);
    }

    private function getServiceFilter($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " partner_id=$partnerId";
        }

        $sql = "
            SELECT
                a.*,
                b.key,
                b.type
            FROM
                data_partner_service a
            LEFT JOIN
                data_partner_service_filter b
            ON
                a.id=b.partner_service_id
            WHERE
            $sqlPartner
        ";

        write_log('debug','getServiceFilter :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    $id			= $row['id'];
                    $shortCode 	= $row['service_shortcode'];
                    $service	= $row['service_name'];
                    if($row['key'] != null && $row['type'] != null){
                        $tmp[]  = array('key' => $row['key'], 'type' => $row['type']);
                    }
                    else{
                        $tmp = false;
                    }
                }
                $param[] = array(
                    'id'		=> $id,
                    'shortCode' => $shortCode,
                    'service'	=> $service,
                    'filter' 	=> $tmp
                );
            }
            return $param;
        }
        else{
            return false;
        }
    }

    private function getServiceRatio($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " AND partner_id=$partnerId";
        }

        $sql = "
            SELECT
                a.id,
                c.start_time,
                c.end_time,
                c.ratio
            FROM
                data_partner_service a
            LEFT JOIN
                data_ratio_service c
            ON
                a.id = c.partner_service_id
            WHERE
                1=1
            $sqlPartner
        ";

        write_log('debug','getServiceRatio :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[$row['id']][] = $row;
            }
            foreach($result as $rows){
                $tmp = array();
                foreach($rows as $row){
                    if($row['start_time'] != null && $row['end_time'] != null && $row['ratio'] != null){
                        $tmp[]= array(
                            'startTime' => $row['start_time'],
                            'endTime' 	=> $row['end_time'],
                            'ratio'		=> ($row['ratio']!=0)?((int)$row['ratio']/100):0
                        );
                    }
                    else{
                        $tmp = false;
                    }
                }

                $param[$row['id']] = $tmp;
            }
            return $param;
        }
        else{
            return false;
        }
    }

    public function getTextDownloadReport($partnerId, $year, $month, $service){
        $params = array();
        $sqlLimit = "";

        $sqlService = "";
        if($service){
            $sqlService = " AND service='$service'";
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        $sqlTemplate = "
            SELECT
                operator,
                service
                @sqlDynamic@
            FROM
                rpt_service2
            WHERE
                operator IS NOT NULL
            AND
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
            AND
                service='@service@'
            AND
                shortcode='@shortCode@'
            @sqlFilter@
            GROUP BY
                operator,service
        ";
        write_log('info', 'getting filters');
        $filters = $this->getServiceFilter($partnerId);
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getServiceRatio($partnerId);
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        if($filters){
            $sqlReport = array();
            foreach ($filters as $row){
                $id = $row['id'];
                // generate sql for daily summary
                $sqlDynamic="";
                $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, floor(ABS(total)*@ratio@), 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND msgstatus='DELIVERED', floor(ABS(total)*@ratio@), 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND msgstatus='DELIVERED', floor(ABS(total)*@ratio@)*gross, 0)) revenue@date@";
                for ($i=1;$i<=$totalDate;$i++) {
                    /* TRICKY */
                    $loopDate = strtotime("$year-$month-$i");
                    $ratioIndex 		= '';
                    $defaultRatioIndex 	= '';
                    foreach($ratio[$id] as $k => $r){
                        if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                            $defaultRatioIndex = $k;
                        }
                        if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                            $r['endTime'] = $dateRangeEnd;
                        }
                        if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                            $ratioIndex = $k;
                        }
                    }

                    if(strlen($ratioIndex) != 0){
                        $sqlDynamic.=str_replace(
                            array("@date@","@ratio@"),
                            array($i,$ratio[$id][$ratioIndex]['ratio']),
                            $sqlDynamicTemplate
                        );
                    }
                    else{
                        $sqlDynamic.=str_replace(
                            array("@date@","@ratio@"),
                            array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                            $sqlDynamicTemplate
                        );
                    }
                }

                $sqlFilter = "";
                if($row['filter']){
                    $filterSubject  = array();
                    $filterOperator = array();
                    $filterSid		= array();
                    $filterPrice	= array();
                    foreach($row['filter'] as $itm){
                        switch($itm['type']){
                            case 'subject':
                                $filterSubject[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'operator':
                                $filterOperator[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'sid':
                                $filterSid[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'price':
                                $filterPrice[] = sprintf("'%s'",$itm['key']);
                                break;
                        }
                    }
                    $pFilter = array();
                    if(count($filterSubject)!=0) $pFilter[] = sprintf("substring_index(substring_index(subject,';',-3),';',1) in (%s)",implode(',', $filterSubject));
                    if(count($filterOperator)!=0)$pFilter[] = sprintf("operator in (%s)",implode(',', $filterOperator));
                    if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                    if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                    $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                }

                $sqlReport[] = str_replace(
                    array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@","@service@","@shortCode@"),
                    array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter,$row['service'],$row['shortCode']),
                    $sqlTemplate
                );
            }

            // combine all query by mapping type with each filters
            $sqlCombine = implode(" UNION ", $sqlReport);

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    operator,service
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlSentTotal[] 	 = "sent$i";
                $sqlDeliveredTotal[] = "delivered$i";
                $sqlRevenueTotal[] 	 = "revenue$i";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    m.*,
                    o.operator as operatorName
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as m
                LEFT JOIN
                    tbl_operator o
                ON
                    m.operator = o.operator_code
                WHERE
                    1=1
                    $sqlService
                ORDER BY
                    operator,totalSent DESC
            ";

            $sqlComplete = "
                $sql
                $sqlLimit
            ";

            $totalRecord = 0;

            if (!empty($sqlLimit)) {
                //write_log('debug',"SQL Executed: $sql - ".print_r($params, true));
                write_log('debug','getTextDownloadReport :: ' . toString($sql));
                $query = $this->db->query($sql, $params);

                if($query != FALSE){
                    $totalRecord = $query->num_rows();
                } else {
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                }
            }

            //write_log('debug',"SQL Executed: $sqlComplete - ".print_r($params, true));
            write_log('debug','getTextDownloadReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete, $params);

            if($query != FALSE){
                if (empty($sqlLimit)) {
                    $totalRecord = $query->num_rows();
                }

                $grandTotal = array(
                    'operator'			=> 'Grand Total',
                    'operatorName'		=> 'Grand Total',
                );

                $response = array();
                foreach ($query->result_array() as $recordSet) {
                    $response[$recordSet['operator']][] = $recordSet;
                }

                $finalResult = array();
                $gtTmp = array();
                foreach($response as $operator => $rows){
                    $result = array();
                    $result['operator'] = $operator;

                    $subtotal = array(
                        'name' 				=> 'TOTAL',
                        'totalSent' 		=> 0,
                        'totalDelivered' 	=> 0,
                        'totalRevenue'		=> 0
                    );
                    for ($i=1;$i<=$totalDate;$i++) {
                        $subtotal['daily'][$i] = array(
                            'sent' 		=> 0,
                            'delivered' => 0,
                            'revenue'	=> 0
                        );
                    }

                    foreach($rows as $recordSet){
                        $operatorName = $recordSet['operatorName'];
                        $service = array();
                        $service['name']			= $recordSet['service'];
                        $service['totalSent']		= $recordSet['totalSent'];
                        $service['totalDelivered']	= $recordSet['totalDelivered'];
                        $service['totalRevenue']	= $recordSet['totalRevenue'];
                        //subtotal
                        $subtotal['totalSent']		+= $recordSet['totalSent'];
                        $subtotal['totalDelivered']	+= $recordSet['totalDelivered'];
                        $subtotal['totalRevenue']	+= $recordSet['totalRevenue'];

                        for ($i=1;$i<=$totalDate;$i++) {
                            $before = (1 < $i) ? $i - 1 : 1;

                            $daily = array();
                            $daily['sent'] 		= $recordSet["sent$i"];
                            $daily['delivered'] = $recordSet["delivered$i"];
                            $daily['revenue'] 	= $recordSet["revenue$i"];

                            @$daily[$i]['sent'] 	+= $row["daily"][$i]["sent"];
                            @$daily[$i]['delivered']+= $row["daily"][$i]["delivered"];
                            @$daily[$i]['revenue'] 	+= $row["daily"][$i]["revenue"];

                            if ($recordSet["revenue$i"] < $recordSet["revenue$before"]) {
                                $daily['color'] = 'background:#f00;';
                            }
                            else if ($recordSet["revenue$i"] > $recordSet["revenue$before"]) {
                                $daily['color'] = 'background:#0f0;';
                            }
                            else {
                                $daily['color'] = 'background:#ccc;';
                            }

                            $service['daily'][$i] = $daily;
                            //subtotal
                            $subtotal['daily'][$i]['sent'] 		+= $recordSet["sent$i"];
                            $subtotal['daily'][$i]['delivered'] += $recordSet["delivered$i"];
                            $subtotal['daily'][$i]['revenue'] 	+= $recordSet["revenue$i"];

                            if ($subtotal['daily'][$before]['revenue'] < $subtotal['daily'][$i]['revenue']) {
                                $subtotal['daily'][$i]['color'] = 'background:#0f0;';
                            }
                            else if ($subtotal['daily'][$before]['revenue'] > $subtotal['daily'][$i]['revenue']) {
                                $subtotal['daily'][$i]['color'] = 'background:#f00;';
                            }
                            else {
                                $subtotal['daily'][$i]['color'] = 'background:#ccc;';
                            }
                        }
                        //grandtotal
                        $gtTmp[$recordSet['service']][] = array(
                            'name' 			=> $recordSet['service'],
                            'totalSent'		=> $recordSet['totalSent'],
                            'totalDelivered'=> $recordSet['totalDelivered'],
                            'totalRevenue'	=> $recordSet['totalRevenue'],
                            'daily'			=> $service['daily']
                        );
                        $result['service'][] = $service;
                    }
                    $result['service'][] = $subtotal;
                    $result['operatorName'] = $operatorName;
                    $finalResult[] = $result;
                }

                //finalize grandtotal
                $subtotal = array(
                        'name' 				=> 'TOTAL',
                        'totalSent' 		=> 0,
                        'totalDelivered' 	=> 0,
                        'totalRevenue'		=> 0
                    );
                    for ($i=1;$i<=$totalDate;$i++) {
                        $subtotal['daily'][$i] = array(
                            'sent' 		=> 0,
                            'delivered' => 0,
                            'revenue'	=> 0
                        );
                    }
                $gtCandidate = array();
                foreach($gtTmp as $rows){
                    $service = array();
                    $daily = array();
                    foreach($rows as $row){
                        $service['name']			= $row['name'];
                        @$service['totalSent']		+= $row['totalSent'];
                        @$service['totalDelivered']	+= $row['totalDelivered'];
                        @$service['totalRevenue']	+= $row['totalRevenue'];
                        //subtotal
                        $subtotal['totalSent']		+= $row['totalSent'];
                        $subtotal['totalDelivered']	+= $row['totalDelivered'];
                        $subtotal['totalRevenue']	+= $row['totalRevenue'];

                        for ($i=1;$i<=$totalDate;$i++) {
                            $before = (1 < $i) ? $i - 1 : 1;

                            @$daily[$i]['sent'] 	+= $row["daily"][$i]["sent"];
                            @$daily[$i]['delivered']+= $row["daily"][$i]["delivered"];
                            @$daily[$i]['revenue'] 	+= $row["daily"][$i]["revenue"];

                            if ($daily[$i]["revenue"] < $daily[$before]["revenue"]) {
                                $daily[$i]['color'] = 'background:#f00;';
                            }
                            else if ($daily[$i]["revenue"] > $daily[$before]["revenue"]) {
                                $daily[$i]['color'] = 'background:#0f0;';
                            }
                            else {
                                $daily[$i]['color'] = 'background:#ccc;';
                            }

                            $service['daily'] = $daily;

                            //subtotal
                            $subtotal['daily'][$i]['sent'] 		+= $row["daily"][$i]["sent"];
                            $subtotal['daily'][$i]['delivered'] += $row["daily"][$i]["delivered"];
                            $subtotal['daily'][$i]['revenue'] 	+= $row["daily"][$i]["revenue"];

                            if ($subtotal['daily'][$before]['revenue'] < $subtotal['daily'][$i]['revenue']) {
                                $subtotal['daily'][$i]['color'] = 'background:#0f0;';
                            }
                            else if ($subtotal['daily'][$before]['revenue'] > $subtotal['daily'][$i]['revenue']) {
                                $subtotal['daily'][$i]['color'] = 'background:#f00;';
                            }
                            else {
                                $subtotal['daily'][$i]['color'] = 'background:#ccc;';
                            }
                        }
                    }
                    $gtCandidate[] = $service;
                }
                $gtCandidate[] = $subtotal;
                $grandTotal['service'] = $gtCandidate;


                array_unshift($finalResult,$grandTotal);

                return array(
                        0 => $totalRecord,
                        1 => $finalResult
                );
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            // no filter defined
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter defined.'));
        }
    }

    public function changePassword($partnerId, $newPassword) {
        $password = md5($newPassword);

        $sql = sprintf("
            UPDATE
                data_partner
            SET
                password = '%s'
            WHERE
                id = %d",
            $this->db->escape_str($password),
            $partnerId
        );

        write_log('debug', 'changePassword :: ' . toString($sql));
        $query = $this->db->query($sql);

        return array(
            0 => 0,
            1 => $query
        );
    }

    public function currentPasswordMatches($partnerId, $oldPassword) {
        $password = md5($oldPassword);

        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM
                data_partner
            WHERE
                id = %d
            AND
                password = '%s'",
            $partnerId,
            $this->db->escape_str($password)
        );

        write_log('debug', 'currentPasswordMatches :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $partner = $query->result_array();

                return array(
                    0 => count($partner),
                    1 => $partner[0]['total']
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for password existence');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getPartnerService($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " partner_id=$partnerId";
        }

        $sql = "
            SELECT
                service_name
            FROM
                data_partner_service
            WHERE
            $sqlPartner
        ";

        write_log('debug','getPartnerService :: ' . toString($sql));
        $query = $this->db->query($sql);

        if($query->num_rows() != 0){
            $result = array();
            foreach($query->result_array() as $row){
                $result[] = $row['service_name'];
            }
            return $result;
        }
        else{
            return false;
        }
    }

    public function getContentOwner() {
        $sql = "
            SELECT
                *
            FROM
                tbl_content_owner";

        write_log('debug', 'getContentOwner :: ' . $sql);
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $owner = $query->result_array();
                return array(
                    0 => 0,
                    1 => $owner
                );
            }
            else {
                write_log('debug', 'No records after running query');
            }
        }
        else {
            write_log('debug', 'Fail getting owner list');
        }

        return array(
            0 => 0,
            1 => $owner
        );
    }

    public function countPartnerContent($partnerId) {
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM
                data_partner_content
            WHERE
                partner_id = %d",
            $partnerId
        );

        write_log('debug', 'countPartnerContent :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $content = $query->result_array();

                return array(
                    0 => count($content),
                    1 => $content[0]['total']
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for total of partner content');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function countPartnerService($partnerId) {
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM
                data_partner_service
            WHERE
                partner_id = %d",
            $partnerId
        );

        write_log('debug', 'countPartnerService :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $service = $query->result_array();

                return array(
                    0 => count($content),
                    1 => $service[0]['total']
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for total of partner service');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function countPartnerPublisher($partnerId) {
        $sql = sprintf("
            SELECT
                COUNT(*) AS total
            FROM
                data_partner_publisher
            WHERE
                partner_id = %d",
            $partnerId
        );

        write_log('debug', 'countPartnerPublisher :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            if (0 < $query->num_rows()) {
                $publisher = $query->result_array();

                return array(
                    0 => count($publisher),
                    1 => $publisher[0]['total']
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for total of partner publisher');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function addPublisherPermission($partnerId, $mappingType, $contentId) {
        $sql = sprintf("
            INSERT INTO
                data_partner_publisher
            VALUES
                (0, %d, '%s', '%s', 'admin', NOW(), '', '')",
            $partnerId,
            $this->db->escape_str($contentId),
            $this->db->escape_str($mappingType)
        );

        write_log('debug', 'addPublisherPermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false !== $query) {
            $getIdSql = sprintf("
                SELECT
                    id
                FROM
                    data_partner_publisher
                WHERE
                    partner_id = %d
                    AND content_id = '%s'
                    AND mapping_type = '%s'
                ORDER BY
                    id DESC
                LIMIT 1",
                $partnerId,
                $this->db->escape_str($contentId),
                $this->db->escape_str($mappingType)
            );

            write_log('debug', 'addPublisherPermission :: ' . toString($getIdSql));
            $getIdQuery = $this->db->query($getIdSql);

            if (false !== $getIdQuery) {
                if (0 < $getIdQuery->num_rows()) {
                    $content = $getIdQuery->result_array();
                    return $content[0]['id'];
                }
                else {
                    // WE GET NOTHING
                    return null;
                }
            }
            else {
                // FAIL GETTING ID OF NEWLY INSERTED SERVICE
                return false;
            }
        }
        else {
            // FAIL ADDING SERVICE
            return false;
        }
    }

    public function addPublisherFilter($partnerPublisherId, $key, $type) {
        $sql = sprintf("
            INSERT INTO
                data_partner_publisher_filter
            VALUES
                (0, %d, '%s', '%s', 'admin', NOW(), '', '')",
            $partnerPublisherId,
            $this->db->escape_str($key),
            $this->db->escape_str($type)
        );

        write_log('debug', 'addPublisherFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function editPublisherPermission($partnerId, $partnerPublisherId, $mappingType, $contentId) {
        $sql = sprintf("
            UPDATE
                data_partner_publisher
            SET
                mapping_type = '%s',
                content_id = '%s'
            WHERE
                id = %d
                AND partner_id = %d",
            $this->db->escape_str($mappingType),
            $this->db->escape_str($contentId),
            $partnerPublisherId,
            $partnerId
        );

        write_log('debug', 'editPublisherPermission :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getPublisherPermission($partnerId, $searchPattern, $startFrom, $limit) {
        $sql = sprintf("
            SELECT
                pp.id, pp.partner_id, pp.content_id, pp.mapping_type,
                ppf.id AS filter_id, ppf.partner_publisher_id, ppf.key, ppf.type,
                co.o_name AS owner,
                rp.id AS ratio_id, rp.partner_publisher_id, rp.start_time, rp.end_time, rp.ratio
            FROM
                data_partner_publisher pp
            LEFT JOIN
                data_partner_publisher_filter ppf
            ON
                pp.id = ppf.partner_publisher_id
            LEFT JOIN
                tbl_content_owner co
            ON
                (
                    pp.mapping_type = 'owner'
                    AND pp.content_id = co.id
                )
            LEFT JOIN
                data_ratio_publisher rp
            ON
                pp.id = rp.partner_publisher_id
            WHERE
                pp.partner_id = %d
                AND
                pp.id IN
                (
                    SELECT *
                    FROM
                    (
                        SELECT id
                        FROM data_partner_content
                        WHERE partner_id = %d
                        LIMIT %d, %d
                    ) partner_content_list
                )
            ORDER BY
                pp.id DESC",
            $partnerId,
            $partnerId,
            $startFrom,
            $limit
        );

        write_log('debug', 'getPublisherPermission :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $publisherList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_publisher
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getPublisherPermission SQL :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $publisherList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function removeAllPublisherFilter($partnerPublisherId) {
        $sql = sprintf("
            DELETE FROM
                data_partner_publisher_filter
            WHERE
                partner_publisher_id = %d",
            $partnerPublisherId
        );

        write_log('debug', 'removeAllPublisherFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getPublisherPermissionById($partnerId, $partnerPublisherId) {
        $sql = sprintf("
            SELECT
                pp.id, pp.partner_id, pp.content_id, pp.mapping_type,
                ppf.id AS filter_id, ppf.partner_publisher_id, ppf.key, ppf.type,
                rp.id AS ratio_id, rp.partner_publisher_id, rp.start_time, rp.end_time, rp.ratio
            FROM
                data_partner_publisher pp
            LEFT JOIN
                data_partner_publisher_filter ppf
            ON
                pp.id = ppf.partner_publisher_id
            LEFT JOIN
                data_ratio_publisher rp
            ON
                pp.id = rp.partner_publisher_id
            WHERE
                pp.id = %d
                AND pp.partner_id = %d
            ORDER BY
                rp.start_time, rp.end_time",
            $partnerPublisherId,
            $partnerId
        );

        write_log('debug', 'getPublisherPermissionById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $publisherList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_partner_publisher
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getPublisherPermissionById :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $publisherList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for publisher list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function removePublisherFilter($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_publisher_filter
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'removePublisherFilter :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function deletePublisher($id) {
        $sql = sprintf("
            DELETE FROM
                data_partner_publisher
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'deletePublisher :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeAccess($id) {
        $sql = sprintf("
            UPDATE
                data_partner
            SET
                has_access = '0'
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'removeAccess :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function grantAccess($id) {
        $sql = sprintf("
            UPDATE
                data_partner
            SET
                has_access = '1'
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'grantAccess :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getPartnerNameById($id) {
        $sql = sprintf("
            SELECT
                username
            FROM
                data_partner
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'getPartnerNameById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $username = $query->result_array();

                return array(
                    0 => 1,
                    1 => $username[0]['username']
                );
            }
            else {
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for username');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getPartnerSharingById($id) {
        $sql = sprintf("
            SELECT
                sharing
            FROM
                data_partner
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'getPartnerSharingById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $sharing = $query->result_array();

                return array(
                    0 => 1,
                    1 => $sharing[0]['sharing']
                );
            }
            else {
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for sharing');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function addContentRatio($partnerContentId, $startDate, $endDate, $ratio) {
        $sql = sprintf("
            INSERT INTO
                data_ratio_content
            VALUES
                (0, %d, '%s', '%s', %d)",
            $partnerContentId,
            $this->db->escape_str($startDate),
            $this->db->escape_str($endDate),
            $this->db->escape_str($ratio)
        );

        write_log('debug', 'addContentRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function addPublisherRatio($partnerPublisherId, $startDate, $endDate, $ratio) {
        $sql = sprintf("
            INSERT INTO
                data_ratio_publisher
            VALUES
                (0, %d, '%s', '%s', %d)",
            $partnerPublisherId,
            $this->db->escape_str($startDate),
            $this->db->escape_str($endDate),
            $this->db->escape_str($ratio)
        );

        write_log('debug', 'addPublisherRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeAllServiceRatio($partnerServiceId) {
        $sql = sprintf("
            DELETE FROM
                data_ratio_service
            WHERE
                partner_service_id = %d",
            $partnerServiceId
        );

        write_log('debug', 'removeAllServiceRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeAllContentRatio($partnerContentId) {
        $sql = sprintf("
            DELETE FROM
                data_ratio_content
            WHERE
                partner_content_id = %d",
            $partnerContentId
        );

        write_log('debug', 'removeAllContentRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function removeAllPublisherRatio($partnerPublisherId) {
        $sql = sprintf("
            DELETE FROM
                data_ratio_publisher
            WHERE
                partner_publisher_id = %d",
            $partnerPublisherId
        );

        write_log('debug', 'removeAllPublisherRatio :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function addPriceMapping($partnerId, $contentCode, $price) {
        $sql = sprintf("
            INSERT INTO
                data_content_price
            VALUES
                (0, %d, '%s', %d)",
            $partnerId,
            $this->db->escape_str($contentCode),
            $this->db->escape_str($price)
        );

        write_log('debug', 'addPriceMapping :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getPriceMapping($partnerId, $searchPattern, $startFrom, $limit) {
        $sql = sprintf("
            SELECT
                *
            FROM
                data_content_price
            WHERE
                partner_id = %d
            ORDER BY
                id DESC
            LIMIT
                %d, %d",
            $partnerId,
            $startFrom,
            $limit
        );

        write_log('debug', 'getPriceMapping :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $priceMappingList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_content_price
                    WHERE
                        partner_id = %d",
                    $partnerId
                );

                write_log('debug', 'getPriceMapping SQL :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $priceMappingList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for price mapping list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function deletePriceMapping($partnerId, $id) {
        $sql = sprintf("
            DELETE FROM
                data_content_price
            WHERE
                id = %d
                AND partner_id = %d",
            $id,
            $partnerId
        );

        write_log('debug', 'deletePriceMapping :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getPriceMappingById($partnerId, $priceMappingId) {
        $sql = sprintf("
            SELECT
                *
            FROM
                data_content_price
            WHERE
                id = %d
                AND partner_id = %d",
            $priceMappingId,
            $partnerId
        );

        write_log('debug', 'getPriceMappingById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                return array(
                    0 => 1,
                    1 => $query->result_array()
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => null
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for publisher list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function editPriceMapping($partnerId, $priceMappingId, $contentCode, $price) {
        $sql = sprintf("
            UPDATE
                data_content_price
            SET
                content_code = %s,
                price = %d
            WHERE
                id = %d
                AND partner_id = %d",
            $this->db->escape_str($contentCode),
            $price,
            $priceMappingId,
            $partnerId
        );

        write_log('debug', 'editPriceMapping :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function addDataReconciliation($shortCode, $operator, $month, $year, $data){
        $sql = "
            INSERT INTO
                data_reconciliation
            (short_code,operator,month,year)
            VALUES
            ('$shortCode', '$operator', '$month', '$year')
        ";

        write_log('debug','addDataReconciliation :: ' . toString($sql));
        if( $this->db->simple_query($sql) ){
            $id = $this->db->insert_id();
            write_log('debug',"Insert ID: $id");

            $totalTrafficInternal 	= 0;
            $totalTrafficOperator 	= 0;
            $totalGrossInternal 	= 0;
            $totalGrossOperator		= 0;
            foreach($data as $row){
                $sqlMapping = "
                    INSERT INTO
                        data_reconciliation_mapping
                    VALUES
                    (null,
                    $id,
                    '{$row['serviceId']}',
                    {$row['trafficInternal']},
                    {$row['trafficOperator']},
                    {$row['grossInternal']},
                    {$row['grossOperator']})
                ";

                //write_log('debug',"SQL Executed: ".toString($sqlMapping));
                write_log('debug','addDataReconciliation :: ' . toString($sqlMapping));
                if( $this->db->simple_query($sqlMapping) ){
                    $totalTrafficInternal += (int)$row['trafficInternal'];
                    $totalTrafficOperator += (int)$row['trafficOperator'];
                    $totalGrossInternal	  += (int)$row['grossInternal'];
                    $totalGrossOperator   += (int)$row['grossOperator'];
                }
                else{
                    // error, rollback!!
                    $this->deleteDataReconciliation($id);
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
//					break;
                }
            }

            $differencePrice = $totalGrossInternal - $totalGrossOperator;
            if(0 < $differencePrice){
                $differencePrice = $differencePrice * -1;
            }
            else{
                $differencePrice = abs($differencePrice);
            }
            $differencePercentage = ($differencePrice * 100) / $totalGrossInternal;
            $partnerPercentage = 100 + $differencePercentage;

            //update summary
            $sqlSummary = "
                UPDATE
                    data_reconciliation
                SET
                    gross_internal=$totalGrossInternal,
                    gross_operator=$totalGrossOperator,
                    difference_price=$differencePrice,
                    difference_percentage=$differencePercentage,
                    partner_percentage=$partnerPercentage
                WHERE
                    id=$id
            ";

            //write_log('debug',"SQL Executed: ".toString($sqlSummary));
            write_log('debug','addDataReconciliation :: ' . toString($sqlSummary));
            if($this->db->simple_query($sqlSummary)){
                return array(
                    'grossInternal' => $totalGrossInternal,
                    'grossOperator'	=> $totalGrossOperator,
                    'differencePrice' => $differencePrice,
                    'differencePercentage' => $differencePercentage
                );
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function updateDataReconciliation($id, $data){
        // remove all mapping
        $sql = "
            DELETE FROM
                data_reconciliation_mapping
            WHERE
                data_reconciliation_id=$id
        ";

        write_log('debug','updateDataReconciliation :: ' . toString($sql));
        if( $this->db->simple_query($sql) ){
            $totalTrafficInternal 	= 0;
            $totalTrafficOperator 	= 0;
            $totalGrossInternal 	= 0;
            $totalGrossOperator		= 0;
            foreach($data as $row){
                $sqlMapping = "
                    INSERT INTO
                        data_reconciliation_mapping
                    VALUES
                    (null,
                    $id,
                    '{$row['serviceId']}',
                    {$row['trafficInternal']},
                    {$row['trafficOperator']},
                    {$row['grossInternal']},
                    {$row['grossOperator']})
                ";

                write_log('debug','updateDataReconciliation :: ' . toString($sqlMapping));
                if( $this->db->simple_query($sqlMapping) ){
                    $totalTrafficInternal += (int)$row['trafficInternal'];
                    $totalTrafficOperator += (int)$row['trafficOperator'];
                    $totalGrossInternal	  += (int)$row['grossInternal'];
                    $totalGrossOperator   += (int)$row['grossOperator'];
                }
                else{
                    // error, rollback!!
                    $this->deleteDataReconciliation($id);
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                    break;
                }
            }

            $differencePrice = $totalGrossInternal - $totalGrossOperator;
            if(0 < $differencePrice){
                write_log('info', 'make minus');
                $differencePrice = $differencePrice * -1;
            }
            else{
                write_log('info', 'make plus');
                $differencePrice = abs($differencePrice);
            }
            $differencePercentage = ($differencePrice * 100) / $totalGrossInternal;
            $partnerPercentage = 100 + $differencePercentage;

            //update summary
            $sqlSummary = "
                UPDATE
                    data_reconciliation
                SET
                    gross_internal=$totalGrossInternal,
                    gross_operator=$totalGrossOperator,
                    difference_price=$differencePrice,
                    difference_percentage=$differencePercentage,
                    partner_percentage=$partnerPercentage
                WHERE
                    id=$id
            ";

            write_log('debug','updateDataReconciliation :: ' . toString($sqlSummary));
            if($this->db->simple_query($sqlSummary)){
                return array(
                    'grossInternal' => $totalGrossInternal,
                    'grossOperator'	=> $totalGrossOperator,
                    'differencePrice' => $differencePrice,
                    'differencePercentage' => $differencePercentage
                );
            }
            else{
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function deleteDataReconciliation($id){
        $sql = "
            DELETE FROM
                data_reconciliation
            WHERE
                id=$id
        ";

        write_log('debug','deleteDataReconciliation :: ' . toString($sql));
        if( $this->db->simple_query($sql) ){
            return true;
        }
        else{
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getDataReconciliation($searchPattern, $startFrom, $limit){
        $sqlSearch="";
        if (isset($searchPattern)) {
            $sqlSearch = " AND (operator LIKE '%$searchPattern%' OR short_code like '%$searchPattern%')";
        }

        $sqlLimit="";
        if (isset($startFrom) && isset($limit)) {
            $sqlLimit = " limit $startFrom, $limit";
        } else  if (isset($limit)) {
            $sqlLimit = " limit $limit";
        }

        $sql = "
        SELECT
            a.*,b.operator as operator_name
        FROM
            data_reconciliation a
        LEFT JOIN
            tbl_operator b
        ON
            a.operator = b.operator_code
        WHERE
            1=1
            $sqlSearch
        ";

        $sqlComplete = "
            $sql
            $sqlLimit
        ";

        $totalRecord = 0;

        if (!empty($sqlLimit)) {
            write_log('debug','getDataReconciliation :: ' . toString($sql));
            $query = $this->db->query($sql);
            if($query != FALSE){
                $totalRecord = $query->num_rows();
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }

        write_log('debug','getDataReconciliation :: ' . toString($sqlComplete));
        $query = $this->db->query($sqlComplete);
        if($query != FALSE){
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

    public function getDataReconciliationById($id){
        $sql = "
        SELECT
            a.*,
            b.*,
            c.operator as operator_name
        FROM
            data_reconciliation a
        LEFT JOIN
            data_reconciliation_mapping b
        ON
            a.id = b.data_reconciliation_id
        LEFT JOIN
            tbl_operator c
        ON
            a.operator = c.operator_code
        WHERE
            a.id=$id
        ";

        write_log('debug','getDataReconciliationById :: ' . toString($sql));
        $query = $this->db->query($sql);
        if($query != FALSE){
            $results = array();
            $mapping = array();
            $tmp = array();
            foreach($query->result_array() as $row){
                $tmp[$row['data_reconciliation_id']][] = $row;
            }
            foreach($tmp as $row){
                $result = array();
                foreach($row as $item){
                    $result['shortCode'] 	= $item['short_code'];
                    $result['operator']	 	= $item['operator'];
                    $result['operator_name']= $item['operator_name'];
                    $result['month']		= $item['month'];
                    $result['year']			= $item['year'];
                    $result['grossInternal']= $item['gross_internal'];
                    $result['grossOperator']= $item['gross_operator'];
                    $result['differencePrice']= $item['difference_price'];
                    $result['differencePercentage'] = $item['difference_percentage'];
                    $result['partnerPercentage']	= $item['partner_percentage'];
                    $result['data'][] = array(
                        'serviceId' => $item['service_id'],
                        'trafficInternal'=> $item['traffic_internal'],
                        'trafficOperator'=> $item['traffic_operator'],
                        'grossInternal'=> $item['gross_internal'],
                        'grossOperator'=> $item['gross_operator']
                    );
                }
                $results[] = $result;
            }
            return $results;
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getInternalReconciliation($shortCode, $operator, $month, $year){
        $sqlShortCode = "";
        if($shortCode){
            $sqlShortCode = " AND shortcode='$shortCode'";
        }

        $sqlOperator = "";
        if($operator){
            $sqlOperator = " AND operator='$operator'";
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        $sql = "
        SELECT
            serviceid,
            sum(ABS(total)) as traffic,
            (sum(ABS(total)) * gross) as gross
        FROM
            rpt_service2
        WHERE
            sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
        $sqlShortCode
        $sqlOperator
        AND
            msgstatus='DELIVERED'
        GROUP BY
            serviceid;
        ";
        write_log('debug','getInternalReconciliation :: ' . toString($sql));
        $query = $this->db->query($sql);
        if($query != FALSE){
            $totalRecord = $query->num_rows();
            return array(
                    0 => $totalRecord,
                    1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function addOperatorSharing($shortCode, $operator, $sharing) {
        $sql = sprintf("
            INSERT INTO
                data_operator_sharing
            VALUES
                (0, '%s', '%s', %d)",
            $this->db->escape_str($shortCode),
            $this->db->escape_str($operator),
            $sharing
        );

        write_log('debug', 'addOperatorSharing :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function updateOperatorSharing($id, $shortCode, $operator, $sharing) {
        $sql = sprintf("
            UPDATE
                data_operator_sharing
            SET
                sharing = %d
            WHERE
                id = %d
                AND short_code = '%s'
                AND operator = '%s'",
            $sharing,
            $id,
            $this->db->escape_str($shortCode),
            $this->db->escape_str($operator)
        );

        write_log('debug', 'updateOperatorSharing :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function getOperatorSharing($searchPattern, $startFrom, $limit) {
        $sql = sprintf("
            SELECT
                os.*, op.operator
            FROM
                data_operator_sharing os
            LEFT JOIN
                tbl_operator op
            ON
                os.operator = op.operator_code
            ORDER BY
                id DESC
            LIMIT %d, %d",
            $startFrom,
            $limit
        );

        write_log('debug', 'getOperatorSharing :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $operatorSharingList = $query->result_array();

                $sqlTotal = sprintf("
                    SELECT
                        COUNT(*) AS total
                    FROM
                        data_operator_sharing"
                );

                write_log('debug', 'getOperatorSharing :: ' . toString($sqlTotal));
                $queryTotal = $this->db->query($sqlTotal);

                if (false != $queryTotal) {
                    $total = $queryTotal->result_array();

                    return array(
                        0 => $total[0]['total'],
                        1 => $operatorSharingList
                    );
                }
                else {
                    write_log('debug', 'Failed while querying for total records');
                    // FAIL QUERYING TOTAL

                    return array(
                        0 => 0,
                        1 => false
                    );
                }
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getOperatorSharingById($id) {
        $sql = sprintf("
            SELECT
                os.*, op.operator
            FROM
                data_operator_sharing os
            LEFT JOIN
                tbl_operator op
            ON
            os.operator = op.operator_code
                WHERE
            os.id = %d",
            $id
        );

        write_log('debug', 'getOperatorSharingById :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $operatorSharingList = $query->result_array();

                return array(
                    0 => 1,
                    1 => $operatorSharingList
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => null
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function deleteOperatorSharing($id) {
        $sql = sprintf("
            DELETE FROM
                data_operator_sharing
            WHERE
                id = %d",
            $id
        );

        write_log('debug', 'deleteOperatorSharing :: ' . toString($sql));
        return $this->db->query($sql);
    }

    private function getDiscrepancy($shortCode,$operator,$month,$year){
        $sql = "
            SELECT
                partner_percentage
            FROM
                data_reconciliation
            WHERE
                short_code='$shortCode'
            AND
                operator='$operator'
            AND
                month='$month'
            AND
                year='$year'
        ";

        write_log('debug','getDiscrepancy :: ' . toString($sql));
        $query = $this->db->query($sql);
        if($query->num_rows() != 0){
            $result = $query->result_array();
            return (int)$result[0]['partner_percentage']/100;
        }
        else{
            return 1;
        }
    }

    public function getPremiumDownloadOperatorReport($partnerId, $year, $month, $shortCode){
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        // get operator list
        $sqlOperator = "
            SELECT
                DISTINCT operator
            FROM
                rpt_content
            WHERE
                sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
        ";

        write_log('debug','getPremiumDownloadOperatorReport :: '. toString($sqlOperator));
        $query = $this->db->query($sqlOperator);

        $operator = array();
        if($query->num_rows() != 0){
            foreach($query->result_array() as $op){
                $operator[] = $op['operator'];
            }
        }

        // template untuk dapetin content list (basic scheme)
        // table content list di cross join dengan operator list
        $sqlDynamicOperator = "";
        $dynamicOperatorTemplate = "SELECT @operator_code@ as operator";
        foreach($operator as $i => $row){
            if($i != 0)	$sqlDynamicOperator .= ' UNION ';
            $sqlDynamicOperator .= str_replace('@operator_code@',$row,$dynamicOperatorTemplate);
        }
        $contentOwnerTemplate = "
            SELECT
                operator.*,
                contentlist.*
            FROM
            (
                SELECT
                    b.content_type as type,
                    content_code as code,
                    content_title as title,
                    0 as price
                    @sqlDynamic@
                FROM
                    tbl_content_dl a
                LEFT JOIN
                    data_content_code_mapping b
                ON
                    SUBSTRING(a.content_code,1,1) = b.prefix
                WHERE
                    @sqlFilter@
            ) as contentlist,
            (
                SELECT
                    *
                FROM
                    ($sqlDynamicOperator) as tbl
            ) as operator
        ";

        // template untuk dapetin report content
        $contentReportTemplate = "
            SELECT
                operator,
                ctype as type,
                code,
                content_title as title,
                price
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
                AND shortcode = '$shortCode'
            AND serviceid != 'FREE'
                @sqlFilter@
            GROUP BY
                code,operator
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentFilter($partnerId,'premium');
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getContentRatio($partnerId,'premium');
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        write_log('info', 'getting contentlist.');
        if($filters){
            $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_content id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@,0 as revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " $filterFieldContent='$contentId' ";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', (ABS(total)*@ratio@)*price, 0)) revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code,operator
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ",$sqlContent[0]),implode(" UNION ",$sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ",$sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ",$sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code,operator
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlFlooring		= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            $sqlFlooringTemplate= ",floor(SUM(sent@date@)),floor(SUM(delivered@date@)),floor(SUM(revenue@date@))";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlFlooring .= str_replace('@date@',$i,$sqlFlooringTemplate);
                $sqlSentTotal[] 	 = "SUM(sent$i)";
                $sqlDeliveredTotal[] = "SUM(delivered$i)";
                $sqlRevenueTotal[] 	 = "SUM(revenue$i)";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    tbl.*,
                    a.operator as operator_name
                    $sqlFlooring
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as tbl
                LEFT JOIN
                    tbl_operator a
                ON
                    tbl.operator = a.operator_code
                GROUP BY
                    code,tbl.operator
                ORDER BY
                    code,tbl.operator
            ";

            $sqlComplete = "
                $sql
            ";

            $totalRecord = 0;

			write_log('debug','getPremiumDownloadOperatorReport :: ' . toString($sqlComplete));
			$query = $this->db->query($sqlComplete);
			if($query != FALSE){
				$grandTotal = array(
					'type' 				=> 'total',
					'code' 				=> 'total',
					'title'				=> 'total',
                    'delivered' => 0,
                    'revenue' => 0
				);
				foreach($operator as $row) {
					$grandTotal['operator'][] = array(
						'sent' 		=> 0,
						'delivered' => 0,
					 	'revenue'	=> 0
					);
				}

				$results   = array();
				$resultTmp = array();
				foreach ($query->result_array() as $row) {
					$resultTmp[$row['code']][] = $row;
				}

                // ada row yang price = 0 dan row yang price != 0 untuk content code yang sama

				foreach($resultTmp as $rows){ // content code
                    $result = array(
                        'type' => '', 'code' => '', 'title' => '', 'price' => '', 'delivered' => 0, 'revenue' => 0, 'operator' => array()
                    );

					foreach($rows as $i => $row){ // operator
                        $currentRow = $row;
                        $result['delivered'] += $row['totalDelivered'];
                        $result['revenue'] += $row['totalRevenue'];

                        $result['operator'][] = array(
                            'price' => $row['price'],
                            'operatorId' 	=> $row['operator'],
                            'operatorName'	=> $row['operator_name'],
                            'sent'			=> $row['totalSent'],
                            'delivered'		=> $row['totalDelivered'],
                            'revenue'		=> $row['totalRevenue']
                        );

                        $grandTotal['operator'][$i]['operatorId'] 	= $row['operator'];
                        $grandTotal['operator'][$i]['operatorName'] = $row['operator_name'];
                        $grandTotal['operator'][$i]['sent'] 		+= $row['totalSent'];
                        $grandTotal['operator'][$i]['delivered'] 	+= $row['totalDelivered'];
                        $grandTotal['operator'][$i]['revenue'] 		+= $row['totalRevenue'];
					}

                    $result['type'] = $currentRow['type'];
                    $result['code'] = $currentRow['code'];
                    $result['title'] = $currentRow['title'];

					$results[] = $result;
				}

				$results[] = $grandTotal;

                return array(
                    0 => $totalRecord,
                    1 => $results
                );
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function getFreeDownloadOperatorReport($partnerId, $year, $month, $shortCode){
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        /* get operator list {
        $sqlOperator = "
            SELECT
                DISTINCT operator
            FROM
                rpt_content
            WHERE
                sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
                AND (
                    operator = '0'
                    OR operator = '00'
                )
        ";

        write_log('debug','getFreeDownloadOperatorReport :: '. toString($sqlOperator));
        $query = $this->db->query($sqlOperator);

        $operator = array();
        if($query->num_rows() != 0){
            foreach($query->result_array() as $op){
                $operator[] = $op['operator'];
            }
        }
        }*/
        $operator = array('0', '00');

        // template untuk dapetin content list (basic scheme)
        // table content list di cross join dengan operator list
        $sqlDynamicOperator = "";
        $dynamicOperatorTemplate = "SELECT @operator_code@ as operator";
        foreach($operator as $i => $row){
            if($i != 0)	$sqlDynamicOperator .= ' UNION ';
            $sqlDynamicOperator .= str_replace('@operator_code@',$row,$dynamicOperatorTemplate);
        }
        $contentOwnerTemplate = "
            SELECT
                operator.*,
                contentlist.*
            FROM
            (
                SELECT
                    b.content_type as type,
                    a.content_code as code,
                    content_title as title
                    @sqlDynamic@
                FROM
                    tbl_content_dl a
                LEFT JOIN
                    data_content_code_mapping b
                ON
                    SUBSTRING(a.content_code,1,1) = b.prefix
                WHERE
                    @sqlFilter@
            ) as contentlist,
            (
                SELECT
                    *
                FROM
                    ($sqlDynamicOperator) as tbl
            ) as operator
        ";

        // template untuk dapetin report content
        $contentReportTemplate = "
            SELECT
                operator,
                ctype as type,
                code,
                content_title as title
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
                AND a.shortcode = '$shortCode'
                AND a.serviceid = 'FREE'
                AND a.price = 0
                @sqlFilter@
            GROUP BY
                code,operator
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentFilter($partnerId,'free');
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getContentRatio($partnerId,'free');
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        write_log('info', 'getting contentlist.');
        if($filters){
            $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_content id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@,0 as revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " a.$filterFieldContent='$contentId' ";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', (ABS(total)*@ratio@)*price, 0)) revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code,operator
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ",$sqlContent[0]),implode(" UNION ",$sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ",$sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ",$sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code,operator
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlFlooring		= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            $sqlFlooringTemplate= ",floor(SUM(sent@date@)),floor(SUM(delivered@date@)),floor(SUM(revenue@date@))";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlFlooring .= str_replace('@date@',$i,$sqlFlooringTemplate);
                $sqlSentTotal[] 	 = "SUM(sent$i)";
                $sqlDeliveredTotal[] = "SUM(delivered$i)";
                $sqlRevenueTotal[] 	 = "SUM(revenue$i)";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    tbl.*,
                    a.operator as operator_name
                    $sqlFlooring
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as tbl
                LEFT JOIN
                    tbl_operator a
                ON
                    tbl.operator = a.operator_code
                GROUP BY
                    code,tbl.operator
                ORDER BY
                    code,tbl.operator
            ";

            $sqlComplete = "
                $sql
            ";

            $totalRecord = 0;

            write_log('debug','getFreeDownloadOperatorReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete);
            if($query != FALSE){
                $grandTotal = array(
                    'type' => 'total',
                    'code' => 'total',
                    'title' => 'total',
                    'operator' => array(
                        0 => array(
                            'sent' => 0,
                            'delivered' => 0,
                            'revenue' => 0,
                            'operatorId' => '',
                            'operatorName' => ''
                        )
                    )
                );
                /*foreach($operator as $row) {
                    $grandTotal['operator'][] = array(
                        'sent' 		=> 0,
                        'delivered' => 0,
                        'revenue'	=> 0
                    );
                }*/

                $results   = array();
                $resultTmp = array();
                foreach ($query->result_array() as $row) {
                    $resultTmp[$row['code']][] = $row;
                }

                foreach($resultTmp as $rows){ // content code
                    $result = array(
                        'type' => '', 'code' => '', 'title' => '', 'operator' => array()
                    );

					foreach($rows as $i => $row){ // operator
                        $currentRow = $row;

                        $grandTotal['operator'][0]['sent'] += $row['totalSent'];
                        $grandTotal['operator'][0]['delivered'] += $row['totalDelivered'];
                        $grandTotal['operator'][0]['revenue'] += $row['totalRevenue'];
					}

                    $result['type'] = $currentRow['type'];
                    $result['code'] = $currentRow['code'];
                    $result['title'] = $currentRow['title'];
                    $result['operator'][0] = array(
                        'operatorId' => '0',
                        'operatorName' => 'UNKNOWN',
                        'sent' => $row['totalSent'],
                        'delivered' => $row['totalDelivered'],
                        'revenue' => $row['totalRevenue']
                    );

					$results[] = $result;
				}

                $grandTotal['operator'][0]['operatorId'] = '0';
                $grandTotal['operator'][0]['operatorName'] = 'UNKNOWN';

                $results[] = $grandTotal;

                return array(
                    0 => $totalRecord,
                    1 => $results
                );
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function getPublisherDownloadOperatorReport($partnerId, $year, $month, $shortCode){
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        // get operator list
        $sqlOperator = "
            SELECT
                DISTINCT operator
            FROM
                rpt_content
            WHERE
                sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
        ";

        write_log('debug','getFreeDownloadOperatorReport :: '. toString($sqlOperator));
        $query = $this->db->query($sqlOperator);

        $operator = array();
        if($query->num_rows() != 0){
            foreach($query->result_array() as $op){
                $operator[] = $op['operator'];
            }
        }

        // template untuk dapetin content list (basic scheme)
        // table content list di cross join dengan operator list
        $sqlDynamicOperator = "";
        $dynamicOperatorTemplate = "SELECT @operator_code@ as operator";
        foreach($operator as $i => $row){
            if($i != 0)	$sqlDynamicOperator .= ' UNION ';
            $sqlDynamicOperator .= str_replace('@operator_code@',$row,$dynamicOperatorTemplate);
        }
        $contentOwnerTemplate = "
            SELECT
                operator.*,
                contentlist.*
            FROM
            (
                SELECT
                    b.content_type as type,
                    a.content_code as code,
                    content_title as title,
                    c.price
                    @sqlDynamic@
                FROM
                    tbl_content_dl a
                LEFT JOIN
                    data_content_code_mapping b
                ON
                    SUBSTRING(a.content_code,1,1) = b.prefix
                LEFT JOIN
                    data_content_price c
                ON
                    a.content_code = c.content_code
                    AND c.partner_id = $partnerId
                WHERE
                    @sqlFilter@
            ) as contentlist,
            (
                SELECT
                    *
                FROM
                    ($sqlDynamicOperator) as tbl
            ) as operator
        ";

        // template untuk dapetin report content
        $contentReportTemplate = "
            SELECT
                operator,
                ctype as type,
                code,
                content_title as title,
                c.price
                @sqlDynamic@
            FROM
                rpt_content a
            LEFT JOIN
                tbl_content_dl b
            ON
                a.code=b.content_code
            LEFT JOIN
                data_content_price c
            ON
                a.code = c.content_code
                AND c.partner_id = $partnerId
            WHERE
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
                AND a.shortcode = '$shortCode'
                @sqlFilter@
            GROUP BY
                code,operator
        ";

        write_log('info', 'getting filters');
        $filters = $this->getContentPublisherFilter($partnerId);

        write_log('info', 'getting ratio');
        $ratio = $this->getContentPublisherRatio($partnerId);

        write_log('info', 'getting contentlist.');
        if($filters){
            $sqlContent = array();
            foreach ($filters as $key => $row){
                foreach($row as $item){
                    if($ratio == false || !isset($ratio[$item['id']])){
                        write_log('warning', "data_partner_publisher id {$item['id']} :No defined ratio.");
                        break;
                    }

                    if($key == 'content'){
                        $filterFieldContent = 'content_code';
                        $filterFieldReport  = 'code';
                        $index = 0;
                    }
                    else{
                        $filterFieldContent = 'content_owner';
                        $filterFieldReport	= 'partner';
                        $index = 1;
                    }

                    $id 		= $item['id'];
                    $contentId 	= $item['contentId'];

                    /* generating list with empty data */
                    // generate sql for daily summary
                    $sqlDynamic = "";
                    $sqlDynamicTemplate = ",0 as sent@date@,0 as delivered@date@,0 as revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        $sqlDynamic.= str_replace("@date@", $i, $sqlDynamicTemplate);
                    }

                    $sqlFilter = " a.$filterFieldContent='$contentId' ";
                    $sqlContentOwner = str_replace(array("@sqlDynamic@","@sqlFilter@"),array($sqlDynamic,$sqlFilter),$contentOwnerTemplate);
                    //---> end

                    /* generating list from summary */
                    // generate sql for daily summary
                    $sqlDynamic="";
                    $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, ABS(total)*@ratio@, 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', ABS(total)*@ratio@, 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND status='DELIVERED', (ABS(total)*@ratio@)*c.price, 0)) revenue@date@";
                    for ($i=1;$i<=$totalDate;$i++) {
                        /* TRICKY */
                        $loopDate = strtotime("$year-$month-$i");
                        $ratioIndex 		= '';
                        $defaultRatioIndex 	= '';
                        foreach($ratio[$id] as $k => $r){
                            if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                                $defaultRatioIndex = $k;
                            }
                            if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                                $r['endTime'] = $dateRangeEnd;
                            }
                            if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                                $ratioIndex = $k;
                            }
                        }

                        if(strlen($ratioIndex) != 0){
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$ratioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                        else{
                            $sqlDynamic.=str_replace(
                                array("@date@","@ratio@"),
                                array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                                $sqlDynamicTemplate
                            );
                        }
                    }

                    $sqlFilter = " AND $filterFieldReport='$contentId'";
                    if($item['filter']){
                        $filterService  = array();
                        $filterSid		= array();
                        $filterPrice	= array();
                        foreach($item['filter'] as $itm){
                            switch($itm['type']){
                                case 'service':
                                    $filterService[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'sid':
                                    $filterSid[] = sprintf("'%s'",$itm['key']);
                                    break;
                                case 'price':
                                    $filterPrice[] = sprintf("'%s'",$itm['key']);
                                    break;
                            }
                        }
                        $pFilter = array();
                        if(count($filterService)!=0) $pFilter[] = sprintf("service in (%s)",implode(',', $filterService));
                        if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                        if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                        $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                    }

                    $sqlContentReport = str_replace(
                        array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@"),
                        array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter),
                        $contentReportTemplate
                    );

                    $sqlContent[$index][] = "
                        SELECT
                            *
                        FROM
                        (
                            $sqlContentReport
                            UNION
                            $sqlContentOwner
                        ) as tbl
                        GROUP BY
                            code,operator
                    ";
                    //--->end
                }
            }

            // combine all query by mapping type with each filters
            if( isset($sqlContent[0]) && isset($sqlContent[1]) ){
                $sqlCombine = implode(" UNION ",array(implode(" UNION ",$sqlContent[0]),implode(" UNION ",$sqlContent[1])));
            }
            elseif( isset($sqlContent[0]) ){
                $sqlCombine = implode(" UNION ",$sqlContent[0]);
            }
            else{
                $sqlCombine = implode(" UNION ",$sqlContent[1]);
            }

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    code,operator
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlFlooring		= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            $sqlFlooringTemplate= ",floor(SUM(sent@date@)),floor(SUM(delivered@date@)),floor(SUM(revenue@date@))";
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlFlooring .= str_replace('@date@',$i,$sqlFlooringTemplate);
                $sqlSentTotal[] 	 = "SUM(sent$i)";
                $sqlDeliveredTotal[] = "SUM(delivered$i)";
                $sqlRevenueTotal[] 	 = "SUM(revenue$i)";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    tbl.*,
                    a.operator as operator_name
                    $sqlFlooring
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as tbl
                LEFT JOIN
                    tbl_operator a
                ON
                    tbl.operator = a.operator_code
                GROUP BY
                    code,tbl.operator
                ORDER BY
                    code,tbl.operator
            ";

            $sqlComplete = "
                $sql
            ";

            $totalRecord = 0;

            write_log('debug','getPublisherDownloadOperatorReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete);
            if($query != FALSE){
                $grandTotal = array(
                    'type' => 'total',
                    'code' => 'total',
                    'title' => 'total',
                );
                foreach($operator as $row) {
                    $grandTotal['operator'] = array(
                        0 => array(
                            'operatorId' => '0',
                            'operatorName' => 'UNKNOWN',
                            'sent' => 0,
                            'delivered' => 0,
                            'revenue' => 0
                        )
                    );
                }

                $results   = array();
                $resultTmp = array();
                foreach ($query->result_array() as $row) {
                    $resultTmp[$row['code']][] = $row;
                }

                foreach($resultTmp as $rows){ // content code
                    $result = array(
                        'type' => '', 'code' => '', 'title' => '', 'delivered' => 0, 'revenue' => 0, 'operator' => array()
                    );

                    foreach($rows as $i => $row){ // operator
                        $currentRow = $row;
                        $result['delivered'] += $row['totalDelivered'];
                        $result['revenue'] += $row['totalRevenue'];

                        if ('0' == $row['operator'] || '00' == $row['operator']) {
                            $result['operator'][0] = array(
                                'price' => $row['price'],
                                'operatorId' => '0',
                                'operatorName' => 'UNKNOWN',
                                'sent' => $row['totalSent'],
                                'delivered' => $row['totalDelivered'],
                                'revenue' => $row['totalRevenue']
                            );

                            $grandTotal['operator'][0]['operatorId'] = '0';
                            $grandTotal['operator'][0]['operatorName'] = 'UNKNOWN';
                            $grandTotal['operator'][0]['sent'] += $row['totalSent'];
                            $grandTotal['operator'][0]['delivered'] += $row['totalDelivered'];
                            $grandTotal['operator'][0]['revenue'] += $row['totalRevenue'];
                        }
                        else {
                            $result['operator'][] = array(
                                'price' => $row['price'],
                                'operatorId' => $row['operator'],
                                'operatorName' => $row['operator_name'],
                                'sent' => $row['totalSent'],
                                'delivered' => $row['totalDelivered'],
                                'revenue' => $row['totalRevenue']
                            );

                            if (!isset($grandTotal['operator'][$i])) {
                                $grandTotal['operator'][$i] = array(
                                    'sent' => 0,
                                    'delivered' => 0,
                                    'revenue' => 0
                                );
                            }

                            $grandTotal['operator'][$i]['operatorId'] = $row['operator'];
                            $grandTotal['operator'][$i]['operatorName'] = $row['operator_name'];
                            $grandTotal['operator'][$i]['sent'] += $row['totalSent'];
                            $grandTotal['operator'][$i]['delivered'] += $row['totalDelivered'];
                            $grandTotal['operator'][$i]['revenue'] += $row['totalRevenue'];
                        }
                    }

                    $result['type'] = $currentRow['type'];
                    $result['code'] = $currentRow['code'];
                    $result['title'] = $currentRow['title'];

                    $results[] = $result;
                }

                $results[] = $grandTotal;

                return array(
                    0 => $totalRecord,
                    1 => $results
                );
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            // no mapping type
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter define.'));
        }
    }

    public function getTextDownloadOperatorReport($partnerId, $year, $month, $service, $shortCode){
        $params = array();
        $sqlLimit = "";

        $sqlService = "";
        if($service){
            $sqlService = " AND service='$service'";
        }

        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        if(date("Y") == $year && (int)date("m") == (int)$month){
            $totalDate = (int) date("d") - 1;
        }
        else{
            $totalDate = $maxDate;
        }

        $dateRangeStart = "$year-$month-01";
        $dateRangeEnd 	= "$year-$month-$totalDate";

        // get operator list
        $sqlOperator = "
            SELECT
                DISTINCT operator
            FROM
                rpt_service2
            WHERE
                sumdate BETWEEN '$dateRangeStart' AND '$dateRangeEnd'
        ";

        write_log('debug','getTextDownloadOperatorReport :: ' . toString($sqlOperator));
        $query = $this->db->query($sqlOperator);

        $operator = array();
        if($query->num_rows() != 0){
            foreach($query->result_array() as $op){
                if (0 < strlen($op['operator'])) {
                    $operator[] = $op['operator'];
                }
            }
        }

        $operatorList = '(' . implode(',', $operator) . ')';

        $sqlTemplate = "
            SELECT
                operator,
                serviceid,
                gross
                @sqlDynamic@
            FROM
                rpt_service2
            WHERE
                operator IN $operatorList
            AND
                sumdate BETWEEN '@dateStart@' AND '@dateEnd@'
            AND
                service='@service@'
            AND
                shortcode='$shortCode'
            @sqlFilter@
            GROUP BY
                operator,serviceid
        ";
        write_log('info', 'getting filters');
        $filters = $this->getServiceFilter($partnerId);
        write_log('debug', 'FILTERS RESULT: ' . toString(print_r($filters,1)));

        write_log('info', 'getting ratio');
        $ratio = $this->getServiceRatio($partnerId);
        write_log('debug', 'RATIO RESULT: ' . toString(print_r($ratio,1)));

        if($filters){
            $sqlReport = array();
            foreach ($filters as $row){
                $id = $row['id'];
                // generate sql for daily summary
                $sqlDynamic="";
                $sqlDynamicTemplate = ", SUM(IF(DAYOFMONTH(sumdate)=@date@, floor(ABS(total)*@ratio@), 0)) sent@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND msgstatus='DELIVERED', floor(ABS(total)*@ratio@), 0)) delivered@date@, SUM(IF(DAYOFMONTH(sumdate)=@date@ AND msgstatus='DELIVERED', floor(ABS(total)*@ratio@)*gross, 0)) revenue@date@";
                for ($i=1;$i<=$totalDate;$i++) {
                    /* TRICKY */
                    $loopDate = strtotime("$year-$month-$i");
                    $ratioIndex 		= '';
                    $defaultRatioIndex 	= '';
                    foreach($ratio[$id] as $k => $r){
                        if($r['startTime'] == '0000-00-00' && ($r['endTime'] == '' || $r['endTime'] == '0000-00-00')){
                            $defaultRatioIndex = $k;
                        }
                        if($r['endTime'] == '' || $r['endTime'] == '0000-00-00'){
                            $r['endTime'] = $dateRangeEnd;
                        }
                        if($r['startTime'] != '0000-00-00' && strtotime($r['startTime']) <= $loopDate && strtotime($r['endTime']) >= $loopDate){
                            $ratioIndex = $k;
                        }
                    }

                    if(strlen($ratioIndex) != 0){
                        $sqlDynamic.=str_replace(
                            array("@date@","@ratio@"),
                            array($i,$ratio[$id][$ratioIndex]['ratio']),
                            $sqlDynamicTemplate
                        );
                    }
                    else{
                        $sqlDynamic.=str_replace(
                            array("@date@","@ratio@"),
                            array($i,$ratio[$id][$defaultRatioIndex]['ratio']),
                            $sqlDynamicTemplate
                        );
                    }
                }

                $sqlFilter = "";
                if($row['filter']){
                    $filterSubject  = array();
                    $filterOperator = array();
                    $filterSid		= array();
                    $filterPrice	= array();
                    foreach($row['filter'] as $itm){
                        switch($itm['type']){
                            case 'subject':
                                $filterSubject[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'operator':
                                $filterOperator[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'sid':
                                $filterSid[] = sprintf("'%s'",$itm['key']);
                                break;
                            case 'price':
                                $filterPrice[] = sprintf("'%s'",$itm['key']);
                                break;
                        }
                    }
                    $pFilter = array();
                    if(count($filterSubject)!=0) $pFilter[] = sprintf("substring_index(substring_index(subject,';',-3),';',1) in (%s)",implode(',', $filterSubject));
                    if(count($filterOperator)!=0)$pFilter[] = sprintf("operator in (%s)",implode(',', $filterOperator));
                    if(count($filterSid)	!=0) $pFilter[] = sprintf("serviceid in (%s)",implode(',', $filterSid));
                    if(count($filterPrice)	!=0) $pFilter[] = sprintf("price in (%s)",implode(',', $filterPrice));

                    $sqlFilter .= sprintf(" AND (%s)", implode(' OR ', $pFilter));
                }

                $sqlReport[] = str_replace(
                    array("@sqlDynamic@","@dateStart@","@dateEnd@","@sqlFilter@","@service@","@shortCode@"),
                    array($sqlDynamic,$dateRangeStart,$dateRangeEnd,$sqlFilter,$row['service'],$row['shortCode']),
                    $sqlTemplate
                );
            }

            // combine all query by mapping type with each filters
            $sqlCombine = implode(" UNION ", $sqlReport);

            // merge record with content code ratio priority on first
            $sqlMerge = "
                SELECT
                    *
                FROM
                    ($sqlCombine) as tbl
                GROUP BY
                    serviceid,operator
            ";

            //get total
            $sqlTotalDynamic	= "";
            $sqlSentTotal 		= array();
            $sqlDeliveredTotal 	= array();
            $sqlRevenueTotal 	= array();
            for ($i=1;$i<=$totalDate;$i++) {
                $sqlSentTotal[] 	 = "sent$i";
                $sqlDeliveredTotal[] = "delivered$i";
                $sqlRevenueTotal[] 	 = "revenue$i";
            }
            $sqlTotalDynamic = sprintf(", %s as totalSent,%s as totalDelivered,%s as totalRevenue",
                implode("+",$sqlSentTotal),
                implode("+",$sqlDeliveredTotal),
                implode("+",$sqlRevenueTotal)
            );

            $sql = "
                SELECT
                    m.*,
                    o.operator as operatorName
                    $sqlTotalDynamic
                FROM
                    ($sqlMerge) as m
                LEFT JOIN
                    tbl_operator o
                ON
                    m.operator = o.operator_code
                WHERE
                    1=1
                    $sqlService
                ORDER BY
                    operator,totalSent DESC
            ";

            $sqlComplete = "
                $sql
                $sqlLimit
            ";
            //echo $sqlComplete; exit;

            $totalRecord = 0;

            if (!empty($sqlLimit)) {
                //write_log('debug',"SQL Executed: $sql - ".print_r($params, true));
                write_log('debug','getTextDownloadOperatorReport :: ' . toString($sql));
                $query = $this->db->query($sql, $params);

                if($query != FALSE){
                    $totalRecord = $query->num_rows();
                } else {
                    throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
                }
            }

            //write_log('debug',"SQL Executed: $sqlComplete - ".print_r($params, true));
            write_log('debug','getTextDownloadOperatorReport :: ' . toString($sqlComplete));
            $query = $this->db->query($sqlComplete, $params);

            if($query != FALSE){
                if (empty($sqlLimit)) {
                    $totalRecord = $query->num_rows();
                }

                $grandTotalBuffer = array();
                $text = $serviceIdList = array();
                $counter = -1;

                foreach ($query->result_array() as $recordSet) {
                    if (strlen($recordSet['serviceid']) > 0) {
                        //$response[$recordSet['serviceid']][] = $recordSet;
                        if (!in_array($recordSet['serviceid'], $serviceIdList)) {
                            $serviceIdList[] = $recordSet['serviceid'];
                            $counter += 1;
                        }

                        $text[$recordSet['serviceid']]['serviceId'] = $recordSet['serviceid'];
                        $text[$recordSet['serviceid']]['price'] = $recordSet['gross'];

                        // operator belum ada di dalam array text
                        if ((isset($text[$recordSet['serviceid']]['operator']) && !in_array($recordSet['operator'], $text[$recordSet['serviceid']]['operator'])) || !isset($text[$recordSet['serviceid']]['operator'])) {
                            $text[$recordSet['serviceid']]['operator'][$recordSet['operator']] = $grandTotal[$recordSet['serviceid']]['text'][$recordSet['operator']] = array(
                                'operatorId' => $recordSet['operator'],
                                'operatorName' => $recordSet['operatorName'],
                                'sent' => 0,
                                'delivered' => 0,
                                'revenue' => 0
                            );
                        }

                        $text[$recordSet['serviceid']]['operator'][$recordSet['operator']]['sent'] += $recordSet['totalSent'];
                        $text[$recordSet['serviceid']]['operator'][$recordSet['operator']]['delivered'] += $recordSet['totalDelivered'];
                        $text[$recordSet['serviceid']]['operator'][$recordSet['operator']]['revenue'] += $recordSet['totalRevenue'];

                        if ((isset($grandTotal[$recordSet['operator']]) && !in_array($recordSet['operator'], $grandTotalBuffer)) || !isset($grandTotalBuffer[$recordSet['operator']])) {
                            if (!isset($grandTotalBuffer[$recordSet['operator']])) {
                                $grandTotalBuffer[$recordSet['operator']] = array(
                                    'operator' => $recordSet['operator'],
                                    'operatorName' => $recordSet['operatorName'],
                                    'sent' => 0,
                                    'delivered' => 0,
                                    'revenue' => 0
                                );
                            }

                            $grandTotalBuffer[$recordSet['operator']]['sent'] += $recordSet['totalSent'];
                            $grandTotalBuffer[$recordSet['operator']]['delivered'] += $recordSet['totalDelivered'];
                            $grandTotalBuffer[$recordSet['operator']]['revenue'] += $recordSet['totalRevenue'];
                        }
                    }
                }

                $text['grandTotal']['serviceId'] = 'Grand Total';
                $text['grandTotal']['price'] = 'Grand Total';
                $text['grandTotal']['operator'] = $grandTotalBuffer;
//                echo '<pre>'; var_dump($text); exit;

                return array(
                    0 => $totalRecord,
                    1 => $text
                );
            } else {
                throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
            }
        }
        else{
            // no filter defined
            throw new Exception(sprintf(RESPONSE_ERROR_UNEXPECTEDQUERYRESULT, 'No filter defined.'));
        }
    }

    public function getDataReconciliationByShortCodeAndOperator($shortCode, $operator){
        $sql = "
        SELECT
            a.*,b.operator as operator_name
        FROM
            data_reconciliation a
        LEFT JOIN
            tbl_operator b
        ON
            a.operator = b.operator_code
        WHERE
            a.short_code = $shortCode
            AND a.operator = $operator
        ";

        $totalRecord = 0;

        write_log('debug','getDataReconciliationByShortCodeAndOperator :: ' . toString($sql));
        $query = $this->db->query($sql);
        if($query != FALSE){
            return array(
                0 => 1,
                1 => $query->result_array()
            );
        } else {
            throw new Exception(sprintf(RESPONSE_ERROR_MYSQLERROR, mysql_error()));
        }
    }

    public function getOperatorSharingByShortCodeAndOperator($shortCode, $operator) {
        $sql = sprintf("
            SELECT
                sharing
            FROM
                data_operator_sharing os
            WHERE
                short_code = '%s'
                AND operator = '%s'",
            $shortCode,
            $operator
        );

        write_log('debug', 'getOperatorSharingByShortCodeAndOperator :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $operatorSharingList = $query->result_array();

                return array(
                    0 => 1,
                    1 => $operatorSharingList
                );
            }
            else {
                // NO DATA
                return array(
                    0 => 0,
                    1 => null
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for content list');
            // FAIL QUERYING PARTNER

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getPartnerIdByUsername($username) {
        $sql = sprintf("
            SELECT
                id
            FROM
                data_partner
            WHERE
                username = '%s'",
            $username
        );

        write_log('debug', 'getPartnerIdByUsername :: ' . toString($sql));
        $query = $this->db->query($sql);

        if (false != $query) {
            if (0 < $query->num_rows()) {
                $id = $query->result_array();

                return array(
                    0 => 1,
                    1 => $id[0]['id']
                );
            }
            else {
                return array(
                    0 => 0,
                    1 => false
                );
            }
        }
        else {
            write_log('debug', 'Failed while querying for partner ID');

            return array(
                0 => 0,
                1 => false
            );
        }
    }

    public function getPartnerPrivilegeList($partnerId){
        $param = array();

        $sqlPartner = "";
        if($partnerId){
            $sqlPartner = " partner_id=$partnerId";
        }

        $sql = "
            SELECT
                section
            FROM
                data_partner_access
            WHERE
            $sqlPartner
        ";

        write_log('debug','getPartnerPrivilegeList :: ' . toString($sql));
        $query = $this->db->query($sql);

        $result = array();
        if($query->num_rows() != 0){
            foreach($query->result_array() as $row){
                $result[] = $row['section'];
            }
        }
        return $result;
    }

    public function partnerDeletePrivilegeList($partnerId) {
        $sql = sprintf("
            DELETE FROM
                data_partner_access
            WHERE
                partner_id = %d",
            $partnerId
        );

        write_log('debug', 'deletePrivilegeList :: ' . toString($sql));
        return $this->db->query($sql);
    }

    public function partnerUpdatePrivilegeList($partnerId, $privilegeList) {
        $success = true;

        if (true !== $this->partnerDeletePrivilegeList($partnerId)) {
            return false;
        }

        $privilegeList = explode(',', $privilegeList);
        foreach ($privilegeList AS $key => $value) {
            if (false === $this->addPrivilege($partnerId, $value)) {
                $success = false;
            }
        }

        return array(
            0 => 0,
            1 => $success
        );
    }
}

