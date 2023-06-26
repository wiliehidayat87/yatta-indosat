<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH . 'smarty/Smarty.class.php');

/**
* @file system/application/libraries/Mysmarty.php
*/
class Mysmarty extends Smarty
{
	function Mysmarty()
	{
		$this->Smarty();

		$config =& get_config();
		
		// absolute path prevents "template not found" errors
		$this->template_dir = (!empty($config['smarty_template_dir']) ? $config['smarty_template_dir'] 
																	  : 'application/views/');
																	
		$this->compile_dir  = (!empty($config['smarty_compile_dir']) ? $config['compile_dir'] 
																	 : APPPATH . 'cache/'); //use CI's cache folder        
		
		if (function_exists('site_url')) {
    		// URL helper required
			$this->assign("site_url", site_url()); // so we can get the full path to CI easily
		}
	}
	
	function view($resource_name, $cache_id = null)   {
		if (strpos($resource_name, '.') === false) {
			$resource_name .= '.tpl';
		}
		return parent::display($resource_name, $cache_id);
	}
} // END class smarty_library
?>
