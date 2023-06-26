<?php
/**
 * password/success controller
 *
 * @package		Service Creation UI
 * @since		July 20 2011
 * @author		Indra (LinkIT Dev Team)
 */

class Success extends CI_Controller{
	public $limit = 0;

	function __construct(){
	
		parent::__construct();
                $this->klogger->log("");	
		$this->load->helper('url');
		$this->load->model('navigation_model');

		if(!$this->session->userdata('xmp_username'))
		redirect(base_url() . 'login');

		$this->mysmarty->assign('xmp_username', $this->session->userdata('xmp_username'));
	}

	function index(){
		$this->klogger->log("");
		$name = $this->session->userdata('xmp_username');
		
		$this->mysmarty->assign('base_url', base_url());
		$this->mysmarty->assign('name', $name);
		$this->mysmarty->view('password/success_view.html');
	
	}


}

?>
