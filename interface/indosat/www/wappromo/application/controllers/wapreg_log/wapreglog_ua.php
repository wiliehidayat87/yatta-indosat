<?php
/**
 *
 * wap/service controller
 *
 * @package		Waptool Creation
 * @since		Oct 17 2011
 * @author		Eko isa (LinkIT Dev Team)
 *
 */

class Wapreglog_ua extends CI_Controller {
	public $limit = 0;
        

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('wap_username'))
        return;
        $this->klogger->log("");
        
    }

    function index() {
        
    }
    
    function get_ua(){
        $this->klogger->log("");
        $this->load->model('wapreglog_model');
        $dtsend = $this->input->post("dtsend");
        if($dtsend){
            $dtout = explode("|", urldecode($dtsend));
            $this->klogger->log($dtsend. "=dtsend");
            $service = $dtout[2];
            $begindate = $dtout[0];
            $enddate = $dtout[1];
			if(isset($dtout[4])){
				$ua = "%".trim($dtout[4])."%";
			}else{
				$ua = '';
			}
			$jadr = 12;
			if(isset($dtout[3])){
				if($dtout[3] == 'def_jad'){
					$jadr = 18;
				}elseif($dtout[3] == 'miss_jad'){
					$jadr = 19;
				}elseif($dtout[3] == 'start_jad'){
					$jadr = 16;
				}elseif($dtout[3] == 'jad'){
					$jadr = 12;
				}elseif($dtout[3] == 'miss_jad_val'){
					$jadr = 21;
				}elseif($dtout[3] == 'miss_jar'){
					$jadr = 20;
				}elseif($dtout[3] == 'start_jar'){
					$jadr = 17;
				}elseif($dtout[3] == 'jar'){
					$jadr = 13;
				}
			}
        }else{
            if($this->input->post("tx_ua")){
                $ua = "%".trim(strtolower($this->input->post("tx_ua")))."%";
            }else{
                $ua = '';
            }
            $service = trim($this->input->post("tx_service"));
            $begindate = trim($this->input->post("tx_begindate"));
            $enddate = trim($this->input->post("tx_enddate"));
            $jadr = trim($this->input->post("tx_jadr"));
        }
        
	//echo "debug ua=".$ua."<br/>";
        $this->klogger->log($service ." > service; ". $begindate ." > begindate; ". $enddate . " > enddate; " . $ua . " > ua; " . $jadr . " > jadr;");
        $rslt = $this->wapreglog_model->getWapTrack($service, $begindate, $enddate, $ua, $jadr);
        // print_r($rslt); 
	$dtloop = 0;
	foreach($rslt['result'] as $dt){
		
		$this->load->library('terawurfl');
        	$wurflObj = new terawurfl;
	        $wurflObj->GetDeviceCapabilitiesFromAgent($dt['ua'], true);
	        $cap = $wurflObj->capabilities; 
		
		$rslt['result'][$dtloop]['ua_show'] = $cap['product_info']['brand_name'] . " " . $cap['product_info']['model_name'];	
		//		print_r($dt);
		//		echo "::::end<br/>";
				$dtloop++;
			}
		//	print_r($rslt['result']);
		/*	
	$this->load->library('terawurfl');
	$wurflObj = new terawurfl;
	$wurflObj->GetDeviceCapabilitiesFromAgent($_SERVER['HTTP_USER_AGENT'],true);
	//$wurflObj->GetDeviceCapabilitiesFromAgent($uagent, true);
	$cap = $wurflObj->capabilities; */
	$ua_show  = ''; //$cap['product_info']['brand_name'] . " " . $cap['product_info']['model_name'];
        
	$this->mysmarty->assign('v_service', $service);
        $this->mysmarty->assign('v_begindate', $begindate);
        $this->mysmarty->assign('v_enddate', '');
        $this->mysmarty->assign('v_ua', $ua);
        $this->mysmarty->assign('v_jadr', $jadr);
	$this->mysmarty->assign('v_ua_show', $ua_show);

	if($rslt['total'] > 0){
		$this->mysmarty->assign('hasil', $rslt['result']);
	}else{
		$this->mysmarty->assign('hasil', array());
	}
        $this->mysmarty->assign('base_url', base_url());
        $this->mysmarty->view('wapreg_log/detail_wplog.html');

    }
    
    
}

