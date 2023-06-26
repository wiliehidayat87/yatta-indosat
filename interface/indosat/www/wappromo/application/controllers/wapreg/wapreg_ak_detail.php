<?php

class Wapreg_ak_detail extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
        $this->klogger->log("");

        $config = get_config();
        $this->mysmarty->assign('base_url', base_url());

    }

    function index($id = 0)
    {
        
        if($id == 0) redirect('wapreg/wapreg_ak');
        
        $this->load->model(array('ak','ak_detail'));
        
        $read_ak = $this->ak->read_ak_by_id($id);
        
        if($read_ak == FALSE) redirect('wapreg/wapreg_ak');
        
        $read_ak_detail = $this->ak_detail->read_all_ak_detail($id);
        
        $day_array = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('ak', $read_ak);
        $this->mysmarty->assign('day', $day_array);
        $this->mysmarty->assign('result', $read_ak_detail);
        $this->mysmarty->assign('page_title', 'AK Schedule');
        $this->mysmarty->view('wapreg/ak_detail_main.html');
    }

    function add($id = 0)
    {
        
        if($id == 0) redirect('wapreg/wapreg_ak');
        
        $this->load->model('ak');
        
        $read_ak = $this->ak->read_ak_by_id($id);
        
        if($read_ak == FALSE) redirect('wapreg/wapreg_ak');
        
        if($this->input->post('submit') == TRUE)
        {
            $day_input	= $this->input->post('day', TRUE);
            $from_hour	= $this->input->post('from_hour', TRUE);
            $from_min		= $this->input->post('from_minute', TRUE);
            $to_hour		= $this->input->post('to_hour', TRUE);
            $to_min			= $this->input->post('to_minute', TRUE);
            $error			= TRUE;
            $error_msg = '';

            foreach($day_input as $day_item)
            {
                if($day_item === FALSE or strlen(trim($day_item)) == 0)
                {
                    $error_msg .= 'Day cannot empty<br />';
		    $error = FALSE;
                    break;
                }
            }
            
            foreach($from_hour as $from_hour_item)
            {
                if($from_hour_item === FALSE or strlen(trim($from_hour_item)) == 0)
                {
                    $error_msg .= 'From cannot empty<br />';
		    $error = FALSE;
                    break;
                }
            }
            
            foreach($from_min as $from_min_item)
            {
                if($from_min_item === FALSE or strlen(trim($from_min_item)) == 0)
                {
                    $error_msg .= 'From cannot empty<br />';
		    $error = FALSE;
                    break;
                }
            }
            
            foreach($to_hour as $to_hour_item)
            {
                if($to_hour_item === FALSE or strlen(trim($to_hour_item)) == 0)
                {
                    $error_msg .= 'To cannot empty<br />';
		    $error = FALSE;
                    break;
                }
            }
            
            foreach($to_min as $to_min_item)
            {
                if($to_min_item === FALSE or strlen(trim($to_min_item)) == 0)
                {
                    $error_msg .= 'To cannot empty<br />';
		    $error = FALSE;
                    break;
                }
            }
            
            if($error != FALSE)
            {
                $this->load->model('ak_detail');
                
                $rows = count($day_input) - 1; // for array indexing
                
                for($i=0; $i <= $rows; $i++)
                {
                    $from_input			= $from_hour[$i] . ':' . $from_min[$i];
                    $to_input				= $to_hour[$i] . ':' . $to_min[$i];
                    
                    $input_attempt	= $this->ak_detail->create_ak_detail($id, (int)$day_input[$i], $from_input, $to_input);
                }
                
                redirect('wapreg/wapreg_ak_detail/index/' . $id);
            }
            else
            {
                $this->mysmarty->assign('day', $day_input);
                $this->mysmarty->assign('from_hour_array', $from_hour);
                $this->mysmarty->assign('from_min_array', $from_min);
                $this->mysmarty->assign('to_hour_array', $to_hour);
                $this->mysmarty->assign('to_min_array', $to_min);
                $this->mysmarty->assign('error', $error_msg);
            }
        }
                
        $day_array = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('day', $day_array);
        $this->mysmarty->view('wapreg/ak_detail_add.html');
    }

    function edit($ak_id=0, $id = 0)
    {
        
        
        if($ak_id == 0) redirect('wapreg/wapreg_ak');
        if($id == 0) redirect('wapreg/wapreg_ak_detail/index/' . $ak_id);
        
        $this->load->model('ak_detail');
        
        $read_detail = $this->ak_detail->read_ak_detail_by_id($id);
        
        if($read_detail == FALSE) redirect('wapreg/wapreg_ak_detail/index/' . $ak_id);
        
        if($this->input->post('submit') == TRUE)
        {
            $day 				= $this->input->post('day', FALSE);
            $from_hour	= $this->input->post('from_hour', TRUE);
            $from_min		= $this->input->post('from_minute', TRUE);
            $to_hour		= $this->input->post('to_hour', TRUE);
            $to_min			= $this->input->post('to_minute', TRUE);
            $error			= TRUE;
            $error_msg = '';
            
            if(empty($day) || $from_hour === FALSE || $from_min === FALSE || $to_hour === FALSE || $to_min === FALSE)
            {
                $error_msg .= 'All fields cannot empty';
		$error = FALSE;
            }
            elseif(strlen(trim($day))==0 || strlen(trim($from_hour))==0 || strlen(trim($from_min))==0 || strlen(trim($to_hour))==0 || strlen(trim($to_min))==0)
            {
                $error_msg .= 'All fields cannot empty';
		$error = FALSE;
            }
            else{
                $from	= $from_hour . ':' . $from_min;
                $to		= $to_hour . ':' . $to_min;
                
                $update_attempt = $this->ak_detail->update_ak_detail($id, (int)$day, $from, $to);
                
                if($update_attempt == FALSE) $error .= 'Error updating database<br />';
            }
            
            if($error == FALSE)
            {
                $this->mysmarty->assign('day_input', $day);
                $this->mysmarty->assign('from_hour', $from_hour);
                $this->mysmarty->assign('from_min', $from_min);
                $this->mysmarty->assign('to_hour', $to_hour);
                $this->mysmarty->assign('to_min', $to_min);
                $this->mysmarty->assign('error', $error_msg);
            }
            else
            {
                redirect('wapreg/wapreg_ak_detail/index/' . $ak_id);
            }
        }
        else
        {
            $this->mysmarty->assign('day_input', $read_detail[0]['day_num']);
            $this->mysmarty->assign('from_hour', substr($read_detail[0]['hour_start'],0,2));
            $this->mysmarty->assign('from_min', substr($read_detail[0]['hour_start'],3,2));
            $this->mysmarty->assign('to_hour', substr($read_detail[0]['hour_stop'],0,2));
            $this->mysmarty->assign('to_min', substr($read_detail[0]['hour_stop'],3,2));
        }
        
        $day_array = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('ak_id', $ak_id);
        $this->mysmarty->assign('day', $day_array);
        $this->mysmarty->view('wapreg/ak_detail_edit.html');
    }

    function delete($ak_id=0, $id = 0)
    {
        
        
        if($ak_id == 0) redirect('wapreg/wapreg_ak');
        if($id == 0) redirect('wapreg/wapreg_ak_detail/index/' . $ak_id);
        
        $this->load->model('ak_detail');
        
        $this->ak_detail->unactivate_ak_detail($id);
        
        redirect('wapreg/wapreg_ak_detail/index/' . $ak_id);
    }
	
}

?>
