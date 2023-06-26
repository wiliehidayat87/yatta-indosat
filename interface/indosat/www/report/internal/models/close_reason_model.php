<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Close_Reason_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getCloseReasonReport($username, $password, $shortCode, $operatorId, $serviceId, $period, $limit, $sorting) {
        $serviceName = 'getClosereasonReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('serviceId', $serviceId);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('limit', $limit);
        $this->my_curl->addParameter('sorting', $sorting);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getClosereasonServiceReport($username, $password, $shortCode, $operatorId, $serviceId, $period, $closeReason, $limit, $sorting) {
        $serviceName = 'getClosereasonServiceReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('serviceId', $serviceId);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('closereason', $closeReason);
        $this->my_curl->addParameter('limit', $limit);
        $this->my_curl->addParameter('sorting', $sorting);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

