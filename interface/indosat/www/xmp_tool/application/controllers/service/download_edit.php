<?php

class Download_edit extends MY_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->output->set_header('Content-Type:text/html; charset=UTF-8');
        $this->load->model('service/download_edit_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());        
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
        $jsFile     = 'service/download_edit.js';
        $this->smarty->assign('jsFile', $jsFile);
        $id         = $this->input->post('id');
        $dataArr    = $this->getDataService($id);
        $nameServiceArr = $this->getNameService($dataArr['service_id']);
        
        $this->smarty->assign('id', $id);
        $this->smarty->assign('nameID', $dataArr['service_id']);
        $this->smarty->assign('nameService', $nameServiceArr['name']);
        $this->smarty->assign('nameServiceADN', $nameServiceArr['adn']);
        $this->smarty->assign('title', $dataArr['title']);
        $this->smarty->assign('disclaimer', $dataArr['disclaimer']);
        $this->smarty->assign('description', $dataArr['description']);
        $this->smarty->assign('type', $dataArr['type']);
        $this->smarty->assign('hHeader1', $dataArr['header1']);
        $this->smarty->assign('hHeader2', $dataArr['header2']);
        $this->smarty->assign('hHeader3', $dataArr['header3']);
        $this->smarty->assign('hFooter1', $dataArr['footer1']);
        $this->smarty->assign('hFooter2', $dataArr['footer2']);
        $this->smarty->assign('hFooter3', $dataArr['footer3']);
        $this->smarty->assign('hPromo1', $dataArr['promo1']);
        $this->smarty->assign('hPromo2', $dataArr['promo2']);
        $this->smarty->assign('hPromo3', $dataArr['promo3']);
        $this->smarty->assign('hBg1', $dataArr['background1']);
        $this->smarty->assign('hBg2', $dataArr['background2']);
        $this->smarty->assign('hBg3', $dataArr['background3']);
        
        $this->smarty->assign('nameList', $this->getNameList());
        $this->smarty->assign('typeList', $this->getTypeList());
        $this->smarty->assign('pageTitle', 'XMP Tools : Manage Edit Download Service');                
        $this->smarty->display('service/download_edit_view.tpl');                
    }
    
    public function ajaxUpdateService(){
        $config=$this->config->item('image');		
        $this->load->library('upload', $config);
        
        $id                 = $this->input->post('id');
        $download_name      = $this->input->post('name-list');
        $download_title     = trim($this->input->post('txt-title'));        
        $download_disclaim  = trim($this->input->post('txt-disclaimer'));
        $download_desc      = trim($this->input->post('txt-description'));
        $download_type      = $this->input->post('type-list');
        $o_download_title   = trim($this->input->post('hidden-title'));
        $o_header_1         = $this->input->post('hidden-header-1');
        $o_header_2         = $this->input->post('hidden-header-2');
        $o_header_3         = $this->input->post('hidden-header-3');
        $o_footer_1         = $this->input->post('hidden-footer-1');
        $o_footer_2         = $this->input->post('hidden-footer-2');
        $o_footer_3         = $this->input->post('hidden-footer-3');
        $o_promo_1          = $this->input->post('hidden-promo-1');
        $o_promo_2          = $this->input->post('hidden-promo-2');
        $o_promo_3          = $this->input->post('hidden-promo-3');
        $o_bg_1             = $this->input->post('hidden-background-1');
        $o_bg_2             = $this->input->post('hidden-background-2');
        $o_bg_3             = $this->input->post('hidden-background-3');
        
        if($download_title!==$o_download_title){
            $d_header_1         = $this->upload->do_upload('file-header-1');
            if($d_header_1){
                $upload_data_header1    = $this->upload->data();
                $header_1               = 'header_'.$download_title.'_1';            
                rename($config['upload_path'].'/'.$upload_data_header1['file_name'],$config['upload_path'].'/'.$header_1.'.'.$upload_data_header1['image_type']);
                $sHeader1               = $header_1.'.'.$upload_data_header1['image_type'];

            }else{
                if(empty($o_header_1)){
                    $sHeader1 = "";
                }else{
                    $getHeaderType1         = explode(".", $o_header_1);

                    $header_1               = 'header_'.$download_title.'_1';
                    rename($config['upload_path'].'/'.$o_header_1,$config['upload_path'].'/'.$header_1.'.'.$getHeaderType1[1]);
                    $sHeader1               = $header_1.'.'.$getHeaderType1[1];            
                }
            }
            $d_header_2         = $this->upload->do_upload('file-header-2');
            if($d_header_2){
                $upload_data_header2    = $this->upload->data();
                $header_2               = 'header_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_header2['file_name'],$config['upload_path'].'/'.$header_2.'.'.$upload_data_header2['image_type']);            
                $sHeader2               = $header_2.'.'.$upload_data_header2['image_type'];

            }else{
                if(empty($o_header_2)){
                    $sHeader2 = "";
                }else{
                    $getHeaderType2         = explode(".", $o_header_2);

                    $header_2               = 'header_'.$download_title.'_2';
                    rename($config['upload_path'].'/'.$o_header_2,$config['upload_path'].'/'.$header_2.'.'.$getHeaderType2[1]);
                    $sHeader2               = $header_2.'.'.$getHeaderType2[1];            
                }
            }  
            $d_header_3         = $this->upload->do_upload('file-header-3');
            if($d_header_3){
                $upload_data_header3    = $this->upload->data();
                $header_3               = 'header_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_header3['file_name'],$config['upload_path'].'/'.$header_3.'.'.$upload_data_header3['image_type']);            
                $sHeader3               = $header_3.'.'.$upload_data_header3['image_type'];

            }else{
                if(empty($o_header_3)){
                    $sHeader3 = "";
                }else{
                    $getHeaderType3         = explode(".", $o_header_3);

                    $header_3               = 'header_'.$download_title.'_3';
                    rename($config['upload_path'].'/'.$o_header_3,$config['upload_path'].'/'.$header_3.'.'.$getHeaderType2[1]);
                    $sHeader3               = $header_3.'.'.$getHeaderType2[1];            
                }
            }

            $d_footer_1         = $this->upload->do_upload('file-footer-1');
            if($d_footer_1){
                $upload_data_footer1    = $this->upload->data();
                $footer_1               = 'footer_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_footer1['file_name'],$config['upload_path'].'/'.$footer_1.'.'.$upload_data_footer1['image_type']);
                $sFooter1               = $footer_1.'.'.$upload_data_footer1['image_type'];

            }else{
                if(empty($o_footer_1)){
                    $sFooter1 = "";
                }else{
                    $getFooterType1         = explode(".", $o_footer_1);

                    $footer_1               = 'footer_'.$download_title.'_1';
                    rename($config['upload_path'].'/'.$o_footer_1,$config['upload_path'].'/'.$footer_1.'.'.$getFooterType1[1]);
                    $sFooter1               = $footer_1.'.'.$getFooterType1[1];            
                }
            }
            $d_footer_2         = $this->upload->do_upload('file-footer-2');
            if($d_footer_2){
                $upload_data_footer2    = $this->upload->data();
                $footer_2               = 'footer_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_footer2['file_name'],$config['upload_path'].'/'.$footer_2.'.'.$upload_data_footer2['image_type']);
                $sFooter2               = $footer_2.'.'.$upload_data_footer2['image_type'];

            }else{
                if(empty($o_footer_2)){
                    $sFooter2 = "";
                }else{
                    $getFooterType2         = explode(".", $o_footer_2);

                    $footer_2               = 'footer_'.$download_title.'_2';
                    rename($config['upload_path'].'/'.$o_footer_2,$config['upload_path'].'/'.$footer_2.'.'.$getFooterType2[1]);
                    $sFooter2               = $footer_2.'.'.$getFooterType2[1];
                }
            }
            $d_footer_3         = $this->upload->do_upload('file-footer-3');
            if($d_footer_3){
                $upload_data_footer3    = $this->upload->data();
                $footer_3               = 'footer_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_footer3['file_name'],$config['upload_path'].'/'.$footer_3.'.'.$upload_data_footer3['image_type']);
                $sFooter3               = $footer_3.'.'.$upload_data_footer3['image_type'];

            }else{
                if(empty($o_footer_3)){
                    $sFooter3 = "";
                }else{
                    $getFooterType3         = explode(".", $o_footer_3);

                    $footer_3               = 'footer_'.$download_title.'_3';
                    rename($config['upload_path'].'/'.$o_footer_3,$config['upload_path'].'/'.$footer_3.'.'.$getFooterType3[1]);
                    $sFooter3               = $footer_3.'.'.$getFooterType3[1];                    
                }
            }

            $d_promo_1          = $this->upload->do_upload('file-promo-1');
            if($d_promo_1){
                $upload_data_promo1     = $this->upload->data();
                $promo_1                = 'promo_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_promo1['file_name'],$config['upload_path'].'/'.$promo_1.'.'.$upload_data_promo1['image_type']);
                $sPromo1                = $promo_1.'.'.$upload_data_promo1['image_type'];

            }else{
                if(empty($o_promo_1)){
                    $sPromo1 = "";
                }else{
                    $getPromoType1         = explode(".", $o_promo_1);

                    $promo_1               = 'promo_'.$download_title.'_1';
                    rename($config['upload_path'].'/'.$o_promo_1,$config['upload_path'].'/'.$promo_1.'.'.$getPromoType1[1]);
                    $sPromo1               = $promo_1.'.'.$getPromoType1[1];
                }
            }
            $d_promo_2          = $this->upload->do_upload('file-promo-2');
            if($d_promo_2){
                $upload_data_promo2     = $this->upload->data();
                $promo_2                = 'promo_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_promo2['file_name'],$config['upload_path'].'/'.$promo_2.'.'.$upload_data_promo2['image_type']);
                $sPromo2                = $promo_2.'.'.$upload_data_promo2['image_type'];

            }else{
                if(empty($o_promo_2)){
                    $sPromo2 = "";
                }else{
                    $getPromoType2         = explode(".", $o_promo_2);

                    $promo_2               = 'promo_'.$download_title.'_2';
                    rename($config['upload_path'].'/'.$o_promo_2,$config['upload_path'].'/'.$promo_2.'.'.$getPromoType2[1]);
                    $sPromo2               = $promo_2.'.'.$getPromoType2[1];
                }
            }
            $d_promo_3          = $this->upload->do_upload('file-promo-3');
            if($d_promo_3){
                $upload_data_promo3     = $this->upload->data();
                $promo_3                = 'promo_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_promo3['file_name'],$config['upload_path'].'/'.$promo_3.'.'.$upload_data_promo3['image_type']);
                $sPromo3                = $promo_3.'.'.$upload_data_promo3['image_type'];

            }else{
                if(empty($o_promo_3)){
                    $sPromo3 = "";
                }else{
                    $getPromoType3         = explode(".", $o_promo_3);

                    $promo_3               = 'promo_'.$download_title.'_3';
                    rename($config['upload_path'].'/'.$o_promo_3,$config['upload_path'].'/'.$promo_3.'.'.$getPromoType3[1]);
                    $sPromo3               = $promo_3.'.'.$getPromoType3[1];
                }
            }

            $d_bg_1             = $this->upload->do_upload('file-background-1');
            if($d_bg_1){
                $upload_data_bg1    = $this->upload->data();
                $bg_1               = 'background_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_bg1['file_name'],$config['upload_path'].'/'.$bg_1.'.'.$upload_data_bg1['image_type']);
                $sBg1               = $bg_1.'.'.$upload_data_bg1['image_type'];

            }else{
                if(empty($o_bg_1)){
                    $sBg1 = "";
                }else{
                    $getBgType1         = explode(".", $o_bg_1);

                    $bg_1               = 'background_'.$download_title.'_1';
                    rename($config['upload_path'].'/'.$o_bg_1,$config['upload_path'].'/'.$bg_1.'.'.$getBgType1[1]);
                    $sBg1               = $bg_1.'.'.$getBgType1[1];
                }
            }
            $d_bg_2             = $this->upload->do_upload('file-background-2');
            if($d_bg_2){
                $upload_data_bg2    = $this->upload->data();
                $bg_2               = 'background_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_bg2['file_name'],$config['upload_path'].'/'.$bg_2.'.'.$upload_data_bg2['image_type']);
                $sBg2               = $bg_2.'.'.$upload_data_bg2['image_type'];

            }else{
                if(empty($o_bg_2)){
                    $sBg2 = "";
                }else{
                    $getBgType2         = explode(".", $o_bg_2);

                    $bg_2               = 'background_'.$download_title.'_2';
                    rename($config['upload_path'].'/'.$o_bg_2,$config['upload_path'].'/'.$bg_2.'.'.$getBgType2[1]);
                    $sBg2               = $bg_2.'.'.$getBgType2[1];
                }
            }
            $d_bg_3             = $this->upload->do_upload('file-background-3');
            if($d_bg_3){
                $upload_data_bg3    = $this->upload->data();
                $bg_3               = 'background_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_bg3['file_name'],$config['upload_path'].'/'.$bg_3.'.'.$upload_data_bg3['image_type']);
                $sBg3               = $bg_3.'.'.$upload_data_bg3['image_type'];

            }else{
                if(empty($o_bg_3)){
                    $sBg3 = "";
                }else{
                    $getBgType3         = explode(".", $o_bg_3);

                    $bg_3               = 'background_'.$download_title.'_3';
                    rename($config['upload_path'].'/'.$o_bg_3,$config['upload_path'].'/'.$bg_3.'.'.$getBgType3[1]);
                    $sBg3               = $bg_3.'.'.$getBgType3[1];
                }
            }
        }else{
            $d_header_1         = $this->upload->do_upload('file-header-1');
            if($d_header_1){
                $upload_data_header1    = $this->upload->data();
                $header_1               = 'header_'.$download_title.'_1';            
                rename($config['upload_path'].'/'.$upload_data_header1['file_name'],$config['upload_path'].'/'.$header_1.'.'.$upload_data_header1['image_type']);
                $sHeader1               = $header_1.'.'.$upload_data_header1['image_type'];

            }else{
                $sHeader1               = $o_header_1;
            }
            $d_header_2         = $this->upload->do_upload('file-header-2');
            if($d_header_2){
                $upload_data_header2    = $this->upload->data();
                $header_2               = 'header_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_header2['file_name'],$config['upload_path'].'/'.$header_2.'.'.$upload_data_header2['image_type']);            
                $sHeader2               = $header_2.'.'.$upload_data_header2['image_type'];

            }else{
                $sHeader2               = $o_header_2;
            }  
            $d_header_3         = $this->upload->do_upload('file-header-3');
            if($d_header_3){
                $upload_data_header3    = $this->upload->data();
                $header_3               = 'header_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_header3['file_name'],$config['upload_path'].'/'.$header_3.'.'.$upload_data_header3['image_type']);            
                $sHeader3               = $header_3.'.'.$upload_data_header3['image_type'];

            }else{
                $sHeader3               = $o_header_3;
            }

            $d_footer_1         = $this->upload->do_upload('file-footer-1');
            if($d_footer_1){
                $upload_data_footer1    = $this->upload->data();
                $footer_1               = 'footer_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_footer1['file_name'],$config['upload_path'].'/'.$footer_1.'.'.$upload_data_footer1['image_type']);
                $sFooter1               = $footer_1.'.'.$upload_data_footer1['image_type'];

            }else{
                $sFooter1               = $o_footer_1;
            }
            $d_footer_2         = $this->upload->do_upload('file-footer-2');
            if($d_footer_2){
                $upload_data_footer2    = $this->upload->data();
                $footer_2               = 'footer_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_footer2['file_name'],$config['upload_path'].'/'.$footer_2.'.'.$upload_data_footer2['image_type']);
                $sFooter2               = $footer_2.'.'.$upload_data_footer2['image_type'];

            }else{
                $sFooter2               = $o_footer_2;
            }
            $d_footer_3         = $this->upload->do_upload('file-footer-3');
            if($d_footer_3){
                $upload_data_footer3    = $this->upload->data();
                $footer_3               = 'footer_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_footer3['file_name'],$config['upload_path'].'/'.$footer_3.'.'.$upload_data_footer3['image_type']);
                $sFooter3               = $footer_3.'.'.$upload_data_footer3['image_type'];

            }else{
                $sFooter3               = $o_footer_3;
            }

            $d_promo_1          = $this->upload->do_upload('file-promo-1');
            if($d_promo_1){
                $upload_data_promo1     = $this->upload->data();
                $promo_1                = 'promo_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_promo1['file_name'],$config['upload_path'].'/'.$promo_1.'.'.$upload_data_promo1['image_type']);
                $sPromo1                = $promo_1.'.'.$upload_data_promo1['image_type'];

            }else{
                $sPromo1               = $o_promo_1;
            }
            $d_promo_2          = $this->upload->do_upload('file-promo-2');
            if($d_promo_2){
                $upload_data_promo2     = $this->upload->data();
                $promo_2                = 'promo_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_promo2['file_name'],$config['upload_path'].'/'.$promo_2.'.'.$upload_data_promo2['image_type']);
                $sPromo2                = $promo_2.'.'.$upload_data_promo2['image_type'];

            }else{
                $sPromo2               = $o_promo_2;
            }
            $d_promo_3          = $this->upload->do_upload('file-promo-3');
            if($d_promo_3){
                $upload_data_promo3     = $this->upload->data();
                $promo_3                = 'promo_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_promo3['file_name'],$config['upload_path'].'/'.$promo_3.'.'.$upload_data_promo3['image_type']);
                $sPromo3                = $promo_3.'.'.$upload_data_promo3['image_type'];

            }else{
                $sPromo3               = $o_promo_3;
            }

            $d_bg_1             = $this->upload->do_upload('file-background-1');
            if($d_bg_1){
                $upload_data_bg1    = $this->upload->data();
                $bg_1               = 'background_'.$download_title.'_1';
                rename($config['upload_path'].'/'.$upload_data_bg1['file_name'],$config['upload_path'].'/'.$bg_1.'.'.$upload_data_bg1['image_type']);
                $sBg1               = $bg_1.'.'.$upload_data_bg1['image_type'];

            }else{
                $sBg1               = $o_bg_1;
            }
            $d_bg_2             = $this->upload->do_upload('file-background-2');
            if($d_bg_2){
                $upload_data_bg2    = $this->upload->data();
                $bg_2               = 'background_'.$download_title.'_2';
                rename($config['upload_path'].'/'.$upload_data_bg2['file_name'],$config['upload_path'].'/'.$bg_2.'.'.$upload_data_bg2['image_type']);
                $sBg2               = $bg_2.'.'.$upload_data_bg2['image_type'];

            }else{
                $sBg2               = $o_bg_2;
            }
            $d_bg_3             = $this->upload->do_upload('file-background-3');
            if($d_bg_3){
                $upload_data_bg3    = $this->upload->data();
                $bg_3               = 'background_'.$download_title.'_3';
                rename($config['upload_path'].'/'.$upload_data_bg3['file_name'],$config['upload_path'].'/'.$bg_3.'.'.$upload_data_bg3['image_type']);
                $sBg3               = $bg_3.'.'.$upload_data_bg3['image_type'];

            }else{
                $sBg3               = $o_bg_3;
            }
            
        }
        
        if(!empty($download_name) && !empty($download_title) && !empty($download_disclaim) && !empty($download_desc) && !empty($download_type)){
            $result	= $this->download_edit_model->updateService(
                            $id, $download_name, $download_title, $download_disclaim, $download_desc, $download_type,
                            $sHeader1, $sHeader2, $sHeader3,
                            $sFooter1, $sFooter2, $sFooter3,
                            $sPromo1, $sPromo2, $sPromo3,
                            $sBg1, $sBg2, $sBg3
                          );
            redirect(base_url().'service/download');       
        }   
    }
    
    public function getDataService($id){
        $dataService = "";
        
        $dataServiceArr = $this->download_edit_model->getDataService($id);
        
        foreach ($dataServiceArr as $dt) {
            $dataService['id']           = $dt['id'];                
            $dataService['service_id']   = $dt['service_id'];                
            $dataService['title']        = $dt['title'];
            $dataService['type']         = $dt['type'];
            $dataService['disclaimer']   = $dt['disclaimer'];
            $dataService['description']  = $dt['description'];
            $dataService['header1']      = $dt['header1'];
            $dataService['header2']      = $dt['header2'];
            $dataService['header3']      = $dt['header3'];
            $dataService['footer1']      = $dt['footer1'];
            $dataService['footer2']      = $dt['footer2'];
            $dataService['footer3']      = $dt['footer3'];
            $dataService['promo1']       = $dt['promo1'];
            $dataService['promo2']       = $dt['promo2'];
            $dataService['promo3']       = $dt['promo3'];
            $dataService['background1']  = $dt['background1'];
            $dataService['background2']  = $dt['background2'];
            $dataService['background3']  = $dt['background3'];
        }
        
        return $dataService;
    }
    
    public function getNameService($idService){
        $nameService = "";

        $nameServiceArr = $this->download_edit_model->getNameService($idService);

        foreach ($nameServiceArr as $dt) {
            $nameService['id']   = $dt['id'];                
            $nameService['name'] = $dt['name'];                
            $nameService['adn']  = $dt['adn'];                
        }

        return $nameService;
    }
    
     public function ajaxCheckTitle(){
        $download_name      = $this->input->post('name');
        $download_title     = trim($this->input->post('title'));
        $old_d_name         = trim($this->input->post('old-title'));

        $response = array();       
        
        if($this->download_edit_model->checkDownloadTitle($download_name, $download_title)){
            if ($download_title == $old_d_name) {
                $response = array(
                    'status' => true,
                    'message' => ''
                );
            }else{
                $response = array(
                    'status' => false,
                    'message' => 'Service title for that service name is already exist, please try another combination'
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
    
    public function getNameList() {
        $nameList = "";

        $nameArr = $this->download_edit_model->getNameList();

        foreach ($nameArr as $dt) {
            $nameList[$dt['id']]['id']   = $dt['id'];                
            $nameList[$dt['id']]['name'] = $dt['name'];                
            $nameList[$dt['id']]['adn']  = $dt['adn'];                
        }

        return $nameList;
    }
    
    public function getTypeList() {        
        $typeList = $this->download_edit_model->getTypeList();
        
        return $typeList;
    }
}
