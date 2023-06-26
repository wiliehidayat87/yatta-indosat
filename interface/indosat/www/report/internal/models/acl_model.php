<?php

	class Acl_model extends CI_Model{
		
		function __construct()
		{
			parent::__construct();
			$this->load->database();
		}
		function getMenuList($group_name)
		{
			$sql  = "SELECT a.status,b.menu,b.parent,b.link FROM sc_group as a";
			$sql .="left join sc_menu as b on a.menu_id=b.id";
			$sql .="where a.group_name=?"; 
			$sql .="order by b.id ";
			$query = $this->db->query($sql,$group_name);
  	
			if($query->num_rows() != 0)
			{
				return $query->result_array();
			}
			else
			{
				return 0;
			}
		}	
	}

?>
