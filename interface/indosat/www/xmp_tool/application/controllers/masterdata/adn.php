<?php

class Adn extends MY_Controller {

    public $limit = 0;

    public function __construct() {
        parent::__construct();

        $this->load->model('masterdata/adn_model');
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
		
        $jsFile = 'masterdata/adn.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data ADN');
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/adn.tpl');
    }

    public function ajaxGetAdnList() {
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

        $mData = $this->adn_model->getAdnList($offset, $limit, $search);
        $total = $mData['total'];
        $data = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id = $dt['id'];
                $adn_name = $dt['name'];
                $description = $dt['description'];
                $date_created = $dt['date_created'];

                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                
                $result .= "<td>$adn_name</td>";
                $result .= "<td>$description</td>";
                $result .= "<td>$date_created</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editAdn($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteAdn($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i ++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "masterdata/adn/ajaxGetAdnList/";
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

    public function ajaxAddAdn() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $adn_name = $this->input->post("txt-adn-name");
        $description = $this->input->post("txt-description");
        $response = array();

        //validate
        if (empty($adn_name)) {
            $status_adn_name = FALSE;
            $msg_adn_name = "ADN Name Field is Required";
        } else {
            $status_adn_name = TRUE;
            $msg_adn_name = "";
        }

        if (!empty($adn_name)) {
            if ($this->adn_model->check_adn_name($adn_name, "")) {
                $response = array(
							'status_adn_name' => FALSE,
							'msg_adn_name' => "ADN Already Exist",
							'status' => FALSE,
							'message' => 'ADN Already Exist'
							);
			} else {
				if (is_numeric($adn_name)) {
					$response = $this->adn_model->addAdn($adn_name, $description);
				}
				else{
					$response = array('status_adn_name' => FALSE, 'msg_adn_name' => "must numeric", 'status' => FALSE, 'message' => 'must numeric');
				}	
            }
        } else {
            $response = array('status_adn_name' => $status_adn_name,
                'msg_adn_name' => $msg_adn_name,
                'status' => FALSE,
                'message' => 'error'
                
            );
        }
      
        echo json_encode($response);
        exit;
    }

    public function ajaxUpdateAdn($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $adn_name = $this->input->post("txt-adn-name");
        $description = $this->input->post("txt-description");
        $adn_name_compare = $this->input->post("adn_name_compare");
        $description_compare = $this->input->post("description_compare");
        $response = array();

        if ($adn_name == $adn_name_compare && $description == $description_compare) {
            $response = array('status' => TRUE, 'message' => '');
            echo json_encode($response);
            exit;
        }

        //validate
        if (empty($adn_name)) {
            $status_adn_name = FALSE;
            $msg_adn_name = "ADN Name Field is Required";
        } else {
            $status_adn_name = TRUE;
            $msg_adn_name = "";
        }
        
        if (!empty($adn_name)) {
            if ($this->adn_model->check_adn_name($adn_name, $id)) {
                $response = array(
                    'status_adn_name' => FALSE,
                    'msg_adn_name' => "ADN Already Exist",
                    'status' => FALSE,
                    'message' => 'ADN Already Exist'
                );
            } else {
				if (is_numeric($adn_name)) {
					$response = $this->adn_model->addAdn($adn_name, $description);
				}
				else{
					$response = $this->adn_model->updateAdn($adn_name, $description, $id);
				}	
            }
        } else {
            $response = array('status_adn_name' => $status_adn_name,
                'msg_adn_name' => $msg_adn_name,
                'status_description' => $status_description,
                'msg_description' => $msg_description,
                'status' => FALSE,
                'message' => 'error'
            );
        }

        echo json_encode($response);
        exit;
    }

    public function ajaxEditAdn() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id = $this->input->post("id");
        $result = $this->adn_model->editAdn($id);

        $response = array(
            'adn_name' => $result[0]['name'],
            'description' => $result[0]['description']
        );

        echo json_encode($response);
        exit;
    }

    public function ajaxDeleteAdn($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $response = $this->adn_model->deleteAdn($id);

        echo json_encode($response);
        exit;
    }

}

?>
