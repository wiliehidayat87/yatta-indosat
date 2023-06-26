<?php
class Group_model extends CI_Model {
    public $db,
           $db_table1 = "`group`";
    public function __construct() {
		parent::__construct();
	}

    public function getGroupList($offset, $limit, $search = "") {
		$this->db=$this->load->database('default',TRUE);   
		
        $pSearch    = "%" . $search . "%";
        $sql_query  = "SELECT a.id, a.group_name, a.group_desc, a.group_menu, a.status ";
        $sql_query .= sprintf("FROM %s a ", $this->db_table1);
        $sql_query .= (!empty ($search)) ? sprintf("WHERE (UPPER(a.group_name) LIKE '%s' OR UPPER(a.group_desc) LIKE '%s' OR UPPER(a.group_menu) LIKE '%s' OR UPPER(a.status) LIKE '%s') ", $pSearch, $pSearch, $pSearch, $pSearch) : "";
        $sql_query .= "ORDER BY a.id ";
        $sql_limit  = sprintf("LIMIT %d, %d ", $offset, $limit);
        
        write_log("info", __METHOD__.", Start Query: ".$sql_query);

        try {
            $query     = $this->db->query($sql_query);
            $total     = $query->num_rows();
            $queryData = $this->db->query($sql_query . $sql_limit);
            $totalData = $queryData->num_rows();

            $result = array (
                'query'  => $sql_query . $sql_limit,
                'total'  => $total,
                'result' => array (
                'data'   => $queryData->result_array(),
                'total'  => $totalData
                )
            );
            write_log("info", __METHOD__.", Query Success "); 
        }
        catch (Exception $e) {
			write_log("info", __METHOD__.", Query Failed ");
            $result = array ();
        }

        return $result;
    }

    public function addNewGroup($group_name, $group_desc, $group_menu) {
        $this->db=$this->load->database('default',TRUE);   
        $result = array ();
        
        write_log("info", __METHOD__.", Start Query ");
        try {
            $data = array (
                'group_name' => $group_name,
                'group_desc' => $group_desc,
                'group_menu' => $group_menu,
                'status'     => "1"
            );

            $this->db->insert($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__.", Query Success "); 
        }
        catch (Exception $e) {
			write_log("info", __METHOD__.", Query Failed ");
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
        }

        return $result;
    }

    public function editGroup($id) {
        $this->db=$this->load->database('default',TRUE);   
        $this->db->select('group_name, group_desc, group_menu');
        $this->db->where('id', $id);
		
        write_log("info", __METHOD__.", Start Query ");
        $query = $this->db->get($this->db_table1);
        if($query){
			write_log("info", __METHOD__.", Query Success ");
			return $query->result_array(); 
		}
		else{
			write_log("info", __METHOD__.", Query Failed ");
			return array();
		}
    }

    public function updateGroup($id, $group_name, $group_desc, $group_menu) {
        $this->db=$this->load->database('default',TRUE);   
        $result = array ();

		write_log("info", __METHOD__.", Start Query ");
        try {
            $data = array (
                'group_name' => $group_name,
                'group_desc' => $group_desc,
                'group_menu' => $group_menu
            );

            $this->db->where('id', $id);
            $this->db->update($this->db_table1, $data);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__.", Query Success ");

        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
            write_log("info", __METHOD__.", Query Failed ");
        }

        return $result;
    }

    public function deleteGroup($id) {
		$this->db=$this->load->database('default',TRUE);
		
		write_log("info", __METHOD__.", Start Query ");   
        try {
            $this->db->where('id', $id);
            $this->db->delete($this->db_table1);

            $result = array ('status' => TRUE, 'message' => '');
            write_log("info", __METHOD__.", Query Success ");
        }
        catch (Exception $e) {
            $result = array ('status' => FALSE, 'message' => $e->getMessage());
            write_log("info", __METHOD__.", Query Failed ");
        }

        return $result;
    }
   
    public function checkGroupList($group_name,$id) {
        $this->db = $this->load->database('default', TRUE);

        $sql = "SELECT * FROM " . $this->db_table1 . " WHERE group_name=? ";
        $sql   .=(!empty($id))?"AND id!='$id' ":""; 
		
        write_log("info", __METHOD__ . ", Start Query: " . $sql);
        $query = $this->db->query($sql, $group_name);
        if ($query) {
            write_log("info", __METHOD__ . ", Query Success ");
            if ($query->num_rows() != 0) {
                return TRUE;
            }
        } else {
            write_log("info", __METHOD__ . ", Query Failed ");
            return FALSE;
        }

        return FALSE;
    }
}
