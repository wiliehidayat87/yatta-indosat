<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        write_log('info', 'Controller ' . $this->router->class . ' initialized');

        write_log('info', 'Loading model api_model');
        $this->load->model('api_model');
        write_log('info', 'Model api_model loaded');
    }

    public function getOperator() {
        $shortCode = $this->input->post('shortCode', 1);
        $shortCodeList = null;

        $result = $this->api_model->getOperator(API_USERNAME, API_PASSWORD, $shortCode, 0, 99999);
        die(json_encode($result));
    }

    public function getService() {
        $shortCode = $this->input->post('shortCode', 1);
        $serviceList = null;

        $result = $this->api_model->getService(API_USERNAME, API_PASSWORD, $shortCode, 0, 99999);
        die(json_encode($result));
    }
}

