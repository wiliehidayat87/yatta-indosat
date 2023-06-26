<?php

class Method_group extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('acl/acl_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->smarty->assign('base_url', base_url());
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

        $id = $this->input->post("id");

        $jsFile = 'acl/method_group.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('id', $id);
        $this->smarty->assign('controller_list', $this->getControllerList());
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Method');
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('acl/method_group.tpl');
    }

    public function ajaxGetMethodGroupList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array ('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $group = $this->input->post("id");
        $search = strtoupper($this->input->post("search"));
        $page = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;
        $limit = $this->input->post("limit");
        $paging = "";
        $result = "";

        $mData = $this->acl_model->getMethodGroupList($offset, $limit, $search, $group);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $group = $dt['u_group'];
                $controller_link = $dt['controller_link'];
                $method = $dt['method'];
                $status = ($dt['status'] == '1') ? "Active" : "Inactive";

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                $result .= "<td>$group</td>";
                $result .= "<td>$controller_link</td>";
                $result .= "<td>$method</td>";
                $result .= "<td>$status</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"activeMethodGroup($id);\">Active</a> <a href=\"javascript:void(0)\" onclick=\"inactiveMethodGroup($id);\">Inactive</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "acl/method_group/ajaxGetMethodGroupList/";
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
            }
            else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
        } else {
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
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

    public function getControllerList() {
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

        foreach ($this->acl_model->getControllerList() as $dt) {
            $id = $dt['id'];
            $controller_list = $dt['link'];

            $result .= "<span><input type=\"checkbox\" name=\"controller-$i\" id=\"controller-$i\" class=\"controller-list\" value=\"$id\" />$controller_list</span><br />";

            $i++;
        }

        return $result;
    }

    public function ajaxScanMethodGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array ('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $group = $this->input->post("id");
        $controller_list = $this->input->post("controller-list");
        $controller = explode(",", $controller_list);
        
        if(empty($controller_list)){
			$response = array('status' => TRUE, 'message' => $controller);
			echo json_encode($response);
			exit;
		}

        foreach ($controller as $idCtrlLink) {
            $getCtrlLink = $this->getControllerLink($idCtrlLink);
            $path = $this->getFilePath($getCtrlLink['link']);

            $arrMethod = $this->getFileClassMethods($path);

            foreach ($arrMethod as $method) {
                if ($method != "__construct") {
                    $response = $this->acl_model->getClassMethod($group, $idCtrlLink, $method);
                    $response = array('status' => TRUE, 'message' => '');
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    private function getControllerLink($idCtrlLink) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $getCtrlName = $this->acl_model->getControllerName($idCtrlLink);

        foreach ($getCtrlName as $dt) {
            $ctrlName['id'] = $dt['id'];
            $ctrlName['link'] = $dt['link'];
        }
        return $ctrlName;
    }

    public function getFilePath($linkFile) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        if (strstr($linkFile, '/')) {
            $link = explode("/", $linkFile);

            $folder = $link['0'];
            $file = $link['1'] . ".php";
            $ctrlFolder = $this->config->item('controller_folder');
            $fileDir = scandir($ctrlFolder . "/" . $folder);
            foreach ($fileDir as $files => $fileKeys) {
                if ($fileKeys != '.' && $fileKeys != '..') {
                    if ($fileKeys == $file) {
                        $sendFilePath = $ctrlFolder . $folder . "/" . $fileKeys;
                        return $sendFilePath;
                    }
                }
            }
        } elseif ($linkFile) {
            $file = $linkFile . ".php";
            $ctrlFolder = $this->config->item('controller_folder');

            $fileDir = scandir($ctrlFolder);
            foreach ($fileDir as $files => $fileKeys) {
                if ($fileKeys != '.' && $fileKeys != '..') {
                    if ($fileKeys == $file) {
                        $sendFilePath = $ctrlFolder . $fileKeys;
                        return $sendFilePath;
                    }
                }
            }
        }

        return FALSE;
    }

    public function getFileClassMethods($file) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $arr = file($file);
        foreach ($arr as $line) {
            if (preg_match('/function ([_A-Za-z0-9]+)/', $line, $regs))
                $arr_methods[] = $regs[1];
        }
        return $arr_methods;
    }

    public function ajaxActiveMethodGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array ('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->acl_model->activeMethodGroup($id);

        echo json_encode($response);
        exit;
    }

    public function ajaxInactiveMethodGroup() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array ('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->acl_model->inactiveMethodGroup($id);

        echo json_encode($response);
        exit;
    }
    
    public function scanController() {
        //not done yet
        $group = $this->input->post("id");
        $controller_list = $this->input->post("controller-list");
        $controller = explode(",", $controller_list);

        foreach ($controller as $idCtrlLink) {
            $getCtrlLink = $this->getControllerLink($idCtrlLink);
            $path = $this->getFilePath($getCtrlLink['link']);

            $arrMethod = $this->getFileClassMethods($path);

            foreach ($arrMethod as $method) {
                if ($method != "__construct") {
                    $response = $this->acl_model->getClassMethod($group, $idCtrlLink, $method);
                    $response = array('status' => TRUE, 'message' => '');
                }
            }
        }
    }

}
