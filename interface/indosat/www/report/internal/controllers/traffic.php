<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Traffic extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model traffic_model');
        $this->load->model('traffic_model');
        write_log('info', 'Model traffic_model loaded');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');

        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting')));
        $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report')));
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

        $jsFile = array('timepicker.js', 'internal_reporting_traffic.js');
        $this->ci_smarty->assign('jsFile', $jsFile);

        $this->ci_smarty->assign('title', 'XMS FO Internal :: Traffic Report');
        $this->ci_smarty->assign('template', 'tpl_traffic_report_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function getTrafficReportForTable() {
        $startDate = $this->input->post('startDate', 1);
        $endDate = $this->input->post('endDate', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $msisdn = $this->input->post('msisdn', 1);
        $type = $this->input->post('type', 1);
        $subject = $this->input->post('subject', 1);
        $request = $this->input->post('request', 1);
        $status = $this->input->post('status', 1);
        $limit = $this->input->post('limit', 1);
        $includeArchive = $this->input->post('includeArchive', 1);
        $page = $this->input->post('page', 1);

        $startDate = (empty($startDate)) ? date("Y-m-d", strtotime("-1 week")) . ' 00:00:00' : $startDate . ':00';
        $endDate = (empty($endDate)) ? date("Y-m-d") . ' 23:59:59' : $endDate . ':59';
        $shortCode = (empty($shortCode)) ? DEFAULT_SHORTCODE : $shortCode;
        $operatorId = (empty($operatorId)) ? '' : $operatorId;
        $type = (empty($type)) ? 'mo' : $type;
        $status = (empty($status)) ? 'all' : $status;
        $page = (empty($page)) ? 1 : $page;
        $limit = (empty($limit)) ? LIMIT : $limit;
        $startFrom = ($page - 1) * $limit;
        $includeArchive = (empty($includeArchive)) ? false : $includeArchive;

        $result = array();
        $apiResult = $this->traffic_model->getTrafficReport(API_USERNAME, API_PASSWORD, $startDate, $endDate, $shortCode, $operatorId, $msisdn, $type, $subject, $request, $status, $startFrom, $limit, $includeArchive);

        if ('OK' != $apiResult['status']) {
            $result = array(
                'status' => 'NOK',
                'message' => 'There was a problem preparing the data from database. Please try again later.',
                'data' => ''
            );
            die(json_encode($result));
        }

        if (0 == count($apiResult['data']) || (0 != count($apiResult['data']) && 0 == $apiResult['data'][0])) {
            $result = array(
                'status' => 'OK',
                'message' => 'There is no data available matching the selected criteria',
                'data' => ''
            );
            die(json_encode($result));
        }

        $rawTraffic = $apiResult['data'][1];
        $columnName = array('OPRT', 'FROM', 'TO', 'SUBJECT', 'SESSION ID', 'SERVICE ID', 'MESSAGE', 'TIMESTAMP', 'SERVICE', 'PARTNER', 'STATUS');
        $data = array();
		
		$count=count($rawTraffic);
        for ($i = 0; $i < $count; $i++) {
            //$data[] = array_values($rawTraffic[$i]);
            $data[] = array(
                substr($rawTraffic[$i]['IAC'], 0, 2),
                $rawTraffic[$i]['MSGFROM'],
                $rawTraffic[$i]['MSGTO'],
                $rawTraffic[$i]['SUBJECT'],
                $rawTraffic[$i]['MSGINDEX'],
                $rawTraffic[$i]['SERVICEID'],
                $rawTraffic[$i]['MSGDATA'],
                $rawTraffic[$i]['MSGTIMESTAMP'],
                $rawTraffic[$i]['SERVICE'],
                $rawTraffic[$i]['PARTNER'],
                $rawTraffic[$i]['MSGSTATUS']
            );
        }

        $previousPage = $page - 1;
        $nextPage = $page + 1;
        $totalPage = ceil($apiResult['data'][0] / $limit);

        $this->ci_smarty->assign('columnName', $columnName);
        $this->ci_smarty->assign('columnLength', count($columnName));
        $this->ci_smarty->assign('data', $data);
        $this->ci_smarty->assign('page', $page);
        $this->ci_smarty->assign('totalPage', $totalPage);
        $this->ci_smarty->assign('previousPage', ($page - 1));
        $this->ci_smarty->assign('nextPage', ($page + 1));

        $result = array(
            'status' => 'OK',
            'message' => '',
            'data' => toString($this->ci_smarty->fetch('tpl_traffic_report_table.tpl'))
        );
        die(json_encode($result));
    }
}

