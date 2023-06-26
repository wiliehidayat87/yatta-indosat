<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');

        write_log('info', 'Controller ' . $this->router->class . ' initialized');
        write_log('info', 'Loading model account_model');
        $this->load->model('account_model');
        write_log('info', 'Model account_model loaded');
        $this->load->model('chart_model');
        write_log('info', 'Model chart_model loaded');
        $this->load->model('dashboard_model');
        write_log('info', 'Model dashboard_model loaded');
        $this->load->library('ci_chart');
        write_log('info', 'Model ci_chart loaded');
        $this->load->library('ci_chart_wrapper');
        write_log('info', 'Model ci_chart_wrapper loaded');
        //     $this->load->library('session');
        //$this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'reporting', 'signOut')));

        $this->load->model('navigation_model');

        $this->ci_smarty->assign('navigation', $this->navigation_model->getMenuHtml());
        //  $this->ci_smarty->assign('menu', $this->privileges->parseMenu('admin', array('dashboard', 'operator', 'close_reason', 'traffic', 'service', 'subject', 'subscriber', 'content_download','user_report','signOut')));
    }

    public function index() {
        // if(isset($_SESSION[SSO_APP_NAME]['user'])){
        //    $_SESSION['userId']     = $_SESSION[SSO_APP_NAME]['user']->user_id;
        //    $_SESSION['username']   = $_SESSION[SSO_APP_NAME]['user']->username;
        //    $_SESSION['email']      = $_SESSION[SSO_APP_NAME]['user']->email;
        // }
        $userId = (!$this->session->userdata('userId')) ? !$this->session->userdata('userId') : 0;

        $dashboardNumber = 0;
        $this->session->set_userdata('dashboardNumber', $dashboardNumber);

        $dateRange = date("Y-m-d", strtotime('-7 day')) . ' - ' . date("Y-m-d");
        $this->ci_smarty->assign('dateRange', $dateRange);

        $response = $this->dashboard_model->getAllDashboard(API_USERNAME, API_PASSWORD, $userId);


        if ($response['status'] == "OK") {
            $chartData = array();

            if (!is_array($response['data']))
                $dashboard[0] = $response['data'];
            else
                $dashboard = $response['data'];

            $dashboardNumber = count($dashboard);
            $this->session->set_userdata('dashboardNumber', $dashboardNumber);

            foreach ($dashboard as $item) {
                $tmp = explode('&', $item['params']);
                $param = array();
                foreach ($tmp as $rowParam) {
                    list($key, $value) = explode('=', $rowParam);
                    $chartData[$item['priority']][$key] = $value;
                }

                $chartData[$item['priority']]['id'] = $item['id'];

                if ($chartData[$item['priority']]['title']) {
                    $chartData[$item['priority']]['title'] = ucwords(str_replace('_', ' ', $chartData[$item['priority']]['title']));
                }

                $chartData[$item['priority']]['json'] = $this->getChartDataWrapper(
                        $chartData[$item['priority']]['chart_type'], $chartData[$item['priority']]['call'], $chartData[$item['priority']]['rangedate'], $chartData[$item['priority']]['data'], $chartData[$item['priority']]['group']
                );
            }
        }

        $chart = array();
        $content = array();
        for ($i = 1; $i <= MAX_DASHBOARD_CHART; $i++) {
            if (isset($chartData[$i]['json'])) {
                if ($chartData[$i]['chart_type'] == 'table_summary') {
                    $content[$i] = $chartData[$i]['json'];

                    array_push($chart, $this->ci_chart->canvas('chart_box_' . $i, 290, 140, ''));
                } elseif ($chartData[$i]['chart_type'] == 'number') {
                    $content[$i] = $chartData[$i]['json'];

                    array_push($chart, $this->ci_chart->canvas('chart_box_' . $i, 290, 140, ''));
                } else {
                    array_push($chart, $this->ci_chart->canvas('chart_box_' . $i, 290, 140, $chartData[$i]['json']));
                }
            } else {
                array_push($chart, $this->ci_chart->canvas('chart_box_' . $i, 290, 140, ''));
            }
        }

        if (false !== isset($chartData)) {
            $this->ci_smarty->assign('chartData', $chartData);
        }

        $this->ci_smarty->assign('chart_box', $content);

        $jsFile = array('json2.js', 'chart.js', 'swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('jsScript', $chart);

        $this->ci_smarty->assign('title', 'XMP Internal :: Dashboard');
        $this->ci_smarty->assign('template', 'tpl_account_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }

    public function signOut() {
        $this->sso_client->logout();
    }

    public function addChart() {
        $index = $this->input->post('index', true);
        $chartData = array();

        if ($index) {
            $result = $this->dashboard_model->getDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $index);

            if ($result['status'] == "OK") {
                $response = (object) $result['data'];
                $chartData = $this->parseParam($response->params);
                $chartData['id'] = $response->id;
                $chartData['index'] = $response->priority;
                $chartData['rangedate'] = $this->rangeDateFilter($chartData['rangedate']);
            }
        } else {
            $chartData['rangeDate'] = date("Y-m-d", strtotime('-7 day')) . ' - ' . date("Y-m-d");
        }

        $result['status'] = 'OK';
        $result['message'] = 'Success';
        $result['data'] = $chartData;

        echo json_encode($result);
    }

    private function parseParam($param) {
        $paramArray = array();

        if ($param == '') {
            return '';
        }

        $tmp = explode('&', $param);

        foreach ($tmp as $item) {
            list($key, $value) = explode('=', $item);
            $paramArray[$key] = $value;
        }

        return $paramArray;
    }

    public function swapChart() {
        $indexBefore = $this->input->post('indexBefore', true);
        $indexAfter = $this->input->post('indexAfter', true);

        if ($indexAfter && $indexBefore) {
            $isSwap = $this->dashboard_model->swapDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $indexBefore, $indexAfter);

            if ($isSwap['status'] == "OK") {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                echo json_encode($result);
            } else {
                $result['status'] = 'NOK';
                $result['message'] = 'Failed';
                echo json_encode($result);
            }
        } else {
            $result['status'] = 'NOK';
            $result['message'] = 'Failed';
            echo json_encode($result);
        }
    }

    public function saveChartSetting() {
        $this->load->library('form');
        $error = false;
        $dashboardNumber = $this->session->userdata('dashboardNumber');

        $name = $this->input->post('name', true);
        $topic = $this->input->post('topic', true);
        $group = $this->input->post('group', true);
        $type = $this->input->post('type', true);
        $data = $this->input->post('data', true);
        $rangeDate = $this->input->post('rangedate', true);

        if ($dashboardNumber >= MAX_DASHBOARD_CHART) {
            $this->form->storeError('topic', 'Dasboard reached maximum number (' . MAX_DASHBOARD_CHART . ')');
            $error = true;
        }

        if ($topic == false || $topic == '') {
            $this->form->storeError('topic', 'Please select the Topic information');
            $error = true;
        }

        if ($group == false || $group == '') {
            $this->form->storeError('group', 'Please select the Group By information');
            $error = true;
        }

        if ($type == false || $type == '') {
            $this->form->storeError('chart_type', 'Please select the Type information');
            $error = true;
        }

        if ($data == false || $data == '') {
            $this->form->storeError('data', 'Please select the Data information');
            $error = true;
        }

        if ($rangeDate == false || $rangeDate == '') {
            $this->form->storeError('date', 'Please select the Range Date information');
            $error = true;
        }

        if ($error == true) {
            $result['status'] = 'NOK';
            $result['message'] = $this->form->parseErrorList();
            echo json_encode($result);
            exit;
        }

        $param = 'name=' . $name . '&title=' . $topic . '&chart_type=' . $type . '&data=' . $data . '&rangedate=' . $this->rangeDateFilter($rangeDate) . '&group=' . $group;

        switch ($topic) {
            case "traffic":
                $call = 'getTopTraffic';
                break;

            case "revenue":
                $call = 'getTopRevenue';
                break;
        }
        $param .= '&call=' . $call;

        $response = $this->dashboard_model->addDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $param);

        if ($response['status'] == "OK") {
            $result = $response;
            $dashboardNumber++;
            $this->session->set_userdata('dashboardNumber', $dashboardNumber);
        } else {
            $result['status'] = 'NOK';
            $result['message'] = 'There was a problem when generating the dashboard. Please contact the administrator.';
        }

        echo json_encode($result);
    }

    /**
     * update dashboard setting
     */
    public function updateChartSetting() {
        $this->load->library('form');
        $error = false;

        $id = $this->input->post('id', true);
        $name = $this->input->post('name', true);
        $topic = $this->input->post('topic', true);
        $group = $this->input->post('group', true);
        $type = $this->input->post('type', true);
        $data = $this->input->post('data', true);
        $rangeDate = $this->input->post('rangedate', true);

        if ($id == false || $id == '') {
            $this->form->storeError('id', 'Invalid Id');
            $error = true;
        }

        if ($topic == false || $topic == '') {
            $this->form->storeError('topic', 'Invalid Topic');
            $error = true;
        }

        if ($group == false || $group == '') {
            $this->form->storeError('group', 'Invalid Group by');
            $error = true;
        }

        if ($type == false || $type == '') {
            $this->form->storeError('chart_type', 'Invalid Type');
            $error = true;
        }

        if ($data == false || $data == '') {
            $this->form->storeError('data', 'Invalid Data');
            $error = true;
        }

        if ($rangeDate == false || $rangeDate == '') {
            $this->form->storeError('date', 'Invalid Range Date');
            $error = true;
        }

        if ($error == true) {
            $result['status'] = 'NOK';
            $result['message'] = $this->form->parseErrorList();
            echo json_encode($result);
            exit;
        }

        $param = 'name=' . $name . '&title=' . $topic . '&chart_type=' . $type . '&data=' . $data . '&rangedate=' . $this->rangeDateFilter($rangeDate) . '&group=' . $group;

        switch ($topic) {
            case "traffic":
                $call = 'getTopTraffic';
                break;

            case "revenue":
                $call = 'getTopRevenue';
                break;
        }

        $param .= '&call=' . $call;
        $response = $this->dashboard_model->modifyDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $param, $id);

        if ($response['status'] == "OK") {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
        } else {
            $result['status'] = 'NOK';
            $result['message'] = 'Failed';
        }

        echo json_encode($result);
    }

    /**
     * remove dashboard setting
     */
    public function removeChart() {
        $dashboardNumber = $this->session->userdata('dashboardNumber');

        $index = $this->input->post('index', true);
        $id = $this->input->post('id', true);

        if ($index) {
            $result = $this->dashboard_model->getDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $index);

            if ($result['status'] == "OK") {
                $data = (object) $result['data'];
                $id = $data->id;

                $response = $this->dashboard_model->deleteDashboard(API_USERNAME, API_PASSWORD, $this->session->userdata('userId'), $id);

                if ($response['status'] == "OK") {
                    $dashboardNumber--;
                    $this->session->set_userdata('dashboardNumber', $dashboardNumber);

                    $result['status'] = 'OK';
                    $result['message'] = 'Success';
                    echo json_encode($result);
                } else {
                    $result['status'] = 'NOK';
                    $result['message'] = 'Error when delete';
                    echo json_encode($result);
                }
            } else {
                $result['status'] = 'NOK';
                $result['message'] = 'Failed to resolve index.';
                echo json_encode($result);
            }
        } else {
            $result['status'] = 'NOK';
            $result['message'] = 'Invalid Index';

            echo json_encode($result);
        }
    }

    public function dateToString() {
        $date = $this->input->post('date', true);

        if ($date) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = $this->rangeDateFilter($date);
        } else {
            $result['status'] = 'NOK';
            $result['message'] = 'Invalid Parameter';
        }
        echo json_encode($result);
    }

    /**
     * convert date to string, vice versa
     * today
     * last 7 days
     * last 14 days
     * last 30 days
     * month to date
     * year to date
     * @param $date
     */
    private function rangeDateFilter($date) {
        if (stripos($date, ' - ') !== false) {
            list($startDate, $endDate) = explode(" - ", trim($date));

            if ($endDate == date("Y-m-d")) {
                list($sYear, $sMonth, $sDate) = explode('-', $startDate);

                if ($sDate == '1' && $sMonth == date("m")) {
                    return 'month to date';
                }

                if ($sDate == '01' && $sMonth == '01' && $sYear == date("Y")) {
                    return 'year to date';
                }

                $dateDiff = mktime(0, 0, 0, date("m"), date("d"), date("Y")) - mktime(0, 0, 0, $sMonth, $sDate, $sYear);
                $days = floor($dateDiff / (60 * 60 * 24));
                switch ($days) {
                    case "7":
                        return 'last 7 days';
                        break;

                    case "14":
                        return 'last 14 days';
                        break;

                    case "30":
                        return 'last 30 days';
                        break;

                    default:
                        return $date;
                        break;
                }
            } else {
                return $date;
            }
        } else {
            if ($date == date("Y-m-d")) {
                return 'today';
            }

            switch (strtolower($date)) {
                case 'today':
                    return date("Y-m-d");
                    break;

                case 'last 7 days':
                    return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 7, date("Y"))) . ' - ' . date("Y-m-d");
                    break;

                case 'last 14 days':
                    return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 14, date("Y"))) . ' - ' . date("Y-m-d");
                    break;

                case 'last 30 days':
                    return date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y"))) . ' - ' . date("Y-m-d");
                    break;

                case 'month to date':
                    return date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y"))) . ' - ' . date("Y-m-d");
                    break;

                case 'year to date':
                    return date("Y-m-d", mktime(0, 0, 0, 1, 1, date("Y"))) . ' - ' . date("Y-m-d");
                    break;

                default:
                    return $date;
                    break;
            }
        }
    }

    private function getTimePeriod($startDate, $endDate) {
        // select timeperiod
        $dateDiff = strtotime($endDate) - strtotime($startDate);
        $days = floor($dateDiff / (60 * 60 * 24));

        if ($days <= X_AXIS_POINT) {
            $timePeriod = 'daily';
        } elseif ($days <= 366) {
            $timePeriod = 'monthly';
        } else {
            $timePeriod = 'yearly';
        }
        return $timePeriod;
    }

    private function getChartPoint($startDate, $endDate) {
        // select timeperiod
        $dateDiff = strtotime($endDate) - strtotime($startDate);
        $days = floor($dateDiff / (60 * 60 * 24));
        $point = 0;

        if ($days <= X_AXIS_POINT) {
            $point = $days + 1;
        } elseif ($days <= 366) {
            $point = (int) date('m', strtotime($endDate)) - (int) date('m', strtotime($startDate)) + 1;
        } else {
            $point = (int) date('Y', strtotime($endDate)) - (int) date('Y', strtotime($startDate)) + 1;
        }

        return $point;
    }

    public function getChartDataWrapperAjax() {
        $name = $this->input->post('name', true);
        $topic = $this->input->post('topic', true);
        $group = $this->input->post('group', true);
        $type = $this->input->post('type', true);
        $data = $this->input->post('data', true);
        $rangeDate = $this->rangeDateFilter($this->input->post('rangedate', true));

        switch ($topic) {
            case "revenue":
                $functionName = 'getTopRevenue';
                break;

            case "traffic":
                $functionName = 'getTopTraffic';
        }

        $result['status'] = 'OK';
        $result['message'] = 'Success';

        if ($type == 'table_summary') {
            $result['data'] = $this->getChartDataWrapper($type, $functionName, $rangeDate, $data, $group);
        }
        if ($type == 'number') {
            $result['data'] = $this->getChartDataWrapper($type, $functionName, $rangeDate, $data, $group);
        } else {
            $result['data'] = json_decode($this->getChartDataWrapper($type, $functionName, $rangeDate, $data, $group));
        }

        echo json_encode($result);
    }

    private function getChartDataWrapper($type, $function, $rangedate, $top, $grouping) {
        $data = $this->{$function}($rangedate, $top, $grouping);

        switch ($type) {
            case 'table_summary':
                return $this->getTableSummaryData($data, false);
                break;

            case 'number':
                return $this->getTotalSummaryData($data, false);
                break;

            case 'line':
                return $this->getLineData($data, false);
                break;

            case 'multibar';
                return $this->getMultiBarData($data, false);
                break;

            case 'area':
                return $this->getAreaData($data, false);
                break;

            case 'bar':
                return $this->getBarData($data, false);
                break;

            case 'pie':
                return $this->getPieData($data, false);
                break;

            case 'stacked':
                return $this->getStackedData($data, false);
                break;
        }
    }

    private function getTopRevenue($rangedate, $top, $grouping) {
        $rangedate = $this->rangeDateFilter($rangedate);

        // check if rangedate format is double date
        if (stripos($rangedate, ' - ') !== false) {
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        } else {
            // single date format
            $startDate = trim($rangedate);
            $endDate = trim($rangedate);
        }

        $timePeriod = $this->getTimePeriod($startDate, $endDate);
        $point = $this->getChartPoint($startDate, $endDate);

        $result = $this->chart_model->getRevenueChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, '', '', $top, $grouping);

        if ($result['status'] == "OK") {
            if (!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else {
            $data = false;
        }

        return $this->normalizeChart($data, $timePeriod, $startDate, $endDate, $point);
    }

    private function getTopTraffic($rangedate, $top, $grouping) {
        $rangedate = $this->rangeDateFilter($rangedate);

        // check if rangedate format is double date
        if (stripos($rangedate, ' - ') !== false) {
            // is double date format
            list($startDate, $endDate) = explode(' - ', trim($rangedate));
        } else {
            // single date format
            $startDate = trim($rangedate);
            $endDate = trim($rangedate);
        }

        $timePeriod = $this->getTimePeriod($startDate, $endDate);
        $point = $this->getChartPoint($startDate, $endDate);

        //for stacked graph
        $result = $this->chart_model->getTrafficChart(API_USERNAME, API_PASSWORD, $startDate, $endDate, $top, $grouping);

        if ($result['status'] == "OK") {
            if (!is_array($result['data']))
                $data[0] = $result['data'];
            else
                $data = $result['data'];
        }
        else {
            $data = false;
        }

        return $this->normalizeStackedChart($data, $timePeriod, $startDate, $endDate, $point);
    }

    private function normalizeChart($data, $timePeriod, $startDate, $endDate, $point) {
        $reformatedData = array();
        list($sYear, $sMonth, $sDay) = explode('-', date("Y-m-d", strtotime($startDate)));

        if ($data == false || empty($data)) {
            error_log('false');
            return false;
        }

        foreach ($data as $row) {
            $row = (array) $row;
            if (empty($row)) {
                error_log('false');
                return false;
            } else {
//              if(count($row['data']) != $point){
                $yAxis = array();
                $data = array();
                $tmp1 = array();
                $tmp2 = array();

                if (is_array($row['xAxis'])) {
                    foreach ($row['xAxis'] as $key => $y) {
                        $tmp1[$y] = $row['value'][$key];
                    }
                } else {
                    $tmp1[$row['xAxis']] = $row['value'];
                }

                for ($i = 0; $i < $point; $i++) {
                    switch ($timePeriod) {
                        case 'daily':
                            $tmp2[date('Ymd', mktime(0, 0, 0, $sMonth, (int) $sDay + $i, $sYear))] = 0;
                            break;
                        case 'monthly':
                            $tmp2[date('Ym', mktime(0, 0, 0, (int) $sMonth + $i, 1, $sYear))] = 0;
                            break;
                        case 'yearly':
                            $tmp2[date('Y', mktime(0, 0, 0, 1, 1, (int) $sYear + $i))] = 0;
                            break;
                    }
                }

                foreach ($tmp1 as $key => $value) {
                    $tmp2[$key] = $value;
                }

                foreach ($tmp2 as $key => $value) {
                    if ($timePeriod == 'daily') {
                        $key = (int) substr($key, -2);
                    }
                    if ($timePeriod == 'monthly') {
                        if (strlen($key) == 6) {
                            $key = date('M', mktime(0, 0, 0, (int) substr($key, -2), 1, 2010));
                        } else {
                            $key = date('M', mktime(0, 0, 0, (int) substr($key, 4, 2), 1, 2010));
                        }
                    }
                    array_push($yAxis, $key);
                    array_push($data, $value);
                }

                unset($row['value']);
                $row['value'] = $data;
                unset($row['xAxis']);
                $row['xAxis'] = $yAxis;
//              }

                array_push($reformatedData, array(
                    'label' => $row['label'],
                    'value' => $row['value'],
                    'xAxis' => $row['xAxis']
                ));
            }
        }

        return $reformatedData;
    }

    private function normalizeStackedChart($data, $timePeriod, $startDate, $endDate, $point) {
        $rowData = array();

        if ($data == false || empty($data)) {
            error_log('false');
            return false;
        }

        $labelText = array();
        $labelId = array();
        foreach ($data as $row) {
            $row = (array) $row;
            if (empty($row)) {
                error_log('false');
                return false;
            } else {
                $yAxis = array();
                $data = array();
                $tmp1 = array();

                if (is_array($row['xAxis'])) {
                    foreach ($row['xAxis'] as $key => $y) {
                        $tmp1[$y] = $row['value'][$key];

                        if (!in_array($y, $labelId)) {
                            array_push($labelId, $y);
                            array_push($labelText, $y);
                        }
                    }
                } else {
                    if ($row['xAxis'] != 'null') {
                        $tmp1[$y] = $row['doubleData'];

                        if (!in_array($y, $labelId)) {
                            array_push($labelId, $y);
                            array_push($labelText, $y);
                        }
                    }
                }

                array_push($rowData, array(
                    'value' => $tmp1,
                    'name' => $row['label']
                ));
            }
        }

        foreach ($rowData as $index => $value) {
            foreach ($labelId as $i => $v) {
                $reformatedData[$index]['value'][$i] = (isset($rowData[$index]['value'][$v])) ? (int) $rowData[$index]['value'][$v] : 0;
                $reformatedData[$index]['xAxis'][$i] = $labelText[$i];
            }
            $reformatedData[$index]['label'] = $rowData[$index]['name'];
        }

        return $reformatedData;
    }

    public function getTableSummaryData($data, $ajaxCall=true) {
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                write_log('info', ' RESULT get table summary data. sessionId:' . $_SESSION['sessionId'] . '|result:' . print_r($result, true));

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($item['value'])) {
                    $data[$key]['value'] = array_sum($item['value']);
                } else {
                    $data[$key]['value'] = $item['value'];
                }
            }
        }


        $response = '<table class="rptRevenue">';
        $caption = 'cp';
        if ($_SESSION['mcpRole'] == 'CS') {
            $caption = 'service';
        }
        $response.= '<thead><tr><th>' . $caption . '</th><th>value</th></tr></thead>';
        foreach ($data as $item) {
            $response.= '<tr><td>' . $item['label'] . '</td><td>' . $item['value'] . '</td></tr>';
        }
        $response.= '</table>';

        return oneLiner($response);
    }

    public function getTotalSummaryData($data, $ajaxCall=true) {
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                write_log('info', ' RESULT get total summary data. sessionId:' . $_SESSION['sessionId']);
                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        $response = 0;
        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($item['value'])) {
                    $data[$key]['value'] = array_sum($item['value']);
                    $response += array_sum($item['value']);
                } else {
                    $data[$key]['value'] = $item['value'];
                    $response += $item['value'];
                }
            }
        }

        return "<div id='' style='margin-top:48px; font-size:32px; font-weight:bold; color:orange; text-align:center;'>" . number_format($response, 0, ',', '.') . "</div>";
    }

    public function getLineData($data, $ajaxCall=true) {
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                write_log('info', ' RESULT get line data. sessionId:' . $_SESSION['sessionId']);

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        $param['stepYAxis'] = Y_AXIS_STEP;
        $line = $this->ci_chart->lineChart($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($line);

            echo json_encode($result);
        } else {

            return $line;
        }
    }

    public function getMultiBarData($data, $ajaxCall=true) {
        $param = array();
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';
                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        $param['stepYAxis'] = Y_AXIS_STEP;
        $multibar = $this->ci_chart->barMultiChart3D($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($multibar);

            echo json_encode($result);
        } else {

            return $multibar;
        }
    }

    public function getAreaData($data, $ajaxCall=true) {
        $param = array();
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        $param['stepYAxis'] = Y_AXIS_STEP;
        $area = $this->ci_chart->areaHolowChart($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($area);

            echo json_encode($result);
        } else {

            return $area;
        }
    }

    public function getPieData($data, $ajaxCall=true) {
        $param = array();
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($item['value'])) {
                    $data[$key]['value'] = array_sum($item['value']);
                } else {
                    $data[$key]['value'] = $item['value'];
                }
            }
        }

        $pie = $this->ci_chart->pieChart($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($pie);

            echo json_encode($result);
        } else {

            return $pie;
        }
    }

    public function getBarData($data, $ajaxCall=true) {
        $param = array();
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $item) {
                if (is_array($item['value'])) {
                    $data[$key]['value'] = array_sum($item['value']);
                } else {
                    $data[$key]['value'] = $item['value'];
                }
            }
        }

        $param['stepYAxis'] = Y_AXIS_STEP;
        $bar = $this->ci_chart->barChart3D($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($bar);

            echo json_encode($result);
        } else {

            return $bar;
        }
    }

    public function getStackedData($data, $ajaxCall=true) {
        $param = array();
        if ($data == false) {
            if ($ajaxCall == true) {
                $result['status'] = 'OK';
                $result['message'] = 'Success';
                $result['data'] = '{}';

                echo json_encode($result);
                exit;
            } else {
                $param['nodata'] = true;
                $data = null;
            }
        }

        $param['stepYAxis'] = Y_AXIS_STEP;
        $bar = $this->ci_chart->stackedBarChart($data, '', $param);

        if ($ajaxCall == true) {
            $result['status'] = 'OK';
            $result['message'] = 'Success';
            $result['data'] = json_decode($bar);

            echo json_encode($result);
        } else {

            return $bar;
        }
    }

}

