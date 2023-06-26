<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Close_Reason extends CI_Controller
{
    public $closeReasonDelimiter;
    public $findThese;
    public $replaceWithThese;

    public function __construct() {
        parent::__construct();
        
		if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');  
        
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model close_reason_model');
        $this->load->model('close_reason_model');
        write_log('info', 'Model close_reason_model loaded');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');
        
        write_log('info', 'Loading model chart_model');
        $this->load->model('chart_model');
        write_log('info', 'Model chart_model loaded');

        $this->closeReasonDelimiter = '-_-';

        // for close reason, substitue:
        $this->findThese = array('=', '+');
        $this->replaceWithThese = array('_-_', '--__--');
        
        $this->load->library('ci_chart');
        $this->load->model('navigation_model');
        
        $this->ci_smarty->assign('navigation',   $this->navigation_model->getMenuHtml());
		$this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
        $this->ci_smarty->assign('closeReasonDelimiter', $this->closeReasonDelimiter);
    }

    public function index() {
        $shortCode = $operator = null;

        $result = $this->api_model->getShortCode(API_USERNAME, API_PASSWORD, 0, 99999);
        $shortCode = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('shortCode', $shortCode);

        $result = $this->api_model->getOperator(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE, 0, 99999);
        $operator = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('operator', $operator);

        $result = $this->api_model->getService(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE, '', '', '',0, 99999);
        $service = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('service', $service);
        
        $chart = array();
        array_push($chart, $this->getDailyCloseReasonReportChart());
        array_push($chart, $this->getCloseReasonReportChart());
        array_push($chart, $this->getDailyCloseReasonPercentageReportChart());
        
        $dummyData = array(array('label'=>'LOADING','value'=>array(0,0)));
        $chartData = $this->ci_chart->canvas('chart_traffic', 940, 200, $this->ci_chart->lineChart($dummyData));
        array_push($chart, $chartData);
        
    	$jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_close_reason.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('jsScript', $chart);

        $this->ci_smarty->assign('title', 'XMP Internal :: Close Reason Report');
        $this->ci_smarty->assign('template', 'tpl_close_reason_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getCloseReasonReportForTable() {
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $serviceId = $this->input->post('serviceId', 1);
        $display = $this->input->post('display', 1);
        $period = $this->input->post('period', 1);
        $limit = $this->input->post('limit', 1);
        $sorting = $this->input->post('sorting', 1);

        $shortCode = (empty($shortCode)) ? DEFAULT_SHORTCODE : $shortCode;
        $operatorId = (empty($operatorId)) ? '' : $operatorId;
        $serviceId = (empty($serviceId)) ? '' : $serviceId;
        $period = (empty($period)) ? date("Y-m") : $period;
        $limit = (empty($limit)) ? '' : $limit;
        $sorting = (empty($sorting)) ? 'total' : $sorting;

    	// check if 1st day on current month
    	if($period == date("Y-m") && date("d") == '01'){
			$period = date("Y") . '-' . ((int)date("m")-1);
    	}

        $result = array();
        $apiResult = $this->close_reason_model->getCloseReasonReport(API_USERNAME, API_PASSWORD, $shortCode, $operatorId, $serviceId, $period, $limit, $sorting);
        
        setDataSession(__CLASS__, $apiResult);
        
        if ('OK' != $apiResult['status']) {
            $result = array(
                'status' => 'NOK',
                'message' => 'There was a problem preparing the data from database. Please try again later.',
                'data' => ''
            );
            die(json_encode($result));
        }

        if (!isset($apiResult['data'][0]) || 0 == $apiResult['data'][0]) {
            $result = array(
                'status' => 'OK',
                'message' => 'There is no data available matching the selected criteria',
                'data' => ''
            );
            die(json_encode($result));
        }

        $rawCloseReason = $apiResult['data'][1];
        $columnName = array_merge(array('TOTAL'), array_keys($rawCloseReason[0]['daily']));
        $mostLeftColumn = array();
        $closeReason = $rows = array();

		$count=count($rawCloseReason);
        for ($i = 0; $i < $count; $i++) {
            $currentMandatory = $currentOptional = array();
            $closeReason[$rawCloseReason[$i]['operator']]['name'] = $rawCloseReason[$i]['operator'];
            $mostLeftColumn[$rawCloseReason[$i]['operator']]['name'] = $rawCloseReason[$i]['operator'];
            $mostLeftColumn[$rawCloseReason[$i]['operator']]['child'][] = array(
                'id' => $rawCloseReason[$i]['operatorId'] . $this->closeReasonDelimiter . str_replace($this->findThese, $this->replaceWithThese, base64_encode($rawCloseReason[$i]['closereason'])),
                'displayClosereason' => htmlentities($rawCloseReason[$i]['closereason']),
                'description' => substr($rawCloseReason[$i]['description'], 0, 40)
            );

            $currentMandatory = array(
                0 => array('total' => number_format($rawCloseReason[$i]['total'], 0, '.', ','))
            );

            foreach ($rawCloseReason[$i]['daily'] AS $key => $value) {
                $currentOptional[] = array(
                    'total' => number_format($value['total'], 0, '.', ','),
                    'color' => $value['color']
                );
            }

            $closeReason[$rawCloseReason[$i]['operator']]['child'][] = array_merge($currentMandatory, $currentOptional);
        }

        $this->ci_smarty->assign('columnName', $columnName);
        $this->ci_smarty->assign('columnLength', count($columnName));
        $this->ci_smarty->assign('mostLeftColumn', $mostLeftColumn);
        $this->ci_smarty->assign('closeReason', $closeReason);
        $this->ci_smarty->assign('closeReasonDelimiter', $this->closeReasonDelimiter);

        $result = array(
            'status' => 'OK',
            'message' => '',
            'data' => toString($this->ci_smarty->fetch('tpl_close_reason_report_table.tpl'))
        );
        die(json_encode($result));
    }

    public function getCloseReasonServiceReportForTable() {
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $serviceId = $this->input->post('serviceId', 1);
        $display = $this->input->post('display', 1);
        $period = $this->input->post('period', 1);
        $limit = $this->input->post('limit', 1);
        $sorting = $this->input->post('sorting', 1);
        $closeReason = base64_decode(str_replace($this->replaceWithThese, $this->findThese, $this->input->post('closeReason', 1)));
        write_log('debug', 'CR: ---- ' . $this->input->post('closeReason', 1));

        $result = array();
        $apiResult = $this->close_reason_model->getClosereasonServiceReport(API_USERNAME, API_PASSWORD, $shortCode, $operatorId, $serviceId, $period, $closeReason, $limit, $sorting);

        if ('OK' != $apiResult['status']) {
            $result = array(
                'status' => 'NOK',
                'message' => 'There was a problem preparing the data from database. Please try again later.',
                'data' => ''
            );
            die(json_encode($result));
        }

        if (0 == $apiResult['data'][0]) {
            $result = array(
                'status' => 'OK',
                'message' => 'There is no data available matching the selected criteria',
                'data' => ''
            );
            die(json_encode($result));
        }

        $rawCloseReason = $apiResult['data'][1];
        $service = $mostLeftColumn = $rightColumn = $currentRowColumn = array();
        $row = 0;
		
		$count2=count($rawCloseReason);
        for ($i = 0; $i < $count2; $i++) {
            $currentRowColumn = array();
            $mostLeftColumn[] = $rawCloseReason[$i]['service'];

            $daily = array_values($rawCloseReason[$i]['daily']);
			
			$count3=count($daily);
            for ($j = 0; $j < $count3; $j++) {
                $daily[$j]['total'] = number_format($daily[$j]['total'], 0, '.', ',');
            }

            $rightColumn[] = array_merge(array(
                0 => array('total' => number_format($rawCloseReason[$i]['total'], 0, '.', ','))),
                $daily
            );
        }

        $this->ci_smarty->assign('operatorId', $operatorId);
        $this->ci_smarty->assign('closeReason', $closeReason);
        $this->ci_smarty->assign('trId', $operatorId . $this->closeReasonDelimiter . str_replace($this->findThese, $this->replaceWithThese, base64_encode($closeReason)));
        $this->ci_smarty->assign('mostLeftColumn', $mostLeftColumn);
        $this->ci_smarty->assign('rightColumn', $rightColumn);
        //var_dump($rightColumn); exit;

        die(json_encode(array(
            'status' => 'OK',
            'message' => '',
            'data' => array(
                'left' => toString($this->ci_smarty->fetch('tpl_close_reason_report_table_subject_left.tpl')),
                'right' => toString($this->ci_smarty->fetch('tpl_close_reason_report_table_subject_right.tpl'))
            )
        )));
    }
    
	public function getChartData(){
    	$data = getDataSession(__CLASS__);
    	$chartData = array();
    	
//    	echo '<pre>';
//    	print_r($data);
//    	exit;
    	
    	if($data != false && $data['status'] == 'OK'){
    		//check if data exists
    		if(!isset($data['data'][1])){
    			echo '{}';
    			exit;
    		}
    		
    		// zero fill, avoid php notice
    		$chartData[0] = 0;
    		foreach($data['data'][1] as $row){
    			foreach($row['daily'] as $i => $daily){
					$chartData[(int)substr($i,3,2)] = 0;
    			}
    		}

    		foreach($data['data'][1] as $row){
	    		foreach($row['daily'] as $i => $daily){
					$chartData[(int)substr($i,3,2)] += $daily['total'];
	    		}
    		}
    	}
    	
    	ksort($chartData);
    	
    	$finalData = array(
    		array(
    			'label' => 'Total Traffic',
    			'value' => $chartData
    		)
    	);    	
    	
    	echo json_encode(json_decode($this->ci_chart->lineChart($finalData),1));
    }
    
    public function getDailyCloseReasonReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $service = trim($this->input->post('service', true));
        $sorting = trim($this->input->post('sorting', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        $pos = strrpos($period, "-");
        if ($pos === false) {
            $endDate    = date('Y-m-d');
            $startDate  = date("Y-m-d", strtotime("-".($period-1)." day", strtotime($endDate)));
            $daterange  = $startDate .' - '. $endDate;
        }
        else {
            list($year, $month) = explode('-', $period);
            $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;
            
	        if(date("Y") == $year && (int)date("m") == (int)$month){
				$totalDate = (int) date("d")-1;
			}
			else{
				$totalDate = $maxDate;
			}

            $daterange = $period.'-01 - '.$period.'-'.$totalDate;
        }
        
        $top=5;
        $chartData = $this->getDailyCloseReasonReport($daterange, $operatorId, $shortcode, $service, $sorting, $top);
        
        if($chartData == false){
            $param['nodata'] = true;
            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
        }
        
        if($ajaxCall != true){
            return  $this->ci_chart->canvas('chart_box_1', 940, 180, $this->ci_chart->lineChart($chartData, '', $param));
        }
        else{
            $result['status'] = "OK";
            $result['message']= "Success";
            $result['data']   = json_decode($this->ci_chart->lineChart($chartData, '', $param));
            
            echo json_encode($result);
        }
    }
    
    public function getCloseReasonReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $service = trim($this->input->post('service', true));
        $sorting = trim($this->input->post('sorting', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        $pos = strrpos($period, "-");
        if ($pos === false) {
            $endDate    = date('Y-m-d');
            $startDate  = date("Y-m-d", strtotime("-".($period-1)." day", strtotime($endDate)));
            $daterange  = $startDate .' - '. $endDate;
        }
        else {
            list($year, $month) = explode('-', $period);
            $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;
            
	        if(date("Y") == $year && (int)date("m") == (int)$month){
				$totalDate = (int) date("d")-1;
			}
			else{
				$totalDate = $maxDate;
			}

            $daterange = $period.'-01 - '.$period.'-'.$totalDate;
        }
        
        $top=5;
        $chartData = $this->getCloseReasonReport($daterange, $operatorId, $shortcode, $service, $sorting, $top);
        
        if($chartData == false){
            $param['nodata'] = true;
            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
        }
        
        if($ajaxCall != true){
            return  $this->ci_chart->canvas('chart_box_2', 450, 180, $this->ci_chart->pieChart($chartData, '', $param));
        }
        else{
            $result['status'] = "OK";
            $result['message']= "Success";
            $result['data']   = json_decode($this->ci_chart->pieChart($chartData, '', $param));
            
            echo json_encode($result);
        }
    }
    
    public function getDailyCloseReasonPercentageReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $service = trim($this->input->post('service', true));
        $sorting = trim($this->input->post('sorting', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(!$shortcode){
        	$shortcode = DEFAULT_SHORTCODE;
        }
        
        //$chartData = $this->getDailyCloseReasonReport($daterange, $operatorId, $shortcode, $service, $sorting, $top, true);
        $result = $this->chart_model->getDailyCloseReasonPercentageChart(
        	API_USERNAME,
        	API_PASSWORD,
        	$period,
        	$shortcode,
        	$operatorId,
        	$service
        );
        
        if($result['status'] == 'NOK'){
            $param['nodata'] = true;
            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
        }
        else{
        	$chartData = $result['data'];
        }
        
        if($ajaxCall != true){
            return  $this->ci_chart->canvas('chart_box_3', 450, 180, $this->ci_chart->lineChart($chartData, '', $param));
        }
        else{
            $result['status'] = "OK";
            $result['message']= "Success";
            $result['data']   = json_decode($this->ci_chart->lineChart($chartData, '', $param));
            
            echo json_encode($result);
        }
    }
    
    private function getCloseReasonReport($rangedate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage=false){
        // check if rangedate format is double date
        if(stripos($rangedate, ' - ') !== false){
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        }
        else{
            // single date format
            $startDate  = trim($rangedate);
            $endDate    = trim($rangedate);
        }

        $result = $this->chart_model->getCloseReasonReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage);
        
        if($result['status'] == "OK"){
            if(!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else{
            $data = false;
        }

        return $data;
    }
    
    private function getDailyCloseReasonReport($rangedate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage=false){
        // check if rangedate format is double date
        if(stripos($rangedate, ' - ') !== false){
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        }
        else{
            // single date format
            $startDate  = trim($rangedate);
            $endDate    = trim($rangedate);
        }

        $result = $this->chart_model->getDailyCloseReasonReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage);
        
        if($result['status'] == "OK"){
            if(!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else{
            $data = false;
        }

        return $data;
    }
}

