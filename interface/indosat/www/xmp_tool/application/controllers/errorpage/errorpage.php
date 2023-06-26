<?php

class Errorpage extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
   }

    public function accessDenied() {
        $this->smarty->assign('pageTitle', 'XMP Tools : Access Denied');
        $this->smarty->assign('message','Access Denied');
        $this->smarty->display('errorpage/errorpage.tpl');
    }

    public function featureDisabled() {
        $this->smarty->assign('pageTitle', 'XMP Tools : Feature Disabled');
        $this->smarty->assign('message','Feature Disabled');
        $this->smarty->display('errorpage/errorpage.tpl');
    }

    public function classNotFound() {
        $this->smarty->assign('pageTitle', 'XMP Tools : Class Not Found');
        $this->smarty->assign('message','Class Not Found');
        $this->smarty->display('errorpage/errorpage.tpl');
    }

}
