<?php
class Changepass_model extends CI_Model{
		
		function __construct(){
			parent::__construct();
			$this->load->database('smswebtool');
		}
		
		public function CheckPassword($currentpass){
			$username = $this->session->userdata('wap_username');
			$currentpass = md5($currentpass);
			
			$sql   = "SELECT password FROM wp_users WHERE username=? and password=?";
			$query = $this->db->query($sql,array($username,$currentpass));
		
				if($query->num_rows() !=0)
				{
					return TRUE;
				}
			
			return FALSE;
		}
		
		public function ChangePassword($newpassword,$newpasswordconfirm){
		if ($newpassword === $newpasswordconfirm)
		{
			$username = $this->session->userdata('wap_username');
 			$newpassword=md5($newpassword);
		
			$sql = "UPDATE wp_users SET password=? WHERE username=?";

				if ($this->db->query($sql,array($newpassword,$username)))
				{
					return TRUE;
				}
		}
		else	
			return FALSE;
		}
	
}
