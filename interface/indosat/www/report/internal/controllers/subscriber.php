<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriber extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');

        write_log('info', 'Loading model subject_model');
        $this->load->model('subscriber_model');
        write_log('info', 'Model subject_model loaded');
        
        write_log('info', 'Loading model chart_model');
        $this->load->model('chart_model');
        write_log('info', 'Model chart_model loaded');
        
        $this->load->library('ci_chart');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report')));
        $this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
    }

    public function index(){
        $chart = array();
        array_push($chart, $this->getDailySubcriberSubtotalReportChart());
        array_push($chart, $this->getDailySubcriberRegUnregReportChart());
        array_push($chart, $this->getDailySubcriberSubtotalPercentageReportChart());
        
        $dummyData = array(array('label'=>'LOADING','value'=>array(0,0)));
        $chartScript = $this->ci_chart->canvas('chart_traffic', 940, 200, $this->ci_chart->lineChart($dummyData));
        array_push($chart, $chartScript);
        
    	$jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_subscriber.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('jsScript', $chart);

        //get shortcode
        $result = $this->api_model->getShortCode(API_USERNAME,API_PASSWORD,0,99999);
        if($result['status'] == 'OK'){
        	$shortcode = $result['data'][1];
        }
        else{
			$shortcode = false;
        }
        $this->ci_smarty->assign('shortcode',$shortcode);

		$result = $this->api_model->getOperator(API_USERNAME,API_PASSWORD,DEFAULT_SHORTCODE,0,99999);
        if($result['status'] == 'OK'){
        	$operator = $result['data'][1];
        }
        else{
			$operator = false;
        }
        $this->ci_smarty->assign('operator',$operator);

        $result = $this->api_model->getService(API_USERNAME,API_PASSWORD,DEFAULT_SHORTCODE);
        if($result['status'] == 'OK'){
        	$service = $result['data'][1];
        }
        else{
			$service = false;
        }
        $this->ci_smarty->assign('service',$service);

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Subscriber Report');
        $this->ci_smarty->assign('template', 'tpl_subscriber_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getOperator(){
    	$shortCode = $this->input->post('shortCode',1);

    	if(!$shortCode){
			$shortCode = '';
    	}
    	$result = $this->api_model->getOperator(API_USERNAME,API_PASSWORD,$shortCode,0,99999);
        echo json_encode($result);
    }

    public function getSubscriberTable(){
    	write_log('info','Call Method: ' . __METHOD__);
    	$period 		= $this->input->post('period',1);
    	$shortCode 		= $this->input->post('shortCode',1);
    	$operatorId 	= $this->input->post('operatorId',1);
    	$service 		= $this->input->post('service',1);
    	$part 			= $this->input->post('part',1);

    	if($part){
    		$return = json_encode(array(
				'status' => 'OK',
				'message'=> 'Success',
				'data'	 => array(
					'part' 	=> (isset($_SESSION['data'][$part])) ? $part : 'EOF',
					'data'	=> $_SESSION['data'][$part-1]
				)
			));
			write_log('info','RETURN: ' . $return);
			echo $return;
			exit;
    	}

    	if(!$period){
			$period = date("Y-m");
    	}

    	// check if 1st day on current month
    	if($period == date("Y-m") && date("d") == '01'){
			$period = date("Y") . '-' . ((int)date("m")-1);
    	}

    	if(!$shortCode){
			$shortCode = DEFAULT_SHORTCODE;
    	}

    	if(!$operatorId){
			$operatorId = '';
    	}

    	if(!$service){
			$service = '';
    	}

		$result = $this->subscriber_model->getSubscriber(
			API_USERNAME,
			API_PASSWORD,
			$period,
			$shortCode,
			$operatorId,
			$service
		);
		
		setDataSession(__CLASS__, $result);

		if($result['status'] == 'OK'){
			if(isset($result['data'][1])){
				$subscriber = $result['data'][1];
			}
			else{
				$subscriber = false;
			}
		}
		else{
			$subscriber = false;
		}

		if(date("Y-m")==date("Y-m",strtotime($period))){
			$activeDays = date("d")-1;
		}
		else{
			$activeDays = date("t",strtotime($period));
		}

		$this->ci_smarty->assign('days', $activeDays);
		$this->ci_smarty->assign('subject', $subscriber);
		$view = toString($this->ci_smarty->fetch('tpl_subscriber_table_show.tpl'));

		$split = str_split($view,40960);
		$_SESSION['data'] = $split;

		if(count($split) == 1){
			echo json_encode(array(
				'status' => 'OK',
				'message'=> 'Success',
				'data'	 => $view
			));
		}
		else{
			echo json_encode(array(
				'status' => 'OK',
				'message'=> 'Success',
				'data'	 => array(
					'part' 	=> 1,
					'data'	=> $split[0]
				)
			));
		}
    }

	public function getSubjectOperator(){
    	$period 	= $this->input->post('period',1);
    	$shortCode 	= $this->input->post('shortCode',1);
    	$subject 	= $this->input->post('subject',1);
		$operatorId	= $this->input->post('operatorId',1);

    	if(!$period){
			$period = date("Y-m");
    	}

    	if(!$shortCode){
			$shortCode = DEFAULT_SHORTCODE;
    	}

    	if(!$subject){
			$subject = '';
    	}

		if(!$operatorId){
			$operatorId = '';
    	}
    	elseif($operatorId == 'all'){
			$operatorId = '';
    	}

		$result = $this->subject_model->getSubjectOperator(API_USERNAME, API_PASSWORD, $period, $subject, $shortCode, $operatorId);

		if($result['status'] == 'OK'){
			$subject = $result['data'][1];
		}
		else{
			$subject = false;
		}

		echo json_encode(array(
			'status' => 'OK',
			'message'=> 'Success',
			'data'	 => $subject
		));
    }
    
	public function getChartData(){
    	$data = getDataSession(__CLASS__);
    	$chartData = array();
    	
    	if($data != false && $data['status'] == 'OK'){
    		//check if data exists
    		if(!isset($data['data'][1])){
    			echo '{}';
    			exit;
    		}
    		
    		// zero fill, avoid php notice
    		$chartData['reg'][0] = 0;
    		$chartData['unreg'][0] = 0;
    		foreach($data['data'][1][0]['subject'][0]['daily'] as $i => $daily){
				$chartData['reg'][$i] = 0;
				$chartData['unreg'][$i] = 0;
    		}
    		
    		foreach($data['data'][1] as $row){
    			if($row['service'] != 'total'){
		    		foreach($row['subject'] as $subject){
		    			if($subject['name'] == 'reg'){
			    			foreach($subject['daily'] as $i => $daily){
								$chartData['reg'][$i] += $daily['total'];
								$label[$i] = $i;
			    			}
		    			}
		    			if($subject['name'] == 'unreg'){
			    			foreach($subject['daily'] as $i => $daily){
								$chartData['unreg'][$i] += $daily['total'];
			    			}
		    			}
		    		}
    			}
    		}
    	}
    	
    	ksort($label);
    	ksort($chartData['reg']);
    	ksort($chartData['unreg']);
    	
    	$finalData[] = array(
   			'label' => 'Total REG',
   			'value' => $chartData['reg'],
    	    'xAxis' => $label
    	);

    	$finalData[] = array(
   			'label' => 'Total UNREG',
   			'value' => $chartData['unreg'],
    	    'xAxis' => $label
    	);
    	
    	echo json_encode(json_decode($this->ci_chart->lineChart($finalData),1));
    }
    
    public function getDailySubcriberSubtotalReportChart(){
        $param = array();
        $operatorId = trim($this->input->post('operator', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $service = '';
        $period = trim($this->input->post('period', true));
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
        $chartData = $this->getDailySubcriberSubtotalReport($daterange, $operatorId, $shortcode, $service, $top, $group);
        
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
    
    public function getDailySubcriberRegUnregReportChart(){
        $param = array();
        $operatorId = trim($this->input->post('operator', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $service = '';
        $period = trim($this->input->post('period', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(!$shortcode){
        	$shortcode = DEFAULT_SHORTCODE;
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
        
        $top=''; 
        $group='service';
        $chartData = $this->getDailySubcriberRegUnregReport($daterange, $operatorId, $shortcode, $service, $top, $group);
        
        if($chartData == false){
            $param['nodata'] = true;
            $chartData = null;
        }
        
        if($ajaxCall != true){
            return $this->ci_chart->canvas('chart_box_2', 450, 180, $this->ci_chart->pieChart($chartData, '', $param));
        }
        else{
            $result['status'] = "OK";
            $result['message']= "Success";
            $result['data']   = json_decode($this->ci_chart->pieChart($chartData, '', $param));
            
            echo json_encode($result);
        }
    }
    
    public function getDailySubcriberSubtotalPercentageReportChart(){
        $param = array();
        $operatorId = trim($this->input->post('operator', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $service = '';
        $period = trim($this->input->post('period', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(!$shortcode){
        	$shortcode = DEFAULT_SHORTCODE;
        }
        
        list($year, $month) = explode('-', $period);
//        $chartData = $this->getDailySubcriberSubtotalReport($daterange, $operatorId, $shortcode, $service, $top, $group, true);
		$result = $this->chart_model->getDailySubscriberPercentageChart(
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
    
    private function getDailySubcriberSubtotalReport($rangedate, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage=false){
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

        $result = $this->chart_model->getDailySubcriberSubtotalReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage);
        
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
    
    private function getDailySubcriberRegUnregReport($rangedate, $operatorId, $shortcode, $service, $top, $grouping){
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

        $result = $this->chart_model->getDailySubcriberRegUnregReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $service, $top, $grouping, false);
        
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
