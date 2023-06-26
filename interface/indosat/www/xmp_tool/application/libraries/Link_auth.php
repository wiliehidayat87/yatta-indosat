<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Link_auth {

    public $error = "";
    public $buffer="";
    private $CI;

    /**
     * @abstract constructor
     */
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('acl/user_model');
    }

    /**
     * @param String $username
     * @param String $password
     * @return Boolean
     * @access public 
     */
    public function login($username, $password)
    {
      $auth =   $this->CI->user_model->login($username, $password);
      
      if($auth)
      {
         $newData = array (
                'uid'       => $auth->id,
                'userName'  => $auth->username,
                'groupId'   => $auth->u_group,
                'groupName' => $auth->group_name,
                'groupMenu' => $auth->group_menu
            );

            $this->CI->session->set_userdata($newData);

            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @abstract destroy all session
     * @return Boolean 
     */
    public function logout() {
        $this->CI->session->sess_destroy();
        return TRUE;
    }

    /**
     * @param String $username
     * @return Boolean
     * @access private 
     */
    private function checkUser($username) {
        $checkUser = $this->CI->user_model->checkUser($username);

        if (is_int($checkUser)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * @abstract 
     * check user is logged In. If session is empty, will be redirect to login page
     * @access public
     * @return Boolean
     */
    public function isLogedIn() {
        $userName = $this->CI->session->userdata('userName');
        if (empty($userName)) {
            $loginPage = $this->CI->config->item('login_page');
            redirect($loginPage);
        } else {
            return TRUE;
        }
    }

    /**
     * @param String $username 
     */
    public function getUserInfo($username) {
        $userInfo = $this->CI->user_model->getUser($username);
        if ($userInfo) {
            return $userInfo;
        } else {
            return FALSE;
        }
    }

    private function checkPassword($username, $password) {
        
    }

    public function setLastActive() {
        
    }

    public function updateUserInfo() {
        
    }

    public function deleteUser() {
        
    }

    public function checkPermission() {
        $this->CI->load->model('acl/acl_model');
        $group = $this->CI->session->userdata('groupId');
        if (!$group)
            return FALSE;

        $controller_link = $this->CI->uri->uri_string(1);
        $ctrlId = $this->CI->acl_model->getControllerId($controller_link);
        if (empty($ctrlId))
            return FALSE;
        $method = $this->CI->uri->rsegment(2);
        $statusAccess = $this->CI->acl_model->getAccessList($ctrlId['0']['id'], $method, $group);
        if (!empty($statusAccess)) {
            if ($statusAccess['0']['status'] == '0')
                return FALSE;
        }
        return TRUE;
    }

    public function permission($method) {
        write_log("info", __METHOD__ . ": start");
        $data = $this->CI->session->userdata('methodList');
        if (empty($data)) {
            redirect(base_url() . 'login');
            return FALSE;
        }
        $this->CI->load->model('acl/acl_model');
        $controller_link = $this->CI->uri->uri_string(1);
        $controller_link = preg_replace('/\/[0-9]+/i', '', $controller_link);
        if (preg_match("/(\w+)\/(\w+)\/(\w+)/i", $controller_link)) {
            $controller_link = preg_replace("/(\w+)\/(\w+)\/(\w+)/","$1/$2",$controller_link);
        }
        $ctrlId = $this->CI->acl_model->getControllerId($controller_link);
        if (empty($ctrlId)) {
            $this->error = array('status' => 'NOK', 'Message' => "Class not Found");
            return FALSE;
        }
        if (in_array(array($ctrlId['0']['id'], $method), $data))
            return TRUE;

        $this->error = array('status' => 'NOK', 'Message' => 'Feature Disabled');
        return FALSE;
    }

    public function permissionSession() {
        write_log("info", __METHOD__ . ": start");
        $this->CI->load->model('acl/permission_model');
        $groupId = $this->CI->session->userdata('groupId');
        if (!$groupId) {
            return FALSE;
        }
        $methodList = $this->CI->permission_model->getMethodList($groupId);
        if (empty($methodList)) {
            return FALSE;
        }
        $data = array();
        foreach ($methodList as $key => $value) {
            $data[] = array($value['controller_link'], $value['method']);
        }

        $this->CI->session->set_userdata(array('methodList' => $data));
        return TRUE;
    }

    public function errorMessage() {
        $this->buffer=$this->error;
        $this->error="";
        return $this->buffer;
    }
}
