<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('link_auth');
		$this->smarty->assign('baseUrl', base_url());
    }
    
    public function index()
    {
        $this->link_auth->isLogedIn();
        $username = $this->session->userdata('userName');
        $data['info'] = $this->link_auth->getUserInfo($username);
       
        $this->parser->parse('example/default', $data);
    }

    /**
     * @abstract example simple login
     * @access public
     */
    public function login()
    {
        
        $username   = $this->input->post('username');
        $password   = $this->input->post('password');
        
        $login = $this->link_auth->login($username, $password);
        
        if($login)
        {
            echo "login success";
            redirect(base_url().'example/user/index');
        }
         else 
        {
             echo "login failed";
        }
        
        $this->smarty->display('example/login');
    }
    
    
    /**
     * @access public
     */
    public function logout()
    {
        $logout = $this->link_auth->logout();
        if($logout)
        {
            redirect(base_url()."example/user/login");
        }
    }
}
