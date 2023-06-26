<?php
/**
 *
 * wap/service controller
 *
 * @package		Waptool Creation
 * @since		September 29 2011
 * @author		Indra (LinkIT Dev Team)
 *
 */

class Subscription extends CI_Controller {
	public $limit = 0;
        

    public function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata('wap_username'))
        redirect(base_url() . 'login');
		$this->klogger->log("");
        $this->load->model(array ('navigation_model', 'subscription_model', 'service_model', 'adn_model','ak_model'));

        $this->mysmarty->assign('navigation',   $this->navigation_model->getMenuHtml());
        $this->mysmarty->assign('wap_username', $this->session->userdata('wap_username'));
        $this->mysmarty->assign('base_url',     base_url());        

		$this->limit = $this->config->item('limit');
	       
    }
	
	function saveNewSubscription(){
		$url_image_wapsub = $this->config->item('url_image_wapsub');
		$path_image_wapsub = $this->config->item('path_image_wapsub');
		$path_image_tmp = $this->config->item('path_image_tmp');
		$data['wap_name']          		= $this->input->post("wap_name");
		$data['wap_service']       		= $this->input->post("service");
		$data['wap_title']         		= $this->input->post("wap_title");
		$data['ak_schedule']       		= $this->input->post("ak_schedule");
        $data['ak_timer']          		= $this->input->post("ak_timer");
		$data['always_enable']			= $this->input->post("always_enable");
		$data['catalogb_image']			= $this->input->post("catalogb_image");
		$data['header_image']			= $this->input->post("header_image");
		$data['header_background']		= $this->input->post("header_background");
		$data['footer_image']			= $this->input->post("footer_image");
		$data['footer_background']		= $this->input->post("footer_background");
		$data['clickable_header']		= $this->input->post("clickable_header");
		$data['clickable_header_text']	= $this->input->post("clickable_header_text");
		$data['clickable_footer']		= $this->input->post("clickable_footer");
		$data['clickable_footer_text']	= $this->input->post("clickable_footer_text");
		$data['thankyou_page_auto_redirect']= $this->input->post("thankyou_page_auto_redirect");
		$data['thankyou_page_time']		= $this->input->post("thankyou_page_time");
		$data['thankyou_page_url']		= $this->input->post("thankyou_page_url");
		$data['confirmation_page_enable']= $this->input->post("confirmation_page_enable");
		$data['wap_setting_msisdn']		= $this->input->post("wap_setting_msisdn");
		$data['wap_setting_javaapps']	= $this->input->post("wap_setting_javaapps");
		$data['bg_color']				= $this->input->post('bg_color');
		$data['bg_picture']				= $this->input->post('bg_picture');
		if($data['catalogb_image']!=''){
			$info=pathinfo($data['catalogb_image']);
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".png")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".gif")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."catalogb_".$data['catalogb_image'], $path_image_wapsub."catalogb_".$data['wap_name'].".".strtolower($info['extension']));
		}
		if($data['bg_picture']!=''){
			$info=pathinfo($data['bg_picture']);
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".png")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".gif")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."bg_picture_".$data['bg_picture'], $path_image_wapsub."bg_picture_".$data['wap_name'].".".strtolower($info['extension']));
		}
		if($data['header_image']!=''){
			$info=pathinfo($data['header_image']);
			if(touch($path_image_wapsub."header_".$data['wap_name'].".png")) unlink($path_image_wapsub."header_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".gif")) unlink($path_image_wapsub."header_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."header_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."header_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."header_".$data['header_image'], $path_image_wapsub."header_".$data['wap_name'].".".strtolower($info['extension']));
		}
		if($data['header_background']!=''){
			$info=pathinfo($data['header_background']);
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".png")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".gif")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."header-background_".$data['header_background'], $path_image_wapsub."header_bg_".$data['wap_name'].".".strtolower($info['extension']));
		}
		if($data['footer_image']!=''){
			$info=pathinfo($data['footer_image']);
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".png")) unlink($path_image_wapsub."footer_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".gif")) unlink($path_image_wapsub."footer_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."footer_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."footer_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."footer_".$data['footer_image'], $path_image_wapsub."footer_".$data['wap_name'].".".$info['extension']);
		}
		if($data['footer_background']!=''){
			$info=pathinfo($data['footer_background']);
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".png")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".gif")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."footer-background_".$data['footer_background'], $path_image_wapsub."footer_bg_".$data['wap_name'].".".strtolower($info['extension']));
		}
		$data['comp_land'] = array();
		for($i=0;$i<count($_POST['landing_page_name']);$i++){
			$data['comp_land'][$i]['landing_page_name']=$_POST['landing_page_name'][$i];
			$data['comp_land'][$i]['landing_page_type']=$_POST['landing_page_type'][$i];
			$data['comp_land'][$i]['landing_page_value']=$_POST['landing_page_value'][$i];
			$data['comp_land'][$i]['landing_page_clickable']=$_POST['landing_page_clickable'][$i];
			$data['comp_land'][$i]['landing_page_msgtext']=$_POST['landing_page_msgtext'][$i];
			$data['comp_land'][$i]['landing_page_image']=$_POST['landing_page_image'][$i];
			$data['comp_land'][$i]['landing_page_id']=$_POST['landing_page_id'][$i];
			if($data['comp_land'][$i]['landing_page_image']!=''){
				@copy($path_image_tmp."landing_".$data['comp_land'][$i]['landing_page_image'], $path_image_wapsub."landing_".$data['wap_name']."_".$data['comp_land'][$i]['landing_page_image']);
			}
			$data['comp_land'][$i]['landing_page_id']=$_POST['landing_page_id'][$i];
		}
		$landing_serialize = $this->input->post('landing_serialize');
		$ar_ls = explode("&",$landing_serialize);
		for($i=0;$i<count($ar_ls);$i++){
			$ar_ls_dl = explode("=",$ar_ls[$i]);
			$id=$ar_ls_dl[1];
			for($j=0;$j<count($data['comp_land']);$j++){
				if($data['comp_land'][$j]['landing_page_id']==$id){
					$data['comp_land'][$j]['landing_page_sort']=$i;
				}
			}
		}
		$data['comp_conf'] = array();
		for($i=0;$i<count($_POST['confirmation_page_name']);$i++){
			$data['comp_conf'][$i]['confirmation_page_name']=$_POST['confirmation_page_name'][$i];
			$data['comp_conf'][$i]['confirmation_page_type']=$_POST['confirmation_page_type'][$i];
			$data['comp_conf'][$i]['confirmation_page_value']=$_POST['confirmation_page_value'][$i];
			$data['comp_conf'][$i]['confirmation_page_clickable']=$_POST['confirmation_page_clickable'][$i];
			$data['comp_conf'][$i]['confirmation_page_image']=$_POST['confirmation_page_image'][$i];
			$data['comp_conf'][$i]['confirmation_page_id']=$_POST['confirmation_page_id'][$i];
			if($data['comp_conf'][$i]['confirmation_page_image']!=''){
				@copy($path_image_tmp."confirmation_".$data['comp_conf'][$i]['confirmation_page_image'], $path_image_wapsub."confirmation_".$data['wap_name']."_".$data['comp_conf'][$i]['confirmation_page_image']);
			}
			$data['comp_conf'][$i]['confirmation_page_sort']=$_POST['confirmation_page_sort'][$i];
		}
		$confirmation_serialize = $this->input->post('confirmation_serialize');
		$ar_cs = explode("&",$confirmation_serialize);
		for($i=0;$i<count($ar_cs);$i++){
			$ar_cs_dl = explode("=",$ar_cs[$i]);
			$id=$ar_cs_dl[1];
			for($j=0;$j<count($data['comp_conf']);$j++){
				if($data['comp_conf'][$j]['confirmation_page_id']==$id){
					$data['comp_conf'][$j]['confirmation_page_sort']=$i;
				}
			}
		}
		$data['comp_tq'] = array();
		for($i=0;$i<count($_POST['thankyou_page_name']);$i++){
			$data['comp_tq'][$i]['thankyou_page_name']=$_POST['thankyou_page_name'][$i];
			$data['comp_tq'][$i]['thankyou_page_type']=$_POST['thankyou_page_type'][$i];
			$data['comp_tq'][$i]['thankyou_page_value']=$_POST['thankyou_page_value'][$i];
			$data['comp_tq'][$i]['thankyou_page_clickable']=$_POST['thankyou_page_clickable'][$i];
			$data['comp_tq'][$i]['thankyou_page_image']=$_POST['thankyou_page_image'][$i];
			$data['comp_tq'][$i]['thankyou_page_id']=$_POST['thankyou_page_id'][$i];
			if($data['comp_tq'][$i]['thankyou_page_image']!=''){
				@copy($path_image_tmp."thankyou_".$data['comp_tq'][$i]['thankyou_page_image'], $path_image_wapsub."thankyou_".$data['wap_name']."_".$data['comp_tq'][$i]['thankyou_page_image']);
			}
			$data['comp_tq'][$i]['thankyou_page_sort']=$_POST['thankyou_page_sort'][$i];
		}
		$thankyou_serialize = $this->input->post('thankyou_serialize');
		$ar_ts = explode("&",$thankyou_serialize);
		for($i=0;$i<count($ar_ts);$i++){
			$ar_ts_dl = explode("=",$ar_ts[$i]);
			$id=$ar_ts_dl[1];
			for($j=0;$j<count($data['comp_tq']);$j++){
				if($data['comp_tq'][$j]['thankyou_page_id']==$id){
					$data['comp_tq'][$j]['thankyou_page_sort']=$i;
				}
			}
		}
		$data['comp_info'] = array();
		for($i=0;$i<count($_POST['info_page_name']);$i++){
			$data['comp_info'][$i]['info_page_name']=$_POST['info_page_name'][$i];
			$data['comp_info'][$i]['info_page_type']=$_POST['info_page_type'][$i];
			$data['comp_info'][$i]['info_page_value']=$_POST['info_page_value'][$i];
			$data['comp_info'][$i]['info_page_clickable']=$_POST['info_page_clickable'][$i];
			$data['comp_info'][$i]['info_page_image']=$_POST['info_page_image'][$i];
			$data['comp_info'][$i]['info_page_id']=$_POST['info_page_id'][$i];
			if($data['comp_info'][$i]['info_page_image']!=''){
				@copy($path_image_tmp."info_".$data['comp_info'][$i]['info_page_image'], $path_image_wapsub."info_".$data['wap_name']."_".$data['comp_info'][$i]['info_page_image']);
			}
			$data['comp_info'][$i]['info_page_sort']=$_POST['info_page_sort'][$i];
		}
		$info_serialize = $this->input->post('info_serialize');
		$ar_is = explode("&",$info_serialize);
		for($i=0;$i<count($ar_is);$i++){
			$ar_is_dl = explode("=",$ar_is[$i]);
			$id=$ar_is_dl[1];
			for($j=0;$j<count($data['comp_info']);$j++){
				if($data['comp_info'][$j]['info_page_id']==$id){
					$data['comp_info'][$j]['info_page_sort']=$i;
				}
			}
		}
		$this->subscription_model->create_new_wapreg($data);
	}
	
	function saveEditSubscription(){
		$url_image_wapsub = $this->config->item('url_image_wapsub');
		$path_image_wapsub = $this->config->item('path_image_wapsub');
		$path_image_tmp = $this->config->item('path_image_tmp');
		$data['id']						= $this->input->post("id");
		$data['wap_name']          		= $this->input->post("wap_name");
		$data['wap_name_old']			= $this->input->post("wap_name_old");
        $data['wap_service']       		= $this->input->post("service");
		$data['wap_service_old']       	= $this->input->post("service_old");
        $data['wap_title']         		= $this->input->post("wap_title");
		$data['ak_schedule']       		= $this->input->post("ak_schedule");
        $data['ak_timer']          		= $this->input->post("ak_timer");
		$data['always_enable']			= $this->input->post("always_enable");
		$data['catalogb_image']			= $this->input->post("catalogb_image");
		$data['catalogb_ext']				= $this->input->post("catalogb_ext");
		$data['header_image']			= $this->input->post("header_image");
		$data['header_ext']				= $this->input->post("header_ext");
		$data['header_background']		= $this->input->post("header_background");
		$data['header_background_ext']	= $this->input->post("header_background_ext");
		$data['footer_image']			= $this->input->post("footer_image");
		$data['footer_ext']				= $this->input->post("footer_ext");
		$data['footer_background']		= $this->input->post("footer_background");
		$data['footer_background_ext']	= $this->input->post("footer_background_ext");
		$data['clickable_header']		= $this->input->post("clickable_header");
		$data['clickable_header_text']	= $this->input->post("clickable_header_text");
		$data['clickable_footer']		= $this->input->post("clickable_footer");
		$data['clickable_footer_text']	= $this->input->post("clickable_footer_text");
		$data['thankyou_page_auto_redirect']= $this->input->post("thankyou_page_auto_redirect");
		$data['thankyou_page_time']		= $this->input->post("thankyou_page_time");
		$data['thankyou_page_url']		= $this->input->post("thankyou_page_url");
		$data['confirmation_page_enable']= $this->input->post("confirmation_page_enable");
		$data['wap_setting_msisdn']		= $this->input->post("wap_setting_msisdn");
		$data['wap_setting_javaapps']	= $this->input->post("wap_setting_javaapps");
		$data['bg_color']				= $this->input->post('bg_color');
		$data['bg_picture']				= $this->input->post('bg_picture');
		if($data['header_image']!=''){
			$info=pathinfo($data['header_image']);
			if(touch($path_image_wapsub."header_".$data['wap_name'].".png")) unlink($path_image_wapsub."header_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".gif")) unlink($path_image_wapsub."header_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."header_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."header_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."header_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."header_".$data['header_image'], $path_image_wapsub."header_".$data['wap_name'].".".$info['extension']);
		}else{
			@copy(
				$path_image_wapsub."header_".$data['wap_name_old'].".".$data['header_ext'],
				$path_image_wapsub."header_".$data['wap_name'].".".$data['header_ext']
			);
		}
		if($data['bg_picture']!=''){
			$info=pathinfo($data['bg_picture']);
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".png")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".gif")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."bg_picture_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."bg_picture_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."bg_picture_".$data['bg_picture'], $path_image_wapsub."bg_picture_".$data['wap_name'].".".strtolower($info['extension']));
		}
		if($data['catalogb_image']!=''){
			$info=pathinfo($data['catalogb_image']);
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".png")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".gif")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."catalogb_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."catalogb_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."catalogb_".$data['catalogb_image'], $path_image_wapsub."catalogb_".$data['wap_name'].".".$info['extension']);
		}else{
			@copy(
				$path_image_wapsub."catalogb_".$data['wap_name_old'].".".$data['catalogb_ext'],
				$path_image_wapsub."catalogb_".$data['wap_name'].".".$data['catalogb_ext']
			);
		}
		if($data['header_background']!=''){
			$info=pathinfo($data['header_background']);
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".png")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".gif")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."header-background_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."header-background_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."header-background_".$data['header_background'], $path_image_wapsub."header_bg_".$data['wap_name'].".".$info['extension']);
		}else{
			@copy(
				$path_image_wapsub."header_bg_".$data['wap_name_old'].".".$data['header_background_ext'],
				$path_image_wapsub."header_bg_".$data['wap_name'].".".$data['header_background_ext']
			);
		}
		if($data['footer_image']!=''){
			$info=pathinfo($data['footer_image']);
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".png")) unlink($path_image_wapsub."footer_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".gif")) unlink($path_image_wapsub."footer_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."footer_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."footer_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."footer_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."footer_".$data['footer_image'], $path_image_wapsub."footer_".$data['wap_name'].".".$info['extension']);
		}else{
			@copy(
				$path_image_wapsub."footer_".$data['wap_name_old'].".".$data['footer_ext'],
				$path_image_wapsub."footer_".$data['wap_name'].".".$data['footer_ext']
			);
		}
		if($data['footer_background']!=''){
			$info=pathinfo($data['footer_background']);
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".png")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".png");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".gif")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".gif");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".jpg")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".jpg");
			if(touch($path_image_wapsub."footer-background_".$data['wap_name'].".jpeg")) unlink($path_image_wapsub."footer-background_".$data['wap_name'].".jepg");
			@copy($path_image_tmp."footer-background_".$data['footer_background'], $path_image_wapsub."footer_bg_".$data['wap_name'].".".$info['extension']);
		}else{
			@copy(
				$path_image_wapsub."footer_bg_".$data['wap_name_old'].".".$data['footer_background_ext'],
				$path_image_wapsub."footer_bg_".$data['wap_name'].".".$data['footer_background_ext']
			);
		}        
		$data['comp_land'] = array();
		for($i=0;$i<count($_POST['landing_page_name']);$i++){
			$data['comp_land'][$i]['landing_page_name']=$_POST['landing_page_name'][$i];
			$data['comp_land'][$i]['landing_page_type']=$_POST['landing_page_type'][$i];
			$data['comp_land'][$i]['landing_page_value']=$_POST['landing_page_value'][$i];
			$data['comp_land'][$i]['landing_page_clickable']=$_POST['landing_page_clickable'][$i];
			$data['comp_land'][$i]['landing_page_msgtext']=$_POST['landing_page_msgtext'][$i];
			$data['comp_land'][$i]['landing_page_image']=$_POST['landing_page_image'][$i];
			$data['comp_land'][$i]['landing_page_id']=$_POST['landing_page_id'][$i];
			$data['comp_land'][$i]['landing_page_sort']=$_POST['landing_page_sort'][$i];
			if($data['comp_land'][$i]['landing_page_image'] != ''){
				@copy($path_image_tmp."landing_".$data['comp_land'][$i]['landing_page_image'], $path_image_wapsub."landing_".$data['wap_name']."_".$data['comp_land'][$i]['landing_page_image']);
				$data['comp_land'][$i]['landing_page_image'] = "landing_".$data['wap_name']."_".$data['comp_land'][$i]['landing_page_image'];
			}else{
				if(isset($_POST['landing_page_image_old'][$i])){
					$data['comp_land'][$i]['landing_page_image'] = $_POST['landing_page_image_old'][$i];
				}
			}
		}
		$landing_serialize = $this->input->post('landing_serialize');
		$ar_ls = explode("&",$landing_serialize);
		for($i=0;$i<count($ar_ls);$i++){
			$ar_ls_dl = explode("=",$ar_ls[$i]);
			$id=$ar_ls_dl[1];
			for($j=0;$j<count($data['comp_land']);$j++){
				if($data['comp_land'][$j]['landing_page_id']==$id){
					$data['comp_land'][$j]['landing_page_sort']=$i;
				}
			}
		}
		$data['comp_conf'] = array();
		for($i=0;$i<count($_POST['confirmation_page_name']);$i++){
			$data['comp_conf'][$i]['confirmation_page_name']=$_POST['confirmation_page_name'][$i];
			$data['comp_conf'][$i]['confirmation_page_type']=$_POST['confirmation_page_type'][$i];
			$data['comp_conf'][$i]['confirmation_page_value']=$_POST['confirmation_page_value'][$i];
			$data['comp_conf'][$i]['confirmation_page_clickable']=$_POST['confirmation_page_clickable'][$i];
			$data['comp_conf'][$i]['confirmation_page_image']=$_POST['confirmation_page_image'][$i];
			if($data['comp_conf'][$i]['confirmation_page_image']!=''){
                                @copy($path_image_tmp."confirmation_".$data['comp_conf'][$i]['confirmation_page_image'], $path_image_wapsub."confirmation_".$data['wap_name']."_".$data['comp_conf'][$i]['confirmation_page_image']);

                               $data['comp_conf'][$i]['confirmation_page_image'] = "confirmation_".$data['wap_name']."_".$data['comp_conf'][$i]['confirmation_page_image'];
			}else{
				if(isset($_POST['confirmation_page_image_old'][$i])){
					$data['comp_conf'][$i]['confirmation_page_image']=$_POST['confirmation_page_image_old'][$i];
				}
			}
			$data['comp_conf'][$i]['confirmation_page_id']=$_POST['confirmation_page_id'][$i];
			$data['comp_conf'][$i]['confirmation_page_sort']=$_POST['confirmation_page_sort'][$i];
		}
		
		$confirmation_serialize = $this->input->post('confirmation_serialize');
		$ar_cs = explode("&",$confirmation_serialize);
		for($i=0;$i<count($ar_cs);$i++){
			$ar_cs_dl = explode("=",$ar_cs[$i]);
			$id=$ar_cs_dl[1];
			for($j=0;$j<count($data['comp_conf']);$j++){
				if($data['comp_conf'][$j]['confirmation_page_id']==$id){
					$data['comp_conf'][$j]['confirmation_page_sort']=$i;
				}
			}
		}
		
		$data['comp_tq'] = array();
		for($i=0;$i<count($_POST['thankyou_page_name']);$i++){
			$data['comp_tq'][$i]['thankyou_page_name']=$_POST['thankyou_page_name'][$i];
			$data['comp_tq'][$i]['thankyou_page_type']=$_POST['thankyou_page_type'][$i];
			$data['comp_tq'][$i]['thankyou_page_value']=$_POST['thankyou_page_value'][$i];
			$data['comp_tq'][$i]['thankyou_page_clickable']=$_POST['thankyou_page_clickable'][$i];
			$data['comp_tq'][$i]['thankyou_page_image']=$_POST['thankyou_page_image'][$i];
			if($data['comp_tq'][$i]['thankyou_page_image']!=''){
                            @copy($path_image_tmp."thankyou_".$data['comp_tq'][$i]['thankyou_page_image'], $path_image_wapsub."thankyou_".$data['wap_name']."_".$data['comp_tq'][$i]['thankyou_page_image']);

                                $data['comp_tq'][$i]['thankyou_page_image'] = "thankyou_".$data['wap_name']."_".$data['comp_tq'][$i]['thankyou_page_image'];
			}else{
				if(isset($_POST['thankyou_page_image_old'][$i])){
					$data['comp_tq'][$i]['thankyou_page_image']=$_POST['thankyou_page_image_old'][$i];
				}
			}
			$data['comp_tq'][$i]['thankyou_page_id']=$_POST['thankyou_page_id'][$i];
			$data['comp_tq'][$i]['thankyou_page_sort']=$_POST['thankyou_page_sort'][$i];
		}
		
		$thankyou_serialize = $this->input->post('thankyou_serialize');
		$ar_ts = explode("&",$thankyou_serialize);
		for($i=0;$i<count($ar_ts);$i++){
			$ar_ts_dl = explode("=",$ar_ts[$i]);
			$id=$ar_ts_dl[1];
			for($j=0;$j<count($data['comp_tq']);$j++){
				if($data['comp_tq'][$j]['thankyou_page_id']==$id){
					$data['comp_tq'][$j]['thankyou_page_sort']=$i;
				}
			}
		}
		$data['comp_info'] = array();
		for($i=0;$i<count($_POST['info_page_name']);$i++){
			$data['comp_info'][$i]['info_page_name']=$_POST['info_page_name'][$i];
			$data['comp_info'][$i]['info_page_type']=$_POST['info_page_type'][$i];
			$data['comp_info'][$i]['info_page_value']=$_POST['info_page_value'][$i];
			$data['comp_info'][$i]['info_page_clickable']=$_POST['info_page_clickable'][$i];
			$data['comp_info'][$i]['info_page_image']=$_POST['info_page_image'][$i];
			if($data['comp_info'][$i]['info_page_image']!=''){
                            @copy($path_image_tmp."info_".$data['comp_info'][$i]['info_page_image'], $path_image_wapsub."info_".$data['wap_name']."_".$data['comp_info'][$i]['info_page_image']);

                               $data['comp_info'][$i]['info_page_image'] = "info_".$data['wap_name']."_".$data['comp_info'][$i]['info_page_image'];
			}else{
				if(isset($_POST['info_page_image_old'][$i])){
					$data['comp_info'][$i]['info_page_image']=$_POST['info_page_image_old'][$i];
				}
			}
			$data['comp_info'][$i]['info_page_id']=$_POST['info_page_id'][$i];
			$data['comp_info'][$i]['info_page_sort']=$_POST['info_page_sort'][$i];
		}
		$info_serialize = $this->input->post('info_serialize');
		$ar_is = explode("&",$info_serialize);
		for($i=0;$i<count($ar_is);$i++){
			$ar_is_dl = explode("=",$ar_is[$i]);
			$id=$ar_is_dl[1];
			for($j=0;$j<count($data['comp_info']);$j++){
				if($data['comp_info'][$j]['info_page_id']==$id){
					$data['comp_info'][$j]['info_page_sort']=$i;
				}
			}
		}

		//error_log(print_r($data['comp_land'],1));
		$this->subscription_model->update_new_wapreg($data);
	}

    public function ajaxRemoveComponent() {
	//$this->klogger->log("");
        $id = $this->input->post("id");

        if (empty($id)) redirect(base_url(). 'wap/subscription');

        //$this->load->model(array('wapreg','wapreg_component'));
        $response = $this->subscription_model->RemoveComponent($id);
       // $this->subscription_model->unactivate_wapreg_params_by_wapregid($id);
        //$this->wapreg_component->unactivate_component_by_wapid($id);

        echo json_encode($response);
        exit;
    }


    function index() {
		$this->klogger->log("");
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->view('wap/subscription_view.html');
    }
    
    function add(){
		$this->klogger->log("");
        $ak_list	= $this->ak_model->read_all();
        $type_array	= array('yes'=>'Activate', 'no'=>'Not Activate');
		$type_component = array(
			 'image',
			 'text',
			 'title',
			 'space',
			 'url',
			 'catalog-a',
			 'catalog-b',
			 'ivr'
		);
		$type_link = array(
			 'y',
			 'n' 
		);
        $this->mysmarty->assign('ak', $ak_list);
        $this->mysmarty->assign('type_array', $type_array);
        $this->mysmarty->assign('base_url', base_url());
		$this->mysmarty->assign('token',uniqid());
		$this->mysmarty->assign( 'type_component', $type_component );
		$this->mysmarty->assign( 'type_link', $type_link );
        $this->mysmarty->view('wap/subscription_add_view.html');
    }
    
    public function ajaxGetSubscriptionList() {
		$this->klogger->log("");
		$search = strtoupper($this->input->post("search"));
        $page   = $this->uri->segment(4);
        $offset = (isset ($page)) ? (int) $page : 0;
        $limit  = $this->limit;
        $paging = "";
        $result = "";
       
        
        $mData  = $this->subscription_model->getSubscriptionList($offset, $limit, $search);
        $total  = $mData['total'];
        $data   = $mData['result']['data'];
        $dTotal = $mData['result']['total'];
        
        if ($total > 0) {
            foreach ($data as $key => $dt) {
                $id             = $dt['id'];
                $wap_name       = $dt['wap_name'];
                $wap_service    = $dt['service'];                
                $wap_title      = $dt['wap_title'];
                $autoreg        = $dt['autoreg'];
                $conf_page      = $dt['confirmation_page_enable'];
                $url_edit       = base_url().'wap/subscription/EditSubscription/'.$id;
                $url_layout       = base_url().'wap/wapreg_layout/index/'.$id;
                //$datecreated    = $dt['datecreated'];
    
                $result .= "<tr>";
                $result .= "<td>$wap_name</td>";
                $result .= "<td>$wap_service</td>";
                $result .= "<td>$wap_title</td>";
                $result .= "<td>$autoreg</td>";
                $result .= "<td>$conf_page</td>";
                $result .= "<td><div class=\"menulink\"><a href=\"$url_edit\">Edit</a> <a onclick=\"deleteSubscription($id);\">Delete</a></div></td>";
                $result .= "</tr>";
            }

            if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url']    = base_url() . "wap/subscription/ajaxGetSubscriptionList/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows']  = $total;
                $pagination['per_page']    = $limit;

                $this->pagination->initialize($pagination);
                $paging = $this->pagination->create_links();
            }
            else {
                $paging = "<b>1</b>";
            }
        }
        else {
            $result .= "<tr><td colspan=\"6\">No data found</td></tr>";
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
    
    public function getWapServiceList($service){
		$this->klogger->log("");
        if($service != ""){
            $result = "";
            $result .="<span>";
            $result .="<select name=\"txt-wap-service\" id=\"txt-wap-service\">";
            foreach ($this->service_model->readWapService() as $_data)
            {
                $id             = $_data['id'];
                $wap_service    = $_data['name'];
                if($wap_service == $service){
                    $result .="<option value=\"$wap_service\" selected=\"selected\">$wap_service</option>";
                }else{
                    $result .="<option value=\"$wap_service\">$wap_service</option>";
                }
                
            }   
            $result .="</select>";
            $result .="</span>";
        }else{
            $result = "";
            $result .="<span>";
            $result .="<select name=\"txt-wap-service\" id=\"txt-wap-service\">";
            foreach ($this->service_model->readWapService() as $_data)
            {
                $id             = $_data['id'];
                $wap_service    = $_data['name'];    
                $result .="<option value=\"$wap_service\">$wap_service</option>";
            }   
            $result .="</select>";
            $result .="</span>";
        }
        return $result;
        
    }
    
    public function getAdnList(){
		$this->klogger->log("");
        $result = "";
        $result .="<span>";
        $result .="<select name=\"txt-adn\" id=\"txt-adn\">";
        foreach ($this->service_model->readAdn() as $__data)
        {
            $id         = $__data['id'];
            $adn        = $__data['name'];    
            $result    .="<option value=\"$adn\">$adn</option>";
        }   
        $result .="</select>";
        $result .="</span>";
        
        return $result;
    }  
    
    public function AddNewSubscription() {
        $this->klogger->log("");
        if ($this->input->post('submit') == TRUE) {
        $wap_name           = $this->input->post("txt-wap-name");
        $wap_service        = $this->input->post("txt-wap-service");
        $wap_title          = $this->input->post("txt-wap-title");
        $auto_reg           = $this->input->post("txt-auto-reg");
        $ak_schedule        = $this->input->post("txt-ak-schedule");
        $homepage           = $this->input->post("txt-homepage");
        $conf_page          = $this->input->post("txt-conf-page");
        $conf_text          = $this->input->post("txt-conf-text");
        $unavailable_text   = $this->input->post("txt-unavailable-text");
        $success_text       = $this->input->post("txt-success-text");
        $url_promo          = $this->input->post("txt-url-promo");
        $service_promo_text = $this->input->post("txt-service-promo-text");
        
        $auto_reg           = ((int)($auto_reg) == 0) ? 0 : $auto_reg;
        $homepage           = ($homepage == 'no') ? $homepage : 'yes';
        $conf_page          = ($conf_page == 'yes') ? $conf_page : 'no';
        
        $error              = FALSE;
        if ((int)$ak_schedule == 0) {
            $ak_default     = $this->ak_model->read_ak_by_name('ALL');
            $ak_schedule    = $ak_default[0]['id'];
        }

        if (empty($wap_name) || empty($wap_service) || empty($wap_title)) {
			$error .= 'Required field is empty<br />';
		}else{
			$service_exist = $this->subscription_model->read_wapreg_by_name($wap_name);
			if ($service_exist != FALSE) {
				$error .= 'wapsite name exist. Please choose another<br />';
			} else {
				if (isset($_FILES['header_image_1']) && $_FILES['header_image_1']['size'] > 0) {
					$do_upload = $this->upload('header_image_1', $wap_name.'_header_1');
					if (is_array($do_upload)) $error .= $do_upload['error'].' for '.$wap_name.'_header_1';
				}
				if (isset($_FILES['header_image_2']) && $_FILES['header_image_2']['size'] > 0) {
					$do_upload = $this->upload('header_image_2', $wap_name.'_header_2');
					if (is_array($do_upload)) $error .= $do_upload['error'].' for '.$wap_name.'_header_2';
				}
				if (isset($_FILES['footer_image_1']) && $_FILES['footer_image_1']['size'] > 0) {
					$do_upload = $this->upload('footer_image_1', $wap_name.'_footer_1');
					if (is_array($do_upload)) $error .= $do_upload['error'];
				}
				if (isset($_FILES['footer_image_2']) && $_FILES['footer_image_2']['size'] > 0) {
					$do_upload = $this->upload('footer_image_2', $wap_name.'_footer_2');
					if (is_array($do_upload)) $error .= $do_upload['error'];
				}
			}
		}

		if ($error == FALSE) {
			$xts_error	  = FALSE;
			$xts_token = NULL;
			$insert_attempt = $this->subscription_model->create_wapreg(
				$wap_name, $wap_service, $wap_title, $auto_reg, $ak_schedule, $homepage,
				$conf_page, $conf_text, $unavailable_text,
				$success_text, $url_promo, $service_promo_text,
				$xts_token
			);
		
			if ($insert_attempt == FALSE) $error .= 'Error inserting into database. Please retry<br />';
		}

		if($error != FALSE) {
			$this->mysmarty->assign('wap_name', $wap_name);
			$this->mysmarty->assign('wap_service', $wap_service);
			$this->mysmarty->assign('wap_title', $wap_title);
			$this->mysmarty->assign('auto_reg', $auto_reg);
			$this->mysmarty->assign('ak_id', $ak_schedule);
			$this->mysmarty->assign('homepage', $homepage);
			$this->mysmarty->assign('confirmation_page', $conf_page);
			$this->mysmarty->assign('conf_text', $conf_text);
			$this->mysmarty->assign('unavailable_text', $unavailable_text);
			$this->mysmarty->assign('success_text', $success_text);
			$this->mysmarty->assign('url_promo', $url_promo);
			$this->mysmarty->assign('service_promo_text', $service_promo_text);
			$this->mysmarty->assign('error', $error);
		}else{
			if ($xts_error == TRUE) {
				$html_code = sprintf('
					<html>
						<script>
							alert("Failed To Generate XTS Token. Please contact service desk for assistance");
							location = "%s";
						</script>
					</html>', base_url().'wap/subscription/'
				);

				echo $html_code;
				exit;
			} else {
				redirect(base_url().'wap/subscription');
			}
		}
	}

	$ak_list	= $this->ak_model->read_all();
	$type_array	= array('yes'=>'Activate', 'no'=>'Not Activate');

	$this->mysmarty->assign('ak', $ak_list);
	$this->mysmarty->assign('type_array', $type_array);
	//$this->mysmarty->assign('service', $this->getWapServiceList($wap_service));
	//$this->mysmarty->assign('page_title', 'Add Wap Registration');
	$this->mysmarty->view('wap/subscription_add_view.html');
    }
    
    public function EditSubscription() {
		$this->klogger->log("");
        $id = $this->uri->segment(4);

        if (empty($id)) redirect(base_url().'wap/subscription');

        $wapreg = $this->subscription_model->read_wapreg_by_id($id);
		$component = $this->subscription_model->read_subscription_component($id);
		$ak_list	= $this->ak_model->read_all();
        $type_array	= array('yes'=>'Activate', 'no'=>'Not Activate');
		$type_component = array(
			 'image',
			 'text',
			 'title',
			 'space',
			 'url',
			 'catalog-a',
			 'catalog-b',
			 'ivr'
		);
		$type_link = array(
			 'y',
			 'n' 
		);

        if ($wapreg == FALSE) redirect(base_url().'wap/subscription');

        if ($this->input->post('submit') == TRUE) {
            $wap_name           = str_replace(' ', '_', strtolower($this->input->post('txt-wap-name')));
            $wap_service        = $this->input->post("txt-wap-service");
            $wap_title          = $this->input->post("txt-wap-title");
            $auto_reg           = $this->input->post("txt-auto-reg");
            $ak_schedule        = $this->input->post("txt-ak-schedule");
            $homepage           = $this->input->post("txt-homepage");
            $conf_page          = $this->input->post("txt-conf-page");
            $conf_text          = $this->input->post("txt-conf-text");
            $unavailable_text   = $this->input->post("txt-unavailable-text");
            $success_text       = $this->input->post("txt-success-text");
            $url_promo          = $this->input->post("txt-url-promo");
            $service_promo_text = $this->input->post("txt-service-promo-text");
            $xts_token          = $this->input->post("xts_token");
            
            $auto_reg           = ((int)($auto_reg) == 0) ? 0 : $auto_reg;
            $homepage           = ($homepage == 'no') ? $homepage : 'yes';
            $conf_page          = ($conf_page == 'yes') ? $conf_page : 'no';

            $error              = FALSE;
            if ((int)$ak_schedule == 0) {
                $ak_default     = $this->ak_model->read_ak_by_name('ALL');
                $ak_schedule    = $ak_default[0]['id'];
            }

            $ar_error = array();
            if (empty($wap_name) || empty($wap_service) || empty($wap_title) || empty($unavailable_text) || empty($success_text)) {
                $error .= 'Required field is empty <br />';
            } else if ($homepage == 'no' && $conf_page == 'no') {
                $error .= 'Either homepage or confirmation page must set to active<br />';
            } else if ($conf_page == 'yes' && empty($conf_text)) {
                $error .= 'Confirmation Text cannot empty because confirmation page set to active<br />';
            } else {
                $service_exist = $this->subscription_model->read_wapreg_by_id_name($id, $wap_name);

                if ($service_exist != FALSE) {
                    $error .= 'Service name exist. Please choose another<br />';
                } else {
                    //print_r($_FILES);
                    if (isset($_FILES['unreg_image']) && $_FILES['unreg_image']['size'] > 0) {
                        $do_upload = $this->upload_unreg('unreg_image', $wap_name.'_unreg_image');

                        if (is_array($do_upload)) $error .= $do_upload['error'].' for '.$wap_name.'_unreg_image';
                    }
                    if (isset($_FILES['header_image_1']) && $_FILES['header_image_1']['size'] > 0) {
                        $do_upload = $this->upload('header_image_1', $wap_name.'_header_1');

                        if (is_array($do_upload)) $error .= $do_upload['error'].' for '.$wap_name.'_header_1';
                    }
                    if (isset($_FILES['header_image_2']) && $_FILES['header_image_2']['size'] > 0) {
                        $do_upload = $this->upload('header_image_2', $wap_name.'_header_2');

                        if (is_array($do_upload)) $error .= $do_upload['error'].' for '.$wap_name.'_header_2';
                    }
                    if (isset($_FILES['header_image_3']) && $_FILES['header_image_3']['size'] > 0) {
                        $do_upload = $this->upload('header_image_3', $wap_name.'_header_3');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }

                    if (isset($_FILES['footer_image_1']) && $_FILES['footer_image_1']['size'] > 0) {
                        $do_upload = $this->upload('footer_image_1', $wap_name.'_footer_1');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['footer_image_2']) && $_FILES['footer_image_2']['size'] > 0) {
                        $do_upload = $this->upload('footer_image_2', $wap_name.'_footer_2');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['footer_image_3']) && $_FILES['footer_image_3']['size'] > 0) {
                        $do_upload = $this->upload('footer_image_3', $wap_name.'_footer_3');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }

                    if (isset($_FILES['promo_image_1']) && $_FILES['promo_image_1']['size'] > 0) {
                        $do_upload = $this->upload('promo_image_1', 'reg_'.$wap_name.'_1');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['promo_image_2']) && $_FILES['promo_image_2']['size'] > 0) {
                        $do_upload = $this->upload('promo_image_2', 'reg_'.$wap_name.'_2');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['promo_image_3']) && $_FILES['promo_image_3']['size'] > 0) {
                        $do_upload = $this->upload('promo_image_3', 'reg_'.$wap_name.'_3');

                        if (is_array($do_upload)) $error .= $do_upload['error'];
                    }

                    if (isset($_FILES['bg_image_1']) && $_FILES['bg_image_1']['size'] > 0) {
                        $do_upload = $this->upload('bg_image_1', 'background_'.$wap_name.'_1');

                        if(is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['bg_image_2']) && $_FILES['bg_image_2']['size'] > 0) {
                        $do_upload = $this->upload('bg_image_2', 'background_'.$wap_name.'_2');

                        if(is_array($do_upload)) $error .= $do_upload['error'];
                    }
                    if (isset($_FILES['bg_image_3']) && $_FILES['bg_image_3']['size'] > 0) {
                        $do_upload = $this->upload('bg_image_3', 'background_'.$wap_name.'_3');

                        if(is_array($do_upload)) $error .= $do_upload['error'];
                    }
                }
            }

            if ($error == FALSE) {
                $update_attempt = $this->subscription_model->update_wapreg(
                    $id, $wap_name, $wap_service, $wap_title, $auto_reg, $ak_schedule, $homepage,
                    $conf_page, $conf_text, $unavailable_text,
                    $success_text, $url_promo, $service_promo_text, $xts_token
                );

                if ($update_attempt == FALSE) $error .= 'Error updating database. Please retry<br />';
            }

            if ($error != FALSE) {
                $this->mysmarty->assign('wap_name', $wap_name);
                $this->mysmarty->assign('wap_service', $wap_service);
                $this->mysmarty->assign('wap_title', $wap_title);
                $this->mysmarty->assign('auto_reg', $auto_reg);
                $this->mysmarty->assign('ak_id', $ak_schedule);
                $this->mysmarty->assign('homepage', $homepage);
                $this->mysmarty->assign('confirmation_page', $conf_page);
                $this->mysmarty->assign('conf_text', $conf_text);
                $this->mysmarty->assign('unavailable_text', $unavailable_text);
                $this->mysmarty->assign('success_text', $success_text);
                $this->mysmarty->assign('url_promo', $url_promo);
                $this->mysmarty->assign('service_promo_text', $service_promo_text);
                $this->mysmarty->assign('xts_token', $xts_token);
                $this->mysmarty->assign('error', $error);
            } else {
                redirect(base_url().'wap/subscription');
            }
        } else {
            $url_image_wapsub = $this->config->item('url_image_wapsub');
			$path_image_wapsub = $this->config->item('path_image_wapsub');
			$path_image_tmp = $this->config->item('path_image_tmp');
			
			$ext = array('jpeg','jpg','gif','png');
			
			foreach($ext as $key){
				//echo $url_image_wapsub."header_".$wapreg[0]['wap_name']."_".$wapreg[0]['service'].".".$key."<br>";
				if(file_exists($path_image_wapsub."header_".$wapreg[0]['wap_name']."_".$wapreg[0]['service'].".".$key)) $extHeader=$key;
				if(file_exists($path_image_wapsub."header_background_".$wapreg[0]['wap_name']."_".$wapreg[0]['service'].".".$key)) $extHeaderBackground=$key;
				if(file_exists($path_image_wapsub."footer_".$wapreg[0]['wap_name']."_".$wapreg[0]['service'].".".$key)) $extFooter=$key;
				if(file_exists($path_image_wapsub."footer_background_".$wapreg[0]['wap_name']."_".$wapreg[0]['service'].".".$key)) $extFooterBackground=$key;
			}
			
			$this->mysmarty->assign('extHeader',$extHeader);
			$this->mysmarty->assign('extHeaderBackground',$extHeaderBackground);
			$this->mysmarty->assign('extFooter',$extFooter);
			$this->mysmarty->assign('extFooterBackground',$extFooterBackground);
			$this->mysmarty->assign('url_image_wapsub', $url_image_wapsub);
			$this->mysmarty->assign('path_image_wapsub', $path_image_wapsub);
			$this->mysmarty->assign('path_image_tmp', $path_image_tmp);
            $this->mysmarty->assign('wap_name', @$wapreg[0]['wap_name']);
            $this->mysmarty->assign('wap_service', @$wapreg[0]['service']);
            $this->mysmarty->assign('wap_title', @$wapreg[0]['wap_title']);
            $this->mysmarty->assign('auto_reg', @$wapreg[0]['autoreg']);
            $this->mysmarty->assign('ak_id', @$wapreg[0]['ak_id']);
            $this->mysmarty->assign('homepage', @$wapreg[0]['homepage']);
            $this->mysmarty->assign('confirmation_page', @$wapreg[0]['confirmation_page']);
            $this->mysmarty->assign('conf_text', @$wapreg[0]['confirmation_text']);
            $this->mysmarty->assign('unavailable_text', @$wapreg[0]['unavailable_text']);
            $this->mysmarty->assign('success_text', @$wapreg[0]['success_text']);
            $this->mysmarty->assign('url_promo', @$wapreg[0]['service_promo']);
            $this->mysmarty->assign('service_promo_text', @$wapreg[0]['service_promo_text']);
            $this->mysmarty->assign('xts_token', @$wapreg[0]['xts_token']);
			$this->mysmarty->assign('data_wapreg',$wapreg);
			$this->mysmarty->assign('data_component',$component);
			$this->mysmarty->assign('ak', $ak_list);
			$this->mysmarty->assign('type_array', $type_array);
			$this->mysmarty->assign( 'type_component', $type_component );
			$this->mysmarty->assign( 'type_link', $type_link );
        }

        $ak_list	= $this->ak_model->read_all();
        $type_array	= array('yes'=>'Activate', 'no'=>'Not Activate');

        $this->mysmarty->assign('type_array', $type_array);
        $this->mysmarty->assign('ak', $ak_list);
        $this->mysmarty->assign('id', $id);
        $this->mysmarty->assign('page_title', 'Edit Wap Registration');
        $this->mysmarty->view('wap/subscription_edit_view.html');
        /*$id     = $this->input->post("id");
        $result = $this->service_model->editService($id);

        $response = array (
            'wap_service'   => $result[0]['service'],
            'wap_name'      => $result[0]['name'],
            'adn'           => $result[0]['adn'],
            'mechanism'     => $result[0]['mechanism']
       );

        echo json_encode($response);
        exit;*/
    }
    
    public function ajaxUpdateSubscription($id) {
        $this->klogger->log("");
        $wap_service        = $this->input->post("txt-wap-service");
        $wap_name           = $this->input->post("txt-wap-name");
        $adn                = $this->input->post("txt-adn");
        $mechanism          = $this->input->post("txt-mechanism");
        $wap_name_compare   = $this->input->post("wap-name-compare");
        
        $response = array();
        
        //validate
        if (empty ($wap_service)) {
            $status_wap_service = FALSE;
            $msg_wap_service    = "require field";
        }
        else {
            $status_wap_service = TRUE;
            $msg_wap_service    = "";
        }
        
        if (empty ($wap_name)) {
            $status_wap_name = FALSE;
            $msg_wap_name    = "require field";
        }
        else {
            $status_wap_name = TRUE;
            $msg_wap_name    = "";
        }
        
        if (empty ($adn)) {
            $status_adn = FALSE;
            $msg_adn    = "require field";
        }
        else {
            $status_adn = TRUE;
            $msg_adn    = "";
        }
        
        if (empty ($mechanism)) {
            $status_mechanism = FALSE;
            $msg_mechanism    = "require field";
        }
        else {
            $status_mechanism = TRUE;
            $msg_mechanism    = "";
        }
        
        if (!empty($wap_name) && !empty($mechanism))
        {
            if($wap_name==$wap_name_compare)
            {
                $response = array ('status' =>TRUE, 'message' => '', 'id'=>$id);
                $response = $this->service_model->updateService($id, $wap_service, $wap_name, $adn, $mechanism);
            }
            else {
                    if ($this->service_model->check_wap_name($wap_name))
                    {
                    $response = array (
                            'status_wap_name' => FALSE, 
                            'msg_wap_name' => "Wap Name already exist, try another name",
                            'status' => FALSE, 
                            'message' => 'Wap Name already exist, try another name'
                             );
                    }
                    else {
                    $response = $this->service_model->updateService($id, $wap_service, $wap_name, $adn, $mechanism);
                    }
            }
        }
        else {
           $response = array (  'status_wap_service'    => $status_wap_service,
                                'msg_wap_service'       => $msg_wap_service,
                                'status_wap_name'       => $status_wap_name,
                                'msg_wap_name'          => $msg_wap_name,
                                'status_adn'            => $status_adn,
                                'msg_adn'               => $msg_adn,
                                'status_mechanism'      => $status_mechanism,
                                'msg_mechanism'         => $msg_mechanism,
                                'status'                => FALSE, 
                                'message'               => 'error'
                              );
        }
        echo json_encode($response);
        exit;
        
    }
    
    public function ajaxDeleteSubscription() {
	$this->klogger->log("");
        $id = $this->input->post("id");

        if (empty($id)) redirect(base_url(). 'wap/subscription');

        //$this->load->model(array('wapreg','wapreg_component'));
        $response = $this->subscription_model->unactivate_wapreg($id);
       // $this->subscription_model->unactivate_wapreg_params_by_wapregid($id);
        //$this->wapreg_component->unactivate_component_by_wapid($id);

        echo json_encode($response);
        exit;
    }
    
    
    function upload_unreg($file, $filename) {
		$this->klogger->log("");
        $config['upload_path']	 = './temp/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']		 = '200';
        $config['file_name']	 = $filename;

        $this->load->library('upload', $config);

        if($this->upload->do_upload($file) == FALSE) {
            $return = array('result'=>'failed', 'error' => $this->upload->display_errors());
        } else {
            $data        = $this->upload->data();
            $img_wh      = '_'.$data['image_width'].'x'.$data['image_height'];
            $config_root = get_config();

            if ($data['file_type'] == 'image/jpeg') {
                $copy   = copy($data['full_path'], $config_root['wapreg_image'].$filename);
                $return = ($copy == FALSE) ? array('result'=>'failed', 'error' => 'failed to copy jpg file') : TRUE;
            } else {
                $image  = ($data['file_type'] == 'image/gif') ? @imagecreatefromgif($data['full_path']) : @imagecreatefrompng($data['full_path']);
                $copy   = @imagejpeg($image, $config_root['wapreg_image'].$filename);
                $return = ($copy == FALSE) ? array('result'=>'failed', 'error' => 'failed to convert jpg file') : TRUE;
            }

            unlink($data['full_path']);
        }

        return $return;
    }
    
    public function upload($file, $filename) {
	$this->klogger->log("");
        $config['upload_path']	 = './temp/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']		 = '200';
        $config['file_name']	 = $filename;

        $this->load->library('upload', $config);

        if($this->upload->do_upload($file) == FALSE) {
            $return = array('result'=>'failed', 'error' => $this->upload->display_errors());
		} else {
            $data        = $this->upload->data();
            $img_wh      = '_'.$data['image_width'].'x'.$data['image_height'];
            $config_root = get_config();
            $root        = '';

            if ($data['file_type'] == 'image/jpeg') {
                $copy   = copy($data['full_path'], $config_root['wapreg_image'].$filename.$img_wh);
                $return = ($copy == FALSE) ? array('result'=>'failed', 'error' => 'failed to copy jpg file') : TRUE;
            } else {
                $image  = ($data['file_type'] == 'image/gif') ? @imagecreatefromgif($data['full_path']) : @imagecreatefrompng($data['full_path']);
                $imageTW = $this->replaceTransparentWhite($image);
				$copy   = @imagejpeg($imageTW, $config_root['wapreg_image'].$filename.$img_wh);
                @chmod($config_root['wapreg_image'].$filename.$img_wh.'.jpg', 0777);
				$return = ($copy == FALSE) ? array('result'=>'failed', 'error' => 'failed to convert jpg file') : TRUE;
            }

            unlink($data['full_path']);
        }

        return $return;
    }
    
    function replaceTransparentWhite($im){
        $src_w = ImageSX($im);
        $src_h = ImageSY($im);
        $backgroundimage = imagecreatetruecolor($src_w, $src_h);
        $white =  ImageColorAllocate ($backgroundimage, 255, 255, 255);
        ImageFill($backgroundimage,0,0,$white);
        ImageAlphaBlending($backgroundimage, TRUE);
        imagecopy($backgroundimage, $im, 0,0,0,0, $src_w, $src_h);
        return $backgroundimage;
    } 

    public function hit_add_api($name, $description) {
	$this->klogger->log("");
        $this->load->helper('json');

        $read_config = get_config();
        $params      = array(
            'name'         => $name,
            'url'          => $read_config['xts_related']['url_format'] . $name,
            'subscriberId' => $read_config['xts_related']['subscriberId'],
            'channel'      => 'wap',
            'description'  => $description,
            'ip'           => $read_config['xts_related']['ip'],
            'tot_proxy'    => $read_config['xts_related']['tot_proxy'],
            'submit'       => 'TRUE'
        );
        
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_URL, $read_config['xts_related']['api_add']);
        @curl_setopt($ch, CURLOPT_HEADER, 0);
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $result	= @curl_exec($ch);
        $data	= @my_json_decode($result);

        @curl_close($ch);
        return $data;
    }
    
}
