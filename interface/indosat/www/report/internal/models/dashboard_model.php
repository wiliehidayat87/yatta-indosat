<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }
    
    public function getAllDashboard($username, $password, $userId) {
        $serviceName = 'getAllDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
    
    public function getDashboard($username, $password, $userId, $index) {
        $serviceName = 'getDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);
        $this->my_curl->addParameter('index', $index);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
    
    public function swapDashboard($username, $password, $userId, $before, $after) {
        $serviceName = 'swapDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);
        $this->my_curl->addParameter('before', $before);
        $this->my_curl->addParameter('after', $after);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
    
    public function addDashboard($username, $password, $userId, $param) {
        $serviceName = 'addDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);
        $this->my_curl->addParameter('param', $param);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
    
    public function modifyDashboard($username, $password, $userId, $param, $id) {
        $serviceName = 'modifyDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);
        $this->my_curl->addParameter('param', $param);
        $this->my_curl->addParameter('id', $id);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
    
    public function deleteDashboard($username, $password, $userId, $id) {
        $serviceName = 'deleteDashboard';
        
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('userId', $userId);
        $this->my_curl->addParameter('id', $id);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

