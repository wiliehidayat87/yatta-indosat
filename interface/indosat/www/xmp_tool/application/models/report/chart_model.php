<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chart_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getTrafficChart($username, $password, $startDate, $endDate, $top, $grouping) {
        $serviceName = 'getTrafficChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getRevenueChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $serviceName = 'getRevenueChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getRevenueChartl7($username, $password, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $serviceName = 'getRevenueChartl7';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyRevenueReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $serviceName = 'getDailyRevenueReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyRevenueReportChartl7($username, $password, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $serviceName = 'getDailyRevenueReportChartl7';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyTrafficReportChart($username, $password, $startDate, $endDate, $shortcode, $top, $grouping) {
        $serviceName = 'getDailyTrafficReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyRevenuePercentageReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $top, $grouping) {
        $serviceName = 'getDailyRevenuePercentageReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailySubcriberSubtotalReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage) {
        $serviceName = 'getDailySubcriberSubtotalReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailySubcriberRegUnregReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $service, $top, $grouping, $isPercentage) {
        $serviceName = 'getDailySubcriberRegUnregReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyUserReportChart($username, $password, $startDate, $endDate, $shortCode, $operatorId, $top, $grouping, $isPercentage) {
        $serviceName = 'getDailyUserReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getUserReportChart($username, $password, $startDate, $endDate, $shortCode, $operatorId, $top, $grouping, $isPercentage) {
        $serviceName = 'getUserReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('grouping', $grouping);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyDownloadContentReportChart($username, $password, $startDate, $endDate, $operatorId, $contentOwner, $contentType, $top, $isPercentage) {
        $serviceName = 'getDailyDownloadContentReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDownloadContentReportChart($username, $password, $startDate, $endDate, $operatorId, $contentType, $top, $isPercentage) {
        $serviceName = 'getDownloadContentReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentType', $contentType);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyCloseReasonReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage) {
        $serviceName = 'getDailyCloseReasonReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('sorting', $sorting);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getCloseReasonReportChart($username, $password, $startDate, $endDate, $operatorId, $shortcode, $service, $sorting, $top, $isPercentage) {
        $serviceName = 'getCloseReasonReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('sorting', $sorting);
        $this->my_curl->addParameter('top', $top);
        $this->my_curl->addParameter('isPercentage', $isPercentage);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyDownloadContentPercentageChart($username, $password, $month, $year, $operatorId='', $contentOwner='', $contentType='') {
        $serviceName = 'getDailyDownloadContentPercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getMonthlyDownloadContentPercentageChart($username, $password, $year, $operatorId='', $contentOwner='', $contentType='') {
        $serviceName = 'getMonthlyDownloadContentPercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyTrafficServicePercentageChart($username, $password, $month, $year, $shortcode, $operatorId='') {
        $serviceName = 'getDailyTrafficServicePercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyTrafficServicePercentageChartl7($username, $password, $month, $year, $shortcode, $operatorId='') {
        $serviceName = 'getDailyTrafficServicePercentageChartl7';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyRevenuePercentageChart($username, $password, $month, $year, $shortcode, $operatorId='') {
        $serviceName = 'getDailyRevenuePercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyUserPercentageChart($username, $password, $month, $year, $shortcode, $operatorId='') {
        $serviceName = 'getDailyUserPercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailySubscriberPercentageChart($username, $password, $month, $year, $shortcode, $operatorId='') {
        $serviceName = 'getDailySubscriberPercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getDailyCloseReasonPercentageChart($username, $password, $period, $shortcode, $operatorId='', $service='') {
        $serviceName = 'getDailyCloseReasonPercentageChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('service', $service);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getMonthlyDownloadContentReportChart($username, $password, $year, $operatorId='', $contentOwner='', $contentType='') {
        $serviceName = 'getMonthlyDownloadContentReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getMonthlyContentOwnerReportChart($username, $password, $year, $operatorId='', $contentType='') {
        $serviceName = 'getMonthlyContentOwnerReportChart';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getTopServiceHighSubscriber($username, $password, $period, $shortcode = '', $operatorId='') {
        $serviceName = 'getTopServiceHighSubscriber';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortcode', $shortcode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

}
?>