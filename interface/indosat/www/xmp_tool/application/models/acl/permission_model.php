<?php
class Permission_model extends CI_Model {
       
	public function __construct() {
		parent::__construct();
 
	}

	public function getMethodList($groupId) {
		$this->db=$this->load->database('default',TRUE);
        $sql   = "SELECT controller_link,method FROM methods WHERE status='1' AND u_group=? ";
        
        write_log("info", __METHOD__.", Start Query ".$sql);    
        if($query = $this->db->query($sql, $groupId)){
			write_log("info", __METHOD__.", Query Success ");
			return $query->result_array();
		}
        else{
			write_log("info", __METHOD__.", Query Failed ");
			return array();
		}
          
	}		
}
