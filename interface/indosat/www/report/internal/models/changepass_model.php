<?php
class Changepass_model extends CI_Model{
		
		public $db_reports;
		  
		public function __construct() {
			parent::__construct();

        $this->db_reports = $this->load->database('reports', TRUE);
	}
		
		public function CheckPassword($currentpass){
			$username = $this->session->userdata('username');
			$currentpass = md5($currentpass);
			
			$sql   = "SELECT password FROM acc_users WHERE username=? and password=?";
			$query = $this->db_reports->query($sql,array($username,$currentpass));
		
				if($query->num_rows() !=0)
				{
					return TRUE;
				}
			
			return FALSE;
		}
		
		public function ChangePassword($newpassword,$newpasswordconfirm){
		if ($newpassword === $newpasswordconfirm)
		{
			$username = $this->session->userdata('username');
 			$newpassword=md5($newpassword);
		
			$sql = "UPDATE acc_users SET password=? WHERE username=?";

				if ($this->db_reports->query($sql,array($newpassword,$username)))
				{
					return TRUE;
				}
		}
		else	
			return FALSE;
		}
	
}
?>
