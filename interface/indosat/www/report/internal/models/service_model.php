<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getService($username, $password, $period, $shortCode, $operatorId='', $searchPattern='', $startFrom=0, $limit=9999) {
        $serviceName = 'getServiceReport';

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

	public function getServiceOperator($username, $password, $period, $service, $shortCode, $operatorId='') {
        $serviceName = 'getServiceOperatorReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }

	public function getServiceOperatorSubject($username, $password, $period, $service, $shortCode, $operatorId) {
        $serviceName = 'getServiceOperatorSubjectReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName),true);
    }
}