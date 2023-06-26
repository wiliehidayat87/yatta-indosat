<?php

/*
 * 
 *  Broadcast tool for XMP
 *  Broadcast schedule managemenet
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-11-19 17:49:59 +0700 (Mon, 19 Nov 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2990 $
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Schedule extends MY_Controller {
    

    public function __construct() {
        parent::__construct();

        $this->load->model('broadcast/broadcast_model');
        $this->load->model('traffic/mo_traffic_model');
        $this->load->helper('broadcast_helper');
        $this->load->helper('url');
        
        $this->load->library('Link_auth');
        
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('themeUrl', $this->theme->getThemePath());
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
        
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        //var_dump($_GET, $_POST); exit;
        $param['rec'] = ($param['rec']) ? $param['rec'] : 50;
        
        if ($param['dopost']=="sche_d" || $param['dopost']=="sche_e" || $param['dopost']=="s_lbl"){
            if (count($param['cSel'])) $this->doWithSelected($param);
            return;
        }
        
        $this->showList($param);
    }
    
    public function showList($param) {
        //$this->smarty->assign('addons_available', $addons["available"]);
        $serviceList  = getServiceList();
        $adnList      = getAdnList();
        $operatorList = getOperatorList();
        $statusList   = getStatusList();
        $repeatList   = getRepeatList();

        //$this->smarty->assign('operatorOptions', $operatorList);
        
        $this->smarty->assign('svc_ids',   $serviceList['id']);
        $this->smarty->assign('svc_names', $serviceList['name']);
        $this->smarty->assign('svc_id',    $param['svc_id']);
        $this->smarty->assign('adn_ids',   $adnList['id']);
        $this->smarty->assign('adn_names', $adnList['name']);
        $this->smarty->assign('opr_ids',   $operatorList['id']);
        $this->smarty->assign('opr_names', $operatorList['name']);
        $this->smarty->assign('sta_ids',   $statusList['id']);
        $this->smarty->assign('sta_names', $statusList['name']);
        $this->smarty->assign('rep_ids',   $repeatList['id']);
        $this->smarty->assign('rep_names', $repeatList['name']);
        
        $schedule = $this->getScheduleList($param);
        $scheduleList = $schedule["result"]["data"];
        $schedule['pg'] = $param['pg'];
        $schedule['rec'] = $param['rec'];
        $this->showNavigation($schedule);
        list ($push_time_date, $push_time_hour) = explode (" ", $scheduleList['push_time'], 2);
        $this->smarty->assign('services',  $scheduleList);
        $this->smarty->assign('push_time_date',  $push_time_date);
        $this->smarty->assign('push_time_hour',  $push_time_hour);
        $this->smarty->assign('rec',  $param['rec']);
        
        $this->smarty->assign('pageTitle', "Broadcast Schedule Management");
        $this->smarty->assign('message', $param['message']);
        $this->smarty->display('broadcast/schedule.tpl');        
    }
    
    public function showNavigation($param) {
        $total_page = ceil($param['total'] / $param['rec']);
        if ($total_page<=1) {
            $pageit .= '<b>[1] </b>';
        } else {
            $pageit = "";
            for ($i=1; $i<=$total_page; $i++) {
                if ($param['pg']!=$i) $pageit .= '<a href="'.base_url().'broadcast/schedule?pg='.$i.'&rec='.$param['rec'].'"><b>'.$i.'</b></a> ';
                else $pageit .= '<b>['.$i.'] </b>';
            }
        }
    
        $nav = '<tr><td colspan="2">
		  	<table cellpadding="0" cellspacing="0">
				<tbody><tr>
				<td valign="bottom"><table cellpadding="0" cellspacing="0"><tbody><tr><td>Page :  '.$pageit.' </td></tr></tbody></table></td>
				<td width="10"></td>
				</tr>
			</tbody></table>
		</td></tr>';
		$this->smarty->assign('page_navigation', $nav);

    }
    
    public function doWithSelected($param) {
        $ids = $param['cSel'];
        $_SQLID = implode (",", $ids);
        
        if ($param['dopost'] == 's_lbl') {
            $sql_query = "UPDATE push_schedule SET content_label='".$param['label']."' WHERE id IN  (".$_SQLID.")";
        } else {
            $status = ($param['dopost'] == 'sche_e') ?"0":"1";
            $sql_query = "UPDATE push_schedule SET status=".$status." WHERE id IN  (".$_SQLID.")";
        }

        $result = $this->broadcast_model->runQuery($sql_query);
        unset($param['cSel'], $param['dopost']);
        $param['message'] = sprintf("%d Schedule entry has been successully updated.", count($ids));
        $this->showList($param);
        
    }
    
    public function editSave() {
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $param["push_time"] = sprintf ("%s %s", $param['push_time_date'], $param['push_time_hour']);
        
        $result = $this->broadcast_model->editSchedule($param);

        //$this->index();
        redirect('broadcast/schedule');
        
    }
    
    public function insert() {
        $serviceList  = getServiceList();
        $adnList      = getAdnList();
        $operatorList = getOperatorList();
        $statusList   = getStatusList();
        $repeatList   = getRepeatList();
        $cSelectList  = getCSelectList();
    
        $this->smarty->assign('svc_ids',         $serviceList['id']);
        $this->smarty->assign('svc_names',       $serviceList['name']);
        $this->smarty->assign('adn_ids',         $adnList['id']);
        $this->smarty->assign('adn_names',       $adnList['name']);
        $this->smarty->assign('opr_ids',         $operatorList['id']);
        $this->smarty->assign('opr_names',       $operatorList['name']);
        $this->smarty->assign('sta_ids',         $statusList['id']);
        $this->smarty->assign('sta_names',       $statusList['name']);
        $this->smarty->assign('rep_ids',         $repeatList['id']);
        $this->smarty->assign('rep_names',       $repeatList['name']);
        $this->smarty->assign('cSelect_ids',     $cSelectList['id']);
        $this->smarty->assign('cSelect_names',   $cSelectList['name']);
        $this->smarty->assign('content_label',   $scheduleList['content_label']);
        $this->smarty->assign('price',           $scheduleList['price']);
        $this->smarty->assign('notes',           $scheduleList['notes']);
        $this->smarty->assign('handlerfile',     $scheduleList['handlerfile']);
        $this->smarty->assign('last_content_id', $scheduleList['last_content_id']);
        $this->smarty->assign('modified',        $scheduleList['modified']);
        $this->smarty->assign('created',         $scheduleList['created']);
        $this->smarty->assign('schedule_id',     $param['id']);
        $this->smarty->assign('action',          'insertSave');
        $this->smarty->assign('push_time_date',  date("Y-m-d"));
        $this->smarty->assign('push_time_hour',  date("H:i:s"));
        
        $this->smarty->assign('pageTitle',       "Input New Broadcast Schedule");
        $this->smarty->display('broadcast/schedule_edit.tpl');
    }
    
    public function insertSave() {
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $param["push_time"] = sprintf ("%s %s", $param['push_time_date'], $param['push_time_hour']);
        
        $result = $this->broadcast_model->addNewSchedule($param);

        //$this->index();
        redirect('broadcast/schedule');
        
    }

    public function delete() {
        if ($_POST['dopost']=="md") {
            if ($_POST['cSel']) {
                $result = $this->broadcast_model->deleteSchedule($_POST['cSel']);
            }
        }
        redirect('broadcast/schedule');
    }
    
    public function edit() {
        //echo "erad";
        
        if ($_GET) $param = $_GET;
        if ($_POST) $param = $_POST;
        
        $serviceList  = getServiceList();
        $adnList      = getAdnList();
        $operatorList = getOperatorList();
        $statusList   = getStatusList();
        $repeatList   = getRepeatList();
        $cSelectList  = getCSelectList();
        
        $scheduleList = $this->getScheduleList($param);

        $this->smarty->assign('svc_ids',         $serviceList['id']);
        $this->smarty->assign('svc_names',       $serviceList['name']);
        $this->smarty->assign('svc_id',          $scheduleList['service']);
        $this->smarty->assign('adn_ids',         $adnList['id']);
        $this->smarty->assign('adn_names',       $adnList['name']);
        $this->smarty->assign('adn_id',          $scheduleList['adn']);
        $this->smarty->assign('opr_ids',         $operatorList['id']);
        $this->smarty->assign('opr_names',       $operatorList['name']);
        $this->smarty->assign('opr_id',          $scheduleList['operator']);
        $this->smarty->assign('sta_ids',         $statusList['id']);
        $this->smarty->assign('sta_names',       $statusList['name']);
        $this->smarty->assign('sta_id',          $scheduleList['status']);
        $this->smarty->assign('rep_ids',         $repeatList['id']);
        $this->smarty->assign('rep_names',       $repeatList['name']);
        $this->smarty->assign('rep_id',          $scheduleList['recurring_type']);
        $this->smarty->assign('cSelect_ids',     $cSelectList['id']);
        $this->smarty->assign('cSelect_names',   $cSelectList['name']);
        $this->smarty->assign('cSelect_id',      $scheduleList['content_select']);
        $this->smarty->assign('content_label',   $scheduleList['content_label']);
        $this->smarty->assign('price',           $scheduleList['price']);
        $this->smarty->assign('notes',           $scheduleList['notes']);
        $this->smarty->assign('handlerfile',     $scheduleList['handlerfile']);
        $this->smarty->assign('last_content_id', $scheduleList['last_content_id']);
        $this->smarty->assign('modified',        $scheduleList['modified']);
        $this->smarty->assign('created',         $scheduleList['created']);
        $this->smarty->assign('schedule_id',     $param['id']);
        $this->smarty->assign('action',          'editSave');
        list ($push_time_date, $push_time_hour) = explode (" ", $scheduleList['push_time'], 2);
        $this->smarty->assign('push_time_date',  $push_time_date);
        $this->smarty->assign('push_time_hour',  $push_time_hour);
        
        $this->smarty->assign('pageTitle',       "Edit Broadcast Schedule");
        $this->smarty->display('broadcast/schedule_edit.tpl');
    }


    public function getScheduleList($param) {
        $scheduleList = array();
        $recurring_type = array("Once","Daily","Weekly");
        $status_type = array("Active","InProgress","InActive");
        
        $dataArr = $this->broadcast_model->getSchedule($param);
        
        if (!$param["id"]) {
            /*foreach ($dataArr["result"]["data"] as $key=>$dt) {
                foreach ($dt as $k=>$d) {

                    if ($k == "status") {
                        $d = $status_type[$d];
                    }
                    if ($k == "recurring_type") {
                        $d = $recurring_type[$d];
                    }
                    $scheduleList[$key][$k] = $d;
                }
            }*/
            $scheduleList = $dataArr;
        } else {
            $scheduleList = $dataArr["result"]["data"][0];
            
        }

        return $scheduleList;
    }

    public function dateToString($param){
        
        $result    = str_replace('-',"", $param);
        $result    = str_replace(':',"", $result);
        $result    = preg_replace("/\s+/",'',$result);
        
        return $result;
    }
    //        $query = $this->mo_traffic_model->getServiceList($params);


}

?>
