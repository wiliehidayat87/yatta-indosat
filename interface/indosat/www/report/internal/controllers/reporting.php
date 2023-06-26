<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporting extends CI_Controller
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

        write_log('info', 'Loading model service_model');
        $this->load->model('service_model');
        write_log('info', 'Model service_model loaded');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report')));
        $this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
    }

    public function operator() {
        $shortCode = $operator = null;

        $result = $this->api_model->getShortCode(API_USERNAME, API_PASSWORD, 0, 99999);
        $shortCode = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('shortCode', $shortCode);

        $result = $this->api_model->getOperator(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE, 0, 99999);
        $operator = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('operator', $operator);

        $jsFile = array('internal_reporting_revenue.js');
        $this->ci_smarty->assign('jsFile', $jsFile);

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Revenue Report');
        $this->ci_smarty->assign('template', 'tpl_revenue_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getOperatorReportForTable() {
        $period = $this->input->post('period', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);

        $period = (empty($period)) ? date("Y-m") : $period;
        $shortCode = (empty($shortCode)) ? DEFAULT_SHORTCODE : $shortCode;
        $operatorId = (empty($operatorId)) ? '' : $operatorId;

        $result = array();
        $apiResult = json_decode($this->reporting_model->getOperatorReport(API_USERNAME, API_PASSWORD, $period, $shortCode, $operatorId), true);

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

        $rawRevenue = $apiResult['data'][1];
        $columnName = array_merge(array('TOTAL', 'AVG', 'MONTH'), array_keys($rawRevenue[0]['moDaily']));
        $mostLeftColumn = array();

        $revenue = $rows = array();
        $dataType = array('MO', 'MT', 'DELIVERED', 'GROSS');

        $rawRevenueTemp = array_pop($rawRevenue);
        array_unshift($rawRevenue, $rawRevenueTemp);
		
		$count=count($rawRevenue);
        for ($i = 0; $i < $count; $i++) {
            $currentMandatory = $currentOptional = array();
            $revenue[$i]['name'] = $rawRevenue[$i]['operatorName'];
            $mostLeftColumn[] = array('id' => $rawRevenue[$i]['operatorId'], 'name' => $rawRevenue[$i]['operatorName']);
			
			$count2 = count($dataType);
            for ($j = 0; $j < $count2; $j++) {
                $currentOptional = array();
                $mostLeftColumn[] = array('id' => $rawRevenue[$i]['operatorId'], 'name' => $dataType[$j]);

                if ('MO' == $dataType[$j]) {
                    $currentMandatory = array(
                        number_format($rawRevenue[$i]['moTotal'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['moAverage'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['moMonthEnd'], 0, ',', '.')
                    );

                    foreach ($rawRevenue[$i]['moDaily'] AS $key => $value) {
                        $currentOptional[] = number_format($value, 0, ',', '.');
                    }

                    $revenue[$i]['child'][] = array_merge($currentMandatory, $currentOptional);
                }
                else if ('MT' == $dataType[$j]) {
                    $currentMandatory = array(
                        number_format($rawRevenue[$i]['mtTotal'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['mtAverage'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['mtMonthEnd'], 0, ',', '.')
                    );

                    foreach ($rawRevenue[$i]['mtDaily'] AS $key => $value) {
                        $currentOptional[] = number_format($value, 0, ',', '.');
                    }

                    $revenue[$i]['child'][] = array_merge($currentMandatory, $currentOptional);
                }
                else if ('DELIVERED' == $dataType[$j]) {
                    $currentMandatory = array(
                        number_format($rawRevenue[$i]['deliveredTotal'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['deliveredAverage'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['deliveredMonthEnd'], 0, ',', '.')
                    );

                    foreach ($rawRevenue[$i]['mtDaily'] AS $key => $value) {
                        $currentOptional[] = number_format($value, 0, ',', '.');
                    }

                    $revenue[$i]['child'][] = array_merge($currentMandatory, $currentOptional);
                }
                else if ('GROSS' == $dataType[$j]) {
                    $currentMandatory = array(
                        number_format($rawRevenue[$i]['grossTotal'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['grossAverage'], 0, ',', '.'),
                        number_format($rawRevenue[$i]['grossMonthEnd'], 0, ',', '.')
                    );

                    foreach ($rawRevenue[$i]['grossDaily'] AS $key => $value) {
                        $currentOptional[] = number_format($value, 0, ',', '.');
                    }

                    $revenue[$i]['child'][] = array_merge($currentMandatory, $currentOptional);
                }
            }
        }

        $this->ci_smarty->assign('columnName', $columnName);
        $this->ci_smarty->assign('columnLength', count($columnName));
        $this->ci_smarty->assign('mostLeftColumn', $mostLeftColumn);
        $this->ci_smarty->assign('revenue', $revenue);

        $result = array(
            'status' => 'OK',
            'message' => '',
            'data' => toString($this->ci_smarty->fetch('tpl_revenue_report_table.tpl'))
        );
        die(json_encode($result));
    }

    public function getOperatorChargingReportForTable() {
        $period = $this->input->post('period', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $type = $this->input->post('type', 1);

        $result = array();
        $apiResult = $this->reporting_model->getOperatorChargingReport(API_USERNAME, API_PASSWORD, $period, $operatorId, $type);
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
        $rawCharging = $apiResult['data'][1];
        $charging = $mostLeftColumn = $rightColumn = $currentRowColumn = array();
        $row = 0;
		
        foreach ($rawCharging AS $key => $value) {
			
			$count3=count($value);
            for ($i = 0; $i < $count3; $i++) {
                $currentRowColumn = array();
                $mostLeftColumn[] = $value[$i]['id'];
                $rightColumn[$row][] = array_merge(array(
                    $value[$i]['total'],
                    $value[$i]['average'],
                    $value[$i]['monthEnd']),
                    array_values($value[$i]['daily'])
                );
            }
        }

        $this->ci_smarty->assign('operatorId', $operatorId);
        $this->ci_smarty->assign('type', $type);

        $this->ci_smarty->assign('mostLeftColumn', $mostLeftColumn);
        $left = $this->ci_smarty->fetch('tpl_revenue_report_table_subject_left.tpl');

        $this->ci_smarty->assign('rightColumn', $rightColumn[0]);
        $right = $this->ci_smarty->fetch('tpl_revenue_report_table_subject_right.tpl');

        die(json_encode(array(
            'status' => 'OK',
            'message' => '',
            'data' => array(
                'left' => toString($left),
                'right' => toString($right)
            )
        )));
    }

    public function subject(){
    	$jsFile = array('internal_reporting_subject.js');
        $this->ci_smarty->assign('jsFile', $jsFile);

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

		if($result['status'] == 'OK'){
			$subject = $result['data'][1];
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

	public function service(){
    	$jsFile = array('internal_reporting_service.js');
        $this->ci_smarty->assign('jsFile', $jsFile);

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

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Service Report');
        $this->ci_smarty->assign('template', 'tpl_service_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

	public function getServiceTable(){
    	write_log('info','Call Method: ' . __METHOD__);
    	$period 		= $this->input->post('period',1);
    	$shortCode 		= $this->input->post('shortCode',1);
    	$operatorId		= $this->input->post('operatorId',1);
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

    	if(!$shortCode){
			$shortCode = DEFAULT_SHORTCODE;
    	}

    	if(!$operatorId){
			$operatorId = '';
    	}
    	elseif($operatorId == 'all'){
			$operatorId = '';
    	}

    	if(!$searchPattern){
			$searchPattern = '';
    	}

		$result = $this->service_model->getService(API_USERNAME,API_PASSWORD,$period,$shortCode,$operatorId,$searchPattern);

		if($result['status'] == 'OK'){
			$service = $result['data'][1];
		}
		else{
			$service = false;
		}

		if(date("Y-m")==date("Y-m",strtotime($period))){
			$activeDays = date("d");
		}
		else{
			$activeDays = date("t",strtotime($period));
		}

		$this->ci_smarty->assign('days', $activeDays);
		$this->ci_smarty->assign('subject', $service);
		$view = toString($this->ci_smarty->fetch('tpl_service_table_show.tpl'));

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

	public function getServiceOperator(){
    	$period 	= $this->input->post('period',1);
    	$shortCode 	= $this->input->post('shortCode',1);
    	$service 	= $this->input->post('service',1);
		$operatorId	= $this->input->post('operatorId',1);

    	if(!$period){
			$period = date("Y-m");
    	}

    	if(!$shortCode){
			$shortCode = DEFAULT_SHORTCODE;
    	}

    	if(!$service){
			$service = '';
    	}

		if(!$operatorId){
			$operatorId = '';
    	}
    	elseif($operatorId == 'all'){
			$operatorId = '';
    	}

		$result = $this->service_model->getServiceOperator(API_USERNAME, API_PASSWORD, $period, $service, $shortCode, $operatorId);

		if($result['status'] == 'OK'){
			$service = $result['data'][1];
		}
		else{
			$service = false;
		}

		echo json_encode(array(
			'status' => 'OK',
			'message'=> 'Success',
			'data'	 => $service
		));
    }

	public function getServiceOperatorSubject(){
    	$period 	= $this->input->post('period',1);
    	$shortCode 	= $this->input->post('shortCode',1);
    	$service 	= $this->input->post('service',1);
		$operatorId	= $this->input->post('operatorId',1);

    	if(!$period){
			$period = date("Y-m");
    	}

    	if(!$shortCode){
			$shortCode = DEFAULT_SHORTCODE;
    	}

    	if(!$service){
			$service = '';
    	}

		if(!$operatorId){
			$operatorId = '';
    	}
    	elseif($operatorId == 'all'){
			$operatorId = '';
    	}

		$result = $this->service_model->getServiceOperatorSubject(API_USERNAME, API_PASSWORD, $period, $service, $shortCode, $operatorId);

		if($result['status'] == 'OK'){
			$service = $result['data'][1];
		}
		else{
			$service = false;
		}

		echo json_encode(array(
			'status' => 'OK',
			'message'=> 'Success',
			'data'	 => $service
		));
    }
}

