<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  
    public function __construct() {
        parent::__construct();
        #user not login
        if (!$this->session->userdata('userName'))
        redirect(base_url() . 'login');

        $this->load->model('common/navigation_model');
        $this->smarty->assign('navigation', $this->navigation_model->getMenuHtml());

        $this->output->set_header("HTTP/1.0 200 OK");
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");
    }

    public function createParameters($mandatoryParams, $optionalParams) {
        $params = array();
        foreach ($mandatoryParams as $param) {
            if ($this->input->get_post($param,1)==FALSE && strlen($this->input->get_post($param,1)) == 0) {
                throw new Exception(sprintf(RESPONSE_ERROR_MISSINGPARAM, $param));
            }
//          $$param = $this->input->get_post($param,1);
            $params[$param] = $this->input->get_post($param,1);
        }
        foreach ($optionalParams as $key=>$value) {
            if ($this->input->get_post($key,1)==FALSE) {
//              $$key = $value;
                $params[$key] = $value;
            } else {
//              $$key = $this->input->get_post($key,1);
                $params[$key] = $this->input->get_post($key,1);
            }
        }
        $this->parameters = $params;
        write_log('debug','Parameters: ' . toString($params));
        return $params;
    }        

    public function numericTypeCheck($key) {
        $value = $this->getParam($key);
        if (isset($value) && !is_numeric($value)) {
            throw new Exception(sprintf(RESPONSE_ERROR_NOTNUMERIC, $key, $value));
        }
    }
    
    public function arrayTypeCheck($key) {
        $value = $this->getParam($key);
        if (isset($value) && !is_array($value)) {
            throw new Exception(sprintf(RESPONSE_ERROR_NOTNUMERIC, $key, $value));
        }
    }

    public function orderTypeCheck($key) {
        $value = $this->getParam($key);
        if (isset($value) && !is_numeric($value)) {
            throw new Exception(sprintf(RESPONSE_ERROR_NOTORDERTYPE, $key, $value));
        }
    }

    public function messageTypeCheck($key) {
        $value = $this->getParam($key);
        if ($value!='MT' && $value!='MO' && $value!='DELIVERED') {
            throw new Exception(sprintf(RESPONSE_ERROR_NOTMSGTYPE, $key, $value));
        }
    }
    
    public function getParam($key) {
        if (isset($this->parameters[$key]))
            return $this->parameters[$key];
        return NULL;
    }

    public function setParam($key, $value) {
        $this->parameters[$key] = $value;
    }
    
}
