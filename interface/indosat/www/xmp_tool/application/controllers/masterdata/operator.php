<?php

class Operator extends MY_controller {

    public $limit = 0;

    function __construct() {
        parent::__construct();

        $this->load->model('masterdata/operator_model');
        $this->load->library('Link_auth');
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
		
        $jsFile = 'masterdata/operator.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Operator');
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/operator.tpl');
    }

    public function ajaxGetOperatorList() {
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

        $mData = $this->operator_model->getOperatorList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $operator_name = $dt['name'];
                $operator_long_name = $dt['long_name'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                
                $result .= "<td>$operator_name</td>";
                $result .= "<td>$operator_long_name</td>";

                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editOperator($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteOperator($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "masterdata/operator/ajaxGetOperatorList/";
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

    public function ajaxAddOperator() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $operator_name = $this->input->post("txt-operator-name");
        $operator_long_name = $this->input->post("txt-operator-long-name");

        $response = array();

        //validate
        if (empty($operator_name)) {
            $status_operator_name = FALSE;
            $msg_operator_name = "Operator Name Field is Required";
        } else {
            $status_operator_name = TRUE;
            $msg_operator_name = "";
        }

        if (empty($operator_long_name)) {
            $status_operator_long_name = FALSE;
            $msg_operator_long_name = "Operator Long Name Field is Required";
        } else {
            $status_operator_long_name = TRUE;
            $msg_operator_long_name = "";
        }

        if (!empty($operator_name) && !empty($operator_long_name)) {
            if ($this->operator_model->check_operator($operator_name, "")) {
                $response = array(
                    'status_operator_name' => FALSE,
                    'msg_operator_name' => "Operator Name already exist",
                    'status' => FALSE,
                    'message' => 'Operator Name already exist'
                );
            } else {
                $response = $this->operator_model->addOperator($operator_name, $operator_long_name);
            }
        } else {
            $response = array(
                'status_operator_name' => $status_operator_name,
                'msg_operator_name' => $msg_operator_name,
                'status_operator_long_name' => $status_operator_long_name,
                'msg_operator_long_name' => $msg_operator_long_name,
                'status' => FALSE,
                'message' => 'error');
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateOperator($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $operator_name = $this->input->post("txt-operator-name");
        $operator_long_name = $this->input->post("txt-operator-long-name");
        $operator_name_compare = $this->input->post("operator-name_compare");
        $operator_long_name_compare = $this->input->post("operator-long-name-compare");
        $response = array();
        
        if ($operator_name == $operator_name_compare && $operator_long_name == $operator_long_name_compare) {
            $response = array('status' => TRUE, 'message' => '');
            echo json_encode($response);
            exit;
        }

        if (empty($operator_name)) {
            $status_operator_name = FALSE;
            $msg_operator_name = "Operator Name Field is Required";
        } else {
            $status_operator_name = TRUE;
            $msg_operator_name = "";
        }

        if (empty($operator_long_name)) {
            $status_operator_long_name = FALSE;
            $msg_operator_long_name = "Operator Long Name Field is Required";
        } else {
            $status_operator_long_name = TRUE;
            $msg_operator_long_name = "";
        }

        if (!empty($operator_name) && !empty($operator_long_name)) {
            if ($this->operator_model->check_operator($operator_name, $id)) {
                $response = array(
                    'status_operator_name' => FALSE,
                    'msg_operator_name' => "Operator Name already exist",
                    'status' => FALSE,
                    'message' => 'Operator Name already exist'
                );
            } else {
                $response = $this->operator_model->updateOperator($operator_name, $operator_long_name, $id);
            }
        } else {
            $response = array(
                'status_operator_name' => $status_operator_name,
                'msg_operator_name' => $msg_operator_name,
                'status_operator_long_name' => $status_operator_long_name,
                'msg_operator_long_name' => $msg_operator_long_name,
                'status' => FALSE,
                'message' => 'error');
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxEditOperator() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id = $this->input->post("id");

        $result = $this->operator_model->editOperator($id);

        $response = array(
            'operator_name' => $result[0]['name'],
            'operator_long_name' => $result[0]['long_name']
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteOperator($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $response = $this->operator_model->deleteOperator($id);

        echo json_encode($response);
        exit;
    }

}

?>
