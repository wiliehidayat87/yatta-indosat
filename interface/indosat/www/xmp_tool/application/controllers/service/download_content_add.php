<?php

class Download_content_add extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->output->set_header('Content-Type:text/html; charset=UTF-8');
        $this->load->model('service/download_content_add_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());        
    }
    
    public function index($id = NULL) {
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $id     = $this->input->post('id');
        $jsFile = 'service/download_content_add.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('idDownload', $id);
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Add Download Content');                
        $this->smarty->display('service/download_content_add_view.tpl');                
    }
    
    public function ajaxAddNewContent(){
        $config=$this->config->item('image');		
        $this->load->library('upload', $config);
        
        $c_sort         = trim($this->input->post('txt-sort'));
        $c_contentcode  = trim($this->input->post('txt-contentcode'));
        $c_title        = trim($this->input->post('txt-title'));
        $c_price        = trim($this->input->post('txt-price'));
        $c_limit        = trim($this->input->post('txt-limit'));
        $c_idService    = trim($this->input->post('txt-service'));

        $c_image         = $this->upload->do_upload('file-image');
        if($c_image){
            $upload_data_image      = $this->upload->data();
            $image                  = 'preview_'.$c_contentcode.'_'.$c_title;            
            rename($config['upload_path'].'/'.$upload_data_image['file_name'],$config['upload_path'].'/'.$image.'.'.$upload_data_image['image_type']);
            $sImage                 = $image.'.'.$upload_data_image['image_type'];
            
        }else{
            $sImage                 = "";
        }
        
        if(!empty($c_contentcode) && !empty($c_title) && !empty($c_price)){
            if ($this->download_content_add_model->checkDownloadTitle($c_contentcode, $c_title)){
                return false;    
            }else{
            $result	= $this->download_content_add_model->saveNewContent(
                            $c_sort, $c_contentcode, $c_title, $c_price, $c_limit, $c_idService,
                            $sImage
                          );
            redirect(base_url().'service/download_content/index/'.$c_idService);
            }         
        }
    }
    
    public function ajaxCheckTitle(){
        $c_contentcode      = trim($this->input->post('code'));
        $c_title            = trim($this->input->post('title'));
        
        $response = array();
        
        if($this->download_content_add_model->checkDownloadTitle($c_contentcode, $c_title)){
            $response = array(
                        'status' => false,
                        'message' => 'Service title for that content code is already exist, please try another combination'
                    );
        }else{
            $response = array(
                        'status' => true,
                        'message' => ''
            );
        }
                        
        echo json_encode($response);
        exit;
    }
    
    public function ajaxCheckNumericSort(){        
        $c_sort            = trim($this->input->post('sort'));
        
        $response = array();
        
        if(!is_numeric($c_sort)){
            $response = array(
                        'status' => false,
                        'message' => 'Input must numeric'
                    );
        }else{
            $response = array(
                        'status' => true,
                        'message' => ''
            );
        }
                        
        echo json_encode($response);
        exit;
    }
    
    public function ajaxCheckNumericPrice(){        
        $c_price            = trim($this->input->post('price'));
        
        $response = array();
        
        if(!is_numeric($c_price)){
            $response = array(
                        'status' => false,
                        'message' => 'Input must numeric'
                    );
        }else{
            $response = array(
                        'status' => true,
                        'message' => ''
            );
        }
                        
        echo json_encode($response);
        exit;
    }
    
    public function ajaxCheckNumericLimit(){        
        $c_limit            = trim($this->input->post('limit'));
        
        $response = array();
        
        if(!is_numeric($c_limit)){
            $response = array(
                        'status' => false,
                        'message' => 'Input must numeric'
                    );
        }else{
            $response = array(
                        'status' => true,
                        'message' => ''
            );
        }
                        
        echo json_encode($response);
        exit;
    }
}
