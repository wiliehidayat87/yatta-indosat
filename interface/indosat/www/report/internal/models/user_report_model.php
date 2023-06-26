<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_report_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getUserReport($username, $password, $adn, $operatorId, $service, $date, $channel, $startFrom, $limit) {
        $serviceName = 'getUserReport';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('adn', $adn);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('service', $service);
        $this->my_curl->addParameter('date', $date);
        $this->my_curl->addParameter('channel', $channel);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

