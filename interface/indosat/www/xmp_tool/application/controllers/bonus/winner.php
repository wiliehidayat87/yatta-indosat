<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Winner extends MY_Controller {

	public $limit = 0;

	public function __construct() {
		parent::__construct();

		$this->load->model('bonus/bonus_model');
		$this->load->library('Link_auth');
		$this->smarty->assign('base_url', base_url());
		$this->smarty->assign('themeUrl', $this->theme->getThemePath());
		$this->limit = $this->config->item('limit');
	}
	
	public function index() {
		write_log("info", __METHOD__ . ", Calling Method: ");
		/*if ($this->link_auth->permission(__FUNCTION__) == FALSE) {
			$message = $this->link_auth->errorMessage();
	
			if ($message['Message'] == "Class not Found")
				redirect(base_url() . 'errorpage/errorpage/classNotFound');
			if ($message['Message'] == "Feature Disabled")
				redirect(base_url() . 'errorpage/errorpage/featureDisabled');
			exit;
		}*/
        $this->smarty->assign('pageTitle', 'XMP Tools : List Winner');		
		$response = $this->getBONUSWinner();
		$this->smarty->assign('sqlQuery', $response["query"]);
		$this->smarty->assign('countTotal', $response["total"]);
		$this->smarty->assign('countTime', $response["exec_time"]);
		$this->smarty->assign('winnerResult', $response["result"]);
	
		$this->smarty->display('bonus/winner.tpl');
	}
	
	public function getBONUSWinner(){
	    $this->benchmark->mark('code_start');
		$mData  = $this->bonus_model->getBONUSWinner();
		//var_dump($mData);

		$total  = $mData['total'];
		$data   = $mData['result']['data'];
		$dTotal = $mData['result']['total'];
		$i = 0;
		if ($total > 0) {
			foreach ($data as $key => $dt) {
			    $msisdn     = $dt['msisdn'];
			    $msgtimestamp    = $dt['time_downloaded'];
			    $msgdata     = $dt['totalnya'];
	
			    if ($i % 2)
				    $result .= "<tr class=\"odd\">";
			    else
				    $result .= "<tr>";
			    if ($prev_msisdn != $msisdn) {
			        $ctr = $ctr+1;
				    $result .= "<td>$ctr</td>";
			    } else
				    $result .= "<td>&nbsp;</td>";
				$detail = $this->bonus_model->getBONUSTrafficByMSISDN($msisdn);
				
				$row_detail = $msisdn;
				foreach ($detail as $key => $dtl) {
				    $row_detail .= "<br>".($key+1).". [".$dtl["time_downloaded"]."] ".$dtl["content_url"];
				}
			    $result .= "<td>$row_detail</td>";
			    $result .= "<td>$msgdata</td>";
			    $result .= "</tr>";
			    $prev_msisdn = $msisdn;
			    $i++;
			}
		
		} else {
			$result .= "<tr><td colspan=\"3\">No data found</td></tr>";
			$paging = "<b></b>";
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
				'total'                 => $i,
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
		
		//echo json_encode($response);
		return $response;
		exit;	
	}
	
}

?>
