<?php

class User extends CI_Controller {
    public $limit = 0;
	
    function __construct() {
		parent::__construct();

        #user not login
        if (!$this->session->userdata('username'))
            redirect(base_url() . 'login');

		$this->load->model(array('navigation_model','users_model'));
        $this->ci_smarty->assign('base_url', base_url());
        $this->ci_smarty->assign('navigation',   $this->navigation_model->getMenuHtml());
    
        $this->limit = $this->config->item('limit');
	}
	
    function index() {
        $jsFile = array('feature.js','acl/user.js','json2.js', 'chart.js', 'swfobject.js', 'internal_account.js', 'internal_account_dashboard.js');
        $this->ci_smarty->assign('group', $this->getGroupList());
        $this->ci_smarty->assign('jsFile', $jsFile);
        $this->ci_smarty->assign('title', 'XMP Internal :: User');
        $this->ci_smarty->assign('template', 'tpl_user_show.tpl');
        $this->ci_smarty->display('document.tpl');
    }
    
    public function ajaxGetUserList() {
        $search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";

        $mData  = $this->users_model->getUserList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id        = $dt['id'];
                $username  = $dt['username'];
				$usergroup = $dt['u_group'];
                $status    = $dt['status'];

                $result .= "<tr>";
                $result .= "<td>$username</td>";
               	$result .= "<td>$usergroup</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editUser($id);\">Edit</a> <a onclick=\"deleteUser($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "acl/user/ajaxGetUserList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows']  = $total;
                $pagination['per_page']    = $limit;

                $this->pagination->initialize($pagination);
                $paging = $this->pagination->create_links();
            }
            else {
                $paging = "<b>1</b>";
            }
        }
        else {
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
        }

        $to = ($page + $limit) > $total ? $total : ($page + $limit);


        $response = array (
            'offset' => $offset,
            'query'  => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from'   => ($page + 1),
            'to'     => $to,
            'total'  => $total
        );

        echo json_encode($response);
    }
    
    public function getGroupList(){
        $result  = "";
        $result .="<span>";
        $result .="<select name=\"txt-group\" id=\"txt-group\">";
        foreach ($this->users_model->readGroup() as $_data)
        {
            $id      = $_data['id'];
            $group   = $_data['group_name'];    
            $result .="<option value=\"$id\">$group</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        return $result;
        
    }
     
    public function ajaxAddNewUser() {
        $username       = $this->input->post("txt-username");
        $password       = $this->input->post("txt-password");
        $confirmpass    = $this->input->post("txt-confirmpass");
        $group          = $this->input->post("txt-group");
        $captcha        = $this->input->post("captcha");
        
        $response = array ();
        
        #validate
        if (empty ($username)) {
            $status_username = FALSE;
            $msg_username    = "required field";
        }
        else {
            $status_username = TRUE;
            $msg_username    = "";
        }
        
        if (empty ($password)) {
            $status_password = FALSE;
            $msg_password    = "required field";
        }
        else {
            $status_password = TRUE;
            $msg_password    = "";
        }
        
        if (empty ($confirmpass)) {
            $status_confirmpass = FALSE;
            $msg_confirmpass    = "required field";
        }
        else {
            $status_confirmpass = TRUE;
            $msg_confirmpass    = "";
        }
     
     
		if(!empty ($username) && !empty($password) && !empty($confirmpass)){
			if ($this->users_model->checkUserList($username,'')){
				$response = array (
								'status_username' 	=> FALSE, 
								'msg_username' 		=> "user already exist", 
								'status' 			=> FALSE, 
								'message' 			=> 'user already exist'
							);
			}
            else{
				$response = $this->users_model->addNewUser($username, $password, $group);
			}    
                
		}
        else{    
                $response = array ( 
								'status_username'		=> $status_username,
								'msg_username'          => $msg_username,
								'status_password'       => $status_password,
								'msg_password'          => $msg_password,
								'status_confirmpass'    => $status_confirmpass,
								'msg_confirmpass'       => $msg_confirmpass,                
								'status'                => FALSE, 
								'message'               => 'error'
                                  );
                            
		}
            echo json_encode($response);
            exit;
        
    }
    
    public function ajaxEditUser() {
        $id     = $this->input->post("id");
        $result = $this->users_model->editUser($id);

        $response = array (
            'username'      => $result[0]['username'],
            'password'      => $result[0]['password'],
            'confirmpass'   => $result[0]['password'],
            'group'         => $result[0]['u_group']
        );

        echo json_encode($response);
        exit;
    }
    
    public function ajaxUpdateUser($id) {
        $username           	= $this->input->post("txt-username");
        $password           	= $this->input->post("txt-password");
        $confirmpass        	= $this->input->post("txt-confirmpass");
        $group              	= $this->input->post("txt-group");
		$username_compare   	= $this->input->post("txt-username-compare");
		$password_compare   	= $this->input->post("txt-password-compare");
		$confirmpass_compare   	= $this->input->post("txt-confirmpass-compare");
		$group_compare   		= $this->input->post("txt-group-compare");
                
        if(	$username 		== $username_compare && 
			$password 		== $password_compare && 
			$confirmpass 	== $confirmpass_compare && 
			$group 			== $group_compare)
        {
            $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
            echo json_encode($response);
            exit;
        }
        
        #validate
        if (empty ($username)) {
            $status_username = FALSE;
            $msg_username    = "require field";
        }
        else {
            $status_username = TRUE;
            $msg_username    = "";
        }
        
        if (empty ($password)) {
            $status_password = FALSE;
            $msg_password    = "require field";
        }
        else {
            $status_password = TRUE;
            $msg_password    = "";
        }
        
        if (empty ($confirmpass)) {
            $status_confirmpass = FALSE;
            $msg_confirmpass    = "require field";
        }
        else {
            $status_confirmpass = TRUE;
            $msg_confirmpass    = "";
        }
        
        if(!empty ($username) && !empty($password) && !empty($confirmpass)){
			if ($this->users_model->checkUserList($username,$id)){
				$response = array (
								'status_username' 	=> FALSE, 
								'msg_username' 		=> "user already exist", 
								'status' 			=> FALSE, 
								'message' 			=> 'user already exist'
							);
			}
            else{
				$response = $this->users_model->updateUser($id, $username, $password, $group);
			}    
                
		}
        else{    
                $response = array ( 
								'status_username'		=> $status_username,
								'msg_username'          => $msg_username,
								'status_password'       => $status_password,
								'msg_password'          => $msg_password,
								'status_confirmpass'    => $status_confirmpass,
								'msg_confirmpass'       => $msg_confirmpass,                
								'status'                => FALSE, 
								'message'               => 'error'
                                  );
                            
		}
            echo json_encode($response);
            exit;

    }
    
    public function ajaxDeleteUser() {
        $id       = $this->input->post("id");
        $response = $this->users_model->deleteUser($id);

        echo json_encode($response);
        exit;
    }
}
?>
