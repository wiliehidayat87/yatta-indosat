<?php

class Ak extends CI_Model
{
	function __construct()
	{
		
        $this->load->database('newwap');
	}
	
	function read_all_ak()
	{
		$sql		= "SELECT * FROM ak_schedule WHERE status = '1' AND name != 'ALL' AND name != 'OFF'";
		$query	= $this->db->query($sql);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function read_all()
	{
		$sql		= "SELECT * FROM ak_schedule WHERE status = '1'";
		$query	= $this->db->query($sql);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function read_ak_by_id($id)
	{
		$sql		= "SELECT * FROM ak_schedule 
								WHERE status = '1' AND name != 'ALL' AND name != 'OFF' AND id = ? LIMIT 1";
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
	
	function read_ak_by_name($name)
	{
		$sql		= "SELECT * FROM ak_schedule WHERE status = '1' AND name = ? LIMIT 1";
		$query	= $this->db->query($sql, $name);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function read_ak_by_name_id($id, $name)
	{
		$sql		= "SELECT * FROM ak_schedule WHERE status = '1' AND name = ? AND id != ? LIMIT 1";
		$query	= $this->db->query($sql, array($name, $id));

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}
	
	function create_ak($name, $description)
	{
		$sql		= "INSERT INTO ak_schedule(name, description, status) VALUES(?, ?, '1')";
		$query	= $this->db->query($sql, array($name, $description));
		
		return $query;		
	}
	
	function update_ak($id, $name, $description)
	{
		$sql		= "UPDATE ak_schedule SET name = ?, description = ? WHERE id = ? AND status = '1' LIMIT 1";
		$query	= $this->db->query($sql, array($name, $description, $id));
		
		return $query;
	}
	
	function unactivate_ak($id)
	{
		$sql		= "UPDATE ak_schedule SET status = '0' WHERE id = ? AND name != 'ALL' AND name != 'OFF' LIMIT 1";
		$query	= $this->db->query($sql, $id);
		
		return $query;
	}
}

?>
