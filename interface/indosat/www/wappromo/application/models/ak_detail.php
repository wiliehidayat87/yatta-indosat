<?php

class Ak_detail extends CI_Model
{
	function __construct()
	{
		
        $this->load->database('newwap');
	}
	
	function read_all_ak_detail($ak_id)
	{
		$sql		= "SELECT * FROM ak_detail WHERE ak_id = ? AND status = '1' ORDER BY day_num, hour_start, hour_stop";
		$query	= $this->db->query($sql, $ak_id);
		
		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function read_ak_detail_by_id($id)
	{
		$sql		= "SELECT * FROM ak_detail WHERE id = ? AND status = '1' LIMIT 1";
		$query	= $this->db->query($sql, $id);
		
		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function create_ak_detail($ak_id, $day_num, $hour_start, $hour_stop)
	{
		$sql		= "INSERT INTO ak_detail(ak_id, day_num, hour_start, hour_stop, status)
								VALUES(?, ?, ?, ?, '1')";
		
		$query	= $this->db->query($sql, array($ak_id, $day_num, $hour_start, $hour_stop));
		
		return $query;
	}
	
	function update_ak_detail($id, $day_num, $hour_start, $hour_stop)
	{
		$sql		= "UPDATE ak_detail SET day_num = ?, hour_start = ?, hour_stop = ? 
								WHERE id = ? AND status = '1' LIMIT 1";
		
		$query	= $this->db->query($sql, array($day_num, $hour_start, $hour_stop, $id));
		
		return $query;
	}
	
	function unactivate_ak_detail($id)
	{
		$sql		= "UPDATE ak_detail SET status = '0' WHERE id = ? LIMIT 1";
		$query	= $this->db->query($sql, $id);
		
		return $query;
	}
	
	function unactivate_ak_detail_by_ak_id($ak_id)
	{
		$sql		= "UPDATE ak_detail SET status = '0' WHERE ak_id = ?";
		$query	= $this->db->query($sql, $id);
		
		return $query;
	}
}

?>
