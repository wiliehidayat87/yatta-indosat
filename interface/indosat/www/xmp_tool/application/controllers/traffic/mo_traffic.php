<?php

class Mo_traffic extends MY_Controller{
    public $limit = 0;

    public function __construct() {
        parent::__construct();
        
        $this->load->model('traffic/mo_traffic_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->smarty->assign('base_url', base_url());
        $this->limit = $this->config->item('limit');
    }
    
    public function index(){
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $jsFile = 'traffic/mo_traffic.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage MO Traffic');
        $this->smarty->assign('pageLimit', $this->limit);
        
        $todayMO = $this->getTodayMOList();        
        $this->smarty->assign('todayMOTotal', $todayMO['total']);
        $this->smarty->assign('todayMOYesterday', $todayMO['yesterday']);
        $this->smarty->assign('todayMOLastSevenDays', $todayMO['lastsevendays']);
        
        $todayMO = $this->getTotalMOList();
        $this->smarty->assign('totalMOThisMonth', $todayMO['thismonth']);
        $this->smarty->assign('totalMOLastMonths', $todayMO['lastmonths']);
        $this->smarty->assign('totalMOLastSixMonths', $todayMO['lastsixmonths']);
        
        $this->smarty->assign('operator_list', $this->getOperatorList(''));
        $this->smarty->assign('adn_list', $this->getAdnList());
        $this->smarty->assign('type_list', $this->getTypeList());
        $this->smarty->display('traffic/mo_traffic_view.tpl');
    }
    
    public function ajaxGetMOTrafficList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        $this->benchmark->mark('code_start');
        
        $from   = "";
        $until  = "";
        
        $page   = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;        
        $paging = "";
        $result = "";
        
        $search = $this->input->post("searchParam");
        if ($search!=1){
            $from           = sprintf('%s-%s-%s %s:%s:%s', date('Y'), date('m'), date('d'), '00', '00', '00');  
            $until          = sprintf('%s-%s-%s %s:%s:%s', date('Y'), date('m'), date('d'), date('H'), date('i'), date('s'));
            $adnNumber      = "";  
            $operatorName   = "";
            $reqType        = "";
            $serviceName    = "";
            $msisdnNumber   = "";
            $msisdnCheckbox = "";
            $smsRequest     = "";
            $limit          = $this->input->post("limit");
        }else{
            $from           = $this->input->post("dateFrom");
            $until          = $this->input->post("dateTo");
            $adnNumber      = $this->input->post("adnNumber");
            $operatorName   = $this->input->post("operatorName");
            $reqType        = $this->input->post("reqType");
            $serviceName    = $this->input->post("serviceName");
            $msisdnNumber   = $this->input->post("msisdnNumber");
            $msisdnCheckbox = $this->input->post("msisdnCheckbox");
            $smsRequest     = $this->input->post("smsRequest");
            $limit          = $this->input->post("limitSearch");                        
        }
        //-- Validate Search --//
        if(!empty($from) && !empty($until)){
            $resFrom = $this->dateToString($from);
            $resUntil = $this->dateToString($until);
            if($resUntil >= $resFrom){
                if(!empty($msisdnNumber) && !empty($serviceName)){
                    if(is_numeric($msisdnNumber)){
                        if($this->mo_traffic_model->checkServiceName($serviceName)){
                            $mData  = $this->mo_traffic_model->getMOTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, 
                                                        $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
                        }else{
                            $response = array('status_serviceName' => false, 'status' => false);
                            echo json_encode($response);
                            exit;
                        }                                               
                    }else{
                        $response = array('status_msisdnNumber' => false, 'status' => false);
                        echo json_encode($response);
                        exit;
                    }
                }else{
                    if(!empty($msisdnNumber)){
                        if(is_numeric($msisdnNumber)){
                            $mData  = $this->mo_traffic_model->getMOTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, 
                                                $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
                        }else{
                            $response = array('status_msisdnNumber' => false, 'status' => false);
                            echo json_encode($response);
                            exit;
                        }
                    }else{
                        if(!empty($serviceName)){
                            if($this->mo_traffic_model->checkServiceName($serviceName)){
                                $mData  = $this->mo_traffic_model->getMOTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, 
                                                            $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
                            }else{
                                $response = array('status_serviceName' => false, 'status' => false);
                                echo json_encode($response);
                                exit;
                            }
                        }else{
                            $mData  = $this->mo_traffic_model->getMOTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName, 
                                                            $reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
                        }
                    }
                }
            }
            else{
                $response = array('status_checkDate' => false, 'status' => false);
                        echo json_encode($response);
                        exit;
            }
        }else{
            $response = array('status_dateRange' => false, 'status' => false);
                        echo json_encode($response);
                        exit;            
        }
        //--End Of Validate Search --//
        
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id         = $dt['id'];
                $mo_date    = $dt['mo_date'];
                $operator   = $this->getOperatorList($dt['operator']);
                $adn        = $dt['adn'];
                $msisdn     = $dt['msisdn'];
                $service    = $dt['service'];
                $type       = $dt['req_type'];
                $sms_req    = $dt['sms'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                    $result .= "<td>$mo_date</td>";
                    $result .= "<td>$operator</td>";
                    $result .= "<td>$adn</td>";
                    $result .= "<td>$msisdn</td>";
                    $result .= "<td>$service</td>";
                    $result .= "<td>$type</td>";
                    $result .= "<td>$sms_req</td>";                
                    $result .= "</tr>";
                    $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "traffic/mo_traffic/ajaxGetMOTrafficList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
                $pagination['per_page'] = $limit;

                $this->pagination->initialize($pagination);
                $paging_data = $this->pagination->create_links();
                $paging_data = preg_replace('/\<strong\>(\d+)\<\/strong\>/i', '<a class="current" href="">$1</a>', $paging_data);
                $paging_data = explode("&nbsp;", $paging_data);
                $paging = "<li>Total row: $total &nbsp;</li>";
                foreach ($paging_data as $page) {
                    if (!empty($page))
                        $paging.="<li>$page</li>";
                }
            }
            else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
        } else {
            $result .= "<tr><td colspan=\"7\">No data found</td></tr>";
            $paging = "<b></b>";
        }
        if (!empty($from) && !empty($until)){
            $searchInfo = "Date Range: ".$from." - ".$until;
                if(!empty($adnNumber))
                    $searchInfo .= " | ADN: ".$adnNumber;
                if(!empty($operatorName))
                    $searchInfo .= " | Operator: ".$operatorName;
                if(!empty($reqType))
                    $searchInfo .= " | Type: ".$reqType;
                if(!empty($serviceName))
                    $searchInfo .= " | Services: ".$serviceName;
                if (!empty($msisdnNumber))
                    $searchInfo .= " | MSISDN: ".$msisdnNumber;
                if (!empty($smsRequest))
                    $searchInfo .= " | SMS: ".$smsRequest;
        }else{
            $searchInfo = "";
        }
        
        $to = ($page + $limit) > $total ? $total : ($page + $limit);
        $this->benchmark->mark('code_end');
        $execution_time = $this->benchmark->elapsed_time('code_start', 'code_end');
        
        $response = array(
            'offset'                => $offset,
            'query'                 => $mData['query'],
            'result'                => $result,
            'paging'                => $paging,
            'from'                  => ($page + 1),
            'to'                    => $to,
            'total'                 => $total,
            'exec_time'             => $execution_time,
            'searchInfo'            => $searchInfo,
            'fromDate'              => $from,
            'untilDate'             => $until,
            'operator'              => $operatorName,
            'adn'                   => $adnNumber,
            'msisdn'                => $msisdnNumber,
            'msisdnCheck'           => $msisdnCheckbox,
            'service'               => $serviceName,
            'type'                  => $reqType,
            'sms'                   => $smsRequest,
            'status_serviceName'    => true,
            'status_msisdnNumber'   => true,
            'status_dateRange'      => true,
            'status'                => true            
        );
        
        echo json_encode($response);
        exit;
    }
    
    public function dateToString($param){
        
        $result    = str_replace('-',"", $param);
        $result    = str_replace(':',"", $result);
        $result    = preg_replace("/\s+/",'',$result);
        
        return $result;
    }
    
    public function getOperatorList($operator_id) {        
        if(!empty($operator_id)){
            $dataArr = array();
            $dataArr = $this->mo_traffic_model->getOperatorList($operator_id);            

            return $dataArr[0]['operator_name'];
        }else{
            $operatorList = "";

            $dataArr = $this->mo_traffic_model->getOperatorList('');

            foreach ($dataArr as $dt) {
                $operatorList[$dt['id']]['id']   = $dt['id'];                
                $operatorList[$dt['id']]['name'] = $dt['operator_name'];                
            }

            return $operatorList;
        }
    }
    
    public function getAdnList() {
        $adnList = "";

        $dataArr = $this->mo_traffic_model->getAdnList('');

        foreach ($dataArr as $dt) {
            $adnList[$dt['id']]['id']   = $dt['id'];                
            $adnList[$dt['id']]['name'] = $dt['adn_name'];                
        }

        return $adnList;
    }
    
    public function getTypeList() {
        $typeList = array();

        $typeList = $this->mo_traffic_model->getTypeList();

        return $typeList;
    }
    
    public function ajaxGetServiceList() {
        $params = $this->input->post('term');

        $data['response'] = 'false'; //Set Default Response
        $query = $this->mo_traffic_model->getServiceList($params);

        if ($query > 0) {
            $data['response'] = 'true'; //Set Response
            $data['result'] = array(); //Create Array

            foreach ($query as $row) {
                $data['message'][] = array('value' => $row->name);
            }
        }

        echo json_encode($data);
    }
    
    public function getTodayMOList(){
        $todayMO = array();

        $todayMO['total']           = $this->mo_traffic_model->getTodayMOTotal();
        $todayMO['yesterday']       = $this->mo_traffic_model->getTodayMOYesterday();
        $todayMO['lastsevendays']   = $this->mo_traffic_model->getTodayMOLastSevenDays();

        return $todayMO; 
    }
    
    public function getTotalMOList(){
        $totalMO = array();

        $totalMO['thismonth']       = $this->mo_traffic_model->getTotalMOThisMonth();
        $totalMO['lastmonths']      = $this->mo_traffic_model->getTotalMOLastMonths();
        $totalMO['lastsixmonths']   = $this->mo_traffic_model->getTotalMOLastSixMonths();

        return $totalMO;
    }

    public function getChartData() {

        $interval = 3;

        if(isset($_POST['chart_span']) && (int)$_POST['chart_span'] > 0)
        {
            $interval = (int)$_POST['chart_span'];
        }

        $data = $this->mo_traffic_model->getMOSummary(time(), $interval);
        $x = array();
        $line = array();

        foreach($data as $key => $value)
        {
            $x[] = $key;
            $line[] = (int)$value;
        }

        $chartData[] = array('label' => '', 'xAxis' => $x, 'value' => $line);


        /* @var $chart ci_chart */
        $this->load->library('CI_Chart');
        $result = $this->ci_chart->areaHolowChart($chartData, '');
		$result = str_replace('"rotate": "25"', '"rotate": "45"', $result);
		
		echo $result;

    }
}
