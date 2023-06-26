<?php

/*
 * 
 *  Broadcast tool for XMP
 *  Broadcast tool helper
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate$
 *  Last updated by   $Author$
 *  Last revision     $LastChangedRevision$
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


function getScheduleList($param) {
    $scheduleList = array();
    $recurring_type = array("Once","Daily","Weekly");
    $status_type = array("Active","InProgress","InActive");
    
    $ci =& get_instance();
    $ci->load->model('broadcast/broadcast_model');
    $dataArr = $ci->broadcast_model->getSchedule($param);

    if (!$param["id"]) {
        foreach ($dataArr["result"]["data"] as $key=>$dt) {
            foreach ($dt as $k=>$d) {

                if ($k == "status") {
                    $d = $status_type[$d];
                }
                if ($k == "recurring_type") {
                    $d = $recurring_type[$d];
                }
                $scheduleList[$key][$k] = $d;
            }
        }
    } else {
        $scheduleList = $dataArr["result"]["data"][0];

    }

    return $scheduleList;
}

function getServiceList() {
    $serviceList = array();

    $ci =& get_instance();
    $ci->load->model('traffic/mo_traffic_model');
    
    $dataArr = $ci->mo_traffic_model->getServiceList();

    foreach ($dataArr as $key=>$dt) {
        $serviceList['id'][$key]   = $dt->name;                
        $serviceList['name'][$key] = $dt->name;                
    }

    return $serviceList;
}

function getOperatorList() {        
    $operatorList = array();

    $ci =& get_instance();
    $ci->load->model('traffic/mo_traffic_model');
    $dataArr = $ci->mo_traffic_model->getOperatorList();

    foreach ($dataArr as $dt) {
        $operatorList['id'][$dt['id']]   = $dt['operator_name'];                
        $operatorList['name'][$dt['id']] = $dt['operator_name'];                
    }

    return $operatorList;
}

function getAdnList() {
    $adnList = array();

    $ci =& get_instance();
    $ci->load->model('traffic/mo_traffic_model');
    $dataArr = $ci->mo_traffic_model->getAdnList();

    foreach ($dataArr as $dt) {
        $adnList['id'][$dt['id']]   = $dt['adn_name'];                
        $adnList['name'][$dt['id']] = $dt['adn_name'];                
    }

    return $adnList;
}

function getStatusList() {
    $statusList["id"]   = array("0","1","2");
    $statusList["name"] = array("Active","InProgress","InActive");

    return $statusList;
}    

function getRepeatList() {
    $repeatList["id"]   = array("0","1","2");
    $repeatList["name"] = array("Once","Daily","Weekly");

    return $repeatList;
}    

function getCSelectList() {
    $repeatList["id"]   = array("datepublish","random","sequential","sequentialrepeat","custom");
    $repeatList["name"] = $repeatList["id"];

    return $repeatList;
}

function getMimeType ($filename) {
    if (function_exists('mime_content_type'))
        $type =  mime_content_type($filename);

    if (empty($type) && function_exists('finfo_file'))
    {
        $finfo  = finfo_open(FILEINFO_MIME);
        $type   = finfo_file($finfo, $filename);
        finfo_close($finfo);
    }
    
    if (empty($type))
    {
        $output = shell_exec('file '.escapeshellcmd($filename));
        if (stripos($output, ' text') !== FALSE) $type = 'text/*';    
        else $type = 'binary/file';
    }
    
    return (empty($type)) ? FALSE:$type;
}

function asAscii ($str) {
    $newstr = '';
    $ln = strlen($str);
    for ($i=0; $i<=$ln; $i++)
    {
        $_key = ord($str{$i});
        if ($_key < 128) $newstr .= $str{$i};
	}
    return $newstr;
}



?>