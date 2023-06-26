<?php
class Wapreg extends CI_Model{

	public  $db_newwap,
	$db_xmp,
	$db_table   = "dev_wapreg",
	$db_table2  = "dev_wapreg_component",
	//$db_table3  = "adn";
	$db_table4  = "ak_schedule";

	function __construct() {
		parent::__construct();

		//   $this->load->database();
	}


	function wapreg()
	{
		parent::Model();
		$this->load->database();
	}

	function read_all_wapreg($page, $row)
	{
		$sql		= "SELECT * FROM dev_wapreg WHERE status = '1' LIMIT ?,?";
		$query	= $this->db->query($sql,array($page, $row));

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function count_all_wapreg()
	{
		$sql		= "SELECT * FROM dev_wapreg WHERE status = '1'";
		$query	= $this->db->query($sql);

		return $query->num_rows();
	}

	function read_wapreg_by_id($id)
	{

		$this->db_newwap = $this->load->database('newwap', TRUE);
		
		$sql		= "SELECT * FROM dev_wapreg WHERE id = '".$id."' AND status = '1' LIMIT 1";	
	$query 		= $this->db_newwap->query($sql, $id );
			
		$total     = $query->num_rows();
		//$totalData = $queryData->num_rows();
			//return $query->result_array();
		
		/*
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

				$sql		= "SELECT * FROM dev_wapreg WHERE id = ? AND status = '1' LIMIT 1";
		$query	= $this->db->query($sql, $id);
		*/

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function read_wapreg_by_name($name)
	{
		$sql		= "SELECT * FROM dev_wapreg WHERE wap_name = ? AND status = '1' LIMIT 1";
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

	function read_wapreg_by_id_name($id, $name)
	{
		$sql		= "SELECT * FROM dev_wapreg WHERE id != ? AND wap_name = ? AND status = '1' LIMIT 1";
		$query	= $this->db->query($sql,array($id, $name));

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function create_wapreg($wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage,
	$confirmation_page, $confirmation_text, $unavailable_text, $success_text,
	$service_promo, $service_promo_text, $xts_token,$unreg_button,$unreg_confirm_text,$unreg_thankyou_text)
	{
		$sql		= "INSERT INTO dev_wapreg(
				wap_name, service, wap_title, autoreg, ak_id, homepage, confirmation_page, 
				confirmation_text, unavailable_text, success_text, service_promo, 
				service_promo_text, xts_token, created, last_modified, status,unreg_button,unreg_confirm_text,unreg_thankyou_text)
				VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), '1', ?, ?, ?)";

		$query	= $this->db->query($sql,array($wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage,
		$confirmation_page, $confirmation_text, $unavailable_text, $success_text, $service_promo,
		$service_promo_text, $xts_token,$unreg_button,$unreg_confirm_text,$unreg_thankyou_text));
		return $query;
	}

	function update_wapreg($id, $wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage, $confirmation_page,
	$confirmation_text, $unavailable_text, $success_text, $service_promo,
	$service_promo_text, $xts_token,$unreg_button,$unreg_confirm_text,$unreg_thankyou_text)
	{
		$sql		= "UPDATE dev_wapreg SET wap_name = ?, service = ?, wap_title = ?, autoreg = ?,
								ak_id = ?, homepage = ?, 
								confirmation_page = ?, confirmation_text = ?, unavailable_text = ?, success_text = ?,
								service_promo = ?, service_promo_text = ?, xts_token = ?, last_modified = NOW(), unreg_button = ?, 
								unreg_confirm_text= ?, unreg_thankyou_text= ? 
								WHERE id = ? LIMIT 1";
		//echo $sql;exit;
		$query	= $this->db->query($sql,
		array(
		$wap_name, $service, $wap_title, $autoreg, $ak_id, $homepage, $confirmation_page,
		$confirmation_text, $unavailable_text, $success_text, $service_promo,
		$service_promo_text, $xts_token, $unreg_button,$unreg_confirm_text,$unreg_thankyou_text,$id
		)
		);
		return $query;
	}

	function unactivate_wapreg($id)
	{
		$sql		= "UPDATE dev_wapreg SET status = '0' WHERE id = ? LIMIT 1";
		$query	= $this->db->query($sql, $id);

		return $query;
	}

	/* --------------------- wapreg_params ---------------------- */

	function read_all_wapreg_params($wapreg_id)
	{
		$sql		= "SELECT * FROM wapreg_params WHERE wapreg_id = ? AND status = '1'";
		$query	= $this->db->query($sql, $wapreg_id);

		if($query->num_rows() != 0)
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function read_wapreg_params_by_id($id)
	{
		$sql		= "SELECT * FROM wapreg_params WHERE id = ? AND status = '1' LIMIT 1";
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

	function create_wapreg_params($wapreg_id, $key_name, $key_value, $key_description)
	{
		$sql		= "INSERT INTO wapreg_params(wapreg_id, key_name, key_value, key_description, status)
								VALUES(?, ?, ?, ?, '1')";

		$query	= $this->db->query($sql, array($wapreg_id, $key_name, $key_value, $key_description));

		return $query;
	}

	function update_wapreg_params($id, $wapreg_id, $key_name, $key_value, $key_description)
	{
		$sql		= "UPDATE wapreg_params SET key_name = ?, key_value = ?, key_description = ? WHERE id = ? AND wapreg_id = ? LIMIT 1";

		$query	= $this->db->query($sql, array($key_name, $key_value, $key_description, $id, $wapreg_id));

		return $query;
	}

	function unactivate_wapreg_params_by_id($id)
	{
		$sql		= "UPDATE wapreg_params SET status = '0' WHERE id = ? LIMIT 1";
		$query	= $this->db->query($sql, $id);

		return $query;
	}

	function unactivate_wapreg_params_by_wapregid($wapreg_id)
	{
		$sql		= "UPDATE wapreg_params SET status = '0' WHERE wapreg_id = ?";
		$query	= $this->db->query($sql, $wapreg_id);

		return $query;
	}
}

?>
