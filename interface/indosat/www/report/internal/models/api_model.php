<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getShortCode($username, $password, $startFrom = '', $limit = '', $searchPattern = '', $orderField = '', $order = '') {
        $serviceName = 'getShortCode';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);
        $this->my_curl->addParameter('searchPattern', $searchPattern);
        $this->my_curl->addParameter('orderField', $orderField);
        $this->my_curl->addParameter('order', $order);
        
        //var_dump(API_URL . $serviceName);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

    public function getOperator($username, $password, $shortCode, $startFrom = '', $limit = '', $searchPattern = '', $orderField = '', $order = '') {
        $serviceName = 'getOperator';
        if($shortCode===''){$shortCode='all';}
        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);
        $this->my_curl->addParameter('searchPattern', $searchPattern);
        $this->my_curl->addParameter('orderField', $orderField);
        $this->my_curl->addParameter('order', $order);

        $result = json_decode($this->my_curl->execute(API_URL . $serviceName), true);
        
        return $result;
    }

    public function getService($username, $password, $shortCode, $searchPattern='', $orderField='', $order='', $startFrom='', $limit='') {
        $serviceName = 'getService';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('searchPattern', $searchPattern);
        $this->my_curl->addParameter('orderField', $orderField);
        $this->my_curl->addParameter('order', $order);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }

	public function getContentOwner($username, $password, $searchPattern='', $startFrom='', $limit='') {
        $serviceName = 'getContentOwner';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('searchPattern', $searchPattern);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

