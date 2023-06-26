<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getUserReport($username, $password, $period, $shortCode, $operatorId, $serviceId) {
        $serviceName = 'getUserReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('period', $period);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('service', $serviceId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

