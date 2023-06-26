<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Traffic_Model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->library('my_curl');
    }

    public function getTrafficReport($username, $password, $startDate, $endDate, $shortCode, $operatorId, $msisdn, $type, $subject, $request, $status, $startFrom, $limit, $includeArchive) {
        $serviceName = 'getTrafficReport';

        $this->my_curl->addParameter('username', $username);
        $this->my_curl->addParameter('password', $password);
        $this->my_curl->addParameter('startDate', $startDate);
        $this->my_curl->addParameter('endDate', $endDate);
        $this->my_curl->addParameter('shortCode', $shortCode);
        $this->my_curl->addParameter('operatorId', $operatorId);
        $this->my_curl->addParameter('msisdn', $msisdn);
        $this->my_curl->addParameter('type', $type);
        $this->my_curl->addParameter('subject', $subject);
        $this->my_curl->addParameter('request', $request);
        $this->my_curl->addParameter('status', $status);
        $this->my_curl->addParameter('startFrom', $startFrom);
        $this->my_curl->addParameter('limit', $limit);
        $this->my_curl->addParameter('includeArchive', $includeArchive);

        return json_decode($this->my_curl->execute(API_URL . $serviceName), true);
    }
}

