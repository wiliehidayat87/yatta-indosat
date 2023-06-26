<?php 	

	class Testcurl extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$data = array();
		$data['status'] = "OK";
		
		echo json_encode($data);exit;
		$this->input->post('username');
		echo $this->input->post('username');
		
		return FALSE;
	}
}
?>
