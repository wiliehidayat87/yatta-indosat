<?php

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();

        //$this->load->helper('url');
        $this->load->model('login_model');
    }

    function index() {
        #user already login
        if ($this->session->userdata('username'))
            redirect(base_url() . 'account');

        if ($this->input->post('login')) {
            if ($this->input->post('username') && $this->input->post('password')) {
                if ($this->login_model->login($this->input->post('username'), $this->input->post('password'))) {
                    $this->load->model('navigation_model');
                    $direct = $this->navigation_model->getMenuList(0, $this->session->userdata('groupmenu'));
                    redirect($direct[0]['link']);
                } else {
                    $errormessage = "Username/Password does not match";
                }
            } else {
                $errormessage = "Username/Password must not empty";
            }

            $this->ci_smarty->assign('errormessage', $errormessage);
        }

        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->display('login.html');
    }

    function logout() {
        $data = array(
            'username' => '',
            'group' => '',
            'groupname' => '',
            'groupmenu' => ''
        );

        $this->session->unset_userdata($data);
        $this->session->sess_destroy();

        redirect(base_url() . 'login');
    }

}

?>
