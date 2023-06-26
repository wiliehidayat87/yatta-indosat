<?php

class Wapreg_ak extends CI_Controller
{
	 public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
        $this->klogger->log("");
        
        $config = get_config();
        $this->mysmarty->assign('base_url', base_url());
	}
	
	function index()
	{
		$this->load->model('ak');
		
		$result = $this->ak->read_all_ak();
		
		$this->mysmarty->assign('result', $result);
        $this->mysmarty->assign('page_title', 'AK Schedule');
		$this->mysmarty->view('wapreg/ak_main.html');
	}
	
	function add()
	{
		if($this->input->post('submit') == TRUE)
		{
			$name					= $this->input->post('name', TRUE);
			$description	= $this->input->post('description', TRUE);
			$error				= FALSE;
			
			if(empty($name))
			{
				$error .= 'Name cannot empty<br />';
			}
			elseif(strtoupper($name) == 'ALL' || strtoupper($name) == 'OFF')
			{
				$error .= 'AK Schedule name for ALL and OFF is reserved<br />';
			}
			else
			{
				$this->load->model('ak');
				
				$name_exist = $this->ak->read_ak_by_name($name);
				
				if($name_exist != FALSE)
				{
					$error .= 'Name exist. Choose another<br />';
				}
				else
				{
					$insert_attempt = $this->ak->create_ak($name, $description);
					
					if($insert_attempt == FALSE) $error .= 'Error inserting to db<br />';
				}
			}
			
			if($error != FALSE)
			{
				$this->mysmarty->assign('name', $name);
				$this->mysmarty->assign('description', $description);
				$this->mysmarty->assign('error', $error);
			}
			else
			{
				redirect('wapreg/wapreg_ak');
			}
			
		}
		
		$this->mysmarty->assign('page_title', ' Add AK Schedule');
		$this->mysmarty->view('wapreg/ak_add.html');
	}
	
	function edit($id = 0)
	{

		if($id==0) redirect('wapreg/wapreg_ak');


		$this->load->model('ak');

		$read_ak = $this->ak->read_ak_by_id($id);

		if($read_ak == FALSE) redirect('wapreg/wapreg_ak');
		
		if($this->input->post('submit') == TRUE)
		{
			$name					= $this->input->post('name', TRUE);
			$description	= $this->input->post('description', TRUE);
			$error				= FALSE;
			
			if(empty($name))
			{
				$error .= 'Name cannot empty<br />';
			}
			elseif(strtoupper($name) == 'ALL' || strtoupper($name) == 'OFF')
			{
				$error .= 'AK Schedule name for ALL and OFF is reserved<br />';
			}
			else
			{
				$schedule_exist = $this->ak->read_ak_by_name_id($id, $name);
				
				if($schedule_exist != FALSE)
				{
					$error .= 'Name exist. Choose another<br />';
				}
				else
				{
					$update_attempt = $this->ak->update_ak($id, $name, $description);
					
					if($update_attempt == FALSE) $error .= 'Error updating db<br />';
				}
			}
			
			if($error != FALSE)
			{
				$this->mysmarty->assign('name', $name);
				$this->mysmarty->assign('description', $description);
				$this->mysmarty->assign('error', $error);
			}
			else
			{
				redirect('wapreg/wapreg_ak');
			}
		}
		else
		{

			$this->mysmarty->assign('name', $read_ak[0]['name']);
			$this->mysmarty->assign('description', $read_ak[0]['description']);
		}

		$this->mysmarty->assign('id', $id);
		$this->mysmarty->assign('page_title', 'Edit AK Schedule');
		$this->mysmarty->view('wapreg/ak_edit.html');
	}
	
	function delete()
	{
		$id = $this->uri->segment(4);

		if(empty($id)) redirect('/wapreg/wapreg_ak');
		
		$this->load->model(array('ak','ak_detail','wapreg'));
		
		//$this->wapreg->update_wapreg_reset_ak($id); // set all wapreg associated with this schedule to ALL before deleting
		$this->ak_detail->unactivate_ak_detail_by_ak_id($id);
		$this->ak->unactivate_ak($id);
		
		redirect('/wapreg/wapreg_ak');
	}
}

?>
