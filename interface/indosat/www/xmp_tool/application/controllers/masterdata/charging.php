<?php

class Charging extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('masterdata/charging_model');
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
		
        $jsFile = 'masterdata/charging.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Charging');
        $this->smarty->assign('operator', $this->getOperatorList());
        $this->smarty->assign('adn', $this->getAdnList());
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/charging.tpl');
    }

    public function ajaxGetChargingList() {
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

        $mData = $this->charging_model->getChargingList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $operator = $dt['operator'];
                $adn = $dt['adn'];
                $charging_id = $dt['charging_id'];
                $gross = $dt['gross'];
                $netto = $dt['netto'];
                $username = $dt['username'];
                $sender_type = $dt['sender_type'];
                $message_type = $dt['message_type'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                
                $result .= "<td>$operator</td>";
                $result .= "<td>$adn</td>";
                $result .= "<td>$charging_id</td>";
                $result .= "<td>$gross</td>";
                $result .= "<td>$netto</td>";
                $result .= "<td>$sender_type</td>";
                $result .= "<td>$message_type</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editCharging($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteCharging($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i ++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "masterdata/charging/ajaxGetChargingList/";
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
            $result .= "<tr><td colspan=\"8\">No data found</td></tr>";
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
        $result .="<select name=\"txt-operator\" id=\"txt-operator\">";
        foreach ($this->charging_model->readOperator() as $_data) {
            $id = $_data['id'];
            $operator = $_data['name'];
            $result .="<option value=\"$id\">$operator</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function getAdnList() {
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
        $result .="<select name=\"txt-adn\" id=\"txt-adn\">";
        foreach ($this->charging_model->readAdn() as $__data) {
            $id = $__data['id'];
            $adn = $__data['name'];
            $result .="<option value=\"$adn\">$adn</option>";
        }
        $result .="</select>";
        $result .="</span>";
        return $result;
    }

    public function ajaxSaveCharging() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $operator = $this->input->post("txt-operator");
        $adn = $this->input->post("txt-adn");
        $charging_id = $this->input->post("txt-charging-id");
        $gross = $this->input->post("txt-gross");
        $netto = $this->input->post("txt-netto");
        $username = $this->input->post("txt-username");
        $password = $this->input->post("txt-password");
        $sender_type = $this->input->post("txt-sender-type");
        $message_type = $this->input->post("txt-message-type");
        $preg = true;
        $response = array();

        #validate
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = "Operator Field is Required";
        } else {
            $status_operator = TRUE;
            $msg_operator = "";
        }
        if (empty($adn)) {
            $status_adn = FALSE;
            $msg_adn = "ADN Field is Required";
        } else {
            $status_adn = TRUE;
            $msg_adn = "";
        }
        if (empty($charging_id)) {
            $status_charging_id = FALSE;
            $msg_charging_id = "Charging Field is Required";
        } else {
            $status_charging_id = TRUE;
            $msg_charging_id = "";
        }
        if (empty($gross)) {
            $status_gross = FALSE;
            $msg_gross = "Gross Field is Required";
        } else {
            $status_gross = TRUE;
            $msg_gross = "";
        }
        if (empty($netto)) {
            $status_netto = FALSE;
            $msg_netto = "Netto Field is Required";
        } else {
            $status_netto = TRUE;
            $msg_netto = "";
        }
        if (empty($username)) {
            $status_username = FALSE;
            $msg_username = "Username Field is Required";
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
        if (empty($sender_type)) {
            $status_sender_type = FALSE;
            $msg_sender_type = "Sender Type Field is Required";
        } else {
            $status_sender_type = TRUE;
            $msg_sender_type = "";
        }
        if (empty($message_type)) {
            $status_message_type = FALSE;
            $msg_message_type = "Message Type Field is Required";
        } else {
            $status_message_type = TRUE;
            $msg_message_type = "";
        }

        if (preg_match('/[a-zA-Z]/', $netto)) {
            $status_netto = FALSE;
            $msg_netto = "Number Only";
            $preg = false;
        }
        if (preg_match('/[a-zA-Z]/', $gross)) {
            $status_gross = FALSE;
            $msg_gross = "Number Only";
            $preg = false;
        }

        if (!empty($operator) && !empty($adn) && !empty($charging_id) && !empty($gross) && !empty($netto) && !empty($username) && !empty($password) && !empty($sender_type) && !empty($message_type) && $preg == true) {
            if (is_numeric($gross)) {
                if (is_numeric($netto)) {
					if($this->charging_model->checkCharging($adn, $charging_id, $operator,'')){
						$response = array(
										'status_netto' 	=> FALSE,
										'msg_netto' 	=> "Charging,adn,operator is not available, please try another combination",
										'status' 		=> FALSE,
										'message' 		=> 'Charging,adn,operator is not available, please try another combination'
									);
						
					}else{
						$response = $this->charging_model->saveCharging($operator, $adn, $charging_id, $gross, $netto, $username, $password, $sender_type, $message_type);
					}
					
                } else {
                    $response = array('status_netto' => FALSE, 'msg_netto' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            } else {
                $response = array('status_gross' => FALSE, 'msg_gross' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
            }
        } else {
            $response = array(
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status_charging_id' => $status_charging_id,
                'msg_charging_id' => $msg_charging_id,
                'status_gross' => $status_gross,
                'msg_gross' => $msg_gross,
                'status_netto' => $status_netto,
                'msg_netto' => $msg_netto,
                'status_username' => $status_username,
                'msg_username' => $msg_username,
                'status_password' => $status_password,
                'msg_password' => $msg_password,
                'status_sender_type' => $status_sender_type,
                'msg_sender_type' => $msg_sender_type,
                'status_message_type' => $status_message_type,
                'msg_message_type' => $msg_message_type,
                'status' => FALSE,
                'message' => 'required field'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxEditCharging() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id = $this->input->post("id");
        $result = $this->charging_model->editCharging($id);

        $response = array(
            'operator' => $result[0]['operator'],
            'adn' => $result[0]['adn'],
            'charging_id' => $result[0]['charging_id'],
            'gross' => $result[0]['gross'],
            'netto' => $result[0]['netto'],
            'username' => $result[0]['username'],
            'password' => $result[0]['password'],
            'sender_type' => $result[0]['sender_type'],
            'message_type' => $result[0]['message_type']
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateCharging($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $operator = $this->input->post("txt-operator");
        $adn = $this->input->post("txt-adn");
        $charging_id = $this->input->post("txt-charging-id");
        $gross = $this->input->post("txt-gross");
        $netto = $this->input->post("txt-netto");
        $username = $this->input->post("txt-username");
        $password = $this->input->post("txt-password");
        $sender_type = $this->input->post("txt-sender-type");
        $message_type = $this->input->post("txt-message-type");
        $preg = true;
         #validate
        if (empty($operator)) {
            $status_operator = FALSE;
            $msg_operator = "Operator Field is Required";
        } else {
            $status_operator = TRUE;
            $msg_operator = "";
        }
        if (empty($adn)) {
            $status_adn = FALSE;
            $msg_adn = "ADN Field is Required";
        } else {
            $status_adn = TRUE;
            $msg_adn = "";
        }
        if (empty($charging_id)) {
            $status_charging_id = FALSE;
            $msg_charging_id = "Charging Field is Required";
        } else {
            $status_charging_id = TRUE;
            $msg_charging_id = "";
        }
        if (empty($gross)) {
            $status_gross = FALSE;
            $msg_gross = "Gross Field is Required";
        } else {
            $status_gross = TRUE;
            $msg_gross = "";
        }
        if (empty($netto)) {
            $status_netto = FALSE;
            $msg_netto = "Netto Field is Required";
        } else {
            $status_netto = TRUE;
            $msg_netto = "";
        }
        if (empty($username)) {
            $status_username = FALSE;
            $msg_username = "Username Field is Required";
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
        if (empty($sender_type)) {
            $status_sender_type = FALSE;
            $msg_sender_type = "Sender Type Field is Required";
        } else {
            $status_sender_type = TRUE;
            $msg_sender_type = "";
        }
        if (empty($message_type)) {
            $status_message_type = FALSE;
            $msg_message_type = "Message Type Field is Required";
        } else {
            $status_message_type = TRUE;
            $msg_message_type = "";
        }

        if (preg_match('/[a-zA-Z]/', $netto)) {
            $status_netto = FALSE;
            $msg_netto = "Number Only";
            $preg = false;
        }
        if (preg_match('/[a-zA-Z]/', $gross)) {
            $status_gross = FALSE;
            $msg_gross = "Number Only";
            $preg = false;
        }

        if (!empty($operator) && !empty($adn) && !empty($charging_id) && !empty($gross) && !empty($netto) && !empty($username) && !empty($password) && !empty($sender_type) && !empty($message_type) && $preg == true) {
            if (is_numeric($gross)) {
                if (is_numeric($netto)) {
					if($this->charging_model->checkCharging($adn, $charging_id, $operator,$id)){
						$response = array(
										'status_netto' 	=> FALSE,
										'msg_netto' 	=> "Charging,adn,operator is not available, please try another combination",
										'status' 		=> FALSE,
										'message' 		=> 'Charging,adn,operator is not available, please try another combination'
									);
					}else{
						$response = $this->charging_model->updateCharging($id, $operator, $adn, $charging_id, $gross, $netto, $username, $password, $sender_type, $message_type);
					}
                } else {
                    $response = array('status_netto' => FALSE, 'msg_netto' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
                }
            } else {
                $response = array('status_gross' => FALSE, 'msg_gross' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
            }
        } else {
            $response = array(
                'status_operator' => $status_operator,
                'msg_operator' => $msg_operator,
                'status_adn' => $status_adn,
                'msg_adn' => $msg_adn,
                'status_charging_id' => $status_charging_id,
                'msg_charging_id' => $msg_charging_id,
                'status_gross' => $status_gross,
                'msg_gross' => $msg_gross,
                'status_netto' => $status_netto,
                'msg_netto' => $msg_netto,
                'status_username' => $status_username,
                'msg_username' => $msg_username,
                'status_password' => $status_password,
                'msg_password' => $msg_password,
                'status_sender_type' => $status_sender_type,
                'msg_sender_type' => $msg_sender_type,
                'status_message_type' => $status_message_type,
                'msg_message_type' => $msg_message_type,
                'status' => FALSE,
                'message' => 'required field'
            );
        }
        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteCharging() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id = $this->input->post("id");
        $response = $this->charging_model->deleteCharging($id);

        echo json_encode($response);
        exit;
    }

}

?>
