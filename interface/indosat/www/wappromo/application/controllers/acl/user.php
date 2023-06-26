<?php
/**
 * acl/user controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Muhammad Indra Rahmanto (LinkIT Dev Team)
 */

class User extends CI_Controller {
    public $limit = 0;
	
    public function __construct() {
	parent::__construct();

        #user not login
        if (!$this->session->userdata('wap_username'))
            redirect(base_url() . 'login');
        $this->klogger->log("");
        $this->load->model(array('user_model','navigation_model'));
        
	$this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
	$this->mysmarty->assign('navigation', $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('base_url', base_url());
	
        $this->limit = $this->config->item('limit');
	}
	
    function index() {
        $this->klogger->log("");
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->assign('group', $this->getGroupList());
        $this->mysmarty->view('acl/user_view.html');
    }
    
    public function ajaxGetUserList() {
        $this->klogger->log(""); 
        $search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";

        $mData  = $this->user_model->getUserList($offset, $limit, $search);
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
            $result .= "<tr><td colspan=\"3\">No data found</td></tr>";
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
        $this->klogger->log("");
        $result  = "";
        $result .="<span>";
        $result .="<select name=\"txt-group\" id=\"txt-group\">";
        foreach ($this->user_model->readGroup() as $_data)
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
        $this->klogger->log("");
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
        
        if (!empty ($username) && !empty($password) && !empty($confirmpass)) {
            if ($username){
                if ($this->user_model->checkUserList($username))
                    $response = array ('status_username' => FALSE, 'msg_username' => "user already exist", 'status' => FALSE, 'message' => 'user already exist');
                else
                    if($password != $confirmpass){
                        $response = array ('status_username' => TRUE, 'msg_username' => "",'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');    
                    }
                        else{
                            $response = $this->user_model->addNewUser($username, $password, $group);
                        }
            }          
        }
        else {
            $response = array (
                'status_username'       => $status_username,
                'msg_username'          => $msg_username,
                'status_password'       => $status_password,
                'msg_password'          => $msg_password,
                'status_confirmpass'    => $status_confirmpass,
                'msg_confirmpass'       => $msg_confirmpass,                
                'status'                => FALSE, 
                'message'               => 'required field'
                );
        }
        
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxEditUser() {
        $this->klogger->log("");
        $id     = $this->input->post("id");
        $result = $this->user_model->editUser($id);

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
        $this->klogger->log("");
        $username           = $this->input->post("txt-username");
        $username_compare   = $this->input->post("txt-username-compare");
        $password           = $this->input->post("txt-password");
        $confirmpass        = $this->input->post("txt-confirmpass");
        $group              = $this->input->post("txt-group");
        
        $response = array();
        
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
        
        if (!empty ($username) && !empty($password) && !empty($confirmpass)) {
            if($username == $username_compare)
            {
                $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);

                if($password != $confirmpass){
                    $response = array ('status_username' => TRUE, 'msg_username' => "",'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');    
                }
                else {
                    $response = $this->user_model->updateUser($id, $username, $password, $group);
                }
            }
            else {
                if ($this->user_model->checkUserList($username)) {
                    $response = array ('status_username' => FALSE, 'msg_username' => "user already exist", 'status' => FALSE, 'message' => 'user already exist');
                }
                else {
                    if($password != $confirmpass){
                        $response = array ('status_username' => TRUE, 'msg_username' => "",'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');    
                    }
                    else {
                        $response = $this->user_model->updateUser($id, $username, $password, $group);
                    }
                }
            }
        }
        else {
            $response = array (
                'status_username'       => $status_username,
                'msg_username'          => $msg_username,
                'status_password'       => $status_password,
                'msg_password'          => $msg_password,
                'status_confirmpass'    => $status_confirmpass,
                'msg_confirmpass'       => $msg_confirmpass,                
                'status'                => FALSE, 
                'message'               => 'required field'
                );
        }
        
        echo json_encode($response);
        exit;
    }
    
    public function ajaxDeleteUser() {
        $this->klogger->log("");
        $id       = $this->input->post("id");
        $response = $this->user_model->deleteUser($id);

        echo json_encode($response);
        exit;
    }
}
