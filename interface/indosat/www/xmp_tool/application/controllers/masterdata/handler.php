<?php

class Handler extends MY_Controller {
    public $limit = 0;
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('masterdata/handler_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
        
    }
    
    public function index() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            print_r($this->link_auth->errorMessage());
            exit;
        }

        $jsFile = 'masterdata/handler.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Handler');
        $this->smarty->assign('status', $this->getStatus());
        $this->smarty->display('masterdata/handler.tpl');
    
    }
    
    public function ajaxGetHandlerList() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(3);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";
        $params = array ('offset' => $offset, 'limit' => $limit, 'search' => $search);
 
        $mData  = $this->handler_model->getHandlerList($params);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        $i=1;
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id             = $dt['id'];
                $name           = $dt['name'];
                $description    = $dt['description'];
                $status         = $dt['status'];
                
                if ($i % 2)
                    $result .= "<tr class=\"odd\">";
                else
                    $result .= "<tr>";
                
                $result .= "<td>$name</td>";
                $result .= "<td>$description</td>";
                $result .= "<td><div class=\"menulink\"><a onclick=\"editHandler($id);\">Edit</a> <a onclick=\"deleteHandler($id)\">Delete</a></div></td>";
                $result .= "</tr>";
                $i ++;
            }
            
            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "handler/ajaxGetHandlerList/";
                $pagination['uri_segment'] = 3;
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
            }
            else {
                $paging = "<b>1</b>";
            }
        }
        else {
            $result .= "<tr><td colspan=\"5\">No data found</td></tr>";
        }

        $to = ($page + $limit) > $total ? $total : ($page + $limit);

        $response = array (
            'offset' => $offset,
            'result' => $result,
            'paging' => $paging,
            'from'   => ($page + 1),
            'to'     => $to,
            'total'  => $total
        );

        echo json_encode($response);
    }    
    
     public function ajaxSaveHandler() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
         
        $handler_name        = $this->input->post("txt-handler-name");
        $handler_description = $this->input->post("txt-handler-description");
                      
        if (preg_match ('/[0-9]/', $handler_name)) {
            $response = array (
                'status_handler_name' => FALSE,
                'msg_handler_name' => "Handler can't be number",
                'status' => FALSE,
                'message' => 'Handler cant be number'
            );

            echo json_encode($response);
            exit;
        }	
        
        $handler_name=str_replace(" ","_",$handler_name);
        $handler_name=strtolower($handler_name);
                
        $response = array();
        
        #validate
        if (empty ($handler_name)) {
            $status_handler_name = FALSE;
            $msg_handler_name    = "required field";
        }
        else {
            $status_handler_name = TRUE;
            $msg_handler_name    = "";
        }        
                       
        if(!empty ($handler_name))
        {
            $check=$this->handler_model->check_handler($handler_name,"");
            
            if ($check['status']=="name_already_exist")
            {
                $response = array (
                           'status_handler_name' => FALSE, 
                           'msg_handler_name' => 'Handler Name already exist',
                           'status' => FALSE, 
                           'message' => 'Handler Name already exist'
                           );
            }
            else
            {
                if ($check['status']=="add")
                {
                    $response = $this->handler_model->saveHandler($handler_name, $handler_description);
                }
                else
                {
                    $response = $this->handler_model->updateHandler($check['id'], $handler_name, $handler_description,$check['status']);                 
                }
            }    
        }
        else
        {
            $response = array (
                    'status_handler_name'        => $status_handler_name,
                    'msg_handler_name'           => $msg_handler_name,
                    'status'                     => FALSE, 
                    'message'                    => 'required field'                
            );
        }        
        echo json_encode($response);
        exit;
    }    
    
    public function ajaxEditHandler() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        $id     = $this->input->post("id");
        $result = $this->handler_model->editHandler($id);

        $response = array (
            'handler_name'           => $result[0]['name'],
            'handler_description'    => $result[0]['description']           
        );

        echo json_encode($response);
        exit;
    }
    
    public function ajaxUpdateHandler($id) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $handler_name        = $this->input->post("txt-handler-name");
        $handler_description = $this->input->post("txt-handler-description");       
        $handler_name_compare = $this->input->post("txt-handler-name-compare");
        $handler_description_compare  = $this->input->post("txt-handler-description-compare");
        $response = array ();
          
        if($handler_name == $handler_name_compare && $handler_description==$handler_description_compare)
        {
            $response = array ('status' => TRUE, 'message' => '');
            echo json_encode($response);
            exit;
        }
        
        if (preg_match ('/[0-9]/', $handler_name )) 
	{
            $response = array (
                           'status_handler_name' => FALSE, 
                           'msg_handler_name' => "Handler can't be number",
                           'status' => FALSE, 
                           'message' => 'Handler cant be number'
                           );
            echo json_encode($response);
            exit;
        }	
        
        $handler_name=str_replace(" ","_",$handler_name);
        
        $handler_name=strtolower($handler_name); 
                
        if (empty ($handler_name)) {
            $status_handler_name = FALSE;
            $msg_handler_name    = "required field";
        }
        else {
            $status_handler_name = TRUE;
            $msg_handler_name    = "";
        }        
                
        if(!empty ($handler_name))
        {
            $check=$this->handler_model->check_handler($handler_name,$id);
            
            if ($check['status']=="name_already_exist" || $check['status']=="restore" )
            {
                $response = array (
                           'status_handler_name' => FALSE, 
                           'msg_handler_name' => 'Handler Name already exist',
                           'status' => FALSE, 
                           'message' => 'Handler Name already exist'
                           );
            }
            else
            {
                $response = $this->handler_model->updateHandler($id, $handler_name, $handler_description,"");                 
            }    
                          
        }
        else{
            $response = array (
                    'status_handler_name'        => $status_handler_name,
                    'msg_handler_name'           => $msg_handler_name,
                    'status'                     => FALSE, 
                    'message'                    => 'required field'                
            );
        }               
        echo json_encode($response);
        exit;
    }
    
    public function ajaxDeleteHandler() {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
        
        $id       = $this->input->post("id");
        $response = $this->handler_model->deleteHandler($id);

        echo json_encode($response);
        exit;
    }    
}

?>
