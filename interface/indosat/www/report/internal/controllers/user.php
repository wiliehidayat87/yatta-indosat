<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model user_model');
        $this->load->model('user_model');
        write_log('info', 'Model user_model loaded');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');
        
        write_log('info', 'Loading model chart_model');
        $this->load->model('chart_model');
        write_log('info', 'Model chart_model loaded');
        
        $this->load->library('ci_chart');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'user', 'content_download','user_report')));
        $this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
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
        array_push($chart, $this->getDailyUserReportChart());
        array_push($chart, $this->getUserReportChart());
        array_push($chart, $this->getDailyUserPercentageReportChart());
        
        $dummyData = array(array('label'=>'LOADING','value'=>array(0,0)));
        $chartScript = $this->ci_chart->canvas('chart_traffic', 940, 200, $this->ci_chart->lineChart($dummyData));
        array_push($chart, $chartScript);
        
    	$jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_user.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('jsScript', $chart);

        $this->ci_smarty->assign('title', 'XMS FO Internal :: User Report');
        $this->ci_smarty->assign('template', 'tpl_user_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getUserReportForTable() {
        $period = $this->input->post('period', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $serviceId = $this->input->post('serviceId', 1);

        $period = (empty($period)) ? date("Y-m") : $period;
        $shortCode = (empty($shortCode)) ? DEFAULT_SHORTCODE : $shortCode;
        $operatorId = (empty($operatorId)) ? '' : $operatorId;
        $serviceId = (empty($serviceId)) ? '' : $serviceId;

    	// check if 1st day on current month
    	if($period == date("Y-m") && date("d") == '01'){
			$period = date("Y") . '-' . ((int)date("m")-1);
    	}

        $result = array();
        $apiResult = $this->user_model->getUserReport(API_USERNAME, API_PASSWORD, $period, $shortCode, $operatorId, $serviceId);

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

        $rawUser = $apiResult['data'][1];
        /*$rawUser = array(
            0 => array(
                'service' => 'ADZAN',
                'daily' => array(
                    '3' => 3000,
                    '2' => 2000,
                    '1' => 1000
                )
            ),
            1 => array(
                'service' => 'ZIKIR',
                'daily' => array(
                    '3' => 31111,
                    '2' => 21111,
                    '1' => 11111
                )
            )
        );*/
        $columnName = array_keys($rawUser[0]['daily']);
        $mostLeftColumn = $rightColumn = array();

		$count=count($rawUser);
        for ($i = 0; $i < $count; $i++) {
            $currentMandatory = $currentOptional = array();
            $mostLeftColumn[] = array(
                'service' => $rawUser[$i]['service']
            );
			
			$count2=count($rawUser[$i]['daily']);
            for ($j = 0; $j < $count2; $j++) {
                $daily = array_values($rawUser[$i]['daily']);
				
				$count3=count($daily);
                for ($j = 0; $j < $count3; $j++) {
                    $daily[$j]['total'] = number_format($daily[$j]['total'], 0, '.', ',');
                    $daily[$j]['color'] = $daily[$j]['color'];
                }

                $rightColumn[$i] = $daily;
            }
        }

        $this->ci_smarty->assign('columnName', $columnName);
        $this->ci_smarty->assign('columnLength', count($columnName));
        $this->ci_smarty->assign('mostLeftColumn', $mostLeftColumn);
        $this->ci_smarty->assign('rightColumn', $rightColumn);

        $result = array(
            'status' => 'OK',
            'message' => '',
            'data' => toString($this->ci_smarty->fetch('tpl_user_report_table.tpl'))
        );
        die(json_encode($result));
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
					$chartData[$i] = 0;
    			}
    		}

    		foreach($data['data'][1] as $row){
    			if($row['service'] != 'total'){
		    		foreach($row['daily'] as $i => $daily){
						$chartData[$i] += $daily['total'];
		    		}
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
    
    public function getDailyUserReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        list($year, $month) = explode('-', $period);
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;
        
    	if(date("Y") == $year && (int)date("m") == (int)$month){
			$totalDate = (int) date("d")-1;
		}
		else{
			$totalDate = $maxDate;
		}

        $daterange = $period.'-01 - '.$period.'-'.$totalDate;
        $top=5; 
        $group='service';
        $chartData = $this->getDailyUserReport($daterange, $shortcode, $operatorId, $top, $group);
                        
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
    
    public function getUserReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }

        if($shortcode == ''){
        	$shortcode = DEFAULT_SHORTCODE;
        }

        $result = $this->chart_model->getTopServiceHighSubscriber(
        	API_USERNAME,
        	API_PASSWORD,
        	$period,
        	$shortcode,
        	$operatorId
        );
        
        if($result['status'] == 'NOK'){
            $param['nodata'] = true;
            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
        }
        else{
        	$chartData = $result['data'];
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
    
    public function getDailyUserPercentageReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $shortcode = trim($this->input->post('shortcode', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(!$shortcode){
        	$shortcode = DEFAULT_SHORTCODE;
        }
        
        list($year, $month) = explode('-', $period);

//        $chartData = $this->getDailyUserReport($daterange, $shortcode, $operatorId, $top, $group, true);
		$result = $this->chart_model->getDailyUserPercentageChart(
			API_USERNAME,
			API_PASSWORD,
			$month,
			$year,
			$shortcode,
			$operatorId			
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
    
    private function getUserReport($rangedate, $shortCode, $operatorId, $top, $grouping, $isPercentage=false){
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

        $result = $this->chart_model->getUserReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $shortCode, $operatorId, $top, $grouping, $isPercentage);
        
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
    
    private function getDailyUserReport($rangedate, $shortCode, $operatorId, $top, $grouping, $isPercentage=false){
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

        $result = $this->chart_model->getDailyUserReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $shortCode, $operatorId, $top, $grouping, $isPercentage);
        
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

