<?php
class Module extends MY_Controller {
	public $limit = 0;

    function __construct() {
		parent::__construct();
		
		$this->load->model('masterdata/module_model');
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
        
        $jsFile = 'masterdata/module.js';  
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Master Data Module');
        $this->smarty->assign('pageLimit',$this->limit);
        $this->smarty->display('masterdata/module.tpl');
    }
    
    public function ajaxGetModuleList() {
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
        
        $mData  = $this->module_model->getModuleList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
		$i = 1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id        = $dt['id'];
                $name  = $dt['name'];
                $description  = $dt['description'];
                $handler  = $dt['handler'];
    
                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                    
                $result .= "<td>$name</td>";
                $result .= "<td>$description</td>";
                $result .= "<td>$handler</td>";
                $result .= "<td class=\"last\"><div class=\"menulink\"><a href=\"javascript:void(0)\" onclick=\"editModule($id);\">Edit</a> <a href=\"javascript:void(0)\" onclick=\"deleteModule($id);\">Delete</a></div></td>";
                $result .= "</tr>";
                $i++;
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "masterdata/custom_handler/ajaxGetModuleList/";
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
       
    public function ajaxAddModule() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $name  			= $this->input->post("txt-name");
        $description  	= $this->input->post("txt-description");
        $handler  		= $this->input->post("txt-handler");
               
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
        if (empty ($handler)) {
            $status_handler = FALSE;
            $msg_handler    = "Handler Field is Required";
        }
        else {
            $status_handler = TRUE;
            $msg_handler    = "";
        }
                    
        if (!empty($name) && !empty($handler))
        {
            if ($this->module_model->checkModule($handler,''))
            {
                $response = array (
                        'status_handler' => FALSE, 
                        'msg_handler' => "Handler already exist, please try another name",
                        'status' => FALSE, 
                        'message' => 'Handler already exist, please try another name'
                         );
            }
            else
            {
                $response = $this->module_model->addModule($name,$description,$handler);
                
            }
        }
        else
        {
            $response = array ( 'status_name' 	=> $status_name,
                                'msg_name'  	=> $msg_name,
								'status_handler'=> $status_handler,
                                'msg_handler'  	=> $msg_handler,
                                'status' 		=> FALSE, 
                                'message' 		=> 'error'
                              );
        }
        
        echo json_encode($response);
        exit;
        
    }
  
    public function ajaxUpdateModule($id) {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
         
        $name  					= $this->input->post("txt-name");
        $description  			= $this->input->post("txt-description");
        $handler	  			= $this->input->post("txt-handler");
        $name_compare  			= $this->input->post("name_compare");
        $description_compare  	= $this->input->post("description_compare");
        $handler_compare  		= $this->input->post("handler_compare");
               
        $response = array ();
        
        if($name==$name_compare && $description==$description_compare && $handler==$handler_compare)
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
		if (empty ($handler)) {
            $status_handler = FALSE;
            $msg_handler    = "Handler Field is Required";
        }
        else {
            $status_handler = TRUE;
            $msg_handler    = "";
        }
        
        if (!empty($name) && !empty($handler))
        {
            if ($this->module_model->checkModule($handler,$id))
            {
                $response = array (
						'status_handler' => FALSE, 
                        'msg_handler' => "Handler already exist, please try another name",
                        'status' => FALSE, 
                        'message' => 'Handler already exist, please try another name'
                         );
            }
            else
            {
                $response = $this->module_model->updateModule($name,$description,$handler,$id);
            }
        }
        else
        {
            $response = array ( 'status_name' 	=> $status_name,
                                'msg_name'  	=> $msg_name,
								'status_handler'=> $status_handler,
                                'msg_handler'  	=> $msg_handler,
                                'status' 		=> FALSE, 
                                'message' 		=> 'error'
                              );
        }
        echo json_encode($response);
        exit;
        
    }
     public function ajaxEditModule() {
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id     = $this->input->post("id");
        $result = $this->module_model->editModule($id);

        $response = array (
						'name' 			=> $result[0]['name'],
						'description' 	=> $result[0]['description'],
						'handler' 		=> $result[0]['handler']
					);

        echo json_encode($response);
        exit;
    }
    
    public function ajaxDeleteModule() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }

        $id = $this->input->post("id");
        $response = $this->module_model->deleteModule($id);

        echo json_encode($response);
        exit;
    }
     
}
?>
