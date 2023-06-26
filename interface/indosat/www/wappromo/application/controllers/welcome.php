<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function Welcome()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->load->helper('url');
        redirect('wap');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
