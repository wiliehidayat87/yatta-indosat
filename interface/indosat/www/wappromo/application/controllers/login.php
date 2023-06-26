<?php
/**
 *
 * Service controller
 *
 * @package		Service Creation UI
 * @since		July 15 2011
 * @author		LinkIT Dev Team
 *
 */

class Login extends CI_Controller {
    public function __construct() {
		parent::__construct();

		//$this->load->helper('url');
		$this->load->model('login_model');
	}
     
    function index() {
        #user already login
        if ($this->session->userdata('wap_username'))
            redirect(base_url() . 'home');

       	if ($this->input->post('login')) {
            if ($this->input->post('username') && $this->input->post('password')) {
                if ($this->login_model->login($this->input->post('username'), $this->input->post('password')))
                    redirect('home');
                else
                    $errormessage = "Username/Password does not match";
            }
            else {
                $errormessage = "Username/Password must not empty";
            }
            
            $this->mysmarty->assign('errormessage', $errormessage);
        }

	    $this->mysmarty->assign('base_url', base_url());
            $this->mysmarty->view('login_view.html');
    }

	function logout() {
        $data = array (
            'wap_username'  => '',
            'wap_group'     => '',
            'wap_groupname' => '',
            'wap_groupmenu' => ''
        );
        
        $this->session->unset_userdata($data);
        $this->session->sess_destroy();
        
        redirect(base_url() . 'login');
    }
}