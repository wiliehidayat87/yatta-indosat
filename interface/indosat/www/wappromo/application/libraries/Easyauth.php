<?php  
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

/**
 * @package     EasyAuth
 * @subpackage  Libraries
 * @category    Authentication
 * @author      Wes Edling (joedesigns.com)
 * @copyright   Copyright (c) 2008, joedesigns.com
 * @link 		http://joedesigns.com/labs/EasyAuth
*/

/**
 * Notes: Uses md5 for password encryption
 * You might need to adjust the query to suit your database structure
*/

class Easyauth {
	var $connected;
	var $user;
	
	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->library('session');
		$this->CI->load->helper('url');
		$this->CI->load->database();
		//$this->set();
		//$this->check();
	}
	
	function clean($s){
		return $this->CI->db->escape($s);
	}
	
    function clearlogin()
    {
        unset($_SESSION['login']);
    }

	function set ($username, $password)
    {
        $_SESSION['login']['username'] = $username;
        $_SESSION['login']['password'] = md5($password);
	}
	
	function check($username = NULL, $password = NULL)
    {
		$this->connected = false;
		
        if ($username == NULL && isset($_SESSION['login']['username']))
            $username = $_SESSION['login']['username'];
        
        if ($password == NULL && isset($_SESSION['login']['password']))
            $password = $_SESSION['login']['password'];
        else
            $password = md5($password);

		if ($password && $username)
        {
            $sql = "
			select * 
			from sc_users 
			where 
				username = ".$this->clean($username)." and 
				password = ".$this->clean($password)."
			limit 1
			";

			$chk = $this->CI->db->query($sql);
			if ($chk->num_rows() == 1)
            {
                return true;
            }          
        }		
		return false;	
			
	}
	
}
?>
