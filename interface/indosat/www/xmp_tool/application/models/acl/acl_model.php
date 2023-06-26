<?php

class Acl_model extends CI_Model {

    public $group = "",
    $db_waptool,
    $db_table = "methods",
    $db_table2 = "menu",
    $db_table3 = "`group`";

    public function __construct() {
        parent::__construct();
    }

    public function getAccessList($controller_link, $method, $group) {
        $this->db = $this->load->database('default', TRUE);

        $sql = "SELECT id,status FROM $this->db_table WHERE controller_link=? AND method=? AND u_group=? ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, array($controller_link, $method, $group))) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getControllerId($controller_link) {
        $this->db = $this->load->database('default', TRUE);

        $sql = "SELECT id FROM $this->db_table2 WHERE link=? limit 0,1";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, $controller_link)) {
            write_log("info", __METHOD__ . ", Query Success ");
            return $query->result_array();
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getClassMethod($group, $class, $method) {
        $this->db = $this->load->database('default', TRUE);

        $data = array(
            'u_group' => $group,
            'controller_link' => $class,
            'method' => $method,
            'status' => '1'
        );

        //check status on database
        $sql = "SELECT id FROM $this->db_table WHERE controller_link=? AND method=? AND u_group=? ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        if ($query = $this->db->query($sql, array($class, $method, $group))) {
            write_log("info", __METHOD__ . ", Query Success ");
            $result = $query->num_rows();
            if ($result) {
                return FALSE;
            } else {
                $this->db->insert($this->db_table, $data);
                return TRUE;
            }
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }
    }

    public function getControllerMenu() {
        $this->db = $this->load->database('default', TRUE);

        $this->db->select('link');
        write_log("info", __METHOD__ . ", Start Query ");
        if ($query = $this->db->get($this->db_table2)) {
            write_log("info", __METHOD__ . ", Query Success ");

            if ($query->num_rows() > 0)
                return $query->result();
        }
        else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return array();
        }
    }

    public function getMethodGroupList($offset, $limit, $search = "", $group) {
        $this->db = $this->load->database('default', TRUE);

        $pSearch = "%" . $search . "%";
        $sql_query = "SELECT a.id, a.u_group, a.controller_link, a.method, a.status, b.group_name as u_group, c.link as controller_link, c.sort ";
        $sql_query .= sprintf("FROM %s as a ", $this->db_table);
        $sql_query .= "LEFT JOIN " . $this->db_table3 . " as b ON a.u_group = b.id ";
        $sql_query .= "LEFT JOIN " . $this->db_table2 . " as c ON a.controller_link = c.id ";
        $sql_query .= sprintf("WHERE a.u_group = '%s' ", $group);
        $sql_query .= ( !empty($search)) ? sprintf("AND (UPPER(b.group_name) LIKE '%s' OR UPPER(c.link) LIKE '%s' OR UPPER(a.method) LIKE '%s' OR UPPER(a.status) LIKE '%s') ", $pSearch, $pSearch, $pSearch, $pSearch) : "";
        $sql_query .= "ORDER BY a.id, c.sort ASC ";
        $sql_limit = sprintf("LIMIT %d, %d ", $offset, $limit);
        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $total = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();
            $result = array(
                'query' => $sql_query . $sql_limit,
                'total' => $total,
                'result' => array(
                    'data' => $queryData->result_array(),
                    'total' => $totalData
                )
            );
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    public function getCheckBoxMethodMenuList() {
        $this->db = $this->load->database('default', TRUE);

        $sql_query = "SELECT a.id, a.controller_link, a.method ";
        $sql_query .= "FROM wp_methods a ";
        $sql_query .= "ORDER BY a.id ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    public function getControllerList() {
        $this->db = $this->load->database('default', TRUE);

        $sql_query = "SELECT a.id, a.sort, a.link ";
        $sql_query .= "FROM " . $this->db_table2 . " a ";
        $sql_query .= "WHERE parent != '0' AND status !='0' ";
        $sql_query .= "ORDER BY a.link ASC, a.id ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    public function getControllerName($idCtrlLink) {
        $this->db = $this->load->database('default', TRUE);

        $sql_query = "SELECT a.id, a.link ";
        $sql_query .= "FROM " . $this->db_table2 . " a ";
        $sql_query .= sprintf("WHERE a.id = %s ", $idCtrlLink);
        $sql_query .= "ORDER BY a.id ";

        write_log("info", __METHOD__ . ", Start Query: " . $sql_query);
        try {
            $query = $this->db->query($sql_query);
            $result = $query->result_array();
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array();
        }

        return $result;
    }

    public function activeMethodGroup($id) {
        $this->db = $this->load->database('default', TRUE);

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $this->db->where('id', $id);
            $this->db->set('status', '1');
            $this->db->update($this->db_table);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function inactiveMethodGroup($id) {
        $this->db = $this->load->database('default', TRUE);

        write_log("info", __METHOD__ . ", Start Query ");
        try {
            $this->db->where('id', $id);
            $this->db->set('status', '0');
            $this->db->update($this->db_table);

            $result = array('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__ . ", Query Success ");
        } catch (Exception $e) {
            write_log("info", __METHOD__ . ", Query Failed ");
            $result = array('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }
    
    public function getGroupid(){
		$this->db = $this->load->database('default',TRUE);
		
		$username=$this->session->userdata('userName');
		$sql="SELECT u_group FROM users WHERE username='$username' limit 0,1";
		
		write_log("info", __METHOD__ . ", Start Query ");
		if($query=$this->db->query($sql)){
			write_log("info", __METHOD__ . ", Query Success ");
			$result=$query->result_array();
			return $result['0']['u_group'];
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return null; 
		}
		
	}
	
	public function getMenuList(){
		$this->db = $this->load->database('default',TRUE);
		
		$sql="SELECT id FROM menu WHERE status='1' AND link !='#' ";
		
		write_log("info", __METHOD__ . ", Start Query ");
		
		if($query=$this->db->query($sql)){
			$result=$query->result_array();
			foreach($result as $data){
				$fData[]=$data['id'];	
			}
			write_log("info", __METHOD__ . ", Query Success ");
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
		}
		return $fData;
	
	}
	
	public function checkLink($link){
		$this->db = $this->load->database('default',TRUE);
		
		$sql="SELECT id FROM menu WHERE link ='$link'";
		
		write_log("info", __METHOD__ . ", Start Query ");
		
		if($query=$this->db->query($sql)){
			if($query->num_rows()>0){
				$result=$query->result_array();
				return $result[0]['id'];
			}
			else
				return null;
			write_log("info", __METHOD__ . ", Query Success ");
		}	
		else
			write_log("info", __METHOD__ . ", Query Failed ");
			
		return null;
	}
	
	public function getGroupList(){
		$this->db = $this->load->database('default',TRUE);
		
		$sql="SELECT id FROM `group` WHERE status='1' order by id";
		
		write_log("info", __METHOD__ . ", Start Query ");
		if($query=$this->db->query($sql)){
			write_log("info", __METHOD__ . ", Query Success ");
			return $query->result_array();
		}
		else{
			write_log("info", __METHOD__ . ", Query Failed ");
			return array(); 
		}
		
	}

}
