<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Report extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model user_report_model');
        $this->load->model('user_report_model');
        write_log('info', 'Model user_report_model loaded');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report')));
        $this->ci_smarty->assign('defaultShortCode', DEFAULT_SHORTCODE);
    }

    public function index() {
        $operator = null;

        $result = $this->api_model->getOperator(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE, 0, 99999);
        $operator = ($result['status'] == 'OK') ? $result['data'][1] : null;
        $this->ci_smarty->assign('operator', $operator);
        
        $results = $this->api_model->getService(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE);
        $service = ($results['status'] == 'OK') ? $results['data'][1] : null;
        $this->ci_smarty->assign('service', $service);
        
        $jsFile = array('timepicker.js', 'internal_reporting_userReport.js');
        $this->ci_smarty->assign('jsFile', $jsFile);

        $this->ci_smarty->assign('title', 'XMS FO Internal :: User Report');
        $this->ci_smarty->assign('template', 'tpl_userReport_show.php');
        $this->ci_smarty->display('document.tpl');
    }

    public function getUserReportForTable() {
        $adn = $this->input->post('adn', 1);
        $date = $this->input->post('date', 1);
        $service = $this->input->post('service', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $channel = $this->input->post('channel', 1);
        $page = $this->input->post('page', 1);        
        $limit=10;
                
        $page = (empty($page)) ? 1 : $page;
        $limit = (empty($limit)) ? LIMIT : $limit;
        $startFrom = ($page - 1) * $limit;
        
    	if ($adn == '' && $date == '' && $service == '' && $operatorId == '' && $channel == '') {
        	$this->ci_smarty->assign('nodata', 1);
            $result = array(
                'status' => 'OK',
                'message' => 'There is no data available matching the selected criteria',
                'data' =>  toString($this->ci_smarty->fetch('tpl_userReport_table_show.php'))
            );
            die(json_encode($result));
        }

        $result = array();
        $apiResult = $this->user_report_model->getUserReport(API_USERNAME, API_PASSWORD, $adn, $operatorId, $service, $date, $channel, $startFrom, $limit);
        
        if ('OK' != $apiResult['status']) {
            $result = array(
                'status' => 'NOK',
                'message' => 'There was a problem preparing the data from database. Please try again later.',
                'data' => ''
            );
            die(json_encode($result));
        }

        if (0 == count($apiResult['data']) || (0 != count($apiResult['data']) && 0 == $apiResult['data'][0])) {
        	$this->ci_smarty->assign('nodata', 1);
            $result = array(
                'status' => 'OK',
                'message' => 'There is no data available matching the selected criteria',
                'data' =>  toString($this->ci_smarty->fetch('tpl_userReport_table_show.php'))
            );
            die(json_encode($result));
        }

        $rawUser = $apiResult['data'][1];
        $columnName = array('No', 'ADN', 'SERVICE', 'OPERATOR', 'CHANNEL', 'DATE SUBSCRIBED', 'DATE CREATED', 'TOTAL');
        $data = array();

        $numbering = ($page-1)*$limit;
        $sumTotal=0;
        
        $count=count($rawUser);
        for ($i = 0; $i < $count; $i++) {
            //$data[] = array_values($rawTraffic[$i]);
            $data[] = array(
            	($numbering + ($i+1)),
                $rawUser[$i]['adn'],
                $rawUser[$i]['service'],
                $rawUser[$i]['operator'],
                $rawUser[$i]['channel'],
                $rawUser[$i]['date_subscribed'],
                $rawUser[$i]['date_created'],
                $rawUser[$i]['total']
            );
            
            $sumTotal=$sumTotal+$rawUser[$i]['total'];
            
        }

        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $totalPage = ceil($apiResult['data'][0] / $limit);

        
        $this->ci_smarty->assign('columnName', $columnName);
        $this->ci_smarty->assign('columnLength', count($columnName));
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('sumTotal', $sumTotal);
        $this->ci_smarty->assign('nodata', 0);
        $this->ci_smarty->assign('page', $page);
        $this->ci_smarty->assign('totalPage', $totalPage);
        $this->ci_smarty->assign('previousPage', ($page - 1));
        $this->ci_smarty->assign('nextPage', ($page + 1));

        $result = array(
            'status' => 'OK',
            'message' => 'Success',
            'data' => toString($this->ci_smarty->fetch('tpl_userReport_table_show.php'))
        );
        die(json_encode($result));
    }
}

