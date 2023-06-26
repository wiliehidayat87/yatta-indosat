<?php

class User extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('acl/user_model');
        $this->load->library('link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
    }

    function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $jsFile = 'acl/user.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage User');
        $this->smarty->assign('group', $this->getGroupList());
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('acl/user.tpl');
    }

    function changepass() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        if ($this->input->post('changepass')) {
            if ($this->input->post('currentpass')) {
                if ($this->user_model->checkpassword($this->input->post('currentpass'))) {
                    if ($this->input->post('newpassword') && $this->input->post('newpasswordconfirm')) {
                        if ($this->user_model->changepassword($this->input->post('newpassword'), $this->input->post('newpasswordconfirm'))) {
                            $this->cpsuccess('password', 'changepass');
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

            $this->smarty->assign('errormessage', $errormessage);
        }
        $this->smarty->assign('pageTitle', 'XMP Tools : Change Password');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->smarty->display('user/changepass_view.tpl');
    }

    function cpsuccess($text, $method) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $name = $this->session->userdata('userName');

        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('text', $text);
        $this->smarty->assign('method', $method);
        $this->smarty->assign('name', $name);
        $this->smarty->display('user/success_view.tpl');
    }

    function changeprofile() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $uid = $this->session->userdata('uid');

        $mData = $this->user_model->getUserData($uid);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];

        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $f_name = $dt['f_name'];
                $l_name = $dt['l_name'];
                $email = $dt['email'];
                $phone = $dt['phone'];
            }
            $this->smarty->assign('f_name', $f_name);
            $this->smarty->assign('l_name', $l_name);
            $this->smarty->assign('email', $email);
            $this->smarty->assign('phone', $phone);
        } else {
            $errormessage = "Data Not Found";

            $this->smarty->assign('errormessage', $errormessage);
        }
        if ($this->input->post('changeprofile')) {
            if ($this->input->post('currentpass')) {
                if ($this->user_model->checkpassword($this->input->post('currentpass'))) {
                    $phone = $this->input->post("phone");
                    if (empty($phone)) {
                        if ($this->user_model->changeprofile($uid, $this->input->post('f_name'), $this->input->post('l_name'), $this->input->post('email'), $this->input->post('phone'))) {
                            $this->cpsuccess('profile', 'changeprofile');
                        } else {
                            $errormessage = "Data input error";
                        }
                    } else {
                        if (preg_match("/[0-9|\-|\s]+/is", $this->input->post('phone'))) {
                            if ($this->user_model->changeprofile($uid, $this->input->post('f_name'), $this->input->post('l_name'), $this->input->post('email'), $this->input->post('phone'))) {
                                $this->cpsuccess('profile', 'changeprofile');
                            }
                            else
                                $errormessage = "SQL Input Data error";
                        }
                        $errormessage = "Phone must be numeric";
                    }
                }
                else
                    $errormessage = "Current password does not match";
            }
            else {
                $errormessage = "Current password must not empty";
            }
            $this->smarty->assign('errormessage', $errormessage);
        }
        $this->smarty->assign('pageTitle', 'XMP Tools : Change Profile');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->display('user/changeprofile_view.tpl');
    }

    public function ajaxGetUserList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $search = strtoupper($this->input->post("search"));
        $page = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;
        $limit = $this->input->post("limit");
        $paging = "";
        $result = "";

        $mData = $this->user_model->getUserList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $username = $dt['username'];
                $usergroup = $dt['u_group'];
                $status = $dt['status'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";

                $result .= "<td>$username</td>";
                $result .= "<td>$usergroup</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editUser($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteUser($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "acl/user/ajaxGetUserList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
                $pagination['per_page'] = $limit;

                $this->pagination->initialize($pagination);
                $paging_data = $this->pagination->create_links();
                $paging_data = preg_replace('/\<strong\>(\d+)\<\/strong\>/i', '<a class="current" href="">$1</a>', $paging_data);
                $paging_data = explode("&nbsp;", $paging_data);
                foreach ($paging_data as $page) {
                    if (!empty($page))
                        $paging.="<li>$page</li>";
                }
            } else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
        } else {
            $result .= "<tr><td colspan=\"3\">No data found</td></tr>";
        }

        $to = ($page + $limit) > $total ? $total : ($page + $limit);


        $response = array(
            'offset' => $offset,
            'query' => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from' => ($page + 1),
            'to' => $to,
            'total' => $total
        );

        echo json_encode($response);
        exit;
    }

    public function getGroupList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-group\" id=\"txt-group\">";
        foreach ($this->user_model->readGroup() as $_data) {
            $id = $_data['id'];
            $group = $_data['group_name'];
            $result .="<option value=\"$id\">$group</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function ajaxAddNewUser() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $username = $this->input->post("txt-username");
        $password = $this->input->post("txt-password");
        $confirmpass = $this->input->post("txt-confirmpass");
        $group = $this->input->post("txt-group");
        $captcha = $this->input->post("captcha");

        $response = array();

        #validate
        if (empty($username)) {
            $status_username = FALSE;
            $msg_username = "User Name Field is Required";
        } else {
            $status_username = TRUE;
            $msg_username = "";
        }

        if (empty($password)) {
            $status_password = FALSE;
            $msg_password = "Password Field is Required";
        } else {
            $status_password = TRUE;
            $msg_password = "";
        }

        if (empty($confirmpass)) {
            $status_confirmpass = FALSE;
            $msg_confirmpass = "Confirmation Password Field is Required";
        } else {
            $status_confirmpass = TRUE;
            $msg_confirmpass = "";
        }

        if (!empty($username) && !empty($password) && !empty($confirmpass)) {
            if ($username) {
                if ($this->user_model->checkUserList($username))
                    $response = array('status_username' => FALSE, 'msg_username' => "user already exist", 'status' => FALSE, 'message' => 'user already exist');
                else
                if ($password != $confirmpass) {
                    $response = array('status_username' => TRUE, 'msg_username' => "", 'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');
                } else {
                    $response = $this->user_model->addNewUser($username, $password, $group);
                }
            }
        } else {
            $response = array(
                'status_username' => $status_username,
                'msg_username' => $msg_username,
                'status_password' => $status_password,
                'msg_password' => $msg_password,
                'status_confirmpass' => $status_confirmpass,
                'msg_confirmpass' => $msg_confirmpass,
                'status' => FALSE,
                'message' => 'required field'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxEditUser() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->user_model->editUser($id);

        $response = array(
            'username' => $result[0]['username'],
            'password' => $result[0]['password'],
            'confirmpass' => $result[0]['password'],
            'group' => $result[0]['u_group']
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateUser($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $username = $this->input->post("txt-username");
        $username_compare = $this->input->post("txt-username-compare");
        $password = $this->input->post("txt-password");
        $confirmpass = $this->input->post("txt-confirmpass");
        $group = $this->input->post("txt-group");

        $response = array();

        #validate
       if (empty($username)) {
            $status_username = FALSE;
            $msg_username = "User Name Field is Required";
        } else {
            $status_username = TRUE;
            $msg_username = "";
        }

        if (empty($password)) {
            $status_password = FALSE;
            $msg_password = "Password Field is Required";
        } else {
            $status_password = TRUE;
            $msg_password = "";
        }

        if (empty($confirmpass)) {
            $status_confirmpass = FALSE;
            $msg_confirmpass = "Confirmation Password Field is Required";
        } else {
            $status_confirmpass = TRUE;
            $msg_confirmpass = "";
        }

        if (!empty($username) && !empty($password) && !empty($confirmpass)) {
            if ($username == $username_compare) {
                $response = array('status' => TRUE, 'message' => '', 'id' => $id);

                if ($password != $confirmpass) {
                    $response = array('status_username' => TRUE, 'msg_username' => "", 'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');
                } else {
                    $response = $this->user_model->updateUser($id, $username, $password, $group);
                }
            } else {
                if ($this->user_model->checkUserList($username)) {
                    $response = array('status_username' => FALSE, 'msg_username' => "user already exist", 'status' => FALSE, 'message' => 'user already exist');
                } else {
                    if ($password != $confirmpass) {
                        $response = array('status_username' => TRUE, 'msg_username' => "", 'status_password' => FALSE, 'status_confirmpass' => FALSE, 'msg_password' => "password doesn't match", 'msg_confirmpass' => "password doesn't match", 'status' => FALSE, 'message' => 'user already exist');
                    } else {
                        $response = $this->user_model->updateUser($id, $username, $password, $group);
                    }
                }
            }
        } else {
            $response = array(
                'status_username' => $status_username,
                'msg_username' => $msg_username,
                'status_password' => $status_password,
                'msg_password' => $msg_password,
                'status_confirmpass' => $status_confirmpass,
                'msg_confirmpass' => $msg_confirmpass,
                'status' => FALSE,
                'message' => 'required field'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteUser() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->user_model->deleteUser($id);

        echo json_encode($response);
        exit;
    }

}

?>
