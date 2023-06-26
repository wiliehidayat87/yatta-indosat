<?php

/*
 * 
 *  Crepo tool for XMP
 *  Content Repository management
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate$
 *  Last updated by   $Author$
 *  Last revision     $LastChangedRevision$
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Repository extends MY_Controller {
    

    public function __construct() {
        parent::__construct();

        //$this->load->model('broadcast/broadcast_model');
        $this->load->library('Link_auth');
        
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        
        /*if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }*/
        
        $this->new_content();
    }
    
    public function new_content() {
        $this->smarty->display('crepo/new_content.tpl');
    }
    
}
?>