<?php

class Custom_handler extends MY_Controller {
    public $limit = 0;

    public function  __construct() {
        parent::__construct();

        $this->load->model('service/custom_handler_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        
        $this->limit = $this->config->item('limit');
    }

    public function index(){
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }

        $jsFile = 'service/custom_handler.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Custom Handler');
        $this->smarty->assign('operator', $this->getOperatorList());
        $this->smarty->assign('service', $this->getServiceList());
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('service/custom_handler_view.tpl');
    }

    public function ajaxGetCustomHandlerList() {
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

        $mData = $this->custom_handler_model->getCustomHandlerList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $pattern = $dt['pattern'];
                $service = $dt['service'];
                $operator = $dt['operator'];
                $handler = $dt['handler'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";

                $result .= "<td>$pattern</td>";
                $result .= "<td>$service</td>";
                $result .= "<td>$operator</td>";
                $result .= "<td>$handler</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editCustomHandler($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteCustomHandler($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i ++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "service/custom_handler/ajaxGetCustomHandlerList/";
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
    }

    public function getOperatorList() {
        /*write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }*/

        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-operator\" id=\"txt-operator\">";
        foreach ($this->custom_handler_model->getOperator() as $_data) {
            $id = $_data['id'];
            $operator = $_data['name'];
            $result .="<option value=\"$id\">$operator</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function getServiceList() {
        /*write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }*/

        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-service\" id=\"txt-service\">";
        foreach ($this->custom_handler_model->getService() as $_data) {
            $id = $_data['id'];
            $service = $_data['name'];
            $result .="<option value=\"$id\">$service</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function ajaxSaveCustomHandler(){
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator = $this->input->post("txt-operator");
        $service = $this->input->post("txt-service");
        $handler = $this->input->post("txt-handler");
        
        $response = array();

        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = 'required field';
        } else {
            $status_pattern = TRUE;
            $msg_pattern = '';
        }
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = 'required field';
        } else {
            $status_operator = TRUE;
            $msg_operator = '';
        }
        if (empty($service)) {
            $status_service = FALSE;
            $msg_service = 'required field';
        } else {
            $status_service = TRUE;
            $msg_service = '';
        }
        if (empty($handler)) {
            $status_handler = FALSE;
            $msg_handler = 'required field';
        } else {
            $status_handler = TRUE;
            $msg_handler = '';
        }

        if (!empty ($pattern) && !empty ($operator) && !empty ($service) && !empty ($handler)) {
            $response = $this->custom_handler_model->saveCustomHandler($pattern, $operator, $service, $handler);
        } else {
            $response = array(
                'status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_service' => $status_service,
                'msg_service' => $msg_service,
                'status_handler' => $status_handler,
                'msg_handler' => $msg_handler,
                'status' => FALSE,
                'message' => 'required field'
            );
        }
        
        echo json_encode($response);
        exit;
    }

    public function ajaxEditCustomHandler() {
	write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->custom_handler_model->editCustomHandler($id);
        
        $response = array(
            'operator' => $result[0]['operator'],
            'pattern' => $result[0]['pattern'],
            'service' => $result[0]['service'],
            'handler' => $result[0]['handler']
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateCustomHandler($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator = $this->input->post("txt-operator");
        $service = $this->input->post("txt-service");
        $handler = $this->input->post("txt-handler");

        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = 'required field';
        } else {
            $status_pattern = TRUE;
            $msg_pattern = '';
        }
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = 'required field';
        } else {
            $status_operator = TRUE;
            $msg_operator = '';
        }
        if (empty($service)) {
            $status_service = FALSE;
            $msg_service = 'required field';
        } else {
            $status_service = TRUE;
            $msg_service = '';
        }
        if (empty($handler)) {
            $status_handler = FALSE;
            $msg_handler = 'required field';
        } else {
            $status_handler = TRUE;
            $msg_handler = '';
        }

        if (!empty ($pattern) && !empty ($operator) && !empty ($service) && !empty ($handler)) {
            $response = $this->custom_handler_model->updateCustomHandler($id, $pattern, $operator, $service, $handler);
        } else {
            $response = array(
                'status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_service' => $status_service,
                'msg_service' => $msg_service,
                'status_handler' => $status_handler,
                'msg_handler' => $msg_handler,
                'status' => FALSE,
                'message' => 'required field'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteCustomHandler() {
	write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->custom_handler_model->deleteCustomHandler($id);

        echo json_encode($response);
        exit;
    }
    
}