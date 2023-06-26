<?php
class Success extends CI_Controller{
	public $limit = 0;

	function __construct(){
	
		parent::__construct();
		 #user not login
       if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');
                         
        $this->load->model(array('navigation_model','group_model'));
        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->assign('navigation',   $this->navigation_model->getMenuHtml());
	}

	function index(){
		$jsFile = array('feature.js','acl/group.js','json2.js','swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('meta', '<meta HTTP-EQUIV="REFRESH" content="3; url='.base_url().'password/changepass">');
        $this->ci_smarty->assign('title', 'XMP Internal :: Password');
        $this->ci_smarty->assign('template', 'tpl_changepass_success_show.tpl');
        $this->ci_smarty->assign('name', $this->session->userdata('username'));
        $this->ci_smarty->display('document.tpl');
        
	}


}

?>
