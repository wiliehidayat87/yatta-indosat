<?php

class Subscription extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('cs/subscription_model');
        $this->load->library('Link_auth');
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
        $this->limit = $this->config->item('limit');
	}

	public function index(){
		write_log("info", __METHOD__ . ", Calling Method: ");
		if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
		
		$jsFile = 'cs/subscription.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : Subscription');
        $this->smarty->assign('pageLimit',$this->limit);
                        
		$this->smarty->assign('service', $this->subscription_model->getService());
        $this->smarty->assign('operator', $this->subscription_model->getOperator());
        $this->smarty->assign('adn', $this->subscription_model->getAdn());
        $this->smarty->display('cs/subscription.tpl');
	}
	
	public function getUserSubscriptionTable(){
		write_log("info", __METHOD__ . ", Calling Method: ");
		if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $message = $this->link_auth->errorMessage();

            if ($message['Message'] == "Class not Found")
                redirect(base_url() . 'errorpage/errorpage/classNotFound');
            if ($message['Message'] == "Feature Disabled")
                redirect(base_url() . 'errorpage/errorpage/featureDisabled');
            exit;
        }
		
		if($this->input->post('adn')){ $adn = $this->input->post('adn');}else{ $adn='';}
        if($this->input->post('msisdn')){ $msisdn = $this->input->post('msisdn');}else{ $msisdn = '';}
        if($this->input->post('service')){ $service = $this->input->post('service');}else{ $service = '';}
        if($this->input->post('operator')){ $operator = $this->input->post('operator');}else{ $operator = '';}
        $statusConv=$this->config->item('status_config');
        
        $page = $this->uri->segment(4);
        $offset = (isset($page)) ? (int) $page : 0;
               
        $limit=10;
                   
        if($adn!='' ||$msisdn!='' ||$operator!='' ||$service!=''){
								
			$this->smarty->assign('searchData', $this->subscription_model->getSearch($msisdn,$operator,$adn,$service,$limit,$offset));
			$this->smarty->assign('nodata', 0);
			}
		else{
			$this->smarty->assign('searchData', '');
			$this->smarty->assign('nodata', 1);
			$total=0;
			}

		$this->smarty->assign('statusConv', $statusConv);
		$this->smarty->assign('numbering', $offset);
        $this->smarty->display('cs/subscription_table.tpl');
	}
	
	public function pagination(){
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
		
		if($this->input->post('adn')){ $adn = $this->input->post('adn');}else{ $adn='';}
        if($this->input->post('msisdn')){ $msisdn = $this->input->post('msisdn');}else{ $msisdn = '';}
        if($this->input->post('service')){ $service = $this->input->post('service');}else{ $service = '';}
        if($this->input->post('operator')){ $operator = $this->input->post('operator');}else{ $operator = '';}
        if($this->input->post('page')){$page = $this->input->post('page'); }else{$page = ''; } 
			
		$paging = "";
		$limit=10;
		$getTotal=$this->subscription_model->getTotalSearch($msisdn,$operator,$adn,$service);
		$total=count($getTotal);
		if ($total > $limit) {
                $this->load->library('pagination');

                $pagination['base_url'] = base_url() . "cs/subscription/getUserSubscriptionTable/";
                $pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
                $pagination['per_page'] = $limit;

                $this->pagination->initialize($pagination);
                $paging_data = $this->pagination->create_links();
                $paging_data = preg_replace('/\<strong\>(\d+)\<\/strong\>/i', '<a class="current" href="">$1</a>', $paging_data);
                $paging_data = explode("&nbsp;", $paging_data);
                $paging = "<li>Total row: $total &nbsp;</li>";
                foreach ($paging_data as $page) {
                    if (!empty($page))
                        $paging.="<li>$page</li>";
                }
            } else {
                $paging = '<li><a class="current" href="">1</a></li>';
            }
            $response['paging']=$paging;
            
            if(empty($total))
				$response['status']="nodata";			
			else
				$response['status']="data available";
				
		echo json_encode($response);
		exit;
	}
	
	public function inactiveCheck(){
		write_log("info", __METHOD__ . ", Calling Method: ");
        if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
            $response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
            echo json_encode($response);
            exit;
        }
		
		$choice = $this->input->post('choice');
		$choice=explode(",",$choice);
        
        $count=count($choice);
        for($x=0;$x<$count;$x++)
        {
			if($choice[$x]!=''){
				$checkedDesc= $this->subscription_model->getChecked($choice[$x]);
				
				$msisdn 	= $checkedDesc[0]->msisdn;
				$adn 		= $checkedDesc[0]->adn;
				$service 	= $checkedDesc[0]->service;
				$operator 	= $checkedDesc[0]->operator;
				$channel 	= 'web';
						
				$apiResult = $this->subscription_model->getInactiveChecked(API_USERNAME, API_PASSWORD, $msisdn, $adn, $service, $operator, $channel);
			
				if($apiResult){						
					if ('OK' != $apiResult['status']) {
						$result = array(
							'status' => 'NOK',
							'description' => 'Invalid Param',
							'data' => $apiResult
						);
						echo (json_encode($result));
					}
					else
					{	 $result = array(
							'status' => 'OK',
							'description' => 'MO Saved',
							'data' => ''
							);
							echo (json_encode($result));
					}
				}
				else
				{ 
					$result = array(
								'status' => 'NOK',
								'description' => 'ERROR',
								'data' => $apiResult
							);
						die (json_encode($result));
				}
			}
		}
	}
		
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
