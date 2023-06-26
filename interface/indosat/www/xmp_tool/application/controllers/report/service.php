<?php

/*
 * 
 *  API Report tool  for XMP
 *  Internal Report by Service
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate$
 *  Last updated by   $Author$
 *  Last revision     $LastChangedRevision$
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends MY_Controller {

    public function __construct() {
        parent::__construct();

        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model service_model');
        $this->load->model('report/service_model');
        write_log('info', 'Model service_model loaded');

        write_log('info', 'Loading model api_model');
        $this->load->model('report/api_model');
        write_log('info', 'Model api_model loaded');

        write_log('info', 'Loading model chart_model');
        $this->load->model('report/chart_model');
        write_log('info', 'Model chart_model loaded');

        $this->load->library('ci_chart');
        
        $this->load->library('Link_auth');
        
        $this->smarty->assign('base_url',   base_url());
        $this->smarty->assign('cssPath',    base_url()."public/report_asset/css/");
        $this->smarty->assign('jsPath',     base_url()."public/report_asset/js/");
        $this->smarty->assign('pluginPath', base_url()."public/report_asset/plugin/");
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        
        /*if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }*/
        
        $chart = array();
        array_push($chart, $this->getDailyRevenueReportChart());
        array_push($chart, $this->getTopRevenueChart());
        array_push($chart, $this->getDailyRevenuePercentageReportChart());

        $dummyData = array(array('label' => 'LOADING', 'value' => array(0, 0)));
        $chartScript = $this->ci_chart->canvas('chart_revenue', 940, 200, $this->ci_chart->lineChart($dummyData));
        array_push($chart, $chartScript);

        $jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_reporting_service.js');
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('jsScript', $chart);

        //get shortcode
        $result = $this->api_model->getShortCode(API_USERNAME, API_PASSWORD, 0, 99999);
        if ($result['status'] == 'OK') {
            $shortcode = $result['data'][1];
        } else {
            $shortcode = false;
        }
        $this->smarty->assign('shortcode', $shortcode);

        $result = $this->api_model->getOperator(API_USERNAME, API_PASSWORD, DEFAULT_SHORTCODE, 0, 99999);
        if ($result['status'] == 'OK') {
            $operator = $result['data'][1];
        } else {
            $operator = false;
        }
        $this->smarty->assign('operator', $operator);

        $this->smarty->assign('title', 'XMP Internal :: Service Report');
        $this->smarty->assign('template', 'report/tpl_service_report_show.tpl');
        $this->smarty->display('report/coba.tpl');
    }

    public function getServiceTable() {
        write_log('info', 'Call Method: ' . __METHOD__);
        $period = $this->input->post('period', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $operatorId = $this->input->post('operatorId', 1);
        $searchPattern = $this->input->post('searchPattern', 1);
        $part = $this->input->post('part', 1);

        if ($part) {
            $return = json_encode(array(
                'status' => 'OK',
                'message' => 'Success',
                'data' => array(
                    'part' => (isset($_SESSION['data'][$part])) ? $part : 'EOF',
                    'data' => $_SESSION['data'][$part - 1]
                )
                    ));
            write_log('info', 'RETURN: ' . $return);
            echo $return;
            exit;
        }

        if (!$period) {
            $period = date("Y-m");
        }

        // check if 1st day on current month
        if ($period == date("Y-m") && date("d") == '01') {
            $period = date("Y") . '-' . ((int) date("m") - 1);
        }

        if (!$shortCode) {
            $shortCode = DEFAULT_SHORTCODE;
        }

        if (!$operatorId) {
            $operatorId = '';
        } elseif ($operatorId == 'all') {
            $operatorId = '';
        }

        if (!$searchPattern) {
            $searchPattern = '';
        }

        $result = $this->service_model->getService(API_USERNAME, API_PASSWORD, $period, $shortCode, $operatorId, $searchPattern);

        setDataSession(__CLASS__, $result);

        if ($result['status'] == 'OK') {
            if (isset($result['data'][1])) {
                $service = $result['data'][1];
            } else {
                $service = false;
            }
        } else {
            $service = false;
        }

        if (date("Y-m") == date("Y-m", strtotime($period))) {
            $activeDays = date("d") - 1;
        } else {
            $activeDays = date("t", strtotime($period));
        }

        $this->smarty->assign('days', $activeDays);
        $this->smarty->assign('subject', $service);
        $view = toString($this->smarty->fetch('tpl_service_table_show.tpl'));

        $split = str_split($view, 40960);
        $_SESSION['data'] = $split;

        if (count($split) == 1) {
            echo json_encode(array(
                'status' => 'OK',
                'message' => 'Success',
                'data' => $view
            ));
        } else {
            echo json_encode(array(
                'status' => 'OK',
                'message' => 'Success',
                'data' => array(
                    'part' => 1,
                    'data' => $split[0]
                )
            ));
        }
    }

    public function getServiceOperator() {
        $period = $this->input->post('period', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $service = $this->input->post('service', 1);
        $operatorId = $this->input->post('operatorId', 1);

        if (!$period) {
            $period = date("Y-m");
        }

        if (!$shortCode) {
            $shortCode = DEFAULT_SHORTCODE;
        }

        if (!$service) {
            $service = '';
        }

        if (!$operatorId) {
            $operatorId = '';
        } elseif ($operatorId == 'all') {
            $operatorId = '';
        }

        $result = $this->service_model->getServiceOperator(API_USERNAME, API_PASSWORD, $period, $service, $shortCode, $operatorId);

        if ($result['status'] == 'OK') {
            $service = $result['data'][1];
        } else {
            $service = false;
        }

        echo json_encode(array(
            'status' => 'OK',
            'message' => 'Success',
            'data' => $service
        ));
    }

    public function getServiceOperatorSubject() {
        $period = $this->input->post('period', 1);
        $shortCode = $this->input->post('shortCode', 1);
        $service = $this->input->post('service', 1);
        $operatorId = $this->input->post('operatorId', 1);

        if (!$period) {
            $period = date("Y-m");
        }

        if (!$shortCode) {
            $shortCode = DEFAULT_SHORTCODE;
        }

        if (!$service) {
            $service = '';
        }

        if (!$operatorId) {
            $operatorId = '';
        } elseif ($operatorId == 'all') {
            $operatorId = '';
        }

        $result = $this->service_model->getServiceOperatorSubject(API_USERNAME, API_PASSWORD, $period, $service, $shortCode, $operatorId);

        if ($result['status'] == 'OK') {
            $service = $result['data'][1];
        } else {
            $service = false;
        }

        echo json_encode(array(
            'status' => 'OK',
            'message' => 'Success',
            'data' => $service
        ));
    }

    public function getChartData() {
        $data = getDataSession(__CLASS__);
        $chartData = array();

        if ($data != false && $data['status'] == 'OK') {
            //check if data exists
            if (!isset($data['data'][1])) {
                echo '{}';
                exit;
            }

            // zero fill, avoid php notice
            $chartData[0] = 0;
            foreach ($data['data'][1] as $row) {
                foreach ($row['daily'] as $i => $daily) {
                    $chartData[$i] = 0;
                }
            }

            foreach ($data['data'][1] as $row) {
                if ($row['service'] != 'total') {
                    foreach ($row['daily'] as $i => $daily) {
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

        echo json_encode(json_decode($this->ci_chart->lineChart($finalData), 1));
    }

    public function getDailyRevenueReportChart() {
        $param = array();
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $period = trim($this->input->post('period', true));
        $ajaxCall = $this->input->post('ajaxCall', true);

        if ($period != true) {
            $period = date('Y-m');
        }

        if ($operatorId == 'all') {
            $operatorId = '';
        }

        list($year, $month) = explode('-', $period);
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $daterange = $period . '-01 - ' . $period . '-' . $totalDate;
        $top = 5;
        $group = 'service';
        $chartData = $this->getDailyRevenueReport($daterange, $operatorId, $shortcode, $top, $group);

        if ($chartData == false) {
            $param['nodata'] = true;
            $chartData = array(array('label' => 'NO DATA', 'value' => array(0, 0)));
        }

        if ($ajaxCall != true) {
            return $this->ci_chart->canvas('chart_box_1', 940, 180, $this->ci_chart->lineChart($chartData, '', $param));
        } else {
            $result['status'] = "OK";
            $result['message'] = "Success";
            $result['data'] = json_decode($this->ci_chart->lineChart($chartData, '', $param));

            echo json_encode($result);
        }
    }

    public function getTopRevenueChart() {
        $param = array();
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $period = trim($this->input->post('period', true));
        $ajaxCall = $this->input->post('ajaxCall', true);

        if ($period != true) {
            $period = date('Y-m');
        }

        if ($operatorId == 'all') {
            $operatorId = '';
        }

        list($year, $month) = explode('-', $period);
        $maxDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        if (date("Y") == $year && (int) date("m") == (int) $month) {
            $totalDate = (int) date("d") - 1;
        } else {
            $totalDate = $maxDate;
        }

        $daterange = $period . '-01 - ' . $period . '-' . $totalDate;
        $top = 10;
        $group = 'service';
        $chartData = $this->getTopRevenue($daterange, $operatorId, $shortcode, $top, $group);

        if ($chartData == false) {
            $param['nodata'] = true;
            $chartData = null;
        }

        if ($ajaxCall != true) {
            return $this->ci_chart->canvas('chart_box_2', 450, 180, $this->ci_chart->pieChart($chartData, '', $param));
        } else {
            $result['status'] = "OK";
            $result['message'] = "Success";
            $result['data'] = json_decode($this->ci_chart->pieChart($chartData, '', $param));

            echo json_encode($result);
        }
    }

    public function getDailyRevenuePercentageReportChart() {
        $param = array();
        $operatorId = trim($this->input->post('operatorId', true));
        $shortcode = trim($this->input->post('shortCode', true));
        $period = trim($this->input->post('period', true));
        $ajaxCall = $this->input->post('ajaxCall', true);

        if ($operatorId == 'all') {
            $operatorId = '';
        }

        if ($period != true) {
            $period = date('Y-m');
        }

        list($year, $month) = explode('-', $period);

        if (!$shortcode) {
            $shortcode = DEFAULT_SHORTCODE;
        }

        //$chartData = $this->getDailyRevenuePercentageReport($daterange, $operatorId, $shortcode, $top, $group);
        $result = $this->chart_model->getDailyRevenuePercentageChart(
                API_USERNAME, API_PASSWORD, $month, $year, $shortcode, $operatorId
        );

        if ($result['status'] == 'NOK') {
            $param['nodata'] = true;
            $chartData = array(array('label' => 'NO DATA', 'value' => array(0, 0)));
        } else {
            $chartData = $result['data'];
        }

        if ($ajaxCall != true) {
            return $this->ci_chart->canvas('chart_box_3', 450, 180, $this->ci_chart->lineChart($chartData, '', $param));
        } else {
            $result['status'] = "OK";
            $result['message'] = "Success";
            $result['data'] = json_decode($this->ci_chart->lineChart($chartData, '', $param));

            echo json_encode($result);
        }
    }

    private function getDailyRevenueReport($rangedate, $operatorId, $shortcode, $top, $grouping) {
        // check if rangedate format is double date
        if (stripos($rangedate, ' - ') !== false) {
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        } else {
            // single date format
            $startDate = trim($rangedate);
            $endDate = trim($rangedate);
        }

        $result = $this->chart_model->getDailyRevenueReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping);

        if ($result['status'] == "OK") {
            if (!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else {
            $data = false;
        }

        return $data;
    }

    private function getDailyRevenuePercentageReport($rangedate, $operatorId, $shortcode, $top, $grouping) {
        // check if rangedate format is double date
        if (stripos($rangedate, ' - ') !== false) {
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        } else {
            // single date format
            $startDate = trim($rangedate);
            $endDate = trim($rangedate);
        }

        $result = $this->chart_model->getDailyRevenuePercentageReportChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping);

        if ($result['status'] == "OK") {
            if (!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else {
            $data = false;
        }

        return $data;
    }

    private function getTopRevenue($rangedate, $operatorId, $shortcode, $top, $grouping) {
        // check if rangedate format is double date
        if (stripos($rangedate, ' - ') !== false) {
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        } else {
            // single date format
            $startDate = trim($rangedate);
            $endDate = trim($rangedate);
        }

        $result = $this->chart_model->getRevenueChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping);

        if ($result['status'] == "OK") {
            if (!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else {
            $data = false;
        }

        return $data;
    }    

}

?>