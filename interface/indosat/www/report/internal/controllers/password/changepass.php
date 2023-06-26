s<?php
class Changepass extends CI_Controller {
	public $limit = 0;
		
    function __construct()
    {
		parent::__construct();
		
		if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');
            
		$this->load->helper('url');
		$this->load->model(array('navigation_model','changepass_model'));
        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->assign('navigation', $this->navigation_model->getMenuHtml());
    
	}

	function index(){
		
       	if ($this->input->post('changepass'))
        {
            if ($this->input->post('currentpass'))
            {
				if ($this->changepass_model->checkpassword($this->input->post('currentpass')))
				{
					if ($this->input->post('newpassword') && $this->input->post('newpasswordconfirm'))
					{
						if ($this->changepass_model->changepassword($this->input->post('newpassword'), $this->input->post('newpasswordconfirm')))
						{
							redirect('password/success');
						}
						else 
							$errormessage = "New password / New password Confirm does not match";
					}
					else
						$errormessage = "New password / New password Confirm must not empty";
				}
				else
					$errormessage = "Current password does not match";
            }
            else
                $errormessage = "Current password must not empty";
            
            $this->ci_smarty->assign('errormessage', $errormessage);
        }
        				
		$jsFile = array('feature.js','json2.js', 'chart.js', 'swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('title', 'XMP Internal :: Password');
        $this->ci_smarty->assign('template', 'tpl_changepass_show.tpl');
        $this->ci_smarty->display('document.tpl');
	}
}

?>
