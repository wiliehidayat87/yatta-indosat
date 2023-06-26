<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tool extends MY_Controller {

	public $limit = 0;

	public function __construct() {
		parent::__construct();

		$this->load->model('bonus/bonus_model');
		$this->load->library('Link_auth');
		$this->smarty->assign('base_url', base_url());
		$this->smarty->assign('themeUrl', $this->theme->getThemePath());
		$this->limit = $this->config->item('limit');
	}

	public function dateToString($param){
	
		$result    = str_replace('-',"", $param);
		$result    = str_replace(':',"", $result);
		$result    = preg_replace("/\s+/",'',$result);
	
		return $result;
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
	
        $jsFile = 'bonus/tool.js';
        $this->smarty->assign('jsFile', $jsFile);
        $this->smarty->assign('pageTitle', 'XMP Tools : BONUS Tool');
        $this->smarty->assign('pageLimit', $this->limit);
        
        $todayBONUS = $this->getTodayBONUSList();        
        $this->smarty->assign('todayBONUSTotal', $todayBONUS['total']);
        $this->smarty->assign('todayBONUSYesterday', $todayBONUS['yesterday']);
        $this->smarty->assign('todayBONUSLastSevenDays', $todayBONUS['lastsevendays']);
        
        $todayBONUS = $this->getTotalBONUSList();
        $this->smarty->assign('totalBONUSThisMonth', $todayBONUS['thismonth']);
        $this->smarty->assign('totalBONUSLastMonths', $todayBONUS['lastmonths']);
        $this->smarty->assign('totalBONUSLastSixMonths', $todayBONUS['lastsixmonths']);
        
        $this->smarty->display('bonus/tool.tpl');
		
	}	

	public function getTodayBONUSList(){
		$todayBONUS = array();
			
		$todayBONUS['total']           = $this->bonus_model->getTodayBONUSTotal();
		$todayBONUS['yesterday']       = $this->bonus_model->getTodayBONUSYesterday();
		$todayBONUS['lastsevendays']   = $this->bonus_model->getTodayBONUSLastSevenDays();
	
		return $todayBONUS;
	}
	
	public function getTotalBONUSList(){
		$totalBONUS = array();
	
		$totalBONUS['thismonth']       = $this->bonus_model->getTotalBONUSThisMonth();
		$totalBONUS['lastmonths']      = $this->bonus_model->getTotalBONUSLastMonths();
		$totalBONUS['lastsixmonths']   = $this->bonus_model->getTotalBONUSLastSixMonths();
	
		return $totalBONUS;
	}
	
	public function ajaxGetBONUSTrafficList() {
		write_log("info", __METHOD__ . ", Calling Method: ");
		if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
			$response = array('status' => FALSE, 'message' => $this->link_auth->errorMessage());
			echo json_encode($response);
			exit;
		}
		$this->benchmark->mark('code_start');
	
		$from   = "";
		$until  = "";
	
		$page   = $this->uri->segment(4);
		$offset = (isset($page)) ? (int) $page : 0;
		$paging = "";
		$result = "";
	
		$search = $this->input->post("searchParam");
		if ($search!=1){
			$from           = sprintf('%s-%s-%s %s:%s:%s', date('Y'), date('m'), date('d'), '00', '00', '00');
			$until          = sprintf('%s-%s-%s %s:%s:%s', date('Y'), date('m'), date('d'), date('H'), date('i'), date('s'));
			$msisdnNumber   = "";
			$limit          = $this->input->post("limit");
		}else{
			$from           = $this->input->post("dateFrom");
			$until          = $this->input->post("dateTo");
			$msisdnNumber   = $this->input->post("msisdnNumber");
			$limit          = $this->input->post("limitSearch");
			if (empty($from)) $from  = sprintf('%s-%s-%s %s:%s:%s', '00', '00', '00', '00', '00', '00');
			if (empty($until)) $until = sprintf('%s-%s-%s %s:%s:%s', date('Y'), date('m'), date('d'), '23', '59', '59');
		}
		//-- Validate Search --//
		if(!empty($from) && !empty($until)){
			$resFrom = $this->dateToString($from);
			$resUntil = $this->dateToString($until);
			if($resUntil >= $resFrom){
				if(!empty($msisdnNumber)){
					if(is_numeric($msisdnNumber)){
						$mData  = $this->bonus_model->getBONUSTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName,
								$reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
					}else{
						$response = array('status_msisdnNumber' => false, 'status' => false);
						echo json_encode($response);
						exit;
					}
				}else{
					$mData  = $this->bonus_model->getBONUSTrafficList($offset, $limit, $from, $until, $adnNumber, $operatorName,
								$reqType, $serviceName, $msisdnNumber, $msisdnCheckbox, $smsRequest, $search);
							
				}
			}
			else{
				$response = array('status_checkDate' => false, 'status' => false);
				echo json_encode($response);
				exit;
			}
		}else{
			$response = array('status_dateRange' => false, 'status' => false);
			echo json_encode($response);
			exit;
		}
		//--End Of Validate Search --//
	
		$total  = $mData['total'];
		$data   = $mData['result']['data'];
		$dTotal = $mData['result']['total'];
		$i = 1;
		if ($total > 0) {
			foreach ($data as $key => $dt) {
				$msisdn     = $dt['msisdn'];
				$msgtimestamp    = $dt['timestamp'];
				$time_downloaded = $dt['time_downloaded'];
				$msgdata     = $dt['content_url'];
	
				if ($i % 2)
					$result .= "<tr class=\"odd\">";
				else
					$result .= "<tr>";
					$result .= "<td>$msisdn</td>";
					$result .= "<td>$msgtimestamp</td>";
					$result .= "<td>$msgdata</td>";
					$result .= "<td>$time_downloaded</td>";
					$result .= "</tr>";
				$i++;
			}
	
			if ($total > $limit) {
			    $this->load->library('pagination');
	
			    $pagination['base_url'] = base_url() . "bonus/tool/ajaxGetBONUSTrafficList/";
				$pagination['uri_segment'] = 4;
                $pagination['total_rows'] = $total;
	            $pagination['per_page'] = $limit;
	
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
                $paging = '<li><a class="current" href="">1</a></li>';
	        }
        } else {
            $result .= "<tr><td colspan=\"7\">No data found</td></tr>";
            $paging = "<b></b>";
        }
        if (!empty($from) && !empty($until)){
	        $searchInfo = "Date Range: ".$from." - ".$until;
        if(!empty($adnNumber))
                	$searchInfo .= " | ADN: ".$adnNumber;
        if(!empty($serviceName))
        	$searchInfo .= " | Services: ".$serviceName;
        if (!empty($msisdnNumber))
        	$searchInfo .= " | MSISDN: ".$msisdnNumber;
        if (!empty($smsRequest))
        	$searchInfo .= " | SMS: ".$smsRequest;
        }else{
            $searchInfo = "";
        }
	
		$to = ($page + $limit) > $total ? $total : ($page + $limit);
        $this->benchmark->mark('code_end');
        $execution_time = $this->benchmark->elapsed_time('code_start', 'code_end');

        $response = array(
    		'offset'                => $offset,
    		'query'                 => $mData['query'],
    		'result'                => $result,
    		'paging'                => $paging,
    		'from'                  => ($page + 1),
            'to'                    => $to,
			'total'                 => $total,
            'exec_time'             => $execution_time,
			'searchInfo'            => $searchInfo,
			'fromDate'              => $from,
			'untilDate'             => $until,
			'operator'              => $operatorName,
			'adn'                   => $adnNumber,
            'msisdn'                => $msisdnNumber,
            'msisdnCheck'           => $msisdnCheckbox,
            'service'               => $serviceName,
            'type'                  => $reqType,
            'sms'                   => $smsRequest,
            'status_serviceName'    => true,
            'status_msisdnNumber'   => true,
            'status_dateRange'      => true,
            'status'                => true
		);

		echo json_encode($response);
		exit;
	}	
}

?>
