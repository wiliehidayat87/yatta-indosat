<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class History extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('cs/history_model');
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

        $jsFile = 'cs/history.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : CS History');
        $this->smarty->assign('pageLimit', $this->limit);
        $this->smarty->assign('service', $this->history_model->getService());
        $this->smarty->assign('operator', $this->history_model->getOperator());
        $this->smarty->assign('adn', $this->history_model->getAdn());
        $this->smarty->assign('subject', $this->history_model->getSubject());
        $this->smarty->display('cs/history.tpl');
    }

    public function getHistoryTable() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $param = array();
        if ($this->input->post('adn')) {
            $param['adn'] = $this->input->post('adn');
        } else {
            $param['adn'] = '';
        }
        if ($this->input->post('msisdn')) {
            $param['msisdn'] = $this->input->post('msisdn');
        } else {
            $param['msisdn'] = '';
        }
        if ($this->input->post('msgdata')) {
            $param['msgdata'] = $this->input->post('msgdata');
        } else {
            $param['msgdata'] = '';
        }
        if ($this->input->post('service')) {
            $param['service'] = $this->input->post('service');
        } else {
            $param['service'] = '';
        }
        if ($this->input->post('subject')) {
            $param['subject'] = $this->input->post('subject');
        } else {
            $param['subject'] = '';
        }
        if ($this->input->post('operator')) {
            $param['operator'] = $this->input->post('operator');
        } else {
            $param['operator'] = '';
        }
        if ($this->input->post('date')) {
            $param['date'] = $this->input->post('date');
        } else {
            $param['date'] = '';
        }


        $search = strtoupper($this->input->post("search"));
        $page = $this->uri->segment(4);
        $param['offset'] = (isset($page)) ? (int) $page : 0;
        $param['limit'] = $this->input->post("limit");
        $paging = "";
        $result = "";

        $mData = $this->history_model->getSearch($param);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $adn = $dt['ADN'];
                $msisdn = $dt['MSISDN'];
                $operator = $dt['operator'];
                $service = $dt['SERVICE'];
                $msgData = $dt['MSGDATA'];
                $msgLastStatus = $dt['MSGLASTSTATUS'];
                $msgStatus = $dt['MSGSTATUS'];
                $closeReason = $dt['CLOSEREASON'];
                $price = $dt['PRICE'];
                $subject = $dt['SUBJECT'];
                $msgTimestamp = $dt['MSGTIMESTAMP'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                $result .= "<td>$adn</td>";
                $result .= "<td>$msisdn</td>";
                $result .= "<td>$operator</td>";
                $result .= "<td>$service</td>";
                $result .= "<td>$msgData</td>";
                $result .= "<td>$msgLastStatus</td>";
                $result .= "<td>$msgStatus</td>";
                $result .= "<td>$closeReason</td>";
                $result .= "<td>$price</td>";
                $result .= "<td>$subject</td>";
                $result .= "<td>$msgTimestamp</td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $param['limit']) {
                $this->load->library('pagination');

                $pagination = array();
                $pagination['base_url'] = base_url() . "cs/history/getHistoryTable/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
                $pagination['per_page'] = $param['limit'];

                $this->pagination->initialize($pagination);
                $paging_data = $this->pagination->create_links();
                $paging_data = preg_replace('/\<strong\>(\d+)\<\/strong\>/i', '<a class="current" href="">$1</a>', $paging_data);
                $paging_data = explode("&nbsp;", $paging_data);
                $paging = "<li>Total row: $total &nbsp;</li>";
                foreach ($paging_data as $page) {
                    if (!empty($page))
                        $paging.="<li>$page</li>";
                }
            } else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
        } else {
            $result .= "<tr><td colspan=\"11\">No data found</td></tr>";
        }

        $to = ($page + $param['limit']) > $total ? $total : ($page + $param['limit']);


        $response = array(
            'offset' => $param['offset'],
            'query' => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from' => ($page + 1),
            'to' => $to,
            'total' => $total
        );

        echo json_encode($response);
    }

    public function pagination() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        if ($this->input->post('adn')) {
            $adn = $this->input->post('adn');
        } else {
            $adn = '';
        }
        if ($this->input->post('msisdn')) {
            $msisdn = $this->input->post('msisdn');
        } else {
            $msisdn = '';
        }
        if ($this->input->post('msgdata')) {
            $msgdata = $this->input->post('msgdata');
        } else {
            $msgdata = '';
        }
        if ($this->input->post('service')) {
            $service = $this->input->post('service');
        } else {
            $service = '';
        }
        if ($this->input->post('operator')) {
            $operator = $this->input->post('operator');
        } else {
            $operator = '';
        }
        if ($this->input->post('date')) {
            $date = $this->input->post('date');
        } else {
            $date = '';
        }

        $paging = "";
        $limit = 10;
        $getTotal = $this->history_model->getTotalSearch($adn, $msisdn, $msgdata, $operator, $service, $date);
        $total = count($getTotal);
        if ($total > $limit) {
            $this->load->library('pagination');

            $pagination['base_url'] = base_url() . "cs/history/getHistoryTable/";
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

        if (empty($total))
            $paging = '<li></li>';

        $response['paging'] = $paging;

        if (empty($total))
            $response['status'] = "nodata";
        else
            $response['status'] = "data available";

        echo json_encode($response);
        exit;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
