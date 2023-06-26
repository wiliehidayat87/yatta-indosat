<?php
class Custom_handler extends MY_Controller {
	public $limit = 0;

    function __construct() {
		parent::__construct();
		
		$this->load->model('masterdata/custom_handler_model');
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
        
        $jsFile = 'masterdata/custom_handler.js';  
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Custom Handler');
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/custom_handler.tpl');
    }
    
    public function ajaxGetCustomHandlerList() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
		$search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit 	= $this->input->post("limit");
        $paging = "";
        $result = "";
        
        $mData  = $this->custom_handler_model->getCustomHandlerList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
		$i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id        = $dt['id'];
                $name  = $dt['name'];
                $description  = $dt['description'];
    
                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                    
                $result .= "<td>$name</td>";
                $result .= "<td>$description</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editCustomHandler($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteCustomHandler($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "masterdata/custom_handler/ajaxGetCustomHandlerList/";
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
       
    public function ajaxAddCustomHandler() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $name  = $this->input->post("txt-name");
        $description  = $this->input->post("txt-description");
               
		$description=(empty($description))?"-":$description;		
        $response = array ();
        
        //validate
        if (empty ($name)) {
            $status_name = FALSE;
            $msg_name    = "Name Field is Required";
        }
        else {
            $status_name = TRUE;
            $msg_name    = "";
        }
                    
        if (!empty($name))
        {
            if ($this->custom_handler_model->checkCustomHandler($name,""))
            {
                $response = array (
                        'status_servname' => FALSE, 
                        'msg_name' => "Custom Handler Name already exist, please try another name",
                        'status' => FALSE, 
                        'message' => 'Custom Handler Name already exist, please try another name'
                         );
            }
            else
            {
                $response = $this->custom_handler_model->addCustomHandler($name,$description);
                
            }
        }
        else
        {
            $response = array ( 'status_name' => $status_name,
                                'msg_name'  => $msg_name,
                                'status' => FALSE, 
                                'message' => 'error'
                              );
        }
        
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
         
        $name  					= $this->input->post("txt-name");
        $description  			= $this->input->post("txt-description");
        $name_compare  			= $this->input->post("name_compare");
        $description_compare  	= $this->input->post("description_compare");
               
        $response = array ();
        
        if($name==$name_compare && $description==$description_compare)
        {
            $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
            echo json_encode($response);
            exit;
        }
       
		$description=(empty($description))?"-":$description;
		
        //validate
        if (empty ($name)) {
            $status_name = FALSE;
            $msg_name    = "Name Field is Required";
        }
        else {
            $status_name = TRUE;
            $msg_name    = "";
        }
          
        if (!empty($name))
        {
            if ($this->custom_handler_model->checkCustomHandler($name,$id))
            {
                $response = array (
                        'status_name' => FALSE, 
                        'msg_name' => "Custom Handler Name already exist, please try another name",
                        'status' => FALSE, 
                        'message' => 'Custom Handler Name already exist, please try another name'
                         );
            }
            else
            {
                $response = $this->custom_handler_model->updateCustomHandler($name,$description,$id);
            }
        }
        else
        {
            $response = array ( 'status_name' => $status_name,
                                'msg_name' => $msg_name,
                                'status' => FALSE, 
                                'message' => 'error'
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
        
        $id     = $this->input->post("id");
        $result = $this->custom_handler_model->editCustomHandler($id);

        $response = array (
						'name' 			=> $result[0]['name'],
						'description' 	=> $result[0]['description']
					);

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
?>
