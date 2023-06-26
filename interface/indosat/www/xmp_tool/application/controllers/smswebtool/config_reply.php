<?php

/*
 * 
 *  SMS web tool for XMP
 *  Reply message management
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-10-10 19:12:44 +0700 (Wed, 10 Oct 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2946 $
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define('REPLY_DIR', '/app/xmp2012/interface/telkomsel/default/service/reply/');

class Config_reply extends MY_Controller {
    

    public function __construct() {
        parent::__construct();

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

        //$this->smarty->display('smswebtool/test.tpl'); exit;
        $this->read();
        
    }

    
    function read() {
        $this->load->helper(array('url','security'));

        $service_code = array();

        //Read directory & return service name

        $service_name	= array();
        $open = @opendir(REPLY_DIR);

        while (FALSE !== ($file = @readdir($open))) {
            if($file != '.' && $file != '..') {
                $display	= explode('_', $file);
                $value		= explode('.', $file);
                if ($value[0]) $service_name[]	= array('name'=>$value[0], 'file'=>$value[0]); 
            }
        }

        //end Read directory

        $url_array = $this->uri->uri_to_assoc(4);

        //Read INI file

        $service_file   = (isset($url_array['code']))? trim($url_array['code']) : NULL;
        $reply_file     = REPLY_DIR . $service_file . '.ini';
        //var_dump($url_array, $service_file, $reply_file);

        if(isset($service_file) && file_exists($reply_file)) {
            $read_reply     = @parse_ini_file($reply_file);	
            $read_percat    = @parse_ini_file($reply_file,true);
            $rows_reply     = array_keys($read_reply);


            foreach($rows_reply as $item) {
                $message            = $read_percat['REPLY'][$item];
                $len_reply          = strlen($message);
                $reply_display[]    = array(
                    'function'=>$item, 
                    'message'=>$message,
                    'value'=>$read_reply[$item], 
                    'length'=>$len_reply,
                    'message_encode'=>base64_encode($message),
                    'function_encode'=>base64_encode($item)
                );

            }

            $this->smarty->assign('service_file', $service_file);
            $this->smarty->assign('reply_display', $reply_display);
        }
        //var_dump($reply_display); exit;

        //End Read INI file

        foreach ($service_name as $k=>$val) {
            $serviceList['id'][$k] = $val['name'];
            $serviceList['name'][$k] = $val['file'];
        }
        $this->smarty->assign('svc_ids',         $serviceList['id']);
        $this->smarty->assign('svc_names',       $serviceList['name']);
        $this->smarty->assign('svc_id',          $url_array['code']);

        $this->smarty->assign('data_code', $service_code);
        $this->smarty->assign('data_service', $service_name);
        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('page_title', 'Message Reply');
        $this->smarty->display('smswebtool/config_reply.tpl');
    }
	
	
    function sort() {
        $this->load->helper('security');

        $url_array = $this->uri->uri_to_assoc(4);
        $code = xss_clean($url_array['code']);

        $service_name	= array();
        $html = "<option value=''>--------</option>";
        $open = @opendir(REPLY_DIR);

        while (FALSE !== ($file = readdir($open))) {
            if($file != '.' && $file != '..') {
                $display	= explode('_', $file);
                $value		= explode('.', $file);

                if(!empty($code)) {
                    $code_file	= explode('.', $display[1]);
                    if($code_file[0] == $code) {
                        $service_name[] = array('name'=>$display[0], 'file'=>$value[0]);  
                    }
                } else {
                    $service_name[] = array('name'=>$display[0], 'file'=>$value);
                }
            }
        }

        foreach($service_name as $item) {
            $html .= "<option value='" . $item['file'] . "'>" . $item['name'] . "</option>";
        }

        echo $html;
    }
	
    function filter() { // format the url and redirect to index
        $this->load->helper('url');

        if($this->input->post('service_filter')) {
            $service = trim($this->input->post('service_filter', TRUE));
            $url = base_url().'smswebtool/config_reply/read/code/' . $service . '/';

            redirect($url);
        } else {
            redirect('smswebtool/config_reply/read');
        }
    }
	
    function edit() {
        $this->load->helper(array('url', 'security'));

        $url_array  = $this->uri->uri_to_assoc(4);
        $service    = (isset($url_array['service'])) ? trim($url_array['service']) : NULL;
        $function   = (isset($url_array['function'])) ? trim(base64_decode($url_array['function'])) : NULL;
        $message    = (isset($url_array['message'])) ? trim(base64_decode($url_array['message'])) : NULL;
        $file       = REPLY_DIR . $service . '.ini';
        
        if (!is_writable($file)) $message = "Ini file ($file) is not writable, please change the file permission";

        if($this->input->post('submit')) {
            $post_message = trim(xss_clean(substr($this->input->post('message'), 0, 160)));
            $post_price	  = $this->input->post('price');
            if(isset($service) && isset($function) && isset($post_message) && file_exists($file)) {
                $iniValue = parse_ini_file($file,true);
                $iniValue['REPLY'][$function] = $post_message;
                $iniValue['CHARGING'][$function] = $post_price;

                $stringSave = '';

                foreach($iniValue as $firstTree => $secondValue) {
                    $stringSave .= '[' . $firstTree . ']' . "\n";

                    foreach($secondValue as $secondTree => $thirdValue) {
                        $stringSave .= $secondTree . ' = "' . $thirdValue . '"' . "\n";
                    }
                }
                //($file, $stringSave, LOCK_EX);
                file_put_contents($file, $stringSave);
                $url_redirect = base_url().'smswebtool/config_reply/read/code/' . $service . '/';
                redirect($url_redirect);
            } else {
                redirect('message');
            }
        }


        if(isset($service) && isset($function) && file_exists($file)) {
            $read_reply = parse_ini_file($file);
            $read_msg   = parse_ini_file($file,true);
            $msg        = $read_msg['REPLY'][$function];
            $price      = $read_reply[$function];
            $edit_reply = array('service'=>$service,'function'=>$function, 'price'=>$price, 'message'=>$msg,'message_encode'=>base64_encode($msg), 'function_encode'=>base64_encode($function));
            $this->smarty->assign('edit_data', $edit_reply);
        } else {
            redirect('message');
        }

        $this->smarty->assign('base_url', base_url());
        $this->smarty->assign('message', $message);
        $this->smarty->assign('page_title', 'Message Reply Edit for ' . $service);
        $this->smarty->display('smswebtool/reply_edit.tpl');
    }
	
    function write_ini_file($assoc_arr, $path, $has_sections = FALSE) {
        $content = ""; 

        if ($has_sections) { 
            foreach ($assoc_arr as $key=>$elem) { 
                $content .= "[".$key."]\n"; 
                foreach ($elem as $key2=>$elem2) { 
                    if(is_array($elem2)) { 
                        for($i=0;$i<count($elem2);$i++) { 
                            $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                        } 
                    } else
                        if($elem2=="") $content .= $key2." = \n"; 
                        else $content .= $key2." = \"".$elem2."\"\n"; 
                } 
            } 
        } else { 
            foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) { 
                for($i=0;$i<count($elem);$i++) { 
                    $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
                } 
            } else
                if($elem=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem."\"\n"; 
            } 
        } 

        if (!$handle = fopen($path, 'w')) return false; 

        if (!fwrite($handle, $content)) return false; 

        fclose($handle); 
        return true;
    }    
}

?>
