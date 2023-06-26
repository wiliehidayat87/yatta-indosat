<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content_Download extends CI_Controller
{
    public function __construct() {
        parent::__construct();
		write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');

        write_log('info', 'Loading model content_download_model');
        $this->load->model('content_download_model');
        write_log('info', 'Model content_download_model loaded');
        
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
        array_push($chart, $this->getDailyDownloadContentReportChart());
        array_push($chart, $this->getDownloadContentReportChart());
        array_push($chart, $this->getDailyDownloadContentPercentageReportChart());
        
        $dummyData = array(array('label'=>'LOADING','value'=>array(0,0)));
        $chartData = $this->ci_chart->canvas('chart_revenue', 940, 200, $this->ci_chart->lineChart($dummyData));
        array_push($chart, $chartData);
         
    	$jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_content_download.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('jsScript', $chart);

		$result = $this->api_model->getOperator(API_USERNAME,API_PASSWORD,DEFAULT_SHORTCODE,0,99999);
        if($result['status'] == 'OK'){
        	$operator = $result['data'][1];
        }
        else{
			$operator = false;
        }
        $this->ci_smarty->assign('operator',$operator);

        $result = $this->api_model->getContentOwner(API_USERNAME,API_PASSWORD);
        if($result['status'] == 'OK'){
        	$cOwner = $result['data'][1];
        }
        else{
			$cOwner = false;
        }
        $this->ci_smarty->assign('contentOwner',$cOwner);

        $this->ci_smarty->assign('contentType',json_decode(CONTENT_TYPE,true));

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Content Download Report');
        $this->ci_smarty->assign('template', 'tpl_content_download_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getContentDownloadTable(){
    	write_log('info','Call Method: ' . __METHOD__);
    	$mode 			= $this->input->post('mode',1);
    	$year			= $this->input->post('year',1);
    	$month			= $this->input->post('month',1);
    	$operatorId 	= $this->input->post('operatorId',1);
    	$contentOwner	= $this->input->post('contentOwner',1);
    	$contentType	= $this->input->post('contentType',1);
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

    	if(!$mode){
			$mode = 'daily';
    	}

    	if(!$year){
			$year = date('Y');
    	}else{
			if((int)$year > (int)date("Y")){
				echo json_encode(array(
					'status' => 'NOK',
					'message'=> 'year is too forward. maximum year is current year.'
				));
			}
    	}

    	if(!$month){
			$month = date('m');
    	}

    	// check if 1st day on current month
    	if(date("Y-m",strtotime($year . '-' . $month)) == date("Y-m") && date("d") == '01'){
			$year = date("Y");
			$month= (int)date("m")-1;
    	}

    	if(!$operatorId){
			$operatorId = '';
    	}

    	if(!$contentOwner){
			$contentOwner = '';
    	}

    	if(!$contentType){
			$contentType = '';
    	}

    	if($mode == 'daily'){
			$result = $this->content_download_model->getDownloadReportDaily(
				API_USERNAME,
				API_PASSWORD,
				$year,
				$month,
				$operatorId,
				$contentOwner,
				$contentType
			);

	    	if(date("Y-m")==date("Y-m",strtotime($year.'-'.$month))){
				$columns = date("d")-1;
			}
			else{
				$columns = date("t",strtotime($year.'-'.$month));
			}
			$result['mode'] = 'daily';
    	}
    	else{
			$result = $this->content_download_model->getDownloadReportMonthly(
				API_USERNAME,
				API_PASSWORD,
				$year,
				$operatorId,
				$contentOwner,
				$contentType
			);

    		if( date("Y") == $year ){
				$columns = date("m");
			}
			else{
				$columns = 12;
			}
			$result['mode'] = 'monthly';
    	}

    	setDataSession(__CLASS__, $result);

		if($result['status'] == 'OK'){
			if(isset($result['data'][1])){
				$download = $result['data'][1];
			}
			else{
				$download = false;
			}
		}
		else{
			$download = false;
		}

		$this->ci_smarty->assign('mode', $mode);
		$this->ci_smarty->assign('columns', $columns);
		$this->ci_smarty->assign('download', $download);
		$view = toString($this->ci_smarty->fetch('tpl_content_download_table_show.tpl'));

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
    		$chartData[0] = 0;
//    		foreach($data['data'][1] as $row){
//    			foreach($row['daily'] as $i => $daily){
//					$chartData[$i] = 0;
//    			}
//    		}

    		// grab total
    		if($data['mode']=='daily'){
	    		foreach($data['data'][1] as $row){
	    			if($row['code'] == 'total'){
		    			foreach($row['daily'] as $i => $daily){
							$chartData[$i] = $daily['revenue'];
		    			}
	    			}
	    		}
    		}
    		else{
	    		foreach($data['data'][1] as $row){
		    			if($row['code'] == 'total'){
			    			foreach($row['monthly'] as $i => $monthly){
								$chartData[$i] = $monthly['revenue'];
			    			}
		    			}
		    		}
	    		}
    		}

    	$finalData = array(
    		array(
    			'label' => 'Total Revenue',
    			'value' => $chartData
    		)
    	);

    	echo json_encode(json_decode($this->ci_chart->lineChart($finalData),1));
    }
    
    public function getDailyDownloadContentReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $contentOwner = trim($this->input->post('contentOwner', true));
        $contentType = trim($this->input->post('contentType', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(stripos($period,'-') !== false){
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
	        $chartData = $this->getDailyDownloadContentReport($daterange, $operatorId, $contentOwner, $contentType, $top);
	        
	        if($chartData == false){
	            $param['nodata'] = true;
	            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
	        }
        }
        else{
			$result = $this->chart_model->getMonthlyDownloadContentReportChart(
				API_USERNAME,
				API_PASSWORD,
				$period,
				$operatorId,
				$contentOwner,
				$contentType
			);

			if($result['status'] == 'NOK' || $result['data'] == false){
				$param['nodata'] = true;
	            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
			}
			else{
				$chartData = $result['data'];
			}
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
    
    public function getDownloadContentReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $contentType = trim($this->input->post('contentType', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(stripos($period,'-') !== false){
	        list($year, $month) = explode('-', $period);
	        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;
	
	        if(date("Y") == $year && (int)date("m") == (int)$month){
				$totalDate = (int) date("d")-1;
			}
			else{
				$totalDate = $maxDate;
			}
		
	        $daterange = $period.'-01 - '.$period.'-'.$totalDate;
	        $top=10;
	        $chartData = $this->getDownloadContentReport($daterange, $operatorId, $contentType, $top);
	        
	        if($chartData == false){
	            $param['nodata'] = true;
	            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
	        }
	        $title = '';
        }
        else{
        	$result = $this->chart_model->getMonthlyContentOwnerReportChart(
				API_USERNAME,
				API_PASSWORD,
				$period,
				$operatorId,
				$contentType
			);

			if($result['status'] == 'NOK' || $result['data'] == false){
				$param['nodata'] = true;
	            $chartData = array(array('label'=>'NO DATA','value'=>array(0,0)));
			}
			else{
				$chartData = $result['data'];
			}
			
			$title = '*)value in million';
			$param['titleStyle'] = "{font-size:8px;margin-left:180px;}";
        }
        
        if($ajaxCall != true){
            return  $this->ci_chart->canvas('chart_box_2', 450, 180, $this->ci_chart->pieChart($chartData, $title, $param));
        }
        else{
            $result['status'] = "OK";
            $result['message']= "Success";
            $result['data']   = json_decode($this->ci_chart->pieChart($chartData, $title, $param));
            
            echo json_encode($result);
        }
    }
    
    public function getDailyDownloadContentPercentageReportChart(){
        $param = array();
        $period = trim($this->input->post('period', true));
        $operatorId = trim($this->input->post('operatorId', true));
        $contentOwner = trim($this->input->post('contentOwner', true));
        $contentType = trim($this->input->post('contentType', true));
        $ajaxCall = $this->input->post('ajaxCall', true);
        
        if($period != true){
            $period = date('Y-m');
        }
        
        if(stripos($period,'-') !== false){
        	list($year, $month) = explode('-', $period);
              
//        $chartData = $this->getDailyDownloadContentReport($daterange, $operatorId, $contentOwner, $contentType, $top, true);
			$result = $this->chart_model->getDailyDownloadContentPercentageChart(
				API_USERNAME, 
				API_PASSWORD,
				$month,
				$year, 
				$operatorId, 
				$contentOwner, 
				$contentType
			);	
        }
        else{
        	$result = $this->chart_model->getMonthlyDownloadContentPercentageChart(
				API_USERNAME, 
				API_PASSWORD,
				$period, 
				$operatorId, 
				$contentOwner, 
				$contentType
			);
        }
        
        
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
    
    private function getDownloadContentReport($rangedate, $operatorId, $contentType, $top, $isPercentage=false){
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

        $result = $this->chart_model->getDownloadContentReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $contentType, $top, $isPercentage);
        
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
    
    private function getDailyDownloadContentReport($rangedate, $operatorId, $contentOwner, $contentType, $top, $isPercentage=false){
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

        $result = $this->chart_model->getDailyDownloadContentReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $contentOwner, $contentType, $top, $isPercentage);
        
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
