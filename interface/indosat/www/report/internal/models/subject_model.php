<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subject_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getSubject($username, $password, $period, $shortCode='', $operatorId='', $searchPattern='', $startFrom=0, $limit=99999) {
        $serviceName = 'getSubjectReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('searchPattern', $searchPattern);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }

	public function getSubjectOperator($username, $password, $period, $subject, $shortCode='', $operatorId='') {
        $serviceName = 'getSubjectOperatorReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('subject', $subject);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }
}