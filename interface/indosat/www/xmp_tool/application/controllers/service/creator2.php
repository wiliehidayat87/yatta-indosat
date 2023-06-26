<?php

class Creator extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('service/creator_model');
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

        $jsFile = 'service/creator.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Creator');
        $this->smarty->assign('operator', $this->getOperatorList());
        $this->smarty->assign('service', $this->getServiceList());
        $this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->display('service/creator_view.tpl');
    }

    public function ajaxGetCreatorList() {
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

        $mData = $this->creator_model->getCreatorList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $pattern = $dt['pattern'];
                $handler = $dt['handler'];
                $operator = $dt['operator_name'];
                $service = $dt['service_name'];
                $date_created = $dt['date_created'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";

                $result .= "<td>$pattern</td>";
                $result .= "<td>$handler</td>";
                $result .= "<td>$operator</td>";
                $result .= "<td>$service</td>";
                $result .= "<td>$date_created</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editCreator($id);\">Edit</a> </td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "service/creator/ajaxGetCreatorList/";
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

    public function ajaxAddNewCreator() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator_id = $this->input->post("txt-operatorId");
        $service_id = $this->input->post("txt-serviceId");

        $response = array();

        //validate
        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = "Name Field is Required";
        } else {
            $status_pattern = TRUE;
            $msg_pattern = "";
        }

        $data['pattern'] = $pattern;
        $data['operator_id'] = $operator_id;
        $data['service_id'] = $service_id;
        
        if (!empty($pattern) && !empty($operator_id) && !empty($service_id)) {
            if ($this->creator_model->check_creator_name($data)) {
                $response = array(
                    'status_pattern' => FALSE,
                    'msg_pattern' => "Service Name and Adn already exist, please try another combination",
                    'status' => FALSE,
                    'message' => 'Service Name and Adn already exist, please try another combination'
                );
            } else {
                $response = $this->creator_model->addNewCreator($data);
                $mData = $this->creator_model->selectIdCreator();
                $data = $mData['result']['data'];

                foreach ($data as $key => $dt) {
                    $id = $dt['id'];
                }

                $response['id'] = $id;
            }
        } else {
            $response = array('status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status' => FALSE,
                'message' => 'error'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateCreator($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $pattern = $this->input->post("txt-pattern");
        $operator_id = $this->input->post("txt-operatorId");
        $service_id = $this->input->post("txt-serviceId");
        $pattern_compare = $this->input->post("pattern_compare");
        $operator_id_compare = $this->input->post("operatorId_compare");
        $service_id_compare = $this->input->post("serviceId_compare");

        $response = array();

        if ($pattern == $pattern_compare && $operator_id == $operator_id_compare && $service_id == $service_id_compare) {
            $response = array('status' => TRUE, 'message' => '', 'id' => $id);
            echo json_encode($response);
            exit;
        }

        //validate
        if (empty($pattern)) {
            $status_pattern = FALSE;
            $msg_pattern = "Name Field is Required";
        } else {
            $status_pattern = TRUE;
            $msg_pattern = "";
        }

        $data['id'] = $id;
        $data['pattern'] = $pattern;
        $data['operator_id'] = $operator_id;
        $data['service_id'] = $service_id;
        
        if (!empty($pattern) && !empty($operator_id) && !empty($service_id)) {
            if ($this->creator_model->check_creator_name($data)) {
                $response = array(
                    'status_pattern' => FALSE,
                    'msg_pattern' => "Service Name and Adn already exist, please try another combination",
                    'status' => FALSE,
                    'message' => 'Service Name and Adn already exist, please try another combination'
                );
            } else {
                $response = $this->creator_model->updateCreator($data);
            }
        } else {
            $response = array('status_pattern' => $status_pattern,
                'msg_pattern' => $msg_pattern,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status' => FALSE,
                'message' => 'error'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxEditCreator() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $result = $this->creator_model->editCreator($id);

        $response = array(
            'pattern' => $result[0]['pattern'],
            'operator_id' => $result[0]['operator_id'],
            'service_id' => $result[0]['service_id']
        );

        echo json_encode($response);
        exit;
    }

    public function getOperatorList() {
        $query = $this->creator_model->getOperatorList();

        $data = array();
        if ($query > 0) {
            foreach ($query as $dt) {
                $data[$dt['id']]['id'] = $dt['id'];
                $data[$dt['id']]['name'] = ucwords(strtolower($dt['name']));
            }
        }

        return $data;
    }

    public function getServiceList() {
        $query = $this->creator_model->getServiceList();

        $data = array();
        if ($query > 0) {
            foreach ($query as $dt) {
                $data[$dt['id']]['id'] = $dt['id'];
                $data[$dt['id']]['name'] = ucwords(strtolower($dt['name']));
            }
        }

        return $data;
    }

}

?>
