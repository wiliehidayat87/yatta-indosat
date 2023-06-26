<?php

class Download_add extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->output->set_header('Content-Type:text/html; charset=UTF-8');
        $this->load->model('service/download_add_model');
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
        $jsFile = 'service/download_add.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('id', $id);
        $this->smarty->assign('nameList', $this->getNameList());
        $this->smarty->assign('typeList', $this->getTypeList());
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Add Download Service');                
        $this->smarty->display('service/download_add_view.tpl');                
    }
    
    public function ajaxAddNewService(){
        $config=$this->config->item('image');		
        $this->load->library('upload', $config);
        
        $download_name      = $this->input->post('name-list');
        $download_title     = trim($this->input->post('txt-title'));
        $download_disclaim  = trim($this->input->post('txt-disclaimer'));
        $download_desc      = trim($this->input->post('txt-description'));
        $download_type      = $this->input->post('type-list');
        
        $d_header_1         = $this->upload->do_upload('file-header-1');
        if($d_header_1){
            $upload_data_header1    = $this->upload->data();
            $header_1               = 'header_'.$download_title.'_1';            
            rename($config['upload_path'].'/'.$upload_data_header1['file_name'],$config['upload_path'].'/'.$header_1.'.'.$upload_data_header1['image_type']);
            $sHeader1               = $header_1.'.'.$upload_data_header1['image_type'];
            
        }else{
            $sHeader1               = "";
        }
        $d_header_2         = $this->upload->do_upload('file-header-2');
        if($d_header_2){
            $upload_data_header2    = $this->upload->data();
            $header_2               = 'header_'.$download_title.'_2';
            rename($config['upload_path'].'/'.$upload_data_header2['file_name'],$config['upload_path'].'/'.$header_2.'.'.$upload_data_header2['image_type']);            
            $sHeader2               = $header_2.'.'.$upload_data_header2['image_type'];
            
        }else{
            $sHeader2               = "";
        }  
        $d_header_3         = $this->upload->do_upload('file-header-3');
        if($d_header_3){
            $upload_data_header3    = $this->upload->data();
            $header_3               = 'header_'.$download_title.'_3';
            rename($config['upload_path'].'/'.$upload_data_header3['file_name'],$config['upload_path'].'/'.$header_3.'.'.$upload_data_header3['image_type']);            
            $sHeader3               = $header_3.'.'.$upload_data_header3['image_type'];
            
        }else{
            $sHeader3               = "";
        }
        
        $d_footer_1         = $this->upload->do_upload('file-footer-1');
        if($d_footer_1){
            $upload_data_footer1    = $this->upload->data();
            $footer_1               = 'footer_'.$download_title.'_1';
            rename($config['upload_path'].'/'.$upload_data_footer1['file_name'],$config['upload_path'].'/'.$footer_1.'.'.$upload_data_footer1['image_type']);
            $sFooter1               = $footer_1.'.'.$upload_data_footer1['image_type'];
            
        }else{
            $sFooter1               = "";
        }
        $d_footer_2         = $this->upload->do_upload('file-footer-2');
        if($d_footer_2){
            $upload_data_footer2    = $this->upload->data();
            $footer_2               = 'footer_'.$download_title.'_2';
            rename($config['upload_path'].'/'.$upload_data_footer2['file_name'],$config['upload_path'].'/'.$footer_2.'.'.$upload_data_footer2['image_type']);
            $sFooter2               = $footer_2.'.'.$upload_data_footer2['image_type'];
            
        }else{
            $sFooter2               = "";
        }
        $d_footer_3         = $this->upload->do_upload('file-footer-3');
        if($d_footer_3){
            $upload_data_footer3    = $this->upload->data();
            $footer_3               = 'footer_'.$download_title.'_3';
            rename($config['upload_path'].'/'.$upload_data_footer3['file_name'],$config['upload_path'].'/'.$footer_3.'.'.$upload_data_footer3['image_type']);
            $sFooter3               = $footer_3.'.'.$upload_data_footer3['image_type'];
            
        }else{
            $sFooter3               = "";
        }
        
        $d_promo_1          = $this->upload->do_upload('file-promo-1');
        if($d_promo_1){
            $upload_data_promo1     = $this->upload->data();
            $promo_1                = 'promo_'.$download_title.'_1';
            rename($config['upload_path'].'/'.$upload_data_promo1['file_name'],$config['upload_path'].'/'.$promo_1.'.'.$upload_data_promo1['image_type']);
            $sPromo1                = $promo_1.'.'.$upload_data_promo1['image_type'];
            
        }else{
            $sPromo1               = "";
        }
        $d_promo_2          = $this->upload->do_upload('file-promo-2');
        if($d_promo_2){
            $upload_data_promo2     = $this->upload->data();
            $promo_2                = 'promo_'.$download_title.'_2';
            rename($config['upload_path'].'/'.$upload_data_promo2['file_name'],$config['upload_path'].'/'.$promo_2.'.'.$upload_data_promo2['image_type']);
            $sPromo2                = $promo_2.'.'.$upload_data_promo2['image_type'];
            
        }else{
            $sPromo2               = "";
        }
        $d_promo_3          = $this->upload->do_upload('file-promo-3');
        if($d_promo_3){
            $upload_data_promo3     = $this->upload->data();
            $promo_3                = 'promo_'.$download_title.'_3';
            rename($config['upload_path'].'/'.$upload_data_promo3['file_name'],$config['upload_path'].'/'.$promo_3.'.'.$upload_data_promo3['image_type']);
            $sPromo3                = $promo_3.'.'.$upload_data_promo3['image_type'];
            
        }else{
            $sPromo3               = "";
        }
        
        $d_bg_1             = $this->upload->do_upload('file-background-1');
        if($d_bg_1){
            $upload_data_bg1    = $this->upload->data();
            $bg_1               = 'background_'.$download_title.'_1';
            rename($config['upload_path'].'/'.$upload_data_bg1['file_name'],$config['upload_path'].'/'.$bg_1.'.'.$upload_data_bg1['image_type']);
            $sBg1               = $bg_1.'.'.$upload_data_bg1['image_type'];
            
        }else{
            $sBg1               = "";
        }
        $d_bg_2             = $this->upload->do_upload('file-background-2');
        if($d_bg_2){
            $upload_data_bg2    = $this->upload->data();
            $bg_2               = 'background_'.$download_title.'_2';
            rename($config['upload_path'].'/'.$upload_data_bg2['file_name'],$config['upload_path'].'/'.$bg_2.'.'.$upload_data_bg2['image_type']);
            $sBg2               = $bg_2.'.'.$upload_data_bg2['image_type'];
            
        }else{
            $sBg2               = "";
        }
        $d_bg_3             = $this->upload->do_upload('file-background-3');
        if($d_bg_3){
            $upload_data_bg3    = $this->upload->data();
            $bg_3               = 'background_'.$download_title.'_3';
            rename($config['upload_path'].'/'.$upload_data_bg3['file_name'],$config['upload_path'].'/'.$bg_3.'.'.$upload_data_bg3['image_type']);
            $sBg3               = $bg_3.'.'.$upload_data_bg3['image_type'];
            
        }else{
            $sBg3               = "";
        }
        
        if(!empty($download_name) && !empty($download_title) && !empty($download_disclaim) && !empty($download_desc) && !empty($download_type)){
            if ($this->download_add_model->checkDownloadTitle($download_name, $download_title)){
                return false;    
            }else{
            $result	= $this->download_add_model->saveNewService(
                            $download_name, $download_title, $download_disclaim, $download_desc, $download_type,
                            $sHeader1, $sHeader2, $sHeader3,
                            $sFooter1, $sFooter2, $sFooter3,
                            $sPromo1, $sPromo2, $sPromo3,
                            $sBg1, $sBg2, $sBg3
                          );
            redirect(base_url().'service/download');
            }         
        }   
    }
    
    public function ajaxCheckTitle(){
        $download_name      = $this->input->post('name');
        $download_title     = trim($this->input->post('title'));
        
        $response = array();
        
        if($this->download_add_model->checkDownloadTitle($download_name, $download_title)){
            $response = array(
                        'status' => false,
                        'message' => 'Service title for that service name is already exist, please try another combination'
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
    
    public function getNameList() {
        $nameList = "";

        $nameArr = $this->download_add_model->getNameList('');

        foreach ($nameArr as $dt) {
            $nameList[$dt['id']]['id']   = $dt['id'];                
            $nameList[$dt['id']]['name'] = $dt['name'];                
            $nameList[$dt['id']]['adn']  = $dt['adn'];                
        }

        return $nameList;
    }
    
    public function getTypeList() {        
        $typeList = $this->download_add_model->getTypeList();
        
        return $typeList;
    }
    
}
