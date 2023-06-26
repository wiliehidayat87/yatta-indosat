<?php

class Controller extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('acl/controller_model');
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

        $jsFile = 'acl/controller.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Controller');
        $this->smarty->assign('status', $this->getStatus());
        $this->smarty->assign('parent_list', $this->getParentList());
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('acl/controller.tpl');
    }

    public function ajaxGetControllerList() {
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

        $mData = $this->controller_model->getControllerList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $menu = $dt['menu'];
                $parent = (empty($dt['parent'])) ? '-' : $dt['parent'];
                $link = (empty($dt['link'])) ? '-' : $dt['link'];
                $sort = $dt['sort'];
                $status = ($dt['status'] == 1) ? "Active" : "Inactive";

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                
                $result .= "<td>$menu</td>";
                $result .= "<td>$parent</td>";
                $result .= "<td>$link</td>";
                $result .= "<td>$sort</td>";
                $result .= "<td>$status</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editController($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteController($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "acl/controller/ajaxGetControllerList/";
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
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
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

    public function getParentList() {
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
        $result .="<select name=\"txt-parent\" id=\"txt-parent\">";
        $result .="<option value=\"0\">-</option>";
        foreach ($this->controller_model->readParentList() as $_data) {
            $id = $_data['id'];
            $parent = $_data['parent'];
            $parent_name = $_data['menu'];
            $result .="<option value=\"$id\">$parent_name</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function getStatus() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        return array('0' => 'Inactive', '1' => 'Active');
    }

    public function ajaxSaveController() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $menu_name = ucwords($this->input->post("txt-menu-name"));
        $parent = $this->input->post("txt-parent");
        $controller_link = strtolower($this->input->post("txt-controller-link"));
        $status = $this->input->post("txt-status");

        $response = array();

        //validate    
        if (empty($menu_name)) {
            $status_menu_name = FALSE;
            $msg_menu_name = "Menu Field is Required";
        } else {
            $status_menu_name = TRUE;
            $msg_menu_name = "";
        }

        if (empty($controller_link)) {
            $status_controlller_link = FALSE;
            $msg_controlller_link = "Controller Link is Required";
        } else {
            $status_controlller_link = TRUE;
            $msg_controlller_link = "";
        }

        if (!empty($menu_name) && !empty($controller_link)) {
            if ($this->controller_model->check_menu_name($menu_name)) {
                $response = array(
                    'status_menu_name' => FALSE,
                    'msg_menu_name' => "Menu Name already exist, try another name",
                    'status' => FALSE,
                    'message' => 'Menu Name already exist, try another name'
                );
            } else {
                $response = $this->controller_model->addController($menu_name, $parent, $controller_link, $status);
            }
        } else {
            $response = array(	'status_menu_name' 			=> $status_menu_name,
								'msg_menu_name' 			=> $msg_menu_name,
								'status_controller_link' 	=> $status_controlller_link,
								'msg_controller_link' 		=> $msg_controlller_link,
								'status' 					=> FALSE,
								'message' 					=> 'error'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxEditController() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->controller_model->editController($id);

        $response = array(
            'menu_name' => $result[0]['menu'],
            'parent' => $result[0]['parent'],
            'controller_link' => $result[0]['link'],
            'sort' => $result[0]['sort'],
            'status' => $result[0]['status'],
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateController($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $menu_name = ucwords($this->input->post("txt-menu-name"));
        $parent = $this->input->post("txt-parent");
        $controller_link = strtolower($this->input->post("txt-controller-link"));
        $sort = $this->input->post("txt-sort");
        $status = $this->input->post("txt-status");
        $menu_name_compare = ucwords($this->input->post("txt-menu-name-compare"));
        $sort_old = $this->input->post("txt-sort-old");

        $response = array();

        //validate    
        if (empty($menu_name)) {
            $status_menu_name = FALSE;
            $msg_menu_name = "Menu Field is Required";
        } else {
            $status_menu_name = TRUE;
            $msg_menu_name = "";
        }

        if (empty($controller_link)) {
            $status_controlller_link = FALSE;
            $msg_controlller_link = "Controller Link Field is Required";
        } else {
            $status_controlller_link = TRUE;
            $msg_controlller_link = "";
        }
        
        if (empty($sort)) {
            $sort = FALSE;
            $msg_sort = "Sort Field is Required";
        } else {
            $status_sort = TRUE;
            $msg_sort = "";
        }

        if (!empty($menu_name) && !empty($controller_link)) {
            if ($menu_name == $menu_name_compare) {
                $response = array('status' => TRUE, 'message' => '', 'id' => $id);
                if (is_numeric($sort)) {
                    $response = $this->controller_model->updateController($id, $menu_name, $parent, $controller_link, $sort, $sort_old, $status);
                } else {
                    $response = array('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            } else {
                if ($this->controller_model->check_menu_name($menu_name)) {
                    $response = array(
                        'status_menu_name' => FALSE,
                        'msg_menu_name' => "Menu Name already exist, try another name",
                        'status' => FALSE,
                        'message' => 'Menu Name already exist, try another name'
                    );
                } else {
                    if (is_numeric($sort)) {
                        $response = $this->controller_model->updateController($id, $menu_name, $parent, $controller_link, $sort, $sort_old, $status);
                    } else {
                        $response = array('status_sort' => FALSE, 'msg_sort' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                    }
                }
            }
        } else {
            $response = array(	'status_menu_name' 			=> $status_menu_name,
								'msg_menu_name' 			=> $msg_menu_name,
								'status_controller_link' 	=> $status_controlller_link,
								'msg_controller_link' 		=> $msg_controlller_link,
								'status_sort' 				=> $status_sort,
								'msg_sort' 					=> $msg_sort,
								'status' 					=> FALSE,
								'message' 					=> 'error'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteController() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->controller_model->deleteController($id);

        echo json_encode($response);
        exit;
    }

}
