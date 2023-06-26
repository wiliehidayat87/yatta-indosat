<?php

class Group extends MY_Controller {

    public $limit = 0;
    public $limit1 = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('acl/group_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
    }

    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $jsFile = 'acl/group.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Group');
        $this->smarty->assign('check_menu', $this->getCheckMenu());
        $this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->display('acl/group.tpl');
    }

    public function ajaxGetGroupList() {
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

        $mData = $this->group_model->getGroupList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $group_name = $dt['group_name'];
                $group_desc = $dt['group_desc'];
                $group_menu = $dt['group_menu'];
                $status = ($dt['status'] == '1') ? "Active" : "Inactive";

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                $result .= "<td>$group_name</td>";
                $result .= "<td>$group_desc</td>";
                $result .= "<td>$status</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editGroup($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteGroup($id);\">Delete</a> <a href=\"javascript:void(0)\" onclick=\"gotoMethodGroup($id);\">Method</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "acl/group/ajaxGetGroupList/";
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
            $result .= "<tr><td colspan=\"4\">No data found</td></tr>";
            $paging = "<b>0</b>";
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

    public function getCheckMenu() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $i = 1;
        $result = "";

        $data = $this->navigation_model->getCheckBoxMenuList();

        $result = $this->build_menu($data);
        //echo $result;
        return $result;
    }

    public function has_children($rows, $id) {
        foreach ($rows as $row) {
            if ($row['parent'] == $id)
                return true;
        }
        return false;
    }

    public function build_menu($rows, $parent=0, $child=false) {

        $result = "<ul" . (!$child ? " id=\"group-list\"" : "") . " style=\"list-style:none;\">\n";
        foreach ($rows as $row) {
            if ($row['parent'] == $parent) {
                $result.= "<li style=\"list-style:none;\">\n<input type=\"checkbox\" name=\"" . ($child ? "child" : "parent") . "" . $parent . "\" id=\"menu-" . $row['id'] . "\" class=\"check-menu\" value=\"" . $row['id'] . "\" />" . $row['menu'] . "\n";
                if ($this->has_children($rows, $row['id']))
                    $result.= $this->build_menu($rows, $row['id'], true);
                $result.= "</li>\n";
            }
        }
        $result.= "</ul>\n";

        return $result;
    }

    public function ajaxSaveGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $group_name = $this->input->post("group-name");
        $group_desc = $this->input->post("group-desc");
        $group_menu = $this->input->post("group-menu");

        $response = array();

        #validate
        if (empty($group_name)) {
            $status_group_name = FALSE;
            $msg_group_name = "Group Name Field is Required";
        } else {
            $status_group_name = TRUE;
            $msg_group_name = "";
        }

        if (!empty($group_name)) {
            if ($this->group_model->checkGroupList($group_name, '')) {
                $response = array(
                    'status_group_name' => FALSE,
                    'msg_group_name' => "Group already exist",
                    'status' => FALSE,
                    'message' => 'group already exist'
                );
            } else {
                $response = $this->group_model->addNewGroup($group_name, $group_desc, $group_menu);
            }
        } else {
            $response = array(
                'status_group_name' => $status_group_name,
                'msg_group_name' => $msg_group_name,
                'status' => FALSE,
                'message' => 'error'
            );
        }
        echo json_encode($response);
        exit;

        echo json_encode($response);
        exit;
    }

    public function ajaxEditGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->group_model->editGroup($id);

        $response = array(
            'group_name' => $result[0]['group_name'],
            'group_desc' => $result[0]['group_desc'],
            'group_menu' => explode(",", $result[0]['group_menu'])
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateGroup($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $group_name = $this->input->post("group-name");
        $group_desc = $this->input->post("group-desc");
        $group_menu = $this->input->post("group-menu");

        $response = array();

        #validate
        if (empty($group_name)) {
            $status_group_name = FALSE;
            $msg_group_name = "Group Name Field is Required";
        } else {
            $status_group_name = TRUE;
            $msg_group_name = "";
        }

        if (!empty($group_name)) {
            if ($this->group_model->checkGroupList($group_name, $id)) {
                $response = array(
                    'status_group_name' => FALSE,
                    'msg_group_name' => "Group already exist",
                    'status' => FALSE,
                    'message' => 'group already exist'
                );
            } else {
                $response = $this->group_model->updateGroup($id, $group_name, $group_desc, $group_menu);
            }
        } else {
            $response = array(
                'status_group_name' => $status_group_name,
                'msg_group_name' => $msg_group_name,
                'status' => FALSE,
                'message' => 'error'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->group_model->deleteGroup($id);

        echo json_encode($response);
        exit;
    }

}
