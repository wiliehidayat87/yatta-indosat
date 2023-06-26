<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subject extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');

        write_log('info', 'Loading model subject_model');
        $this->load->model('subject_model');
        write_log('info', 'Model subject_model loaded');
        
        $this->load->library('ci_chart');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report')));
        $this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
    }

    public function index(){
        $dummyData = array(array('label'=>'LOADING','value'=>array(0,0)));
        $chart = $this->ci_chart->canvas('chart_revenue', 600, 200, $this->ci_chart->lineChart($dummyData));
        
    	$jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_subject.js');
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

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Subject Report');
        $this->ci_smarty->assign('template', 'tpl_subject_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getSubjectTable(){
    	write_log('info','Call Method: ' . __METHOD__);
    	$period 		= $this->input->post('period',1);
    	$shortCode 		= $this->input->post('shortCode',1);
    	$operator 		= $this->input->post('operator',1);
    	$searchPattern 	= $this->input->post('searchPattern',1);
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

    	if(!$operator){
			$operator = '';
    	}
    	elseif($operator == 'all'){
			$operator = '';
    	}

    	if(!$searchPattern){
			$searchPattern = '';
    	}

		$result = $this->subject_model->getSubject(API_USERNAME,API_PASSWORD,$period,$shortCode,$operator,$searchPattern);

		setDataSession(__CLASS__, $result);
		
		if($result['status'] == 'OK'){
			if(isset($result['data'][1])){
				$subject = $result['data'][1];
			}
			else{
				$subject = false;
			}
		}
		else{
			$subject = false;
		}

		if(date("Y-m")==date("Y-m",strtotime($period))){
			$activeDays = date("d");
		}
		else{
			$activeDays = date("t",strtotime($period));
		}

		$this->ci_smarty->assign('days', $activeDays);
		$this->ci_smarty->assign('subject', $subject);
		$view = toString($this->ci_smarty->fetch('tpl_subject_table_show.tpl'));

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
    		foreach($data['data'][1] as $row){
    			foreach($row['daily'] as $i => $daily){
					$chartData[$i] = 0;
    			}
    		}

    		foreach($data['data'][1] as $row){
    			if($row['name'] != 'total'){
	    			foreach($row['daily'] as $i => $daily){
						$chartData[$i] += $daily['revenue'];
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
}
