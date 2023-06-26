<?php

/*
 * 
 *  Deployment tool for XMP
 *  Deploy compressed telco package from $addon_dir to $installed_dir
 * 
 *  Original Author: erad (eko.radianto@linkit360.com)
 *  Last updated      $LastChangedDate: 2012-10-10 19:12:44 +0700 (Wed, 10 Oct 2012) $
 *  Last updated by   $Author: erad $
 *  Last revision     $LastChangedRevision: 2946 $
 * 
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Xmp_install extends MY_Controller {
    
    public $installed_dir = "/app/xmp2012/interface/";
    public $addon_dir     = "/app/xmp2012/system/addon/";
    public $addon_ext     = array(".zip");

    public function __construct() {
        parent::__construct();

       // $this->load->model('bonus/bonus_model');
        $this->load->library('Link_auth');
        $this->load->library('zend');
        $this->load->library('zipper');
        
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
        
        if ($_REQUEST["cb_addon"]) {
            $selected_addon = $_REQUEST["cb_addon"];
            $this->deployAddon($selected_addon);
        }
        
        $addons = $this->checkInstalledAddon();
        //var_dump($addons);
        $this->smarty->assign('addons_installed', $addons["installed"]);
        $this->smarty->assign('addons_available', $addons["available"]);
        
        $this->smarty->display('admin/xmp_install.tpl');
        
    }
    
    public function deployAddon($addons) {
        foreach ($addons as $addon) {
            foreach ($this->addon_ext as $ext) {
                $addon_file = $this->addon_dir . $addon . $ext; 
                if (is_file($addon_file)) {
                    //var_dump($addon_file);
                    if ($ext==".zip") $this->zipper->unzip($addon_file, $this->installed_dir, false);
                }
            }
        }
    }


    public function readDir($source_dir, $exclude=array()) {
        $files = array();
        
        if ($handle = opendir($source_dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != ".svn" && !in_array($entry,$exclude)) {
                    $files[] = $entry;
                }
            }
            closedir($handle);
            asort($files);
            return $files;
        }     
    }
    
    public function checkInstalledAddon() {
        $installed = $this->readDir($this->installed_dir,array("default"));
        $available = $this->readDir($this->addon_dir);
        
        $no_extension = array();
        foreach ($available as $key=>$filename) {
            $explode = explode(".", $filename, -1);
            $no_extension[] = $explode[0]; //implode(".", explode(".", $explode, -1));
        }
        $available = $no_extension;
        
        $intersect = array_intersect($installed, $available);
        $difference  = array_diff($available, $intersect);
        
        return array("installed"=>$intersect, "available"=>$difference);
    }

}

?>
