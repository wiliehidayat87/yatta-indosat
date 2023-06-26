<?php

/**
 * acl/user controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Muhammad Indra Rahmanto (LinkIT Dev Team)
 */
class Auth extends CI_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('acl/user_model');

        $this->load->library('link_auth');
        $this->smarty->assign('userName', $this->session->userdata('userName'));
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
    }

    function index() {
        $this->login();
    }

    function login() {
        #user already login
        if ($this->session->userdata('userName')) redirect(base_url() . 'welcome');

        session_start();
        if (!isset($_SESSION["origURL"]) && $_SERVER["HTTP_REFERER"]) $_SESSION["origURL"] = $_SERVER["HTTP_REFERER"];
        if ($this->input->post('login')) {

            if ($this->input->post('username') && $this->input->post('password')) {
                if ($this->user_model->login($this->input->post('username'), $this->input->post('password'))) {
                    $login = $this->link_auth->login($this->input->post('username'), $this->input->post('password'));
                    $this->link_auth->permissionSession();
                    if ($_SESSION["origURL"]) {
                        $goTo = $_SESSION["origURL"];
                        unset ($_SESSION["origURL"]);
                        header("Location: ".$goTo);
                    } else {
                        redirect('welcome');
                    }
                } else {
                    $errormessage = "Username/Password does not match";
                }
            } else {
                $errormessage = "Username/Password must not empty";
            }

            $this->smarty->assign('errormessage', $errormessage);
        }

        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->smarty->display('user/login_view.tpl');
    }

    function logout() {
        $logout = $this->link_auth->logout();
        if ($logout) {
            redirect(base_url() . "login");
        }
    }

}
