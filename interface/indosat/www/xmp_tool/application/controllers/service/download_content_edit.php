<?php

class Download_content_edit extends MY_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->output->set_header('Content-Type:text/html; charset=UTF-8');
        $this->load->model('service/download_content_edit_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
    }
    
    public function index($id=NULL){
        write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
        $jsFile = 'service/download_content_edit.js';
        $this->smarty->assign('jsFile', $jsFile);
        $id         = $this->input->post('id');
        $dataArr    = $this->getDataContent($id);
        
        $this->smarty->assign('id', $id);
        $this->smarty->assign('serviceID', $dataArr['service_id']);
        $this->smarty->assign('contentCode', $dataArr['code']);
        $this->smarty->assign('title', $dataArr['title']);
        $this->smarty->assign('price', $dataArr['price']);
        $this->smarty->assign('sort', $dataArr['sort']);
        $this->smarty->assign('limit', $dataArr['limit']);
        $this->smarty->assign('hImage', $dataArr['image']);

        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Edit Download Content');                
        $this->smarty->display('service/download_content_edit_view.tpl');
    }
    
    public function ajaxUpdateContent(){
        $config=$this->config->item('image');		
        $this->load->library('upload', $config);
        
        $id                 = $this->input->post('id');
        $c_idService        = trim($this->input->post('hidden-service'));
        $c_sort             = trim($this->input->post('txt-sort'));
        $c_contentcode      = trim($this->input->post('txt-contentcode'));
        $c_title            = trim($this->input->post('txt-title'));        
        $c_price            = trim($this->input->post('txt-price'));
        $c_limit            = trim($this->input->post('txt-limit'));        
        $o_c_sort           = trim($this->input->post('hidden-sort'));
        $o_c_contentcode    = trim($this->input->post('hidden-contentcode'));
        $o_c_title          = trim($this->input->post('hidden-title'));
        $o_image            = $this->input->post('hidden-image');
        
        if($c_title!==$o_c_title){
            $c_image         = $this->upload->do_upload('file-image');
            if($c_image){
                $upload_data_image    = $this->upload->data();
                $image                = 'preview_'.$c_contentcode.'_'.$c_title;
                rename($config['upload_path'].'/'.$upload_data_image['file_name'],$config['upload_path'].'/'.$image.'.'.$upload_data_image['image_type']);
                $sImage               = $image.'.'.$upload_data_image['image_type'];

            }else{
                if(empty($o_image)){
                    $sImage = "";
                }else{
                    $getImageType        = explode(".", $o_image);

                    $image               = 'preview_'.$c_contentcode.'_'.$c_title;
                    rename($config['upload_path'].'/'.$o_image,$config['upload_path'].'/'.$image.'.'.$getImageType[1]);
                    $sImage               = $image.'.'.$getImageType[1];            
                }
            }            
        }else{
            $c_image         = $this->upload->do_upload('file-image');
            if($c_image){
                $upload_data_image   = $this->upload->data();
                $image               = 'preview_'.$c_contentcode.'_'.$c_title;            
                rename($config['upload_path'].'/'.$upload_data_image['file_name'],$config['upload_path'].'/'.$image.'.'.$upload_data_image['image_type']);
                $sImage              =  $image.'.'.$upload_data_image['image_type'];

            }else{
                $sImage              = $o_image;
            }                        
        }
        
        if(!empty($c_contentcode) && !empty($c_title) && !empty($c_price)){
            $result	= $this->download_content_edit_model->updateContent(
                            $id, $c_sort, $c_contentcode, $c_title, $c_price, $c_limit, $c_idService,
                            $sImage
                          );
            redirect(base_url().'service/download_content/index/'.$c_idService);     
        }   
    }
    
    public function getDataContent($id){
        $dataContent = "";
        
        $dataContentArr = $this->download_content_edit_model->getDataContent($id);
        
        foreach ($dataContentArr as $dt) {
            $dataContent['id']          = $dt['id'];                
            $dataContent['service_id']  = $dt['wap_service_download_id'];                
            $dataContent['code']        = $dt['code'];
            $dataContent['title']       = $dt['title'];
            $dataContent['image']       = $dt['image'];
            $dataContent['price']       = $dt['price'];
            $dataContent['sort']        = $dt['sort'];            
            $dataContent['limit']       = $dt['limit'];            
        }
        
        return $dataContent;
    }
    
    public function ajaxCheckTitle(){
        $c_contentcode      = trim($this->input->post('code'));
        $c_title            = trim($this->input->post('title'));
        $old_c_title        = trim($this->input->post('old-title'));

        $response = array();       
        
        if($this->download_content_edit_model->checkDownloadTitle($c_contentcode, $c_title)){
            if ($c_title == $old_c_title) {
                $response = array(
                    'status' => true,
                    'message' => ''
                );
            }else{
                $response = array(
                    'status' => false,
                    'message' => 'Service title for that content codee is already exist, please try another combination'
                );
            }
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
