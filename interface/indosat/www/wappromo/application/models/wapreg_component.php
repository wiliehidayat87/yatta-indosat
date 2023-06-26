<?php

class Wapreg_component extends CI_Model
{

	public  $db_newwap,
	$db_xmp,
	$db_table   = "dev_wapreg",
	$db_table2  = "dev_wapreg_component",
	//$db_table3  = "adn";
	$db_table4  = "ak_schedule";

	function __construct() {
		parent::__construct();

		$this->load->database();
	}

	function wapreg_component()
	{
		parent::Model();
		$this->load->database();
	}

	function read_all_component($wap_id)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "SELECT * FROM dev_wapreg_component WHERE wap_id = ? AND status = '1' ORDER BY `sort`";
		$query = $this->db_newwap->query($sql, $wap_id);
		$total     = $query->num_rows();

		if($query->num_rows() != 0)
		{
			$result = array (
                'sql'  => $sql,
                'total'  => $total,
                'result' => array (
                                    'data'   => $query->result_array()
			)
			);
			return $result;
		}
		else
		{
			return FALSE;
		}
	}

	function read_component_by_id($id)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql	 	= "SELECT * FROM dev_wapreg_component WHERE id = ? AND status = '1' LIMIT 1";
		$query = $this->db_newwap->query($sql, $id);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function read_component_by_wapid_name($wap_id, $name)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "SELECT * FROM dev_wapreg_component WHERE wap_id = '".$wap_id."' AND name = '".$name."' AND status = '1' LIMIT 1";
		$query = $this->db_newwap->query($sql);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function read_component_by_id_wapid_name($id, $wap_id, $name)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "SELECT * FROM dev_wapreg_component WHERE id != ? AND wap_id = ? AND name = ? AND status = '1' LIMIT 1";
		$query = $this->db_newwap->query($sql, array($id, $wap_id, $name));

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function read_component_max_sort($wap_id)
	{

		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "SELECT MAX(`sort`) as `max` FROM dev_wapreg_component WHERE wap_id = ? AND status = '1'";
		$query = $this->db_newwap->query($sql, $wap_id);

		$result = $query->result_array();

		return $result[0]['max'];
	}

	function read_component_min_sort($wap_id)
	{

		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "SELECT MIN(`sort`) as `min` FROM dev_wapreg_component WHERE wap_id = ? AND status = '1'";
		$query = $this->db_newwap->query($sql, $wap_id);

		$result = $query->result_array();

		return $result[0]['min'];
	}

	function create_component($wap_id, $name, $type, $value, $is_link, $image, $sort)
	{

		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "INSERT INTO dev_wapreg_component(wap_id, name, type, value, is_link, image, sort, created, modified, status)
								VALUES(?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), '1')";
		$query = $this->db_newwap->query($sql, array($wap_id, $name, $type, $value, $is_link, $image, $sort));

		return $query;
	}

	function update_component($id, $name, $type, $value, $is_link, $image)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);
		$sql		= "UPDATE dev_wapreg_component SET name = ?, type = ?, value = ?, is_link = ?,
								image = ?, modified = NOW() WHERE id = ? LIMIT 1";		
		$query = $this->db_newwap->query($sql, array($name, $type, $value, $is_link, $image, $id));

		return $query;
	}

	function update_component_without_image($id, $name, $type, $value, $is_link)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);
//var_dump($id, $name, $type, $value, $is_link);		
		$sql = "UPDATE dev_wapreg_component SET name = ?, type = ?, value = ?, is_link = ?,
								modified = NOW() WHERE id = ? LIMIT 1";
		$query	= $this->db->query($sql, array($name, $type, $value, $is_link, $id));
//var_dump('OYE',$query, $this->db);
		return $query;
	}

	function update_component_sort_order($wapsite_id, $old_sort, $new_sort)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "UPDATE  dev_wapreg_component SET `sort` = IF( `sort` = ?, ?, ?) WHERE `sort` IN ( ?, ?) AND wap_id=?";
		$query = $this->db_newwap->query($sql, array($old_sort, $new_sort, $old_sort, $old_sort, $new_sort, $wapsite_id));

		return $query;
	}

	function unactivate_component_by_wapid($wap_id)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "UPDATE dev_wapreg_component SET status = '0' WHERE id = ? LIMIT 1";
		$query = $this->db_newwap->query($sql, $wap_id);

		return $query;
	}

	function unactivate_component_by_id($id)
	{
		$this->db_newwap = $this->load->database('newwap', TRUE);

		$sql		= "UPDATE dev_wapreg_component SET status = '0' WHERE id = ? LIMIT 1";
		$query = $this->db_newwap->query($sql, $id);

		return $query;
	}
}

?>
