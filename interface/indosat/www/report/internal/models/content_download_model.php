<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class content_download_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getDownloadReportDaily($username, $password, $year, $month, $operatorId='', $contentOwner='', $contentType='') {
        $serviceName = 'getDownloadReportDaily';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('month', $month);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }

	public function getDownloadReportMonthly($username, $password, $year, $operatorId='', $contentOwner='', $contentType='') {
        $serviceName = 'getDownloadReportMonthly';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('year', $year);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('contentOwner', $contentOwner);
        $this->my_curl->addParameter('contentType', $contentType);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }
}