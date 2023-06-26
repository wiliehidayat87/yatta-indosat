<?php
class Service extends MY_Controller {
	public $limit = 0;

    function __construct() {
		parent::__construct();
		
		$this->load->model(array('masterdata/adn_model','masterdata/service_model'));
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
        
        $jsFile = 'masterdata/service.js';  
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Service');
        $this->smarty->assign('adn',$this->getAdn());
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/service.tpl');
    }
    
    public function ajaxGetServiceList() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
		$search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit = $this->input->post("limit");
        $paging = "";
        $result = "";
        
        $mData  = $this->service_model->getServiceList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
		$i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id        = $dt['id'];
                $service_name  = $dt['name'];
                $adn = $dt['adn'];
               // $description  = $dt['description'];
                $date_created  = $dt['date_created'];
    
                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                    
                $result .= "<td>$service_name</td>";
                $result .= "<td>$adn</td>";
                $result .= "<td></td>";
                $result .= "<td>$date_created</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editService($id);\">Edit</a> </td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "masterdata/service/ajaxGetServiceList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows']  = $total;
                $pagination['per_page']    = $limit;

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

        $response = array (
            'offset' => $offset,
            'query'  => $mData['query'],
            'result' => $result,
            'paging' => $paging,
            'from'   => ($page + 1),
            'to'     => $to,
            'total'  => $total
        );

        echo json_encode($response);
    }
       
    public function ajaxAddNewService() {
	write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $service_name  = $this->input->post("txt-service-name");
        $adn  = $this->input->post("txt-adn");
               
        $response = array ();
        
        //validate
        if (empty ($service_name)) {
            $status_servname = FALSE;
            $msg_servname    = "Name Field is Required";
        }
        else {
            $status_servname = TRUE;
            $msg_servname    = "";
        }
        
        if (empty ($adn)) {
            $status_adn = FALSE;
            $msg_adn    = "ADN Field is Required";
        }
        else {
            $status_adn = TRUE;
            $msg_adn    = "";
        }
        
        if (!empty($service_name) && !empty($adn))
        {
            if ($this->service_model->check_service_name($service_name,$adn))
            {
                $response = array (
                        'status_servname' => FALSE, 
                        'msg_servname' => "Service Name and Adn already exist, please try another combination",
                        'status' => FALSE, 
                        'message' => 'Service Name and Adn already exist, please try another combination'
                         );
            }
            else
            {
                $response = $this->service_model->addNewService($service_name,$adn);
                $mData  = $this->service_model->selectIdService();
                $data   = $mData['result']['data'];
                
                foreach ($data as $key => $dt){
                $id = $dt['id'];
                }
                
                $response['id']=$id;
            }
        }
        else
        {
            $response = array ( 'status_servname' => $status_servname,
                                'msg_servname'  => $msg_servname,
                                'status_adn' => $status_adn,
                                'msg_adn' => $msg_adn,
                                'status' => FALSE, 
                                'message' => 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
  
    public function ajaxUpdateService($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
         
        $service_name  = $this->input->post("txt-service-name");
        $adn  = $this->input->post("txt-adn");
        $service_name_compare  = $this->input->post("service_name_compare");
        $adn_compare  = $this->input->post("adn_compare");
               
        $response = array ();
        
        if($service_name==$service_name_compare && $adn==$adn_compare)
        {
            $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
            echo json_encode($response);
            exit;
        }
       
        //validate
        if (empty ($service_name)) {
            $status_servname = FALSE;
            $msg_servname    = "Name Field is Required";
        }
        else {
            $status_servname = TRUE;
            $msg_servname    = "";
        }
        
        if (empty ($adn)) {
            $status_adn = FALSE;
            $msg_adn    = "ADN Field is Required";
        }
        else {
            $status_adn = TRUE;
            $msg_adn    = "";
        }
           
        
        if (!empty($service_name) && !empty($adn))
        {
            if ($this->service_model->check_service_name($service_name,$adn))
            {
                $response = array (
                        'status_servname' => FALSE, 
                        'msg_servname' => "Service Name and Adn already exist, please try another combination",
                        'status' => FALSE, 
                        'message' => 'Service Name and Adn already exist, please try another combination'
                         );
            }
            else
            {
                $response = $this->service_model->updateService($service_name,$adn,$id);
            }
        }
        else
        {
            $response = array ( 'status_servname' => $status_servname,
                                'msg_servname' => $msg_servname,
                                'status_adn' => $status_adn,
                                'msg_adn' => $msg_adn,
                                'status' => FALSE, 
                                'message' => 'error'
                              );
        }
        echo json_encode($response);
        exit;
        
    }
     public function ajaxEditService() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id     = $this->input->post("id");
        $result = $this->service_model->editService($id);

        $response = array (
            'service_name' => $result[0]['name'],
            'adn' => $result[0]['adn']
       );

        echo json_encode($response);
        exit;
    }
    
    public function getAdn() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        	
        $mData  = $this->adn_model->getAdnList("", "", "");
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
       
        if ($total > 0) 
        {
            foreach ($data as $key => $dt)
            {
                $adn[$dt['name']]  = $dt['name'];
                
            }
            
        }
    return $adn;
    }
  
}
?>
