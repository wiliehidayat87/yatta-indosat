<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class L7_Model extends CI_Model {

    public function  __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getOperatorReportL7($username, $password, $period, $shortCode, $operatorId, $type) {
        $serviceName = 'getOperatorReportL7';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('type', $type);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getOperatorChargingReportL7($username, $password, $period, $operatorId, $type, $shortCode) {
        $serviceName = 'getOperatorChargingReportL7';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('type', strtoupper($type));
        $this->my_curl->addParameter('shortCode', $shortCode);
//        error_log($type, 3, "/tmp/l7.log");
        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}