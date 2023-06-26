<?php
/**
 * password/changepass controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Indra (LinkIT Dev Team)
 */
 
class Changepass extends CI_Controller {
	public $limit = 0;
		
    function __construct()
    {
		parent::__construct();
		$this->load->helper('url');
                $this->klogger->log("");
		$this->load->model(array('changepass_model','navigation_model'));
		
		$this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
		$this->mysmarty->assign('navigation', $this->navigation_model->getMenuHtml());
		$this->limit = $this->config->item('limit');

		#user not login
		if(!$this->session->userdata('wap_username'))
		redirect(base_url() . 'login');
	}

	function index(){
	$this->klogger->log("");
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
            
            $this->mysmarty->assign('errormessage', $errormessage);
        }
        
		$this->mysmarty->assign('base_url', base_url());
		$this->mysmarty->view('password/changepass_view.html');
	}
}

?>
