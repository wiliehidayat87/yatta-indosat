<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operator_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getOperatorReport($username, $password, $period, $shortCode, $operatorId) {
        $serviceName = 'getOperatorReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getOperatorChargingReport($username, $password, $period, $operatorId, $type, $shortCode) {
        $serviceName = 'getOperatorChargingReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('type', strtoupper($type));
        $this->my_curl->addParameter('shortCode', $shortCode);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

